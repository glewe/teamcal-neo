<?php
/**
 * declination.php
 * 
 * Declination page controller
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

//=============================================================================
//
// VARIABLE DEFAULTS
//
$inputAlert = array();

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
   // Load sanitized form info for the view
   //
   $viewData['threshold'] = $_POST['txt_threshold'];
     
   //
   // Form validation
   //
   $inputError = false;
   if (isset($_POST['btn_save']))
   {
      if ( isset($_POST['chk_absence']) ) 
      {
         if (!formInputValid('txt_threshold', 'max_length|numeric', 2)) $inputError = true;
         if ( isset($_POST['opt_absencePeriod'])) 
         {
            switch ($_POST['opt_absencePeriod'])
            {
               case 'nowEnddate':
                  if (!formInputValid('txt_absenceEnddate', 'required|date')) $inputError = true;
                  break;
               case 'startdateForever':
                  if (!formInputValid('txt_absenceStartdate', 'required|date')) $inputError = true;
                  break;
               case 'startdateEnddate':
                  if (!formInputValid('txt_absenceStartdate', 'required|date')) $inputError = true;
                  if (!formInputValid('txt_absenceEnddate', 'required|date')) $inputError = true;
                  break;
            }
         }
      }
      
      if ( isset($_POST['chk_before']) ) 
      {
         if (!formInputValid('opt_beforeoption', 'required')) 
         {
            $inputError = true;
         }
         else 
         {
            if ( isset($_POST['opt_beforeoption']) AND $_POST['opt_beforeoption'] == 'date' )
            {
               if (!formInputValid('txt_beforedate', 'required|date')) $inputError = true;
            }
         }
         
         if ( isset($_POST['opt_beforePeriod']))
         {
            switch ($_POST['opt_beforePeriod'])
            {
               case 'nowEnddate':
                  if (!formInputValid('txt_beforeEnddate', 'required|date')) $inputError = true;
                  break;
               case 'startdateForever':
                  if (!formInputValid('txt_beforeStartdate', 'required|date')) $inputError = true;
                  break;
               case 'startdateEnddate':
                  if (!formInputValid('txt_beforeStartdate', 'required|date')) $inputError = true;
                  if (!formInputValid('txt_beforeEnddate', 'required|date')) $inputError = true;
                  break;
            }
         }
      }
      
      if ( isset($_POST['chk_period1']) ) 
      {
         if (!formInputValid('txt_period1start', 'required|date')) $inputError = true;
         if (!formInputValid('txt_period1end', 'required|date')) $inputError = true;
         if (!formInputValid('txt_period1Message', 'alpha_numeric_dash_blank_special')) $inputError = true;
          
         if ( isset($_POST['opt_period1Period']))
         {
            switch ($_POST['opt_period1Period'])
            {
               case 'nowEnddate':
                  if (!formInputValid('txt_period1Enddate', 'required|date')) $inputError = true;
                  break;
               case 'startdateForever':
                  if (!formInputValid('txt_period1Startdate', 'required|date')) $inputError = true;
                  break;
               case 'startdateEnddate':
                  if (!formInputValid('txt_period1Startdate', 'required|date')) $inputError = true;
                  if (!formInputValid('txt_period1Enddate', 'required|date')) $inputError = true;
                  break;
            }
         }
          
         if (!$inputError)
         {
            $periodstart = str_replace("-","",$_POST['txt_period1start']);
            $periodend   = str_replace("-","",$_POST['txt_period1end']);
            if ($periodend<=$periodstart)
            {
               $inputAlert['period1end'] = sprintf($LANG['alert_input_greater_than'], $LANG['decl_period1start']);
               $inputError = true;               
            }
         }
      }
      
      if ( isset($_POST['chk_period2']) ) 
      {
         if (!formInputValid('txt_period2start', 'required|date')) $inputError = true;
         if (!formInputValid('txt_period2end', 'required|date')) $inputError = true;
         if (!formInputValid('txt_period2Message', 'alpha_numeric_dash_blank_special')) $inputError = true;
          
         if ( isset($_POST['opt_period2Period']))
         {
            switch ($_POST['opt_period2Period'])
            {
               case 'nowEnddate':
                  if (!formInputValid('txt_period2Enddate', 'required|date')) $inputError = true;
                  break;
               case 'startdateForever':
                  if (!formInputValid('txt_period2Startdate', 'required|date')) $inputError = true;
                  break;
               case 'startdateEnddate':
                  if (!formInputValid('txt_period2Startdate', 'required|date')) $inputError = true;
                  if (!formInputValid('txt_period2Enddate', 'required|date')) $inputError = true;
                  break;
            }
         }
         
         if (!$inputError)
         {
            $periodstart = str_replace("-","",$_POST['txt_period2start']);
            $periodend   = str_replace("-","",$_POST['txt_period2end']);
            if ($periodend<=$periodstart)
            {
               $inputAlert['period2end'] = sprintf($LANG['alert_input_greater_than'], $LANG['decl_period2start']);
               $inputError = true;               
            }
         }
      }
      
      if ( isset($_POST['chk_period3']) ) 
      {
         if (!formInputValid('txt_period3start', 'required|date')) $inputError = true;
         if (!formInputValid('txt_period3end', 'required|date')) $inputError = true;
         if (!formInputValid('txt_period3Message', 'alpha_numeric_dash_blank_special')) $inputError = true;
          
         if ( isset($_POST['opt_period3Period']))
         {
            switch ($_POST['opt_period3Period'])
            {
               case 'nowEnddate':
                  if (!formInputValid('txt_period3Enddate', 'required|date')) $inputError = true;
                  break;
               case 'startdateForever':
                  if (!formInputValid('txt_period3Startdate', 'required|date')) $inputError = true;
                  break;
               case 'startdateEnddate':
                  if (!formInputValid('txt_period3Startdate', 'required|date')) $inputError = true;
                  if (!formInputValid('txt_period3Enddate', 'required|date')) $inputError = true;
                  break;
            }
         }
          
         if (!$inputError)
         {
            $periodstart = str_replace("-","",$_POST['txt_period3start']);
            $periodend   = str_replace("-","",$_POST['txt_period3end']);
            if ($periodend<=$periodstart)
            {
               $inputAlert['period3end'] = sprintf($LANG['alert_input_greater_than'], $LANG['decl_period3start']);
               $inputError = true;               
            }
         }
      }
      
   }
    
   if (!$inputError)
   {
      // ,------,
      // | Save |
      // '------'
      if (isset($_POST['btn_save']))
      {
         //
         // Absence threshold
         //
         if ( isset($_POST['chk_absence']) ) 
         {
            $C->save("declAbsence","1");
            if ( strlen($_POST['txt_threshold']) ) $C->save("declThreshold",$_POST['txt_threshold']); else $C->save("declThreshold","0");
            if ( isset($_POST['opt_base']) ) $C->save("declBase", $_POST['opt_base']); else $C->save("declBase","all");
            if ( isset($_POST['opt_absencePeriod']) ) $C->save("declAbsencePeriod", $_POST['opt_absencePeriod']); else $C->save("declAbsencePeriod","nowForever");
            if ( isset($_POST['txt_absenceStartdate']) ) $C->save("declAbsenceStartdate", $_POST['txt_absenceStartdate']);
            if ( isset($_POST['txt_absenceEnddate']) )$C->save("declAbsenceEnddate", $_POST['txt_absenceEnddate']);
         }
         else 
         {
            $C->save("declAbsence","0");
         }
         
         //
         // Before date
         //
         if ( isset($_POST['chk_before']) ) 
         {
            $C->save("declBefore","1");
            if ( isset($_POST['opt_beforePeriod']) ) $C->save("declBeforePeriod", $_POST['opt_beforePeriod']); else $C->save("declBeforePeriod","nowForever");
            if ( isset($_POST['txt_beforeStartdate']) ) $C->save("declBeforeStartdate", $_POST['txt_beforeStartdate']);
            if ( isset($_POST['txt_beforeEnddate']) )$C->save("declBeforeEnddate", $_POST['txt_beforeEnddate']);
            
            if ( isset($_POST['opt_beforeoption'])) 
            {
               $C->save("declBeforeOption", $_POST['opt_beforeoption']);
               if ($_POST['opt_beforeoption'] == 'today') 
               {
                  $C->save("declBeforeDate", "");
               }
               else
               {
                  $C->save("declBeforeDate", $_POST['txt_beforedate']);
               }
            }
         }
         else
         {
            $C->save("declBefore","0");
         }
          
         //
         // Period 1
         //
         if ( isset($_POST['chk_period1']) )
         {
            $C->save("declPeriod1","1");
            $C->save("declPeriod1Start", $_POST['txt_period1start']);
            $C->save("declPeriod1End", $_POST['txt_period1end']);
            if ( isset($_POST['opt_period1Period']) ) $C->save("declPeriod1Period", $_POST['opt_period1Period']); else $C->save("declPeriod1Period","nowForever");
            if ( isset($_POST['txt_period1Startdate']) ) $C->save("declPeriod1Startdate", $_POST['txt_period1Startdate']);
            if ( isset($_POST['txt_period1Enddate']) )$C->save("declPeriod1Enddate", $_POST['txt_period1Enddate']);
            $C->save("declPeriod1Message", sanitize($_POST['txt_period1Message']));
         }
         else
         {
            $C->save("declPeriod1","0");
         }
         
         //
         // Period 2
         //
         if ( isset($_POST['chk_period2']) )
         {
            $C->save("declPeriod2","1");
            $C->save("declPeriod2Start", $_POST['txt_period2start']);
            $C->save("declPeriod2End", $_POST['txt_period2end']);
            if ( isset($_POST['opt_period2Period']) ) $C->save("declPeriod2Period", $_POST['opt_period2Period']); else $C->save("declPeriod2Period","nowForever");
            if ( isset($_POST['txt_period2Startdate']) ) $C->save("declPeriod2Startdate", $_POST['txt_period2Startdate']);
            if ( isset($_POST['txt_period2Enddate']) )$C->save("declPeriod2Enddate", $_POST['txt_period2Enddate']);
            $C->save("declPeriod2Message", sanitize($_POST['txt_period2Message']));
         }
         else
         {
            $C->save("declPeriod2","0");
         }
          
         //
         // Period 3
         //
         if ( isset($_POST['chk_period3']) )
         {
            $C->save("declPeriod3","1");
            $C->save("declPeriod3Start", $_POST['txt_period3start']);
            $C->save("declPeriod3End", $_POST['txt_period3end']);
            if ( isset($_POST['opt_period3Period']) ) $C->save("declPeriod3Period", $_POST['opt_period3Period']); else $C->save("declPeriod3Period","nowForever");
            if ( isset($_POST['txt_period3Startdate']) ) $C->save("declPeriod3Startdate", $_POST['txt_period3Startdate']);
            if ( isset($_POST['txt_period3Enddate']) )$C->save("declPeriod3Enddate", $_POST['txt_period3Enddate']);
            $C->save("declPeriod3Message", sanitize($_POST['txt_period3Message']));
         }
         else
         {
            $C->save("declPeriod3","0");
         }
          
         //
         // Scope
         //
         $scope = '';
         if (isset($_POST['sel_roles']) )
         {
            foreach ($_POST['sel_roles'] as $scopeRole)
            {
               $scope .= $scopeRole.',';
            }
         }
         $scope = rtrim($scope,',');
         $C->save("declScope", $scope);
          
         //
         // Log this event
         //
         $LOG->log("logConfig",L_USER,"log_decl_updated");
          
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['decl_alert_save'];
         $alertData['text'] = $LANG['decl_alert_save_success'];
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
      $alertData['text'] = $LANG['decl_alert_save_failed'];
      $alertData['help'] = '';
   }
}

//=============================================================================
//
// PREPARE VIEW
//

//
// Absence Threshold Rule
//
$viewData['declAbsence'] = $C->read('declAbsence');
$viewData['declThreshold'] = $C->read('declThreshold');
$viewData['declBase'] = $C->read('declBase');
$viewData['declAbsencePeriod'] = ($C->read('declAbsencePeriod'))?$C->read('declAbsencePeriod'):'nowForever';
$viewData['declAbsenceStartdate'] = $C->read('declAbsenceStartdate');
$viewData['declAbsenceEnddate'] = $C->read('declAbsenceEnddate');
$absenceStartdateDisabled = true;
$absenceEnddateDisabled = true;
switch ($viewData['declAbsencePeriod'])
{
   case 'nowEnddate':
      $absenceStartdateDisabled = true;
      $absenceEnddateDisabled = false;
      break;
   case 'startdateForever':
      $absenceStartdateDisabled = false;
      $absenceEnddateDisabled = true;
      break;
   case 'startdateEnddate':
      $absenceStartdateDisabled = false;
      $absenceEnddateDisabled = false;
      break;
}

//
// Absence Threshold Rule Status
//
$viewData['declAbsenceStatus'] = getDeclinationStatus($C->read('declAbsence'), $viewData['declAbsencePeriod'], $viewData['declAbsenceStartdate'], $viewData['declAbsenceEnddate']);

//
// Before Date Rule
//
$viewData['declBefore'] = $C->read('declBefore');
$viewData['declBeforeOption'] = $C->read('declBeforeOption');
$viewData['declBeforeDate'] = $C->read('declBeforeDate');
$viewData['declBeforePeriod'] = ($C->read('declBeforePeriod'))?$C->read('declBeforePeriod'):'nowForever';
$viewData['declBeforeStartdate'] = $C->read('declBeforeStartdate');
$viewData['declBeforeEnddate'] = $C->read('declBeforeEnddate');
$beforeStartdateDisabled = true;
$beforeEnddateDisabled = true;
switch ($viewData['declBeforePeriod'])
{
   case 'nowEnddate':
      $beforeStartdateDisabled = true;
      $beforeEnddateDisabled = false;
      break;
   case 'startdateForever':
      $beforeStartdateDisabled = false;
      $beforeEnddateDisabled = true;
      break;
   case 'startdateEnddate':
      $beforeStartdateDisabled = false;
      $beforeEnddateDisabled = false;
      break;
}

//
// Before Date Rule Status
//
$viewData['declBeforeStatus'] = getDeclinationStatus($C->read('declBefore'), $viewData['declBeforePeriod'], $viewData['declBeforeStartdate'], $viewData['declBeforeEnddate']);

//
// Period 1 Rule
//
$viewData['declPeriod1'] = $C->read('declPeriod1');
$viewData['declPeriod1Start'] = $C->read('declPeriod1Start');
$viewData['declPeriod1End'] = $C->read('declPeriod1End');
$viewData['declPeriod1Period'] = ($C->read('declPeriod1Period'))?$C->read('declPeriod1Period'):'nowForever';
$viewData['declPeriod1Startdate'] = $C->read('declPeriod1Startdate');
$viewData['declPeriod1Enddate'] = $C->read('declPeriod1Enddate');
$period1StartdateDisabled = true;
$period1EnddateDisabled = true;
switch ($C->read('declPeriod1Period'))
{
   case 'nowEnddate':
      $period1StartdateDisabled = true;
      $period1EnddateDisabled = false;
      break;
   case 'startdateForever':
      $period1StartdateDisabled = false;
      $period1EnddateDisabled = true;
      break;
   case 'startdateEnddate':
      $period1StartdateDisabled = false;
      $period1EnddateDisabled = false;
      break;
}

//
// Period 1 Rule Status
//
$viewData['declPeriod1Status'] = getDeclinationStatus($C->read('declPeriod1'), $viewData['declPeriod1Period'], $viewData['declPeriod1Startdate'], $viewData['declPeriod1Enddate']);

//
// Period 2 Rule
//
$viewData['declPeriod2'] = $C->read('declPeriod2');
$viewData['declPeriod2Start'] = $C->read('declPeriod2Start');
$viewData['declPeriod2End'] = $C->read('declPeriod2End');
$viewData['declPeriod2Period'] = ($C->read('declPeriod2Period'))?$C->read('declPeriod2Period'):'nowForever';
$viewData['declPeriod2Startdate'] = $C->read('declPeriod2Startdate');
$viewData['declPeriod2Enddate'] = $C->read('declPeriod2Enddate');
$period2StartdateDisabled = true;
$period2EnddateDisabled = true;
switch ($C->read('declPeriod2Period'))
{
   case 'nowEnddate':
      $period2StartdateDisabled = true;
      $period2EnddateDisabled = false;
      break;
   case 'startdateForever':
      $period2StartdateDisabled = false;
      $period2EnddateDisabled = true;
      break;
   case 'startdateEnddate':
      $period2StartdateDisabled = false;
      $period2EnddateDisabled = false;
      break;
}

//
// Period 2 Rule Status
//
$viewData['declPeriod2Status'] = getDeclinationStatus($C->read('declPeriod2'), $viewData['declPeriod2Period'], $viewData['declPeriod2Startdate'], $viewData['declPeriod2Enddate']);

//
// Period 3 Rule
//
$viewData['declPeriod3'] = $C->read('declPeriod3');
$viewData['declPeriod3Start'] = $C->read('declPeriod3Start');
$viewData['declPeriod3End'] = $C->read('declPeriod3End');
$viewData['declPeriod3Period'] = ($C->read('declPeriod3Period'))?$C->read('declPeriod3Period'):'nowForever';
$viewData['declPeriod3Startdate'] = $C->read('declPeriod3Startdate');
$viewData['declPeriod3Enddate'] = $C->read('declPeriod3Enddate');
$period3StartdateDisabled = true;
$period3EnddateDisabled = true;
switch ($C->read('declPeriod3Period'))
{
   case 'nowEnddate':
      $period3StartdateDisabled = true;
      $period3EnddateDisabled = false;
      break;
   case 'startdateForever':
      $period3StartdateDisabled = false;
      $period3EnddateDisabled = true;
      break;
   case 'startdateEnddate':
      $period3StartdateDisabled = false;
      $period3EnddateDisabled = false;
      break;
}

//
// Period 3 Rule Status
//
$viewData['declPeriod3Status'] = getDeclinationStatus($C->read('declPeriod3'), $viewData['declPeriod3Period'], $viewData['declPeriod3Startdate'], $viewData['declPeriod3Enddate']);

//
// Scope
//
$viewData['declNotifyUser'] = $C->read('declNotifyUser');
$viewData['declNotifyManager'] = $C->read('declNotifyManager');
$viewData['declNotifyDirector'] = $C->read('declNotifyDirector');
$viewData['declNotifyAdmin'] = $C->read('declNotifyAdmin');

$viewData['declApplyToAll'] = $C->read('declApplyToAll');

$viewData['absence'] = array (
   array ( 'prefix' => 'decl', 'name' => 'absence', 'type' => 'check', 'value' => $viewData['declAbsence'] ),
   array ( 'prefix' => 'decl', 'name' => 'threshold', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['declThreshold'], 'maxlength' => '2', 'error' => (isset($inputAlert['threshold'])?$inputAlert['threshold']:'') ),
   array ( 'prefix' => 'decl', 'name' => 'base', 'type' => 'radio', 'values' => array ('all', 'group'), 'value' => $viewData['declBase'] ),
   array ( 'prefix' => 'decl', 'name' => 'absencePeriod', 'type' => 'radio', 'values' => array ('nowForever', 'nowEnddate', 'startdateForever', 'startdateEnddate'), 'value' => $viewData['declAbsencePeriod'] ),
   array ( 'prefix' => 'decl', 'name' => 'absenceStartdate', 'type' => 'date', 'value' => $viewData['declAbsenceStartdate'], 'error' => (isset($inputAlert['absenceStartdate'])?$inputAlert['absenceStartdate']:''), 'disabled' => $absenceStartdateDisabled ),
   array ( 'prefix' => 'decl', 'name' => 'absenceEnddate', 'type' => 'date', 'value' => $viewData['declAbsenceEnddate'], 'error' => (isset($inputAlert['absenceEnddate'])?$inputAlert['absenceEnddate']:''), 'disabled' => $absenceEnddateDisabled  ),
);

$viewData['before'] = array (
   array ( 'prefix' => 'decl', 'name' => 'before', 'type' => 'check', 'value' => $viewData['declBefore'] ),
   array ( 'prefix' => 'decl', 'name' => 'beforeoption', 'type' => 'radio', 'values' => array ('today', 'date'), 'value' => $viewData['declBeforeOption'], 'error' => (isset($inputAlert['beforeoption'])?$inputAlert['beforeoption']:'') ),
   array ( 'prefix' => 'decl', 'name' => 'beforedate', 'type' => 'date', 'value' => $viewData['declBeforeDate'], 'error' => (isset($inputAlert['beforedate'])?$inputAlert['beforedate']:'') ), 
   array ( 'prefix' => 'decl', 'name' => 'beforePeriod', 'type' => 'radio', 'values' => array ('nowForever', 'nowEnddate', 'startdateForever', 'startdateEnddate'), 'value' => $viewData['declBeforePeriod'] ),
   array ( 'prefix' => 'decl', 'name' => 'beforeStartdate', 'type' => 'date', 'value' => $viewData['declBeforeStartdate'], 'error' => (isset($inputAlert['beforeStartdate'])?$inputAlert['beforeStartdate']:''), 'disabled' => $beforeStartdateDisabled ),
   array ( 'prefix' => 'decl', 'name' => 'beforeEnddate', 'type' => 'date', 'value' => $viewData['declBeforeEnddate'], 'error' => (isset($inputAlert['beforeEnddate'])?$inputAlert['beforeEnddate']:''), 'disabled' => $beforeEnddateDisabled  ),
);

$viewData['period1'] = array (
   array ( 'prefix' => 'decl', 'name' => 'period1', 'type' => 'check', 'value' => $viewData['declPeriod1'] ),
   array ( 'prefix' => 'decl', 'name' => 'period1start', 'type' => 'date', 'value' => $viewData['declPeriod1Start'], 'error' => (isset($inputAlert['period1start'])?$inputAlert['period1start']:'') ),
   array ( 'prefix' => 'decl', 'name' => 'period1end', 'type' => 'date', 'value' => $viewData['declPeriod1End'], 'error' => (isset($inputAlert['period1end'])?$inputAlert['period1end']:'') ),
   array ( 'prefix' => 'decl', 'name' => 'period1Period', 'type' => 'radio', 'values' => array ('nowForever', 'nowEnddate', 'startdateForever', 'startdateEnddate'), 'value' => $viewData['declPeriod1Period'] ),
   array ( 'prefix' => 'decl', 'name' => 'period1Startdate', 'type' => 'date', 'value' => $viewData['declPeriod1Startdate'], 'error' => (isset($inputAlert['period1Startdate'])?$inputAlert['period1Startdate']:''), 'disabled' => $period1StartdateDisabled ),
   array ( 'prefix' => 'decl', 'name' => 'period1Enddate', 'type' => 'date', 'value' => $viewData['declPeriod1Enddate'], 'error' => (isset($inputAlert['period1Enddate'])?$inputAlert['period1Enddate']:''), 'disabled' => $period1EnddateDisabled  ),
   array ( 'prefix' => 'decl', 'name' => 'period1Message', 'type' => 'textlong', 'value' => strip_tags($C->read("declPeriod1Message")), 'placeholder' => $LANG['alert_decl_period'], 'maxlength' => '240', 'error' => (isset($inputAlert['period1Message'])?$inputAlert['period1Message']:'') ),
);

$viewData['period2'] = array (
   array ( 'prefix' => 'decl', 'name' => 'period2', 'type' => 'check', 'value' => $viewData['declPeriod2'] ),
   array ( 'prefix' => 'decl', 'name' => 'period2start', 'type' => 'date', 'value' => $viewData['declPeriod2Start'], 'error' => (isset($inputAlert['period2start'])?$inputAlert['period2start']:'') ),
   array ( 'prefix' => 'decl', 'name' => 'period2end', 'type' => 'date', 'value' => $viewData['declPeriod2End'], 'error' => (isset($inputAlert['period2end'])?$inputAlert['period2end']:'') ),
   array ( 'prefix' => 'decl', 'name' => 'period2Period', 'type' => 'radio', 'values' => array ('nowForever', 'nowEnddate', 'startdateForever', 'startdateEnddate'), 'value' => $viewData['declPeriod2Period'] ),
   array ( 'prefix' => 'decl', 'name' => 'period2Startdate', 'type' => 'date', 'value' => $viewData['declPeriod2Startdate'], 'error' => (isset($inputAlert['period2Startdate'])?$inputAlert['period2Startdate']:''), 'disabled' => $period2StartdateDisabled ),
   array ( 'prefix' => 'decl', 'name' => 'period2Enddate', 'type' => 'date', 'value' => $viewData['declPeriod2Enddate'], 'error' => (isset($inputAlert['period2Enddate'])?$inputAlert['period2Enddate']:''), 'disabled' => $period2EnddateDisabled  ),
   array ( 'prefix' => 'decl', 'name' => 'period2Message', 'type' => 'textlong', 'value' => strip_tags($C->read("declPeriod2Message")), 'placeholder' => $LANG['alert_decl_period'], 'maxlength' => '240', 'error' => (isset($inputAlert['period2Message'])?$inputAlert['period2Message']:'') ),
);

$viewData['period3'] = array (
   array ( 'prefix' => 'decl', 'name' => 'period3', 'type' => 'check', 'value' => $viewData['declPeriod3'] ),
   array ( 'prefix' => 'decl', 'name' => 'period3start', 'type' => 'date', 'value' => $viewData['declPeriod3Start'], 'error' => (isset($inputAlert['period3start'])?$inputAlert['period3start']:'') ),
   array ( 'prefix' => 'decl', 'name' => 'period3end', 'type' => 'date', 'value' => $viewData['declPeriod3End'], 'error' => (isset($inputAlert['period3end'])?$inputAlert['period3end']:'') ),
   array ( 'prefix' => 'decl', 'name' => 'period3Period', 'type' => 'radio', 'values' => array ('nowForever', 'nowEnddate', 'startdateForever', 'startdateEnddate'), 'value' => $viewData['declPeriod3Period'] ),
   array ( 'prefix' => 'decl', 'name' => 'period3Startdate', 'type' => 'date', 'value' => $viewData['declPeriod3Startdate'], 'error' => (isset($inputAlert['period3Startdate'])?$inputAlert['period3Startdate']:''), 'disabled' => $period3StartdateDisabled ),
   array ( 'prefix' => 'decl', 'name' => 'period3Enddate', 'type' => 'date', 'value' => $viewData['declPeriod3Enddate'], 'error' => (isset($inputAlert['period3Enddate'])?$inputAlert['period3Enddate']:''), 'disabled' => $period3EnddateDisabled  ),
   array ( 'prefix' => 'decl', 'name' => 'period3Message', 'type' => 'textlong', 'value' => strip_tags($C->read("declPeriod3Message")), 'placeholder' => $LANG['alert_decl_period'], 'maxlength' => '240', 'error' => (isset($inputAlert['period3Message'])?$inputAlert['period3Message']:'') ),
);

$roles = $RO->getAll();
$currentScope = $C->read('declScope');
$currentScopeArray = explode(',', $currentScope);  
foreach ($roles as $role)
{
   $viewData['roles'][] = array('val' => $role['id'], 'name' => $role['name'], 'selected' => (in_array($role['id'],$currentScopeArray))?true:false);
}
$viewData['scope'] = array (
   array ( 'prefix' => 'decl', 'name' => 'roles', 'type' => 'listmulti', 'values' => $viewData['roles'] ),
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
