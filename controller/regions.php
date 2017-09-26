<?php
/**
 * regions.php
 * 
 * Regions page controller
 *
 * @category TeamCal Neo 
 * @version 1.6.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo '<script type="text/javascript">alert("Debug: ");</script>';

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
$MM = new Months();
$RS = new Regions(); // Source region for merge
$RT = new Regions(); // Target region for merge

//=============================================================================
//
// VARIABLE DEFAULTS
//
$viewData['txt_name'] = '';
$viewData['txt_description'] = '';

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST))
{
   // ,--------,
   // | Create |
   // '--------'
   if ( isset($_POST['btn_regionCreate']) )
   {
      //
      // Sanitize input
      //
      $_POST = sanitize($_POST);
       
      //
      // Form validation
      //
      $inputAlert = array();
      $inputError = false;
      if (!formInputValid('txt_name', 'required|alpha_numeric_dash')) $inputError = true;
      if (!formInputValid('txt_description', 'alpha_numeric_dash_blank')) $inputError = true;
   
      $viewData['txt_name'] = $_POST['txt_name'];
      if ( isset($_POST['txt_description']) ) $viewData['txt_description'] = $_POST['txt_description'];
      
      if (!$inputError)
      {
         $R->name = $viewData['txt_name'];
         $R->description = $viewData['txt_description'];
         $R->create();
   
         //
         // Log this event
         //
         $LOG->log("logRegion",$L->checkLogin(),"log_region_created", $R->name." ".$R->description);
                
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['btn_create_region'];
         $alertData['text'] = $LANG['regions_alert_region_created'];
         $alertData['help'] = '';
      }
      else
      {
         //
         // Fail
         //
         $showAlert = TRUE;
         $alertData['type'] = 'danger';
         $alertData['title'] = $LANG['alert_danger_title'];
         $alertData['subject'] = $LANG['btn_create_region'];
         $alertData['text'] = $LANG['regions_alert_region_created_fail'];
         $alertData['help'] = '';
      }
   }
   // ,--------,
   // | Delete |
   // '--------'
   elseif ( isset($_POST['btn_regionDelete']) )
   {
      $R->delete($_POST['hidden_id']);
      $M->deleteRegion($_POST['hidden_id']);
      $UO->deleteOptionByValue('calfilterRegion', $_POST['hidden_id']);
      
      //
      // Log this event
      //
      $LOG->log("logRegion",$L->checkLogin(),"log_region_deleted", $_POST['hidden_name']);
       
      //
      // Success
      //
      $showAlert = TRUE;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_delete_region'];
      $alertData['text'] = $LANG['regions_alert_region_deleted'];
      $alertData['help'] = '';
   }
   // ,-------------,
   // | iCal Import |
   // '-------------'
   elseif ( isset($_POST['btn_uploadIcal']) )
   {
      if ( trim($_FILES['file_ical']['tmp_name'])=='' )
      {
         //
         // No filename was submitted
         //
         $showAlert = TRUE;
         $alertData['type'] = 'danger';
         $alertData['title'] = $LANG['alert_danger_title'];
         $alertData['subject'] = $LANG['alert_input'];
         $alertData['text'] = $LANG['regions_alert_no_file'].print_r($_FILES['file_ical']);
         $alertData['help'] = '';
      }
      else
      {
         $viewData['icalRegionID'] = $_POST['sel_ical_region'];
         $viewData['icalRegionName'] = $R->getNameById($viewData['icalRegionID']);
         
         //
         // Parse the iCal file events
         //
         $iCalEvents = array();
         preg_match_all("#(?sU)BEGIN:VEVENT.*END:VEVENT#", file_get_contents($_FILES['file_ical']['tmp_name']), $events);
         
         //
         // Now go through all events
         //
         foreach($events[0] as $event)
         {
            //
            // Read the event start and end string
            //
            preg_match("#(?sU)DTSTART;.*DATE:([0-9]{8})#", $event, $start);
            preg_match("#(?sU)DTEND;.*DATE:([0-9]{8})#", $event, $end);

            //
            // Create time stamps and substract 24h from the end date cause the 
            // end date of an iCal event is not included
            //
            $start = mktime (0,0,0, substr($start[1],4,2), substr($start[1],6,2), substr($start[1],0,4));
            $end = mktime (0,0,0, substr($end[1],4,2), substr($end[1],6,2), substr($end[1],0,4));
            $end = $end - 86400;
            
            //
            // Loop through all events and save the date string into an array
            //
            for($i=$start; $i<=$end; $i+=86400)
            {
               $eventDate = date("Ymd", $i);
               $iCalEvents[] = $eventDate;
            }
         };

         //
         // Loop through the date string array and save each one in the region template 
         //
         foreach($iCalEvents as $i)
         {
            $eventYear = substr($i, 0, 4);
            $eventMonth = substr($i, 4, 2);
            $eventDay = intval(substr($i, 6, 2));
            
            if ( !$MM->getMonth($eventYear, $eventMonth, $viewData['icalRegionID']) )
            {
               //
               // Seems there is no template for this month yet.
               // If we have one in cache, write it first.
               //
               if ( $M->year ) $M->update($M->year, $M->month, $viewData['icalRegionID']);
               //
               // Create the new blank template
               //
               createMonth($eventYear, $eventMonth, 'region', $viewData['icalRegionID']);
            }
            else
            {
               //
               // There is a template for this month in memory. Save it first.
               //
               $M->update($M->year, $M->month, $viewData['icalRegionID']);
            }
            
            if ($M->getHoliday($eventYear, $eventMonth, $eventDay, $viewData['icalRegionID'])==0)
            {
               //
               // No Holiday set yet for this day. Good to overwrite.
               //
               $M->setHoliday($eventYear, $eventMonth, $eventDay, $viewData['icalRegionID'], $_POST['sel_ical_holiday']);
            }
            else
            {
               //
               // This is an existing holiday. Check the overwrite flag.
               //
               if (isset($_POST['chk_ical_overwrite']))
               {
                  $M->setHoliday($eventYear, $eventMonth, $eventDay, $viewData['icalRegionID'], $_POST['sel_ical_holiday']);
               }
            }
         }
         
         //
         // Log this event
         //
         $LOG->log("logRegion",$L->checkLogin(),"log_region_ical", $_FILES['file_ical']['name'] . ' => ' . $viewData['icalRegionName']);
         
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['regions_tab_ical'];
         $alertData['text'] = sprintf($LANG['regions_ical_imported'], $_FILES['file_ical']['name'], $viewData['icalRegionName']);
         $alertData['help'] = '';
      }
   }
   // ,----------,
   // | Transfer |
   // '----------'
   elseif ( isset($_POST['btn_regionTransfer']) )
   {
      if ($_POST['sel_region_a'] == $_POST['sel_region_b']) 
      {
         //
         // Same source and target region
         //
         $showAlert = TRUE;
         $alertData['type'] = 'danger';
         $alertData['title'] = $LANG['alert_danger_title'];
         $alertData['subject'] = $LANG['alert_input'];
         $alertData['text'] = $LANG['regions_alert_transfer_same'];
         $alertData['help'] = '';
      }
      else 
      {
         //
         // Loop through every month of the source region
         //
         $sregion = $_POST['sel_region_a'];
         $tregion = $_POST['sel_region_b'];
         $stemplates = $M->getRegion($sregion);
         
         foreach ($stemplates as $stpl)
         {
            if ( !$M->getMonth($stpl['year'], $stpl['month'], $tregion) )
            {
               //
               // No target template found for this year/month/region
               // Create an empty template first.
               //
               createMonth($stpl['year'], $stpl['month'], 'region', $tregion);
            }
            
            $M->getMonth($stpl['year'], $stpl['month'], $tregion);
            for ($i = 1; $i<=31; $i++)
            {
               $prop = 'hol' . $i;
               if ($stpl[$prop] > 3)
               {
                  //
                  // Source holds a custom holiday here (1 = Business day, 2 = Saturday, 3 = Sunday)
                  //
                  if ( $M->$prop <= 3 )
                  {
                     //
                     // Target holds no custom holiday here. Save to overwrite.
                     //
                     $M->$prop = $stpl[$prop];
                  }
                  else
                  {
                     //
                     // Target holds a custom holiday here. Check overwrite flag.
                     //
                     if ( isset($_POST['chk_overwrite']) ) $M->$prop = $stpl[$prop];
                  }
               }
            }
            
            //
            // And save the template
            //
            $M->update($stpl['year'], $stpl['month'], $tregion);
         }
         
         //
         // Log this event
         //
         $LOG->log("logRegion",$L->checkLogin(),"log_region_transferred", $R->getNameById($sregion)." => ".$R->getNameById($tregion));
         
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['regions_tab_transfer'];
         $alertData['text'] = sprintf($LANG['regions_transferred'], $R->getNameById($sregion), $R->getNameById($tregion));
         $alertData['help'] = '';
      }
   }
}
   
//=============================================================================
//
// PREPARE VIEW
//
$viewData['regions'] = $R->getAll();
foreach ($viewData['regions'] as $region)
{
   $viewData['regionList'][] = array ('val' => $region['id'], 'name' => $region['name'], 'selected' => false );
}

$holidays = $H->getAll();
foreach ($holidays as $holiday)
{
   $viewData['holidayList'][] = array ('val' => $holiday['id'], 'name' => $holiday['name'], 'selected' => false );
}
$viewData['ical'] = array (
   array ( 'prefix' => 'regions', 'name' => 'ical_region', 'type' => 'list', 'values' => $viewData['regionList'] ),
   array ( 'prefix' => 'regions', 'name' => 'ical_holiday', 'type' => 'list', 'values' => $viewData['holidayList'] ),
   array ( 'prefix' => 'regions', 'name' => 'ical_overwrite', 'type' => 'check', 'value' => false ),
);

$viewData['merge'] = array (
   array ( 'prefix' => 'regions', 'name' => 'region_a', 'type' => 'list', 'values' => $viewData['regionList'] ),
   array ( 'prefix' => 'regions', 'name' => 'region_b', 'type' => 'list', 'values' => $viewData['regionList'] ),
   array ( 'prefix' => 'regions', 'name' => 'region_overwrite', 'type' => 'check', 'value' => false ),
);

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
