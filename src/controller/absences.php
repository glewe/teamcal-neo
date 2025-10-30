<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
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
global $allConfig;
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
$licExpiryWarning = $allConfig['licExpiryWarning'];
$LIC = new License();
$LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];
$viewData['txt_name'] = '';

//-----------------------------------------------------------------------------
// PROCESS FORM
//
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && !empty($_POST)) {

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

      if (isset($_POST['txt_name']) && is_string($_POST['txt_name'])) {
        $viewData['txt_name'] = htmlspecialchars($_POST['txt_name'], ENT_QUOTES, 'UTF-8');
      }

      if (!$inputError) {
        $AA = new Absences();
        $AA->name = $viewData['txt_name'];
        $AA->icon = 'fas fa-times';
        $AA->symbol = strtoupper(substr($viewData['txt_name'], 0, 1));
        $AA->create();

        // Send notification and log event
        if ($allConfig['emailNotifications']) {
          sendAbsenceEventNotifications('created', $AA->name);
        }
        $LOG->logEvent('logAbsence', L_USER, 'log_abs_created', $AA->name);

        // Renew CSRF token after successful form processing
        if (session_status() === PHP_SESSION_ACTIVE) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Success
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
      $hidden_id = (isset($_POST['hidden_id']) && is_string($_POST['hidden_id'])) ? $_POST['hidden_id'] : '';
      $hidden_name = (isset($_POST['hidden_name']) && is_string($_POST['hidden_name'])) ? $_POST['hidden_name'] : '';
      if ($hidden_id !== '') {
        // Batch delete operations to reduce database calls
        $T->replaceAbsId($hidden_id, '0');
        $AG->unassignAbs($hidden_id);
        $UO->deleteOptionByValue('calfilterAbs', $hidden_id);
        $A->setAllSubsPrimary($hidden_id);
        $A->delete($hidden_id);
      }

      // Send notification and log event
      if ($hidden_name !== '') {
        if ($allConfig['emailNotifications']) {
          sendAbsenceEventNotifications('deleted', $hidden_name);
        }
        $LOG->logEvent('logAbsence', L_USER, 'log_abs_deleted', $hidden_name);
      }

      // Renew CSRF token after successful form processing
      if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }

      // Success
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

// Pre-fetch all sub-absences to avoid N+1 query problem
$allSubAbsences = array();
foreach ($viewData['absences'] as $absence) {
  if (!($absence['counts_as'] ?? false)) {
    $subAbsences = $A->getAllSub($absence['id']);
    if (!empty($subAbsences)) {
      $allSubAbsences[$absence['id']] = $subAbsences;
    }
  }
}
$viewData['allSubAbsences'] = $allSubAbsences;

// Pre-calculate styles for absences to reduce inline calculations
foreach ($viewData['absences'] as &$absence) {
  $absence['bgstyle'] = (isset($absence['bgtrans']) && $absence['bgtrans']) ? "" : (isset($absence['bgcolor']) ? "background-color: #" . $absence['bgcolor'] . ";" : "");
}
unset($absence);

// Pre-calculate styles for sub-absences
foreach ($viewData['allSubAbsences'] as $absId => $subAbsences) {
  foreach ($subAbsences as &$subabs) {
    $subabs['bgstyle'] = (isset($subabs['bgtrans']) && $subabs['bgtrans']) ? "" : (isset($subabs['bgcolor']) ? "background-color: #" . $subabs['bgcolor'] . ";" : "");
  }
  unset($subabs);
}

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
