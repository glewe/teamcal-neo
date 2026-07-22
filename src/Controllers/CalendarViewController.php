<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

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
    $limit = intval($this->allConfig['usersPerPage']);
    if ($limit > 0) {
      $total                  = count($users);
      $pages                  = (int) ceil($total / $limit);
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
        if ($showMonths > 1) {
          $showMonths--;
        }
        if ($this->isLoggedIn()) {
          $this->UO->save($this->UL->username, 'showMonths', (string) $showMonths);
        }
      }
      if (isset($_POST['btn_onemore'])) {
        $showMonths = intval($_POST['hidden_showmonths']);
        if ($showMonths <= 12) {
          $showMonths++;
        }
        if ($this->isLoggedIn()) {
          $this->UO->save($this->UL->username, 'showMonths', (string) $showMonths);
        }
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

    // Build Months — month 1 fully; placeholder metadata for months 2–N (no DB queries).
    $viewData['monthPlaceholders'] = [];
    $lazyPage                      = $viewData['page'] ?? 1;

    if ($viewmode === 'splitmonth') {
      $currYear  = intval($viewData['year']);
      $currMonth = intval($viewData['month']);

      // Build first split-pair fully
      $viewData['months'][] = $this->CalendarMonthBuilder->buildMonthMeta(
        (string) $currYear,
        sprintf('%02d', $currMonth),
        (string) $viewData['regionid'],
        $viewData['regionname'],
        $viewmode
      );
      $currMonth++;
      if ($currMonth > 12) {
        $currMonth = 1;
        $currYear++;
      }

      // Placeholder entries for remaining split-pairs 2..N
      for ($splitIdx = 1; $splitIdx < $showMonths; $splitIdx++) {
        $viewData['monthPlaceholders'][] = [
          'year'     => sprintf('%04d', $currYear),
          'month'    => sprintf('%02d', $currMonth),
          'region'   => $viewData['regionid'],
          'group'    => $groupfilter,
          'abs'      => $absfilter,
          'page'     => $lazyPage,
          'viewmode' => $viewmode,
        ];
        $currMonth++;
        if ($currMonth > 12) {
          $currMonth = 1;
          $currYear++;
        }
      }
    }
    else {
      // fullmonth: build month 1 fully
      $viewData['months'][] = $this->CalendarMonthBuilder->buildMonthMeta(
        $viewData['year'],
        $viewData['month'],
        (string) $viewData['regionid'],
        $viewData['regionname'],
        $viewmode
      );

      // Placeholder entries for months 2..N
      if ($showMonths > 1) {
        $prevYear  = intval($viewData['year']);
        $prevMonth = intval($viewData['month']);

        for ($i = 2; $i <= $showMonths; $i++) {
          if ($prevMonth == 12) {
            if ($this->allConfig['currentYearOnly'] && $this->allConfig["currYearRoles"]) {
              $arrCurrYearRoles = explode(',', $this->allConfig["currYearRoles"]);
              $userRole         = $this->U->getRole($this->isLoggedIn() ? $this->UL->username : "");
              if (in_array($userRole, $arrCurrYearRoles)) {
                break;
              }
            }
            $nextMonth = "01";
            $nextYear  = $prevYear + 1;
          }
          else {
            $nextMonth = sprintf('%02d', $prevMonth + 1);
            $nextYear  = $prevYear;
          }

          $viewData['monthPlaceholders'][] = [
            'year'     => (string) $nextYear,
            'month'    => $nextMonth,
            'region'   => $viewData['regionid'],
            'group'    => $groupfilter,
            'abs'      => $absfilter,
            'page'     => $lazyPage,
            'viewmode' => $viewmode,
          ];
          $prevYear  = intval($nextYear);
          $prevMonth = intval($nextMonth);
        }
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
    $viewData['userPerPage']              = $this->allConfig['usersPerPage'];

    $validWidths = ['full', '1024', '800', '640', '480', '400', '320', '240'];
    if (isset($_GET['width']) && in_array($_GET['width'], $validWidths, true) && $this->isLoggedIn()) {
      $this->UO->save($this->UL->username, 'width', $_GET['width']);
    }
    if (!$viewData['width'] = $this->UO->read($this->UL->username, 'width')) {
      $this->UO->save($this->UL->username, 'width', 'full');
      $viewData['width'] = 'full';
    }

    $viewData['calendaronly'] = (isset($_GET['calendaronly']) && $_GET['calendaronly'] === "1");

    //
    // Prepare data for User Rows
    //
    $trustedRoles         = explode(',', $this->allConfig['trustedRoles']);
    $viewData['userRows'] = [];
    $countedUsersPerMonth = [];

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
        $monthKey                     = $vmonth['year'] . $vmonth['month'];
        $userRow['months'][$monthKey] = $this->CalendarMonthBuilder->buildUserMonthRow(
          $username,
          $vmonth,
          $viewData,
          $trustedRoles,
          $countedUsersPerMonth
        );
      }
      unset($vmonth);
      $viewData['userRows'][] = $userRow;
    }

    $this->render('calendarview', $viewData);
  }
}
