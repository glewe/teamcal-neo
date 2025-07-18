<?php
/**
 * Pattern Edit Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.0.0
 */
global $C;
global $CONF;
global $controller;
global $LANG;
global $LOG;
global $PTN;
global $A;

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
$PTN = new Patterns();
if (isset($_GET['id'])) {
  $missingData = false;
  $id = sanitize($_GET['id']);
  if (!$PTN->get($id)) {
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
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$viewData['PTN'] = $PTN;
$viewData['id'] = $PTN->id;
$viewData['name'] = $PTN->name;
$viewData['description'] = $PTN->description;
$viewData['abs1'] = $PTN->abs1;
$viewData['abs2'] = $PTN->abs2;
$viewData['abs3'] = $PTN->abs3;
$viewData['abs4'] = $PTN->abs4;
$viewData['abs5'] = $PTN->abs5;
$viewData['abs6'] = $PTN->abs6;
$viewData['abs7'] = $PTN->abs7;
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
  // Load sanitized form info for the view
  //
  $viewData['name'] = $_POST['txt_name'];
  $viewData['description'] = $_POST['txt_description'];
  //
  // Form validation
  //
  $inputError = false;
  if (!formInputValid('txt_name', 'required|alpha_numeric_dash_blank') ||
    !formInputValid('txt_description', 'alpha_numeric_dash_blank_special') ||
    !formInputValid('sel_abs1', 'numeric') ||
    !formInputValid('sel_abs2', 'numeric') ||
    !formInputValid('sel_abs3', 'numeric') ||
    !formInputValid('sel_abs4', 'numeric') ||
    !formInputValid('sel_abs5', 'numeric') ||
    !formInputValid('sel_abs6', 'numeric') ||
    !formInputValid('sel_abs7', 'numeric')
  ) {
    $inputError = true;
  }

  if (!$inputError) {
    // ,--------,
    // | Update |
    // '--------'
    if (isset($_POST['btn_update'])) {

      if ($PTN->abs1 != $_POST['sel_abs1'] ||
        $PTN->abs2 != $_POST['sel_abs2'] ||
        $PTN->abs3 != $_POST['sel_abs3'] ||
        $PTN->abs4 != $_POST['sel_abs4'] ||
        $PTN->abs5 != $_POST['sel_abs5'] ||
        $PTN->abs6 != $_POST['sel_abs6'] ||
        $PTN->abs7 != $_POST['sel_abs7']
      ) {
        //
        // The pattern has changed. Check whether the new combination already exists
        //
        $abs1 = $_POST['sel_abs1'];
        $abs2 = $_POST['sel_abs2'];
        $abs3 = $_POST['sel_abs3'];
        $abs4 = $_POST['sel_abs4'];
        $abs5 = $_POST['sel_abs5'];
        $abs6 = $_POST['sel_abs6'];
        $abs7 = $_POST['sel_abs7'];
        $checkPattern = [ 0, $abs1, $abs2, $abs3, $abs4, $abs5, $abs6, $abs7 ];
        if ($name = $PTN->patternExists($checkPattern)) {
          //
          // New pattern already exists
          //
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['btn_create_pattern'];
          $alertData['text'] = sprintf($LANG['ptn_alert_exists'], $name);
          $alertData['help'] = '';
        }
      }

      if (!$showAlert) {
        //
        // Pattern has not changed or the new pattern does not exist yet. Update.
        //
        $PTN->name = $_POST['txt_name'];
        $PTN->description = $_POST['txt_description'];
        $PTN->abs1 = $_POST['sel_abs1'];
        $PTN->abs2 = $_POST['sel_abs2'];
        $PTN->abs3 = $_POST['sel_abs3'];
        $PTN->abs4 = $_POST['sel_abs4'];
        $PTN->abs5 = $_POST['sel_abs5'];
        $PTN->abs6 = $_POST['sel_abs6'];
        $PTN->abs7 = $_POST['sel_abs7'];
        //
        // Update pattern record
        //
        $PTN->update($PTN->id);
        //
        // Log this event
        //
        $LOG->logEvent("logPattern", L_USER, "log_pattern_updated", $PTN->name);
        //
        // Success
        //
        $showAlert = true;
        $alertData['type'] = 'success';
        $alertData['title'] = $LANG['alert_success_title'];
        $alertData['subject'] = $LANG['ptn_alert_edit'];
        $alertData['text'] = $LANG['ptn_alert_edit_success'];
        $alertData['help'] = '';
        //
        // Load new info for the view
        //
        $PTN->get($PTN->id);
        $viewData['PTN'] = $PTN;
        $viewData['name'] = $PTN->name;
        $viewData['description'] = $PTN->description;
        $viewData['abs1'] = $PTN->abs1;
        $viewData['abs2'] = $PTN->abs2;
        $viewData['abs3'] = $PTN->abs3;
        $viewData['abs4'] = $PTN->abs4;
        $viewData['abs5'] = $PTN->abs5;
        $viewData['abs6'] = $PTN->abs6;
        $viewData['abs7'] = $PTN->abs7;
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
    $alertData['text'] = $LANG['ptn_alert_save_failed'];
    $alertData['help'] = '';
  }
}

//-----------------------------------------------------------------------------
//
// PREPARE VIEW
//
$viewData['abs1Absences'][] = array( 'val' => 0, 'name' => $LANG['none'], 'selected' => ($PTN->abs1 === 0) ? true : false );
$viewData['abs2Absences'][] = array( 'val' => 0, 'name' => $LANG['none'], 'selected' => ($PTN->abs2 === 0) ? true : false );
$viewData['abs3Absences'][] = array( 'val' => 0, 'name' => $LANG['none'], 'selected' => ($PTN->abs3 === 0) ? true : false );
$viewData['abs4Absences'][] = array( 'val' => 0, 'name' => $LANG['none'], 'selected' => ($PTN->abs4 === 0) ? true : false );
$viewData['abs5Absences'][] = array( 'val' => 0, 'name' => $LANG['none'], 'selected' => ($PTN->abs5 === 0) ? true : false );
$viewData['abs6Absences'][] = array( 'val' => 0, 'name' => $LANG['none'], 'selected' => ($PTN->abs6 === 0) ? true : false );
$viewData['abs7Absences'][] = array( 'val' => 0, 'name' => $LANG['none'], 'selected' => ($PTN->abs7 === 0) ? true : false );
$absences = $A->getAll();
foreach ($absences as $absence) {
  $viewData['abs1Absences'][] = array( 'val' => $absence['id'], 'name' => $absence['name'], 'selected' => ($PTN->abs1 === $absence['id']) ? true : false );
  $viewData['abs2Absences'][] = array( 'val' => $absence['id'], 'name' => $absence['name'], 'selected' => ($PTN->abs2 === $absence['id']) ? true : false );
  $viewData['abs3Absences'][] = array( 'val' => $absence['id'], 'name' => $absence['name'], 'selected' => ($PTN->abs3 === $absence['id']) ? true : false );
  $viewData['abs4Absences'][] = array( 'val' => $absence['id'], 'name' => $absence['name'], 'selected' => ($PTN->abs4 === $absence['id']) ? true : false );
  $viewData['abs5Absences'][] = array( 'val' => $absence['id'], 'name' => $absence['name'], 'selected' => ($PTN->abs5 === $absence['id']) ? true : false );
  $viewData['abs6Absences'][] = array( 'val' => $absence['id'], 'name' => $absence['name'], 'selected' => ($PTN->abs6 === $absence['id']) ? true : false );
  $viewData['abs7Absences'][] = array( 'val' => $absence['id'], 'name' => $absence['name'], 'selected' => ($PTN->abs7 === $absence['id']) ? true : false );
}
$viewData['pattern'] = array(
  array( 'prefix' => 'ptn', 'name' => 'name', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['name'], 'maxlength' => '40', 'mandatory' => true, 'error' => (isset($inputAlert['name']) ? $inputAlert['name'] : '') ),
  array( 'prefix' => 'ptn', 'name' => 'description', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['description'], 'maxlength' => '100', 'error' => (isset($inputAlert['description']) ? $inputAlert['description'] : '') ),
  array( 'prefix' => 'ptn', 'name' => 'abs1', 'type' => 'list', 'values' => $viewData['abs1Absences'] ),
  array( 'prefix' => 'ptn', 'name' => 'abs2', 'type' => 'list', 'values' => $viewData['abs2Absences'] ),
  array( 'prefix' => 'ptn', 'name' => 'abs3', 'type' => 'list', 'values' => $viewData['abs3Absences'] ),
  array( 'prefix' => 'ptn', 'name' => 'abs4', 'type' => 'list', 'values' => $viewData['abs4Absences'] ),
  array( 'prefix' => 'ptn', 'name' => 'abs5', 'type' => 'list', 'values' => $viewData['abs5Absences'] ),
  array( 'prefix' => 'ptn', 'name' => 'abs6', 'type' => 'list', 'values' => $viewData['abs6Absences'] ),
  array( 'prefix' => 'ptn', 'name' => 'abs7', 'type' => 'list', 'values' => $viewData['abs7Absences'] ),
);

//-----------------------------------------------------------------------------
//
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
