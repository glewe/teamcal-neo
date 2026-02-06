<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;
use DateTime;

/**
 * Roles Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class RolesController extends BaseController
{
  private array $viewData = [];

  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['roles']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $date    = new DateTime();
    $weekday = $date->format('N');
    if ($weekday == random_int(1, 7)) {
      $alertData        = array();
      $showAlert        = false;
      $licExpiryWarning = (int) $this->allConfig['licExpiryWarning'];
      $LIC              = new LicenseModel();
      $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);
    }

    $this->viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $this->viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $this->viewData['txt_name']        = '';
    $this->viewData['txt_description'] = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_roleCreate'])) {
        $this->createRole();
      }
      elseif (isset($_POST['btn_roleDelete'])) {
        $this->deleteRole();
      }

      if (isset($_SESSION)) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
    }

    $this->viewData['roles'] = $this->RO->getAll();
    $this->render('roles', $this->viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a new role.
   *
   * @return void
   */
  private function createRole() {
    $inputError = false;
    if (!formInputValid('txt_name', 'required|alpha_numeric_dash') || !formInputValid('txt_description', 'alpha_numeric_dash_blank')) {
      $inputError                          = true;
      $this->viewData['alertData']['text'] = $this->LANG['roles_alert_created_fail_input'];
    }
    $this->viewData['txt_name']        = $_POST['txt_name'];
    $this->viewData['txt_description'] = $_POST['txt_description'] ?? '';

    if ($this->RO->getByName($_POST['txt_name'])) {
      $inputError                          = true;
      $this->viewData['alertData']['text'] = $this->LANG['roles_alert_created_fail_duplicate'];
    }

    if (!$inputError) {
      $this->RO->name        = $this->viewData['txt_name'];
      $this->RO->description = $this->viewData['txt_description'];
      $this->RO->color       = 'default';
      $this->RO->create();

      $mailError = '';
      if ($this->allConfig['emailNotifications']) {
        sendRoleEventNotifications("created", $this->RO->name, $this->RO->description, $mailError);
      }
      $this->LOG->logEvent("logRole", $this->UL->username, "log_role_created", $this->RO->name . " " . $this->RO->description);

      $this->viewData['showAlert'] = true;
      $this->viewData['alertData'] = [
        'type'    => (empty($mailError)) ? 'success' : 'warning',
        'title'   => (empty($mailError)) ? $this->LANG['alert_success_title'] : $this->LANG['alert_warning_title'],
        'subject' => $this->LANG['btn_create_role'],
        'text'    => $this->LANG['roles_alert_created'],
        'help'    => (empty($mailError)) ? '' : $this->LANG['contact_administrator']
      ];
      if (!empty($mailError)) {
        $this->viewData['alertData']['text'] .= '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
      }
    }
    else {
      $this->viewData['showAlert'] = true;
      $this->viewData['alertData'] = [
        'type'    => 'danger',
        'title'   => $this->LANG['alert_danger_title'],
        'subject' => $this->LANG['btn_create_role'],
        'text'    => $this->viewData['alertData']['text'],
        'help'    => ''
      ];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a role.
   *
   * @return void
   */
  private function deleteRole() {
    if (isset($_POST['hidden_id'], $_POST['hidden_name'], $_POST['hidden_description'])) {
      $this->RO->delete($_POST['hidden_id']);
      $this->P->deleteRole($_POST['hidden_id']);

      $mailError = '';
      if ($this->allConfig['emailNotifications']) {
        sendRoleEventNotifications("deleted", $_POST['hidden_name'], $_POST['hidden_description'], $mailError);
      }
      $this->LOG->logEvent("logRole", $this->UL->username, "log_role_deleted", $_POST['hidden_name']);

      $this->viewData['showAlert'] = true;
      $this->viewData['alertData'] = [
        'type'    => (empty($mailError)) ? 'success' : 'warning',
        'title'   => (empty($mailError)) ? $this->LANG['alert_success_title'] : $this->LANG['alert_warning_title'],
        'subject' => $this->LANG['btn_delete_role'],
        'text'    => $this->LANG['roles_alert_deleted'],
        'help'    => (empty($mailError)) ? '' : $this->LANG['contact_administrator']
      ];
      if (!empty($mailError)) {
        $this->viewData['alertData']['text'] .= '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
      }
    }
    else {
      $this->viewData['showAlert'] = true;
      $this->viewData['alertData'] = [
        'type'    => 'danger',
        'title'   => $this->LANG['alert_danger_title'],
        'subject' => $this->LANG['btn_delete_role'],
        'text'    => $this->LANG['roles_alert_deleted_fail'],
        'help'    => ''
      ];
    }
  }
}
