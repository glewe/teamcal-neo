<?php
/**
 * absenceedit.php
 * 
 * Absence edit controller
 *
 * @category TeamCal Neo 
 * @version 2.2.0
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
// CHECK URL PARAMETERS
//
$AA = new Absences(); // for the absence type to be edited

if (isset($_GET['id']))
{
   $missingData = FALSE;
   $id = sanitize($_GET['id']);
   if (!$AA->get($id)) $missingData = TRUE;
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
   $viewData['name'] = $_POST['txt_name'];
     
   //
   // Form validation
   //
   $inputError = false;
   if (isset($_POST['btn_save']))
   {
      if (!formInputValid('txt_name', 'required|alpha_numeric_dash_blank')) $inputError = true;
      if (!formInputValid('txt_symbol', 'alpha_numeric')) $inputError = true;
      if (!formInputValid('txt_color', 'hexadecimal|exact_length', 6)) $inputError = true;
      if (!formInputValid('txt_bgcolor', 'hexadecimal|exact_length', 6)) $inputError = true;
      if (!formInputValid('txt_factor', 'numeric|max_length', 4)) $inputError = true;
      if (!formInputValid('txt_allowance', 'numeric|max_length', 3)) $inputError = true;
      if (!formInputValid('txt_allowmonth', 'numeric|max_length', 2)) $inputError = true;
      if (!formInputValid('txt_allowweek', 'numeric|max_length', 1)) $inputError = true;
   }
    
   if (!$inputError)
   {
      // ,------,
      // | Save |
      // '------'
      if (isset($_POST['btn_save']))
      {
         $AA->id = $_POST['hidden_id'];

         //
         // General
         //
         $AA->name = $_POST['txt_name'];
         if (isset($_POST['txt_symbol'])) $AA->symbol = $_POST['txt_symbol']; else $AA->symbol = strtoupper(substr($_POST['txt_name'], 0, 1));
         if (isset($_POST['opt_iconcolor'])) $AA->iconcolor = $_POST['opt_iconcolor']; else $AA->iconcolor = 'default';
         $AA->color = $_POST['txt_color'];
         $AA->bgcolor = $_POST['txt_bgcolor'];
         if (isset($_POST['chk_bgtrans'])) $AA->bgtrans = '1'; else $AA->bgtrans = '0';
          
         //
         // Options
         //
         $AA->factor = $_POST['txt_factor'];
         $AA->allowance = $_POST['txt_allowance'];
         $AA->allowmonth = $_POST['txt_allowmonth'];
         $AA->allowweek = $_POST['txt_allowweek'];
         $AA->counts_as = $_POST['sel_counts_as'];
         if (isset($_POST['chk_counts_as_present'])) $AA->counts_as_present = '1'; else $AA->counts_as_present = '0';
         if (isset($_POST['chk_approval_required'])) $AA->approval_required = '1'; else $AA->approval_required = '0';
         if (isset($_POST['chk_manager_only'])) $AA->manager_only = '1'; else $AA->manager_only = '0';
         if (isset($_POST['chk_hide_in_profile'])) $AA->hide_in_profile = '1'; else $AA->hide_in_profile = '0';
         if (isset($_POST['chk_confidential'])) $AA->confidential = '1'; else $AA->confidential = '0';
         if (isset($_POST['chk_takeover'])) $AA->takeover = '1'; else $AA->takeover = '0';
         if (isset($_POST['chk_show_in_remainder'])) $AA->show_in_remainder = '1'; else $AA->show_in_remainder = '0';
         
         //
         // Group assignments
         //
         $AG->unassignAbs($AA->id);
         if (isset($_POST['sel_groups']) )
         {
            foreach ($_POST['sel_groups'] as $grp)
            {
               $AG->assign($AA->id, $grp);
            }
         }
         
         //
         // Update the record
         //
         $AA->update($AA->id);
          
         //
         // Send notification e-mails to the subscribers of user events
         //
         if ($C->read("emailNotifications"))
         {
            sendAbsenceEventNotifications("changed", $AA->name);
         }
          
         //
         // Log this event
         //
         $LOG->log("logAbsence",L_USER,"log_abs_updated", $AA->name);
          
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['abs_alert_edit'];
         $alertData['text'] = $LANG['abs_alert_edit_success'];
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
      $alertData['text'] = $LANG['abs_alert_save_failed'];
      $alertData['help'] = '';
   }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['id'] = $AA->id;
$viewData['name'] = $AA->name;
$viewData['symbol'] = $AA->symbol;
$viewData['icon'] = $AA->icon;
$viewData['color'] = $AA->color;
$viewData['bgcolor'] = $AA->bgcolor;
$viewData['bgtrans'] = $AA->bgtrans;

$viewData['general'] = array (
   array ( 'prefix' => 'abs', 'name' => 'name', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['name'], 'maxlength' => '80', 'mandatory' => true, 'error' =>  (isset($inputAlert['name'])?$inputAlert['name']:'') ),
   array ( 'prefix' => 'abs', 'name' => 'symbol', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['symbol'], 'maxlength' => '1', 'mandatory' => true, 'error' =>  (isset($inputAlert['symbol'])?$inputAlert['symbol']:'') ),
   array ( 'prefix' => 'abs', 'name' => 'color', 'type' => 'color', 'value' => $viewData['color'], 'maxlength' => '6', 'error' =>  (isset($inputAlert['color'])?$inputAlert['color']:'') ), 
   array ( 'prefix' => 'abs', 'name' => 'bgcolor', 'type' => 'color', 'value' => $viewData['bgcolor'], 'maxlength' => '6', 'error' =>  (isset($inputAlert['bgcolor'])?$inputAlert['bgcolor']:'') ), 
   array ( 'prefix' => 'abs', 'name' => 'bgtrans', 'type' => 'check', 'value' => $viewData['bgtrans'] ), 
);

$viewData['factor'] = $AA->factor;
$viewData['allowance'] = $AA->allowance;
$viewData['allowmonth'] = $AA->allowmonth;
$viewData['allowweek'] = $AA->allowweek;
$otherAbs = $AA->getAllPrimaryBut($AA->id);
$viewData['otherAbs'][] = array('val' => '0', 'name' => "None", 'selected' => ($AA->counts_as == '0')?true:false );
foreach ($otherAbs as $abs)
{
   $viewData['otherAbs'][] = array('val' => $abs['id'], 'name' => $abs['name'], 'selected' => ($AA->counts_as == $abs['id'])?true:false );
}
$viewData['counts_as']['val'] = $AA->counts_as;
if ($viewData['counts_as']['val']) $viewData['counts_as']['name'] = $AA->getName($AA->counts_as); else $viewData['counts_as']['name'] = "None";
$viewData['counts_as_present'] = $AA->counts_as_present;
$viewData['approval_required'] = $AA->approval_required;
$viewData['manager_only'] = $AA->manager_only;
$viewData['hide_in_profile'] = $AA->hide_in_profile;
$viewData['confidential'] = $AA->confidential;
$viewData['takeover'] = $AA->takeover;
$viewData['show_in_remainder'] = $AA->show_in_remainder;

$viewData['options'] = array (
   array ( 'prefix' => 'abs', 'name' => 'factor', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['factor'], 'maxlength' => '4', 'error' =>  (isset($inputAlert['factor'])?$inputAlert['factor']:'') ), 
   array ( 'prefix' => 'abs', 'name' => 'allowance', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['allowance'], 'maxlength' => '3', 'error' =>  (isset($inputAlert['allowance'])?$inputAlert['allowance']:'') ), 
   array ( 'prefix' => 'abs', 'name' => 'allowmonth', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['allowmonth'], 'maxlength' => '2', 'error' =>  (isset($inputAlert['allowmonth'])?$inputAlert['allowmonth']:'') ), 
   array ( 'prefix' => 'abs', 'name' => 'allowweek', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['allowweek'], 'maxlength' => '2', 'error' =>  (isset($inputAlert['allowweek'])?$inputAlert['allowweek']:'') ), 
   array ( 'prefix' => 'abs', 'name' => 'counts_as', 'type' => 'list', 'values' => $viewData['otherAbs'], 'topvalue' => array('val' => '0', 'name' => 'None') ),
   array ( 'prefix' => 'abs', 'name' => 'counts_as_present', 'type' => 'check', 'value' => $viewData['counts_as_present'] ), 
   array ( 'prefix' => 'abs', 'name' => 'approval_required', 'type' => 'check', 'value' => $viewData['approval_required'] ), 
   array ( 'prefix' => 'abs', 'name' => 'manager_only', 'type' => 'check', 'value' => $viewData['manager_only'] ), 
   array ( 'prefix' => 'abs', 'name' => 'hide_in_profile', 'type' => 'check', 'value' => $viewData['hide_in_profile'] ), 
   array ( 'prefix' => 'abs', 'name' => 'confidential', 'type' => 'check', 'value' => $viewData['confidential'] ), 
   array ( 'prefix' => 'abs', 'name' => 'takeover', 'type' => 'check', 'value' => $viewData['takeover'] ),
   array ( 'prefix' => 'abs', 'name' => 'show_in_remainder', 'type' => 'check', 'value' => $viewData['show_in_remainder'] ),
);

$groups = $G->getAll();
foreach ($groups as $group)
{
   if ($AG->isAssigned($viewData['id'], $group['id'])) $selected = true; else $selected = false;
   $viewData['groupsAssigned'][] = array('val' => $group['id'], 'name' => $group['name'], 'selected' => $selected);
}

$viewData['groups'] = array (
   array ( 'prefix' => 'abs', 'name' => 'groups', 'type' => 'listmulti', 'values' => $viewData['groupsAssigned'] ),
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
