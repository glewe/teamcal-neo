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
 * @since     5.4.0
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
    if (!$this->R->getById($regionfilter)) {
      http_response_code(400);
      echo '<div class="alert alert-danger">' . $this->LANG['alert_no_data_text'] . '</div>';
      exit;
    }
    $regionid   = $this->R->id;
    $regionname = $this->R->name;

    // Filters and view options
    $groupfilter = isset($_GET['group'])    ? sanitize($_GET['group'])    : 'all';
    $absfilter   = isset($_GET['abs'])      ? sanitize($_GET['abs'])      : 'all';
    $viewmode    = isset($_GET['viewmode']) ? sanitize($_GET['viewmode']) : 'fullmonth';
    if ($viewmode !== 'fullmonth' && $viewmode !== 'splitmonth') {
      $viewmode = 'fullmonth';
    }
    $page = max(1, (int) filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]));

    // Build filtered user list (mirrors CalendarViewController::execute())
    $users = $this->U->getAllButHidden();

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
      if ($usr['username'] === $this->UL->username) {
        $allowed = true;
      }
      elseif (!$this->U->isHidden($usr['username'])) {
        if (isAllowed("calendarviewall") || (isAllowed("calendarviewgroup") && $this->UG->shareGroups($usr['username'], $this->UL->username))) {
          $allowed = true;
        }
      }
      if ($allowed)
        $allowedUsers[] = $usr;
    }
    $users = $allowedUsers;

    // Build month meta (one month / split-pair)
    $vmonth = $this->CalendarMonthBuilder->buildMonthMeta($year, $month, (string) $regionid, $regionname, $viewmode);

    // Ensure user template rows exist for this month
    foreach ($users as $user) {
      if (!$this->T->getTemplate($user['username'], $year, $month)) {
        createMonth($year, $month, 'user', $user['username']);
      }
    }

    // Minimal context subset passed into buildUserMonthRow / prepareDayData
    $rowContext = [
      'regionid'              => $regionid,
      'absfilter'             => ($absfilter !== 'all'),
      'absid'                 => $absfilter,
      'pastDayColor'          => $this->allConfig['pastDayColor'],
      'regionalHolidays'      => $this->C->read('regionalHolidays'),
      'regionalHolidaysColor' => $this->C->read('regionalHolidaysColor'),
    ];

    $trustedRoles         = explode(',', $this->allConfig['trustedRoles']);
    $countedUsersPerMonth = [];
    $userRows             = [];
    $monAbsConfig         = $this->C->read('monitorAbsence');

    foreach ($users as $usr) {
      $username = $usr['username'];
      $userRow  = [
        'username'    => $username,
        'fullName'    => $this->U->getLastFirst($username),
        'profileLink' => isAllowed($this->CONF['controllers']['viewprofile']->permission) ? 'index.php?action=viewprofile&profile=' . $username : null,
        'nameStyle'   => ($groupfilter !== 'all' && !$this->UG->isMemberOrManagerOfGroup($username, (string) $groupfilter)) ? 'm-name-guest' : 'm-name',
        'avatar'      => $this->allConfig['showAvatars'] ? $this->UO->read($username, 'avatar') : null,
        'roleIcon'    => $this->allConfig['showRoleIcons'] ? [
          'name'  => $this->RO->getNameById($this->U->getRole($username)),
          'color' => $this->RO->getColorById($this->U->getRole($username))
        ] : null,
        'groups'      => array_merge(array_keys($this->UG->getAllforUser2($username)), $this->UG->getGuestships($username)),
        'monitorAbs'  => null,
        'months'      => []
      ];

      if ($monAbsConfig) {
        $monAbsIds             = explode(',', (string) $monAbsConfig);
        $userRow['monitorAbs'] = [];
        foreach ($monAbsIds as $monAbsId) {
          if (empty($monAbsId))
            continue;
          $summary = $this->AbsenceService->getAbsenceSummary($username, (string) $monAbsId, $year);
          $monAbsIcon = $this->C->read('symbolAsIcon')
            ? $this->A->getSymbol((string) $monAbsId)
            : '<i class="' . $this->A->getIcon((string) $monAbsId) . '"></i>';
          $userRow['monitorAbs'][] = [
            'name'           => $this->A->getName((string) $monAbsId),
            'remainder'      => $summary['remainder'],
            'totalallowance' => $summary['totalallowance'],
            'icon'           => $monAbsIcon,
            'color'          => $this->A->getColor((string) $monAbsId)
          ];
        }
      }

      $monthKey                     = $vmonth['year'] . $vmonth['month'];
      $userRow['months'][$monthKey] = $this->CalendarMonthBuilder->buildUserMonthRow(
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
      'width'                    => $this->UO->read($this->UL->username, 'width') ?: 'full',
      'firstDayOfWeek'           => $this->allConfig['firstDayOfWeek'],
      'showWeekNumbers'          => $this->allConfig['showWeekNumbers'],
      'defgroupfilter'           => $defgroupfilter,
      'groups'                   => ($groupfilter === 'all' || $groupfilter === 'allbygroup') ? $this->G->getAll() : $this->G->getRowById($groupfilter),
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
