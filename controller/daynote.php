<?php
/**
 * daynote.php
 * 
 * Daynote editor controller
 *
 * @category TeamCal Neo
 * @version 1.9.009
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2018 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo '<script type="text/javascript">alert("Debug: ");</script>';

//=============================================================================
//
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission) OR 
    (isset($_GET['for']) AND $_GET['for']=='all' AND !isAllowed('daynoteglobal')))
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
if (isset($_GET['date']) AND isset($_GET['for']))
{
   $missingData = FALSE;
   $dnDate = sanitize($_GET['date']);
   $for = sanitize($_GET['for']);
   $region = '1'; // Default
   if ($for=="all")
   {
      if (isset($_GET['region'])) $region = sanitize($_GET['region']);
      else $missingData = TRUE;
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
$viewData['id'] = '';
$viewData['date'] = substr($dnDate,0,4).'-'.substr($dnDate,4,2).'-'.substr($dnDate,6,2);
$viewData['month'] = substr($dnDate,0,6);
$viewData['enddate'] = '';
$viewData['user'] = $for;
if ($for=='all') $viewData['userFullname'] = $LANG['all']; else $viewData['userFullname'] = $U->getFullname($for);
$viewData['region'] = $region;
$viewData['regionName'] = 'Default';
$viewData['daynote'] = '';
$viewData['color'] = 'info';
$viewData['confidential'] = '0';
$viewData['exists'] = false;
$regions = $R->getAll();

//
// If Daynote exists, get it
//
if ($D->get($dnDate,$for,$region)) 
{
   $viewData['id'] = $D->id;
   $viewData['date'] = substr($D->yyyymmdd,0,4).'-'.substr($D->yyyymmdd,4,2).'-'.substr($D->yyyymmdd,6,2);
   $viewData['user'] = $D->username;
   $viewData['region'] = $D->region;
   $viewData['regionName'] = $R->getNameById($D->region);
   $viewData['daynote'] = $D->daynote;
   $viewData['color'] = $D->color;
   $viewData['confidential'] = $D->confidential;
   $viewData['exists'] = true;
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
   // Load sanitized input for view
   //
   $viewData['date'] = $_POST['txt_date'];
   $viewData['daynote'] = $_POST['txt_daynote'];
   $viewData['color'] = $_POST['opt_color'];
   if (isset($_POST['chk_confidential'])) $viewData['confidential'] = '1'; else $viewData['confidential'] = '0';
    
   //
   // Form validation
   //
   $inputError = false;
   if (isset($_POST['btn_create']))
   {
      if (!formInputValid('txt_date', 'required|date')) $inputError = true;
      if (!formInputValid('txt_enddate', 'date')) $inputError = true;
      if (!formInputValid('txt_daynote', 'required')) $inputError = true;
      if (!isset($_POST['sel_regions']))
      {
         $inputAlert['regions'] = $LANG['alert_input_required'];
         $inputError = true;
      }
   }
    
   if (!$inputError)
   {
      // ,----------------,
      // | Create, Update |
      // '----------------'
      if (isset($_POST['btn_create']) OR isset($_POST['btn_update']))
      {
         $D->deleteByDateAndUser($dnDate, $viewData['user']);
          
         if (isset($_POST['sel_regions']))
         {
            foreach ($_POST['sel_regions'] as $reg)
            {
               $D->yyyymmdd = $dnDate;
               $D->username = $viewData['user'];
               $D->region = $reg;
               $D->daynote = $viewData['daynote'];
               $D->color = $viewData['color'];
               $D->confidential = $viewData['confidential'];
               $D->create();
            }
         }

         if (isset($_POST['txt_enddate']))
         {
            $viewData['enddate'] = $_POST['txt_enddate'];
            $enddate = str_replace('-', '', $viewData['enddate']);
            if ($enddate > $D->yyyymmdd)
            {
               for ($i=$D->yyyymmdd+1; $i<=$enddate; $i++)
               {
                  $D->deleteByDateAndUser($i, $viewData['user']);
                  foreach ($_POST['sel_regions'] as $reg)
                  {
                     $D->yyyymmdd = $i;
                     $D->username = $viewData['user'];
                     $D->region = $reg;
                     $D->daynote = $viewData['daynote'];
                     $D->color = $viewData['color'];
                     $D->confidential = $viewData['confidential'];
                     $D->create();
                  }
               }
            }
         }
          
         //
         // Log this event
         //
         if ($viewData['user']=='all') $logentry = $viewData['date']."|".$R->getNameById($viewData['region']).": ".substr($viewData['daynote'],0,20)."...";
         else                          $logentry = $viewData['date']."|".$viewData['user'].": ".substr($viewData['daynote'],0,20)."...";

         if (isset($_POST['btn_create'])) 
         {
            $LOG->log("logDaynote",L_USER,"log_dn_created", $logentry);
            //
            // Success
            //
            $showAlert = TRUE;
            $alertData['type'] = 'success';
            $alertData['title'] = $LANG['alert_success_title'];
            $alertData['subject'] = $LANG['dn_alert_create'];
            $alertData['text'] = $LANG['dn_alert_create_success'];
            $alertData['help'] = '';
         }
         
         if (isset($_POST['btn_update'])) 
         {
            $LOG->log("logDaynote",L_USER,"log_dn_updated", $logentry);
            //
            // Success
            //
            $showAlert = TRUE;
            $alertData['type'] = 'success';
            $alertData['title'] = $LANG['alert_success_title'];
            $alertData['subject'] = $LANG['dn_alert_update'];
            $alertData['text'] = $LANG['dn_alert_update_success'];
            $alertData['help'] = '';
         }
          
      }
      // ,--------,
      // | Delete |
      // '--------'
      if (isset($_POST['btn_delete']))
      {
         $D->deleteByDateAndUser($dnDate, $viewData['user']);
         
         if (isset($_POST['txt_enddate']))
         {
            $startdate = str_replace('-', '', $_POST['txt_date']);
            $enddate = str_replace('-', '', $_POST['txt_enddate']);
            if ($enddate > $startdate)
            {
               for ($i=$startdate; $i<=$enddate; $i++)
               {
                  $D->deleteByDateAndUser($i, $viewData['user']);
               }
            }
         }
         
         //
         // Log this event
         //
         if ($viewData['user']=='all') $logentry = $viewData['date']."|".$R->getNameById($viewData['region']).": ".substr($viewData['daynote'],0,20)."...";
         else                          $logentry = $viewData['date']."|".$viewData['user'].": ".substr($viewData['daynote'],0,20)."...";
         
         $LOG->log("logDaynote",L_USER,"log_dn_deleted", $logentry);
         
         header("Location: index.php?action=".$controller."&date=".str_replace('-','',$viewData['date']).'&for='.$viewData['user'].'&region='.$viewData['region']);
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
      $alertData['text'] = $LANG['dn_alert_failed'];
      $alertData['help'] = '';
   }
}
      
      
//=============================================================================
//
// PREPARE VIEW
//
if ($viewData['exists'])
{
   //
   // A daynote exists for this user and date. Select each reason it is valid for.
   // If no region is set, select the Default region.
   //
   foreach ($regions as $region)
   {
      $viewData['regions'][] = array('val' => $region['id'], 'name' => $region['name'], 'selected' => ($D->get($dnDate,$for,$region['id']))?true:(($region['id']==$viewData['region'])?true:false));
   }
}
else
{
   //
   // Mo daynote exists for this user and date. Select the Default region.
   //
   foreach ($regions as $region)
   {
      $viewData['regions'][] = array('val' => $region['id'], 'name' => $region['name'], 'selected' => ($region['id']==$viewData['region'])?true:false);
   }
}

$viewData['daynote'] = array (
   array ( 'prefix' => 'dn', 'name' => 'date', 'type' => 'date', 'value' => $viewData['date'], 'maxlength' => '10', 'mandatory' => true, 'error' =>  (isset($inputAlert['date'])?$inputAlert['date']:'') ),
   array ( 'prefix' => 'dn', 'name' => 'enddate', 'type' => 'date', 'value' => $viewData['enddate'], 'maxlength' => '10', 'mandatory' => false, 'error' =>  (isset($inputAlert['enddate'])?$inputAlert['enddate']:'') ),
   array ( 'prefix' => 'dn', 'name' => 'daynote', 'type' => 'textarea', 'value' => $viewData['daynote'], 'rows' => '10', 'placeholder' => $LANG['dn_daynote_placeholder'], 'mandatory' => true, 'error' =>  (isset($inputAlert['daynote'])?$inputAlert['daynote']:'') ),
   array ( 'prefix' => 'dn', 'name' => 'regions', 'type' => 'listmulti', 'values' => $viewData['regions'], 'mandatory' => true, 'error' =>  (isset($inputAlert['regions'])?$inputAlert['regions']:'') ),
   array ( 'prefix' => 'dn', 'name' => 'color', 'type' => 'radio', 'values' => array('info', 'success', 'warning', 'danger'), 'value' => $viewData['color'] ),
   array ( 'prefix' => 'dn', 'name' => 'confidential', 'type' => 'check', 'value' => $viewData['confidential'] ),
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
