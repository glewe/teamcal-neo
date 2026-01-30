<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UserModel;

/**
 * User Add Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class UserAddController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    // Check Permission
    if (!isAllowed($this->CONF['controllers']['useradd']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $UP = new UserModel();
    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];
    /** @var array<string, string> $alertData */
    $alertData = [];
    $showAlert = false;

    $viewData               = [];
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      // CSRF Check
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;
      if (!formInputValid('txt_username', 'required|username'))
        $inputError = true;
      if (!formInputValid('txt_lastname', 'required|alpha_numeric_dash_blank_dot'))
        $inputError = true;
      if (!formInputValid('txt_firstname', 'alpha_numeric_dash_blank_dot'))
        $inputError = true;
      if (!formInputValid('txt_email', 'required|email'))
        $inputError = true;
      if (!formInputValid('txt_password', 'required|pwd' . $this->allConfig['pwdStrength']))
        $inputError = true;
      if (!formInputValid('txt_password2', 'required|pwd' . $this->allConfig['pwdStrength']))
        $inputError = true;
      if (!formInputValid('txt_password2', 'match', 'txt_password')) {
        $inputAlert['password2'] = sprintf($this->LANG['alert_input_match'], $this->LANG['profile_password2'], $this->LANG['profile_password']);
        $inputError              = true;
      }

      if (!$inputError) {
        if (isset($_POST['btn_profileCreate'])) {
          $UP->username    = $_POST['txt_username'];
          $UP->lastname    = $_POST['txt_lastname'];
          $UP->firstname   = $_POST['txt_firstname'];
          $UP->email       = $_POST['txt_email'];
          $UP->role        = 2;
          $UP->locked      = 0;
          $UP->hidden      = 0;
          $UP->onhold      = 0;
          $UP->verify      = 0;
          $UP->bad_logins  = 0;
          $UP->grace_start = DEFAULT_TIMESTAMP;
          $UP->last_login  = DEFAULT_TIMESTAMP;
          $UP->created     = date('YmdHis');

          $this->UO->save($_POST['txt_username'], 'gender', 'male');
          $this->UO->save($_POST['txt_username'], 'avatar', 'default_male.png');
          $this->UO->save($_POST['txt_username'], 'language', 'default');

          if (isset($_POST['txt_password']) && isset($_POST['txt_password2']) && $_POST['txt_password'] == $_POST['txt_password2']) {
            $UP->password       = password_hash(trim($_POST['txt_password']), PASSWORD_DEFAULT);
            $UP->last_pw_change = date('YmdHis');
          }
          $UP->create();

          if (isset($_POST['chk_create_mail'])) {
            sendAccountCreatedMail($UP->email, $UP->username, $_POST['txt_password']);
          }

          if ($this->allConfig['emailNotifications']) {
            sendUserEventNotifications("created", $UP->username, $UP->firstname, $UP->lastname);
          }

          $this->LOG->logEvent("logUser", $this->UL->username, "log_user_added", $UP->username);

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['profile_alert_create'];
          $alertData['text']    = $this->LANG['profile_alert_create_success'];
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

    // Ensure LANG is loaded
    $noop                                                  = $this->LANG;
    $this->_instances['LANG']['profile_password_comment'] .= $this->LANG['password_rules_' . $this->allConfig['pwdStrength']];
    $viewData['personal']                                  = [
      ['prefix' => 'profile', 'name' => 'username', 'type' => 'text', 'placeholder' => '', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' => ($inputAlert['username'] ?? '')],
      ['prefix' => 'profile', 'name' => 'lastname', 'type' => 'text', 'placeholder' => '', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' => ($inputAlert['lastname'] ?? '')],
      ['prefix' => 'profile', 'name' => 'firstname', 'type' => 'text', 'placeholder' => '', 'value' => '', 'maxlength' => '80', 'mandatory' => false, 'error' => ($inputAlert['firstname'] ?? '')],
      ['prefix' => 'profile', 'name' => 'email', 'type' => 'text', 'placeholder' => '', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' => ($inputAlert['email'] ?? '')],
      ['prefix' => 'profile', 'name' => 'password', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' => ($inputAlert['password'] ?? '')],
      ['prefix' => 'profile', 'name' => 'password2', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' => ($inputAlert['password2'] ?? '')],
      ['prefix' => 'profile', 'name' => 'create_mail', 'type' => 'check', 'value' => '0'],
    ];

    $this->render('useradd', $viewData);
  }
}
