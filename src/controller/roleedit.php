<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Role Edit Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Controllers
 * @since 3.0.0
 */

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
$RO2 = new Roles(); // for the profile to be created or updated
if (isset($_GET['id'])) {
  $missingData = false;
  $roleid = sanitize($_GET['id']);
  if (!$RO2->getById($roleid)) {
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
$viewData['id'] = $RO2->id;
$viewData['name'] = $RO2->name;
$viewData['description'] = $RO2->description;
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
  // Form validation
  //
  $inputError = false;
  if (
    !formInputValid('txt_name', 'required|alpha_numeric_dash') ||
    !formInputValid('txt_description', 'alpha_numeric_dash_blank')
  ) {
    $inputError = true;
    $alertData['text'] = $LANG['role_alert_save_failed'];
  }
  //
  // Load sanitized form info for the view
  //
  $viewData['name'] = $_POST['txt_name'];
  $viewData['description'] = $_POST['txt_description'];

  if (!$inputError) {
    // ,--------,
    // | Update |
    // '--------'
    if (isset($_POST['btn_roleUpdate'])) {
      $oldName = $RO2->name;
      $RO2->name = $_POST['txt_name'];
      $RO2->description = $_POST['txt_description'];
      if ($_POST['opt_color']) {
        $RO2->color = $_POST['opt_color'];
      }
      $RO2->update($RO2->id);
      //
      // Send notification e-mails to the subscribers of user events
      //
      if ($C->read("emailNotifications")) {
        sendRoleEventNotifications("changed", $RO2->name . ' (ex: ' . $oldName . ')', $RO2->description);
      }
      //
      // Log this event
      //
      $LOG->logEvent("logRole", L_USER, "log_role_updated", $RO2->name . ' (ex: ' . $oldName . ')');
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['role_alert_edit'];
      $alertData['text'] = $LANG['role_alert_edit_success'];
      $alertData['help'] = '';
      //
      // Load new info for the view
      //
      $viewData['name'] = $RO2->name;
      $viewData['description'] = $RO2->description;
      $viewData['color'] = $RO2->color;
    }
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_input'];
    $alertData['help'] = '';
  }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['role'] = array(
  array( 'prefix' => 'role', 'name' => 'name', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['name'], 'maxlength' => '40', 'mandatory' => true, 'error' => (isset($inputAlert['name']) ? $inputAlert['name'] : '') ),
  array( 'prefix' => 'role', 'name' => 'description', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['description'], 'maxlength' => '100', 'error' => (isset($inputAlert['description']) ? $inputAlert['description'] : '') ),
  array( 'prefix' => 'role', 'name' => 'color', 'type' => 'radio', 'values' => $bsColors, 'value' => $RO2->getColorByName($viewData['name']) ),
);

//=============================================================================
//
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
