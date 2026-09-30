<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\CalendarDayModel;
use App\Models\LicenseModel;
use App\Models\PatternModel;
use DateTime;

/**
 * Calendar Edit Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class CalendarEditController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    // Check URL Parameters
    $missingData = false;
    $yyyymm      = '';
    $region      = '';
    $caluser     = '';

    if (isset($_GET['month']) && isset($_GET['region']) && isset($_GET['user'])) {
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

      $caluser = sanitize($_GET['user']);
      if (!$this->userModel->findByName($caluser)) {
        $missingData = true;
      }
    }
    else {
      $missingData = true;
    }

    if ($missingData) {
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
      return;
    }

    $viewData['pageHelp']         = $this->allConfig['pageHelp'];
    $viewData['showAlerts']       = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly']  = $this->allConfig['currentYearOnly'];
    $viewData['currYearRoles']    = $this->allConfig['currYearRoles'];
    $viewData['showRegionButton'] = $this->allConfig['showRegionButton'];
    $viewData['takeover']         = $this->allConfig['takeover'];

    if ($viewData['currentYearOnly'] && $viewData['year'] != date('Y') && $viewData['currYearRoles']) {
      $arrCurrYearRoles = explode(',', $viewData['currYearRoles']);
      $userRole         = $this->userModel->getRole($this->userLoggedIn->username);
      if (in_array($userRole, $arrCurrYearRoles)) {
        header("Location: index.php?action=calendaredit&month=" . date('Ym') . "&region=" . $region . "&user=" . $caluser);
        die();
      }
    }

    // Check Permission
    $allowed = false;
    if (isAllowed($this->CONF['controllers']['calendaredit']->permission)) {
      if ($this->userLoggedIn->username == $caluser) {
        if (isAllowed("calendareditown")) {
          $allowed = true;
        }
      }
      elseif ($this->userGroupModel->shareGroupMemberships($this->userLoggedIn->username, $caluser)) {
        if (isAllowed("calendareditgroup") || (isAllowed("calendareditgroupmanaged") && $this->userGroupModel->isGroupManagerOfUser($this->userLoggedIn->username, $caluser))) {
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

    // Check License (Randomly)
    $date    = new DateTime();
    $weekday = $date->format('N');
    if ($weekday == (string) random_int(1, 7)) {
      $alertData        = [];
      $showAlert        = false;
      $licExpiryWarning = $this->allConfig['licExpiryWarning'];
      $LIC              = new LicenseModel();
      $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);
    }

    $patternModel                  = new PatternModel($this->dbModel->db, $this->CONF);
    $patterns             = $patternModel->getAll();
    $users                = $this->userModel->getAll();
    $inputAlert           = [];
    $currDate             = date('Y-m-d');
    $viewData['dateInfo'] = dateInfo($viewData['year'], $viewData['month']);
    $holidayMap           = $this->calendarDayModel->getMonthMap($viewData['year'], $viewData['month'], $viewData['regionid']);
    $weekdayGrid           = CalendarDayModel::buildWeekdayGrid($viewData['year'], $viewData['month']);

    $alertData = [];
    $showAlert = false;

    // Process Form
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

        $monthMap = $this->absenceDayModel->getMonthMap($caluser, $viewData['year'], $viewData['month']);
        for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
          $currentAbsences[$i]   = $monthMap[$i] ?? 0;
          $requestedAbsences[$i] = $currentAbsences[$i];
          $approvedAbsences[$i]  = '0';
          $declinedAbsences[$i]  = '0';
        }

        $doNotSave = false;

        if (isset($_POST['btn_save'])) {
          for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
            $key = 'opt_abs_' . $i;
            if (isset($_POST[$key])) {
              $requestedAbsences[$i] = $_POST[$key];
            }
            else {
              $requestedAbsences[$i] = $currentAbsences[$i];
            }
          }
        }
        elseif (isset($_POST['btn_clearall'])) {
          if (isset($_POST['chk_clearAbsences'])) {
            for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
              $requestedAbsences[$i] = '0';
            }
          }
          if (isset($_POST['chk_clearDaynotes'])) {
            for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
              $daynoteDate = $viewData['year'] . $viewData['month'] . sprintf("%02d", ($i));
              $this->daynoteModel->delete($daynoteDate, $caluser, $viewData['regionid']);
            }
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
          if (!formInputValid('txt_periodStart', 'required|date')) {
            $inputError = true;
          }
          if (!formInputValid('txt_periodEnd', 'required|date')) {
            $inputError = true;
          }
          if (!is_numeric($_POST['sel_periodAbsence'])) {
            $inputError = true;
          }

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
              $doNotSave            = true;
              $showAlert            = true;
              $alertData['type']    = 'danger';
              $alertData['title']   = $this->LANG['alert_danger_title'];
              $alertData['subject'] = $this->LANG['alert_input'];
              $alertData['text']    = $this->LANG['caledit_alert_out_of_range'];
              $alertData['help']    = '';
            }
          }
          else {
            $doNotSave            = true;
            $showAlert            = true;
            $alertData['type']    = 'danger';
            $alertData['title']   = $this->LANG['alert_danger_title'];
            $alertData['subject'] = $this->LANG['alert_input'];
            $alertData['text']    = $this->LANG['caledit_alert_save_failed'];
            $alertData['help']    = '';
          }
        }
        elseif (isset($_POST['btn_saverecurring']) && isset($_POST['sel_recurringAbsence']) && is_numeric($_POST['sel_recurringAbsence'])) {
          $startDate = $viewData['year'] . $viewData['month'] . '01';
          $endDate   = $viewData['year'] . $viewData['month'] . $viewData['dateInfo']['daysInMonth'];
          $wdays     = ['monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6, 'sunday' => 7];
          foreach ($_POST as $key => $value) {
            foreach ($wdays as $wday => $wdaynr) {
              if ($key == $wday) {
                for ($i = $startDate; $i <= $endDate; $i++) {
                  $day         = intval(substr((string) $i, 6, 2));
                  $loopDayInfo = dateInfo($viewData['year'], $viewData['month'], (string) $day);
                  if ($loopDayInfo['wday'] == $wdaynr) {
                    $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
                  }
                }
              }
              elseif ($key == "workdays") {
                for ($i = $startDate; $i <= $endDate; $i++) {
                  $day         = intval(substr((string) $i, 6, 2));
                  $loopDayInfo = dateInfo($viewData['year'], $viewData['month'], (string) $day);
                  if ($loopDayInfo['wday'] >= 1 && $loopDayInfo['wday'] <= 5) {
                    $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
                  }
                }
              }
              elseif ($key == "weekends") {
                for ($i = $startDate; $i <= $endDate; $i++) {
                  $day         = intval(substr((string) $i, 6, 2));
                  $loopDayInfo = dateInfo($viewData['year'], $viewData['month'], (string) $day);
                  if ($loopDayInfo['wday'] >= 6 && $loopDayInfo['wday'] <= 7) {
                    $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
                  }
                }
              }
            }
          }
        }

        if (!$doNotSave) {
          $mailError        = '';
          $approved         = $this->absenceService->approveAbsences($caluser, $viewData['year'], $viewData['month'], $currentAbsences, $requestedAbsences, $viewData['regionid'], $mailError);
          $approvalResult   = $approved['approvalResult'];
          $approvedAbsences = $approved['approvedAbsences'];

          $sendNotification = false;
          $alerttype        = 'success';
          $alertHelp        = '';
          $alertText        = $this->LANG['caledit_alert_update_' . $approvalResult];
          $logText          = '<br>';

          if (!empty($mailError)) {
            $alerttype = 'warning';
            $alertHelp = $this->LANG['contact_administrator'];
            $alertText = $this->LANG['caledit_alert_update_' . $approvalResult] . '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
          }

          switch ($approvalResult) {
            case 'all':
              $logText .= $this->LANG['approved'] . '<br>';
              foreach ($requestedAbsences as $key => $val) {
                if ($val) {
                  $logText .= '- ' . $viewData['year'] . $viewData['month'] . sprintf("%02d", $key) . ': ' . $this->absenceModel->getName((string) $val) . '<br>';
                }
              }
              $this->absenceDayModel->setMonthMap($caluser, $viewData['year'], $viewData['month'], $requestedAbsences);
              $sendNotification = true;
              break;
            case 'partial':
              $logText .= $this->LANG['partially_approved'] . '<br>';
              foreach ($approved['approvedAbsences'] as $key => $val) {
                if ($val) {
                  $logText .= '- ' . $viewData['year'] . $viewData['month'] . sprintf("%02d", $key) . ': ' . $this->absenceModel->getName((string) $val) . '<br>';
                }
              }
              $this->absenceDayModel->setMonthMap($caluser, $viewData['year'], $viewData['month'], $approved['approvedAbsences']);
              $sendNotification = true;
              $alerttype = 'info';
              foreach ($approved['declinedReasons'] as $reason) {
                if (strlen($reason)) {
                  $alertHelp .= $reason . "<br>";
                }
              }
              foreach ($approved['declinedReasonsLog'] as $reason) {
                if (strlen($reason)) {
                  $logText .= "<i>" . $reason . "</i><br>";
                }
              }
              break;
            default:
              $alerttype = 'info';
              break;
          }

          $mailError = '';
          if ($this->allConfig['emailNotifications'] && $sendNotification) {
            sendUserCalEventNotifications("changed", $caluser, $viewData['year'], $viewData['month'], $mailError);
          }

          $this->logModel->logEvent("logCalendar", $this->userLoggedIn->username, "log_cal_usr_tpl_chg", $caluser . " " . $viewData['year'] . $viewData['month'] . $logText);

          if (isset($_SESSION)) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
          }

          $showAlert            = true;
          $alertData['type']    = (empty($mailError)) ? $alerttype : 'warning';
          $alertData['title']   = (empty($mailError)) ? $this->LANG['alert_' . $alerttype . '_title'] : $this->LANG['alert_warning_title'];
          $alertData['subject'] = $this->LANG['caledit_alert_update'];
          $alertData['text']    = $this->LANG['caledit_alert_update_' . $approved['approvalResult']];
          if (!empty($mailError)) {
            $alertData['text'] .= '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
          }
          $alertData['help']    = (empty($mailError)) ? $alertHelp : $this->LANG['contact_administrator'];
        }
      }
      elseif (isset($_POST['btn_region'])) {
        header("Location: index.php?action=calendaredit&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $_POST['sel_region'] . "&user=" . $caluser);
        die();
      }
      elseif (isset($_POST['btn_width'])) {
        $this->userOptionModel->save($this->userLoggedIn->username, 'width', $_POST['sel_width']);
        header("Location: index.php?action=calendaredit&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $region . "&user=" . $caluser);
        die();
      }
      elseif (isset($_POST['btn_user'])) {
        header("Location: index.php?action=calendaredit&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $region . "&user=" . $_POST['sel_user']);
        die();
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    // Prepare View
    $viewData['username']  = $caluser;
    $viewData['fullname']  = $this->userModel->getFullname($caluser);
    $viewData['absences']  = $this->absenceModel->getAll();
    $viewData['holidays']  = $this->holidayModel->getAllCustom();
    $viewData['dayStyles'] = [];
    $viewData['patterns']  = $patterns;

    $absenceColorCache = [];
    foreach ($viewData['absences'] as $abs) {
      $absenceColorCache[$abs['id']] = ['color' => $this->absenceModel->getColor((string) $abs['id']), 'bgcolor' => $this->absenceModel->getBgColor((string) $abs['id'])];
    }

    $holidayColorCache = [];
    foreach ($viewData['holidays'] as $hol) {
      $holidayColorCache[$hol['id']] = ['color' => $this->holidayModel->getColor((string) $hol['id']), 'bgcolor' => $this->holidayModel->getBgColor((string) $hol['id'])];
    }
    for ($i = 2; $i <= 3; $i++) {
      $holidayColorCache[$i] = ['color' => $this->holidayModel->getColor((string) $i), 'bgcolor' => $this->holidayModel->getBgColor((string) $i)];
    }

    $usergroups             = $this->userGroupModel->getAllforUser($caluser);
    $viewData['groupnames'] = " <span style=\"font-weight:normal;\">(";
    foreach ($usergroups as $ug) {
      $viewData['groupnames'] .= $this->groupModel->getNameByID((string) $ug['groupid']) . ", ";
    }
    $viewData['groupnames'] = substr($viewData['groupnames'], 0, -2) . ")</span>";

    $allRegions = $this->regionModel->getAll();
    foreach ($allRegions as $reg) {
      if (!$this->regionModel->getAccess((string) $reg['id'], $this->userLoggedIn->getRole($this->userLoggedIn->username)) || $this->regionModel->getAccess((string) $reg['id'], $this->userLoggedIn->getRole($this->userLoggedIn->username)) == 'edit') {
        $viewData['regions'][] = $reg;
      }
    }

    $viewData['users'] = [];
    foreach ($users as $usr) {
      $allowed = false;
      if ($usr['username'] == $this->userLoggedIn->username && isAllowed("calendareditown")) {
        $allowed = true;
      }
      elseif (!$this->userModel->isHidden($usr['username'])) {
        if (isAllowed("calendareditall") || (isAllowed("calendareditgroup") && $this->userGroupModel->shareGroups($usr['username'], $this->userLoggedIn->username))) {
          $allowed = true;
        }
      }
      if ($allowed) {
        $viewData['users'][] = ['username' => $usr['username'], 'lastfirst' => $usr['lastname'] . ', ' . $usr['firstname']];
      }
    }

    for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
      $color                     = '';
      $bgcolor                   = '';
      $border                    = '';
      $viewData['dayStyles'][$i] = '';
      $holidayId                 = $holidayMap[$i] ?? 1;
      $weekday                   = $weekdayGrid['wday' . $i];
      if ($holidayId !== 1) {
        if (isset($holidayColorCache[$holidayId])) {
          $color   = 'color:#' . $holidayColorCache[$holidayId]['color'] . ';';
          $bgcolor = 'background-color:#' . $holidayColorCache[$holidayId]['bgcolor'] . ';';
        }
      }
      elseif ($weekday == 6 || $weekday == 7) {
        $weekendIndex = $weekday - 4;
        if (isset($holidayColorCache[$weekendIndex])) {
          $color   = 'color:#' . $holidayColorCache[$weekendIndex]['color'] . ';';
          $bgcolor = 'background-color:#' . $holidayColorCache[$weekendIndex]['bgcolor'] . ';';
        }
      }
      $loopDate = date('Y-m-d', mktime(0, 0, 0, (int) $viewData['month'], $i, (int) $viewData['year']));
      if ($loopDate == $currDate) {
        $border = 'border-left: ' . $this->allConfig['todayBorderSize'] . 'px solid #' . $this->allConfig['todayBorderColor'] . ';border-right: ' . $this->allConfig['todayBorderSize'] . 'px solid #' . $this->allConfig['todayBorderColor'] . ';';
      }
      if (strlen($color) || strlen($bgcolor) || strlen($border)) {
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

    $viewMonthMap             = $this->absenceDayModel->getMonthMap($viewData['username'], $viewData['year'], $viewData['month']);
    $viewData['calendarDays'] = [];
    $currDate                 = date('Y-m-d');
    for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
      $day                     = [];
      $day['num']              = $i;
      $day['style']            = $viewData['dayStyles'][$i];
      $day['weekday']          = $weekdayGrid['wday' . $i];
      $day['weeknum']          = $weekdayGrid['week' . $i];
      $day['isFirstDayOfWeek'] = ($weekdayGrid['wday' . $i] == $viewData['firstDayOfWeek']);
      $day['date']             = $viewData['year'] . $viewData['month'] . sprintf('%02d', $i);
      $day['loopDate']         = date('Y-m-d', mktime(0, 0, 0, (int) $viewData['month'], $i, (int) $viewData['year']));
      $day['isToday']          = ($day['loopDate'] == date('Y-m-d'));
      $day['currentAbsence']   = $viewMonthMap[$i] ?? 0;
      if ($day['currentAbsence']) {
        if (isset($absenceColorCache[$day['currentAbsence']])) {
          $color   = 'color:#' . $absenceColorCache[$day['currentAbsence']]['color'] . ';';
          $bgcolor = 'background-color:#' . $absenceColorCache[$day['currentAbsence']]['bgcolor'] . ';';
        }
        else {
          $color   = '';
          $bgcolor = '';
        }
        $border = '';
        if ($day['isToday']) {
          $border = 'border-left: ' . $this->allConfig['todayBorderSize'] . 'px solid #' . $this->allConfig['todayBorderColor'] . ';border-right: ' . $this->allConfig['todayBorderSize'] . 'px solid #' . $this->allConfig['todayBorderColor'] . ';';
        }
        $day['absenceStyle'] = ' style="' . $color . $bgcolor . $border . '"';
        $day['absenceIcon']  = $this->allConfig['symbolAsIcon'] ? $this->absenceModel->getSymbol((string) $day['currentAbsence']) : '<span class="' . $this->absenceModel->getIcon((string) $day['currentAbsence']) . '"></span>';
      }
      else {
        $day['absenceStyle'] = $day['style'];
        $day['absenceIcon']  = '';
      }
      if ($this->daynoteModel->get($day['date'], $viewData['username'], $viewData['regionid'], true)) {
        $day['daynoteIcon']    = 'fas fa-sticky-note';
        $day['daynoteTooltip'] = ' data-placement="top" data-type="' . $this->daynoteModel->color . '" data-bs-toggle="tooltip" title="' . $this->daynoteModel->daynote . '"';
      }
      else {
        $day['daynoteIcon']    = 'far fa-sticky-note';
        $day['daynoteTooltip'] = '';
      }
      $viewData['calendarDays'][$i] = $day;
    }

    $isGroupManager     = $this->userGroupModel->isGroupManagerOfUser($this->userLoggedIn->username, $viewData['username']);
    $isAdmin            = (bool) $this->userLoggedIn->is_system;
    $hasManagerOnlyRole = isAllowed('manageronlyabsences');
    $isAdminRole        = ($this->allConfig['managerOnlyIncludesAdministrator'] && $this->userLoggedIn->hasRole($this->userLoggedIn->username, '1'));

    $viewData['absencesForUser'] = [];
    foreach ($viewData['absences'] as $abs) {
      $valid                         = ($isAdmin || $this->userService->absenceIsValidForUser((string) $abs['id'], (string) $this->userLoggedIn->username) && (!$abs['manager_only'] || $isGroupManager || $hasManagerOnlyRole || $isAdminRole));
      $abs['validForUser']           = $valid;
      $viewData['absencesForUser'][] = $abs;
    }

    $viewData['absenceRows'] = [];
    foreach ($viewData['absencesForUser'] as $abs) {
      if ($abs['validForUser']) {
        $row = ['id' => $abs['id'], 'name' => $abs['name'], 'days' => []];
        for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
          $row['days'][$i] = [
            'checked' => (($viewMonthMap[$i] ?? 0) == $abs['id']),
            'style'   => $viewData['calendarDays'][$i]['style'],
            'num'     => $i
          ];
        }
        $viewData['absenceRows'][] = $row;
      }
    }

    $this->render('calendaredit', $viewData);
  }
}
