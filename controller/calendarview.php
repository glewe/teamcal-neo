<?php
/**
 * calendarview.php
 * 
 * Calendar view page controller
 *
 * @category TeamCal Neo 
 * @version 1.8.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"calendarview.php: \");</script>";

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
// CHECK URL PARAMETERS OR USER DEFAULTS
//

$missingData = FALSE;

//-----------------------------------------------------------------------------
//
// We need a Month
//
if (isset($_GET['month']))
{
   //
   // Passed by URL always wins
   //
   $monthfilter = sanitize($_GET['month']);
}
elseif (L_USER AND $monthfilter=$UO->read($UL->username, 'calfilterMonth')) 
{
   //
   // Nothing in URL but user has a last-used value in his profile. Let's try that one.
   // (The value was loaded via the if statement so nothing needed in this block.)
   //
}
else
{
   //
   // Default to current year and month
   //
   $monthfilter = date('Y').date('m');
}

//
// If we have a Month, check validity
//
if (!$missingData)
{
   $viewData['year'] = substr($monthfilter, 0, 4);
   $viewData['month'] = substr($monthfilter, 4, 2);
   if ( !is_numeric($monthfilter) OR
         strlen($monthfilter) != 6 OR
         !checkdate(intval($viewData['month']),1,intval($viewData['year'])) )
   {
      $missingData = TRUE;
   }
   else 
   {
      if (L_USER) $UO->save($UL->username, 'calfilterMonth', $monthfilter);
   }
}

//-----------------------------------------------------------------------------
//
// We need a Region
//
if (isset($_GET['region']))
{
   //
   // Passed by URL always wins
   //
   $regionfilter = sanitize($_GET['region']);
   if (L_USER) $UO->save($UL->username, 'calfilterRegion', $regionfilter);
}
elseif (L_USER AND $regionfilter=$UO->read($UL->username, 'calfilterRegion'))
{
   //
   // Nothing in URL but user has a last-used value in his profile. Let's try that one.
   // (The value was loaded via the if statement so nothing needed in this block.)
   //
}
else
{
   //
   // Default to default region
   //
   $regionfilter = '1';
}

//
// If we have a Region, check validity
//
if (!$missingData)
{
   if (!$R->getById($regionfilter))
   {
      $missingData = TRUE;
   }
   else
   {
      $viewData['regionid'] = $R->id;
      $viewData['regionname'] = $R->name;
      if (L_USER) $UO->save($UL->username, 'calfilterRegion', $regionfilter);
   }
}

if ($missingData)
{
   //
   // No valid Month or Region
   //
   $alertData['type'] = 'danger';
   $alertData['title'] = $LANG['alert_danger_title'];
   $alertData['subject'] = $LANG['alert_no_data_subject'];
   $alertData['text'] = $LANG['alert_no_data_text'];
   $alertData['help'] = $LANG['alert_no_data_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
   die();
}

//-----------------------------------------------------------------------------
//
// Group filter (optional, defaults to 'all')
//
$users = $U->getAllButHidden();

if (isset($_GET['group']))
{
   $groupfilter = sanitize($_GET['group']);
   if (L_USER) $UO->save($UL->username, 'calfilterGroup', $groupfilter);
}
elseif (L_USER AND $groupfilter=$UO->read($UL->username, 'calfilterGroup'))
{
   //
   // Nothing in URL but user has a last-used value in his profile.
   // (The value was loaded via the if statement so nothing needed in this block.)
   //
}
else
{
   $groupfilter = 'all';
}
    
$viewData['groupid'] = $groupfilter;
if ($groupfilter == "all")
{
   $viewData['group'] = $LANG['all'];
}
else
{
   $viewData['group'] = $G->getNameById($groupfilter);
   //
   // Remove all users from array that are not in requested group.
   //
   $calusers = array();
   foreach ($users as $key=>$usr)
   {
      if ($UG->isMemberOrGuestOfGroup($usr['username'], $groupfilter))
      {
         $calusers[] = $usr;
      }
   }
   $users = $calusers;
}

//-----------------------------------------------------------------------------
//
// Absence filter (optional, defaults to 'all')
//
if (isset($_GET['abs']))
{
   $absfilter = sanitize($_GET['abs']);
   if (L_USER) $UO->save($UL->username, 'calfilterAbs', $absfilter);
}
elseif (L_USER AND $absfilter=$UO->read($UL->username, 'calfilterAbs'))
{
   //
   // Nothing in URL but user has a last-used value in his profile.
   // (The value was loaded via the if statement so nothing needed in this block.)
   //
}
else
{
   $absfilter = 'all';
}

$viewData['absid'] = $absfilter;
if ($absfilter == "all")
{
   $viewData['absfilter'] = false;
   $viewData['absence'] = $LANG['all'];
}
else
{
   $viewData['absfilter'] = true;
   $viewData['absence'] = $A->getName($absfilter);
   $ausers = array();
   foreach ($users as $usr)
   {
      if ($T->hasAbsence($usr['username'], date('Y'), date('m'),$absfilter))
      {
         array_push($ausers,$usr);
      }
   }
   unset($users);
   $users = $ausers;
}

//-----------------------------------------------------------------------------
//
// Search filter (optional, defaults to 'all')
//
$viewData['search'] = '';
if (L_USER AND $searchfilter=$UO->read($UL->username, 'calfilterSearch'))
{
   //
   // Nothing in URL but user has a last-used value in his profile.
   // (The value was loaded via the if statement so nothing needed in this block.)
   //
   $viewData['search'] = $searchfilter;
   unset($users);
   $users = $U->getAllLike($searchfilter);
}

//-----------------------------------------------------------------------------
//
// Search Reset
//
if (isset($_GET['search']) AND $_GET['search']=="reset")
{
   if (L_USER) $UO->deleteUserOption($UL->username, 'calfilterSearch');
   header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller);
   die();
}

//-----------------------------------------------------------------------------
//
// Default back to current yearmonth if option is set and role matches
//
if ($C->read('currentYearOnly') AND $viewData['year']!=date('Y'))
{
   if ($C->read("currYearRoles")) {
      //
      // Applies to roles
      //
      $arrCurrYearRoles = array();
      $arrCurrYearRoles = explode(',', $C->read("currYearRoles"));
      $userRole = $U->getRole(L_USER);
      if (in_array($userRole,$arrCurrYearRoles)) {
         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . date('Ym') . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter);
         die();
      }
   }
   else {
      //
      // Just in case currYearRoles is not set yet
      //
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . date('Ym') . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter);
      die();
   }
}

//-----------------------------------------------------------------------------
//
// Paging
//
if ($limit = $C->read("usersPerPage"))
{
   // 
   // How many users do we have?
   //
   $total = count($users);
   
   // 
   // How many pages do we need for them?
   //
   $pages = ceil($total/$limit);
   
   // 
   // What page are we currently on?
   //
   $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, 
      array(
         'options' => array(
            'default'   => 1,
            'min_range' => 1,
         ),
      )
   ));   

   // 
   // Get the $users records do we need for this page
   //
   $offset = ($page - 1)  * $limit;
   $start = $offset;
   $end = min(($offset + $limit), $total) - 1;
   $pageusers = array();
   for ($i=$start; $i<=$end; $i++)
   {
      array_push($pageusers,$users[$i]);
   }
   unset($users);
   $users = $pageusers;
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
// Figure out how many months to display
//
$viewData['showTwoMonths'] = false;
$viewData['nbrMonths'] = 'Two';

if ($calendarMonths=$UO->read($UL->username, 'calendarMonths'))
{
   switch($calendarMonths)
   {
      case 'default':
         if ($C->read('showTwoMonths')) {
            $viewData['showTwoMonths'] = true;
            $viewData['nbrMonths'] = 'One';
         }
         else
         {
            $viewData['showTwoMonths'] = false;
            $viewData['nbrMonths'] = 'Two';
         }
         break;

      case 'one':
         $viewData['showTwoMonths'] = false;
         $viewData['nbrMonths'] = 'Two';
         break;
         
      case 'two':
         $viewData['showTwoMonths'] = true;
         $viewData['nbrMonths'] = 'One';
         break;
   }
}
else
{
   $UO->save($UL->username, 'calendarMonths', 'default');
   if ($C->read('showTowMonths'))
   {
      $viewData['showTwoMonths'] = true;
      $viewData['nbrMonths'] = 'One';
   }
}

//
// Prepare following month if option set
//
if ($viewData['showTwoMonths'])
{
   $M2 = new Months();
   if ($viewData['month']==12) 
   {
      $viewData['month2'] = 1;
      $viewData['year2'] = $viewData['year'] + 1;
   }
   else 
   {
      $viewData['month2'] = $viewData['month'] + 1;
      $viewData['year2'] = $viewData['year'];
   }
   $viewData['dateInfo2'] = dateInfo($viewData['year2'], $viewData['month2']);
}

if ($trustedRoles=$C->read("trustedRoles"))
{
   $viewData['trustedRoles'] = explode(',', $trustedRoles);
}
else 
{
   $viewData['trustedRoles'] = array ('1');
   $C->save("trustedRoles", '1');
}

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
   $LOG->log("logMonth", L_USER, "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
}

//
// Do the above for the following month if option set
//
if ($viewData['showTwoMonths'])
{
   if (!$M2->getMonth($viewData['year2'], $viewData['month2'], $viewData['regionid'])) 
   {
      createMonth($viewData['year2'], $viewData['month2'], 'region', $viewData['regionid']);
      $M2->getMonth($viewData['year2'], $viewData['month2'], $viewData['regionid']);
      
      //
      // Send notification e-mails to the subscribers of user events
      //
      if ($C->read("emailNotifications"))
      {
         sendMonthEventNotifications("created", $viewData['year2'], $viewData['month2'], $viewData['regionname']);
      }
            
      //
      // Log this event
      //
      $LOG->log("logMonth", L_USER, "log_month_tpl_created", $M2->region . ": " . $M2->year . "-" . $M2->month);
   }
}

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
      // ,--------------,
      // | Select Month |
      // '--------------'
      if (isset($_POST['btn_month']))
      {
         if (L_USER)
         {
            $UO->save($UL->username, 'calfilterMonth', $_POST['txt_year'] . $_POST['sel_month']);
         }
         
         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $_POST['txt_year'] . $_POST['sel_month']. "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter);
         die();
      }
      // ,---------------,
      // | Select Region |
      // '---------------'
      elseif (isset($_POST['btn_region']))
      {
         if (L_USER)
         {
            $UO->save($UL->username, 'calfilterRegion', $_POST['sel_region']);
         }
          
         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $monthfilter . "&region=" . $_POST['sel_region']. "&group=" . $groupfilter . "&abs=" . $absfilter);
         die();
      }
      // ,---------------,
      // | Select Group  |
      // '---------------'
      elseif (isset($_POST['btn_group']))
      {
         if (L_USER)
         {
            $UO->save($UL->username, 'calfilterGroup', $_POST['sel_group']);
         }
          
         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $_POST['sel_group'] . "&abs=" . $absfilter);
         die();
      }
      // ,----------------,
      // | Select Absence |
      // '----------------'
      elseif (isset($_POST['btn_abssearch']))
      {
         if (L_USER)
         {
            $UO->save($UL->username, 'calfilterAbs', $_POST['sel_absence']);
         }
          
         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $_POST['sel_absence']);
         die();
      }
      // ,---------------------,
      // | Select Screen Width |
      // '---------------------'
      elseif (isset($_POST['btn_width']))
      {
         $UO->save($UL->username, 'width', $_POST['sel_width']);
         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $_POST['sel_absence']);
         die();
      }
      // ,---------------,
      // | Search User   |
      // '---------------'
      elseif (isset($_POST['btn_search']))
      {
         if (L_USER)
         {
            $UO->save($UL->username, 'calfilterSearch', $_POST['txt_search']);
         }
          
         $viewData['search'] = $_POST['txt_search'];
         unset($users);
         $users = $U->getAllLike($_POST['txt_search']);
      }
      // ,-------------------,
      // | Search User Clear |
      // '-------------------'
      elseif (isset($_POST['btn_search_clear']))
      {
         if (L_USER)
         {
            $UO->deleteUserOption($UL->username, 'calfilterSearch');
         }
         
         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter);
         die();
      }
      // ,-------,
      // | Reset |
      // '-------'
      elseif (isset($_POST['btn_reset']))
      {
         if (L_USER)
         {
            $UO->deleteUserOption($UL->username, 'calfilter');
            $UO->deleteUserOption($UL->username, 'calfilterMonth');
            $UO->deleteUserOption($UL->username, 'calfilterRegion');
            $UO->deleteUserOption($UL->username, 'calfilterGroup');
            $UO->deleteUserOption($UL->username, 'calfilterAbs');
            $UO->deleteUserOption($UL->username, 'calfilterSearch');
         }
         
         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller);
         die();
      }
      // ,---------------,
      // | ShowTwoMonths |
      // '---------------'
      elseif (isset($_POST['btn_showTwoMonths']))
      {
         switch ($viewData['showTwoMonths'])
         {
            case true;
               $UO->save($UL->username, 'calendarMonths', 'one');
               break;
            case false;
               $UO->save($UL->username, 'calendarMonths', 'two');
               break;
         }

         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller);
         die();
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
$viewData['absences'] = $A->getAll();
$viewData['allGroups'] = $G->getAll();
$viewData['holidays'] = $H->getAllCustom();

if ($groupfilter == 'all')
{
   $viewData['groups'] = $G->getAll();
}
else 
{
   $viewData['groups'] = $G->getRowById($groupfilter);
}

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

   //
   // Do the above for the following month if option set
   //
   if ($viewData['showTwoMonths'])
   {
      if (!$T->getTemplate($user['username'], $viewData['year2'], $viewData['month2']))
      {
         createMonth($viewData['year2'], $viewData['month2'], 'user', $user['username']);
      }
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
      if ($H->keepWeekendColor($M->$hprop))
      {
         //
         // Weekend color shall be kept. So if this a weekend day color it as such.
         //
         if ($M->$wprop==6 OR $M->$wprop==7)
         {
            $color = 'color:#' . $H->getColor($M->$wprop-4) . ';';
            $bgcolor = 'background-color:#' . $H->getBgColor($M->$wprop-4) . ';';
         }
         else
         {
            $color = 'color:#' . $H->getColor($M->$hprop) . ';';
            $bgcolor = 'background-color:#' . $H->getBgColor($M->$hprop) . ';';
         }
      }
      else 
      {
         $color = 'color:#' . $H->getColor($M->$hprop) . ';';
         $bgcolor = 'background-color:#' . $H->getBgColor($M->$hprop) . ';';
      }
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
// Do the above for the following month if option set
//
if ($viewData['showTwoMonths'])
{
   for ($i=1; $i<=$viewData['dateInfo2']['daysInMonth']; $i++) 
   {
      $color = '';
      $bgcolor = '';
      $border = '';
      $viewData['dayStyles2'][$i] = '';
      $hprop = 'hol'.$i;
      $wprop = 'wday'.$i;
      if ($M2->$hprop) 
      {
         //
         // This is a holiday. Get the coloring info.
         //
         if ($H->keepWeekendColor($M2->$hprop))
         {
            //
            // Weekend color shall be kept. So if this a weekend day color it as such.
            //
            if ($M2->$wprop==6 OR $M2->$wprop==7)
            {
               $color = 'color:#' . $H->getColor($M2->$wprop-4) . ';';
               $bgcolor = 'background-color:#' . $H->getBgColor($M2->$wprop-4) . ';';
            }
            else
            {
               $color = 'color:#' . $H->getColor($M2->$hprop) . ';';
               $bgcolor = 'background-color:#' . $H->getBgColor($M2->$hprop) . ';';
            }
         }
         else 
         {
            $color = 'color:#' . $H->getColor($M2->$hprop) . ';';
            $bgcolor = 'background-color:#' . $H->getBgColor($M2->$hprop) . ';';
         }
      }
      else if ($M2->$wprop==6 OR $M2->$wprop==7) 
      {
         //
         // This is a Saturday or Sunday. Get the coloring info.
         //
         $color = 'color:#' . $H->getColor($M2->$wprop-4) . ';';
         $bgcolor = 'background-color:#' . $H->getBgColor($M2->$wprop-4) . ';';
      }
      
      //
      // Get today style
      //
      $loopDate = date('Y-m-d', mktime(0, 0, 0, $viewData['month2'], $i, $viewData['year2']));
      if ( $loopDate == $currDate )
      {
         $border = 'border-left: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';border-right: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';';
      }
      
      //
      // Build styles
      //
      if ( strlen($color) OR strlen($bgcolor) OR strlen($border) )
      {
         $viewData['dayStyles2'][$i] = $color . $bgcolor . $border;
      }
   }
}

//
// Get the number of business days
//
$cntfrom = $viewData['year'].$viewData['month'].'01';
$cntto = $viewData['year'].$viewData['month'].$viewData['dateInfo']['daysInMonth'];
$viewData['businessDays'] = countBusinessDays($cntfrom, $cntto, $viewData['regionid']);

//
// Do the above for the following month if option set
//
if ($viewData['showTwoMonths'])
{
   $cntfrom2 = $viewData['year2'].$viewData['month2'].'01';
   $cntto2 = $viewData['year2'].$viewData['month2'].$viewData['dateInfo2']['daysInMonth'];
   $viewData['businessDays2'] = countBusinessDays($cntfrom2, $cntto2, $viewData['regionid']);
}

$todayDate = getdate(time());
$viewData['yearToday'] = $todayDate['year'];
$viewData['monthToday'] = sprintf("%02d", $todayDate['mon']);
$viewData['regions'] = $R->getAll();
$viewData['showWeekNumbers'] = $C->read('showWeekNumbers');
$mobilecols['full'] = $viewData['dateInfo']['daysInMonth'];
$viewData['supportMobile'] = $C->read('supportMobile');
$viewData['firstDayOfWeek'] = $C->read("firstDayOfWeek");

if (!$viewData['width']=$UO->read($UL->username, 'width')) 
{
   $UO->save($UL->username, 'width', 'full');
   $viewData['width'] = 'full';
}

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
