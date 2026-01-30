<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
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

    $missingData  = false;
    $yyyymm       = '';
    $region       = '';
    $calgroup     = '';
    $calgroupuser = '';

    if (isset($_GET['month']) && isset($_GET['region']) && isset($_GET['group'])) {
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

      $calgroup     = sanitize($_GET['group']);
      $calgroupuser = 'group:' . $calgroup;
      if (!$this->G->getById($calgroup)) {
        $missingData = true;
      }
      else {
        $viewData['groupid']       = $this->G->id;
        $viewData['groupname']     = $this->G->name;
        $viewData['groupusername'] = $calgroupuser;
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
    if ($this->UG->isGroupManagerOfGroup($this->UL->username, $calgroup)) {
      $allowed = true;
    }
    if (isAllowed($this->CONF['controllers']['groupcalendaredit']->permission)) {
      if ($this->UG->isMemberOrManagerOfGroup($this->UL->username, (string) $calgroup)) {
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

    $PTN                  = new PatternModel($this->DB->db, $this->CONF);
    $patterns             = $PTN->getAll();
    $groups               = $this->G->getAll();
    $users                = $this->U->getAll();
    $inputAlert           = [];
    $currDate             = date('Y-m-d');
    $viewData['dateInfo'] = dateInfo($viewData['year'], $viewData['month']);

    if (!$this->M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid'])) {
      createMonth($viewData['year'], $viewData['month'], 'region', $viewData['regionid']);
      $this->M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid']);
      $this->LOG->logEvent("logMonth", $this->UL->username, "log_month_tpl_created", $this->M->region . ": " . $this->M->year . "-" . $this->M->month);
    }

    if (!$this->T->getTemplate($calgroupuser, $viewData['year'], $viewData['month'])) {
      createMonth($viewData['year'], $viewData['month'], 'user', $calgroupuser);
      $this->T->getTemplate($calgroupuser, $viewData['year'], $viewData['month']);
      $this->LOG->logEvent("logMonth", $this->UL->username, "log_month_tpl_created", "Group: " . $this->G->name . ": " . $this->M->year . "-" . $this->M->month);
    }

    $alertData = [];
    $showAlert = false;

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
          $currentAbsences[$i]   = $this->T->getAbsence($calgroupuser, $viewData['year'], $viewData['month'], (string) $i);
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
          foreach ($requestedAbsences as $key => $val) {
            $col           = 'abs' . $key;
            $this->T->$col = (int) $val;
          }
          $this->T->update($calgroupuser, $viewData['year'], $viewData['month']);

          $groupmembers = $this->UG->getAllForGroup((string) $calgroup);
          foreach ($groupmembers as $member) {
            if ($this->T->getTemplate($member['username'], $viewData['year'], $viewData['month'])) {
              foreach ($requestedAbsences as $key => $val) {
                $col = 'abs' . $key;
                if ($this->T->$col) {
                  if (!isset($_POST['chk_keepExisting'])) {
                    $this->T->$col = (int) $val;
                  }
                }
                else {
                  $this->T->$col = (int) $val;
                }
              }
              $this->T->update($member['username'], $viewData['year'], $viewData['month']);
            }
          }

          if ($this->allConfig['emailNotifications']) {
            sendUserCalEventNotifications("changed", $calgroupuser, $viewData['year'], $viewData['month']);
          }

          $this->LOG->logEvent("logUser", $this->UL->username, "log_cal_grp_tpl_chg", $this->G->name . ": " . $viewData['year'] . $viewData['month']);

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
        $this->UO->save($this->UL->username, 'width', $_POST['sel_width']);
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

    $viewData['absences']  = $this->A->getAll();
    $viewData['holidays']  = $this->H->getAllCustom();
    $viewData['dayStyles'] = [];
    $viewData['patterns']  = $patterns;

    $allRegions = $this->R->getAll();
    foreach ($allRegions as $reg) {
      if (!$this->R->getAccess((string) $reg['id'], $this->UL->getRole($this->UL->username)) || $this->R->getAccess((string) $reg['id'], $this->UL->getRole($this->UL->username)) == 'edit') {
        $viewData['regions'][] = $reg;
      }
    }

    $viewData['groups'] = [];
    foreach ($groups as $group) {
      $allowed = false;
      if ($this->UG->isMemberOrManagerOfGroup($this->UL->username, (string) $group['id'])) {
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
      $hprop                     = 'hol' . $i;
      $wprop                     = 'wday' . $i;
      if ($this->M->$hprop) {
        $color   = 'color:#' . $this->H->getColor((string) $this->M->$hprop) . ';';
        $bgcolor = 'background-color:#' . $this->H->getBgColor((string) $this->M->$hprop) . ';';
      }
      elseif ($this->M->$wprop == 6 || $this->M->$wprop == 7) {
        $color   = 'color:#' . $this->H->getColor((string) ($this->M->$wprop - 4)) . ';';
        $bgcolor = 'background-color:#' . $this->H->getBgColor((string) ($this->M->$wprop - 4)) . ';';
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

    $viewData['showAlerts'] = $this->allConfig['showAlerts'];

    $this->render('groupcalendaredit', $viewData);
  }
}
