<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\PatternModel;

/**
 * Pattern Add Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     4.0.0
 */
class PatternAddController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['patternadd']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $PTN = new PatternModel($this->DB->db, $this->CONF);

    $alertData = [];
    $showAlert = false;
    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = []; // Initialize for formInputValid() errors

    $viewData                = [];
    $viewData['pageHelp']    = $this->allConfig['pageHelp'];
    $viewData['showAlerts']  = $this->allConfig['showAlerts'];
    $viewData['name']        = '';
    $viewData['description'] = '';
    $viewData['csrf_token']  = $_SESSION['csrf_token'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $viewData['name']        = $_POST['txt_name'];
      $viewData['description'] = $_POST['txt_description'];

      $inputError = false;
      if (
        !formInputValid('txt_name', 'required|alpha_numeric_dash_blank') ||
        !formInputValid('txt_description', 'alpha_numeric_dash_blank_special') ||
        !formInputValid('sel_abs1', 'numeric') ||
        !formInputValid('sel_abs2', 'numeric') ||
        !formInputValid('sel_abs3', 'numeric') ||
        !formInputValid('sel_abs4', 'numeric') ||
        !formInputValid('sel_abs5', 'numeric') ||
        !formInputValid('sel_abs6', 'numeric') ||
        !formInputValid('sel_abs7', 'numeric')
      ) {
        $inputError = true;
      }

      if (!$inputError) {
        if (isset($_POST['btn_create'])) {
          $showAlert    = false;
          $checkPattern = [0, (int) $_POST['sel_abs1'], (int) $_POST['sel_abs2'], (int) $_POST['sel_abs3'], (int) $_POST['sel_abs4'], (int) $_POST['sel_abs5'], (int) $_POST['sel_abs6'], (int) $_POST['sel_abs7']];
          if ($name = $PTN->patternExists($checkPattern)) {
            $showAlert            = true;
            $alertData['type']    = 'warning';
            $alertData['title']   = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['btn_create_pattern'];
            $alertData['text']    = sprintf($this->LANG['ptn_alert_exists'], $name);
            $alertData['help']    = '';
          }
          else {
            $PTN->name        = $_POST['txt_name'];
            $PTN->description = $_POST['txt_description'];
            for ($i = 1; $i <= 7; $i++) {
              $PTN->{'abs' . $i} = (int) $_POST['sel_abs' . $i];
            }
            $PTN->create();

            $this->LOG->logEvent("logPattern", $this->UL->username, "log_pattern_created", $PTN->name);

            $showAlert            = true;
            $alertData['type']    = 'success';
            $alertData['title']   = $this->LANG['alert_success_title'];
            $alertData['subject'] = $this->LANG['btn_create_pattern'];
            $alertData['text']    = $this->LANG['ptn_alert_created'];
            $alertData['help']    = '';

            $viewData['name']        = $PTN->name;
            $viewData['description'] = $PTN->description;
          }
        }
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $viewData['csrf_token'] = $_SESSION['csrf_token'];
      }
      else {
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_input'];
        $alertData['text']    = $this->LANG['ptn_alert_created_failed'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $absenceOptions = [['val' => 0, 'name' => $this->LANG['none']]];
    $absences       = $this->A->getAll();
    foreach ($absences as $absence) {
      $absenceOptions[] = ['val' => $absence['id'], 'name' => $absence['name']];
    }

    for ($i = 1; $i <= 7; $i++) {
      $viewKey            = 'abs' . $i . 'Absences';
      $viewData[$viewKey] = [];
      foreach ($absenceOptions as $option) {
        $viewData[$viewKey][] = [
          'val'      => $option['val'],
          'name'     => $option['name'],
          'selected' => ($i === 1 && $option['val'] == 0) // Default 'None' for first element
        ];
      }
    }

    $viewData['pattern'] = [
      ['prefix' => 'ptn', 'name' => 'name', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['name'], 'maxlength' => '40', 'mandatory' => true, 'error' => ($inputAlert['name'] ?? '')],
      ['prefix' => 'ptn', 'name' => 'description', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['description'], 'maxlength' => '100', 'error' => ($inputAlert['description'] ?? '')],
      ['prefix' => 'ptn', 'name' => 'abs1', 'type' => 'list', 'values' => $viewData['abs1Absences']],
      ['prefix' => 'ptn', 'name' => 'abs2', 'type' => 'list', 'values' => $viewData['abs2Absences']],
      ['prefix' => 'ptn', 'name' => 'abs3', 'type' => 'list', 'values' => $viewData['abs3Absences']],
      ['prefix' => 'ptn', 'name' => 'abs4', 'type' => 'list', 'values' => $viewData['abs4Absences']],
      ['prefix' => 'ptn', 'name' => 'abs5', 'type' => 'list', 'values' => $viewData['abs5Absences']],
      ['prefix' => 'ptn', 'name' => 'abs6', 'type' => 'list', 'values' => $viewData['abs6Absences']],
      ['prefix' => 'ptn', 'name' => 'abs7', 'type' => 'list', 'values' => $viewData['abs7Absences']],
    ];

    $this->render('patternadd', $viewData);
  }
}
