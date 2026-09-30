<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

/**
 * Calendar View Month Controller
 *
 * Fragment endpoint: renders a single month's HTML block (calendarviewmonth.twig)
 * for lazy loading by the JS loader on the calendar view page.
 *
 * Called via: index.php?action=calendarviewmonth&month=YYYYMM&region=X&group=Y&abs=Z&page=P&viewmode=M
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     6.0.0
 */
class CalendarViewMonthController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['calendarview']->permission)) {
      http_response_code(403);
      echo '<div class="alert alert-warning">' . $this->LANG['alert_not_allowed_text'] . '</div>';
      exit;
    }

    // Validate month parameter (YYYYMM)
    $monthParam = isset($_GET['month']) ? sanitize($_GET['month']) : '';
    if (!is_numeric($monthParam) || strlen($monthParam) !== 6) {
      http_response_code(400);
      echo '<div class="alert alert-danger">' . $this->LANG['alert_no_data_text'] . '</div>';
      exit;
    }
    $year  = substr($monthParam, 0, 4);
    $month = substr($monthParam, 4, 2);
    if (!checkdate((int) $month, 1, (int) $year)) {
      http_response_code(400);
      echo '<div class="alert alert-danger">' . $this->LANG['alert_no_data_text'] . '</div>';
      exit;
    }

    // Region
    $regionfilter = isset($_GET['region']) ? sanitize($_GET['region']) : '1';
    if (!$this->regionModel->getById($regionfilter)) {
      http_response_code(400);
      echo '<div class="alert alert-danger">' . $this->LANG['alert_no_data_text'] . '</div>';
      exit;
    }
    $regionid = $this->regionModel->id;

    // Filters and view options
    $groupfilter = isset($_GET['group'])    ? sanitize($_GET['group'])    : 'all';
    $absfilter   = isset($_GET['abs'])      ? sanitize($_GET['abs'])      : 'all';
    $viewmode    = isset($_GET['viewmode']) ? sanitize($_GET['viewmode']) : 'fullmonth';
    if ($viewmode !== 'fullmonth' && $viewmode !== 'splitmonth') {
      $viewmode = 'fullmonth';
    }
    $page = max(1, (int) filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]));

    // Build filtered user list (mirrors CalendarViewController::execute())
    $users = $this->userModel->getAllButHidden();

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

    $limit = intval($this->allConfig['usersPerPage']);
    if ($limit > 0) {
      $total  = count($users);
      $pages  = (int) ceil($total / $limit);
      $page   = min($pages ?: 1, $page);
      $offset = ($page - 1) * $limit;
      $users  = array_slice($users, $offset, $limit);
    }

    // Permission filter (same visibility rules as CalendarViewController)
    $allowedUsers = [];
    foreach ($users as $usr) {
      $allowed = false;
      if ($usr['username'] === $this->userLoggedIn->username) {
        $allowed = true;
      }
      elseif (!$this->userModel->isHidden($usr['username'])) {
        if (isAllowed("calendarviewall") || (isAllowed("calendarviewgroup") && $this->userGroupModel->shareGroups($usr['username'], $this->userLoggedIn->username))) {
          $allowed = true;
        }
      }
      if ($allowed)
        $allowedUsers[] = $usr;
    }
    $users = $allowedUsers;

    // Build month meta (one month / split-pair)
    $vmonth = $this->calendarMonthBuilderService->buildMonthMeta($year, $month, (string) $regionid, $viewmode);

    // Minimal context subset passed into buildUserMonthRow / prepareDayData
    $rowContext = [
      'regionid'              => $regionid,
      'absfilter'             => ($absfilter !== 'all'),
      'absid'                 => $absfilter,
      'pastDayColor'          => $this->allConfig['pastDayColor'],
      'regionalHolidays'      => $this->configModel->read('regionalHolidays'),
      'regionalHolidaysColor' => $this->configModel->read('regionalHolidaysColor'),
    ];

    $trustedRoles         = explode(',', $this->allConfig['trustedRoles']);
    $countedUsersPerMonth = [];
    $userRows             = [];
    $monAbsConfig         = $this->configModel->read('monitorAbsence');

    foreach ($users as $usr) {
      $username = $usr['username'];
      $userRow  = [
        'username'    => $username,
        'fullName'    => $this->userModel->getLastFirst($username),
        'profileLink' => isAllowed($this->CONF['controllers']['viewprofile']->permission) ? 'index.php?action=viewprofile&profile=' . $username : null,
        'nameStyle'   => ($groupfilter !== 'all' && !$this->userGroupModel->isMemberOrManagerOfGroup($username, (string) $groupfilter)) ? 'm-name-guest' : 'm-name',
        'avatar'      => $this->allConfig['showAvatars'] ? $this->userOptionModel->read($username, 'avatar') : null,
        'roleIcon'    => $this->allConfig['showRoleIcons'] ? [
          'name'  => $this->roleModel->getNameById($this->userModel->getRole($username)),
          'color' => $this->roleModel->getColorById($this->userModel->getRole($username))
        ] : null,
        'groups'      => array_merge(array_keys($this->userGroupModel->getAllforUser2($username)), $this->userGroupModel->getGuestships($username)),
        'monitorAbs'  => null,
        'months'      => []
      ];

      if ($monAbsConfig) {
        $monAbsIds             = explode(',', (string) $monAbsConfig);
        $userRow['monitorAbs'] = [];
        foreach ($monAbsIds as $monAbsId) {
          if (empty($monAbsId))
            continue;
          $summary = $this->absenceService->getAbsenceSummary($username, (string) $monAbsId, $year);
          $monAbsIcon = $this->configModel->read('symbolAsIcon')
            ? $this->absenceModel->getSymbol((string) $monAbsId)
            : '<i class="' . $this->absenceModel->getIcon((string) $monAbsId) . '"></i>';
          $userRow['monitorAbs'][] = [
            'name'           => $this->absenceModel->getName((string) $monAbsId),
            'remainder'      => $summary['remainder'],
            'totalallowance' => $summary['totalallowance'],
            'icon'           => $monAbsIcon,
            'color'          => $this->absenceModel->getColor((string) $monAbsId)
          ];
        }
      }

      $monthKey                     = $vmonth['year'] . $vmonth['month'];
      $userRow['months'][$monthKey] = $this->calendarMonthBuilderService->buildUserMonthRow(
        $username,
        $vmonth,
        $rowContext,
        $trustedRoles,
        $countedUsersPerMonth
      );

      $userRows[] = $userRow;
    }

    // defgroupfilter: respect the allbygroup override (mirrors main controller)
    $defgroupfilter = $this->allConfig['defgroupfilter'];
    if ($groupfilter === 'allbygroup') {
      $defgroupfilter = 'allbygroup';
    }

    $viewData = [
      'month'                    => $vmonth,
      'userRows'                 => $userRows,
      'hideManagers'             => $this->allConfig['hideManagers'],
      'width'                    => $this->userOptionModel->read($this->userLoggedIn->username, 'width') ?: 'full',
      'firstDayOfWeek'           => $this->allConfig['firstDayOfWeek'],
      'showWeekNumbers'          => $this->allConfig['showWeekNumbers'],
      'defgroupfilter'           => $defgroupfilter,
      'groups'                   => ($groupfilter === 'all' || $groupfilter === 'allbygroup') ? $this->groupModel->getAll() : $this->groupModel->getRowById($groupfilter),
      'includeSummary'           => $this->allConfig['includeSummary'],
      'showSummary'              => $this->allConfig['showSummary'],
      'summaryAbsenceTextColor'  => $this->allConfig['summaryAbsenceTextColor'],
      'summaryPresenceTextColor' => $this->allConfig['summaryPresenceTextColor'],
      'showAvatars'              => $this->allConfig['showAvatars'],
      'showRoleIcons'            => $this->allConfig['showRoleIcons'],
      'absfilter'                => ($absfilter !== 'all'),
      'absid'                    => $absfilter,
    ];

    $this->renderFragment('calendarviewmonth', $viewData);
  }
}
