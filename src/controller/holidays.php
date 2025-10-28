<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Holidays Controller
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
global $H;
global $LIC;

$allConfig = $C->readAll();
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];

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
// CHECK LICENSE
//
$date = new DateTime();
$weekday = $date->format('N');
if ($weekday == rand(1, 7)) {
  $alertData = array();
  $showAlert = false;
  $licExpiryWarning = $allConfig['licExpiryWarning'];
  $LIC = new License();
  $LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$viewData['txt_name'] = '';
$viewData['txt_description'] = '';

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

  // ,--------,
  // | Create |
  // '--------'
  if (isset($_POST['btn_holCreate'])) {
    //
    // Form validation
    //
    $inputAlert = array();
    $inputError = false;
    if (!formInputValid('txt_name', 'required|alpha_numeric_dash_blank')) {
      $inputError = true;
    }
    if (!formInputValid('txt_description', 'alpha_numeric_dash_blank')) {
      $inputError = true;
    }

    $viewData['txt_name'] = $_POST['txt_name'] ?? '';
    $viewData['txt_description'] = $_POST['txt_description'] ?? '';

    if (!$inputError) {
      $HH = new Holidays();
      $HH->name = $viewData['txt_name'];
      $HH->description = $viewData['txt_description'];
      $HH->create();
      //
      // Send notification e-mails to the subscribers of group events
      //
      if ($allConfig['emailNotifications']) {
        sendHolidayEventNotifications("created", $HH->name, $HH->description);
      }
      //
      // Log this event
      //
      $LOG->logEvent("logHoliday", L_USER, "log_abs_created", $HH->name);
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_create_abs'];
      $alertData['text'] = $LANG['hol_alert_created'];
      $alertData['help'] = '';
      //
      // Renew CSRF token after successful create
      //
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } else {
      //
      // Input validation failed
      //
      $showAlert = true;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['btn_create_abs'];
      $alertData['text'] = $LANG['hol_alert_created_fail'];
      $alertData['help'] = '';
    }
  }
  // ,--------,
  // | Delete |
  // '--------'
  elseif (isset($_POST['btn_holDelete'])) {
    $H->delete($_POST['hidden_id'] ?? '');
    //
    // Send notification e-mails to the subscribers of group events
    //
    if ($allConfig['emailNotifications']) {
      sendHolidayEventNotifications("deleted", $_POST['hidden_name'] ?? '', $_POST['hidden_description'] ?? '');
    }
    //
    // Log this event
    //
    $LOG->logEvent("logHoliday", L_USER, "log_hol_deleted", $_POST['hidden_name'] ?? '');
    //
    // Success
    //
    $showAlert = true;
    $alertData['type'] = 'success';
    $alertData['title'] = $LANG['alert_success_title'];
    $alertData['subject'] = $LANG['btn_delete_holiday'];
    $alertData['text'] = $LANG['hol_alert_deleted'];
    $alertData['help'] = '';
    //
    // Renew CSRF token after successful delete
    //
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
}

//-----------------------------------------------------------------------------
//
// PREPARE VIEW
//
$viewData['holidays'] = $H->getAll();
asort($viewData['holidays']);

//-----------------------------------------------------------------------------
//
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
