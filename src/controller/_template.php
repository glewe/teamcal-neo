<?php
/**
 * Controller Template
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
global $RO;
global $U;
global $UL;
global $UG;
global $UR;

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
// CHECK URL PARAMETERS
//
$missingData = false;

//
// Check $_GET data. If something is wrong or missing, set $missingData = true
//
// TODO

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

//-----------------------------------------------------------------------------
// PROCESS FORM
//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

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
  // Sanitize input
  //
  $_POST = sanitize($_POST);

  //
  // Form validation
  //
  $inputError = false;

  //
  // Validate input data. If something is wrong or missing, set $inputError = true
  //
  // TODO

  if (!$inputError) {
    // ,------,
    // | Save |
    // '------'
    if (isset($_POST['btn_save'])) {
      $reloadPage = false;

      //
      // Process form input. If a page reload is necessary to show the changes, set $reloadPage = true
      //
      // TODO

      //
      // Log this event
      //
      $LOG->logEvent("logUser", L_USER, "log_user_registered", $UR->username);

      //
      // Reload page in case of language change, so it takes effect.
      //
      if ($reloadPage) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller);
        die();
      }

      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['register_title'];
      $alertData['text'] = $LANG['register_alert_success'];
      $alertData['help'] = '';
    }
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_input'];
    $alertData['text'] = $LANG['register_alert_failed'];
    $alertData['help'] = '';
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$viewData['sample'] = '';

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
