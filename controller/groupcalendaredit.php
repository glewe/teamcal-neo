<?php
/**
 * groupcalendaredit.php
 * 
 * Group calendar edit page controller
 *
 * @category TeamCal Neo 
 * @version 2.2.3
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
if (isset($_GET['month']) AND isset($_GET['region']) AND isset($_GET['group']))
{
   $missingData = FALSE;
   
   //
   // Check month
   //
   $yyyymm = sanitize($_GET['month']);
   $viewData['year'] = substr($yyyymm, 0, 4);
   $viewData['month'] = substr($yyyymm, 4, 2);
   if (!is_numeric($yyyymm) OR strlen($yyyymm) != 6 OR !checkdate(intval($viewData['month']),1,intval($viewData['year'])) ) 
   {
      $missingData = TRUE;
   }

   //
   // Check region
   //
   $region = sanitize($_GET['region']);
   if (!$R->getById($region)) 
   {
      $missingData = TRUE; 
   }
   else
   {
      if ($R->getAccess($R->id, $UL->getRole($UL->username)) == 'view')
      {
         //
         // The current user (role) can only view the region specified in the URL.
         // So we replace it with the default region.
         //
         $R->getById('1'); 
      }
      $viewData['regionid'] = $R->id;
      $viewData['regionname'] = $R->name;
   }
    
   //
   // Check group
   // We will use the same template table as for the users. We just create a username like "group:<groupID>".
   //
   $calgroup = sanitize($_GET['group']);
   $calgroupuser = 'group:' . $calgroup;
   if (!$G->getById($calgroup)) 
   {
      $missingData = TRUE;
   }
   else
   {
      $viewData['groupid'] = $G->id;
      $viewData['groupname'] = $G->name;
      $viewData['groupusername'] = $calgroupuser;
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
if ($C->read('currentYearOnly') AND $viewData['year']!=date('Y'))
{
   header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . date('Ym') . "&region=" . $region . "&group=" . $calgroup);
   die();
}

//=============================================================================
//
// CHECK PERMISSION
//
$allowed = false;
if (isAllowed($CONF['controllers'][$controller]->permission))
{
   if ( $UG->shareGroups($UL->username, $calgroup) )
   {
      if (isAllowed("calendareditgroup")) $allowed = true;
   }
   else
   {
      if (isAllowed("calendareditall")) $allowed = true;
   }
}

if (!$allowed)
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
$groups = $G->getAll();
$users = $U->getAll();
$inputAlert = array();
$currDate = date('Y-m-d');
$viewData['dateInfo'] = dateInfo($viewData['year'], $viewData['month']);

//
// See if a region template exists. If not, create one.
//
if (!$M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid'])) 
{
   createMonth($viewData['year'], $viewData['month'], 'region', $viewData['regionid']);
   $M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid']);
    
   //
   // Log this event
   //
   $LOG->log("logMonth", L_USER, "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
}

//
// See if a user template for this group exists. If not, create one.
//
if (!$T->getTemplate($calgroupuser, $viewData['year'], $viewData['month']))
{
   createMonth($viewData['year'], $viewData['month'], 'user', $calgroupuser);
   $T->getTemplate($calgroupuser, $viewData['year'], $viewData['month']);
   
   //
   // Log this event
   //
   $LOG->log("logMonth", L_USER, "log_month_tpl_created", "Group: " . $G->name . ": " . $M->year . "-" . $M->month);
}

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST))
{
   if (isset($_POST['btn_save']) OR isset($_POST['btn_clearall']) OR isset($_POST['btn_saveperiod']) OR isset($_POST['btn_saverecurring']))
   {
      //
      // All changes to the calendar are handled in this block.
      // Note: It does NOT finish with the Approval routine. All groups absences will overwrite every affected user's absences.
      // First, get the current absences of the group into an array $currentAbsences
      // Second, set initialize the $requestedAbsences array to the current ones. Updates are done below.
      //
      for ($i=1; $i<=$viewData['dateInfo']['daysInMonth']; $i++)
      {
         $currentAbsences[$i] = $T->getAbsence($calgroupuser, $viewData['year'], $viewData['month'], $i);
         $requestedAbsences[$i] = $currentAbsences[$i];
         $approvedAbsences[$i] = '0';
         $declinedAbsences[$i] = '0';
      }
      
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
            $key = 'opt_abs_'.$i;
            if (isset($_POST[$key]))
            {
               $requestedAbsences[$i] = $_POST[$key];
            }
            else
            {
               $requestedAbsences[$i] = '0';
            }
         }
      }
      // ,-----------,
      // | Clear All |
      // '-----------'
      else if (isset($_POST['btn_clearall']))
      {
         //
         // Loop thru the radio boxes
         //
         for ($i=1; $i<=$viewData['dateInfo']['daysInMonth']; $i++)
         {
            $requestedAbsences[$i] = '0';
         }
      }
      // ,-------------,
      // | Save Period |
      // '-------------'
      elseif (isset($_POST['btn_saveperiod']))
      {
         //
         // Form validation
         //
         $inputError = false;
         if (!formInputValid('txt_periodStart', 'required|date')) $inputError = true;
         if (!formInputValid('txt_periodEnd', 'required|date')) $inputError = true;
      
         if (!$inputError)
         {
            $startPieces = explode("-",$_POST['txt_periodStart']);
            $startYear = $startPieces[0];
            $startMonth = $startPieces[1];
             
            $endPieces = explode("-",$_POST['txt_periodEnd']);
            $endYear = $endPieces[0];
            $endMonth = $endPieces[1];
            
            if ($startYear==$viewData['year'] AND $endYear==$viewData['year'] AND $startMonth==$viewData['month'] AND $endMonth==$viewData['month'])
            {
               $startDate = str_replace("-", "", $_POST['txt_periodStart']);
               $endDate = str_replace("-", "", $_POST['txt_periodEnd']);
             
               for ($i=$startDate; $i<=$endDate; $i++)
               {
                  $year = substr($i, 0, 4);
                  $month = substr($i, 4, 2);
                  $day = intval(substr($i, 6, 2));
                  $requestedAbsences[$day] = $_POST['sel_periodAbsence'];
               }
            }
            else 
            {
               //
               // Input out of range
               //
               $showAlert = TRUE;
               $alertData['type'] = 'danger';
               $alertData['title'] = $LANG['alert_danger_title'];
               $alertData['subject'] = $LANG['alert_input'];
               $alertData['text'] = $LANG['caledit_alert_out_of_range'];
               $alertData['help'] = '';
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
            $alertData['text'] = $LANG['caledit_alert_save_failed'];
            $alertData['help'] = '';
         }
      }
      // ,----------------,
      // | Save Recurring |
      // '----------------'
      elseif (isset($_POST['btn_saverecurring']))
      {
         $startDate = $viewData['year'].$viewData['month'].'01';
         $endDate =  $viewData['year'].$viewData['month'].$viewData['dateInfo']['daysInMonth'];
         $wdays = array('monday'=>1, 'tuesday'=>2, 'wednesday'=>3, 'thursday'=>4, 'friday'=>5, 'saturday'=>6, 'sunday'=>7);
         
         foreach($_POST as $key=>$value)
         {
            foreach ($wdays as $wday=>$wdaynr)
            {
               if ( $key==$wday )
               {
                  //
                  // The checkbox for this weekday was set. Loop through the month and mark all of them.
                  // 
                  for ($i=$startDate; $i<=$endDate; $i++)
                  {
                     $day = intval(substr($i, 6, 2));
                     $loopDayInfo = dateInfo($viewData['year'],$viewData['month'],$day);
                     if ( $loopDayInfo['wday']==$wdaynr )
                     {
                        $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
                     }
                  }
               }
               elseif ( $key=="workdays" )
               {
                  //
                  // The checkbox for workdays was set. Loop through the month and mark all workdays.
                  //
                  for ($i=$startDate; $i<=$endDate; $i++)
                  {
                     $day = intval(substr($i, 6, 2));
                     $loopDayInfo = dateInfo($viewData['year'],$viewData['month'],$day);
                     if ( $loopDayInfo['wday']>=1 AND $loopDayInfo['wday']<=5 )
                     {
                        $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
                     }
                  }
               }
               elseif ( $key=="weekends" )
               {
                  //
                  // The checkbox for weekends was set. Loop through the month and mark all weekend days.
                  //
                  for ($i=$startDate; $i<=$endDate; $i++)
                  {
                    $day = intval(substr($i, 6, 2));
                     $loopDayInfo = dateInfo($viewData['year'],$viewData['month'],$day);
                     if ( $loopDayInfo['wday']>=6 AND $loopDayInfo['wday']<=7 )
                     {
                        $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
                     }
                  }
               }
            }
         }
         
      }
      
      if (!$showAlert)
      {
         //
         // At this point we have two arrays:
         // - $currentAbsences (Absences before this request)
         // - $requestedAbsences (Absences requested)
         //
         // There is no approval check for group absences. So we just take all requested
         // absences and write them to the calgroupuser template and to every group member's template.
         //
         foreach ($requestedAbsences as $key => $val)
         {
            $col = 'abs'.$key;
            $T->$col = $val;
         }
         $T->update($calgroupuser, $viewData['year'], $viewData['month']);

         //
         // Now loop through every user of the selected group
         //
         $groupmembers = $UG->getAllForGroup($calgroup);
         foreach ($groupmembers as $member)
         {
            //
            // Get the current template for this user
            //
            if ($T->getTemplate($member['username'], $viewData['year'], $viewData['month']))
            {
               //
               // Loop through all requested absences for the group
               //
               foreach ($requestedAbsences as $key => $val)
               {
                  $col = 'abs'.$key;
                  if ($T->$col)
                  {
                     //
                     // User has an absence already. Only overwrite if keepExisting was not checked.
                     //
                     if (!isset($_POST['chk_keepExisting'])) $T->$col = $val;
                  }
                  else
                  {
                     //
                     // User has no absence yet. Set the new group absence.
                     //
                     $T->$col = $val;
                  }
               }
               $T->update($member['username'], $viewData['year'], $viewData['month']);
            }
         }

         $sendNotification = true;
         $alerttype = 'success';
         $alertHelp = '';
         
         //
         // Send notification e-mails to the subscribers of user events
         //
         if ($C->read("emailNotifications") AND $sendNotification)
         {
            sendUserCalEventNotifications("changed", $viewData['year'], $viewData['month'], $calgroupuser);
         }
             
         //
         // Log this event
         //
         $LOG->log("logUser", $UL->username, "log_cal_grp_tpl_chg", $G->name . ": " . $viewData['year'].$viewData['month']);
         
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = $alerttype;
         $alertData['title'] = $LANG['alert_'.$alerttype.'_title'];
         $alertData['subject'] = $LANG['caledit_alert_update'];
         $alertData['text'] = $LANG['caledit_alert_update_group'];
         $alertData['help'] = $alertHelp;
      }
   }
   // ,---------------,
   // | Select Region |
   // '---------------'
   elseif (isset($_POST['btn_region']))
   {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $_POST['sel_region'] . "&group=" . $calgroup);
      die();
   }
   // ,---------------------,
   // | Select Screen Width |
   // '---------------------'
   elseif (isset($_POST['btn_width']))
   {
      $UO->save($UL->username, 'width', $_POST['sel_width']);
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $region . "&group=" . $calgroup);
      die();
   }
   // ,--------------,
   // | Select Group |
   // '--------------'
   elseif (isset($_POST['btn_group']))
   {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $region . "&group=" . $_POST['sel_group']);
      die();
   }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['absences'] = $A->getAll();
$viewData['holidays'] = $H->getAllCustom();
$viewData['dayStyles'] = array();

//
// Only prepare those regions the current user (role) can edit
//
$allRegions = $R->getAll();
foreach ($allRegions as $reg)
{
   if (!$R->getAccess($reg['id'], $UL->getRole($UL->username)) OR $R->getAccess($reg['id'], $UL->getRole($UL->username)) == 'edit' )
   {
      $viewData['regions'][] = $reg;
   }
}

//
// Only prepare those groups the current user (role) can edit
//
$viewData['groups'] = array();
foreach ($groups as $group)
{
   $allowed = false;
   if ( $UG->shareGroups($UL->username, $group['id']) )
   {
      if (isAllowed("calendareditgroup")) $allowed = true;
   }
   else
   {
      if (isAllowed("calendareditall")) $allowed = true;
   }
   
   if ($allowed)
   {
      $viewData['groups'][] = array ('id' => $group['id'], 'name' => $group['name']);
   }
}

//
// Get the holiday and weekend colors
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
      $viewData['dayStyles'][$i] = ' style="' . $color . $bgcolor . $border . '"';
   }
}

$todayDate = getdate(time());
$viewData['yearToday'] = $todayDate['year'];
$viewData['monthToday'] = sprintf("%02d", $todayDate['mon']);
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
