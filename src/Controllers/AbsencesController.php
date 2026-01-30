<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;

/**
 * Absences Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class AbsencesController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    if (!isAllowed($this->CONF['controllers']['absences']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    resetTabindex();
    $viewData               = [];
    $viewData['csrf_token'] = $_SESSION['csrf_token'] ?? '';

    // Check License
    $alertData        = [];
    $showAlert        = false;
    $licExpiryWarning = (int) $this->allConfig['licExpiryWarning'];
    $LIC              = new LicenseModel($this->DB->db, $this->CONF);
    $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);


    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert             = [];
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];
    $viewData['txt_name']   = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_absCreate'])) {
        $inputError = false;
        if (!formInputValid('txt_name', 'alpha_numeric_dash_blank')) {
          $inputError = true;
        }

        if (isset($_POST['txt_name'])) {
          $viewData['txt_name'] = htmlspecialchars($_POST['txt_name'], ENT_QUOTES, 'UTF-8');
        }

        if (!$inputError) {
          $this->A->name   = $viewData['txt_name'];
          $this->A->icon   = 'fas fa-times';
          $this->A->symbol = strtoupper(substr($viewData['txt_name'], 0, 1));
          $this->A->create();

          if ($this->allConfig['emailNotifications']) {
            sendAbsenceEventNotifications('created', $this->A->name);
          }
          $this->LOG->logEvent('logAbsence', $this->UL->username, 'log_abs_created', $this->A->name);

          if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
          }

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['btn_create_abs'];
          $alertData['text']    = $this->LANG['abs_alert_created'];
          $alertData['help']    = '';
        }
        else {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['btn_create_abs'];
          $alertData['text']    = $this->LANG['abs_alert_created_fail'];
          $alertData['help']    = '';
        }
      }
      elseif (isset($_POST['btn_absDelete'])) {
        $hidden_id   = $_POST['hidden_id'] ?? '';
        $hidden_name = $_POST['hidden_name'] ?? '';

        if ($hidden_id !== '') {
          $this->T->replaceAbsId((int) $hidden_id, 0);
          $this->AG->unassignAbs($hidden_id);
          $this->UO->deleteOptionByValue('calfilterAbs', $hidden_id);
          $this->A->setAllSubsPrimary($hidden_id);
          $this->A->delete($hidden_id);
        }

        if ($hidden_name !== '') {
          if ($this->allConfig['emailNotifications']) {
            sendAbsenceEventNotifications('deleted', $hidden_name);
          }
          $this->LOG->logEvent('logAbsence', $this->UL->username, 'log_abs_deleted', $hidden_name);
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $showAlert            = true;
        $alertData['type']    = 'success';
        $alertData['title']   = $this->LANG['alert_success_title'];
        $alertData['subject'] = $this->LANG['btn_delete_abs'];
        $alertData['text']    = $this->LANG['abs_alert_deleted'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $viewData['absences'] = $this->A->getAll();
    $allSubAbsences       = [];
    foreach ($viewData['absences'] as $absence) {
      if (!($absence['counts_as'] ?? false)) {
        $subAbsences = $this->A->getAllSub((string) $absence['id']);
        if (!empty($subAbsences)) {
          $allSubAbsences[$absence['id']] = $subAbsences;
        }
      }
    }
    $viewData['allSubAbsences'] = $allSubAbsences;

    foreach ($viewData['absences'] as &$absence) {
      $absence['bgstyle'] = (isset($absence['bgtrans']) && $absence['bgtrans']) ? "" : (isset($absence['bgcolor']) ? "background-color: #" . $absence['bgcolor'] . ";" : "");
    }
    unset($absence);

    foreach ($viewData['allSubAbsences'] as $absId => $subAbsences) {
      foreach ($subAbsences as &$subabs) {
        $subabs['bgstyle'] = (isset($subabs['bgtrans']) && $subabs['bgtrans']) ? "" : (isset($subabs['bgcolor']) ? "background-color: #" . $subabs['bgcolor'] . ";" : "");
      }
      unset($subabs);
      $viewData['allSubAbsences'][$absId] = $subAbsences;
    }

    $this->render('absences', $viewData);
  }
}
