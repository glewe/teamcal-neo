<?php
/**
 * year.php
 * 
 * Year calendar page controller
 *
 * @category TeamCal Neo 
 * @version 0.3.003
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
if (isset($_GET['year']) AND isset($_GET['region']) AND isset($_GET['user']))
{
   $missingData = FALSE;
   
   $yyyy = sanitize($_GET['year']);
   if ( !is_numeric($yyyy) OR strlen($yyyy) != 4 OR !checkdate(1,1,intval($yyyy)) ) $missingData = TRUE;
   
   $region = sanitize($_GET['region']);
   if (!$R->getById($region)) $missingData = TRUE;
   
   $users = $U->getAll();
   if (strlen($_GET['user']))
   {
      $user = sanitize($_GET['user']);
      if (!$U->exists($user)) $missingData = TRUE;
   }
   else
   {
      $user = $users[0]['username'];
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
if ( !isAllowed($controller) OR 
     (isAllowed($controller) AND !$L->checkLogin() AND !isAllowed("calendarviewall"))
   )
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

/**
 * Loop through all months
 */
for ($i=1; $i<=12; $i++)
{
   /**
    * See if a template for the month exists. If not, create one.
    */
   if (!$M->getMonth($yyyy, $i, $R->id)) 
   {
      createMonth($yyyy, $i, 'region', $R->id);
        
      /**
       * Send notification e-mails to the subscribers of user events
       */
      if ($C->read("emailNotifications"))
      {
         sendMonthEventNotifications("created", $yyyy, $i, $R->name);
      }
             
      /**
       * Log this event
       */
      $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
   }

   /**
    * See if a template for the month for the user exists. If not, create one.
    */
   if (!$T->getTemplate($user, $yyyy, $i))
   {
      createMonth($yyyy, $i, 'user', $user);
   }
   
   $yearData['monthInfo'][$i] = dateInfo($yyyy, $i);
    
   /**
    * Loop through all days of the current month
    */
   for ($d=1; $d<=$yearData['monthInfo'][$i]['daysInMonth']; $d++)
   {
      $yearData['month'][$i][$d]['wday'] = $M->getWeekday($yyyy, $i, $d, $R->id);
      $yearData['month'][$i][$d]['hol'] = $M->getHoliday($yyyy, $i, $d, $R->id);
      $yearData['month'][$i][$d]['abs'] = $T->getAbsence($user, $yyyy, $i, $d);
      $yearData['month'][$i][$d]['symbol'] = '';
      $yearData['month'][$i][$d]['icon'] = '';
      $yearData['month'][$i][$d]['style'] = '';
      $yearData['month'][$i][$d]['absstyle'] = '';
      
      $color = '';
      $bgcolor = '';
      $border = '';
      
      /**
       * Get weekend style
       */
      if ($yearData['month'][$i][$d]['wday']==6 or $yearData['month'][$i][$d]['wday']==7)
      {
         $color = 'color: #' . $H->getColor($yearData['month'][$i][$d]['wday']-4) . ';';
         $bgcolor = 'background-color: #' . $H->getBgColor($yearData['month'][$i][$d]['wday']-4) . ';';
      }
      
      /**
       * Get holiday style (overwrites weekend style)
       */
      if ($yearData['month'][$i][$d]['hol'])
      {
         $color = 'color: #' . $H->getColor($yearData['month'][$i][$d]['hol']) . ';';
         $bgcolor = 'background-color: #' . $H->getBgColor($yearData['month'][$i][$d]['hol']) . ';';
      }
      
      /**
       * Get today style
       */
      $loopDate = date('Y-m-d', mktime(0, 0, 0, $i, $d, $yyyy));
      if ( $loopDate == $currDate )
      {
         $border = 'border: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';';
      }
      
      /**
       * Build styles
       */
      if ( strlen($color) OR strlen($bgcolor) OR strlen($border) )
      {
         $yearData['month'][$i][$d]['style'] = ' style="' . $color . $bgcolor . $border . '"';
      }
      
      /**
       * Get absence style of user template.
       */
      if ( $yearData['month'][$i][$d]['abs'] )
      {
         $A->get($yearData['month'][$i][$d]['abs']);
         $yearData['month'][$i][$d]['icon'] = $A->icon;
         if ($A->bgtrans) $bgStyle = ""; else $bgStyle = "background-color: #" . $A->bgcolor . ';"';
         $yearData['month'][$i][$d]['absstyle'] = ' style="color: #' . $A->color . ';' . $bgStyle . '"';
      }
   }
}

/**
 * ========================================================================
 * Process form
 */
if (!empty($_POST))
{
   /**
    * ,---------------,
    * | Select Region |
    * '---------------'
    */
   if (isset($_POST['btn_region']))
   {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&year=" . $yyyy . "&region=" . $_POST['sel_region'] . "&user=" . $user);
      die();
   }
   /**
    * ,-------------,
    * | Select User |
    * '-------------'
    */
   elseif (isset($_POST['btn_user']))
   {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&year=" . $yyyy . "&region=" . $region . "&user=" . $_POST['sel_user']);
      die();
   }
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$yearData['username'] = $user;
$yearData['fullname'] = $U->getFullname($user);
$yearData['year'] = $yyyy;
$yearData['regionid'] = $R->id;
$yearData['regionname'] = $R->name;
$yearData['regions'] = $R->getAll();

$yearData['users'] = array();
foreach ($users as $usr) 
{
   $allowed = false;
   if ($usr['username']==$UL->username) 
   {
      $allowed = true;
   }
   else if ( !$U->isHidden($usr['username']) ) 
   {
      if (isAllowed("calendarviewall")) 
      {
         $allowed = true;
      }
      elseif (isAllowed("calendarviewgroup") AND $UG->shareGroups($usr['username'], $UL->username) ) 
      {
         $allowed = true;
      }
   }
   if ($allowed) 
   {
      $yearData['users'][] = array ('username' => $usr['username'], 'lastfirst' => $U->getLastFirst($usr['username']));
   }
}

$color = $H->getColor(2);
$bgcolor = $H->getBgColor(2);
$yearData['satStyle'] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';

$color = $H->getColor(3);
$bgcolor = $H->getBgColor(3);
$yearData['sunStyle'] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';


/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
