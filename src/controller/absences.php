<?php
/**
 * Absences Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
global $A;
global $AG;
global $C;
global $CONF;
global $controller;
global $LANG;
global $LOG;
global $LIC;
global $T;
global $UO;

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
$licExpiryWarning = $C->read('licExpiryWarning');
$LIC = new License();
$LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$viewData['txt_name'] = '';

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

  if (!$inputError) {
    // ,--------,
    // | Create |
    // '--------'
    if (isset($_POST['btn_absCreate'])) {
      //
      // Form validation
      //
      $inputAlert = array();
      $inputError = false;
      if (!formInputValid('txt_name', 'alpha_numeric_dash_blank')) {
        $inputError = true;
      }

      if (isset($_POST['txt_name'])) {
        $viewData['txt_name'] = $_POST['txt_name'];
      }

      if (!$inputError) {
        $AA = new Absences();
        $AA->name = $viewData['txt_name'];
        $AA->icon = 'fas fa-times';
        $AA->symbol = strtoupper(substr($viewData['txt_name'], 0, 1));
        $AA->create();

        //
        // Send notification e-mails to the subscribers of group events
        //
        if ($C->read("emailNotifications")) {
          sendAbsenceEventNotifications("created", $AA->name);
        }

        //
        // Log this event
        //
        $LOG->logEvent("logAbsence", L_USER, "log_abs_created", $AA->name);

        //
        // Success
        //
        $showAlert = true;
        $alertData['type'] = 'success';
        $alertData['title'] = $LANG['alert_success_title'];
        $alertData['subject'] = $LANG['btn_create_abs'];
        $alertData['text'] = $LANG['abs_alert_created'];
        $alertData['help'] = '';
      } else {
        //
        // Fail
        //
        $showAlert = true;
        $alertData['type'] = 'danger';
        $alertData['title'] = $LANG['alert_danger_title'];
        $alertData['subject'] = $LANG['btn_create_abs'];
        $alertData['text'] = $LANG['abs_alert_created_fail'];
        $alertData['help'] = '';
      }
    }
    // ,--------,
    // | Delete |
    // '--------'
    elseif (isset($_POST['btn_absDelete'])) {
      $T->replaceAbsId($_POST['hidden_id'], '0');
      $AG->unassignAbs($_POST['hidden_id']);
      $UO->deleteOptionByValue('calfilterAbs', $_POST['hidden_id']);
      $A->setAllSubsPrimary($_POST['hidden_id']);
      $A->delete($_POST['hidden_id']);

      //
      // Send notification e-mails to the subscribers of group events
      //
      if ($C->read("emailNotifications")) {
        sendAbsenceEventNotifications("deleted", $_POST['hidden_name']);
      }

      //
      // Log this event
      //
      $LOG->logEvent("logAbsence", L_USER, "log_abs_deleted", $_POST['hidden_name']);

      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_delete_abs'];
      $alertData['text'] = $LANG['abs_alert_deleted'];
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
$viewData['absences'] = $A->getAll();

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
