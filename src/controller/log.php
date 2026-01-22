<?php

/**
 * Log Controller
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
global $LIC;

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
// GENERATE TEST LOGS (ONLY FOR DEVELOPMENT PURPOSES)
//
$generateTestLogs = false;
if ($generateTestLogs) {
  $LOG->generateTestLogs(123);
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$viewData['logPeriod'] = '';
$viewData['logSearchUser'] = '';
$viewData['logSearchEvent'] = '';
$sort = "DESC";
if (isset($_GET['sort']) && strtoupper($_GET['sort']) == "ASC") {
  $sort = "ASC";
}
$eventsPerPage = 50;
$currentPage = 1;
if (isset($_GET['page'])) {
  $currentPage = intval(sanitize($_GET['page']));
}

$logToday = dateInfo(date("Y"), date("m"), date("d"));
$C->save("logto", $logToday['ISO'] . ' 23:59:59.999999'); // Default is today

$logtypes = array(
  'Calendar',
  'CalendarOptions',
  'Config',
  'Database',
  'Daynote',
  'Group',
  'Import',
  'Login',
  'Log',
  'Message',
  'Month',
  'Pattern',
  'Permission',
  'Region',
  'Registration',
  'Role',
  'Upload',
  'User'
);

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
    // ,---------,
    // | Refresh |
    // '---------'
    if (isset($_POST['btn_refresh'])) {
      $refreshBatchConfigs = [];

      switch ($_POST['sel_logPeriod']) {
        case "curr_month":
          $refreshBatchConfigs["logperiod"] = "curr_month";
          $refreshBatchConfigs["logfrom"] = $logToday['firstOfMonth'] . ' 00:00:00.000000';
          $refreshBatchConfigs["logto"] = $logToday['lastOfMonth'] . ' 23:59:59.999999';
          break;

        case "curr_quarter":
          $refreshBatchConfigs["logperiod"] = "curr_quarter";
          $refreshBatchConfigs["logfrom"] = $logToday['firstOfQuarter'] . ' 00:00:00.000000';
          $refreshBatchConfigs["logto"] = $logToday['lastOfQuarter'] . ' 23:59:59.999999';
          break;

        case "curr_half":
          $refreshBatchConfigs["logperiod"] = "curr_half";
          $refreshBatchConfigs["logfrom"] = $logToday['firstOfHalf'] . ' 00:00:00.000000';
          $refreshBatchConfigs["logto"] = $logToday['lastOfHalf'] . ' 23:59:59.999999';
          break;

        case "curr_year":
          $refreshBatchConfigs["logperiod"] = "curr_year";
          $refreshBatchConfigs["logfrom"] = $logToday['firstOfYear'] . ' 00:00:00.000000';
          $refreshBatchConfigs["logto"] = $logToday['lastOfYear'] . ' 23:59:59.999999';
          break;

        case "curr_all":
          $refreshBatchConfigs["logperiod"] = "curr_all";
          $refreshBatchConfigs["logfrom"] = '2004-01-01 00:00:00.000000';
          $refreshBatchConfigs["logto"] = $logToday['ISO'] . ' 23:59:59.999999';
          break;

        case "custom":
        default:
          $viewData['logPeriod'] = 'custom';
          $refreshBatchConfigs["logperiod"] = "custom";
          if (!isset($_POST['txt_logPeriodFrom']) || !isset($_POST['txt_logPeriodTo']) || !strlen($_POST['txt_logPeriodFrom']) || !strlen($_POST['txt_logPeriodTo'])) {
            //
            // One or both dates missing
            //
            $showAlert = true;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = $LANG['alert_input_validation_subject'];
            $alertData['text'] = $LANG['alert_log_date_missing_text'];
            $alertData['help'] = $LANG['alert_log_date_missing_help'];
          } elseif (!preg_match("/(\d{4})-(\d{2})-(\d{2})/", $_POST['txt_logPeriodFrom']) || !preg_match("/(\d{4})-(\d{2})-(\d{2})/", $_POST['txt_logPeriodTo'])) {
            //
            // One or both dates have wrong format
            //
            $showAlert = true;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = $LANG['alert_input_validation_subject'];
            $alertData['text'] = $LANG['alert_date_format_text'];
            $alertData['help'] = $LANG['alert_date_format_help'];
          } elseif ($_POST['txt_logPeriodFrom'] > $_POST['txt_logPeriodTo']) {
            //
            // End date smaller than start date
            //
            $showAlert = true;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = $LANG['alert_input_validation_subject'];
            $alertData['text'] = $LANG['alert_date_endbeforestart_text'];
            $alertData['help'] = $LANG['alert_date_endbeforestart_help'];
          } else {
            //
            // All good. Save the new custom period
            //
            $refreshBatchConfigs["logfrom"] = $_POST['txt_logPeriodFrom'] . ' 00:00:00.000000';
            $refreshBatchConfigs["logto"] = $_POST['txt_logPeriodTo'] . ' 23:59:59.999999';
          }
          break;
      }

      $refreshBatchConfigs["logtype"] = $_POST['sel_logType'];

      //
      // Save all refresh settings in batch
      //
      $C->saveBatch($refreshBatchConfigs);
      $allConfig = $C->readAll();
    }
    // ,------,
    // | Save |
    // '------'
    elseif (isset($_POST['btn_logSave'])) {
      $newConfig = [];

      foreach ($logtypes as $lt) {
        //
        // Set log levels
        //
        if (isset($_POST['chk_log' . $lt]) && $_POST['chk_log' . $lt]) {
          $newConfig["log" . $lt] = "1";
        } else {
          $newConfig["log" . $lt] = "0";
        }
        //
        // Set log filters
        //
        if (isset($_POST['chk_logfilter' . $lt]) && $_POST['chk_logfilter' . $lt]) {
          $newConfig["logfilter" . $lt] = "1";
        } else {
          $newConfig["logfilter" . $lt] = "0";
        }
        //
        // Set log colors
        //
        if ($_POST['opt_logcolor' . $lt]) {
          $newConfig["logcolor" . $lt] = $_POST['opt_logcolor' . $lt];
        } else {
          $newConfig["logcolor" . $lt] = "default";
        }
      }

      //
      // Save all log settings in batch
      //
      $C->saveBatch($newConfig);

      //
      // Log this event
      //
      $LOG->logEvent("logLog", L_USER, "log_log_updated");
      header("Location: index.php?action=" . $controller);
    }
    // ,-------,
    // | Clear |
    // '-------'
    elseif (isset($_POST['btn_clear'])) {
      $periodFrom = $allConfig['logfrom'];
      $periodTo = $allConfig['logto'];
      $LOG->delete($periodFrom, $periodTo);
      //
      // Log this event
      //
      $LOG->logEvent("logLog", L_USER, "log_log_cleared");
      header("Location: index.php?action=" . $controller);
    }
    // ,-------,
    // | Reset |
    // '-------'
    elseif (isset($_POST['btn_reset'])) {
      $resetBatchConfigs = [
        "logperiod" => "curr_all",
        "logfrom" => '2004-01-01 00:00:00.000000',
        "logto" => $logToday['ISO'] . ' 23:59:59.999999',
        "logtype" => "%",
        "logsearchuser" => "%",
        "logsearchevent" => "%"
      ];

      $C->saveBatch($resetBatchConfigs);
      $LOG->logEvent("logLog", L_USER, "log_log_reset");
      header("Location: index.php?action=" . $controller);
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
    $alertData['text'] = $LANG['register_alert_failed'];
    $alertData['help'] = '';
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$periodFrom = $allConfig['logfrom'];
$periodTo = $allConfig['logto'];
$logPeriod = $allConfig['logperiod'];

$logType = '%';
if (isset($allConfig['logtype']) && $allConfig['logtype'] != '') {
  $logType = $allConfig['logtype'];
}

$logSearchUser = '%';
if (isset($allConfig['logsearchuser']) && $allConfig['logsearchuser'] != '') {
  $logSearchUser = '%';
}

$logSearchEvent = '%';
if (isset($allConfig['logsearchevent']) && $allConfig['logsearchevent'] != '') {
  $logSearchEvent = '%';
}

$events = $LOG->read($sort, $periodFrom, $periodTo, $logType, $logSearchUser, $logSearchEvent);

$viewData['events'] = array();
if (count($events)) {
  foreach ($events as $event) {
    if ($allConfig['logfilter' . substr($event['type'], 3)]) {
      $viewData['events'][] = $event;
    }
  }
}

$viewData['types'] = $logtypes;
$viewData['logperiod'] = $logPeriod;
$viewData['logfrom'] = substr($periodFrom, 0, 10);
$viewData['logto'] = substr($periodTo, 0, 10);
$viewData['logtype'] = $logType;
$viewData['numEvents'] = count($viewData['events']);
$viewData['eventsPerPage'] = $eventsPerPage;
$viewData['sort'] = $sort;
if ($viewData['numEvents']) {
  $viewData['numPages'] = ceil($viewData['numEvents'] / $viewData['eventsPerPage']);
} else {
  $viewData['numPages'] = 0;
}
$viewData['currentPage'] = $currentPage;

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
