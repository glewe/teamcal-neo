<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\HolidayModel;

/**
 * Holiday Edit Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class HolidayEditController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['holidayedit']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $HH = new HolidayModel($this->DB->db, $this->CONF);

    if (isset($_GET['id'])) {
      $missingData = false;
      $id          = sanitize($_GET['id']);
      if (!$HH->get($id)) {
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
    $viewData['csrf_token'] = $_SESSION['csrf_token'] ?? '';

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

      $viewData['id']               = $_POST['hidden_id'];
      $viewData['name']             = $_POST['txt_name'];
      $viewData['description']      = $_POST['txt_description'];
      $viewData['color']            = $_POST['txt_color'];
      $viewData['bgcolor']          = $_POST['txt_bgcolor'];
      $viewData['businessday']      = isset($_POST['chk_businessday']) ? 1 : 0;
      $viewData['noabsence']        = isset($_POST['chk_noabsence']) ? 1 : 0;
      $viewData['keepweekendcolor'] = isset($_POST['chk_keepweekendcolor']) ? 1 : 0;

      $inputError = false;
      if (isset($_POST['btn_holidayUpdate'])) {
        if (!formInputValid('txt_name', 'required|alpha_numeric_dash_blank'))
          $inputError = true;
        if (!formInputValid('txt_description', 'alpha_numeric_dash_blank_special'))
          $inputError = true;
        if (!formInputValid('txt_color', 'required|hex_color'))
          $inputError = true;
        if (!formInputValid('txt_bgcolor', 'required|hex_color'))
          $inputError = true;
      }

      if (!$inputError) {
        if (isset($_POST['btn_holidayUpdate'])) {
          $id                   = $_POST['hidden_id'];
          $HH->name             = $_POST['txt_name'];
          $HH->description      = $_POST['txt_description'];
          $HH->color            = ltrim(sanitize($_POST['txt_color']), '#');
          $HH->bgcolor          = ltrim(sanitize($_POST['txt_bgcolor']), '#');
          $HH->businessday      = isset($_POST['chk_businessday']) ? 1 : 0;
          $HH->noabsence        = isset($_POST['chk_noabsence']) ? 1 : 0;
          $HH->keepweekendcolor = isset($_POST['chk_keepweekendcolor']) ? 1 : 0;

          $HH->update($id);

          if ($this->allConfig['emailNotifications']) {
            sendHolidayEventNotifications("changed", $HH->name, $HH->description);
          }

          $this->LOG->logEvent("logHoliday", $this->UL->username, "log_hol_updated", $HH->name);

          $showAlert              = true;
          $alertData['type']      = 'success';
          $alertData['title']     = $this->LANG['alert_success_title'];
          $alertData['subject']   = $this->LANG['hol_alert_edit'];
          $alertData['text']      = $this->LANG['hol_alert_edit_success'];
          $alertData['help']      = '';
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
          $viewData['csrf_token'] = $_SESSION['csrf_token'];
        }
        else {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['alert_input'];
          $alertData['text']    = $this->LANG['hol_alert_save_failed'];
          $alertData['help']    = '';
        }
      }
    }

    $viewData['alertData'] = $alertData;
    $viewData['showAlert'] = $showAlert;

    $viewData['id']               = $HH->id;
    $viewData['name']             = $HH->name;
    $viewData['description']      = $HH->description;
    $viewData['color']            = $HH->color;
    $viewData['bgcolor']          = $HH->bgcolor;
    $viewData['businessday']      = $HH->businessday;
    $viewData['noabsence']        = $HH->noabsence;
    $viewData['keepweekendcolor'] = $HH->keepweekendcolor;

    $viewData['holiday'] = [
      ['prefix' => 'hol', 'name' => 'name', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['name'], 'maxlength' => '40', 'mandatory' => true, 'error' => ($inputAlert['name'] ?? '')],
      ['prefix' => 'hol', 'name' => 'description', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['description'], 'maxlength' => '100', 'error' => ($inputAlert['description'] ?? '')],
      ['prefix' => 'hol', 'name' => 'color', 'type' => 'coloris', 'value' => (!empty($viewData['color']) ? '#' . $viewData['color'] : ''), 'maxlength' => '6', 'mandatory' => true, 'error' => ($inputAlert['color'] ?? '')],
      ['prefix' => 'hol', 'name' => 'bgcolor', 'type' => 'coloris', 'value' => (!empty($viewData['bgcolor']) ? '#' . $viewData['bgcolor'] : ''), 'maxlength' => '6', 'mandatory' => true, 'error' => ($inputAlert['bgcolor'] ?? '')],
      ['prefix' => 'hol', 'name' => 'keepweekendcolor', 'type' => 'check', 'value' => $viewData['keepweekendcolor']],
      ['prefix' => 'hol', 'name' => 'businessday', 'type' => 'check', 'value' => $viewData['businessday']],
      ['prefix' => 'hol', 'name' => 'noabsence', 'type' => 'check', 'value' => $viewData['noabsence']],
    ];

    $this->render('holidayedit', $viewData);
  }
}
