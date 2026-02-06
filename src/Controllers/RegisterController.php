<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UserModel;
use App\Services\CaptchaService;

/**
 * Register Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class RegisterController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!$this->allConfig['allowRegistration']) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $captchaService = new CaptchaService();
    $UR             = new UserModel($this->DB->db, $this->CONF);
    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];
    $alertData  = [];
    $showAlert  = false;

    $viewData               = [];
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;
      if (!formInputValid('txt_username', 'required|username')) {
        $inputError = true;
      }
      if (!formInputValid('txt_lastname', 'required|alpha_numeric_dash_blank_dot')) {
        $inputError = true;
      }
      if (!formInputValid('txt_firstname', 'alpha_numeric_dash_blank_dot')) {
        $inputError = true;
      }
      if (!formInputValid('txt_email', 'required|email')) {
        $inputError = true;
      }
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
      if (!formInputValid('txt_code', 'required|alpha_numeric')) {
        $inputError = true;
      }

      if (!$inputError) {
        if (isset($_POST['btn_register'])) {
          if (!$captchaService->verifyHoneypot($_POST) || !$captchaService->verifyAnswer($_POST['txt_code'])) {
            $showAlert            = true;
            $alertData['type']    = 'warning';
            $alertData['title']   = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['alert_captcha_wrong'];
            $alertData['text']    = $this->LANG['alert_captcha_wrong_text'];
            $alertData['help']    = $this->LANG['alert_captcha_wrong_help'];
          }
          else {
            $UR->username       = $_POST['txt_username'];
            $UR->lastname       = $_POST['txt_lastname'];
            $UR->firstname      = $_POST['txt_firstname'];
            $UR->email          = $_POST['txt_email'];
            $UR->role           = 2;
            $UR->locked         = 1;
            $UR->hidden         = 0;
            $UR->onhold         = 0;
            $UR->verify         = 1;
            $UR->bad_logins     = 0;
            $UR->grace_start    = DEFAULT_TIMESTAMP;
            $UR->last_login     = DEFAULT_TIMESTAMP;
            $UR->created        = date('YmdHis');
            $UR->password       = password_hash(trim($_POST['txt_password']), PASSWORD_DEFAULT);
            $UR->last_pw_change = date('YmdHis');
            $UR->create();

            $this->UO->save($UR->username, 'avatar', 'default_male.png');

            $alphanum   = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $verifycode = substr(str_shuffle($alphanum), 0, 32);
            $this->UO->save($UR->username, "verifycode", $verifycode);

            $mailError = '';
            sendAccountRegisteredMail($UR->email, $UR->username, $UR->lastname, $UR->firstname, $verifycode, $mailError);

            $this->LOG->logEvent("logRegistration", $this->UL->username, "log_user_registered", $UR->username);

            $showAlert            = true;
            $alertData['type']    = (empty($mailError)) ? 'success' : 'warning';
            $alertData['title']   = (empty($mailError)) ? $this->LANG['alert_success_title'] : $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['register_title'];
            $alertData['text']    = $this->LANG['register_alert_success'];
            if (!empty($mailError)) {
              $alertData['text'] .= '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
            }
            $alertData['help'] = (empty($mailError)) ? '' : $this->LANG['contact_administrator'];
          }
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
        $alertData['text']    = $this->LANG['register_alert_failed'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $lang                               = $this->LANG;
    $lang['register_password_comment'] .= $lang['password_rules_' . $this->allConfig['pwdStrength']];
    $viewData['LANG']                   = $lang;
    $viewData['personal']               = [
      ['prefix' => 'register', 'name' => 'username', 'type' => 'text', 'placeholder' => '', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' => ($inputAlert['username'] ?? '')],
      ['prefix' => 'register', 'name' => 'lastname', 'type' => 'text', 'placeholder' => '', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' => ($inputAlert['lastname'] ?? '')],
      ['prefix' => 'register', 'name' => 'firstname', 'type' => 'text', 'placeholder' => '', 'value' => '', 'maxlength' => '80', 'mandatory' => false, 'error' => ($inputAlert['firstname'] ?? '')],
      ['prefix' => 'register', 'name' => 'email', 'type' => 'text', 'placeholder' => '', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' => ($inputAlert['email'] ?? '')],
      ['prefix' => 'register', 'name' => 'password', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' => ($inputAlert['password'] ?? '')],
      ['prefix' => 'register', 'name' => 'password2', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' => ($inputAlert['password2'] ?? '')],
      ['prefix' => 'register', 'name' => 'code', 'type' => 'captcha', 'question' => $captchaService->generateQuestion(), 'value' => '', 'maxlength' => '6', 'mandatory' => true, 'error' => ($inputAlert['code'] ?? '')],
    ];

    $this->render('register', $viewData);
  }
}
