<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Patterns Controller
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
global $PTN;

$allConfig = $C->readAll();

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
$alertData = array();
$showAlert = false;
$licExpiryWarning = $allConfig['licExpiryWarning'];
$LIC = new License();
$LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//
$PTN = new Patterns();

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];
$viewData['currentYearOnly'] = $allConfig['currentYearOnly'];
$viewData['txt_name'] = '';
$viewData['txt_description'] = '';

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
  //
  // Validate input data. If something is wrong or missing, set $inputError = true
  //
  if (!$inputError) {
    // ,--------,
    // | Create |
    // '--------'
    if (isset($_POST['btn_roleCreate']) && $_POST['btn_roleCreate'] === '1') {
      //
      // Form validation
      //
      $inputError = false;
      if (
        !formInputValid('txt_name', 'required|alpha_numeric_dash') ||
        !formInputValid('txt_description', 'alpha_numeric_dash_blank')
      ) {
        $inputError = true;
        $alertData['text'] = $LANG['roles_alert_created_fail_input'];
      }
      $viewData['txt_name'] = htmlspecialchars($_POST['txt_name'] ?? '', ENT_QUOTES, 'UTF-8');

      if (isset($_POST['txt_description'])) {
        $viewData['txt_description'] = htmlspecialchars($_POST['txt_description'], ENT_QUOTES, 'UTF-8');
      }

      if (!$inputError) {
        $PTN->name = $viewData['txt_name'];
        $PTN->description = $viewData['txt_description'];
        $PTN->abs1 = $_POST['sel_abs1'];
        $PTN->abs2 = $_POST['sel_abs2'];
        $PTN->abs3 = $_POST['sel_abs3'];
        $PTN->abs4 = $_POST['sel_abs4'];
        $PTN->abs5 = $_POST['sel_abs5'];
        $PTN->abs6 = $_POST['sel_abs6'];
        $PTN->abs7 = $_POST['sel_abs7'];
        $PTN->create();
        //
        // Log this event
        //
        $LOG->logEvent("logPattern", L_USER, "log_pattern_created", $PTN->name . " " . $PTN->description);
        //
        // Success
        //
        $showAlert = true;
        $alertData['type'] = 'success';
        $alertData['title'] = $LANG['alert_success_title'];
        $alertData['subject'] = $LANG['btn_create_pattern'];
        $alertData['text'] = $LANG['roles_alert_created'];
        $alertData['help'] = '';
      } else {
        //
        // Fail
        //
        $showAlert = true;
        $alertData['type'] = 'danger';
        $alertData['title'] = $LANG['alert_danger_title'];
        $alertData['subject'] = $LANG['btn_create_pattern'];
        $alertData['help'] = '';
      }
    }
    // ,--------,
    // | Delete |
    // '--------'
    elseif (isset($_POST['btn_patternDelete']) && $_POST['btn_patternDelete'] === '1') {
      //
      // Delete Pattern
      //
      $PTN->delete($_POST['hidden_id']);
      //
      // Log this event
      //
      $LOG->logEvent("logRole", L_USER, "log_pattern_deleted", $_POST['hidden_name']);
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_delete_pattern'];
      $alertData['text'] = $LANG['ptn_alert_deleted'];
      $alertData['help'] = '';
    }
    //
    // Renew CSRF token after successful form processing
    //
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_input'];
    $alertData['text'] = $LANG['ptn_alert_created_failed'];
    $alertData['help'] = '';
  }
}

//-----------------------------------------------------------------------------
//
// PREPARE VIEW
//

//
// Default: Get all patterns
//
$viewData['patterns'] = $PTN->getAll();
$viewData['searchPattern'] = '';

//-----------------------------------------------------------------------------
//
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
