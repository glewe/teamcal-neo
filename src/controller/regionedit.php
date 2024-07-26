<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Region Edit Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Controllers
 * @since 3.0.0
 */

// echo '<script type="text/javascript">alert("Debug: ");</script>';

//=============================================================================
//
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

//=============================================================================
//
// CHECK URL PARAMETERS
//
$RR = new Regions(); // for the profile to be created or updated
if (isset($_GET['id'])) {
  $missingData = false;
  $id = sanitize($_GET['id']);
  if (!$RR->getById($id)) {
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
// LOAD CONTROLLER RESOURCES
//

//=============================================================================
//
// VARIABLE DEFAULTS
//
$viewData['id'] = $RR->id;
$viewData['name'] = $RR->name;
$viewData['description'] = $RR->description;
$inputAlert = array();

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
  // Load sanitized form info for the view
  //
  $viewData['id'] = $_POST['hidden_id'];
  $viewData['name'] = $_POST['txt_name'];
  $viewData['description'] = $_POST['txt_description'];
  //
  // Form validation
  //
  $inputError = false;
  if (isset($_POST['btn_regionUpdate'])) {
    if (!formInputValid('txt_name', 'required|alpha_numeric_dash')) {
      $inputError = true;
    }
    if (!formInputValid('txt_description', 'alpha_numeric_dash_blank')) {
      $inputError = true;
    }
  }

  if (!$inputError) {
    // ,--------,
    // | Update |
    // '--------'
    if (isset($_POST['btn_regionUpdate'])) {
      $RR->name = $_POST['txt_name'];
      $RR->description = $_POST['txt_description'];
      //
      // Update region record
      //
      $RR->update($_POST['hidden_id']);
      //
      // Update region access table
      //
      $RR->deleteAccess($_POST['hidden_id']);
      if (isset($_POST['sel_viewOnlyRoles'])) {
        foreach ($_POST['sel_viewOnlyRoles'] as $roleid) {
          $RR->setAccess($_POST['hidden_id'], $roleid, 'view');
        }
      }
      //
      // Log this event
      //
      $LOG->logEvent("logRegion", L_USER, "log_region_updated", $RR->name);
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['region_alert_edit'];
      $alertData['text'] = $LANG['region_alert_edit_success'];
      $alertData['help'] = '';
      //
      // Load new info for the view
      //
      $viewData['name'] = $RR->name;
      $viewData['description'] = $RR->description;
    }
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_input'];
    $alertData['text'] = $LANG['region_alert_save_failed'];
    $alertData['help'] = '';
  }
}

//=============================================================================
//
// PREPARE VIEW
//
$roles = $RO->getAll();
foreach ($roles as $role) {
  $viewData['viewOnlyRoles'][] = array( 'val' => $role['id'], 'name' => $role['name'], 'selected' => ($R->getAccess($viewData['id'], $role['id']) == "view") ? true : false );
}
$viewData['region'] = array(
  array( 'prefix' => 'region', 'name' => 'name', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['name'], 'maxlength' => '40', 'mandatory' => true, 'error' => (isset($inputAlert['name']) ? $inputAlert['name'] : '') ),
  array( 'prefix' => 'region', 'name' => 'description', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['description'], 'maxlength' => '100', 'error' => (isset($inputAlert['description']) ? $inputAlert['description'] : '') ),
  array( 'prefix' => 'region', 'name' => 'viewOnlyRoles', 'type' => 'listmulti', 'values' => $viewData['viewOnlyRoles'] ),
);

//=============================================================================
//
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
