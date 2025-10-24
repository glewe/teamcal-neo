<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Verify Controller
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
$missingData = false;

if (
  !isset($_GET['verify']) ||
  !isset($_GET['username']) ||
  strlen($_GET['verify']) <> 32 ||
  !in_array($_GET['username'], $U->getUsernames())
) {
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
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$UA = new Users(); // Used for admin user
$UA->findByName("admin");

//-----------------------------------------------------------------------------
// PROCESS URL
//
$ruser = trim($_GET['username']);
$rverify = trim($_GET['verify']);

if ($fverify = $UO->read($ruser, "verifycode")) {
  if ($fverify == $rverify) {
    //
    // Found the user and a matching verify code
    //
    $UO->deleteUserOption($ruser, "verifycode");
    $U->findByName($ruser);
    $fullname = $U->firstname . " " . $U->lastname;

    if ($C->read("adminApproval")) {
      //
      // Success but admin needs to approve.
      // Unset verify flag, keep account locked, send mail to admin.
      //
      $U->unverify($U->username);
      sendAccountNeedsApprovalMail($UA->email, $U->username, $U->lastname, $U->firstname);
      //
      // Log this event
      //
      $LOG->logEvent("logRegistration", $U->username, "log_user_verify_approval", $U->username . " (" . $fullname . ")");
      //
      // Success but approval needed
      //
      $showAlert = true;
      $alertData['type'] = 'info';
      $alertData['title'] = $LANG['alert_info_title'];
      $alertData['subject'] = $LANG['alert_reg_subject'];
      $alertData['text'] = $LANG['alert_reg_approval_needed'];
      $alertData['help'] = '';
    } else {
      //
      // Success and no approval needed. Unlock and unverify
      //
      $U->unlock($U->username);
      $U->unverify($U->username);
      //
      // Log this event
      //
      $LOG->logEvent("logRegistration", $U->username, "log_user_verify_unlocked", $U->username . " (" . $fullname . ")");
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['alert_reg_subject'];
      $alertData['text'] = $LANG['alert_reg_successful'];
      $alertData['help'] = '';
    }
  } else {
    //
    // Verify code mismatch
    //
    sendAccountVerificationMismatchMail($UA->email, $ruser, $fverify, $rverify);
    //
    // Log this event
    //
    $LOG->logEvent("logRegistration", $U->username, "log_user_verify_mismatch", $U->username . " (" . $fullname . "): " . $rverify . "<>" . $rverify);
    //
    // Verify code mismatch
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_reg_subject'];
    $alertData['text'] = $LANG['alert_reg_mismatch'];
    $alertData['help'] = '';
  }
} else {
  //
  // User or verify code does not exist
  //
  if (!$U->findByName($ruser)) {
    //
    // Log this event
    //
    $LOG->logEvent("logRegistration", $ruser, "log_user_verify_usr_notexist", $ruser . " : " . $rverify);
    //
    // Failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_reg_subject'];
    $alertData['text'] = $LANG['alert_reg_no_user'];
    $alertData['help'] = '';
  } else {
    //
    // Log this event
    //
    $LOG->logEvent("logRegistration", $ruser, "log_user_verify_code_notexist", $ruser . " : " . $rverify);
    //
    // Failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_reg_subject'];
    $alertData['text'] = $LANG['alert_reg_no_vcode'];
    $alertData['help'] = '';
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
