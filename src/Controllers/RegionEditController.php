<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\RegionModel;

/**
 * Region Edit Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class RegionEditController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['regionedit']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $RR          = new RegionModel($this->DB->db, $this->CONF);
    $missingData = false;

    if (isset($_GET['id'])) {
      $id = sanitize($_GET['id']);
      if (!$RR->getById($id)) {
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

    $viewData                    = [];
    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];
    $viewData['id']              = $RR->id;
    $viewData['name']            = $RR->name;
    $viewData['description']     = $RR->description;
    $viewData['csrf_token']      = $_SESSION['csrf_token'] ?? '';

    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];
    $alertData  = [];
    $showAlert  = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $viewData['id']          = $_POST['hidden_id'];
      $viewData['name']        = $_POST['txt_name'];
      $viewData['description'] = $_POST['txt_description'];

      $inputError = false;
      if (isset($_POST['btn_regionUpdate'])) {
        if (!formInputValid('txt_name', 'required|alpha_numeric_dash'))
          $inputError = true;
        if (!formInputValid('txt_description', 'alpha_numeric_dash_blank'))
          $inputError = true;
      }

      if (!$inputError) {
        if (isset($_POST['btn_regionUpdate'])) {
          $RR->name        = $_POST['txt_name'];
          $RR->description = $_POST['txt_description'];
          $RR->update($_POST['hidden_id']);

          $RR->deleteAccess($_POST['hidden_id']);
          if (isset($_POST['sel_viewOnlyRoles'])) {
            foreach ($_POST['sel_viewOnlyRoles'] as $roleid) {
              $RR->setAccess($_POST['hidden_id'], $roleid, 'view');
            }
          }

          $this->LOG->logEvent("logRegion", $this->UL->username, "log_region_updated", $RR->name);

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['region_alert_edit'];
          $alertData['text']    = $this->LANG['region_alert_edit_success'];
          $alertData['help']    = '';

          if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $viewData['csrf_token'] = $_SESSION['csrf_token'];
          }

          $viewData['name']        = $RR->name;
          $viewData['description'] = $RR->description;
        }
      }
      else {
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_input'];
        $alertData['text']    = $this->LANG['region_alert_save_failed'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $roles = $this->RO->getAll();
    foreach ($roles as $role) {
      $viewData['viewOnlyRoles'][] = ['val' => $role['id'], 'name' => $role['name'], 'selected' => ($this->R->getAccess((string) $viewData['id'], (string) $role['id']) == "view")];
    }
    $viewData['region'] = [
      ['prefix' => 'region', 'name' => 'name', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['name'], 'maxlength' => '40', 'mandatory' => true, 'error' => ($inputAlert['name'] ?? '')],
      ['prefix' => 'region', 'name' => 'description', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['description'], 'maxlength' => '100', 'error' => ($inputAlert['description'] ?? '')],
      ['prefix' => 'region', 'name' => 'viewOnlyRoles', 'type' => 'listmulti', 'values' => $viewData['viewOnlyRoles']],
    ];

    $this->render('regionedit', $viewData);
  }
}
