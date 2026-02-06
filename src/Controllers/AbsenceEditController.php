<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\AbsenceModel;

/**
 * Absence Edit Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class AbsenceEditController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    resetTabindex();
    if (!isAllowed($this->CONF['controllers']['absenceedit']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $AA          = new AbsenceModel($this->DB->db, $this->CONF);
    $missingData = false;

    if (isset($_GET['id'])) {
      $id = sanitize($_GET['id']);
      if (!$AA->get($id)) {
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

    $viewData               = [];
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];

    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];
    /** @var array<string, string> $alertData */
    $alertData              = [];
    $showAlert              = false;
    $viewData['csrf_token'] = $_SESSION['csrf_token'] ?? '';
    $viewData['showToast']  = false; // Default

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $viewData['name'] = $_POST['txt_name'];

      $inputError = false;
      if (isset($_POST['btn_save'])) {
        if (!formInputValid('txt_name', 'required|alpha_numeric_dash_blank'))
          $inputError = true;
        if (!formInputValid('txt_symbol', 'ctype_graph'))
          $inputError = true;
        if (!formInputValid('txt_color', 'hex_color'))
          $inputError = true;
        if (!formInputValid('txt_bgcolor', 'hex_color'))
          $inputError = true;
        if (!formInputValid('txt_factor', 'numeric|max_length', '4'))
          $inputError = true;
        if (!formInputValid('txt_allowance', 'numeric|max_length', '3'))
          $inputError = true;
        if (!formInputValid('txt_allowmonth', 'numeric|max_length', '2'))
          $inputError = true;
        if (!formInputValid('txt_allowweek', 'numeric|max_length', '1'))
          $inputError = true;
      }

      if (!$inputError) {
        if (isset($_POST['btn_save'])) {
          $AA->id      = (int) ($_POST['hidden_id'] ?? 0);
          $AA->name    = $_POST['txt_name'];
          $AA->symbol  = isset($_POST['txt_symbol']) ? $_POST['txt_symbol'] : strtoupper(substr($_POST['txt_name'], 0, 1));
          $AA->color   = ltrim(sanitize($_POST['txt_color']), '#');
          $AA->bgcolor = ltrim(sanitize($_POST['txt_bgcolor']), '#');
          $AA->bgtrans = isset($_POST['chk_bgtrans']) ? 1 : 0;

          $AA->factor     = (float) ($_POST['txt_factor'] ?? 1);
          $AA->allowance  = (int) ($_POST['txt_allowance'] ?? 0);
          $AA->allowmonth = (int) ($_POST['txt_allowmonth'] ?? 0);
          $AA->allowweek  = (int) ($_POST['txt_allowweek'] ?? 0);
          $AA->counts_as  = (int) ($_POST['sel_counts_as'] ?? 0);

          $checkboxes = [
            'chk_counts_as_present' => 'counts_as_present',
            'chk_approval_required' => 'approval_required',
            'chk_manager_only'      => 'manager_only',
            'chk_hide_in_profile'   => 'hide_in_profile',
            'chk_confidential'      => 'confidential',
            'chk_takeover'          => 'takeover',
            'chk_show_in_remainder' => 'show_in_remainder',
          ];

          foreach ($checkboxes as $postKey => $property) {
            $AA->$property = isset($_POST[$postKey]) ? 1 : 0;
          }

          $this->AG->unassignAbs((string) $AA->id);
          if (isset($_POST['sel_groups'])) {
            foreach ($_POST['sel_groups'] as $grp) {
              $this->AG->assign((string) $AA->id, $grp);
            }
          }

          $AA->update((string) $AA->id);

          $mailError = '';
          if ($this->allConfig['emailNotifications']) {
            sendAbsenceEventNotifications("changed", $AA->name, $mailError);
          }
          $this->LOG->logEvent("logAbsence", $this->UL->username, "log_abs_updated", $AA->name);

          if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $viewData['csrf_token'] = $_SESSION['csrf_token'];
          }

          $showAlert            = true;
          $alertData['type']    = (empty($mailError)) ? 'success' : 'warning';
          $alertData['title']   = (empty($mailError)) ? $this->LANG['alert_success_title'] : $this->LANG['alert_warning_title'];
          $alertData['subject'] = $this->LANG['abs_alert_edit'];
          $alertData['text']    = $this->LANG['abs_alert_edit_success'];
          if (!empty($mailError)) {
            $alertData['text'] .= '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
          }
          $alertData['help'] = (empty($mailError)) ? '' : $this->LANG['contact_administrator'];
        }
      }
      else {
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_input'];
        $alertData['text']    = $this->LANG['abs_alert_save_failed'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $viewData['id']      = $AA->id;
    $viewData['name']    = $AA->name;
    $viewData['symbol']  = $AA->symbol;
    $viewData['icon']    = $AA->icon;
    $viewData['color']   = $AA->color;
    $viewData['bgcolor'] = $AA->bgcolor;
    $viewData['bgtrans'] = $AA->bgtrans;

    $viewData['factor']     = $AA->factor;
    $viewData['allowance']  = $AA->allowance;
    $viewData['allowmonth'] = $AA->allowmonth;
    $viewData['allowweek']  = $AA->allowweek;

    $otherAbs               = $AA->getAllPrimaryBut((string) $AA->id);
    $viewData['otherAbs']   = [];
    $viewData['otherAbs'][] = ['val' => '0', 'name' => 'None', 'selected' => ($AA->counts_as == '0')];

    $absenceNameMap = ['0' => 'None'];
    if ($otherAbs) {
      foreach ($otherAbs as $abs) {
        $absenceNameMap[$abs['id']] = $abs['name'];
        $viewData['otherAbs'][]     = ['val' => $abs['id'], 'name' => $abs['name'], 'selected' => ($AA->counts_as == $abs['id'])];
      }
    }

    $viewData['counts_as']['val']  = $AA->counts_as;
    $viewData['counts_as']['name'] = $absenceNameMap[$AA->counts_as] ?? 'None';
    $viewData['counts_as_present'] = $AA->counts_as_present;
    $viewData['approval_required'] = $AA->approval_required;
    $viewData['manager_only']      = $AA->manager_only;
    $viewData['hide_in_profile']   = $AA->hide_in_profile;
    $viewData['confidential']      = $AA->confidential;
    $viewData['takeover']          = $AA->takeover;
    $viewData['show_in_remainder'] = $AA->show_in_remainder;

    $groups                     = $this->G->getAll();
    $viewData['groupsAssigned'] = [];
    foreach ($groups as $group) {
      $selected                     = $this->AG->isAssigned((string) $viewData['id'], (string) $group['id']);
      $viewData['groupsAssigned'][] = ['val' => $group['id'], 'name' => $group['name'], 'selected' => $selected];
    }

    $viewData['formObjects'] = [
      'general' => [
        ['prefix' => 'abs', 'name' => 'name', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['name'], 'maxlength' => '80', 'mandatory' => true, 'error' => $inputAlert['name'] ?? ''],
        ['prefix' => 'abs', 'name' => 'symbol', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['symbol'], 'maxlength' => '1', 'mandatory' => true, 'error' => $inputAlert['symbol'] ?? ''],
        ['prefix' => 'abs', 'name' => 'color', 'type' => 'coloris', 'value' => (!empty($viewData['color']) ? '#' . $viewData['color'] : ''), 'maxlength' => '6', 'error' => $inputAlert['color'] ?? ''],
        ['prefix' => 'abs', 'name' => 'bgcolor', 'type' => 'coloris', 'value' => (!empty($viewData['bgcolor']) ? '#' . $viewData['bgcolor'] : ''), 'maxlength' => '6', 'error' => $inputAlert['bgcolor'] ?? ''],
        ['prefix' => 'abs', 'name' => 'bgtrans', 'type' => 'check', 'value' => $viewData['bgtrans']],
      ],
      'options' => [
        ['prefix' => 'abs', 'name' => 'factor', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['factor'], 'maxlength' => '4', 'error' => $inputAlert['factor'] ?? ''],
        ['prefix' => 'abs', 'name' => 'allowance', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['allowance'], 'maxlength' => '3', 'error' => $inputAlert['allowance'] ?? ''],
        ['prefix' => 'abs', 'name' => 'allowmonth', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['allowmonth'], 'maxlength' => '2', 'error' => $inputAlert['allowmonth'] ?? ''],
        ['prefix' => 'abs', 'name' => 'allowweek', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['allowweek'], 'maxlength' => '2', 'error' => $inputAlert['allowweek'] ?? ''],
        ['prefix' => 'abs', 'name' => 'counts_as', 'type' => 'list', 'values' => $viewData['otherAbs'], 'topvalue' => ['val' => '0', 'name' => 'None']],
        ['prefix' => 'abs', 'name' => 'counts_as_present', 'type' => 'check', 'value' => $viewData['counts_as_present']],
        ['prefix' => 'abs', 'name' => 'approval_required', 'type' => 'check', 'value' => $viewData['approval_required']],
        ['prefix' => 'abs', 'name' => 'manager_only', 'type' => 'check', 'value' => $viewData['manager_only']],
        ['prefix' => 'abs', 'name' => 'hide_in_profile', 'type' => 'check', 'value' => $viewData['hide_in_profile']],
        ['prefix' => 'abs', 'name' => 'confidential', 'type' => 'check', 'value' => $viewData['confidential']],
        ['prefix' => 'abs', 'name' => 'takeover', 'type' => 'check', 'value' => $viewData['takeover']],
        ['prefix' => 'abs', 'name' => 'show_in_remainder', 'type' => 'check', 'value' => $viewData['show_in_remainder']],
      ],
      'groups'  => [
        ['prefix' => 'abs', 'name' => 'groups', 'type' => 'listmulti', 'values' => $viewData['groupsAssigned']],
      ]
    ];

    $this->render('absenceedit', $viewData);
  }
}
