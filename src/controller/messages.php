<?php
/**
 * Messages page controller
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
global $MSG;
global $UMSG;
global $UL;

//-----------------------------------------------------------------------------
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission) || !$C->read('activateMessages')) {
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
$msgData = $MSG->getAllByUser($UL->username);

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
    // ,---------,
    // | Confirm |
    // '---------'
    if (isset($_POST['btn_confirm'])) {
      $UMSG->setSilent($_POST['msgId']);
      $LOG->logEvent("logMessages", $UL->username, "log_msg_confirmed", $_POST['msgId']);
      header("Location: index.php?action=" . $controller);
    }
    // ,-------------,
    // | Confirm All |
    // '-------------'
    elseif (isset($_POST['btn_confirm_all'])) {
      $UMSG->setSilentByUser($UL->username);
      //
      // Log this event
      //
      $LOG->logEvent("logMessages", $UL->username, "log_msg_all_confirmed_by", $UL->username);
      header("Location: index.php?action=" . $controller);
    }
    // ,--------,
    // | Delete |
    // '--------'
    elseif (isset($_POST['btn_delete'])) {
      $UMSG->delete($_POST['msgId']);
      deleteOrphanedMessages();
      //
      // Log this event
      //
      $LOG->logEvent("logMessages", $UL->username, "log_msg_deleted", $_POST['msgId']);
      header("Location: index.php?action=" . $controller);
    }
    // ,------------,
    // | Delete All |
    // '------------'
    elseif (isset($_POST['btn_delete_all'])) {
      $UMSG->deleteByUser($UL->username);
      deleteOrphanedMessages();
      //
      // Log this event
      //
      $LOG->logEvent("logMessages", $UL->username, "log_msg_all_deleted", $UL->username);
      header("Location: index.php?action=" . $controller);
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

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
