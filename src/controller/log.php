<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
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
// CHECK LICENSE
//
$alertData = array();
$showAlert = false;
$licExpiryWarning = $C->read('licExpiryWarning');
$LIC = new License();
$LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);

//=============================================================================
//
// LOAD CONTROLLER RESOURCES
//

//=============================================================================
//
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
  'Permission',
  'Region',
  'Registration',
  'Role',
  'Upload',
  'User'
);

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
  //
  // Validate input data. If something is wrong or missing, set $inputError = true
  //
  if (!$inputError) {
    // ,---------,
    // | Refresh |
    // '---------'
    if (isset($_POST['btn_refresh'])) {
      switch ($_POST['sel_logPeriod']) {
        case "curr_month":
          $C->save("logperiod", "curr_month");
          $C->save("logfrom", $logToday['firstOfMonth'] . ' 00:00:00.000000');
          $C->save("logto", $logToday['lastOfMonth'] . ' 23:59:59.999999');
          break;

        case "curr_quarter":
          $C->save("logperiod", "curr_quarter");
          $C->save("logfrom", $logToday['firstOfQuarter'] . ' 00:00:00.000000');
          $C->save("logto", $logToday['lastOfQuarter'] . ' 23:59:59.999999');
          break;

        case "curr_half":
          $C->save("logperiod", "curr_half");
          $C->save("logfrom", $logToday['firstOfHalf'] . ' 00:00:00.000000');
          $C->save("logto", $logToday['lastOfHalf'] . ' 23:59:59.999999');
          break;

        case "curr_year":
          $C->save("logperiod", "curr_year");
          $C->save("logfrom", $logToday['firstOfYear'] . ' 00:00:00.000000');
          $C->save("logto", $logToday['lastOfYear'] . ' 23:59:59.999999');
          break;

        case "curr_all":
          $C->save("logperiod", "curr_all");
          $C->save("logfrom", '2004-01-01 00:00:00.000000');
          $C->save("logto", $logToday['ISO'] . ' 23:59:59.999999');
          break;

        case "custom":
        default:
          $viewData['logPeriod'] = 'custom';
          $C->save("logperiod", "custom");
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
            $C->save("logfrom", $_POST['txt_logPeriodFrom'] . ' 00:00:00.000000');
            $C->save("logto", $_POST['txt_logPeriodTo'] . ' 23:59:59.999999');
          }
          break;
      }

      $C->save("logtype", $_POST['sel_logType']);

      if (isset($_POST['txt_logSearchUser']) && strlen($_POST['txt_logSearchUser'])) {
        $viewData['logSearchUser'] = sanitize($_POST['txt_logSearchUser']);
        $C->save("logsearchuser", '%' . $viewData['logSearchUser'] . '%');
      }

      if (isset($_POST['txt_logSearchEvent']) && strlen($_POST['txt_logSearchEvent'])) {
        $viewData['logSearchEvent'] = sanitize($_POST['txt_logSearchEvent']);
        $C->save("logsearchevent", '%' . $viewData['logSearchEvent'] . '%');
      }
    }
    // ,------,
    // | Save |
    // '------'
    elseif (isset($_POST['btn_logSave'])) {
      foreach ($logtypes as $lt) {
        //
        // Set log levels
        //
        if (isset($_POST['chk_log' . $lt]) && $_POST['chk_log' . $lt]) {
          $C->save("log" . $lt, "1");
        } else {
          $C->save("log" . $lt, "0");
        }
        //
        // Set log filters
        //
        if (isset($_POST['chk_logfilter' . $lt]) && $_POST['chk_logfilter' . $lt]) {
          $C->save("logfilter" . $lt, "1");
        } else {
          $C->save("logfilter" . $lt, "0");
        }
        //
        // Set log colors
        //
        if ($_POST['opt_logcolor' . $lt]) {
          $C->save("logcolor" . $lt, $_POST['opt_logcolor' . $lt]);
        } else {
          $C->save("logcolor" . $lt, "default");
        }
      }
      //
      // Log this event
      //
      $LOG->logEvent("logLoglevel", L_USER, "log_log_updated");
      header("Location: index.php?action=" . $controller);
    }
    // ,-------,
    // | Clear |
    // '-------'
    elseif (isset($_POST['btn_clear'])) {
      $periodFrom = $C->read("logfrom");
      $periodTo = $C->read("logto");
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
      $C->save("logperiod", "curr_all");
      $C->save("logfrom", '2004-01-01 00:00:00.000000');
      $C->save("logto", $logToday['ISO'] . ' 23:59:59.999999');
      $C->save("logtype", "%");
      $C->save("logsearchuser", "%");
      $C->save("logsearchevent", "%");
      header("Location: index.php?action=" . $controller);
    }
    // ,------,
    // | Sort |
    // '------'
    elseif (isset($_POST['btn_sort'])) {
      if ($sort == 'DESC') {
        header("Location: index.php?action=" . $controller . "&sort=ASC");
      } else {
        header("Location: index.php?action=" . $controller);
      }
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

//=============================================================================
//
// PREPARE VIEW
//
$periodFrom = $C->read("logfrom");
$periodTo = $C->read("logto");
$logPeriod = $C->read("logperiod");
if (!$logType = $C->read("logtype")) {
  $logType = '%';
}
if (!$logSearchUser = $C->read("logsearchuser")) {
  $logSearchUser = '%';
}
if (!$logSearchEvent = $C->read("logsearchevent")) {
  $logSearchEvent = '%';
}
$events = $LOG->read($sort, $periodFrom, $periodTo, $logType, $logSearchUser, $logSearchEvent);
$viewData['events'] = array();
if (count($events)) {
  foreach ($events as $event) {
    if ($C->read("logfilter" . substr($event['type'], 3))) {
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

//=============================================================================
//
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
