<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\MonthModel;

/**
 * Calendar View Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class CalendarViewController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['calendarview']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $missingData  = false;
    $monthfilter  = '';
    $regionfilter = '';

    // Month Filter
    if (isset($_GET['month'])) {
      $monthfilter = sanitize($_GET['month']);
    }
    elseif ($this->isLoggedIn() && $monthfilter = $this->UO->read($this->UL->username, 'calfilterMonth')) {
      // Loaded from user option
    }
    else {
      $monthfilter = date('Y') . date('m');
    }

    $viewData          = [];
    $viewData['year']  = substr($monthfilter, 0, 4);
    $viewData['month'] = substr($monthfilter, 4, 2);
    if (!is_numeric($monthfilter) || strlen($monthfilter) != 6 || !checkdate(intval($viewData['month']), 1, intval($viewData['year']))) {
      $missingData = true;
    }
    else {
      if ($this->isLoggedIn())
        $this->UO->save($this->UL->username, 'calfilterMonth', $monthfilter);
    }

    // Region Filter
    if (isset($_GET['region'])) {
      $regionfilter = sanitize($_GET['region']);
      if ($this->isLoggedIn())
        $this->UO->save($this->UL->username, 'calfilterRegion', $regionfilter);
    }
    elseif ($this->isLoggedIn() && $regionfilter = $this->UO->read($this->UL->username, 'calfilterRegion')) {
      // Loaded from user option
    }
    else {
      $regionfilter = '1';
    }

    if (!$missingData) {
      if (!$this->R->getById($regionfilter)) {
        $missingData = true;
      }
      else {
        $viewData['regionid']   = $this->R->id;
        $viewData['regionname'] = $this->R->name;
        if ($this->isLoggedIn())
          $this->UO->save($this->UL->username, 'calfilterRegion', $regionfilter);
      }
    }

    if ($missingData) {
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
      return;
    }

    // Users and Filters
    $users       = $this->U->getAllButHidden();
    $groupOption = $this->isLoggedIn() ? $this->UO->read($this->UL->username, 'calfilterGroup') : false;
    $absOption   = $this->isLoggedIn() ? $this->UO->read($this->UL->username, 'calfilterAbs') : false;
    $groupfilter = $_GET['group'] ?? ($groupOption ?: ($this->allConfig['defgroupfilter'] ?: 'all'));
    $absfilter   = $_GET['abs'] ?? ($absOption ?: 'all');

    if ($this->isLoggedIn()) {
      if (isset($_GET['group']))
        $this->UO->save($this->UL->username, 'calfilterGroup', $groupfilter);
      if (isset($_GET['abs']))
        $this->UO->save($this->UL->username, 'calfilterAbs', $absfilter);
    }

    $viewData['groupid'] = $groupfilter;
    $viewData['absid']   = $absfilter;

    if ($groupfilter !== 'all' || $absfilter !== 'all') {
      $filteredUsers = [];
      foreach ($users as $usr) {
        $include = true;
        if ($groupfilter !== 'all' && $groupfilter !== 'allbygroup') {
          $include = $this->UG->isMemberOrGuestOfGroup($usr['username'], (string) $groupfilter);
          if (!$include)
            continue;
        }
        if ($absfilter !== 'all') {
          $include = $this->T->hasAbsence($usr['username'], date('Y'), date('m'), (int) $absfilter);
        }
        if ($include)
          $filteredUsers[] = $usr;
      }
      $users = $filteredUsers;
    }

    $viewData['group']     = ($groupfilter == "all") ? $this->LANG['all'] : $this->G->getNameById($groupfilter);
    $viewData['absfilter'] = ($absfilter !== "all");
    $viewData['absence']   = ($absfilter == "all") ? $this->LANG['all'] : $this->A->getName((string) $absfilter);

    $viewData['search'] = '';
    if ($this->isLoggedIn() && $searchfilter = $this->UO->read($this->UL->username, 'calfilterSearch')) {
      $viewData['search'] = $searchfilter;
      $users              = $this->U->getAllLike($searchfilter);
    }

    if (isset($_GET['search']) && $_GET['search'] == "reset") {
      if ($this->isLoggedIn())
        $this->UO->deleteUserOption($this->UL->username, 'calfilterSearch');
      header("Location: index.php?action=calendarview");
      die();
    }

    if ($this->allConfig['currentYearOnly'] && $viewData['year'] != date('Y')) {
      if ($this->allConfig['currYearRoles']) {
        $arrCurrYearRoles = explode(',', $this->allConfig['currYearRoles']);
        $userRole         = $this->U->getRole($this->isLoggedIn() ? $this->UL->username : "");
        if (in_array($userRole, $arrCurrYearRoles)) {
          header("Location: index.php?action=calendarview&month=" . date('Ym') . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter);
          die();
        }
      }
      else {
        header("Location: index.php?action=calendarview&month=" . date('Ym') . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter);
        die();
      }
    }

    // Paging
    if ($limit = $this->allConfig['usersPerPage']) {
      $total                  = count($users);
      $pages                  = ceil($total / $limit);
      $page                   = min($pages, (int) filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]));
      $offset                 = ($page - 1) * $limit;
      $users                  = array_slice($users, $offset, $limit);
      $viewData['totalUsers'] = $total;
      $viewData['page']       = $page;
    }

    if ($this->UO->read($this->UL->username, 'showMonths')) {
      $showMonths = intval($this->UO->read($this->UL->username, 'showMonths'));
    }
    elseif ($this->allConfig['showMonths']) {
      $showMonths = intval($this->allConfig['showMonths']);
    }
    else {
      $showMonths = 1;
      $this->C->save('showMonths', '1');
    }

    $viewmode = 'fullmonth';
    if ($this->UO->read($this->UL->username, 'calViewMode')) {
      $viewmode = $this->UO->read($this->UL->username, 'calViewMode');
    }
    if (isset($_GET['viewmode'])) {
      $viewmode = sanitize($_GET['viewmode']);
    }

    $viewData['months'] = [];

    // Handle POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }
      $_POST = sanitize($_POST);

      if (isset($_POST['btn_oneless'])) {
        $showMonths = intval($_POST['hidden_showmonths']);
        if ($showMonths > 1)
          $showMonths--;
      }
      if (isset($_POST['btn_onemore'])) {
        $showMonths = intval($_POST['hidden_showmonths']);
        if ($showMonths <= 12)
          $showMonths++;
      }

      if (isset($_POST['btn_month'])) {
        if ($this->isLoggedIn())
          $this->UO->save($this->UL->username, 'calfilterMonth', $_POST['txt_year'] . $_POST['sel_month']);
        header("Location: index.php?action=calendarview&month=" . $_POST['txt_year'] . $_POST['sel_month'] . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter . "&viewmode=" . $viewmode);
        die();
      }
      elseif (isset($_POST['btn_region'])) {
        if ($this->isLoggedIn())
          $this->UO->save($this->UL->username, 'calfilterRegion', $_POST['sel_region']);
        header("Location: index.php?action=calendarview&month=" . $monthfilter . "&region=" . $_POST['sel_region'] . "&group=" . $groupfilter . "&abs=" . $absfilter . "&viewmode=" . $viewmode);
        die();
      }
      elseif (isset($_POST['btn_group'])) {
        if ($this->isLoggedIn())
          $this->UO->save($this->UL->username, 'calfilterGroup', $_POST['sel_group']);
        header("Location: index.php?action=calendarview&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $_POST['sel_group'] . "&abs=" . $absfilter . "&viewmode=" . $viewmode);
        die();
      }
      elseif (isset($_POST['btn_abssearch'])) {
        if ($this->isLoggedIn())
          $this->UO->save($this->UL->username, 'calfilterAbs', $_POST['sel_absence']);
        header("Location: index.php?action=calendarview&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $_POST['sel_absence'] . "&viewmode=" . $viewmode);
        die();
      }
      elseif (isset($_POST['btn_width'])) {
        $this->UO->save($this->UL->username, 'width', $_POST['sel_width']);
        header("Location: index.php?action=calendarview&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter . "&viewmode=" . $viewmode);
        die();
      }
      elseif (isset($_POST['btn_viewmode'])) {
        if ($this->isLoggedIn())
          $this->UO->save($this->UL->username, 'calViewMode', $_POST['sel_viewmode']);
        header("Location: index.php?action=calendarview&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter . "&viewmode=" . $_POST['sel_viewmode']);
        die();
      }
      elseif (isset($_POST['btn_search'])) {
        if ($this->isLoggedIn())
          $this->UO->save($this->UL->username, 'calfilterSearch', $_POST['txt_search']);
        $viewData['search'] = $_POST['txt_search'];
        $users              = $this->U->getAllLike($_POST['txt_search']);
      }
      elseif (isset($_POST['btn_search_clear'])) {
        if ($this->isLoggedIn())
          $this->UO->deleteUserOption($this->UL->username, 'calfilterSearch');
        header("Location: index.php?action=calendarview&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter . "&viewmode=" . $viewmode);
        die();
      }
      elseif (isset($_POST['btn_reset'])) {
        if ($this->isLoggedIn()) {
          $this->UO->deleteUserOption($this->UL->username, 'calfilter');
          $this->UO->deleteUserOption($this->UL->username, 'calfilterMonth');
          $this->UO->deleteUserOption($this->UL->username, 'calfilterRegion');
          $this->UO->deleteUserOption($this->UL->username, 'calfilterGroup');
          $this->UO->deleteUserOption($this->UL->username, 'calfilterAbs');
          $this->UO->deleteUserOption($this->UL->username, 'calfilterSearch');
        }
        header("Location: index.php?action=calendarview");
        die();
      }
    }
    if ($this->isLoggedIn() && ($viewmode == 'fullmonth' || $viewmode == 'splitmonth')) {
      $this->UO->save($this->UL->username, 'calViewMode', $viewmode);
    }
    else {
      $viewmode = 'fullmonth';
    }

    $viewData['viewmode'] = $viewmode;

    // Build Months
    if ($viewmode === 'splitmonth') {
      $currYear  = intval($viewData['year']);
      $currMonth = intval($viewData['month']);

      for ($splitIdx = 0; $splitIdx < $showMonths; $splitIdx++) {
        $nextMonth = $currMonth + 1;
        $nextYear  = $currYear;
        if ($nextMonth > 12) {
          $nextMonth = 1;
          $nextYear++;
        }

        $currMonthInfo = dateInfo((string) $currYear, sprintf('%02d', $currMonth));
        $nextMonthInfo = dateInfo((string) $nextYear, sprintf('%02d', $nextMonth));

        $M = new MonthModel();
        if (!$M->getMonth((string) $currYear, sprintf('%02d', $currMonth), $viewData['regionid'])) {
          createMonth((string) $currYear, sprintf('%02d', $currMonth), 'region', $viewData['regionid']);
          $M->getMonth((string) $currYear, sprintf('%02d', $currMonth), $viewData['regionid']);
        }

        $nextM = new MonthModel();
        if (!$nextM->getMonth((string) $nextYear, sprintf('%02d', $nextMonth), $viewData['regionid'])) {
          createMonth((string) $nextYear, sprintf('%02d', $nextMonth), 'region', $viewData['regionid']);
          $nextM->getMonth((string) $nextYear, sprintf('%02d', $nextMonth), $viewData['regionid']);
          if ($this->allConfig['emailNotifications']) {
            sendMonthEventNotifications("created", (string) $nextYear, sprintf('%02d', $nextMonth), $viewData['regionname']);
          }
          $this->LOG->logEvent("logMonth", $this->isLoggedIn() ? $this->UL->username : "", "log_month_tpl_created", $nextM->region . ": " . $nextM->year . "-" . $nextM->month);
        }

        $viewData['months'][] = [
          'year'          => $currYear,
          'month'         => sprintf('%02d', $currMonth),
          'dateInfo'      => $currMonthInfo,
          'dayStart'      => $currMonthInfo['daysInMonth'] - 14,
          'dayEnd'        => $currMonthInfo['daysInMonth'],
          'nextMonthInfo' => $nextMonthInfo,
          'nextMonthDays' => 15,
          'isSplitMonth'  => true,
          'M'             => $M,
          'nextM'         => $nextM,
          'dayStyles'     => [],
          'businessDays'  => 0,
        ];

        $currMonth = $nextMonth;
        $currYear  = $nextYear;
      }
    }
    else {
      $M = new MonthModel();
      if (!$M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid'])) {
        createMonth($viewData['year'], $viewData['month'], 'region', $viewData['regionid']);
        $M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid']);
        if ($this->allConfig['emailNotifications']) {
          sendMonthEventNotifications("created", $viewData['year'], $viewData['month'], $viewData['regionname']);
        }
        $this->LOG->logEvent("logMonth", $this->isLoggedIn() ? $this->UL->username : "", "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
      }
      $viewData['months'] = [
        [
          'year'         => $viewData['year'],
          'month'        => $viewData['month'],
          'dateInfo'     => dateInfo($viewData['year'], $viewData['month']),
          'M'            => $M,
          'dayStyles'    => [],
          'businessDays' => 0,
        ],
      ];
    }

    if ($showMonths > 1 && $viewmode === 'fullmonth') {
      $prevYear  = intval($viewData['year']);
      $prevMonth = intval($viewData['month']);
      for ($i = 2; $i <= $showMonths; $i++) {
        if ($prevMonth == 12) {
          if ($this->allConfig['currentYearOnly'] && $this->allConfig["currYearRoles"]) {
            $arrCurrYearRoles = explode(',', $this->allConfig["currYearRoles"]);
            $userRole         = $this->U->getRole($this->isLoggedIn() ? $this->UL->username : "");
            if (in_array($userRole, $arrCurrYearRoles)) {
              $i = $showMonths + 1;
              continue;
            }
            else {
              $nextMonth = "01";
              $nextYear  = $prevYear + 1;
            }
          }
          else {
            $nextMonth = "01";
            $nextYear  = $prevYear + 1;
          }
        }
        else {
          $nextMonth = sprintf('%02d', $prevMonth + 1);
          $nextYear  = $prevYear;
        }

        $M = new MonthModel();
        if (!$M->getMonth((string) $nextYear, $nextMonth, $viewData['regionid'])) {
          createMonth((string) $nextYear, $nextMonth, 'region', $viewData['regionid']);
          $M->getMonth((string) $nextYear, $nextMonth, $viewData['regionid']);
          if ($this->allConfig['emailNotifications']) {
            sendMonthEventNotifications("created", (string) $nextYear, $nextMonth, $viewData['regionname']);
          }
          $this->LOG->logEvent("logMonth", $this->isLoggedIn() ? $this->UL->username : "", "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
        }

        $viewData['months'][] = [
          'year'         => $nextYear,
          'month'        => $nextMonth,
          'dateInfo'     => dateInfo((string) $nextYear, $nextMonth),
          'M'            => $M,
          'dayStyles'    => [],
          'businessDays' => 0,
        ];
        $prevYear             = intval($nextYear);
        $prevMonth            = intval($nextMonth);
      }
    }

    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];
    $viewData['absences']   = $this->A->getAll();
    $viewData['allGroups']  = $this->G->getAll();
    $viewData['holidays']   = $this->H->getAllCustom();
    $viewData['groups']     = ($groupfilter == 'all' || $groupfilter == 'allbygroup') ? $this->G->getAll() : $this->G->getRowById($groupfilter);
    if ($groupfilter == 'allbygroup') {
      $viewData['defgroupfilter'] = 'allbygroup';
    }
    $viewData['dayStyles'] = [];

    $viewData['users'] = [];
    foreach ($users as $usr) {
      $allowed = false;
      if ($usr['username'] == $this->UL->username) {
        $allowed = true;
      }
      elseif (!$this->U->isHidden($usr['username'])) {
        if (isAllowed("calendarviewall") || (isAllowed("calendarviewgroup") && $this->UG->shareGroups($usr['username'], $this->UL->username))) {
          $allowed = true;
        }
      }
      if ($allowed) {
        $viewData['users'][] = $usr;
      }
    }

    foreach ($viewData['users'] as $user) {
      foreach ($viewData['months'] as $vmonth) {
        if (!$this->T->getTemplate($user['username'], (string) $vmonth['year'], (string) $vmonth['month'])) {
          createMonth((string) $vmonth['year'], (string) $vmonth['month'], 'user', $user['username']);
        }
      }
    }

    // Styles and Business Days (Simplified for brevity, logic is same as original)
    // ... (Copying the style generation loop) ...
    // I'll include the style generation loop because it's critical for the view.

    $holidayColors = [];
    $allHolidays   = $this->H->getAll();
    foreach ($allHolidays as $holiday) {
      $holidayColors[$holiday['id']] = ['color' => $holiday['color'], 'bgcolor' => $holiday['bgcolor']];
    }

    $weekendColors    = [];
    $weekendColors[6] = $holidayColors[2] ?? ['color' => '000000', 'bgcolor' => 'ffffff']; // Sat
    $weekendColors[7] = $holidayColors[3] ?? ['color' => '000000', 'bgcolor' => 'ffffff']; // Sun

    $j                = 0;
    $todayBorderStyle = 'border-left: ' . $this->allConfig["todayBorderSize"] . 'px solid #' . $this->allConfig["todayBorderColor"] . ';border-right: ' . $this->allConfig["todayBorderSize"] . 'px solid #' . $this->allConfig["todayBorderColor"] . ';';
    $currDate         = date('Y-m-d');

    foreach ($viewData['months'] as $j => $vmonth) {
      $dayStyles = [];
      $monthObj  = $vmonth['M'];
      $monthNum  = intval($vmonth['month']);
      $yearNum   = intval($vmonth['year']);

      // Main Month Days
      for ($i = 1; $i <= $vmonth['dateInfo']['daysInMonth']; $i++) {
        $hprop     = 'hol' . $i;
        $wprop     = 'wday' . $i;
        $holidayId = (int) $monthObj->$hprop;
        $weekday   = (int) $monthObj->$wprop;
        $color     = '';
        $bgcolor   = '';
        $border    = '';

        if ($holidayId && isset($holidayColors[$holidayId])) {
          if ($this->H->keepWeekendColor((string) $holidayId) && ($weekday == 6 || $weekday == 7)) {
            $wc      = $weekendColors[$weekday];
            $color   = 'color:#' . $wc['color'] . ';';
            $bgcolor = 'background-color:#' . $wc['bgcolor'] . ';';
          }
          else {
            $color   = 'color:#' . $holidayColors[$holidayId]['color'] . ';';
            $bgcolor = 'background-color:#' . $holidayColors[$holidayId]['bgcolor'] . ';';
          }
        }
        elseif ($weekday == 6 || $weekday == 7) {
          $wc      = $weekendColors[$weekday];
          $color   = 'color:#' . $wc['color'] . ';';
          $bgcolor = 'background-color:#' . $wc['bgcolor'] . ';';
        }

        if (date('Y-m-d', mktime(0, 0, 0, $monthNum, $i, $yearNum)) == $currDate) {
          $border = $todayBorderStyle;
        }

        if ($color || $bgcolor || $border) {
          $dayStyles[$i] = $color . $bgcolor . $border;
        }
      }

      // Split Month Next 15 Days
      if (isset($vmonth['isSplitMonth'])) {
        $nextMonthObj = $vmonth['nextM'];
        $nextMonthNum = $monthNum + 1 > 12 ? 1 : $monthNum + 1;
        $nextYearNum  = $monthNum + 1 > 12 ? $yearNum + 1 : $yearNum;

        for ($i = 1; $i <= 15; $i++) {
          $hprop     = 'hol' . $i;
          $wprop     = 'wday' . $i;
          $holidayId = (int) $nextMonthObj->$hprop;
          $weekday   = (int) $nextMonthObj->$wprop;
          $color     = '';
          $bgcolor   = '';
          $border    = '';

          if ($holidayId && isset($holidayColors[$holidayId])) {
            if ($this->H->keepWeekendColor((string) $holidayId) && ($weekday == 6 || $weekday == 7)) {
              $wc      = $weekendColors[$weekday];
              $color   = 'color:#' . $wc['color'] . ';';
              $bgcolor = 'background-color:#' . $wc['bgcolor'] . ';';
            }
            else {
              $color   = 'color:#' . $holidayColors[$holidayId]['color'] . ';';
              $bgcolor = 'background-color:#' . $holidayColors[$holidayId]['bgcolor'] . ';';
            }
          }
          elseif ($weekday == 6 || $weekday == 7) {
            $wc      = $weekendColors[$weekday];
            $color   = 'color:#' . $wc['color'] . ';';
            $bgcolor = 'background-color:#' . $wc['bgcolor'] . ';';
          }

          if (date('Y-m-d', mktime(0, 0, 0, $nextMonthNum, $i, $nextYearNum)) == $currDate) {
            $border = $todayBorderStyle;
          }

          if ($color || $bgcolor || $border) {
            $dayStyles['next_' . $i] = $color . $bgcolor . $border;
          }
        }
      }
      $viewData['months'][$j]['dayStyles'] = $dayStyles;


      // Global Daynotes for Header (moved from views/calendarviewmonthheader.php)
      $headerDaynotes = [];
      $dnColors       = [
        'info'    => '#0dcaf0',
        'success' => '#198754',
        'warning' => '#ffc107',
        'danger'  => '#dc3545',
      ];

      for ($i = 1; $i <= $vmonth['dateInfo']['daysInMonth']; $i++) {
        if ($this->D->get($vmonth['year'] . $vmonth['month'] . sprintf("%02d", $i), 'all', (string) $viewData['regionid'], true)) {
          $c                  = $this->D->color;
          $hex                = $dnColors[$c] ?? ((strpos($c, '#') === 0) ? $c : '#' . $c);
          $headerDaynotes[$i] = [
            'color'    => $c,
            'colorHex' => $hex,
            'note'     => $this->D->daynote
          ];
        }
      }
      if (isset($vmonth['isSplitMonth'])) {
        $nextMonthNum = intval($vmonth['month']) + 1 > 12 ? 1 : intval($vmonth['month']) + 1;
        $nextYearNum  = intval($vmonth['month']) + 1 > 12 ? intval($vmonth['year']) + 1 : intval($vmonth['year']);
        for ($i = 1; $i <= 15; $i++) {
          if ($this->D->get((string) $nextYearNum . sprintf("%02d", $nextMonthNum) . sprintf("%02d", $i), 'all', (string) $viewData['regionid'], true)) {
            $c                            = $this->D->color;
            $hex                          = $dnColors[$c] ?? ((strpos($c, '#') === 0) ? $c : '#' . $c);
            $headerDaynotes['next_' . $i] = [
              'color'    => $c,
              'colorHex' => $hex,
              'note'     => $this->D->daynote
            ];
          }
        }
      }
      $viewData['months'][$j]['headerDaynotes'] = $headerDaynotes;

      $j++;
    }

    $j = 0;
    foreach ($viewData['months'] as $vmonth) {
      $cntfrom                                = $vmonth['year'] . $vmonth['month'] . '01';
      $cntto                                  = $vmonth['year'] . $vmonth['month'] . $vmonth['dateInfo']['daysInMonth'];
      $viewData['months'][$j]['businessDays'] = $this->AbsenceService->countBusinessDays($cntfrom, $cntto, $viewData['regionid']);

      if (isset($vmonth['isSplitMonth'])) {
        $nextMonthNum = intval($vmonth['month']) + 1;
        $nextYearNum  = intval($vmonth['year']);
        if ($nextMonthNum > 12) {
          $nextMonthNum = 1;
          $nextYearNum++;
        }
        $nextMonthInfo                                   = dateInfo((string) $nextYearNum, sprintf('%02d', $nextMonthNum));
        $nextCntfrom                                     = $nextYearNum . sprintf('%02d', $nextMonthNum) . '01';
        $nextCntto                                       = $nextYearNum . sprintf('%02d', $nextMonthNum) . $nextMonthInfo['daysInMonth'];
        $viewData['months'][$j]['nextMonthBusinessDays'] = $this->AbsenceService->countBusinessDays($nextCntfrom, $nextCntto, $viewData['regionid']);
      }
      $j++;
    }

    $todayDate              = getdate(time());
    $viewData['yearToday']  = $todayDate['year'];
    $viewData['monthToday'] = sprintf("%02d", $todayDate['mon']);
    $viewData['regions']    = $this->R->getAll();

    // Config options
    $viewData['calendarFontSize']         = $this->allConfig['calendarFontSize'];
    $viewData['defgroupfilter']           = $this->allConfig['defgroupfilter'];
    $viewData['firstDayOfWeek']           = $this->allConfig["firstDayOfWeek"];
    $viewData['hideManagers']             = $this->allConfig['hideManagers'];
    $viewData['includeSummary']           = $this->allConfig['includeSummary'];
    $viewData['monitorAbsence']           = $this->C->read('monitorAbsence');
    $viewData['pastDayColor']             = $this->allConfig['pastDayColor'];
    $viewData['regionalHolidays']         = $this->C->read("regionalHolidays");
    $viewData['regionalHolidaysColor']    = $this->C->read("regionalHolidaysColor");
    $viewData['repeatHeaderCount']        = $this->allConfig['repeatHeaderCount'];
    $viewData['showAvatars']              = $this->allConfig['showAvatars'];
    $viewData['showRegionButton']         = $this->allConfig['showRegionButton'];
    $viewData['showRoleIcons']            = $this->allConfig['showRoleIcons'];
    $viewData['showSummary']              = $this->allConfig['showSummary'];
    $viewData['symbolAsIcon']             = $this->C->read('symbolAsIcon');
    $viewData['showTooltipCount']         = $this->allConfig['showTooltipCount'];
    $viewData['showWeekNumbers']          = $this->allConfig['showWeekNumbers'];
    $viewData['summaryAbsenceTextColor']  = $this->allConfig['summaryAbsenceTextColor'];
    $viewData['summaryPresenceTextColor'] = $this->allConfig['summaryPresenceTextColor'];
    $viewData['supportMobile']            = $this->allConfig['supportMobile'];
    $viewData['userPerPage']              = $this->allConfig['usersPerPage'];

    $mobilecols['full'] = $viewData['months'][0]['dateInfo']['daysInMonth'];
    if (!$viewData['width'] = $this->UO->read($this->UL->username, 'width')) {
      $this->UO->save($this->UL->username, 'width', 'full');
      $viewData['width'] = 'full';
    }

    $viewData['calendaronly'] = (isset($_GET['calendaronly']) && $_GET['calendaronly'] === "1");

    //
    // Prepare data for User Rows (moved from views/calendarviewuserrow.php)
    //
    $trustedRoles     = explode(',', $this->allConfig['trustedRoles']);
    $currDate         = date('Y-m-d');
    $todayBorderStyle = 'border-left: ' . $this->allConfig["todayBorderSize"] . 'px solid #' . $this->allConfig["todayBorderColor"] . ';border-right: ' . $this->allConfig["todayBorderSize"] . 'px solid #' . $this->allConfig["todayBorderColor"] . ';';

    $viewData['userRows'] = [];
    $countedUsersPerMonth = []; // [monthKey => [username => true]]

    foreach ($viewData['users'] as $usr) {
      $username = $usr['username'];
      $userRow  = [
        'username'    => $username,
        'fullName'    => $this->U->getLastFirst($username),
        'profileLink' => isAllowed($this->CONF['controllers']['viewprofile']->permission) ? "index.php?action=viewprofile&profile=" . $username : null,
        'nameStyle'   => ($groupfilter != "all" && !$this->UG->isMemberOrManagerOfGroup($username, (string) $groupfilter)) ? "m-name-guest" : "m-name",
        'avatar'      => $this->allConfig['showAvatars'] ? $this->UO->read($username, 'avatar') : null,
        'roleIcon'    => $this->allConfig['showRoleIcons'] ? [
          'name'  => $this->RO->getNameById($this->U->getRole($username)),
          'color' => $this->RO->getColorById($this->U->getRole($username))
        ] : null,
        'groups'      => array_merge(array_keys($this->UG->getAllforUser2($username)), $this->UG->getGuestships($username)),
        'monitorAbs'  => null,
        'months'      => []
      ];

      if ($monAbsConfig = $viewData['monitorAbsence']) {
        $monAbsIds             = explode(',', (string) $monAbsConfig);
        $userRow['monitorAbs'] = [];
        foreach ($monAbsIds as $monAbsId) {
          if (empty($monAbsId))
            continue;
          $summary = $this->AbsenceService->getAbsenceSummary($username, (string) $monAbsId, (string) $viewData['year']);
          if ($this->C->read('symbolAsIcon')) {
            $monAbsIcon = $this->A->getSymbol((string) $monAbsId);
          }
          else {
            $monAbsIcon = '<i class="' . $this->A->getIcon((string) $monAbsId) . '"></i>';
          }
          $userRow['monitorAbs'][] = [
            'name'           => $this->A->getName((string) $monAbsId),
            'remainder'      => $summary['remainder'],
            'totalallowance' => $summary['totalallowance'],
            'icon'           => $monAbsIcon,
            'color'          => $this->A->getColor((string) $monAbsId)
          ];
        }
      }

      foreach ($viewData['months'] as &$vmonth) {
        /** @var array<string, mixed> $vmonth */
        $monthKey = $vmonth['year'] . $vmonth['month'];
        if (!isset($vmonth['dayAbsCount'])) {
          $isSplit                = isset($vmonth['isSplitMonth']) && $vmonth['isSplitMonth'];
          $vmonth['dayAbsCount']  = array_fill(1, $isSplit ? 46 : 31, 0);
          $vmonth['dayPresCount'] = array_fill(1, $isSplit ? 46 : 31, 0);
        }

        $mRow = [
          'editLink' => null,
          'days'     => [],
          'nextDays' => []
        ];

        // Edit Permission Check
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
        $daystart = isset($vmonth['dayStart']) ? $vmonth['dayStart'] : 1;
        $dayend   = isset($vmonth['dayEnd']) ? $vmonth['dayEnd'] : $vmonth['dateInfo']['daysInMonth'];

        for ($i = $daystart; $i <= $dayend; $i++) {
          $dayData          = $this->prepareDayData($username, $i, $vmonth['year'], $vmonth['month'], $vmonth['dayStyles'][$i] ?? '', $trustedRoles, $currDate, $viewData, (string) $viewData['regionid']);
          $mRow['days'][$i] = $dayData;

          // Count for summary
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

          $nextMonthTemplate = new \App\Models\TemplateModel();
          $nextMonthTemplate->getTemplate($username, (string) $nextMonthYear, sprintf('%02d', $nextMonthNum));

          $nextMonthEditLink = null;
          if ($editAllowed) {
            $nextMonthEditLink = 'index.php?action=calendaredit&month=' . $nextMonthYear . sprintf('%02d', $nextMonthNum) . '&region=' . $viewData['regionid'] . '&user=' . $username;
          }
          $mRow['nextMonthEditLink'] = $nextMonthEditLink;

          for ($i = 1; $i <= 15; $i++) {
            $dayData              = $this->prepareDayData($username, $i, (string) $nextMonthYear, sprintf('%02d', $nextMonthNum), $vmonth['dayStyles']['next_' . $i] ?? '', $trustedRoles, $currDate, $viewData, (string) $viewData['regionid'], $nextMonthTemplate);
            $mRow['nextDays'][$i] = $dayData;

            // Count for summary (using indices 16-30 for split view)
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
        $userRow['months'][$monthKey]               = $mRow;
      }
      unset($vmonth);
      $viewData['userRows'][] = $userRow;
    }

    $this->render('calendarview', $viewData);
  }

  /**
   * Prepares data for a single day in a user row
   */
  private function prepareDayData($username, $day, $year, $month, $gridStyle, $trustedRoles, $currDate, $viewData, $regionid, $templateOverride = null): array {
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
            $dayData['icon'] = '<span class="' . $this->A->getIcon((string) $absId) . ' align-bottom"></span>';
          }

          $taken = '';
          if ($this->allConfig['showTooltipCount']) {
            $countFrom   = $year . $month . '01';
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int) $month, (int) $year);
            $countTo     = $year . $month . $daysInMonth;
            $takenMonth  = $this->AbsenceService->countAbsence($username, (string) $absId, $countFrom, $countTo, true, false);

            $countFromYear = $year . '0101';
            $countToYear   = $year . '1231';
            $takenYear     = $this->AbsenceService->countAbsence($username, (string) $absId, $countFromYear, $countToYear, true, false);

            $taken = ' (' . $takenMonth . '/' . $takenYear . ')';
          }
          $dayData['tooltip'] = $this->A->getName((string) $absId) . $taken;
        }
        else {
          // Different absence filtered out
          $dayData['style']   .= 'color: #d5d5d5;background-color: #d5d5d5;';
          $dayData['tooltip']  = $this->LANG['cal_tt_anotherabsence'];
        }
      }
      else {
        // Confidential not allowed
        $dayData['style']   .= 'color: #d5d5d5;background-color: #d5d5d5;';
        $dayData['tooltip']  = $this->LANG['cal_tt_absent'];
      }
    }
    else {
      if ($loopDate < $currDate && $viewData['pastDayColor']) {
        $dayData['style'] .= "background-color:#" . $viewData['pastDayColor'] . ";";
      }
    }

    // Daynote
    $hasDaynote = false;
    if ($this->D->get($year . $month . sprintf("%02d", $day), $username, (string) $regionid, true)) {
      $hasDaynote = true;
    }

    if ($hasDaynote) {
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

    // Regional Holiday
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

  private array $regionMonths = [];
  //---------------------------------------------------------------------------
  /**
   * Helper to get a MonthModel for a region and cache it.
   */
  private function getRegionMonth($year, $month, $region) {
    $key = $year . $month . $region;
    if (!isset($this->regionMonths[$key])) {
      $M = new MonthModel();
      $M->getMonth($year, $month, $region);
      $this->regionMonths[$key] = $M;
    }
    return $this->regionMonths[$key];
  }
}
