<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;
use App\Models\HolidayModel;
use DateTime;

/**
 * Holidays Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class HolidaysController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['holidays']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    // Check License
    $alertData        = [];
    $showAlert        = false;
    $licExpiryWarning = (int) $this->allConfig['licExpiryWarning'];
    $LIC              = new LicenseModel($this->DB->db, $this->CONF);
    $date             = new DateTime();
    $weekday          = (int) $date->format('N');
    if ($weekday === random_int(1, 7)) {
      $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);
    }

    $viewData                    = [];
    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['txt_name']        = '';
    $viewData['txt_description'] = '';
    $viewData['csrf_token']      = $_SESSION['csrf_token'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_holCreate'])) {
        $inputError = false;
        if (!formInputValid('txt_name', 'required|alpha_numeric_dash_blank'))
          $inputError = true;
        if (!formInputValid('txt_description', 'alpha_numeric_dash_blank'))
          $inputError = true;

        $viewData['txt_name']        = $_POST['txt_name'] ?? '';
        $viewData['txt_description'] = $_POST['txt_description'] ?? '';

        if (!$inputError) {
          $HH              = new HolidayModel($this->DB->db, $this->CONF);
          $HH->name        = $viewData['txt_name'];
          $HH->description = $viewData['txt_description'];
          $HH->create();

          $mailError = '';
          if ($this->allConfig['emailNotifications']) {
            sendHolidayEventNotifications("created", $HH->name, $HH->description, $mailError);
          }
          $this->LOG->logEvent("logHoliday", $this->UL->username, "log_abs_created", $HH->name);

          $showAlert            = true;
          $alertData['type']    = (empty($mailError)) ? 'success' : 'warning';
          $alertData['title']   = (empty($mailError)) ? $this->LANG['alert_success_title'] : $this->LANG['alert_warning_title'];
          $alertData['subject'] = $this->LANG['btn_create_abs'];
          $alertData['text']    = $this->LANG['hol_alert_created'];
          if (!empty($mailError)) {
            $alertData['text'] .= '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
          }
          $alertData['help'] = (empty($mailError)) ? '' : $this->LANG['contact_administrator'];
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
          $viewData['csrf_token'] = $_SESSION['csrf_token'];
        }
        else {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['btn_create_abs'];
          $alertData['text']    = $this->LANG['hol_alert_created_fail'];
          $alertData['help']    = '';
        }
      }
      elseif (isset($_POST['btn_holDelete'])) {
        $this->H->delete($_POST['hidden_id'] ?? '');
        $mailError = '';
        if ($this->allConfig['emailNotifications']) {
          sendHolidayEventNotifications("deleted", $_POST['hidden_name'] ?? '', $_POST['hidden_description'] ?? '', $mailError);
        }
        $this->LOG->logEvent("logHoliday", $this->UL->username, "log_hol_deleted", $_POST['hidden_name'] ?? '');

        $showAlert            = true;
        $alertData['type']    = (empty($mailError)) ? 'success' : 'warning';
        $alertData['title']   = (empty($mailError)) ? $this->LANG['alert_success_title'] : $this->LANG['alert_warning_title'];
        $alertData['subject'] = $this->LANG['btn_delete_holiday'];
        $alertData['text']    = $this->LANG['hol_alert_deleted'];
        if (!empty($mailError)) {
          $alertData['text'] .= '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
        }
        $alertData['help'] = (empty($mailError)) ? '' : $this->LANG['contact_administrator'];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $viewData['csrf_token'] = $_SESSION['csrf_token'];
      }
    }

    $viewData['alertData'] = $alertData;
    $viewData['showAlert'] = $showAlert;
    $viewData['holidays']  = $this->H->getAll();
    asort($viewData['holidays']);

    $this->render('holidays', $viewData);
  }
}
