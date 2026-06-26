<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\AbsenceModel;
use App\Models\ConfigModel;
use App\Models\DaynoteModel;
use App\Models\HolidayModel;
use App\Models\LogModel;
use App\Models\MonthModel;
use App\Models\TemplateModel;
use App\Models\UserGroupModel;
use App\Models\UserModel;
use App\Models\UserOptionModel;

/**
 * CalendarMonthBuilderService
 *
 * Encapsulates per-month data computation for the calendar view: MonthModel
 * lookup/creation, day styles, header daynotes, business days, and user-row
 * day data. Extracted from CalendarViewController to support per-month lazy
 * loading (PERFORMANCE_2 Step 1).
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     5.4.0
 */
class CalendarMonthBuilderService
{
  private AbsenceModel    $A;
  private ConfigModel     $C;
  private DaynoteModel    $D;
  private HolidayModel    $H;
  private LogModel        $LOG;
  private TemplateModel   $T;
  private UserGroupModel  $UG;
  private UserModel       $U;
  private UserModel       $UL;
  private UserOptionModel $UO;
  private AbsenceService  $AbsenceService;
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
  /** @var array<string, MonthModel> */
  private array $regionMonths = [];
  /** @var array<string, string> */
  private array $tooltipCountCache = [];

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param AbsenceModel          $A
   * @param ConfigModel           $C
   * @param DaynoteModel          $D
   * @param HolidayModel          $H
   * @param LogModel              $LOG
   * @param TemplateModel         $T
   * @param UserGroupModel        $UG
   * @param UserModel             $U
   * @param UserModel             $UL   Logged-in user model
   * @param UserOptionModel       $UO
   * @param AbsenceService        $AbsenceService
   * @param array<string, mixed>  $allConfig
   * @param array<string, mixed>  $CONF
   * @param array<string, string> $LANG
   */
  public function __construct(
    AbsenceModel    $A,
    ConfigModel     $C,
    DaynoteModel    $D,
    HolidayModel    $H,
    LogModel        $LOG,
    TemplateModel   $T,
    UserGroupModel  $UG,
    UserModel       $U,
    UserModel       $UL,
    UserOptionModel $UO,
    AbsenceService  $AbsenceService,
    array           $allConfig,
    array           $CONF,
    array           $LANG
  ) {
    $this->A              = $A;
    $this->C              = $C;
    $this->D              = $D;
    $this->H              = $H;
    $this->LOG            = $LOG;
    $this->T              = $T;
    $this->UG             = $UG;
    $this->U              = $U;
    $this->UL             = $UL;
    $this->UO             = $UO;
    $this->AbsenceService = $AbsenceService;
    $this->allConfig      = $allConfig;
    $this->CONF           = $CONF;
    $this->LANG           = $LANG;
  }

  //---------------------------------------------------------------------------
  /**
   * Build the complete month meta entry for a single month (or split-month pair).
   *
   * Combines MonthModel lookup/creation, dayStyles, headerDaynotes, and
   * businessDays into one call so the result is ready to push into
   * $viewData['months'].
   *
   * @param string $year       Four-digit year (YYYY)
   * @param string $month      Two-digit month (MM)
   * @param string $regionId   Region ID
   * @param string $regionName Region display name (used in email notifications)
   * @param string $viewmode   'fullmonth' or 'splitmonth'
   *
   * @return array<string, mixed>
   */
  public function buildMonthMeta(
    string $year,
    string $month,
    string $regionId,
    string $regionName,
    string $viewmode
  ): array {
    $this->ensureHolidayColors();

    if ($viewmode === 'splitmonth') {
      return $this->buildSplitMonthEntry($year, $month, $regionId, $regionName);
    }
    return $this->buildFullMonthEntry($year, $month, $regionId, $regionName);
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
      if ($this->UL->username === $username) {
        if (isAllowed("calendareditown"))
          $editAllowed = true;
      }
      elseif ($this->UG->shareGroupMemberships($this->UL->username, $username)) {
        if (isAllowed("calendareditgroup") || (isAllowed("calendareditgroupmanaged") && $this->UG->isGroupManagerOfUser($this->UL->username, $username)))
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

    $this->T->getTemplate($username, (string) $vmonth['year'], (string) $vmonth['month']);
    $daystart = $vmonth['dayStart'] ?? 1;
    $dayend   = $vmonth['dayEnd']   ?? $vmonth['dateInfo']['daysInMonth'];

    for ($i = $daystart; $i <= $dayend; $i++) {
      $dayData          = $this->prepareDayData($username, $i, (string) $vmonth['year'], $vmonth['month'], $vmonth['dayStyles'][$i] ?? '', $trustedRoles, $currDate, $viewData, (string) $viewData['regionid']);
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

      $nextMonthTemplate = new TemplateModel();
      $nextMonthTemplate->getTemplate($username, (string) $nextMonthYear, sprintf('%02d', $nextMonthNum));

      $nextMonthEditLink = null;
      if ($editAllowed) {
        $nextMonthEditLink = 'index.php?action=calendaredit&month=' . $nextMonthYear . sprintf('%02d', $nextMonthNum) . '&region=' . $viewData['regionid'] . '&user=' . $username;
      }
      $mRow['nextMonthEditLink'] = $nextMonthEditLink;

      for ($i = 1; $i <= 15; $i++) {
        $dayData              = $this->prepareDayData($username, $i, (string) $nextMonthYear, sprintf('%02d', $nextMonthNum), $vmonth['dayStyles']['next_' . $i] ?? '', $trustedRoles, $currDate, $viewData, (string) $viewData['regionid'], $nextMonthTemplate);
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
  private function buildFullMonthEntry(string $year, string $month, string $regionId, string $regionName): array {
    $M = new MonthModel();
    if (!$M->getMonth($year, $month, $regionId)) {
      createMonth($year, $month, 'region', $regionId);
      $M->getMonth($year, $month, $regionId);
      if ($this->allConfig['emailNotifications']) {
        sendMonthEventNotifications("created", $year, $month, $regionName);
      }
      $this->LOG->logEvent("logMonth", $this->loggedInUsername(), "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
    }

    $dateInfo       = dateInfo($year, $month);
    $dayStyles      = $this->computeFullMonthDayStyles($year, $month, $M, $dateInfo);
    $headerDaynotes = $this->computeHeaderDaynotes($year, $month, $dateInfo['daysInMonth'], $regionId);
    $businessDays   = $this->AbsenceService->countBusinessDays($year . $month . '01', $year . $month . $dateInfo['daysInMonth'], $regionId);

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
  private function buildSplitMonthEntry(string $year, string $month, string $regionId, string $regionName): array {
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

    $M = new MonthModel();
    if (!$M->getMonth((string) $currYear, $currFmt, $regionId)) {
      createMonth((string) $currYear, $currFmt, 'region', $regionId);
      $M->getMonth((string) $currYear, $currFmt, $regionId);
    }

    $nextM = new MonthModel();
    if (!$nextM->getMonth((string) $nextYear, $nextFmt, $regionId)) {
      createMonth((string) $nextYear, $nextFmt, 'region', $regionId);
      $nextM->getMonth((string) $nextYear, $nextFmt, $regionId);
      if ($this->allConfig['emailNotifications']) {
        sendMonthEventNotifications("created", (string) $nextYear, $nextFmt, $regionName);
      }
      $this->LOG->logEvent("logMonth", $this->loggedInUsername(), "log_month_tpl_created", $nextM->region . ": " . $nextM->year . "-" . $nextM->month);
    }

    $dayStyles      = $this->computeSplitMonthDayStyles($currYear, $currMonth, $M, $currMonthInfo, $nextYear, $nextMonth, $nextM);
    $headerDaynotes = $this->computeSplitHeaderDaynotes((string) $currYear, $currFmt, $currMonthInfo['daysInMonth'], $nextYear, $nextMonth, $regionId);

    $businessDays          = $this->AbsenceService->countBusinessDays((string) $currYear . $currFmt . '01', (string) $currYear . $currFmt . $currMonthInfo['daysInMonth'], $regionId);
    $nextMonthBusinessDays = $this->AbsenceService->countBusinessDays((string) $nextYear . $nextFmt . '01', (string) $nextYear . $nextFmt . $nextMonthInfo['daysInMonth'], $regionId);

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
    foreach ($this->H->getAll() as $holiday) {
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
   * @param MonthModel           $monthObj
   * @param array<string, mixed> $dateInfo
   *
   * @return array<int, string>
   */
  private function computeFullMonthDayStyles(string $year, string $month, MonthModel $monthObj, array $dateInfo): array {
    $dayStyles        = [];
    $monthNum         = intval($month);
    $yearNum          = intval($year);
    $currDate         = date('Y-m-d');
    $todayBorderStyle = $this->todayBorderStyle();

    for ($i = 1; $i <= $dateInfo['daysInMonth']; $i++) {
      $style = $this->computeDayStyle($monthObj, $monthNum, $yearNum, $i, $currDate, $todayBorderStyle);
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
   * @param MonthModel           $M         Current month model
   * @param array<string, mixed> $currMonthInfo
   * @param int                  $nextYear
   * @param int                  $nextMonth
   * @param MonthModel           $nextM     Next month model
   *
   * @return array<int|string, string>
   */
  private function computeSplitMonthDayStyles(int $currYear, int $currMonth, MonthModel $M, array $currMonthInfo, int $nextYear, int $nextMonth, MonthModel $nextM): array {
    $dayStyles        = [];
    $currDate         = date('Y-m-d');
    $todayBorderStyle = $this->todayBorderStyle();

    // Main month days
    for ($i = 1; $i <= $currMonthInfo['daysInMonth']; $i++) {
      $style = $this->computeDayStyle($M, $currMonth, $currYear, $i, $currDate, $todayBorderStyle);
      if ($style !== '') {
        $dayStyles[$i] = $style;
      }
    }

    // Next month first 15 days
    $nextMonthNum = $nextMonth > 12 ? 1 : $nextMonth;
    $nextYearNum  = $nextMonth > 12 ? $currYear + 1 : $nextYear;

    for ($i = 1; $i <= 15; $i++) {
      $style = $this->computeDayStyle($nextM, $nextMonthNum, $nextYearNum, $i, $currDate, $todayBorderStyle);
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
   * @param MonthModel $monthObj
   * @param int        $monthNum
   * @param int        $yearNum
   * @param int        $day
   * @param string     $currDate         Y-m-d
   * @param string     $todayBorderStyle CSS border declaration
   *
   * @return string
   */
  private function computeDayStyle(MonthModel $monthObj, int $monthNum, int $yearNum, int $day, string $currDate, string $todayBorderStyle): string {
    $hprop     = 'hol' . $day;
    $wprop     = 'wday' . $day;
    $holidayId = (int) $monthObj->$hprop;
    $weekday   = (int) $monthObj->$wprop;
    $color     = '';
    $bgcolor   = '';
    $border    = '';

    if ($holidayId && isset($this->holidayColorsCache[$holidayId])) {
      if ($this->H->keepWeekendColor((string) $holidayId) && ($weekday == 6 || $weekday == 7)) {
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
      if ($this->D->get($year . $month . sprintf("%02d", $i), 'all', $regionId, true)) {
        $c                  = $this->D->color;
        $hex                = $dnColors[$c] ?? ((strpos($c, '#') === 0) ? $c : '#' . $c);
        $headerDaynotes[$i] = [
          'color'    => $c,
          'colorHex' => $hex,
          'note'     => $this->D->daynote
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
      if ($this->D->get((string) $nextYearNum . sprintf("%02d", $nextMonthNum) . sprintf("%02d", $i), 'all', $regionId, true)) {
        $c                            = $this->D->color;
        $hex                          = $dnColors[$c] ?? ((strpos($c, '#') === 0) ? $c : '#' . $c);
        $headerDaynotes['next_' . $i] = [
          'color'    => $c,
          'colorHex' => $hex,
          'note'     => $this->D->daynote
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
   * @param TemplateModel|null   $templateOverride
   *
   * @return array<string, mixed>
   */
  private function prepareDayData(
    string        $username,
    int           $day,
    string        $year,
    string        $month,
    string        $gridStyle,
    array         $trustedRoles,
    string        $currDate,
    array         $viewData,
    string        $regionid,
    ?TemplateModel $templateOverride = null
  ): array {
    $T        = $templateOverride ?: $this->T;
    $absCol   = 'abs' . $day;
    $absId    = $T->$absCol;
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
      $dayData['isAbsent']        = true;
      $dayData['countsAsPresent'] = (bool) $this->A->getCountsAsPresent((string) $absId);

      $allowed = true;
      if ($this->A->isConfidential((string) $absId)) {
        $userRole = $this->U->getRole($this->UL->username);
        if (!in_array($userRole, $trustedRoles) && $this->UL->username !== 'admin' && $this->UL->username !== $username) {
          $allowed = false;
        }
      }

      if ($allowed) {
        if (!$viewData['absfilter'] || $absId == $viewData['absid']) {
          $color             = 'color: #' . $this->A->getColor((string) $absId) . ';';
          $bgcolor           = $this->A->getBgTrans((string) $absId) ? '' : 'background-color: #' . $this->A->getBgColor((string) $absId) . ';';
          $dayData['style'] .= $color . $bgcolor;

          if ($this->C->read('symbolAsIcon')) {
            $dayData['icon'] = $this->A->getSymbol((string) $absId);
          }
          else {
            $dayData['icon'] = '<span class="' . $this->A->getIcon((string) $absId) . '"></span>';
          }

          $taken = '';
          if ($this->allConfig['showTooltipCount']) {
            $cacheKey = $username . '|' . $year . '|' . $month . '|' . $absId;
            if (!isset($this->tooltipCountCache[$cacheKey])) {
              $countFrom   = $year . $month . '01';
              $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int) $month, (int) $year);
              $countTo     = $year . $month . $daysInMonth;
              $takenMonth  = $this->AbsenceService->countAbsence($username, (string) $absId, $countFrom, $countTo, true, false);

              $countFromYear = $year . '0101';
              $countToYear   = $year . '1231';
              $takenYear     = $this->AbsenceService->countAbsence($username, (string) $absId, $countFromYear, $countToYear, true, false);

              $this->tooltipCountCache[$cacheKey] = ' (' . $takenMonth . '/' . $takenYear . ')';
            }
            $taken = $this->tooltipCountCache[$cacheKey];
          }
          $dayData['tooltip'] = $this->A->getName((string) $absId) . $taken;
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
    if ($this->D->get($year . $month . sprintf("%02d", $day), $username, $regionid, true)) {
      $allowed = true;
      if ($this->D->isConfidential((string) $this->D->id)) {
        $userRole = $this->U->getRole($this->UL->username);
        if (!in_array($userRole, $trustedRoles) && $this->UL->username !== $this->D->username && $this->UL->username !== 'admin') {
          $allowed = false;
        }
      }
      if ($allowed) {
        $dayData['hasDaynote']     = true;
        $dayData['daynoteTooltip'] = $this->D->daynote;
        $dayData['daynoteColor']   = $this->D->color;
      }
    }

    // Regional Holiday border
    if ($viewData['regionalHolidays']) {
      $userRegion = $this->UO->read($username, 'region') ?: '1';
      if ($userRegion != $regionid) {
        $rM   = $this->getRegionMonth($year, $month, $userRegion);
        $prop = 'hol' . $day;
        if ($rM->$prop) {
          $dayData['style'] .= 'border: 2px solid #' . $viewData['regionalHolidaysColor'] . ' !important;';
        }
      }
    }

    return $dayData;
  }

  //---------------------------------------------------------------------------
  /**
   * Load (and cache) a MonthModel for a given region. Used for regional-holiday lookups.
   *
   * @param string     $year
   * @param string     $month
   * @param string|int $region
   *
   * @return MonthModel
   */
  private function getRegionMonth(string $year, string $month, string|int $region): MonthModel {
    $key = $year . $month . (string) $region;
    if (!isset($this->regionMonths[$key])) {
      $M = new MonthModel();
      $M->getMonth($year, $month, (string) $region);
      $this->regionMonths[$key] = $M;
    }
    return $this->regionMonths[$key];
  }

  //---------------------------------------------------------------------------
  /**
   * Build the today-border CSS declaration from app config.
   */
  private function todayBorderStyle(): string {
    return 'border-left: ' . $this->allConfig['todayBorderSize'] . 'px solid #' . $this->allConfig['todayBorderColor'] . ';border-right: ' . $this->allConfig['todayBorderSize'] . 'px solid #' . $this->allConfig['todayBorderColor'] . ';';
  }

  //---------------------------------------------------------------------------
  /**
   * Return the logged-in username, or empty string when not logged in.
   */
  private function loggedInUsername(): string {
    return $this->UL->username !== '' ? $this->UL->username : '';
  }
}
