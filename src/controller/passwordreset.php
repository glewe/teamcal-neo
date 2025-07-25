<?php
/**
 * Password Reset Controller
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
global $U;
global $UO;

//-----------------------------------------------------------------------------
// CHECK URL PARAMETERS
//
$UP = new Users(); // for the profile to be updated
if (isset($_GET['token'])) {
  $missingData = false;
  $token = sanitize($_GET['token']);
  if (!$UP->findByToken($token)) {
    $missingData = true;
  } else {
    $tokenExpired = false;
    $now = date('YmdHis');
    $expiry = $UO->read($UP->username, 'pwdTokenExpiry');
    if ($now > $expiry) {
      $tokenExpired = true;
    }
  }
} else {
  $missingData = true;
}

if ($missingData) {
  //
  // URL param fail
  //
  $alertData['type'] = 'danger';
  $alertData['title'] = $LANG['alert_alert_title'];
  $alertData['subject'] = $LANG['alert_no_data_subject'];
  $alertData['text'] = $LANG['alert_no_data_text'];
  $alertData['help'] = $LANG['alert_no_data_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

if ($tokenExpired) {
  //
  // Token expired
  //
  $alertData['type'] = 'warning';
  $alertData['title'] = $LANG['alert_warning_title'];
  $alertData['subject'] = $LANG['alert_pwdTokenExpired_subject'];
  $alertData['text'] = $LANG['alert_pwdTokenExpired_text'];
  $alertData['help'] = $LANG['alert_pwdTokenExpired_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//-----------------------------------------------------------------------------
// CHECK PERMISSION
//

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$inputAlert = array();

//-----------------------------------------------------------------------------
//
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
    // | Update |
    // '--------'
    if (
      isset($_POST['btn_update']) &&
      isset($_POST['txt_password']) && strlen($_POST['txt_password']) &&
      isset($_POST['txt_password2']) && strlen($_POST['txt_password2']) &&
      $_POST['txt_password'] == $_POST['txt_password2']
    ) {
      //
      // Password
      //
      $UP->password = password_hash(trim($_POST['txt_password']), PASSWORD_DEFAULT);
      $UP->last_pw_change = date('YmdHis');
      $UP->update($UP->username);
      $UO->deleteUserOption($UP->username, 'pwdTokenExpiry');
      //
      // Log this event
      //
      $LOG->logEvent("logUser", L_USER, "log_user_pwd_reset", $UP->username);
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['profile_alert_update'];
      $alertData['text'] = $LANG['profile_alert_update_success'];
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
  array( 'prefix' => 'profile', 'name' => 'username', 'type' => 'text', 'placeholder' => '', 'value' => $UP->username, 'maxlength' => '80', 'disabled' => true ),
  array( 'prefix' => 'profile', 'name' => 'lastname', 'type' => 'text', 'placeholder' => '', 'value' => $UP->lastname, 'maxlength' => '80', 'disabled' => true ),
  array( 'prefix' => 'profile', 'name' => 'firstname', 'type' => 'text', 'placeholder' => '', 'value' => $UP->firstname, 'maxlength' => '80', 'disabled' => true ),
  array( 'prefix' => 'profile', 'name' => 'password', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' => (isset($inputAlert['password']) ? $inputAlert['password'] : '') ),
  array( 'prefix' => 'profile', 'name' => 'password2', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' => (isset($inputAlert['password2']) ? $inputAlert['password2'] : '') ),
);

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
