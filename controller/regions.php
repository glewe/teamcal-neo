<?php
/**
 * regions.php
 * 
 * Regions page controller
 *
 * @category TeamCal Neo 
 * @version 0.3.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

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
$MM = new Months();
$RS = new Regions(); // Source region for merge
$RT = new Regions(); // Target region for merge

/**
 * ========================================================================
 * Initialize variables
 */
$regionsData['txt_name'] = '';
$regionsData['txt_description'] = '';

/**
 * ========================================================================
 * Process form
 */

/**
 * ,--------,
 * | Create |
 * '--------'
 */
if ( isset($_POST['btn_regionCreate']) )
{
   /**
    * Sanitize input
    */
   $_POST = sanitize($_POST);
    
   /**
    * Form validation
    */
   $inputAlert = array();
   $inputError = false;
   if (!formInputValid('txt_name', 'required|alpha_numeric_dash')) $inputError = true;
   if (!formInputValid('txt_description', 'alpha_numeric_dash_blank')) $inputError = true;

   $regionsData['txt_name'] = $_POST['txt_name'];
   if ( isset($_POST['txt_description']) ) $regionsData['txt_description'] = $_POST['txt_description'];
   
   if (!$inputError)
   {
      $R->name = $regionsData['txt_name'];
      $R->description = $regionsData['txt_description'];
      $R->create();

      /**
       * Log this event
       */
      $LOG->log("logRegion",$L->checkLogin(),"log_region_created", $R->name." ".$R->description);
             
      /**
       * Success
       */
      $showAlert = TRUE;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_create_region'];
      $alertData['text'] = $LANG['regions_alert_region_created'];
      $alertData['help'] = '';
   }
   else
   {
      /**
       * Fail
       */
      $showAlert = TRUE;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['btn_create_region'];
      $alertData['text'] = $LANG['regions_alert_region_created_fail'];
      $alertData['help'] = '';
   }
}
/**
 * ,--------,
 * | Delete |
 * '--------'
 */
elseif ( isset($_POST['btn_regionDelete']) )
{
   $R->delete($_POST['hidden_id']);
   $M->deleteRegion($_POST['hidden_id']);
   
   /**
    * Log this event
    */
   $LOG->log("logRegion",$L->checkLogin(),"log_region_deleted", $_POST['hidden_name']);
    
   /**
    * Success
    */
   $showAlert = TRUE;
   $alertData['type'] = 'success';
   $alertData['title'] = $LANG['alert_success_title'];
   $alertData['subject'] = $LANG['btn_delete_region'];
   $alertData['text'] = $LANG['regions_alert_region_deleted'];
   $alertData['help'] = '';
}
/**
 * ,-------------,
 * | iCal Import |
 * '-------------'
 */
elseif ( isset($_POST['btn_uploadIcal']) )
{
   if ( trim($_FILES['file_ical']['tmp_name'])=='' )
   {
      /**
       * No filename was submitted
       */
      $showAlert = TRUE;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['alert_input'];
      $alertData['text'] = $LANG['regions_alert_no_file'].print_r($_FILES['file_ical']);
      $alertData['help'] = '';
   }
   else
   {
      $regionsData['icalRegionID'] = $_POST['sel_ical_regionID'];
      $regionsData['icalRegionName'] = $_POST['sel_ical_regionName'];
      
      /**
       * Now parse the iCal file (original code by Franz)
       */
      $begin_of_ical = 999999999999999999999999999999;
      $end_of_ical = 0;
      $iCalEvents = array();
      preg_match_all("#(?sU)BEGIN:VEVENT.*END:VEVENT#", file_get_contents($_FILES['file_ical']['tmp_name']), $events);
      
      foreach($events[0] as $event)
      {
         preg_match("#(?sU)DTSTART;.*DATE:([0-9]{8})#", $event, $start);
         preg_match("#(?sU)DTEND;.*DATE:([0-9]{8})#", $event, $end);
         $start = mktime (0,0,0, substr($start[1],4,2), substr($start[1],6,2), substr($start[1],0,4));
         $end = mktime (0,0,0, substr($end[1],4,2), substr($end[1],6,2), substr($end[1],0,4));
         $end = $end - 86400; // Need to subtract 24h to limit entry to a single day (submitted by Stefan Mayr)
          
         /**
          * Catch the earliest and latest event date of the iCal file
          */
         if ($begin_of_ical > $start) $begin_of_ical = $start;
         if ($end_of_ical < $end) $end_of_ical = $end;
      
         /**
          * Save this event to the array
          */
         $iCalEvents[$start] = $end;
      };
      
      
      /**
       * Ok, now we have all events in our array.
       * Let's loop through all events an do this for each:
       * - create a region month template for the event start and event end if not exists
       * - add the absence symbol(s) for this event to the month template(s)
       * - save the template(s)
       */
      $current_event = $begin_of_ical;
      $M->year = 0;
      $M->month = 0;
      $M->yearmonth = 0;
      while ($current_event < $end_of_ical)
      {
         $current_year = date("Y", $current_event);
         $current_month = date("m", $current_event);
         $current_yearmonth = date("Ym", $current_event);
      
         if ($M->year.$M->month != $current_yearmonth)
         {
            /**
             * We don't have the month template we want. Two possible reasons:
             * - we haven't loaded one
             * - we stepped over a month border with our current_event
             *
             * Let's check with a second MM instance if there is a template for this month yet.
             * We need the second instance cause getMonth() would overwrite an M instance that we might still
             * have in memory and not saved yet.
             */
            if ( !$MM->getMonth($regionsData['icalRegionID'], $current_year, $current_month) )
            {
               /**
                * Seems there is no template for this month yet.
                * If we have one in cache, write it first.
                */
               if ( $M->year ) $M->update($regionsData['icalRegionID'], $M->year, $M->month);
               /**
                * Create the new blank template
                */
               createMonth($current_year, $current_month, 'region', $regionsData['icalRegionID']);
            }
            else
            {
               /**
                * There is a template for this month.
                * Let's save the current and load the new.
                */
               $M->update($regionsData['icalRegionID'], $M->year, $M->month);
               $M->getMonth($regionsData['icalRegionID'], $current_year, $current_month);
            }
         }
      
         /**
          * Put the user-selected absence type in the month template for the current iCal event
          */
         $dayno = date("j", $current_event);
      
         $start_of_iCal_period = min(array_keys($iCalEvents)); // Select start of earliest iCal period
         $end_of_iCal_period = $iCalEvents[$start_of_iCal_period]; // Select end of earliest iCal period
         if ($start_of_iCal_period <= $current_event)
         {
            if ($end_of_iCal_period >= $current_event)
            {
               /**
                * We are currently inbetween begin and end of an iCal period
                */
               if ($M->getDay($regionsData['icalRegionID'], $current_year, $current_month, $dayno) <= 3)
               {
                  /**
                   * This is a business or weekend day. Only change the holiday type in this case.
                   */
                  $prop = 'abs' . $dayno;
                  $M->$prop = $_POST['sel_ical_holiday']; 
               }
               else 
               {
                  /**
                   * This is an existing holiday. Check the overwrite flag.
                   */
                  if (isset($_POST['chk_ical_overwrite']))
                  {
                     $prop = 'abs' . $dayno;
                     $M->$prop = $_POST['sel_ical_holiday'];
                  }
               }
            }
            else
            {
               /**
                * We are done with this event period! Remove this period from the iCalEvents array.
                * That makes the next one the earliest.
                */
               unset($iCalEvents[$start_of_iCal_period]);
            }
         }
         $current_event = strtotime("+1 day", $current_event);
      }
      
      /**
       * Ok, lets save the last month
       */
      $M->update($regionsData['icalRegionID'], $M->year, $M->month);
      
      /**
       * Log this event
       */
      $LOG->log("logRegion",$L->checkLogin(),"log_region_ical", $_FILES['file_ical']['name'] . ' => ' . $regionsData['icalRegionName']);
      
      /**
       * Success
       */
      $showAlert = TRUE;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['regions_tab_ical'];
      $alertData['text'] = sprintf($LANG['regions_ical_imported'], $_FILES['file_ical']['name'], $regionsData['icalRegionName']);
      $alertData['help'] = '';
   }
}
/**
 * ,-------,
 * | Merge |
 * '-------'
 */
elseif ( isset($_POST['btn_regionTransfer']) )
{
   if ($_POST['sel_region_a'] == $_POST['sel_region_b']) 
   {
      /**
       * Same source and target region
       */
      $showAlert = TRUE;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['alert_input'];
      $alertData['text'] = $LANG['regions_alert_merge_same'];
      $alertData['help'] = '';
   }
   else 
   {
      /**
       * Loop through every month of the source region
       */
      $sregion = $_POST['sel_region_a'];
      $tregion = $_POST['sel_region_b'];
      $stemplates = $M->getRegion($sregion);
      foreach ($templates as $stpl)
      {
         if ( !$M->getMonth($tregion, $stpl['year'], $stpl['month']) )
         {
            /**
             * No target template found for this region/year/month
             * Lets just create a base template first.
             */
            createMonthTemplate('month', $tregion, $stpl['year'], $stpl['month']);
         }
         
         $M->getMonth($tregion, $stpl['year'], $stpl['month']);
         for ($i = 0; $i<=31; $i++)
         {
            $prop = 'abs' . $i;
            if ($stpl[$prop] > 3)
            {
               /**
                * Source holds a custom holiday here (1 = Business day, 2 = Saturday, 3 = Sunday)
                */
               if ( $M->$prop <= 3 )
               {
                  /**
                   * Target holds no custom holiday here. Save to overwrite.
                   */
                  $M->$prop = $stpl[$prop];
               }
               else
               {
                  /**
                   * Target holds a custom holiday here. Check overwrite flag.
                   */
                  if ( isset($_POST['chk_overwrite']) ) $M->$prop = $stpl[$prop];
               }
            }
         }
         /**
         * And save the template
         */
         $M->update($tregion, $stpl['year'], $stpl['month']);
      }
      
      /**
       * Log this event
       */
      $LOG->log("logRegion",$L->checkLogin(),"log_region_transferred", $R->getNameById($sregion)." => ".$R->getNameById($tregion));
      
      /**
       * Success
       */
      $showAlert = TRUE;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['regions_tab_merge'];
      $alertData['text'] = sprintf($LANG['regions_transferred'], $R->getNameById($sregion), $R->getNameById($tregion));
      $alertData['help'] = '';
   }
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$regionsData['regions'] = $R->getAll();
foreach ($regionsData['regions'] as $region)
{
   $regionsData['regionList'][] = array ('val' => $region['id'], 'name' => $region['name'], 'selected' => false );
}

$holidays = $H->getAll();
foreach ($holidays as $holiday)
{
   $regionsData['holidayList'][] = array ('val' => $holiday['id'], 'name' => $holiday['name'], 'selected' => false );
}
$regionsData['ical'] = array (
   array ( 'prefix' => 'regions', 'name' => 'ical_region', 'type' => 'list', 'values' => $regionsData['regionList'] ),
   array ( 'prefix' => 'regions', 'name' => 'ical_holiday', 'type' => 'list', 'values' => $regionsData['holidayList'] ),
   array ( 'prefix' => 'regions', 'name' => 'ical_overwrite', 'type' => 'check', 'value' => false ),
);

$regionsData['merge'] = array (
   array ( 'prefix' => 'regions', 'name' => 'region_a', 'type' => 'list', 'values' => $regionsData['regionList'] ),
   array ( 'prefix' => 'regions', 'name' => 'region_b', 'type' => 'list', 'values' => $regionsData['regionList'] ),
   array ( 'prefix' => 'regions', 'name' => 'region_overwrite', 'type' => 'check', 'value' => false ),
);

/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
