<?php
/**
 * year.php
 * 
 * Year calendar page controller
 *
 * @category TeamCal Neo 
 * @version 2.0.1
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo '<script type="text/javascript">alert("Debug: ");</script>';

//=============================================================================
//
// CHECK URL PARAMETERS
//
if (isset($_GET['year']) AND isset($_GET['region']) AND isset($_GET['user']))
{
   $missingData = FALSE;
   
   $yyyy = sanitize($_GET['year']);
   if ( !is_numeric($yyyy) OR strlen($yyyy) != 4 OR !checkdate(1,1,intval($yyyy)) ) $missingData = TRUE;
   
   $region = sanitize($_GET['region']);
   if (!$R->getById($region)) $missingData = TRUE;
   
   if (strlen($_GET['user']))
   {
      $user = sanitize($_GET['user']);
      if (!$U->exists($user)) $missingData = TRUE;
   }
   else
   {
      $user = '';
   }
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

//
// Default back to current yearmonth if option is set
//
if ($C->read('currentYearOnly') AND $yyyy!=date('Y'))
{
   header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&year=" . date('Y') . "&region=" . $region . "&user=" . $user);
   die();
}

//=============================================================================
//
// CHECK PERMISSION
//
if ( !isAllowed($CONF['controllers'][$controller]->permission) )
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
$inputAlert = array();
$currDate = date('Y-m-d');
$users = $U->getAll();

//
// Loop through all months
//
for ($i=1; $i<=12; $i++)
{
   //
   // See if a template for the month exists. If not, create one.
   //
   if (!$M->getMonth($yyyy, $i, $R->id)) 
   {
      createMonth($yyyy, $i, 'region', $R->id);
        
      //
      // Send notification e-mails to the subscribers of user events
      //
      if ($C->read("emailNotifications"))
      {
         sendMonthEventNotifications("created", $yyyy, $i, $R->name);
      }
             
      //
      // Log this event
      //
      $LOG->log("logMonth", L_USER, "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
   }

   //
   // See if a template for the month for the user exists. If not, create one.
   //
   if (!$T->getTemplate($user, $yyyy, $i))
   {
      createMonth($yyyy, $i, 'user', $user);
   }
   
   $viewData['monthInfo'][$i] = dateInfo($yyyy, $i);
    
   //
   // Loop through all days of the current month
   //
   for ($d=1; $d<=$viewData['monthInfo'][$i]['daysInMonth']; $d++)
   {
      $viewData['month'][$i][$d]['wday'] = $M->getWeekday($yyyy, $i, $d, $R->id);
      $viewData['month'][$i][$d]['hol'] = $M->getHoliday($yyyy, $i, $d, $R->id);
      $viewData['month'][$i][$d]['abs'] = $T->getAbsence($user, $yyyy, $i, $d);
      $viewData['month'][$i][$d]['symbol'] = '';
      $viewData['month'][$i][$d]['icon'] = '';
      $viewData['month'][$i][$d]['style'] = '';
      $viewData['month'][$i][$d]['absstyle'] = '';
      
      $color = '';
      $bgcolor = '';
      $border = '';
      
      //
      // Get weekend style
      //
      if ($viewData['month'][$i][$d]['wday']==6 or $viewData['month'][$i][$d]['wday']==7)
      {
         $color = 'color: #' . $H->getColor($viewData['month'][$i][$d]['wday']-4) . ';';
         $bgcolor = 'background-color: #' . $H->getBgColor($viewData['month'][$i][$d]['wday']-4) . ';';
      }
      
      //
      // Get holiday style (overwrites weekend style)
      //
      if ($viewData['month'][$i][$d]['hol'])
      {
         $color = 'color: #' . $H->getColor($viewData['month'][$i][$d]['hol']) . ';';
         $bgcolor = 'background-color: #' . $H->getBgColor($viewData['month'][$i][$d]['hol']) . ';';
      }
      
      //
      // Get today style
      //
      $loopDate = date('Y-m-d', mktime(0, 0, 0, $i, $d, $yyyy));
      if ( $loopDate == $currDate )
      {
         $border = 'border: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';';
      }
      
      //
      // Build styles
      //
      if ( strlen($color) OR strlen($bgcolor) OR strlen($border) )
      {
         $viewData['month'][$i][$d]['style'] = ' style="' . $color . $bgcolor . $border . '"';
      }
      
      //
      // Get absence style of user template.
      //
      if ( $viewData['month'][$i][$d]['abs'] )
      {
         $A->get($viewData['month'][$i][$d]['abs']);
         $viewData['month'][$i][$d]['icon'] = $A->icon;
         $viewData['month'][$i][$d]['symbol'] = $A->symbol;
         if ($A->bgtrans) $bgStyle = ""; else $bgStyle = "background-color: #" . $A->bgcolor . ";";
         $viewData['month'][$i][$d]['absstyle'] = ' style="color: #' . $A->color . ';' . $bgStyle . '"';
      }
   }
}

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST))
{
   // ,---------------,
   // | Select Region |
   // '---------------'
   if (isset($_POST['btn_region']))
   {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&year=" . $yyyy . "&region=" . $_POST['sel_region'] . "&user=" . $user);
      die();
   }
   // ,---------------,
   // | Select User   |
   // '---------------'
   elseif (isset($_POST['btn_user']))
   {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&year=" . $yyyy . "&region=" . $region . "&user=" . $_POST['sel_user']);
      die();
   }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['username'] = $user;
$viewData['fullname'] = $U->getFullname($user);
$viewData['year'] = $yyyy;
$viewData['regionid'] = $R->id;
$viewData['regionname'] = $R->name;
$viewData['regions'] = $R->getAll();

$viewData['users'] = array();
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
      $viewData['users'][] = array ('username' => $usr['username'], 'lastfirst' => $U->getLastFirst($usr['username']));
   }
}

$color = $H->getColor(2);
$bgcolor = $H->getBgColor(2);
$viewData['satStyle'] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';

$color = $H->getColor(3);
$bgcolor = $H->getBgColor(3);
$viewData['sunStyle'] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
