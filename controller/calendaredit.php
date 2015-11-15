<?php
/**
 * calendaredit.php
 * 
 * Edit calendar page controller
 *
 * @category TeamCal Neo 
 * @version 0.3.00
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
if (isset($_GET['month']) AND isset($_GET['region']) AND isset($_GET['user']))
{
   $missingData = FALSE;
   
   $yyyymm = sanitize($_GET['month']);
   $calData['year'] = substr($yyyymm, 0, 4);
   $calData['month'] = substr($yyyymm, 4, 2);
   if (!is_numeric($yyyymm) OR strlen($yyyymm) != 6 OR !checkdate(intval($calData['month']),1,intval($calData['year'])) ) 
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
    
   $caluser = sanitize($_GET['user']);
   if (!$U->findByName($caluser)) 
   {
      $missingData = TRUE;
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
$allowed = false;
if (isAllowed("calendaredit"))
{
   if ( $UL->username == $caluser )
   {
      if (isAllowed("calendareditown")) $allowed = true;
   }
   else if ( $UG->shareGroups($UL->username, $caluser) )
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

/**
 * ========================================================================
 * Load controller stuff
 */

/**
 * ========================================================================
 * Initialize variables
 */
$users = $U->getAllButAdmin();
$inputAlert = array();
$currDate = date('Y-m-d');
$calData['dateInfo'] = dateInfo($calData['year'], $calData['month']);

/**
 * See if a region template exists. If not, create one.
 */
if (!$M->getMonth($calData['year'], $calData['month'], $calData['regionid'])) 
{
   createMonth($calData['year'], $calData['month'], 'region', $calData['regionid']);
   $M->getMonth($calData['year'], $calData['month'], $calData['regionid']);
    
   /**
    * Log this event
    */
   $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
}

/**
 * See if a user template exists. If not, create one.
 */
if (!$T->getTemplate($caluser, $calData['year'], $calData['month']))
{
   createMonth($calData['year'], $calData['month'], 'user', $caluser);
   $T->getTemplate($caluser, $calData['year'], $calData['month']);
   
   /**
    * Log this event
    */
   $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_created", $caluser . ": " . $M->year . "-" . $M->month);
}

/**
 * ========================================================================
 * Process form
 */
if (!empty($_POST))
{
   if (isset($_POST['btn_save']) OR isset($_POST['btn_clearall']) OR isset($_POST['btn_saveperiod']) OR isset($_POST['btn_saverecurring']))
   {
      /**
       * All changes to the calendar are handled in this block since it finishes with the Approval routine.
       * First, get the current absences of the user into an array $currentAbsences
       * Second, set initialize the $requestedAbsences array to the current ones. Updates are done below.
       */
      for ($i=1; $i<=$calData['dateInfo']['daysInMonth']; $i++)
      {
         $currentAbsences[$i] = $T->getAbsence($caluser, $calData['year'], $calData['month'], $i);
         $requestedAbsences[$i] = $currentAbsences[$i];
         $approvedAbsences[$i] = '0';
         $declinedAbsences[$i] = '0';
      }
      
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
         for ($i=1; $i<=$calData['dateInfo']['daysInMonth']; $i++)
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
      /**
       * ,-----------,
       * | Clear all |
       * '-----------'
       */
      else if (isset($_POST['btn_clearall']))
      {
         /**
          * Loop thru the radio boxes
          */
         for ($i=1; $i<=$calData['dateInfo']['daysInMonth']; $i++)
         {
            $requestedAbsences[$i] = '0';
         }
      }
      /**
       * ,---------------,
       * | Save Period   |
       * '---------------'
       */
      elseif (isset($_POST['btn_saveperiod']))
      {
         /**
          * Form validation
          */
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
            
            if ($startYear==$calData['year'] AND $endYear==$calData['year'] AND $startMonth==$calData['month'] AND $endMonth==$calData['month'])
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
               /**
                * Input out of range
                */
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
            /**
             * Input validation failed
             */
            $showAlert = TRUE;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = $LANG['alert_input'];
            $alertData['text'] = $LANG['caledit_alert_save_failed'];
            $alertData['help'] = '';
         }
      }
      /**
       * ,----------------,
       * | Save Recurring |
       * '----------------'
       */
      elseif (isset($_POST['btn_saverecurring']))
      {
         $startDate = $calData['year'].$calData['month'].'01';
         $endDate =  $calData['year'].$calData['month'].$calData['dateInfo']['daysInMonth'];
         $wdays = array('monday'=>1, 'tuesday'=>2, 'wednesday'=>3, 'thursday'=>4, 'friday'=>5, 'saturday'=>6, 'sunday'=>7);
         
         foreach($_POST as $key=>$value)
         {
            foreach ($wdays as $wday=>$wdaynr)
            {
               if ( $key==$wday )
               {
                  /*
                   * The checkbox for this weekday was set. Loop through the month and mark all of them.
                   */ 
                  for ($i=$startDate; $i<=$endDate; $i++)
                  {
                     $day = intval(substr($i, 6, 2));
                     $loopDayInfo = dateInfo($calData['year'],$calData['month'],$day);
                     if ( $loopDayInfo['wday']==$wdaynr )
                     {
                        $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
                     }
                  }
               }
               elseif ( $key=="workdays" )
               {
                  /*
                   * The checkbox for workdays was set. Loop through the month and mark all workdays.
                   */
                  for ($i=$startDate; $i<=$endDate; $i++)
                  {
                     $day = intval(substr($i, 6, 2));
                     $loopDayInfo = dateInfo($calData['year'],$calData['month'],$day);
                     if ( $loopDayInfo['wday']>=1 AND $loopDayInfo['wday']<=5 )
                     {
                        $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
                     }
                  }
               }
               elseif ( $key=="weekends" )
               {
                  /*
                   * The checkbox for weekends was set. Loop through the month and mark all weekend days.
                   */
                  for ($i=$startDate; $i<=$endDate; $i++)
                  {
                    $day = intval(substr($i, 6, 2));
                     $loopDayInfo = dateInfo($calData['year'],$calData['month'],$day);
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
         /*
          * At this point we have four arrays:
          * - $currentAbsences (Absences before this request)
          * - $requestedAbsences (Absences requested)
          * - $approved['approvedAbsences'] (Absences approved, coming back from the approval function)
          * - $approved['declinedReasons'] (Declined Reasons, coming back from the approval function)
          */
         $approved = approveAbsences($caluser, $calData['year'], $calData['month'], $currentAbsences, $requestedAbsences);
         
         $sendNotification = false;
         $alerttype = 'success';
         $alertHelp = '';
         switch ($approved['approvalResult'])
         {
            case 'all':
               foreach ($requestedAbsences as $key => $val)
               {
                  $col = 'abs'.$key;
                  $T->$col = $val;
               }
               $T->update($caluser, $calData['year'], $calData['month']);
               $sendNotification = true;
               break;
               
            case 'partial':
               foreach ($approved['approvedAbsences'] as $key => $val)
               {
                  $col = 'abs'.$key;
                  $T->$col = $val;
               }
               $T->update($caluser, $calData['year'], $calData['month']);
               $sendNotification = true;
               $alerttype = 'info';
               foreach ($approved['declinedReasons'] as $reason)
               {
                  if (strlen($reason)) $alertHelp .= $reason . "<br>";
               }
               break;
               
            case 'none':
               $alerttype = 'info';
               break;
         }
         
         /**
          * Send notification e-mails to the subscribers of user events
          */
         if ($C->read("emailNotifications") AND $sendNotification)
         {
            sendUserCalEventNotifications("changed", $calData['year'], $calData['month'], $caluser);
         }
             
         /**
          * Log this event
          */
         $LOG->log("logUser", $UL->username, "log_cal_usr_tpl_chg", $caluser . " " . $calData['year'].$calData['month']);
         
         /**
          * Success
          */
         $showAlert = TRUE;
         $alertData['type'] = $alerttype;
         $alertData['title'] = $LANG['alert_'.$alerttype.'_title'];
         $alertData['subject'] = $LANG['caledit_alert_update'];
         $alertData['text'] = $LANG['caledit_alert_update_' . $approved['approvalResult']];
         $alertData['help'] = $alertHelp;
      }
   }
   /**
    * ,---------------,
    * | Select region |
    * '---------------'
    */
   elseif (isset($_POST['btn_region']))
   {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $calData['year'] . $calData['month'] . "&region=" . $_POST['sel_region'] . "&user=" . $caluser);
      die();
   }
   /**
    * ,-------------,
    * | Select User |
    * '-------------'
    */
   elseif (isset($_POST['btn_user']))
   {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&month=" . $calData['year'] . $calData['month'] . "&region=" . $_POST['sel_region'] . "&user=" . $_POST['sel_user']);
      die();
   }
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$calData['username'] = $caluser;
$calData['fullname'] = $U->getFullname($caluser);
$calData['absences'] = $A->getAll();
$calData['holidays'] = $H->getAllCustom();
$calData['regions'] = $R->getAll();
$calData['dayStyles'] = array();

$calData['users'] = array();
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
      $calData['users'][] = array ('username' => $usr['username'], 'lastfirst' => $U->getLastFirst($usr['username']));
   }
}

/**
 * Get the holiday and weekend colors
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
      $calData['dayStyles'][$i] = ' style="' . $color . $bgcolor . $border . '"';
   }
}

$todayDate = getdate(time());
$calData['yearToday'] = $todayDate['year'];
$calData['monthToday'] = sprintf("%02d", $todayDate['mon']);
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
