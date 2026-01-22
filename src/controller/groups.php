<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Groups Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
global $allConfig;
global $C;
global $CONF;
global $controller;
global $LANG;
global $LOG;
global $UG;
global $UL;
global $U;
global $G;

//-----------------------------------------------------------------------------
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission) && !$UG->isGroupManager($UL->username)) {
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
  if ($inputError === false) {
    // ,--------,
    // | Create |
    // '--------'
    if (isset($_POST['btn_groupCreate'])) {
      //
      // Form validation
      //
      $inputAlert = array();
      $inputError = false;
      if (formInputValid('txt_name', 'required|alpha_numeric_dash') !== true) {
        $inputError = true;
      }
      if (formInputValid('txt_description', 'alpha_numeric_dash_blank') !== true) {
        $inputError = true;
      }

      $viewData['txt_name'] = $_POST['txt_name'];
      $viewData['txt_description'] = $_POST['txt_description'] ?? '';

      if ($inputError === false) {
        $G->name = $viewData['txt_name'];
        $G->description = $viewData['txt_description'];
        $G->minpresent = 0;
        $G->maxabsent = 9999;
        $G->minpresentwe = 0;
        $G->maxabsentwe = 9999;
        $G->create();

        //
        // Send notification e-mails to the subscribers of group events
        //
        if ($allConfig['emailNotifications']) {
          sendGroupEventNotifications("created", $G->name, $G->description);
        }

        //
        // Log this event
        //
        $LOG->logEvent("logGroup", L_USER, "log_group_created", $G->name . " " . $G->description);

        //
        // Success
        //
        $showAlert = true;
        $alertData['type'] = 'success';
        $alertData['title'] = $LANG['alert_success_title'];
        $alertData['subject'] = $LANG['btn_create_group'];
        $alertData['text'] = $LANG['groups_alert_group_created'];
        $alertData['help'] = '';
      } else {
        //
        // Fail
        //
        $showAlert = true;
        $alertData['type'] = 'danger';
        $alertData['title'] = $LANG['alert_danger_title'];
        $alertData['subject'] = $LANG['btn_create_group'];
        $alertData['text'] = $LANG['groups_alert_group_created_fail'];
        $alertData['help'] = '';
      }
    }
    // ,--------,
    // | Delete |
    // '--------'
    elseif (isset($_POST['btn_groupDelete'])) {
      $G->delete($_POST['hidden_id']);
      $UG->deleteByGroup($_POST['hidden_id']);
      $UO->deleteOptionByValue('calfilterGroup', $_POST['hidden_id']);

      //
      // Send notification e-mails to the subscribers of group events
      //
      if ($allConfig['emailNotifications']) {
        sendGroupEventNotifications("deleted", $_POST['hidden_name'], $_POST['hidden_description']);
      }

      //
      // Log this event
      //
      $LOG->logEvent("logGroup", L_USER, "log_group_deleted", $_POST['hidden_name']);

      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_delete_group'];
      $alertData['text'] = $LANG['groups_alert_group_deleted'];
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

// Load all config values in one query for maximum performance
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];

//
// Default: Get all groups
//
$viewData['groups'] = $G->getAllCached();

if (!isAllowed($CONF['controllers'][$controller]->permission) && $UG->isGroupManager($UL->username)) {
  //
  // This user does not have global permission to manage groups, but is a manager of some groups.
  // Let's show only the groups this user is managing.
  //
  $viewData['groups'] = $UG->getAllManagedGroupsForUser($UL->username);
}

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
