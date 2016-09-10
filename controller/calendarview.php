<?php
/**
 * calendarview.php
 * 
 * Calendar view page controller
 *
 * @category TeamCal Neo 
* @version 0.9.009
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag http://tcneo.lewe.com/doc/license.txt
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"calendar.php: \");</script>";

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
   $viewData['year'] = substr($yyyymm, 0, 4);
   $viewData['month'] = substr($yyyymm, 4, 2);
   if ( !is_numeric($yyyymm) OR 
        strlen($yyyymm) != 6 OR 
        !checkdate(intval($viewData['month']),1,intval($viewData['year'])) ) 
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
      $viewData['regionid'] = $R->id;
      $viewData['regionname'] = $R->name;
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
$viewData['dateInfo'] = dateInfo($viewData['year'], $viewData['month']);

//
// See if a region month template exists. If not, create one.
//
if (!$M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid'])) 
{
   createMonth($viewData['year'], $viewData['month'], 'region', $viewData['regionid']);
   $M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid']);
    
   //
   // Send notification e-mails to the subscribers of user events
   //
   if ($C->read("emailNotifications"))
   {
      sendMonthEventNotifications("created", $viewData['year'], $viewData['month'], $viewData['regionname']);
   }
          
   //
   // Log this event
   //
   $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
}

//
// Select default users (All)
//
$viewData['absfilter'] = false;
$viewData['absid'] = 'all';
$viewData['absence'] = $LANG['all'];
$viewData['absences'] = $A->getAll();
$viewData['groupid'] = 'all';
$viewData['group'] = $LANG['all'];
$viewData['search'] = '';

$users = $U->getAll();

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
   if (isset($_POST['btn_search']))
   {
      if (!formInputValid('txt_search', 'required|alpha_numeric_dash')) $inputError = true;
   }
    
   if (!$inputError)
   {
      // ,---------------,
      // | Select Region |
      // '---------------'
      if (isset($_POST['btn_region']))
      {
         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $_POST['sel_region']);
         die();
      }
      // ,---------------,
      // | Select Group  |
      // '---------------'
      elseif (isset($_POST['btn_group']))
      {
         if ($_POST['sel_group'] == "all")
         {
            unset($users);
            $users = $U->getAll();
         }
         else 
         {
            $viewData['groupval'] = $_POST['sel_group'];
            $viewData['group'] = $G->getNameById($_POST['sel_group']);
            unset($users);
            $users = $UG->getAllForGroup($_POST['sel_group']);
         }
      }
      // ,---------------,
      // | Search User   |
      // '---------------'
      elseif (isset($_POST['btn_search']))
      {
         $viewData['search'] = $_POST['txt_search'];
         unset($users);
         $users = $U->getAllLike($_POST['txt_search']);
      }
      // ,----------------,
      // | Search Absence |
      // '----------------'
      elseif (isset($_POST['btn_abssearch']))
      {
         if ($_POST['sel_absence'] != "all")
         {
            $viewData['absfilter'] = true;
            $viewData['absid'] = $_POST['sel_absence'];
            $viewData['absence'] = $A->getName($_POST['sel_absence']);
            $ausers = array();
            foreach ($users as $usr)
            {
               if ($T->hasAbsence($usr['username'], date('Y'), date('m'),$_POST['sel_absence']))
               {
                  array_push($ausers,$usr);
               }
            }
            unset($users);
            $users = $ausers;
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
      $alertData['text'] = $LANG['abs_alert_save_failed'];
      $alertData['help'] = '';
   }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['holidays'] = $H->getAllCustom();
$viewData['groups'] = $G->getAll();
$viewData['dayStyles'] = array();

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
      $viewData['users'][] = $usr;
   }
}

//
// See if a month template for each user exists. If not, create one.
//
foreach ($viewData['users'] as $user)
{
   if (!$T->getTemplate($user['username'], $viewData['year'], $viewData['month']))
   {
      createMonth($viewData['year'], $viewData['month'], 'user', $user['username']);
   }
}

//
// Get the holiday and weekend colors
// These styles are saved in the $viewData['dayStyles'] array and affect the whole
// column of a day.
//
for ($i=1; $i<=$viewData['dateInfo']['daysInMonth']; $i++) 
{
   $color = '';
   $bgcolor = '';
   $border = '';
   $viewData['dayStyles'][$i] = '';
   $hprop = 'hol'.$i;
   $wprop = 'wday'.$i;
   if ($M->$hprop) 
   {
      //
      // This is a holiday. Get the coloring info.
      //
      $color = 'color:#' . $H->getColor($M->$hprop) . ';';
      $bgcolor = 'background-color:#' . $H->getBgColor($M->$hprop) . ';';
   }
   else if ($M->$wprop==6 OR $M->$wprop==7) 
   {
      //
      // This is a Saturday or Sunday. Get the coloring info.
      //
      $color = 'color:#' . $H->getColor($M->$wprop-4) . ';';
      $bgcolor = 'background-color:#' . $H->getBgColor($M->$wprop-4) . ';';
   }
   
   //
   // Get today style
   //
   $loopDate = date('Y-m-d', mktime(0, 0, 0, $viewData['month'], $i, $viewData['year']));
   if ( $loopDate == $currDate )
   {
      $border = 'border-left: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';border-right: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';';
   }
   
   //
   // Build styles
   //
   if ( strlen($color) OR strlen($bgcolor) OR strlen($border) )
   {
      $viewData['dayStyles'][$i] = $color . $bgcolor . $border;
   }
}

//
// Get the number of business days
//
$cntfrom = $viewData['year'].$viewData['month'].'01';
$cntto = $viewData['year'].$viewData['month'].$viewData['dateInfo']['daysInMonth'];
$viewData['businessDays'] = countBusinessDays($cntfrom, $cntto, $viewData['regionid']);

$todayDate = getdate(time());
$viewData['yearToday'] = $todayDate['year'];
$viewData['monthToday'] = sprintf("%02d", $todayDate['mon']);
$viewData['regions'] = $R->getAll();
$viewData['showWeekNumbers'] = $C->read('showWeekNumbers');
$mobilecols['full'] = $viewData['dateInfo']['daysInMonth'];
$viewData['supportMobile'] = $C->read('supportMobile');
$viewData['firstDayOfWeek'] = $C->read("firstDayOfWeek");

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
