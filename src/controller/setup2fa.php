<?php
if (!defined('VALID_ROOT')) {
  exit('');
}

/**
 * Setup 2FA Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Controllers
 * @since 3.7.0
 */

use RobThree\Auth\TwoFactorAuth;

//=============================================================================
//
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

//=============================================================================
//
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

//=============================================================================
//
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

//=============================================================================
//
// LOAD CONTROLLER RESOURCES
//
$tfa = new TwoFactorAuth('TeamCal Neo');

//=============================================================================
//
// VARIABLE DEFAULTS
//
$secret = $tfa->createSecret();
$bcode = $tfa->getQRCodeImageAsDataUri($UL->username, $secret);

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST)) {
  //
  // Sanitize input
  //
  $_POST = sanitize($_POST);
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

//=============================================================================
//
// PREPARE VIEW
//
$viewData['profile'] = $profile;
$viewData['fullname'] = $UP->firstname . ' ' . $UP->lastname . ' (' . $UP->username . ')';
$viewData['secret'] = $secret;
$viewData['bcode'] = $bcode;

//=============================================================================
//
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
