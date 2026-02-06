<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\RoleModel;

/**
 * Role Edit Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class RoleEditController extends BaseController
{
  private array $viewData = [];

  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['roleedit']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $RO2 = new RoleModel();
    if (isset($_GET['id'])) {
      $missingData = false;
      $roleid      = sanitize($_GET['id']);
      if (!$RO2->getById($roleid)) {
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

    $this->viewData['pageHelp']    = $this->allConfig['pageHelp'];
    $this->viewData['showAlerts']  = $this->allConfig['showAlerts'];
    $this->viewData['id']          = $RO2->id;
    $this->viewData['name']        = $RO2->name;
    $this->viewData['description'] = $RO2->description;

    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $this->viewData['name']        = $_POST['txt_name'];
      $this->viewData['description'] = $_POST['txt_description'];

      $inputError = false;
      if (!formInputValid('txt_name', 'required|alpha_numeric_dash')) {
        $inputError         = true;
        $inputAlert['name'] = $this->LANG['alert_input'];
      }
      if (!formInputValid('txt_description', 'alpha_numeric_dash_blank')) {
        $inputError                = true;
        $inputAlert['description'] = $this->LANG['alert_input'];
      }

      if ($inputError) {
        $alertData['text'] = $this->LANG['role_alert_save_failed'];
      }

      if (!$inputError) {
        if (isset($_POST['btn_roleUpdate'])) {
          $oldName          = $RO2->name;
          $RO2->name        = $_POST['txt_name'];
          $RO2->description = $_POST['txt_description'];
          if ($_POST['opt_color']) {
            $RO2->color = $_POST['opt_color'];
          }
          $RO2->update($RO2->id);

          $mailError = '';
          if ($this->allConfig['emailNotifications']) {
            sendRoleEventNotifications("changed", $RO2->name . ' (ex: ' . $oldName . ')', $RO2->description, $mailError);
          }
          $this->LOG->logEvent("logRole", $this->UL->username, "log_role_updated", $RO2->name . ' (ex: ' . $oldName . ')');

          $this->viewData['showAlert'] = true;
          $this->viewData['alertData'] = [
            'type'    => (empty($mailError)) ? 'success' : 'warning',
            'title'   => (empty($mailError)) ? $this->LANG['alert_success_title'] : $this->LANG['alert_warning_title'],
            'subject' => $this->LANG['role_alert_edit'],
            'text'    => $this->LANG['role_alert_edit_success'],
            'help'    => (empty($mailError)) ? '' : $this->LANG['contact_administrator']
          ];
          if (!empty($mailError)) {
            $this->viewData['alertData']['text'] .= '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
          }

          $this->viewData['name']        = $RO2->name;
          $this->viewData['description'] = $RO2->description;
          $this->viewData['color']       = $RO2->color;
        }

        if (isset($_SESSION)) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
      }
      else {
        $this->viewData['showAlert'] = true;
        $this->viewData['alertData'] = [
          'type'    => 'danger',
          'title'   => $this->LANG['alert_danger_title'],
          'subject' => $this->LANG['alert_input'],
          'text'    => $alertData['text'],
          'help'    => ''
        ];
      }
    }

    $roleColor              = $RO2->getColorByName($this->viewData['name']);
    $this->viewData['role'] = [
      ['prefix' => 'role', 'name' => 'name', 'type' => 'text', 'placeholder' => '', 'value' => $this->viewData['name'], 'maxlength' => '40', 'mandatory' => true, 'error' => ($inputAlert['name'] ?? '')],
      ['prefix' => 'role', 'name' => 'description', 'type' => 'text', 'placeholder' => '', 'value' => $this->viewData['description'], 'maxlength' => '100', 'error' => ($inputAlert['description'] ?? '')],
      ['prefix' => 'role', 'name' => 'color', 'type' => 'radio', 'values' => $this->bsColors, 'value' => $roleColor],
    ];

    $this->render('roleedit', $this->viewData);
  }
}
