<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\PatternModel;

/**
 * Pattern Edit Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     4.0.0
 */
class PatternEditController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['patternedit']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $patternModel         = new PatternModel($this->dbModel->db, $this->CONF);
    $missingData = false;
    if (isset($_GET['id'])) {
      $id = sanitize($_GET['id']);
      if (!$patternModel->get($id)) {
        $missingData = true;
      }
    }
    else {
      $missingData = true;
    }

    $showAlert = false;
    $alertData = [];

    if ($missingData) {
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
      return;
    }

    $viewData                = [];
    $viewData['pageHelp']    = $this->allConfig['pageHelp'];
    $viewData['showAlerts']  = $this->allConfig['showAlerts'];
    $viewData['patternModel']         = $patternModel;
    $viewData['id']          = $patternModel->id;
    $viewData['name']        = $patternModel->name;
    $viewData['description'] = $patternModel->description;
    $weekdayMap              = $patternModel->getWeekdayMap((string) $patternModel->id);
    for ($i = 1; $i <= 7; $i++) {
      $viewData['abs' . $i] = $weekdayMap[$i];
    }

    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = []; // Initialize for formInputValid() errors

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
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
        if (isset($_POST['btn_update'])) {
          $newWeekdayMap  = [];
          $patternChanged = false;
          for ($i = 1; $i <= 7; $i++) {
            $newWeekdayMap[$i] = (int) $_POST['sel_abs' . $i];
            if ($weekdayMap[$i] != $newWeekdayMap[$i]) {
              $patternChanged = true;
            }
          }

          if ($patternChanged) {
            if ($name = $patternModel->patternExists($newWeekdayMap)) {
              $showAlert            = true;
              $alertData['type']    = 'warning';
              $alertData['title']   = $this->LANG['alert_warning_title'];
              $alertData['subject'] = $this->LANG['btn_create_pattern'];
              $alertData['text']    = sprintf($this->LANG['ptn_alert_exists'], $name);
              $alertData['help']    = '';
            }
          }

          if (!$showAlert) {
            $patternModel->name        = $_POST['txt_name'];
            $patternModel->description = $_POST['txt_description'];
            $patternModel->update((string) $patternModel->id);
            $patternModel->setWeekdayMap((string) $patternModel->id, $newWeekdayMap);

            $this->logModel->logEvent("logPattern", $this->userLoggedIn->username, "log_pattern_updated", $patternModel->name);

            $showAlert            = true;
            $alertData['type']    = 'success';
            $alertData['title']   = $this->LANG['alert_success_title'];
            $alertData['subject'] = $this->LANG['ptn_alert_edit'];
            $alertData['text']    = $this->LANG['ptn_alert_edit_success'];
            $alertData['help']    = '';
            $patternModel->get((string) $patternModel->id);

            $viewData['patternModel']         = $patternModel;
            $viewData['name']        = $patternModel->name;
            $viewData['description'] = $patternModel->description;
            $weekdayMap              = $patternModel->getWeekdayMap((string) $patternModel->id);
            for ($i = 1; $i <= 7; $i++) {
              $viewData['abs' . $i] = $weekdayMap[$i];
            }
          }
        }
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
      else {
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_input'];
        $alertData['text']    = $this->LANG['ptn_alert_save_failed'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $absenceOptions = [['val' => 0, 'name' => $this->LANG['none']]];
    $absences       = $this->absenceModel->getAll();
    foreach ($absences as $absence) {
      $absenceOptions[] = ['val' => $absence['id'], 'name' => $absence['name']];
    }

    for ($i = 1; $i <= 7; $i++) {
      $viewKey            = 'abs' . $i . 'Absences';
      $viewData[$viewKey] = [];
      $currentValue       = $weekdayMap[$i];
      foreach ($absenceOptions as $option) {
        $viewData[$viewKey][] = [
          'val'      => $option['val'],
          'name'     => $option['name'],
          'selected' => ($currentValue === $option['val'])
        ];
      }
    }

    // Prepare absence data for pattern macros
    $absencesAll = $this->absenceModel->getAll();
    $absData     = [];
    foreach ($absencesAll as $abs) {
      $absId   = (string) $abs['id'];
      $bgStyle = '';
      if (!$this->absenceModel->getBgTrans($absId)) {
        $bgColor = $this->absenceModel->getBgColor($absId);
        $bgStyle = $bgColor ? $bgColor : 'ffffff';
      }

      $symbol = '';
      if ($this->allConfig['symbolAsIcon']) {
        $symbol = $this->absenceModel->getSymbol($absId);
      }
      else {
        $symbol = '<span class="' . $this->absenceModel->getIcon($absId) . '"></span>';
      }

      $absData[$absId] = [
        'name'         => $this->absenceModel->getName($absId),
        'color'        => $this->absenceModel->getColor($absId),
        'bgColor'      => $bgStyle,
        'bgTrans'      => $this->absenceModel->getBgTrans($absId),
        'icon'         => $this->absenceModel->getIcon($absId),
        'symbol'       => $symbol,
        'symbolAsIcon' => $this->allConfig['symbolAsIcon']
      ];
    }
    // Add "None" absence (0)
    $absData[0]               = [
      'name'         => '',
      'color'        => '000000',
      'bgColor'      => 'ffffff',
      'bgTrans'      => false,
      'icon'         => '',
      'symbol'       => '',
      'symbolAsIcon' => false
    ];
    $viewData['absences']     = $absData;
    $viewData['weekdayShort'] = $this->LANG['weekdayShort'];

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

    $this->render('patternedit', $viewData);
  }
}
