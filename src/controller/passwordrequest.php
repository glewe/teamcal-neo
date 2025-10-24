<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Password Request Controller
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
// VARIABLE DEFAULTS
//
$showAlert = false;
$viewData['email'] = '';
$viewData['multipleUsers'] = false;

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
  if (!formInputValid('txt_email', 'required|email')) {
    $inputError = true;
  }

  if (!$inputError) {
    $email = $_POST['txt_email'];
    $viewData['email'] = $email;

    // ,-------,
    // | Reset |
    // '-------'
    if (isset($_POST['btn_request_password'])) {
      if ($pwdUsers = $U->getAllForEmail($email)) {
        if (count($pwdUsers) === 1) {
          //
          // One user found with the given email address. Create a token.
          //
          $token = hash('md5', 'PasswordResetRequestFor' . $pwdUsers[0]['username']);
          $expiryDateTime = date('YmdHis', strtotime(date('YmdHis') . ' +1 day'));
          $UO->save($pwdUsers[0]['username'], 'pwdTokenExpiry', $expiryDateTime);
          sendPasswordResetMail($pwdUsers[0]['email'], $pwdUsers[0]['username'], $pwdUsers[0]['lastname'], $pwdUsers[0]['firstname'], $token);

          //
          // Log this event
          //
          $LOG->logEvent("logUser", L_USER, "log_user_pwd_request", $pwdUsers[0]['username']);

          //
          // Success
          //
          $showAlert = true;
          $alertData['type'] = 'success';
          $alertData['title'] = $LANG['alert_success_title'];
          $alertData['subject'] = $LANG['pwdreq_title'];
          $alertData['text'] = $LANG['pwdreq_alert_success'];
          $alertData['help'] = '';
        } else {
          if (isset($_POST['opt_user'])) {
            $pwdUser = $U->findByName($_POST['opt_user']);
            $token = hash('md5', 'PasswordResetRequestFor' . $U->username);
            $expiryDateTime = date('YmdHis', strtotime(date('YmdHis') . ' +1 day'));
            $UO->save($U->username, 'pwdTokenExpiry', $expiryDateTime);
            sendPasswordResetMail($U->email, $U->username, $U->lastname, $U->firstname, $token);

            //
            // Log this event
            //
            $LOG->logEvent("logUser", L_USER, "log_user_pwd_request", $U->username);

            //
            // Success
            //
            $showAlert = true;
            $alertData['type'] = 'success';
            $alertData['title'] = $LANG['alert_success_title'];
            $alertData['subject'] = $LANG['pwdreq_title'];
            $alertData['text'] = $LANG['pwdreq_alert_success'];
            $alertData['help'] = '';
          } else {
            $viewData['multipleUsers'] = true;
            $viewData['pwdUsers'] = $pwdUsers;
          }
        }
      } else {
        //
        // Email not found
        //
        $showAlert = true;
        $alertData['type'] = 'warning';
        $alertData['title'] = $LANG['alert_warning_title'];
        $alertData['subject'] = $LANG['pwdreq_alert_notfound'];
        $alertData['text'] = $LANG['pwdreq_alert_notfound_text'];
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
    $alertData['text'] = $LANG['pwdreq_alert_failed'];
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
