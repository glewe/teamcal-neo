<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;
use DateTime;

/**
 * Month Edit Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class MontheditController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    if (!isAllowed($this->CONF['controllers']['monthedit']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    // Check License
    // Checks when the current weekday matches a random number between 1 and 7 (legacy logic retained)
    $date    = new DateTime();
    $weekday = (int) $date->format('N');
    if ($weekday === rand(1, 7)) {
      $alertData        = [];
      $showAlert        = false;
      $licExpiryWarning = (int) $this->allConfig['licExpiryWarning'];
      $LIC              = new LicenseModel();
      $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);
      if ($showAlert) {
        // In legacy, this logic sets $alertData but continues or maybe shows it? 
        // Legacy wrapper just sets variables. We'll pass it to view if needed, 
        // or render alert page if critical. LicenseModel usually sets $alertData.
      }
    }

    // Check URL Parameters
    $viewData    = [];
    $missingData = false;
    $region      = '';

    if (isset($_GET['month']) && isset($_GET['region'])) {
      $yyyymm            = sanitize($_GET['month']);
      $region            = sanitize($_GET['region']);
      $viewData['year']  = substr($yyyymm, 0, 4);
      $viewData['month'] = substr($yyyymm, 4, 2);

      if (!is_numeric($yyyymm) || strlen($yyyymm) != 6 || !checkdate((int) $viewData['month'], 1, (int) $viewData['year'])) {
        $missingData = true;
      }

      if (!$this->R->getById($region)) {
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

    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];

    // Default back to current yearmonth if option is set
    if ($viewData['currentYearOnly'] && $viewData['year'] != date('Y')) {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=monthedit&month=" . date('Ym') . "&region=" . $region);
      die();
    }

    $viewData['regionid']   = $this->R->id;
    $viewData['regionname'] = $this->R->name;

    // See if a template exists. If not, create one.
    if (!$this->M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid'])) {
      createMonth($viewData['year'], $viewData['month'], 'region', $viewData['regionid']);
      $this->LOG->logEvent("logMonth", $this->UL->username, "log_month_tpl_created", $viewData['regionid'] . ": " . $viewData['year'] . "-" . $viewData['month']);
      // Re-fetch after creation
      $this->M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid']);
    }

    $viewData['dateInfo'] = dateInfo($viewData['year'], $viewData['month']);

    $alertData              = [];
    $showAlert              = false;
    $viewData['csrf_token'] = $_SESSION['csrf_token'] ?? '';

    // Process Form
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_save'])) {
        for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
          $key = 'opt_hol_' . $i;
          if (isset($_POST[$key])) {
            $this->M->setHoliday($viewData['year'], $viewData['month'], (string) $i, $viewData['regionid'], $_POST[$key]);
          }
        }

        if ($this->allConfig['emailNotifications']) {
          sendMonthEventNotifications("changed", $viewData['year'], $viewData['month'], $viewData['regionname']);
        }

        $this->LOG->logEvent("logMonth", $this->UL->username, "log_month_tpl_updated", $this->M->region . " " . $this->M->year . $this->M->month);

        $showAlert            = true;
        $alertData['type']    = 'success';
        $alertData['title']   = $this->LANG['alert_success_title'];
        $alertData['subject'] = $this->LANG['monthedit_alert_update'];
        $alertData['text']    = $this->LANG['monthedit_alert_update_success'];
        $alertData['help']    = '';

      }
      elseif (isset($_POST['btn_clearall'])) {
        $this->M->clearHolidays($viewData['year'], $viewData['month'], $viewData['regionid']);

        if ($this->allConfig['emailNotifications']) {
          sendMonthEventNotifications("changed", $viewData['year'], $viewData['month'], $viewData['regionname']);
        }

        $this->LOG->logEvent("logMonth", $this->UL->username, "log_month_tpl_updated", $this->M->region . " " . $this->M->year . $this->M->month);

        $showAlert            = true;
        $alertData['type']    = 'success';
        $alertData['title']   = $this->LANG['alert_success_title'];
        $alertData['subject'] = $this->LANG['monthedit_alert_update'];
        $alertData['text']    = $this->LANG['monthedit_alert_update_success'];
        $alertData['help']    = '';

      }
      elseif (isset($_POST['btn_region'])) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=monthedit&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $_POST['sel_region']);
        die();
      }

      if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $viewData['csrf_token'] = $_SESSION['csrf_token'];
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    // Prepare View Data
    // Re-load month in case of updates
    $this->M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid']);
    $viewData['holidays'] = $this->H->getAllCustom();

    $viewData['dayStyles'] = [];
    $holidayColorCache     = [];

    foreach ($viewData['holidays'] as $hol) {
      $holidayColorCache[$hol['id']] = [
        'color'   => $this->H->getColor((string) $hol['id']),
        'bgcolor' => $this->H->getBgColor((string) $hol['id'])
      ];
    }

    // Cache weekend colors
    for ($i = 2; $i <= 3; $i++) {
      $holidayColorCache[$i] = [
        'color'   => $this->H->getColor((string) $i),
        'bgcolor' => $this->H->getBgColor((string) $i)
      ];
    }

    for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
      $viewData['dayStyles'][$i] = '';
      $hprop                     = 'hol' . $i;
      $wprop                     = 'wday' . $i;

      if ($this->M->$hprop) {
        if (isset($holidayColorCache[$this->M->$hprop])) {
          $color                     = $holidayColorCache[$this->M->$hprop]['color'];
          $bgcolor                   = $holidayColorCache[$this->M->$hprop]['bgcolor'];
          $viewData['dayStyles'][$i] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';
        }
      }
      elseif ($this->M->$wprop == 6 || $this->M->$wprop == 7) {
        $weekendIndex = $this->M->$wprop - 4;
        if (isset($holidayColorCache[$weekendIndex])) {
          $color                     = $holidayColorCache[$weekendIndex]['color'];
          $bgcolor                   = $holidayColorCache[$weekendIndex]['bgcolor'];
          $viewData['dayStyles'][$i] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';
        }
      }
    }

    $todayDate                   = getdate(time());
    $viewData['yearToday']       = $todayDate['year'];
    $viewData['monthToday']      = sprintf("%02d", $todayDate['mon']);
    $viewData['regions']         = $this->R->getAll();
    $viewData['showWeekNumbers'] = $this->allConfig['showWeekNumbers'];
    $viewData['supportMobile']   = $this->allConfig['supportMobile'];

    $this->render('monthedit', $viewData);
  }
}
