<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UserModel;

/**
 * Password Reset Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class PasswordResetController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    $UP           = new UserModel($this->DB->db, $this->CONF);
    $missingData  = false;
    $tokenExpired = false;

    if (isset($_GET['token'])) {
      $token = sanitize($_GET['token']);
      if (!$UP->findByToken($token)) {
        $missingData = true;
      }
      else {
        $now    = date('YmdHis');
        $expiry = $this->UO->read($UP->username, 'pwdTokenExpiry');
        if ($now > $expiry) {
          $tokenExpired = true;
        }
      }
    }
    else {
      $missingData = true;
    }

    if ($missingData) {
      $this->renderAlert('danger', $this->LANG['alert_alert_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
      return;
    }

    if ($tokenExpired) {
      $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['alert_pwdTokenExpired_subject'], $this->LANG['alert_pwdTokenExpired_text'], $this->LANG['alert_pwdTokenExpired_help']);
      return;
    }

    $viewData               = [];
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];

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

      $inputError = false;
      if (!formInputValid('txt_password', 'required|pwd' . $this->allConfig['pwdStrength'])) {
        $inputError = true;
      }
      if (!formInputValid('txt_password2', 'required|pwd' . $this->allConfig['pwdStrength'])) {
        $inputError = true;
      }
      if (!formInputValid('txt_password2', 'match', 'txt_password')) {
        $inputAlert['password2'] = sprintf($this->LANG['alert_input_match'], $this->LANG['profile_password2'], $this->LANG['profile_password']);
        $inputError              = true;
      }

      if (!$inputError) {
        if (
          isset($_POST['btn_update']) &&
          isset($_POST['txt_password']) && strlen($_POST['txt_password']) &&
          isset($_POST['txt_password2']) && strlen($_POST['txt_password2']) &&
          $_POST['txt_password'] == $_POST['txt_password2']
        ) {
          $UP->password       = password_hash(trim($_POST['txt_password']), PASSWORD_DEFAULT);
          $UP->last_pw_change = date('YmdHis');
          $UP->update($UP->username);
          $this->UO->deleteUserOption($UP->username, 'pwdToken');
          $this->UO->deleteUserOption($UP->username, 'pwdTokenExpiry');

          $this->LOG->logEvent("logUser", $this->UL->username, "log_user_pwd_reset", $UP->username);

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['profile_alert_update'];
          $alertData['text']    = $this->LANG['profile_alert_update_success'];
          $alertData['help']    = '';
        }

        if (isset($_SESSION)) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
      }
      else {
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_input'];
        $alertData['text']    = $this->LANG['profile_alert_save_failed'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $lang                              = $this->LANG;
    $lang['profile_password_comment'] .= $lang['password_rules_' . $this->allConfig['pwdStrength']];
    $viewData['LANG']                  = $lang;
    $viewData['personal']              = [
      ['prefix' => 'profile', 'name' => 'username', 'type' => 'text', 'placeholder' => '', 'value' => $UP->username, 'maxlength' => '80', 'disabled' => true],
      ['prefix' => 'profile', 'name' => 'lastname', 'type' => 'text', 'placeholder' => '', 'value' => $UP->lastname, 'maxlength' => '80', 'disabled' => true],
      ['prefix' => 'profile', 'name' => 'firstname', 'type' => 'text', 'placeholder' => '', 'value' => $UP->firstname, 'maxlength' => '80', 'disabled' => true],
      ['prefix' => 'profile', 'name' => 'password', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' => ($inputAlert['password'] ?? '')],
      ['prefix' => 'profile', 'name' => 'password2', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' => ($inputAlert['password2'] ?? '')],
    ];

    $this->render('passwordreset', $viewData);
  }
}
