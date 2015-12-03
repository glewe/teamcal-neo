<?php
/**
 * monthedit.php
 * 
 * Month edit page controller
 *
 * @category TeamCal Neo 
 * @version 0.3.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcneo.lewe.com/doc/license.txt
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

/**
 * ========================================================================
 * Check URL params
 */
if (isset($_GET['month']) AND isset($_GET['region']))
{
   $missingData = FALSE;
   $yyyymm = sanitize($_GET['month']);
   $region = sanitize($_GET['region']);
   $monthData['year'] = substr($yyyymm, 0, 4);
   $monthData['month'] = substr($yyyymm, 4, 2);
   if (!is_numeric($yyyymm) OR strlen($yyyymm) != 6 OR !checkdate(intval($monthData['month']),1,intval($monthData['year'])) ) $missingData = TRUE;
   if (!$R->getById($region)) $missingData = TRUE;
}
else
{
   $missingData = TRUE;
}

if ($missingData)
{
   /**
    * URL param fail
    */
   $alertData['type'] = 'danger';
   $alertData['title'] = $LANG['alert_danger_title'];
   $alertData['subject'] = $LANG['alert_no_data_subject'];
   $alertData['text'] = $LANG['alert_no_data_text'];
   $alertData['help'] = $LANG['alert_no_data_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
   die();
}

/**
 * ========================================================================
 * Check if allowed
 */
if (!isAllowed($controller))
{
   $alertData['type'] = 'warning';
   $alertData['title'] = $LANG['alert_alert_title'];
   $alertData['subject'] = $LANG['alert_not_allowed_subject'];
   $alertData['text'] = $LANG['alert_not_allowed_text'];
   $alertData['help'] = $LANG['alert_not_allowed_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
   die();
}

/**
 * ========================================================================
 * Load controller stuff
 */

/**
 * ========================================================================
 * Initialize variables
 */
$inputAlert = array();
$monthData['regionid'] = $R->id;
$monthData['regionname'] = $R->name;

/**
 * See if a template exists. If not, create one.
 */
if (!$M->getMonth($monthData['year'], $monthData['month'], $monthData['regionid'])) 
{
   createMonth($monthData['year'], $monthData['month'], 'region', $monthData['regionid']);
     
   /**
    * Log this event
    */
   $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
}

$monthData['dateInfo'] = dateInfo($monthData['year'], $monthData['month']);

/**
 * ========================================================================
 * Process form
 */
if (!empty($_POST))
{
   /**
    * ,------,
    * | Save |
    * '------'
    */
   if (isset($_POST['btn_save']))
   {
      /**
       * Loop thru the radio boxes
       */
      for ($i=1; $i<=$monthData['dateInfo']['daysInMonth']; $i++) 
      {
         $key = 'opt_hol_'.$i;
         if (isset($_POST[$key])) $M->setHoliday($monthData['year'], $monthData['month'], $i, $monthData['regionid'], $_POST[$key]);
      }
      
      /**
       * Send notification e-mails to the subscribers of user events
       */
      if ($C->read("emailNotifications"))
      {
         sendMonthEventNotifications("changed", $monthData['year'], $monthData['month'], $monthData['regionname']);
      }
          
      /**
       * Log this event
       */
      $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_updated", $M->region . " " . $M->year.$M->month);
      
      /**
       * Success
       */
      $showAlert = TRUE;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['monthedit_alert_update'];
      $alertData['text'] = $LANG['monthedit_alert_update_success'];
      $alertData['help'] = '';
   }
   /**
    * ,-----------,
    * | Clear all |
    * '-----------'
    */
   elseif (isset($_POST['btn_clearall']))
   {
      $M->clearHolidays($monthData['year'], $monthData['month'], $monthData['regionid']);
      
      /**
       * Send notification e-mails to the subscribers of user events
       */
      if ($C->read("emailNotifications"))
      {
         sendMonthEventNotifications("changed", $monthData['year'], $monthData['month'], $monthData['regionname']);
      }
      
      /**
       * Log this event
       */
      $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_updated", $M->region . " " . $M->year.$M->month);
      
      /**
       * Success
      */
      $showAlert = TRUE;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['monthedit_alert_update'];
      $alertData['text'] = $LANG['monthedit_alert_update_success'];
      $alertData['help'] = '';
   }
   /**
    * ,--------,
    * | Select |
    * '--------'
    */
   elseif (isset($_POST['btn_region']))
   {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $monthData['year'] . $monthData['month'] . "&region=" . $_POST['sel_region']);
      die();
   }
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$M->getMonth($monthData['year'], $monthData['month'], $monthData['regionid']);
$monthData['holidays'] = $H->getAllCustom();
$monthData['dayStyles'] = array();

/**
 * Get the holiday and weekend colors
 */
for ($i=1; $i<=$monthData['dateInfo']['daysInMonth']; $i++) 
{
   $monthData['dayStyles'][$i] = '';
   $hprop = 'hol'.$i;
   $wprop = 'wday'.$i;
   if ($M->$hprop) 
   {
      /**
       * This is a holiday. Get the coloring info.
       */
      $color = $H->getColor($M->$hprop);
      $bgcolor = $H->getBgColor($M->$hprop);
      $monthData['dayStyles'][$i] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';
   }
   else if ($M->$wprop==6 OR $M->$wprop==7) 
   {
      /**
       * This is a Saturday or Sunday. Get the coloring info.
       */
      $color = $H->getColor($M->$wprop-4);
      $bgcolor = $H->getBgColor($M->$wprop-4);
      $monthData['dayStyles'][$i] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';
   }
}

$todayDate = getdate(time());
$monthData['yearToday'] = $todayDate['year'];
$monthData['monthToday'] = sprintf("%02d", $todayDate['mon']);
$monthData['regions'] = $R->getAll();
$monthData['showWeekNumbers'] = $C->read('showWeekNumbers');
$mobilecols['full'] = $monthData['dateInfo']['daysInMonth'];
$monthData['supportMobile'] = $C->read('supportMobile');

/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
