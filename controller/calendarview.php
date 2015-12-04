<?php
/**
 * calendar.php
 * 
 * Calendar page controller
 *
 * @category TeamCal Neo 
 * @version 0.3.004
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcneo.lewe.com/doc/license.txt
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"calendar.php: \");</script>";

/**
 * ========================================================================
 * Check URL params
 */
if (isset($_GET['month']) AND isset($_GET['region']))
{
   $missingData = FALSE;
   
   $yyyymm = sanitize($_GET['month']);
   $calData['year'] = substr($yyyymm, 0, 4);
   $calData['month'] = substr($yyyymm, 4, 2);
   if ( !is_numeric($yyyymm) OR 
        strlen($yyyymm) != 6 OR 
        !checkdate(intval($calData['month']),1,intval($calData['year'])) ) 
   {
      $missingData = TRUE;
   }
   
   $region = sanitize($_GET['region']);
   if (!$R->getById($region)) 
   {
      $missingData = TRUE;
   }
   else 
   {
      $calData['regionid'] = $R->id;
      $calData['regionname'] = $R->name;
   }
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

/**
 * ========================================================================
 * Load controller stuff
 */

/**
 * ========================================================================
 * Initialize variables
 */
$inputAlert = array();
$currDate = date('Y-m-d');
$calData['dateInfo'] = dateInfo($calData['year'], $calData['month']);

/**
 * See if a region month template exists. If not, create one.
 */
if (!$M->getMonth($calData['year'], $calData['month'], $calData['regionid'])) 
{
   createMonth($calData['year'], $calData['month'], 'region', $calData['regionid']);
   $M->getMonth($calData['year'], $calData['month'], $calData['regionid']);
    
   /**
    * Send notification e-mails to the subscribers of user events
    */
   if ($C->read("emailNotifications"))
   {
      sendMonthEventNotifications("created", $calData['year'], $calData['month'], $calData['regionname']);
   }
          
   /**
    * Log this event
    */
   $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
}

/**
 * Select default users (All)
 */
$calData['absid'] = 'all';
$calData['absence'] = $LANG['all'];
$calData['absences'] = $A->getAll();
$calData['groupid'] = 'all';
$calData['group'] = $LANG['all'];
$calData['users'] = $U->getAll();
$calData['search'] = '';

/**
 * ========================================================================
 * Process form
 */
if (!empty($_POST))
{
   /**
    * Sanitize input
    */
   $_POST = sanitize($_POST);
   
   /**
    * Form validation
    */
   $inputError = false;
   if (isset($_POST['btn_search']))
   {
      if (!formInputValid('txt_search', 'required|alpha_numeric_dash')) $inputError = true;
   }
    
   if (!$inputError)
   {
      /**
       * ,---------------,
       * | Select Region |
       * '---------------'
       */
      if (isset($_POST['btn_region']))
      {
         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $calData['year'] . $calData['month'] . "&region=" . $_POST['sel_region']);
         die();
      }
      /**
       * ,--------------,
       * | Select Group |
       * '--------------'
       */
      elseif (isset($_POST['btn_group']))
      {
         if ($_POST['sel_group'] == "all")
         {
            $calData['users'] = $U->getAll();
         }
         else 
         {
            $calData['groupval'] = $_POST['sel_group'];
            $calData['group'] = $G->getNameById($_POST['sel_group']);
            $calData['users'] = $UG->getAllForGroup($_POST['sel_group']);
         }
      }
      /**
       * ,-------------,
       * | Search user |
       * '-------------'
       */
      elseif (isset($_POST['btn_search']))
      {
         $calData['search'] = $_POST['txt_search'];
         $calData['users'] = $U->getAllLike($_POST['txt_search']);
      }
      /**
       * ,----------------,
       * | Search absence |
       * '----------------'
       */
      elseif (isset($_POST['btn_abssearch']))
      {
         $calData['users'] = array();
         $usernames = array();
         $calData['absid'] = $_POST['sel_absence'];
         $calData['absence'] = $A->getName($_POST['sel_absence']);
         $calData['users'] = $T->getUsersForAbsence(date('Y'), date('m'), intval(date('d')), $_POST['sel_absence']);
      }
   }
   else
   {
      /**
       * Input validation failed
       */
      $showAlert = TRUE;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['alert_input'];
      $alertData['text'] = $LANG['abs_alert_save_failed'];
      $alertData['help'] = '';
   }
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$calData['holidays'] = $H->getAllCustom();
$calData['groups'] = $G->getAll();
$calData['dayStyles'] = array();

/**
 * See if a month template for each user exists. If not, create one.
 */
foreach ($calData['users'] as $user)
{
   if (!$T->getTemplate($user['username'], $calData['year'], $calData['month']))
   {
      createMonth($calData['year'], $calData['month'], 'user', $user['username']);
   }
}

/**
 * Get the holiday and weekend colors
 * These styles are saved in the $calData['dayStyles'] array and affect the whole
 * column of a day.
 */
for ($i=1; $i<=$calData['dateInfo']['daysInMonth']; $i++) 
{
   $color = '';
   $bgcolor = '';
   $border = '';
   $calData['dayStyles'][$i] = '';
   $hprop = 'hol'.$i;
   $wprop = 'wday'.$i;
   if ($M->$hprop) 
   {
      /**
       * This is a holiday. Get the coloring info.
       */
      $color = 'color:#' . $H->getColor($M->$hprop) . ';';
      $bgcolor = 'background-color:#' . $H->getBgColor($M->$hprop) . ';';
   }
   else if ($M->$wprop==6 OR $M->$wprop==7) 
   {
      /**
       * This is a Saturday or Sunday. Get the coloring info.
       */
      $color = 'color:#' . $H->getColor($M->$wprop-4) . ';';
      $bgcolor = 'background-color:#' . $H->getBgColor($M->$wprop-4) . ';';
   }
   
   /**
    * Get today style
    */
   $loopDate = date('Y-m-d', mktime(0, 0, 0, $calData['month'], $i, $calData['year']));
   if ( $loopDate == $currDate )
   {
      $border = 'border-left: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';border-right: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';';
   }
   
   /**
    * Build styles
    */
   if ( strlen($color) OR strlen($bgcolor) OR strlen($border) )
   {
      $calData['dayStyles'][$i] = $color . $bgcolor . $border;
   }
}

$todayDate = getdate(time());
$calData['yearToday'] = $todayDate['year'];
$calData['monthToday'] = sprintf("%02d", $todayDate['mon']);
$calData['regions'] = $R->getAll();
$calData['showWeekNumbers'] = $C->read('showWeekNumbers');
$mobilecols['full'] = $calData['dateInfo']['daysInMonth'];
$calData['supportMobile'] = $C->read('supportMobile');

/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
