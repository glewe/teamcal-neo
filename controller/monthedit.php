<?php
/**
 * monthedit.php
 * 
 * Month edit page controller
 *
 * @category TeamCal Neo 
 * @version 0.9.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet) http://tcneo.lewe.com/doc/license.txt
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
// CHECK URL PARAMETERS
//
if (isset($_GET['month']) AND isset($_GET['region']))
{
   $missingData = FALSE;
   $yyyymm = sanitize($_GET['month']);
   $region = sanitize($_GET['region']);
   $viewData['year'] = substr($yyyymm, 0, 4);
   $viewData['month'] = substr($yyyymm, 4, 2);
   if (!is_numeric($yyyymm) OR strlen($yyyymm) != 6 OR !checkdate(intval($viewData['month']),1,intval($viewData['year'])) ) $missingData = TRUE;
   if (!$R->getById($region)) $missingData = TRUE;
}
else
{
   $missingData = TRUE;
}

if ($missingData)
{
   //
   // URL param fail
   //
   $alertData['type'] = 'danger';
   $alertData['title'] = $LANG['alert_danger_title'];
   $alertData['subject'] = $LANG['alert_no_data_subject'];
   $alertData['text'] = $LANG['alert_no_data_text'];
   $alertData['help'] = $LANG['alert_no_data_help'];
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
$inputAlert = array();
$viewData['regionid'] = $R->id;
$viewData['regionname'] = $R->name;

//
// See if a template exists. If not, create one.
//
if (!$M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid'])) 
{
   createMonth($viewData['year'], $viewData['month'], 'region', $viewData['regionid']);
     
   //
   // Log this event
   //
   $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
}

$viewData['dateInfo'] = dateInfo($viewData['year'], $viewData['month']);

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST))
{
   // ,------,
   // | Save |
   // '------'
   if (isset($_POST['btn_save']))
   {
      //
      // Loop thru the radio boxes
      //
      for ($i=1; $i<=$viewData['dateInfo']['daysInMonth']; $i++) 
      {
         $key = 'opt_hol_'.$i;
         if (isset($_POST[$key])) $M->setHoliday($viewData['year'], $viewData['month'], $i, $viewData['regionid'], $_POST[$key]);
      }
      
      //
      // Send notification e-mails to the subscribers of user events
      //
      if ($C->read("emailNotifications"))
      {
         sendMonthEventNotifications("changed", $viewData['year'], $viewData['month'], $viewData['regionname']);
      }
          
      //
      // Log this event
      //
      $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_updated", $M->region . " " . $M->year.$M->month);
      
      //
      // Success
      //
      $showAlert = TRUE;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['monthedit_alert_update'];
      $alertData['text'] = $LANG['monthedit_alert_update_success'];
      $alertData['help'] = '';
   }
   // ,-----------,
   // | Clear All |
   // '-----------'
   elseif (isset($_POST['btn_clearall']))
   {
      $M->clearHolidays($viewData['year'], $viewData['month'], $viewData['regionid']);
      
      //
      // Send notification e-mails to the subscribers of user events
      //
      if ($C->read("emailNotifications"))
      {
         sendMonthEventNotifications("changed", $viewData['year'], $viewData['month'], $viewData['regionname']);
      }
      
      //
      // Log this event
      //
      $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_updated", $M->region . " " . $M->year.$M->month);
      
      //
      // Success
     //
      $showAlert = TRUE;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['monthedit_alert_update'];
      $alertData['text'] = $LANG['monthedit_alert_update_success'];
      $alertData['help'] = '';
   }
   // ,---------------,
   // | Select Region |
   // '---------------'
   elseif (isset($_POST['btn_region']))
   {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $_POST['sel_region']);
      die();
   }
}

//=============================================================================
//
// PREPARE VIEW
//
$M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid']);
$viewData['holidays'] = $H->getAllCustom();
$viewData['dayStyles'] = array();

//
// Get the holiday and weekend colors
//
for ($i=1; $i<=$viewData['dateInfo']['daysInMonth']; $i++) 
{
   $viewData['dayStyles'][$i] = '';
   $hprop = 'hol'.$i;
   $wprop = 'wday'.$i;
   if ($M->$hprop) 
   {
      //
      // This is a holiday. Get the coloring info.
      //
      $color = $H->getColor($M->$hprop);
      $bgcolor = $H->getBgColor($M->$hprop);
      $viewData['dayStyles'][$i] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';
   }
   else if ($M->$wprop==6 OR $M->$wprop==7) 
   {
      //
      // This is a Saturday or Sunday. Get the coloring info.
      //
      $color = $H->getColor($M->$wprop-4);
      $bgcolor = $H->getBgColor($M->$wprop-4);
      $viewData['dayStyles'][$i] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';
   }
}

$todayDate = getdate(time());
$viewData['yearToday'] = $todayDate['year'];
$viewData['monthToday'] = sprintf("%02d", $todayDate['mon']);
$viewData['regions'] = $R->getAll();
$viewData['showWeekNumbers'] = $C->read('showWeekNumbers');
$mobilecols['full'] = $viewData['dateInfo']['daysInMonth'];
$viewData['supportMobile'] = $C->read('supportMobile');

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
