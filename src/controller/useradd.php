<?php
/**
 * User Edit Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
global $C;
global $CONF;
global $controller;
global $LANG;
global $LOG;
global $UP;
global $UO;

//-----------------------------------------------------------------------------
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission)) {
  $alertData['type'] = 'warning';
  $alertData['title'] = $LANG['alert_alert_title'];
  $alertData['subject'] = $LANG['alert_not_allowed_subject'];
  $alertData['text'] = $LANG['alert_not_allowed_text'];
  $alertData['help'] = $LANG['alert_not_allowed_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$UP = new Users(); // for the profile to be created
$inputAlert = array();

//-----------------------------------------------------------------------------
// PROCESS FORM
//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

  //
  // Sanitize input
  //
  $_POST = sanitize($_POST);

  //
  // CSRF token check
  //
  if (!isset($_POST['csrf_token']) || (isset($_POST['csrf_token']) && $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
    $alertData['type'] = 'warning';
    $alertData['title'] = $LANG['alert_alert_title'];
    $alertData['subject'] = $LANG['alert_csrf_invalid_subject'];
    $alertData['text'] = $LANG['alert_csrf_invalid_text'];
    $alertData['help'] = $LANG['alert_csrf_invalid_help'];
    require_once WEBSITE_ROOT . '/controller/alert.php';
    die();
  }

  //
  // Form validation
  //
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
  if (!formInputValid('txt_password', 'required|pwd' . $C->read('pwdStrength'))) {
    $inputError = true;
  }
  if (!formInputValid('txt_password2', 'required|pwd' . $C->read('pwdStrength'))) {
    $inputError = true;
  }
  if (!formInputValid('txt_password2', 'match', 'txt_password')) {
    $inputAlert['password2'] = sprintf($LANG['alert_input_match'], $LANG['profile_password2'], $LANG['profile_password']);
    $inputError = true;
  }
  if (!$inputError) {
    // ,--------,
    // | Create |
    // '--------'
    if (isset($_POST['btn_profileCreate'])) {
      //
      // Personal
      //
      $UP->username = $_POST['txt_username'];
      $UP->lastname = $_POST['txt_lastname'];
      $UP->firstname = $_POST['txt_firstname'];
      $UP->email = $_POST['txt_email'];
      //
      // Account
      //
      $UP->role = '2'; // Default role "User"
      $UP->locked = '0';
      $UP->hidden = '0';
      $UP->onhold = '0';
      $UP->verify = '0';
      $UP->bad_logins = '0';
      $UP->grace_start = DEFAULT_TIMESTAMP;
      $UP->last_login = DEFAULT_TIMESTAMP;
      $UP->created = date('YmdHis');
      //
      // Default user options
      //
      $UO->save($_POST['txt_username'], 'gender', 'male');
      $UO->save($_POST['txt_username'], 'avatar', 'default_male.png');
      $UO->save($_POST['txt_username'], 'language', 'default');
      //
      // Password
      //
      if (
        isset($_POST['txt_password']) && strlen($_POST['txt_password']) &&
        isset($_POST['txt_password2']) && strlen($_POST['txt_password2']) &&
        $_POST['txt_password'] == $_POST['txt_password2']
      ) {
        $UP->password = password_hash(trim($_POST['txt_password']), PASSWORD_DEFAULT);
        $UP->last_pw_change = date('YmdHis');
      }
      $UP->create();
      //
      // Send notification e-mail to the created uses
      //
      if (isset($_POST['chk_create_mail'])) {
        sendAccountCreatedMail($UP->email, $UP->username, $_POST['txt_password']);
      }
      //
      // Send notification e-mails to the subscribers of user events
      //
      if ($C->read("emailNotifications")) {
        sendUserEventNotifications("created", $UP->username, $UP->firstname, $UP->lastname);
      }
      //
      // Log this event
      //
      $LOG->logEvent("logUser", L_USER, "log_user_added", $UP->username);
      //
      // Input validation failed
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['profile_alert_create'];
      $alertData['text'] = $LANG['profile_alert_create_success'];
      $alertData['help'] = '';
    }
    //
    // Renew CSRF token after successful form processing
    //
    if (isset($_SESSION)) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_input'];
    $alertData['text'] = $LANG['profile_alert_save_failed'];
    $alertData['help'] = '';
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$LANG['profile_password_comment'] .= $LANG['password_rules_' . $C->read('pwdStrength')];
$viewData['personal'] = array(
  array( 'prefix' => 'profile', 'name' => 'username', 'type' => 'text', 'placeholder' => '', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' => (isset($inputAlert['username']) ? $inputAlert['username'] : '') ),
  array( 'prefix' => 'profile', 'name' => 'lastname', 'type' => 'text', 'placeholder' => '', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' => (isset($inputAlert['lastname']) ? $inputAlert['lastname'] : '') ),
  array( 'prefix' => 'profile', 'name' => 'firstname', 'type' => 'text', 'placeholder' => '', 'value' => '', 'maxlength' => '80', 'mandatory' => false, 'error' => (isset($inputAlert['firstname']) ? $inputAlert['firstname'] : '') ),
  array( 'prefix' => 'profile', 'name' => 'email', 'type' => 'text', 'placeholder' => '', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' => (isset($inputAlert['email']) ? $inputAlert['email'] : '') ),
  array( 'prefix' => 'profile', 'name' => 'password', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' => (isset($inputAlert['password']) ? $inputAlert['password'] : '') ),
  array( 'prefix' => 'profile', 'name' => 'password2', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' => (isset($inputAlert['password2']) ? $inputAlert['password2'] : '') ),
  array( 'prefix' => 'profile', 'name' => 'create_mail', 'type' => 'check', 'value' => '0' ),
);

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
