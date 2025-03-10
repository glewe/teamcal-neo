<?php
/**
 * Group Edit Controller
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
global $UG;
global $UL;
global $U;

//-----------------------------------------------------------------------------
// CHECK URL PARAMETERS
//
$GG = new Groups(); // for the profile to be created or updated
if (isset($_GET['id'])) {
  $missingData = false;
  $id = sanitize($_GET['id']);
  if (!$GG->getById($id)) {
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
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission) && !$UG->isGroupManagerOfGroup($UL->username, $GG->id)) {
  $alertData['type'] = 'warning';
  $alertData['title'] = $LANG['alert_alert_title'];
  $alertData['subject'] = $LANG['alert_not_allowed_subject'];
  $alertData['text'] = $LANG['alert_not_allowed_text'];
  $alertData['help'] = $LANG['alert_not_allowed_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$viewData['id'] = $GG->id;
$viewData['name'] = $GG->name;
$viewData['description'] = $GG->description;
$viewData['minpresent'] = $GG->minpresent;
$viewData['maxabsent'] = $GG->maxabsent;
$viewData['minpresentwe'] = $GG->minpresentwe;
$viewData['maxabsentwe'] = $GG->maxabsentwe;
$inputAlert = array();

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
  // Load sanitized form info for the view
  //
  $viewData['id'] = $_POST['hidden_id'];
  $viewData['name'] = $_POST['txt_name'];
  $viewData['description'] = $_POST['txt_description'];

  //
  // Form validation
  //
  $inputError = false;
  if (isset($_POST['btn_groupUpdate'])) {
    if (!formInputValid('txt_name', 'required|alpha_numeric_dash')) {
      $inputError = true;
    }
    if (!formInputValid('txt_description', 'alpha_numeric_dash_blank')) {
      $inputError = true;
    }
    if (!formInputValid('txt_minpresent', 'numeric')) {
      $inputError = true;
    }
    if (!formInputValid('txt_maxabsent', 'numeric')) {
      $inputError = true;
    }
    if (!formInputValid('txt_minpresentwe', 'numeric')) {
      $inputError = true;
    }
    if (!formInputValid('txt_maxabsentwe', 'numeric')) {
      $inputError = true;
    }
  }

  if (!$inputError) {
    // ,--------,
    // | Update |
    // '--------'
    if (isset($_POST['btn_groupUpdate'])) {
      $GG->name = $_POST['txt_name'];
      $GG->description = $_POST['txt_description'];
      $GG->minpresent = $_POST['txt_minpresent'];
      $GG->maxabsent = $_POST['txt_maxabsent'];
      $GG->minpresentwe = $_POST['txt_minpresentwe'];
      $GG->maxabsentwe = $_POST['txt_maxabsentwe'];

      $GG->update($_POST['hidden_id']);

      //
      // Memberships, Managerships
      //
      if (isAllowed("groupmemberships") || $UG->isGroupManagerOfGroup($UL->username, $viewData['id'])) {
        if (isset($_POST['sel_members'])) {
          $UG->deleteAllMembers($_POST['hidden_id']);
          foreach ($_POST['sel_members'] as $uname) {
            $UG->save($uname, $_POST['hidden_id'], 'member');
          }
        }
        if (isset($_POST['sel_managers'])) {
          $UG->deleteAllManagers($_POST['hidden_id']);
          foreach ($_POST['sel_managers'] as $uname) {
            $UG->save($uname, $_POST['hidden_id'], 'manager');
          }
        }
      }

      //
      // Send notification e-mails to the subscribers of user events
      //
      if ($C->read("emailNotifications")) {
        sendGroupEventNotifications("changed", $GG->name, $GG->description);
      }

      //
      // Log this event
      //
      $LOG->logEvent("logGroup", L_USER, "log_group_updated", $GG->name);

      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['group_alert_edit'];
      $alertData['text'] = $LANG['group_alert_edit_success'];
      $alertData['help'] = '';

      //
      // Load new info for the view
      //
      $viewData['name'] = $GG->name;
      $viewData['description'] = $GG->description;
      $viewData['minpresent'] = $GG->minpresent;
      $viewData['maxabsent'] = $GG->maxabsent;
      $viewData['minpresentwe'] = $GG->minpresentwe;
      $viewData['maxabsentwe'] = $GG->maxabsentwe;
    }
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_input'];
    $alertData['text'] = $LANG['group_alert_save_failed'];
    $alertData['help'] = '';
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$viewData['group'] = array(
  array( 'prefix' => 'group', 'name' => 'name', 'type' => isAllowed($CONF['controllers'][$controller]->permission)? 'text' : 'info', 'placeholder' => '', 'value' => $viewData['name'], 'maxlength' => '40', 'mandatory' => true, 'error' => (isset($inputAlert['name']) ? $inputAlert['name'] : '') ),
  array( 'prefix' => 'group', 'name' => 'description', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['description'], 'maxlength' => '100', 'error' => (isset($inputAlert['description']) ? $inputAlert['description'] : '') ),
  array( 'prefix' => 'group', 'name' => 'minpresent', 'type' => 'text', 'placeholder' => '0', 'value' => $viewData['minpresent'], 'maxlength' => '4', 'error' => (isset($inputAlert['minpresent']) ? $inputAlert['minpresent'] : '') ),
  array( 'prefix' => 'group', 'name' => 'maxabsent', 'type' => 'text', 'placeholder' => '9999', 'value' => $viewData['maxabsent'], 'maxlength' => '4', 'error' => (isset($inputAlert['maxabsent']) ? $inputAlert['maxabsent'] : '') ),
  array( 'prefix' => 'group', 'name' => 'minpresentwe', 'type' => 'text', 'placeholder' => '0', 'value' => $viewData['minpresentwe'], 'maxlength' => '4', 'error' => (isset($inputAlert['minpresentwe']) ? $inputAlert['minpresentwe'] : '') ),
  array( 'prefix' => 'group', 'name' => 'maxabsentwe', 'type' => 'text', 'placeholder' => '9999', 'value' => $viewData['maxabsentwe'], 'maxlength' => '4', 'error' => (isset($inputAlert['maxabsentwe']) ? $inputAlert['maxabsentwe'] : '') ),
);

if (isAllowed("groupmemberships") || $UG->isGroupManagerOfGroup($UL->username, $viewData['id'])) {
  $disabled = false;
} else {
  $disabled = true;
}
$members = $U->getAll();
foreach ($members as $member) {
  $fullname = $member['lastname'] . ', ' . $member['firstname'];
  $username = $member['username'];
  $viewData['memberlist'][] = array( 'val' => $username, 'name' => $fullname, 'selected' => ($UG->isMemberOfGroup($username, $viewData['id'])) ? true : false );
  $viewData['managerlist'][] = array( 'val' => $username, 'name' => $fullname, 'selected' => ($UG->isGroupManagerOfGroup($username, $viewData['id'])) ? true : false );
}
$viewData['members'] = array(
  array( 'prefix' => 'group', 'name' => 'members', 'type' => 'listmulti', 'values' => $viewData['memberlist'], 'disabled' => $disabled ),
);
$viewData['managers'] = array(
  array( 'prefix' => 'group', 'name' => 'managers', 'type' => 'listmulti', 'values' => $viewData['managerlist'], 'disabled' => $disabled ),
);

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
