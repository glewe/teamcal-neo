<?php
/**
 * Setup 2FA Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.7.0
 */
global $C;
global $CONF;
global $controller;
global $LANG;
global $LOG;
global $UO;
global $UL;
global $UP;

use RobThree\Auth\TwoFactorAuth;

//-----------------------------------------------------------------------------
// CHECK 2FA DISABLED
//
if ($C->read('disableTfa')) {
  $alertData['type'] = 'info';
  $alertData['title'] = $LANG['alert_info_title'];
  $alertData['subject'] = $LANG['alert_not_enabled_subject'];
  $alertData['text'] = $LANG['alert_not_enabled_text'];
  $alertData['help'] = '';
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//-----------------------------------------------------------------------------
// CHECK URL PARAMETERS
//
$UP = new Users(); // for the profile to be created or updated
if (isset($_GET['profile'])) {
  $missingData = false;
  $profile = sanitize($_GET['profile']);
  if (!$UP->findByName($profile)) {
    $missingData = true;
  }
} else {
  $missingData = true;
}

if ($missingData) {
  //
  // URL param fail
  //
  $alertData['type'] = 'danger';
  $alertData['title'] = $LANG['alert_danger_title'];
  $alertData['subject'] = $LANG['alert_no_data_subject'];
  $alertData['text'] = $LANG['alert_no_data_text'];
  $alertData['help'] = $LANG['alert_no_data_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//-----------------------------------------------------------------------------
// CHECK PERMISSION
//
$allowed = false;
if ($UL->username == $profile) {
  $allowed = true;
}

if (!$allowed) {
  $alertData['type'] = 'warning';
  $alertData['title'] = $LANG['alert_warning_title'];
  $alertData['subject'] = $LANG['alert_not_allowed_subject'];
  $alertData['text'] = $LANG['alert_not_allowed_text'];
  $alertData['help'] = $LANG['alert_not_allowed_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//-----------------------------------------------------------------------------
// CHECK EXISTING SECRET
//
if ($UO->read($profile, 'secret')) {
  $alertData['type'] = 'warning';
  $alertData['title'] = $LANG['alert_warning_title'];
  $alertData['subject'] = $LANG['alert_secret_exists_subject'];
  $alertData['text'] = $LANG['alert_secret_exists_text'];
  $alertData['help'] = $LANG['alert_secret_exists_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//
$tfa = new TwoFactorAuth('TeamCal Neo');

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$secret = $tfa->createSecret();
$bcode = $tfa->getQRCodeImageAsDataUri($UL->username, $secret);

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
  if (isset($_POST['btn_verify']) && !formInputValid('txt_totp', 'numeric')) {
    $inputError = true;
  }

  if (!$inputError) {
    // ,--------,
    // | Verify |
    // '--------'
    if (isset($_POST['btn_verify'])) {
      $secret = $_POST['hidden_s'];
      $totp = $_POST['txt_totp'];
      $verifyResult = $tfa->verifyCode($secret, $totp);
      if ($verifyResult) {
        //
        // Success
        //
        $UO->save($profile, 'secret', openssl_encrypt($secret, "AES-128-ECB", APP_LIC_KEY));
        //
        // Log this event.
        //
        $LOG->logEvent("logUser", L_USER, "log_user_updated", $UP->username);
        //
        // Log out so a new login with 2FA is needed.
        //
        $L->logout();
        $alertData['type'] = 'success';
        $alertData['title'] = $LANG['alert_success_title'];
        $alertData['subject'] = $LANG['profile_alert_update'];
        $alertData['text'] = $LANG['setup2fa_alert_success'];
        $alertData['help'] = '';
        require_once WEBSITE_ROOT . '/controller/alert.php';
        die();
      } else {
        $secret = $_POST['hidden_s'];
        $bcode = $tfa->getQRCodeImageAsDataUri($UL->email, $secret);
        //
        // Code mismatch
        //
        $showAlert = true;
        $alertData['type'] = 'warning';
        $alertData['title'] = $LANG['alert_warning_title'];
        $alertData['subject'] = $LANG['alert_input'];
        $alertData['text'] = $LANG['setup2fa_alert_mismatch'];
        $alertData['help'] = '';
      }
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
    $alertData['text'] = $LANG['setup2fa_alert_input'];
    $alertData['help'] = $LANG['setup2fa_alert_input_help'];
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$viewData['profile'] = $profile;
$viewData['fullname'] = $UP->firstname . ' ' . $UP->lastname . ' (' . $UP->username . ')';
$viewData['secret'] = $secret;
$viewData['bcode'] = $bcode;

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
