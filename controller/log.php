<?php
/**
 * log.php
 * 
 * Log page controller
 *
 * @category TeamCal Neo 
 * @version 0.5.006
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

//=============================================================================
//
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission))
{
   $alertData['type'] = 'warning';
   $alertData['title'] = $LANG['alert_alert_title'];
   $alertData['subject'] = $LANG['alert_not_allowed_subject'];
   $alertData['text'] = $LANG['alert_not_allowed_text'];
   $alertData['help'] = $LANG['alert_not_allowed_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
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
$viewData['logPeriod'] = '';
$sort = "DESC";
if (isset($_GET['sort']) and strtoupper($_GET['sort']) == "ASC") $sort = "ASC";
$eventsPerPage = 50;
$currentPage = 1;
if ( isset($_GET['page']) ) $currentPage = intval(sanitize($_GET['page']));

$logToday = dateInfo(date("Y"), date("m"), date("d"));
$C->save("logto", $logToday['ISO'] . ' 23:59:59.999999'); // Default is today

$showAlert = false;
$logtypes = array (
   'CalendarOptions',
   'Config',
   'Database',
   'Daynote',
   'Group',
   'Import',
   'Login',
   'Log',
   'Message',
   'Permission',
   'Registration',
   'Role',
   'Upload',
   'User' 
);

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST))
{
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
   
   if (!$inputError)
   {
      // ,---------,
      // | Refresh |
      // '---------'
      if (isset($_POST['btn_refresh']))
      {
         switch ($_POST['sel_logPeriod'])
         {
            case "curr_month" :
               $C->save("logperiod", "curr_month");
               $C->save("logfrom", $logToday['firstOfMonth'] . ' 00:00:00.000000');
               $C->save("logto", $logToday['lastOfMonth'] . ' 23:59:59.999999');
               break;
            
            case "curr_quarter" :
               $C->save("logperiod", "curr_quarter");
               $C->save("logfrom", $logToday['firstOfQuarter'] . ' 00:00:00.000000');
               $C->save("logto", $logToday['lastOfQuarter'] . ' 23:59:59.999999');
               break;
            
            case "curr_half" :
               $C->save("logperiod", "curr_half");
               $C->save("logfrom", $logToday['firstOfHalf'] . ' 00:00:00.000000');
               $C->save("logto", $logToday['lastOfHalf'] . ' 23:59:59.999999');
               break;
            
            case "curr_year" :
               $C->save("logperiod", "curr_year");
               $C->save("logfrom", $logToday['firstOfYear'] . ' 00:00:00.000000');
               $C->save("logto", $logToday['lastOfYear'] . ' 23:59:59.999999');
               break;
            
            case "curr_all" :
               $C->save("logperiod", "curr_all");
               $C->save("logfrom", '2004-01-01 00:00:00.000000');
               $C->save("logto", $logToday['ISO'] . ' 23:59:59.999999');
               break;
            
            case "custom" :
               $viewData['logPeriod'] = 'custom';
               $C->save("logperiod", "custom");
               if (!isset($_POST['txt_logPeriodFrom']) or !isset($_POST['txt_logPeriodTo']) or !strlen($_POST['txt_logPeriodFrom']) or !strlen($_POST['txt_logPeriodTo']))
               {
                  //
                  // One or both dates missing
                  //
                  $showAlert = true;
                  $alertData['type'] = 'danger';
                  $alertData['title'] = $LANG['alert_danger_title'];
                  $alertData['subject'] = $LANG['alert_input_validation_subject'];
                  $alertData['text'] = $LANG['alert_log_date_missing_text'];
                  $alertData['help'] = $LANG['alert_log_date_missing_help'];
               }
               else if (!preg_match("/(\d{4})-(\d{2})-(\d{2})/", $_POST['txt_logPeriodFrom']) or !preg_match("/(\d{4})-(\d{2})-(\d{2})/", $_POST['txt_logPeriodTo']))
               {
                  //
                  // One or both dates have wrong format
                  //
                  $showAlert = true;
                  $alertData['type'] = 'danger';
                  $alertData['title'] = $LANG['alert_danger_title'];
                  $alertData['subject'] = $LANG['alert_input_validation_subject'];
                  $alertData['text'] = $LANG['alert_date_format_text'];
                  $alertData['help'] = $LANG['alert_date_format_help'];
               }
               else if ($_POST['txt_logPeriodFrom'] > $_POST['txt_logPeriodTo'])
               {
                  //
                  // End date smaller than start date
                  //
                  $showAlert = true;
                  $alertData['type'] = 'danger';
                  $alertData['title'] = $LANG['alert_danger_title'];
                  $alertData['subject'] = $LANG['alert_input_validation_subject'];
                  $alertData['text'] = $LANG['alert_date_endbeforestart_text'];
                  $alertData['help'] = $LANG['alert_date_endbeforestart_help'];
               }
               else
               {
                  //
                  // All good. Save the new custom period
                  //
                  $C->save("logfrom", $_POST['txt_logPeriodFrom'] . ' 00:00:00.000000');
                  $C->save("logto", $_POST['txt_logPeriodTo'] . ' 23:59:59.999999');
               }
               break;
         }
      }
      // ,------,
      // | Save |
      // '------'
      else if (isset($_POST['btn_logSave']))
      {
         foreach ( $logtypes as $lt )
         {
            //
            // Set log levels
            //
            if (isset($_POST['chk_log' . $lt]) and $_POST['chk_log' . $lt]) $C->save("log" . $lt, "1"); else $C->save("log" . $lt, "0");
            
            //
            // Set log filters
            //
            if (isset($_POST['chk_logfilter' . $lt]) and $_POST['chk_logfilter' . $lt]) $C->save("logfilter" . $lt, "1"); else $C->save("logfilter" . $lt, "0");
            
            //
            // Set log colors
            //
            if ($_POST['opt_logcolor' . $lt]) $C->save("logcolor" . $lt, $_POST['opt_logcolor' . $lt]); else $C->save("logcolor" . $lt, "default");
         }
         //
         // Log this event
         //
         $LOG->log("logLoglevel", $L->checkLogin(), "log_log_updated");
         header("Location: index.php?action=".$controller);
      }
      // ,-------,
      // | Clear |
      // '-------'
      else if (isset($_POST['btn_clear']))
      {
         $periodFrom = $C->read("logfrom");
         $periodTo = $C->read("logto");
         $LOG->delete($periodFrom, $periodTo);
         
         //
         // Log this event
         //
         $LOG->log("logLog", $L->checkLogin(), "log_log_cleared");
         header("Location: index.php?action=".$controller);
      }
      // ,-------,
      // | Reset |
      // '-------'
      else if (isset($_POST['btn_reset']))
      {
         $C->save("logperiod", "curr_all");
         $C->save("logfrom", '2004-01-01 00:00:00.000000');
         $C->save("logto", $logToday['ISO'] . ' 23:59:59.999999');
         header("Location: index.php?action=".$controller);
      }
      // ,------,
      // | Sort |
      // '------'
      else if (isset($_POST['btn_sort']))
      {
         if ($sort=='DESC') 
         {
            header("Location: index.php?action=".$controller."&sort=ASC");
         }
         else
         {
            header("Location: index.php?action=".$controller);
         }
      }
   }
   else
   {
      //
      // Input validation failed
      //
      $showAlert = TRUE;
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
$events = $LOG->read($sort, $periodFrom, $periodTo);
$viewData['events'] = array ();
if (count($events))
{
   foreach ( $events as $event )
   {
      if ($C->read("logfilter" . substr($event['type'], 3))) $viewData['events'][] = $event;
   }
}
$viewData['types'] = $logtypes;
$viewData['logperiod'] = $logPeriod;
$viewData['logfrom'] = substr($periodFrom, 0, 10);
$viewData['logto'] = substr($periodTo, 0, 10);
$viewData['numEvents'] = count($viewData['events']);
$viewData['eventsPerPage'] = $eventsPerPage;
$viewData['sort'] = $sort;
if ($viewData['numEvents']) $viewData['numPages'] = ceil($viewData['numEvents']/$viewData['eventsPerPage']); else $viewData['numPages'] = 0;
$viewData['currentPage'] = $currentPage;

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
