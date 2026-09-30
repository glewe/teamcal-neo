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
    elseif ($this->isLoggedIn() && $monthfilter = $this->userOptionModel->read($this->userLoggedIn->username, 'calfilterMonth')) {
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
        $this->userOptionModel->save($this->userLoggedIn->username, 'calfilterMonth', $monthfilter);
    }

    // Region Filter
    if (isset($_GET['region'])) {
      $regionfilter = sanitize($_GET['region']);
      if ($this->isLoggedIn())
        $this->userOptionModel->save($this->userLoggedIn->username, 'calfilterRegion', $regionfilter);
    }
    elseif ($this->isLoggedIn() && $regionfilter = $this->userOptionModel->read($this->userLoggedIn->username, 'calfilterRegion')) {
      // Loaded from user option
    }
    else {
      $regionfilter = '1';
    }

    if (!$missingData) {
      if (!$this->regionModel->getById($regionfilter)) {
        $missingData = true;
      }
      else {
        $viewData['regionid']   = $this->regionModel->id;
        $viewData['regionname'] = $this->regionModel->name;
        if ($this->isLoggedIn())
          $this->userOptionModel->save($this->userLoggedIn->username, 'calfilterRegion', $regionfilter);
      }
    }

    if ($missingData) {
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
      return;
    }

    // Users and Filters
    $users       = $this->userModel->getAllButHidden();
    $groupOption = $this->isLoggedIn() ? $this->userOptionModel->read($this->userLoggedIn->username, 'calfilterGroup') : false;
    $absOption   = $this->isLoggedIn() ? $this->userOptionModel->read($this->userLoggedIn->username, 'calfilterAbs') : false;
    $groupfilter = $_GET['group'] ?? ($groupOption ?: ($this->allConfig['defgroupfilter'] ?: 'all'));
    $absfilter   = $_GET['abs'] ?? ($absOption ?: 'all');

    if ($this->isLoggedIn()) {
      if (isset($_GET['group']))
        $this->userOptionModel->save($this->userLoggedIn->username, 'calfilterGroup', $groupfilter);
      if (isset($_GET['abs']))
        $this->userOptionModel->save($this->userLoggedIn->username, 'calfilterAbs', $absfilter);
    }

    $viewData['groupid'] = $groupfilter;
    $viewData['absid']   = $absfilter;

    if ($groupfilter !== 'all' || $absfilter !== 'all') {
      $filteredUsers = [];
      foreach ($users as $usr) {
        $include = true;
        if ($groupfilter !== 'all' && $groupfilter !== 'allbygroup') {
          $include = $this->userGroupModel->isMemberOrGuestOfGroup($usr['username'], (string) $groupfilter);
          if (!$include)
            continue;
        }
        if ($absfilter !== 'all') {
          $include = $this->absenceDayModel->hasAbsence($usr['username'], date('Y'), date('m'), (int) $absfilter);
        }
        if ($include)
          $filteredUsers[] = $usr;
      }
      $users = $filteredUsers;
    }

    $viewData['group']     = ($groupfilter == "all") ? $this->LANG['all'] : $this->groupModel->getNameById($groupfilter);
    $viewData['absfilter'] = ($absfilter !== "all");
    $viewData['absence']   = ($absfilter == "all") ? $this->LANG['all'] : $this->absenceModel->getName((string) $absfilter);

    $viewData['search'] = '';
    if ($this->isLoggedIn() && $searchfilter = $this->userOptionModel->read($this->userLoggedIn->username, 'calfilterSearch')) {
      $viewData['search'] = $searchfilter;
      $users              = $this->userModel->getAllLike($searchfilter);
    }

    if (isset($_GET['search']) && $_GET['search'] == "reset") {
      if ($this->isLoggedIn())
        $this->userOptionModel->deleteUserOption($this->userLoggedIn->username, 'calfilterSearch');
      header("Location: index.php?action=calendarview");
      die();
    }

    if ($this->allConfig['currentYearOnly'] && $viewData['year'] != date('Y')) {
      if ($this->allConfig['currYearRoles']) {
        $arrCurrYearRoles = explode(',', $this->allConfig['currYearRoles']);
        $userRole         = $this->userModel->getRole($this->isLoggedIn() ? $this->userLoggedIn->username : "");
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

    if ($this->userOptionModel->read($this->userLoggedIn->username, 'showMonths')) {
      $showMonths = intval($this->userOptionModel->read($this->userLoggedIn->username, 'showMonths'));
    }
    elseif ($this->allConfig['showMonths']) {
      $showMonths = intval($this->allConfig['showMonths']);
    }
    else {
      $showMonths = 1;
      $this->configModel->save('showMonths', '1');
    }

    $viewmode = 'fullmonth';
    if ($this->userOptionModel->read($this->userLoggedIn->username, 'calViewMode')) {
      $viewmode = $this->userOptionModel->read($this->userLoggedIn->username, 'calViewMode');
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
          $this->userOptionModel->save($this->userLoggedIn->username, 'showMonths', (string) $showMonths);
        }
      }
      if (isset($_POST['btn_onemore'])) {
        $showMonths = intval($_POST['hidden_showmonths']);
        if ($showMonths <= 12) {
          $showMonths++;
        }
        if ($this->isLoggedIn()) {
          $this->userOptionModel->save($this->userLoggedIn->username, 'showMonths', (string) $showMonths);
        }
      }

      if (isset($_POST['btn_month'])) {
        if ($this->isLoggedIn())
          $this->userOptionModel->save($this->userLoggedIn->username, 'calfilterMonth', $_POST['txt_year'] . $_POST['sel_month']);
        header("Location: index.php?action=calendarview&month=" . $_POST['txt_year'] . $_POST['sel_month'] . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter . "&viewmode=" . $viewmode);
        die();
      }
      elseif (isset($_POST['btn_region'])) {
        if ($this->isLoggedIn())
          $this->userOptionModel->save($this->userLoggedIn->username, 'calfilterRegion', $_POST['sel_region']);
        header("Location: index.php?action=calendarview&month=" . $monthfilter . "&region=" . $_POST['sel_region'] . "&group=" . $groupfilter . "&abs=" . $absfilter . "&viewmode=" . $viewmode);
        die();
      }
      elseif (isset($_POST['btn_group'])) {
        if ($this->isLoggedIn())
          $this->userOptionModel->save($this->userLoggedIn->username, 'calfilterGroup', $_POST['sel_group']);
        header("Location: index.php?action=calendarview&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $_POST['sel_group'] . "&abs=" . $absfilter . "&viewmode=" . $viewmode);
        die();
      }
      elseif (isset($_POST['btn_abssearch'])) {
        if ($this->isLoggedIn())
          $this->userOptionModel->save($this->userLoggedIn->username, 'calfilterAbs', $_POST['sel_absence']);
        header("Location: index.php?action=calendarview&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $_POST['sel_absence'] . "&viewmode=" . $viewmode);
        die();
      }
      elseif (isset($_POST['btn_width'])) {
        $this->userOptionModel->save($this->userLoggedIn->username, 'width', $_POST['sel_width']);
        header("Location: index.php?action=calendarview&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter . "&viewmode=" . $viewmode);
        die();
      }
      elseif (isset($_POST['btn_viewmode'])) {
        if ($this->isLoggedIn())
          $this->userOptionModel->save($this->userLoggedIn->username, 'calViewMode', $_POST['sel_viewmode']);
        header("Location: index.php?action=calendarview&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter . "&viewmode=" . $_POST['sel_viewmode']);
        die();
      }
      elseif (isset($_POST['btn_search'])) {
        if ($this->isLoggedIn())
          $this->userOptionModel->save($this->userLoggedIn->username, 'calfilterSearch', $_POST['txt_search']);
        $viewData['search'] = $_POST['txt_search'];
        $users              = $this->userModel->getAllLike($_POST['txt_search']);
      }
      elseif (isset($_POST['btn_search_clear'])) {
        if ($this->isLoggedIn())
          $this->userOptionModel->deleteUserOption($this->userLoggedIn->username, 'calfilterSearch');
        header("Location: index.php?action=calendarview&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter . "&viewmode=" . $viewmode);
        die();
      }
      elseif (isset($_POST['btn_reset'])) {
        if ($this->isLoggedIn()) {
          $this->userOptionModel->deleteUserOption($this->userLoggedIn->username, 'calfilter');
          $this->userOptionModel->deleteUserOption($this->userLoggedIn->username, 'calfilterMonth');
          $this->userOptionModel->deleteUserOption($this->userLoggedIn->username, 'calfilterRegion');
          $this->userOptionModel->deleteUserOption($this->userLoggedIn->username, 'calfilterGroup');
          $this->userOptionModel->deleteUserOption($this->userLoggedIn->username, 'calfilterAbs');
          $this->userOptionModel->deleteUserOption($this->userLoggedIn->username, 'calfilterSearch');
        }
        header("Location: index.php?action=calendarview");
        die();
      }
    }
    if ($this->isLoggedIn() && ($viewmode == 'fullmonth' || $viewmode == 'splitmonth')) {
      $this->userOptionModel->save($this->userLoggedIn->username, 'calViewMode', $viewmode);
    }
    else {
      if (!$this->isLoggedIn() && ($_GET['viewmode'] ?? '') === 'splitmonth') {
        $viewData['showViewModeToast'] = true;
      }
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
      $viewData['months'][] = $this->calendarMonthBuilderService->buildMonthMeta(
        (string) $currYear,
        sprintf('%02d', $currMonth),
        (string) $viewData['regionid'],
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
      $viewData['months'][] = $this->calendarMonthBuilderService->buildMonthMeta(
        $viewData['year'],
        $viewData['month'],
        (string) $viewData['regionid'],
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
              $userRole         = $this->userModel->getRole($this->isLoggedIn() ? $this->userLoggedIn->username : "");
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
    $viewData['absences']   = $this->absenceModel->getAll();
    $viewData['allGroups']  = $this->groupModel->getAll();
    $viewData['holidays']   = $this->holidayModel->getAllCustom();
    $viewData['groups']     = ($groupfilter == 'all' || $groupfilter == 'allbygroup') ? $this->groupModel->getAll() : $this->groupModel->getRowById($groupfilter);
    if ($groupfilter == 'allbygroup') {
      $viewData['defgroupfilter'] = 'allbygroup';
    }
    $viewData['dayStyles'] = [];

    $viewData['users'] = [];
    foreach ($users as $usr) {
      $allowed = false;
      if ($usr['username'] == $this->userLoggedIn->username) {
        $allowed = true;
      }
      elseif (!$this->userModel->isHidden($usr['username'])) {
        if (isAllowed("calendarviewall") || (isAllowed("calendarviewgroup") && $this->userGroupModel->shareGroups($usr['username'], $this->userLoggedIn->username))) {
          $allowed = true;
        }
      }
      if ($allowed) {
        $viewData['users'][] = $usr;
      }
    }

    $todayDate              = getdate(time());
    $viewData['yearToday']  = $todayDate['year'];
    $viewData['monthToday'] = sprintf("%02d", $todayDate['mon']);
    $viewData['regions']    = $this->regionModel->getAll();

    // Config options
    $viewData['calendarFontSize']         = $this->allConfig['calendarFontSize'];
    $viewData['defgroupfilter']           = $this->allConfig['defgroupfilter'];
    $viewData['firstDayOfWeek']           = $this->allConfig["firstDayOfWeek"];
    $viewData['hideManagers']             = $this->allConfig['hideManagers'];
    $viewData['includeSummary']           = $this->allConfig['includeSummary'];
    $viewData['monitorAbsence']           = $this->configModel->read('monitorAbsence');
    $viewData['pastDayColor']             = $this->allConfig['pastDayColor'];
    $viewData['regionalHolidays']         = $this->configModel->read("regionalHolidays");
    $viewData['regionalHolidaysColor']    = $this->configModel->read("regionalHolidaysColor");
    $viewData['repeatHeaderCount']        = $this->allConfig['repeatHeaderCount'];
    $viewData['showAvatars']              = $this->allConfig['showAvatars'];
    $viewData['showRegionButton']         = $this->allConfig['showRegionButton'];
    $viewData['showRoleIcons']            = $this->allConfig['showRoleIcons'];
    $viewData['showSummary']              = $this->allConfig['showSummary'];
    $viewData['symbolAsIcon']             = $this->configModel->read('symbolAsIcon');
    $viewData['showTooltipCount']         = $this->allConfig['showTooltipCount'];
    $viewData['showWeekNumbers']          = $this->allConfig['showWeekNumbers'];
    $viewData['summaryAbsenceTextColor']  = $this->allConfig['summaryAbsenceTextColor'];
    $viewData['summaryPresenceTextColor'] = $this->allConfig['summaryPresenceTextColor'];
    $viewData['userPerPage']              = $this->allConfig['usersPerPage'];

    $validWidths = ['full', '1024', '800', '640', '480', '400', '320', '240'];
    if (isset($_GET['width']) && in_array($_GET['width'], $validWidths, true) && $this->isLoggedIn()) {
      $this->userOptionModel->save($this->userLoggedIn->username, 'width', $_GET['width']);
    }
    if (!$viewData['width'] = $this->userOptionModel->read($this->userLoggedIn->username, 'width')) {
      $this->userOptionModel->save($this->userLoggedIn->username, 'width', 'full');
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
        'fullName'    => $this->userModel->getLastFirst($username),
        'profileLink' => isAllowed($this->CONF['controllers']['viewprofile']->permission) ? "index.php?action=viewprofile&profile=" . $username : null,
        'nameStyle'   => ($groupfilter != "all" && !$this->userGroupModel->isMemberOrManagerOfGroup($username, (string) $groupfilter)) ? "m-name-guest" : "m-name",
        'avatar'      => $this->allConfig['showAvatars'] ? $this->userOptionModel->read($username, 'avatar') : null,
        'roleIcon'    => $this->allConfig['showRoleIcons'] ? [
          'name'  => $this->roleModel->getNameById($this->userModel->getRole($username)),
          'color' => $this->roleModel->getColorById($this->userModel->getRole($username))
        ] : null,
        'groups'      => array_merge(array_keys($this->userGroupModel->getAllforUser2($username)), $this->userGroupModel->getGuestships($username)),
        'monitorAbs'  => null,
        'months'      => []
      ];

      if ($monAbsConfig = $viewData['monitorAbsence']) {
        $monAbsIds             = explode(',', (string) $monAbsConfig);
        $userRow['monitorAbs'] = [];
        foreach ($monAbsIds as $monAbsId) {
          if (empty($monAbsId))
            continue;
          $summary = $this->absenceService->getAbsenceSummary($username, (string) $monAbsId, (string) $viewData['year']);
          if ($this->configModel->read('symbolAsIcon')) {
            $monAbsIcon = $this->absenceModel->getSymbol((string) $monAbsId);
          }
          else {
            $monAbsIcon = '<i class="' . $this->absenceModel->getIcon((string) $monAbsId) . '"></i>';
          }
          $userRow['monitorAbs'][] = [
            'name'           => $this->absenceModel->getName((string) $monAbsId),
            'remainder'      => $summary['remainder'],
            'totalallowance' => $summary['totalallowance'],
            'icon'           => $monAbsIcon,
            'color'          => $this->absenceModel->getColor((string) $monAbsId)
          ];
        }
      }

      foreach ($viewData['months'] as &$vmonth) {
        $monthKey                     = $vmonth['year'] . $vmonth['month'];
        $userRow['months'][$monthKey] = $this->calendarMonthBuilderService->buildUserMonthRow(
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
