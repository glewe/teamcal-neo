<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\AbsenceDayModel;
use App\Models\AbsenceModel;
use App\Models\CalendarDayModel;
use App\Models\ConfigModel;
use App\Models\DaynoteModel;
use App\Models\HolidayModel;
use App\Models\UserGroupModel;
use App\Models\UserModel;
use App\Models\UserOptionModel;

/**
 * CalendarMonthBuilderService
 *
 * Encapsulates per-month data computation for the calendar view: holiday
 * lookups, day styles, header daynotes, business days, and user-row day
 * data. Extracted from CalendarViewController to support per-month lazy
 * loading (PERFORMANCE_2 Step 1).
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     6.0.0
 */
class CalendarMonthBuilderService
{
  private AbsenceModel    $absenceModel;
  private AbsenceDayModel $absenceDayModel;
  private CalendarDayModel $calendarDayModel;
  private ConfigModel     $configModel;
  private DaynoteModel    $daynoteModel;
  private HolidayModel    $holidayModel;
  private UserGroupModel  $userGroupModel;
  private UserModel       $userModel;
  private UserModel       $userLoggedIn;
  private UserOptionModel $userOptionModel;
  private AbsenceService  $absenceService;
  /** @var array<string, mixed> */
  private array $allConfig;
  /** @var array<string, mixed> */
  private array $CONF;
  /** @var array<string, string> */
  private array $LANG;

  /** @var array<int, array{color: string, bgcolor: string}>|null */
  private ?array $holidayColorsCache = null;
  /** @var array<int, array{color: string, bgcolor: string}> */
  private array $weekendColors = [];
  /** @var array<string, array<int, int>> */
  private array $regionHolidayMaps = [];
  /** @var array<string, string> */
  private array $tooltipCountCache = [];
  /** @var array<string, array{countsAsPresent: bool, isConfidential: bool, color: string, bgTrans: bool, bgColor: string, symbol: string, icon: string, name: string}> */
  private array $absenceCache = [];
  /** @var array<string, string> */
  private array $userRegionCache = [];
  private ?string $loggedInRole = null;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param AbsenceModel          $absenceModel
   * @param AbsenceDayModel       $absenceDayModel
   * @param CalendarDayModel      $calendarDayModel
   * @param ConfigModel           $configModel
   * @param DaynoteModel          $daynoteModel
   * @param HolidayModel          $holidayModel
   * @param UserGroupModel        $userGroupModel
   * @param UserModel             $userModel
   * @param UserModel             $userLoggedIn   Logged-in user model
   * @param UserOptionModel       $userOptionModel
   * @param AbsenceService        $absenceService
   * @param array<string, mixed>  $allConfig
   * @param array<string, mixed>  $CONF
   * @param array<string, string> $LANG
   */
  public function __construct(
    AbsenceModel    $absenceModel,
    AbsenceDayModel $absenceDayModel,
    CalendarDayModel $calendarDayModel,
    ConfigModel     $configModel,
    DaynoteModel    $daynoteModel,
    HolidayModel    $holidayModel,
    UserGroupModel  $userGroupModel,
    UserModel       $userModel,
    UserModel       $userLoggedIn,
    UserOptionModel $userOptionModel,
    AbsenceService  $absenceService,
    array           $allConfig,
    array           $CONF,
    array           $LANG
  ) {
    $this->absenceModel              = $absenceModel;
    $this->absenceDayModel             = $absenceDayModel;
    $this->calendarDayModel             = $calendarDayModel;
    $this->configModel              = $configModel;
    $this->daynoteModel              = $daynoteModel;
    $this->holidayModel              = $holidayModel;
    $this->userGroupModel             = $userGroupModel;
    $this->userModel              = $userModel;
    $this->userLoggedIn             = $userLoggedIn;
    $this->userOptionModel             = $userOptionModel;
    $this->absenceService = $absenceService;
    $this->allConfig      = $allConfig;
    $this->CONF           = $CONF;
    $this->LANG           = $LANG;
  }

  //---------------------------------------------------------------------------
  /**
   * Build the complete month meta entry for a single month (or split-month pair).
   *
   * Combines holiday-map lookup, dayStyles, headerDaynotes, and businessDays
   * into one call so the result is ready to push into $viewData['months'].
   *
   * @param string $year     Four-digit year (YYYY)
   * @param string $month    Two-digit month (MM)
   * @param string $regionId Region ID
   * @param string $viewmode 'fullmonth' or 'splitmonth'
   *
   * @return array<string, mixed>
   */
  public function buildMonthMeta(
    string $year,
    string $month,
    string $regionId,
    string $viewmode
  ): array {
    $this->ensureHolidayColors();

    if ($viewmode === 'splitmonth') {
      return $this->buildSplitMonthEntry($year, $month, $regionId);
    }
    return $this->buildFullMonthEntry($year, $month, $regionId);
  }

  //---------------------------------------------------------------------------
  /**
   * Build a single user's month row data (days array, edit link, summary counts).
   *
   * Updates $vmonth['dayAbsCount'] and $vmonth['dayPresCount'] in place so
   * the summary row totals accumulate correctly across all users.
   *
   * @param string                             $username
   * @param array<string, mixed>               $vmonth               Month meta entry (mutated for abs/pres counts)
   * @param array<string, mixed>               $viewData
   * @param string[]                           $trustedRoles
   * @param array<string, array<string, bool>> $countedUsersPerMonth Tracks counted users per month key
   *
   * @return array<string, mixed>
   */
  public function buildUserMonthRow(
    string $username,
    array  &$vmonth,
    array  $viewData,
    array  $trustedRoles,
    array  &$countedUsersPerMonth
  ): array {
    $monthKey = $vmonth['year'] . $vmonth['month'];

    if (empty($vmonth['dayAbsCount'])) {
      $isSplit                = isset($vmonth['isSplitMonth']) && $vmonth['isSplitMonth'];
      $vmonth['dayAbsCount']  = array_fill(1, $isSplit ? 46 : 31, 0);
      $vmonth['dayPresCount'] = array_fill(1, $isSplit ? 46 : 31, 0);
    }

    $currDate         = date('Y-m-d');
    $todayBorderStyle = $this->todayBorderStyle();

    $mRow = [
      'editLink' => null,
      'days'     => [],
      'nextDays' => []
    ];

    $editAllowed = false;
    if (isAllowed($this->CONF['controllers']['calendaredit']->permission)) {
      if ($this->userLoggedIn->username === $username) {
        if (isAllowed("calendareditown"))
          $editAllowed = true;
      }
      elseif ($this->userGroupModel->shareGroupMemberships($this->userLoggedIn->username, $username)) {
        if (isAllowed("calendareditgroup") || (isAllowed("calendareditgroupmanaged") && $this->userGroupModel->isGroupManagerOfUser($this->userLoggedIn->username, $username)))
          $editAllowed = true;
      }
      else {
        if (isAllowed("calendareditall"))
          $editAllowed = true;
      }
    }
    if ($editAllowed) {
      $mRow['editLink'] = 'index.php?action=calendaredit&month=' . $vmonth['year'] . $vmonth['month'] . '&region=' . $viewData['regionid'] . '&user=' . $username;
    }

    $monthMap = $this->absenceDayModel->getMonthMap($username, (string) $vmonth['year'], (string) $vmonth['month']);
    $daystart = $vmonth['dayStart'] ?? 1;
    $dayend   = $vmonth['dayEnd']   ?? $vmonth['dateInfo']['daysInMonth'];

    for ($i = $daystart; $i <= $dayend; $i++) {
      $dayData          = $this->prepareDayData($username, $i, (string) $vmonth['year'], $vmonth['month'], $vmonth['dayStyles'][$i] ?? '', $trustedRoles, $currDate, $viewData, (string) $viewData['regionid'], $monthMap[$i] ?? 0);
      $mRow['days'][$i] = $dayData;

      if (!isset($countedUsersPerMonth[$monthKey][$username])) {
        if ($dayData['isAbsent'] && !$dayData['countsAsPresent']) {
          $vmonth['dayAbsCount'][$i]++;
        }
        else {
          $vmonth['dayPresCount'][$i]++;
        }
      }
    }

    if (isset($vmonth['isSplitMonth']) && $vmonth['isSplitMonth']) {
      $nextMonthNum  = intval($vmonth['month']) + 1;
      $nextMonthYear = intval($vmonth['year']);
      if ($nextMonthNum > 12) {
        $nextMonthNum = 1;
        $nextMonthYear++;
      }

      $nextMonthMap = $this->absenceDayModel->getMonthMap($username, (string) $nextMonthYear, sprintf('%02d', $nextMonthNum));

      $nextMonthEditLink = null;
      if ($editAllowed) {
        $nextMonthEditLink = 'index.php?action=calendaredit&month=' . $nextMonthYear . sprintf('%02d', $nextMonthNum) . '&region=' . $viewData['regionid'] . '&user=' . $username;
      }
      $mRow['nextMonthEditLink'] = $nextMonthEditLink;

      for ($i = 1; $i <= 15; $i++) {
        $dayData              = $this->prepareDayData($username, $i, (string) $nextMonthYear, sprintf('%02d', $nextMonthNum), $vmonth['dayStyles']['next_' . $i] ?? '', $trustedRoles, $currDate, $viewData, (string) $viewData['regionid'], $nextMonthMap[$i] ?? 0);
        $mRow['nextDays'][$i] = $dayData;

        if (!isset($countedUsersPerMonth[$monthKey][$username])) {
          if ($dayData['isAbsent'] && !$dayData['countsAsPresent']) {
            $vmonth['dayAbsCount'][$i + 15]++;
          }
          else {
            $vmonth['dayPresCount'][$i + 15]++;
          }
        }
      }
    }

    $countedUsersPerMonth[$monthKey][$username] = true;
    return $mRow;
  }

  //---------------------------------------------------------------------------
  /**
   * Build a full-month (non-split) meta entry.
   */
  /** @return array<string, mixed> */
  private function buildFullMonthEntry(string $year, string $month, string $regionId): array {
    $M          = CalendarDayModel::buildWeekdayGrid($year, $month);
    $holidayMap = $this->calendarDayModel->getMonthMap($year, $month, $regionId);

    $dateInfo       = dateInfo($year, $month);
    $dayStyles      = $this->computeFullMonthDayStyles($year, $month, $holidayMap, $M, $dateInfo);
    $headerDaynotes = $this->computeHeaderDaynotes($year, $month, $dateInfo['daysInMonth'], $regionId);
    $businessDays   = $this->absenceService->countBusinessDays($year . $month . '01', $year . $month . $dateInfo['daysInMonth'], $regionId);

    return [
      'year'           => $year,
      'month'          => $month,
      'dateInfo'       => $dateInfo,
      'M'              => $M,
      'dayStyles'      => $dayStyles,
      'headerDaynotes' => $headerDaynotes,
      'businessDays'   => $businessDays,
      'dayAbsCount'    => [],
      'dayPresCount'   => [],
    ];
  }

  //---------------------------------------------------------------------------
  /**
   * Build a split-month meta entry (last ~15 days of $month + first 15 days
   * of the following month).
   */
  /** @return array<string, mixed> */
  private function buildSplitMonthEntry(string $year, string $month, string $regionId): array {
    $currYear  = intval($year);
    $currMonth = intval($month);
    $nextMonth = $currMonth + 1;
    $nextYear  = $currYear;
    if ($nextMonth > 12) {
      $nextMonth = 1;
      $nextYear++;
    }
    $currFmt = sprintf('%02d', $currMonth);
    $nextFmt = sprintf('%02d', $nextMonth);

    $currMonthInfo = dateInfo((string) $currYear, $currFmt);
    $nextMonthInfo = dateInfo((string) $nextYear, $nextFmt);

    $M         = CalendarDayModel::buildWeekdayGrid((string) $currYear, $currFmt);
    $holidayMap = $this->calendarDayModel->getMonthMap((string) $currYear, $currFmt, $regionId);

    $nextM         = CalendarDayModel::buildWeekdayGrid((string) $nextYear, $nextFmt);
    $nextHolidayMap = $this->calendarDayModel->getMonthMap((string) $nextYear, $nextFmt, $regionId);

    $dayStyles      = $this->computeSplitMonthDayStyles($currYear, $currMonth, $holidayMap, $M, $currMonthInfo, $nextYear, $nextMonth, $nextHolidayMap, $nextM);
    $headerDaynotes = $this->computeSplitHeaderDaynotes((string) $currYear, $currFmt, $currMonthInfo['daysInMonth'], $nextYear, $nextMonth, $regionId);

    $businessDays          = $this->absenceService->countBusinessDays((string) $currYear . $currFmt . '01', (string) $currYear . $currFmt . $currMonthInfo['daysInMonth'], $regionId);
    $nextMonthBusinessDays = $this->absenceService->countBusinessDays((string) $nextYear . $nextFmt . '01', (string) $nextYear . $nextFmt . $nextMonthInfo['daysInMonth'], $regionId);

    return [
      'year'                  => $year,
      'month'                 => $currFmt,
      'dateInfo'              => $currMonthInfo,
      'dayStart'              => $currMonthInfo['daysInMonth'] - 14,
      'dayEnd'                => $currMonthInfo['daysInMonth'],
      'nextMonthInfo'         => $nextMonthInfo,
      'nextMonthDays'         => 15,
      'isSplitMonth'          => true,
      'M'                     => $M,
      'nextM'                 => $nextM,
      'dayStyles'             => $dayStyles,
      'headerDaynotes'        => $headerDaynotes,
      'businessDays'          => $businessDays,
      'nextMonthBusinessDays' => $nextMonthBusinessDays,
      'dayAbsCount'           => [],
      'dayPresCount'          => [],
    ];
  }

  //---------------------------------------------------------------------------
  /**
   * Ensure $holidayColorsCache and $weekendColors are populated (one DB query).
   */
  private function ensureHolidayColors(): void {
    if ($this->holidayColorsCache !== null) {
      return;
    }
    $this->holidayColorsCache = [];
    foreach ($this->holidayModel->getAll() as $holiday) {
      $this->holidayColorsCache[(int) $holiday['id']] = ['color' => $holiday['color'], 'bgcolor' => $holiday['bgcolor']];
    }
    $this->weekendColors[6] = $this->holidayColorsCache[2] ?? ['color' => '000000', 'bgcolor' => 'ffffff'];
    $this->weekendColors[7] = $this->holidayColorsCache[3] ?? ['color' => '000000', 'bgcolor' => 'ffffff'];
  }

  //---------------------------------------------------------------------------
  /**
   * Compute dayStyles for a full (non-split) month.
   *
   * @param string               $year
   * @param string               $month
   * @param array<int, int>      $holidayMap [day => holidayId]
   * @param array<string, int>   $weekdayGrid
   * @param array<string, mixed> $dateInfo
   *
   * @return array<int, string>
   */
  private function computeFullMonthDayStyles(string $year, string $month, array $holidayMap, array $weekdayGrid, array $dateInfo): array {
    $dayStyles        = [];
    $monthNum         = intval($month);
    $yearNum          = intval($year);
    $currDate         = date('Y-m-d');
    $todayBorderStyle = $this->todayBorderStyle();

    for ($i = 1; $i <= $dateInfo['daysInMonth']; $i++) {
      $style = $this->computeDayStyle($holidayMap[$i] ?? 1, $weekdayGrid['wday' . $i], $monthNum, $yearNum, $i, $currDate, $todayBorderStyle);
      if ($style !== '') {
        $dayStyles[$i] = $style;
      }
    }
    return $dayStyles;
  }

  //---------------------------------------------------------------------------
  /**
   * Compute dayStyles for a split-month entry (main days + 'next_X' keys for next 15 days).
   *
   * @param int                  $currYear
   * @param int                  $currMonth
   * @param array<int, int>      $holidayMap    Current month's holiday map
   * @param array<string, int>   $weekdayGrid   Current month's weekday grid
   * @param array<string, mixed> $currMonthInfo
   * @param int                  $nextYear
   * @param int                  $nextMonth
   * @param array<int, int>      $nextHolidayMap  Next month's holiday map
   * @param array<string, int>   $nextWeekdayGrid Next month's weekday grid
   *
   * @return array<int|string, string>
   */
  private function computeSplitMonthDayStyles(int $currYear, int $currMonth, array $holidayMap, array $weekdayGrid, array $currMonthInfo, int $nextYear, int $nextMonth, array $nextHolidayMap, array $nextWeekdayGrid): array {
    $dayStyles        = [];
    $currDate         = date('Y-m-d');
    $todayBorderStyle = $this->todayBorderStyle();

    // Main month days
    for ($i = 1; $i <= $currMonthInfo['daysInMonth']; $i++) {
      $style = $this->computeDayStyle($holidayMap[$i] ?? 1, $weekdayGrid['wday' . $i], $currMonth, $currYear, $i, $currDate, $todayBorderStyle);
      if ($style !== '') {
        $dayStyles[$i] = $style;
      }
    }

    // Next month first 15 days
    $nextMonthNum = $nextMonth > 12 ? 1 : $nextMonth;
    $nextYearNum  = $nextMonth > 12 ? $currYear + 1 : $nextYear;

    for ($i = 1; $i <= 15; $i++) {
      $style = $this->computeDayStyle($nextHolidayMap[$i] ?? 1, $nextWeekdayGrid['wday' . $i], $nextMonthNum, $nextYearNum, $i, $currDate, $todayBorderStyle);
      if ($style !== '') {
        $dayStyles['next_' . $i] = $style;
      }
    }
    return $dayStyles;
  }

  //---------------------------------------------------------------------------
  /**
   * Compute the CSS style string for a single day cell.
   *
   * @param int    $holidayId Holiday ID for this day (1 = Business Day / no override)
   * @param int    $weekday   ISO weekday (1=Mon..7=Sun)
   * @param int    $monthNum
   * @param int    $yearNum
   * @param int    $day
   * @param string $currDate         Y-m-d
   * @param string $todayBorderStyle CSS border declaration
   *
   * @return string
   */
  private function computeDayStyle(int $holidayId, int $weekday, int $monthNum, int $yearNum, int $day, string $currDate, string $todayBorderStyle): string {
    $color   = '';
    $bgcolor = '';
    $border  = '';

    if ($holidayId !== 1 && isset($this->holidayColorsCache[$holidayId])) {
      if ($this->holidayModel->keepWeekendColor((string) $holidayId) && ($weekday == 6 || $weekday == 7)) {
        $wc      = $this->weekendColors[$weekday];
        $color   = 'color:#' . $wc['color'] . ';';
        $bgcolor = 'background-color:#' . $wc['bgcolor'] . ';';
      }
      else {
        $color   = 'color:#' . $this->holidayColorsCache[$holidayId]['color'] . ';';
        $bgcolor = 'background-color:#' . $this->holidayColorsCache[$holidayId]['bgcolor'] . ';';
      }
    }
    elseif ($weekday == 6 || $weekday == 7) {
      $wc      = $this->weekendColors[$weekday];
      $color   = 'color:#' . $wc['color'] . ';';
      $bgcolor = 'background-color:#' . $wc['bgcolor'] . ';';
    }

    if (date('Y-m-d', mktime(0, 0, 0, $monthNum, $day, $yearNum)) == $currDate) {
      $border = $todayBorderStyle;
    }

    return $color . $bgcolor . $border;
  }

  //---------------------------------------------------------------------------
  /**
   * Compute global (header) daynotes for a single month.
   *
   * @param string $year
   * @param string $month
   * @param int    $daysInMonth
   * @param string $regionId
   *
   * @return array<int, array{color: string, colorHex: string, note: string}>
   */
  private function computeHeaderDaynotes(string $year, string $month, int $daysInMonth, string $regionId): array {
    $headerDaynotes = [];
    $dnColors       = [
      'info'    => '#0dcaf0',
      'success' => '#198754',
      'warning' => '#ffc107',
      'danger'  => '#dc3545',
    ];

    for ($i = 1; $i <= $daysInMonth; $i++) {
      if ($this->daynoteModel->get($year . $month . sprintf("%02d", $i), 'all', $regionId, true)) {
        $c                  = $this->daynoteModel->color;
        $hex                = $dnColors[$c] ?? ((strpos($c, '#') === 0) ? $c : '#' . $c);
        $headerDaynotes[$i] = [
          'color'    => $c,
          'colorHex' => $hex,
          'note'     => $this->daynoteModel->daynote
        ];
      }
    }
    return $headerDaynotes;
  }

  //---------------------------------------------------------------------------
  /**
   * Compute global (header) daynotes for a split-month entry.
   *
   * @param string $year
   * @param string $month
   * @param int    $daysInCurrMonth
   * @param int    $nextYear
   * @param int    $nextMonth
   * @param string $regionId
   *
   * @return array<int|string, array{color: string, colorHex: string, note: string}>
   */
  private function computeSplitHeaderDaynotes(string $year, string $month, int $daysInCurrMonth, int $nextYear, int $nextMonth, string $regionId): array {
    $headerDaynotes = $this->computeHeaderDaynotes($year, $month, $daysInCurrMonth, $regionId);

    $nextMonthNum = $nextMonth > 12 ? 1 : $nextMonth;
    $nextYearNum  = $nextMonth > 12 ? intval($year) + 1 : $nextYear;
    $dnColors     = [
      'info'    => '#0dcaf0',
      'success' => '#198754',
      'warning' => '#ffc107',
      'danger'  => '#dc3545',
    ];

    for ($i = 1; $i <= 15; $i++) {
      if ($this->daynoteModel->get((string) $nextYearNum . sprintf("%02d", $nextMonthNum) . sprintf("%02d", $i), 'all', $regionId, true)) {
        $c                            = $this->daynoteModel->color;
        $hex                          = $dnColors[$c] ?? ((strpos($c, '#') === 0) ? $c : '#' . $c);
        $headerDaynotes['next_' . $i] = [
          'color'    => $c,
          'colorHex' => $hex,
          'note'     => $this->daynoteModel->daynote
        ];
      }
    }
    return $headerDaynotes;
  }

  //---------------------------------------------------------------------------
  /**
   * Prepare data for a single day in a user row.
   *
   * @param string               $username
   * @param int                  $day
   * @param string               $year
   * @param string               $month
   * @param string               $gridStyle
   * @param string[]             $trustedRoles
   * @param string               $currDate
   * @param array<string, mixed> $viewData
   * @param string               $regionid
   * @param int                  $absId    Absence ID assigned to this user/day, 0 = none
   *
   * @return array<string, mixed>
   */
  private function prepareDayData(
    string $username,
    int    $day,
    string $year,
    string $month,
    string $gridStyle,
    array  $trustedRoles,
    string $currDate,
    array  $viewData,
    string $regionid,
    int    $absId
  ): array {
    $loopDate = date('Y-m-d', mktime(0, 0, 0, (int) $month, $day, (int) $year));

    $dayData = [
      'day'               => $day,
      'style'             => $gridStyle,
      'icon'              => null,
      'tooltip'           => null,
      'isAbsent'          => false,
      'countsAsPresent'   => false,
      'hasDaynote'        => false,
      'daynoteTooltip'    => null,
      'daynoteColor'      => null,
      'birthdayIndicator' => false,
      'regionalHoliday'   => false
    ];

    if ($absId) {
      $absAttrs                   = $this->getAbsenceAttrs((string) $absId);
      $dayData['isAbsent']        = true;
      $dayData['countsAsPresent'] = $absAttrs['countsAsPresent'];

      $allowed = true;
      if ($absAttrs['isConfidential']) {
        if (!in_array($this->getLoggedInRole(), $trustedRoles) && !$this->userLoggedIn->is_system && $this->userLoggedIn->username !== $username) {
          $allowed = false;
        }
      }

      if ($allowed) {
        if (!$viewData['absfilter'] || $absId == $viewData['absid']) {
          $color             = 'color: #' . $absAttrs['color'] . ';';
          $bgcolor           = $absAttrs['bgTrans'] ? '' : 'background-color: #' . $absAttrs['bgColor'] . ';';
          $dayData['style'] .= $color . $bgcolor;

          if ($this->configModel->read('symbolAsIcon')) {
            $dayData['icon'] = $absAttrs['symbol'];
          }
          else {
            $dayData['icon'] = '<span class="' . $absAttrs['icon'] . '"></span>';
          }

          $taken = '';
          if ($this->allConfig['showTooltipCount']) {
            $cacheKey = $username . '|' . $year . '|' . $month . '|' . $absId;
            if (!isset($this->tooltipCountCache[$cacheKey])) {
              $countFrom   = $year . $month . '01';
              $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int) $month, (int) $year);
              $countTo     = $year . $month . $daysInMonth;
              $takenMonth  = $this->absenceService->countAbsence($username, (string) $absId, $countFrom, $countTo, true, false);

              $countFromYear = $year . '0101';
              $countToYear   = $year . '1231';
              $takenYear     = $this->absenceService->countAbsence($username, (string) $absId, $countFromYear, $countToYear, true, false);

              $this->tooltipCountCache[$cacheKey] = ' (' . $takenMonth . '/' . $takenYear . ')';
            }
            $taken = $this->tooltipCountCache[$cacheKey];
          }
          $dayData['tooltip'] = $absAttrs['name'] . $taken;
        }
        else {
          $dayData['style']  .= 'color: #d5d5d5;background-color: #d5d5d5;';
          $dayData['tooltip'] = $this->LANG['cal_tt_anotherabsence'];
        }
      }
      else {
        $dayData['style']  .= 'color: #d5d5d5;background-color: #d5d5d5;';
        $dayData['tooltip'] = $this->LANG['cal_tt_absent'];
      }
    }
    else {
      if ($loopDate < $currDate && $viewData['pastDayColor']) {
        $dayData['style'] .= "background-color:#" . $viewData['pastDayColor'] . ";";
      }
    }

    // Daynote
    if ($this->daynoteModel->get($year . $month . sprintf("%02d", $day), $username, $regionid, true)) {
      $allowed = true;
      if ($this->daynoteModel->isConfidential((string) $this->daynoteModel->id)) {
        if (!in_array($this->getLoggedInRole(), $trustedRoles) && $this->userLoggedIn->username !== $this->daynoteModel->username && !$this->userLoggedIn->is_system) {
          $allowed = false;
        }
      }
      if ($allowed) {
        $dayData['hasDaynote']     = true;
        $dayData['daynoteTooltip'] = $this->daynoteModel->daynote;
        $dayData['daynoteColor']   = $this->daynoteModel->color;
      }
    }

    // Regional Holiday border
    if ($viewData['regionalHolidays']) {
      if (!isset($this->userRegionCache[$username])) {
        $this->userRegionCache[$username] = $this->userOptionModel->read($username, 'region') ?: '1';
      }
      $userRegion = $this->userRegionCache[$username];
      if ($userRegion != $regionid) {
        $rHolidayMap = $this->getRegionHolidayMap($year, $month, $userRegion);
        if (($rHolidayMap[$day] ?? 1) !== 1) {
          $dayData['style'] .= 'border: 2px solid #' . $viewData['regionalHolidaysColor'] . ' !important;';
        }
      }
    }

    return $dayData;
  }

  //---------------------------------------------------------------------------
  /**
   * Look up (and cache) the logged-in user's role. Called per day cell for
   * confidentiality checks, so it must not hit the database every time.
   */
  private function getLoggedInRole(): string {
    return $this->loggedInRole ??= $this->userModel->getRole($this->userLoggedIn->username);
  }

  //---------------------------------------------------------------------------
  /**
   * Look up (and cache) an absence type's display/behavior attributes.
   *
   * The absence catalog is small and static for the duration of a request,
   * but this is looked up once per user per day in prepareDayData(), so
   * without caching the same $absId gets re-queried repeatedly.
   *
   * @param string $absId
   *
   * @return array{countsAsPresent: bool, isConfidential: bool, color: string, bgTrans: bool, bgColor: string, symbol: string, icon: string, name: string}
   */
  private function getAbsenceAttrs(string $absId): array {
    if (!isset($this->absenceCache[$absId])) {
      $this->absenceCache[$absId] = [
        'countsAsPresent' => (bool) $this->absenceModel->getCountsAsPresent($absId),
        'isConfidential'  => (bool) $this->absenceModel->isConfidential($absId),
        'color'           => $this->absenceModel->getColor($absId),
        'bgTrans'         => (bool) $this->absenceModel->getBgTrans($absId),
        'bgColor'         => $this->absenceModel->getBgColor($absId),
        'symbol'          => $this->absenceModel->getSymbol($absId),
        'icon'            => $this->absenceModel->getIcon($absId),
        'name'            => $this->absenceModel->getName($absId),
      ];
    }
    return $this->absenceCache[$absId];
  }

  //---------------------------------------------------------------------------
  /**
   * Load (and cache) a region's holiday map for a given month. Used for regional-holiday lookups.
   *
   * @param string     $year
   * @param string     $month
   * @param string|int $region
   *
   * @return array<int, int> [day => holidayId]
   */
  private function getRegionHolidayMap(string $year, string $month, string|int $region): array {
    $key = $year . $month . (string) $region;
    if (!isset($this->regionHolidayMaps[$key])) {
      $this->regionHolidayMaps[$key] = $this->calendarDayModel->getMonthMap($year, $month, (string) $region);
    }
    return $this->regionHolidayMaps[$key];
  }

  //---------------------------------------------------------------------------
  /**
   * Build the today-border CSS declaration from app config.
   */
  private function todayBorderStyle(): string {
    return 'border-left: ' . $this->allConfig['todayBorderSize'] . 'px solid #' . $this->allConfig['todayBorderColor'] . ';border-right: ' . $this->allConfig['todayBorderSize'] . 'px solid #' . $this->allConfig['todayBorderColor'] . ';';
  }
}
