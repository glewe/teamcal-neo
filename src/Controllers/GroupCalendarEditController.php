<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\CalendarDayModel;
use App\Models\PatternModel;

/**
 * Group Calendar Edit Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class GroupCalendarEditController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    $viewData               = [];
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];

    $missingData = false;
    $yyyymm      = '';
    $region      = '';
    $calgroup    = '';

    if (isset($_GET['month']) && isset($_GET['region']) && isset($_GET['group'])) {
      $yyyymm            = sanitize($_GET['month']);
      $viewData['year']  = substr($yyyymm, 0, 4);
      $viewData['month'] = substr($yyyymm, 4, 2);

      if (!is_numeric($yyyymm) || strlen($yyyymm) != 6 || !checkdate(intval($viewData['month']), 1, intval($viewData['year']))) {
        $missingData = true;
      }

      $region = sanitize($_GET['region']);
      if (!$this->regionModel->getById($region)) {
        $missingData = true;
      }
      else {
        if ($this->regionModel->getAccess($this->regionModel->id, $this->userLoggedIn->getRole($this->userLoggedIn->username)) == 'view') {
          $this->regionModel->getById('1');
        }
        $viewData['regionid']   = $this->regionModel->id;
        $viewData['regionname'] = $this->regionModel->name;
      }

      $calgroup = sanitize($_GET['group']);
      if (!$this->groupModel->getById($calgroup)) {
        $missingData = true;
      }
      else {
        $viewData['groupid']   = $this->groupModel->id;
        $viewData['groupname'] = $this->groupModel->name;
      }
    }
    else {
      $missingData = true;
    }

    if ($missingData) {
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
      return;
    }

    if ($this->allConfig['currentYearOnly'] && $viewData['year'] != date('Y')) {
      header("Location: index.php?action=groupcalendaredit&month=" . date('Ym') . "&region=" . $region . "&group=" . $calgroup);
      die();
    }

    $allowed = false;
    if ($this->userGroupModel->isGroupManagerOfGroup($this->userLoggedIn->username, $calgroup)) {
      $allowed = true;
    }
    if (isAllowed($this->CONF['controllers']['groupcalendaredit']->permission)) {
      if ($this->userGroupModel->isMemberOrManagerOfGroup($this->userLoggedIn->username, (string) $calgroup)) {
        if (isAllowed("calendareditgroup")) {
          $allowed = true;
        }
      }
      else {
        if (isAllowed("calendareditall")) {
          $allowed = true;
        }
      }
    }

    if (!$allowed) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $patternModel                  = new PatternModel($this->dbModel->db, $this->CONF);
    $patterns             = $patternModel->getAll();
    $groups               = $this->groupModel->getAll();
    $users                = $this->userModel->getAll();
    $inputAlert           = [];
    $currDate             = date('Y-m-d');
    $viewData['dateInfo'] = dateInfo($viewData['year'], $viewData['month']);
    $viewData['M']        = CalendarDayModel::buildWeekdayGrid($viewData['year'], $viewData['month']);
    $holidayMap           = $this->calendarDayModel->getMonthMap($viewData['year'], $viewData['month'], $viewData['regionid']);

    // The group-level "current selection" starting point is never persisted
    // (there is no group pseudo-user in the redesigned schema) - it starts
    // blank each time the page loads, and only reflects this request's own
    // submission after a successful save.
    $viewData['currentAbsences'] = array_fill(1, $viewData['dateInfo']['daysInMonth'], 0);

    $alertData = [];
    $showAlert = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_save']) || isset($_POST['btn_clearall']) || isset($_POST['btn_saveperiod']) || isset($_POST['btn_saverecurring']) || isset($_POST['btn_savepattern'])) {
        $currentAbsences   = [];
        $requestedAbsences = [];
        $approvedAbsences  = [];
        $declinedAbsences  = [];

        for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
          $currentAbsences[$i]   = 0;
          $requestedAbsences[$i] = $currentAbsences[$i];
          $approvedAbsences[$i]  = '0';
          $declinedAbsences[$i]  = '0';
        }

        if (isset($_POST['btn_save'])) {
          for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
            $key = 'opt_abs_' . $i;
            if (isset($_POST[$key])) {
              $requestedAbsences[$i] = $_POST[$key];
            }
            else {
              $requestedAbsences[$i] = '0';
            }
          }
        }
        elseif (isset($_POST['btn_clearall'])) {
          for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
            $requestedAbsences[$i] = '0';
          }
        }
        elseif (isset($_POST['btn_savepattern'])) {
          $patternWeekdayMap = $patternModel->getWeekdayMap($_POST['sel_absencePattern']);
          for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
            $weekday = dateInfo($viewData['year'], $viewData['month'], (string) $i)['wday'];
            if (isset($_POST['chk_absencePatternSkipHolidays'])) {
              $holidayId = $holidayMap[$i] ?? 1;
              if ($holidayId !== 1 && !$this->holidayModel->isBusinessDay((string) $holidayId)) {
                $requestedAbsences[$i] = $currentAbsences[$i];
              }
              else {
                $requestedAbsences[$i] = $patternWeekdayMap[$weekday];
              }
            }
            else {
              $requestedAbsences[$i] = $patternWeekdayMap[$weekday];
            }
          }
        }
        elseif (isset($_POST['btn_saveperiod'])) {
          $inputError = false;
          if (!formInputValid('txt_periodStart', 'required|date'))
            $inputError = true;
          if (!formInputValid('txt_periodEnd', 'required|date'))
            $inputError = true;

          if (!$inputError) {
            $startPieces = explode("-", $_POST['txt_periodStart']);
            $endPieces   = explode("-", $_POST['txt_periodEnd']);
            if ($startPieces[0] == $viewData['year'] && $endPieces[0] == $viewData['year'] && $startPieces[1] == $viewData['month'] && $endPieces[1] == $viewData['month']) {
              $startDate = str_replace("-", "", $_POST['txt_periodStart']);
              $endDate   = str_replace("-", "", $_POST['txt_periodEnd']);
              for ($i = $startDate; $i <= $endDate; $i++) {
                $day                     = intval(substr((string) $i, 6, 2));
                $requestedAbsences[$day] = $_POST['sel_periodAbsence'];
              }
            }
            else {
              $showAlert            = true;
              $alertData['type']    = 'danger';
              $alertData['title']   = $this->LANG['alert_danger_title'];
              $alertData['subject'] = $this->LANG['alert_input'];
              $alertData['text']    = $this->LANG['caledit_alert_out_of_range'];
              $alertData['help']    = '';
            }
          }
          else {
            $showAlert            = true;
            $alertData['type']    = 'danger';
            $alertData['title']   = $this->LANG['alert_danger_title'];
            $alertData['subject'] = $this->LANG['alert_input'];
            $alertData['text']    = $this->LANG['caledit_alert_save_failed'];
            $alertData['help']    = '';
          }
        }
        elseif (isset($_POST['btn_saverecurring'])) {
          $startDate = $viewData['year'] . $viewData['month'] . '01';
          $endDate   = $viewData['year'] . $viewData['month'] . $viewData['dateInfo']['daysInMonth'];
          $wdays     = ['monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6, 'sunday' => 7];
          foreach ($_POST as $key => $value) {
            foreach ($wdays as $wday => $wdaynr) {
              if ($key == $wday) {
                for ($i = $startDate; $i <= $endDate; $i++) {
                  $day         = intval(substr((string) $i, 6, 2));
                  $loopDayInfo = dateInfo($viewData['year'], $viewData['month'], (string) $day);
                  if ($loopDayInfo['wday'] == $wdaynr)
                    $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
                }
              }
              elseif ($key == "workdays") {
                for ($i = $startDate; $i <= $endDate; $i++) {
                  $day         = intval(substr((string) $i, 6, 2));
                  $loopDayInfo = dateInfo($viewData['year'], $viewData['month'], (string) $day);
                  if ($loopDayInfo['wday'] >= 1 && $loopDayInfo['wday'] <= 5)
                    $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
                }
              }
              elseif ($key == "weekends") {
                for ($i = $startDate; $i <= $endDate; $i++) {
                  $day         = intval(substr((string) $i, 6, 2));
                  $loopDayInfo = dateInfo($viewData['year'], $viewData['month'], (string) $day);
                  if ($loopDayInfo['wday'] >= 6 && $loopDayInfo['wday'] <= 7)
                    $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
                }
              }
            }
          }
        }

        if (!$showAlert) {
          // No group pseudo-row to persist - reflect what was just applied
          // back into the page instead (see the note above).
          $viewData['currentAbsences'] = $requestedAbsences;

          $groupmembers = $this->userGroupModel->getAllForGroup((string) $calgroup);
          foreach ($groupmembers as $member) {
            $memberMap = $this->absenceDayModel->getMonthMap($member['username'], $viewData['year'], $viewData['month']);
            foreach ($requestedAbsences as $key => $val) {
              if ($memberMap[$key]) {
                if (!isset($_POST['chk_keepExisting'])) {
                  $memberMap[$key] = (int) $val;
                }
              }
              else {
                $memberMap[$key] = (int) $val;
              }
            }
            $this->absenceDayModel->setMonthMap($member['username'], $viewData['year'], $viewData['month'], $memberMap);
          }

          $this->logModel->logEvent("logUser", $this->userLoggedIn->username, "log_cal_grp_tpl_chg", $this->groupModel->name . ": " . $viewData['year'] . $viewData['month']);

          // sendUserCalEventNotifications() was never actually reachable here:
          // it matches recipients via UserGroupModel::getAllforUser(), which
          // returned nothing for the group's pseudo-username, so this path
          // never notified anyone even before the pseudo-row was removed.

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'] ?? 'SUCCESS';
          $alertData['subject'] = $this->LANG['caledit_alert_update'] ?? 'UPDATE';
          $alertData['text']    = $this->LANG['caledit_alert_update_group'] ?? 'UPDATE SUCCESS';
          if (isset($_POST['btn_clearall'])) {
            $alertData['text'] = $this->LANG['caledit_alert_update_group_cleared'] ?? 'UPDATE SUCCESS';
          }
          $alertData['help'] = '';
        }
      }
      elseif (isset($_POST['btn_region'])) {
        header("Location: index.php?action=groupcalendaredit&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $_POST['sel_region'] . "&group=" . $calgroup);
        die();
      }
      elseif (isset($_POST['btn_width'])) {
        $this->userOptionModel->save($this->userLoggedIn->username, 'width', $_POST['sel_width']);
        header("Location: index.php?action=groupcalendaredit&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $region . "&group=" . $calgroup);
        die();
      }
      elseif (isset($_POST['btn_group'])) {
        header("Location: index.php?action=groupcalendaredit&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $region . "&group=" . $_POST['sel_group']);
        die();
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $viewData['absences']  = $this->absenceModel->getAll();
    $viewData['holidays']  = $this->holidayModel->getAllCustom();
    $viewData['dayStyles'] = [];
    $viewData['patterns']  = $patterns;

    $allRegions = $this->regionModel->getAll();
    foreach ($allRegions as $reg) {
      if (!$this->regionModel->getAccess((string) $reg['id'], $this->userLoggedIn->getRole($this->userLoggedIn->username)) || $this->regionModel->getAccess((string) $reg['id'], $this->userLoggedIn->getRole($this->userLoggedIn->username)) == 'edit') {
        $viewData['regions'][] = $reg;
      }
    }

    $viewData['groups'] = [];
    foreach ($groups as $group) {
      $allowed = false;
      if ($this->userGroupModel->isMemberOrManagerOfGroup($this->userLoggedIn->username, (string) $group['id'])) {
        if (isAllowed("calendareditgroup"))
          $allowed = true;
      }
      else {
        if (isAllowed("calendareditall"))
          $allowed = true;
      }
      if ($allowed) {
        $viewData['groups'][] = ['id' => $group['id'], 'name' => $group['name']];
      }
    }

    for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
      $color                     = '';
      $bgcolor                   = '';
      $border                    = '';
      $viewData['dayStyles'][$i] = '';
      $holidayId                 = $holidayMap[$i] ?? 1;
      $weekday                   = $viewData['M']['wday' . $i];
      if ($holidayId !== 1) {
        $color   = 'color:#' . $this->holidayModel->getColor((string) $holidayId) . ';';
        $bgcolor = 'background-color:#' . $this->holidayModel->getBgColor((string) $holidayId) . ';';
      }
      elseif ($weekday == 6 || $weekday == 7) {
        $color   = 'color:#' . $this->holidayModel->getColor((string) ($weekday - 4)) . ';';
        $bgcolor = 'background-color:#' . $this->holidayModel->getBgColor((string) ($weekday - 4)) . ';';
      }

      $loopDate = date('Y-m-d', mktime(0, 0, 0, (int) $viewData['month'], $i, (int) $viewData['year']));
      if ($loopDate == $currDate) {
        $border = 'border-left: ' . $this->allConfig['todayBorderSize'] . 'px solid #' . $this->allConfig['todayBorderColor'] . ';border-right: ' . $this->allConfig['todayBorderSize'] . 'px solid #' . $this->allConfig['todayBorderColor'] . ';';
      }

      if ($color !== '' || $bgcolor !== '' || $border !== '') {
        $viewData['dayStyles'][$i] = ' style="' . $color . $bgcolor . $border . '"';
      }
    }

    $todayDate                   = getdate(time());
    $viewData['yearToday']       = $todayDate['year'];
    $viewData['monthToday']      = sprintf("%02d", $todayDate['mon']);
    $viewData['showWeekNumbers'] = $this->allConfig['showWeekNumbers'];
    $mobilecols['full']          = $viewData['dateInfo']['daysInMonth'];
    $viewData['supportMobile']   = $this->allConfig['supportMobile'];
    $viewData['firstDayOfWeek']  = $this->allConfig['firstDayOfWeek'];
    if (!$viewData['width'] = $this->userOptionModel->read($this->userLoggedIn->username, 'width')) {
      $this->userOptionModel->save($this->userLoggedIn->username, 'width', 'full');
      $viewData['width'] = 'full';
    }

    $viewData['showAlerts'] = $this->allConfig['showAlerts'];

    $this->render('groupcalendaredit', $viewData);
  }
}
