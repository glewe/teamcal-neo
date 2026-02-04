<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
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
      if (!$this->R->getById($region)) {
        $missingData = true;
      }
      else {
        if ($this->R->getAccess($this->R->id, $this->UL->getRole($this->UL->username)) == 'view') {
          $this->R->getById('1');
        }
        $viewData['regionid']   = $this->R->id;
        $viewData['regionname'] = $this->R->name;
      }

      $caluser = sanitize($_GET['user']);
      if (!$this->U->findByName($caluser)) {
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
      $userRole         = $this->U->getRole($this->UL->username);
      if (in_array($userRole, $arrCurrYearRoles)) {
        header("Location: index.php?action=calendaredit&month=" . date('Ym') . "&region=" . $region . "&user=" . $caluser);
        die();
      }
    }

    // Check Permission
    $allowed = false;
    if (isAllowed($this->CONF['controllers']['calendaredit']->permission)) {
      if ($this->UL->username == $caluser) {
        if (isAllowed("calendareditown")) {
          $allowed = true;
        }
      }
      elseif ($this->UG->shareGroupMemberships($this->UL->username, $caluser)) {
        if (isAllowed("calendareditgroup") || (isAllowed("calendareditgroupmanaged") && $this->UG->isGroupManagerOfUser($this->UL->username, $caluser))) {
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

    $PTN                  = new PatternModel($this->DB->db, $this->CONF);
    $patterns             = $PTN->getAll();
    $users                = $this->U->getAll();
    $inputAlert           = [];
    $currDate             = date('Y-m-d');
    $viewData['dateInfo'] = dateInfo($viewData['year'], $viewData['month']);

    if (!$this->M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid'])) {
      createMonth($viewData['year'], $viewData['month'], 'region', $viewData['regionid']);
      $this->M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid']);
      $this->LOG->logEvent("logMonth", $this->UL->username, "log_month_tpl_created", $this->M->region . ": " . $this->M->year . "-" . $this->M->month);
    }

    if (!$this->T->getTemplate($caluser, $viewData['year'], $viewData['month'])) {
      createMonth($viewData['year'], $viewData['month'], 'user', $caluser);
      $this->T->getTemplate($caluser, $viewData['year'], $viewData['month']);
      $this->LOG->logEvent("logMonth", $this->UL->username, "log_month_tpl_created", $caluser . ": " . $this->M->year . "-" . $this->M->month);
    }

    $alertData = [];
    $showAlert = false;

    // Process Form
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_save']) || isset($_POST['btn_clearall']) || isset($_POST['btn_saveperiod']) || isset($_POST['btn_saverecurring']) || isset($_POST['btn_savepattern'])) {
        $currentAbsences   = [];
        $requestedAbsences = [];
        $approvedAbsences  = [];
        $declinedAbsences  = [];

        for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
          $currentAbsences[$i]   = $this->T->getAbsence($caluser, $viewData['year'], $viewData['month'], (string) $i);
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
              $this->D->delete($daynoteDate, $caluser, $viewData['regionid']);
            }
          }
        }
        elseif (isset($_POST['btn_savepattern'])) {
          $PTN->get($_POST['sel_absencePattern']);
          for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
            $weekday = dateInfo($viewData['year'], $viewData['month'], (string) $i)['wday'];
            $prop    = 'abs' . $weekday;
            if (isset($_POST['chk_absencePatternSkipHolidays'])) {
              $hprop = 'hol' . $i;
              if ($this->M->$hprop && !$this->H->isBusinessDay($this->M->$hprop)) {
                $requestedAbsences[$i] = $currentAbsences[$i];
              }
              else {
                $requestedAbsences[$i] = $PTN->$prop;
              }
            }
            else {
              $requestedAbsences[$i] = $PTN->$prop;
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
          $approved         = $this->AbsenceService->approveAbsences($caluser, $viewData['year'], $viewData['month'], $currentAbsences, $requestedAbsences, $viewData['regionid']);
          $sendNotification = false;
          $alerttype        = 'success';
          $alertHelp        = '';
          $logText          = '<br>';

          switch ($approved['approvalResult']) {
            case 'all':
              $logText .= $this->LANG['approved'] . '<br>';
              foreach ($requestedAbsences as $key => $val) {
                $col           = 'abs' . $key;
                $this->T->$col = (int) $val;
                if ($val) {
                  $logText .= '- ' . $viewData['year'] . $viewData['month'] . sprintf("%02d", $key) . ': ' . $this->A->getName((string) $val) . '<br>';
                }
              }
              $this->T->update($caluser, $viewData['year'], $viewData['month']);
              $sendNotification = true;
              break;
            case 'partial':
              $logText .= $this->LANG['partially_approved'] . '<br>';
              foreach ($approved['approvedAbsences'] as $key => $val) {
                $col           = 'abs' . $key;
                $this->T->$col = (int) $val;
                if ($val) {
                  $logText .= '- ' . $viewData['year'] . $viewData['month'] . sprintf("%02d", $key) . ': ' . $this->A->getName((string) $val) . '<br>';
                }
              }
              $this->T->update($caluser, $viewData['year'], $viewData['month']);
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

          if ($this->allConfig['emailNotifications'] && $sendNotification) {
            sendUserCalEventNotifications("changed", $caluser, $viewData['year'], $viewData['month']);
          }

          $this->LOG->logEvent("logCalendar", $this->UL->username, "log_cal_usr_tpl_chg", $caluser . " " . $viewData['year'] . $viewData['month'] . $logText);

          if (isset($_SESSION)) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
          }

          $showAlert            = true;
          $alertData['type']    = $alerttype;
          $alertData['title']   = $this->LANG['alert_' . $alerttype . '_title'];
          $alertData['subject'] = $this->LANG['caledit_alert_update'];
          $alertData['text']    = $this->LANG['caledit_alert_update_' . $approved['approvalResult']];
          $alertData['help']    = $alertHelp;
        }
      }
      elseif (isset($_POST['btn_region'])) {
        header("Location: index.php?action=calendaredit&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $_POST['sel_region'] . "&user=" . $caluser);
        die();
      }
      elseif (isset($_POST['btn_width'])) {
        $this->UO->save($this->UL->username, 'width', $_POST['sel_width']);
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
    $viewData['fullname']  = $this->U->getFullname($caluser);
    $viewData['absences']  = $this->A->getAll();
    $viewData['holidays']  = $this->H->getAllCustom();
    $viewData['dayStyles'] = [];
    $viewData['patterns']  = $patterns;

    $absenceColorCache = [];
    foreach ($viewData['absences'] as $abs) {
      $absenceColorCache[$abs['id']] = ['color' => $this->A->getColor((string) $abs['id']), 'bgcolor' => $this->A->getBgColor((string) $abs['id'])];
    }

    $holidayColorCache = [];
    foreach ($viewData['holidays'] as $hol) {
      $holidayColorCache[$hol['id']] = ['color' => $this->H->getColor((string) $hol['id']), 'bgcolor' => $this->H->getBgColor((string) $hol['id'])];
    }
    for ($i = 2; $i <= 3; $i++) {
      $holidayColorCache[$i] = ['color' => $this->H->getColor((string) $i), 'bgcolor' => $this->H->getBgColor((string) $i)];
    }

    $usergroups             = $this->UG->getAllforUser($caluser);
    $viewData['groupnames'] = " <span style=\"font-weight:normal;\">(";
    foreach ($usergroups as $ug) {
      $viewData['groupnames'] .= $this->G->getNameByID((string) $ug['groupid']) . ", ";
    }
    $viewData['groupnames'] = substr($viewData['groupnames'], 0, -2) . ")</span>";

    $allRegions = $this->R->getAll();
    foreach ($allRegions as $reg) {
      if (!$this->R->getAccess((string) $reg['id'], $this->UL->getRole($this->UL->username)) || $this->R->getAccess((string) $reg['id'], $this->UL->getRole($this->UL->username)) == 'edit') {
        $viewData['regions'][] = $reg;
      }
    }

    $viewData['users'] = [];
    foreach ($users as $usr) {
      $allowed = false;
      if ($usr['username'] == $this->UL->username && isAllowed("calendareditown")) {
        $allowed = true;
      }
      elseif (!$this->U->isHidden($usr['username'])) {
        if (isAllowed("calendareditall") || (isAllowed("calendareditgroup") && $this->UG->shareGroups($usr['username'], $this->UL->username))) {
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
      $hprop                     = 'hol' . $i;
      $wprop                     = 'wday' . $i;
      if ($this->M->$hprop) {
        if (isset($holidayColorCache[$this->M->$hprop])) {
          $color   = 'color:#' . $holidayColorCache[$this->M->$hprop]['color'] . ';';
          $bgcolor = 'background-color:#' . $holidayColorCache[$this->M->$hprop]['bgcolor'] . ';';
        }
      }
      elseif ($this->M->$wprop == 6 || $this->M->$wprop == 7) {
        $weekendIndex = $this->M->$wprop - 4;
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
    if (!$viewData['width'] = $this->UO->read($this->UL->username, 'width')) {
      $this->UO->save($this->UL->username, 'width', 'full');
      $viewData['width'] = 'full';
    }

    $viewData['calendarDays'] = [];
    $currDate                 = date('Y-m-d');
    for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
      $day                     = [];
      $day['num']              = $i;
      $day['style']            = $viewData['dayStyles'][$i];
      $day['weekday']          = $this->M->{'wday' . $i};
      $day['weeknum']          = $this->M->{'week' . $i};
      $day['isFirstDayOfWeek'] = ($this->M->{'wday' . $i} == $viewData['firstDayOfWeek']);
      $day['date']             = $viewData['year'] . $viewData['month'] . sprintf('%02d', $i);
      $day['loopDate']         = date('Y-m-d', mktime(0, 0, 0, (int) $viewData['month'], $i, (int) $viewData['year']));
      $day['isToday']          = ($day['loopDate'] == date('Y-m-d'));
      $day['currentAbsence']   = $this->T->getAbsence($viewData['username'], $viewData['year'], $viewData['month'], (string) $i);
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
        $day['absenceIcon']  = $this->allConfig['symbolAsIcon'] ? $this->A->getSymbol((string) $day['currentAbsence']) : '<span class="' . $this->A->getIcon((string) $day['currentAbsence']) . '"></span>';
      }
      else {
        $day['absenceStyle'] = $day['style'];
        $day['absenceIcon']  = '';
      }
      if ($this->D->get($day['date'], $viewData['username'], $viewData['regionid'], true)) {
        $day['daynoteIcon']    = 'fas fa-sticky-note';
        $day['daynoteTooltip'] = ' data-placement="top" data-type="' . $this->D->color . '" data-bs-toggle="tooltip" title="' . $this->D->daynote . '"';
      }
      else {
        $day['daynoteIcon']    = 'far fa-sticky-note';
        $day['daynoteTooltip'] = '';
      }
      $viewData['calendarDays'][$i] = $day;
    }

    $isGroupManager     = $this->UG->isGroupManagerOfUser($this->UL->username, $viewData['username']);
    $isAdmin            = ($this->UL->username == 'admin');
    $hasManagerOnlyRole = isAllowed('manageronlyabsences');
    $isAdminRole        = ($this->allConfig['managerOnlyIncludesAdministrator'] && $this->UL->hasRole($this->UL->username, '1'));

    $viewData['absencesForUser'] = [];
    foreach ($viewData['absences'] as $abs) {
      $valid                         = ($isAdmin || $this->UserService->absenceIsValidForUser((string) $abs['id'], (string) $this->UL->username) && (!$abs['manager_only'] || $isGroupManager || $hasManagerOnlyRole || $isAdminRole));
      $abs['validForUser']           = $valid;
      $viewData['absencesForUser'][] = $abs;
    }

    $viewData['absenceRows'] = [];
    foreach ($viewData['absencesForUser'] as $abs) {
      if ($abs['validForUser']) {
        $row = ['id' => $abs['id'], 'name' => $abs['name'], 'days' => []];
        for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
          $prop            = 'abs' . $i;
          $row['days'][$i] = [
            'checked' => ($this->T->$prop == $abs['id']),
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
