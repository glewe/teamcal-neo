<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Login 2FA Controller
 * Handles the second step of login for users with 2FA enabled.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     4.3.0
 */
global $C;
global $CONF;
global $controller;
global $LANG;
global $LOG;
global $L;
global $U;
global $UMSG;
global $UO;

use RobThree\Auth\TwoFactorAuth;

$tfa = new TwoFactorAuth('TeamCal Neo');

$showAlert = false;
$alertData = [];

if (!isset($_SESSION['2fa_user'])) {
  //
  // No 2FA session, redirect to login
  //
  header('Location: index.php?action=login');
  exit;
}

$uname = $_SESSION['2fa_user'];
$pword = $_SESSION['2fa_pword'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
  //
  // Sanitize input
  //
  $_POST = sanitize($_POST);
  //
  // CSRF token check
  //
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $alertData['type'] = 'warning';
    $alertData['title'] = $LANG['alert_alert_title'];
    $alertData['subject'] = $LANG['alert_csrf_invalid_subject'];
    $alertData['text'] = $LANG['alert_csrf_invalid_text'];
    $alertData['help'] = $LANG['alert_csrf_invalid_help'];
    require_once WEBSITE_ROOT . '/controller/alert.php';
    die();
  }
  $inputError = false;
  if (!isset($_POST['totp']) || !preg_match('/^[0-9]{6}$/', $_POST['totp'])) {
    $inputError = true;
    $showAlert = true;
    $alertData['type'] = 'warning';
    $alertData['title'] = $LANG['alert_warning_title'];
    $alertData['subject'] = $LANG['login_error_2fa'];
    $alertData['text'] = $LANG['login_error_2fa_text'];
    $alertData['help'] = '';
    $LOG->logEvent('logLogin', $uname, 'log_login_2fa');
  }
  if (!$inputError) {
    $userSecret = openssl_decrypt($UO->read($uname, 'secret'), 'AES-128-ECB', APP_LIC_KEY);
    $totp = $_POST['totp'];
    $result = $tfa->verifyCode($userSecret, $totp);
    if ($result) {
      //
      // Code matches. Log the user in.
      //
      $L->loginUser($uname, $pword);
      $LOG->logEvent('logLogin', $uname, 'log_login_success');
      unset($_SESSION['2fa_user'], $_SESSION['2fa_pword']);
      //
      // Check for popup announcements
      //
      $popups = $UMSG->getAllPopupByUser($uname);
      if (count($popups)) {
        header('Location: index.php?action=messages');
      } else {
        header('Location: index.php?action=' . $C->read('homepage'));
      }
      exit;
    } else {
      $showAlert = true;
      $alertData['type'] = 'warning';
      $alertData['title'] = $LANG['alert_warning_title'];
      $alertData['subject'] = $LANG['login_error_2fa'];
      $alertData['text'] = $LANG['login_error_2fa_text'];
      $alertData['help'] = '';
      $LOG->logEvent('logLogin', $uname, 'log_login_2fa');
    }
  }
  // 
  // Renew CSRF token after processing
  //
  if (isset($_SESSION)) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
}

require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php'; 