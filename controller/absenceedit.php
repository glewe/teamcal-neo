<?php
/**
 * absenceedit.php
 * 
 * Config page controller
 *
 * @category TeamCal Neo 
 * @version 0.3.00
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

/**
 * ========================================================================
 * Check URL params
 */
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
else
{
   /**
    * ========================================================================
    * Check if allowed
    */
   if (!isAllowed($controller))
   {
      $alertData['type'] = 'warning';
      $alertData['title'] = $LANG['alert_alert_title'];
      $alertData['subject'] = $LANG['alert_not_allowed_subject'];
      $alertData['text'] = $LANG['alert_not_allowed_text'];
      $alertData['help'] = $LANG['alert_not_allowed_help'];
      require (WEBSITE_ROOT . '/controller/alert.php');
      die();
   }
}

/**
 * ========================================================================
 * Load controller stuff
 */

/**
 * ========================================================================
 * Initialize variables
 */
$inputAlert = array();

/**
 * ========================================================================
 * Process form
 */
if (!empty($_POST))
{
   /**
    * Sanitize input
    */
   $_POST = sanitize($_POST);
    
   /**
    * Load sanitized form info for the view
    */
   $absData['name'] = $_POST['txt_name'];
     
   /**
    * Form validation
    */
   $inputError = false;
   if (isset($_POST['btn_save']))
   {
      if (!formInputValid('txt_name', 'required|alpha_numeric_dash_blank')) $inputError = true;
      if (!formInputValid('txt_symbol', 'alpha_numeric')) $inputError = true;
      if (!formInputValid('txt_color', 'hexadecimal|exact_length', 6)) $inputError = true;
      if (!formInputValid('txt_bgcolor', 'hexadecimal|exact_length', 6)) $inputError = true;
      if (!formInputValid('txt_factor', 'numeric|max_length', 4)) $inputError = true;
      if (!formInputValid('txt_allowance', 'numeric|max_length', 4)) $inputError = true;
   }
    
   if (!$inputError)
   {
      /**
       * ,------,
       * | Save |
       * '------'
       */
      if (isset($_POST['btn_save']))
      {
         $AA->id = $_POST['hidden_id'];

         /**
          * General
          */
         $AA->name = $_POST['txt_name'];
         if (isset($_POST['txt_symbol'])) $AA->symbol = $_POST['txt_symbol']; else $AA->symbol = strtoupper(substr($_POST['txt_name'], 0, 1));
         if (isset($_POST['opt_iconcolor'])) $AA->iconcolor = $_POST['opt_iconcolor']; else $AA->iconcolor = 'default';
         $AA->color = $_POST['txt_color'];
         $AA->bgcolor = $_POST['txt_bgcolor'];
         if (isset($_POST['chk_bgtrans'])) $AA->bgtrans = '1'; else $AA->bgtrans = '0';
          
         /**
          * Options
          */
         $AA->factor = $_POST['txt_factor'];
         $AA->allowance = $_POST['txt_allowance'];
         $AA->counts_as = $_POST['sel_counts_as'];
         if (isset($_POST['chk_counts_as_present'])) $AA->counts_as_present = '1'; else $AA->counts_as_present = '0';
         if (isset($_POST['chk_show_in_remainder'])) $AA->show_in_remainder = '1'; else $AA->show_in_remainder = '0';
         if (isset($_POST['chk_show_totals'])) $AA->show_totals = '1'; else $AA->show_totals = '0';
         if (isset($_POST['chk_approval_required'])) $AA->approval_required = '1'; else $AA->approval_required = '0';
         if (isset($_POST['chk_manager_only'])) $AA->manager_only = '1'; else $AA->manager_only = '0';
         if (isset($_POST['chk_hide_in_profile'])) $AA->hide_in_profile = '1'; else $AA->hide_in_profile = '0';
         if (isset($_POST['chk_confidential'])) $AA->confidential = '1'; else $AA->confidential = '0';

         /**
          * Group assignments
          */
         $AG->unassignAbs($AA->id);
         if (isset($_POST['sel_groups']) )
         {
            foreach ($_POST['sel_groups'] as $grp)
            {
               $AG->assign($AA->id, $grp);
            }
         }
         
         /**
          * Update the record
          */
         $AA->update($AA->id);
          
         /**
          * Send notification e-mails to the subscribers of user events
          */
         if ($C->read("emailNotifications"))
         {
            sendAbsenceEventNotifications("changed", $AA->name);
         }
          
         /**
          * Log this event
          */
         $LOG->log("logAbsence",$L->checkLogin(),"log_abs_updated", $AA->name);
          
         /**
          * Success
          */
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
      /**
       * Input validation failed
       */
      $showAlert = TRUE;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['alert_input'];
      $alertData['text'] = $LANG['abs_alert_save_failed'];
      $alertData['help'] = '';
   }
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$absData['id'] = $AA->id;
$absData['name'] = $AA->name;
$absData['symbol'] = $AA->symbol;
$absData['icon'] = $AA->icon;
$absData['color'] = $AA->color;
$absData['bgcolor'] = $AA->bgcolor;
$absData['bgtrans'] = $AA->bgtrans;

$absData['general'] = array (
   array ( 'prefix' => 'abs', 'name' => 'name', 'type' => 'text', 'value' => $absData['name'], 'maxlength' => '80', 'mandatory' => true, 'error' =>  (isset($inputAlert['name'])?$inputAlert['name']:'') ),
   array ( 'prefix' => 'abs', 'name' => 'symbol', 'type' => 'text', 'value' => $absData['symbol'], 'maxlength' => '1', 'mandatory' => true, 'error' =>  (isset($inputAlert['symbol'])?$inputAlert['symbol']:'') ),
   array ( 'prefix' => 'abs', 'name' => 'color', 'type' => 'color', 'value' => $absData['color'], 'maxlength' => '6', 'error' =>  (isset($inputAlert['color'])?$inputAlert['color']:'') ), 
   array ( 'prefix' => 'abs', 'name' => 'bgcolor', 'type' => 'color', 'value' => $absData['bgcolor'], 'maxlength' => '6', 'error' =>  (isset($inputAlert['bgcolor'])?$inputAlert['bgcolor']:'') ), 
   array ( 'prefix' => 'abs', 'name' => 'bgtrans', 'type' => 'check', 'value' => $absData['bgtrans'] ), 
);

$absData['factor'] = $AA->factor;
$absData['allowance'] = $AA->allowance;
$otherAbs = $AA->getAllPrimaryBut($AA->id);
$absData['otherAbs'][] = array('val' => '0', 'name' => "None", 'selected' => ($AA->counts_as == '0')?true:false );
foreach ($otherAbs as $abs)
{
   $absData['otherAbs'][] = array('val' => $abs['id'], 'name' => $abs['name'], 'selected' => ($AA->counts_as == $abs['id'])?true:false );
}
$absData['counts_as']['val'] = $AA->counts_as;
if ($absData['counts_as']['val']) $absData['counts_as']['name'] = $AA->getName($AA->counts_as); else $absData['counts_as']['name'] = "None";
$absData['counts_as_present'] = $AA->counts_as_present;
$absData['show_in_remainder'] = $AA->show_in_remainder;
$absData['show_totals'] = $AA->show_totals;
$absData['approval_required'] = $AA->approval_required;
$absData['manager_only'] = $AA->manager_only;
$absData['hide_in_profile'] = $AA->hide_in_profile;
$absData['confidential'] = $AA->confidential;

$absData['options'] = array (
   array ( 'prefix' => 'abs', 'name' => 'factor', 'type' => 'text', 'value' => $absData['factor'], 'maxlength' => '4', 'error' =>  (isset($inputAlert['factor'])?$inputAlert['factor']:'') ), 
   array ( 'prefix' => 'abs', 'name' => 'allowance', 'type' => 'text', 'value' => $absData['allowance'], 'maxlength' => '4', 'error' =>  (isset($inputAlert['allowance'])?$inputAlert['allowance']:'') ), 
   array ( 'prefix' => 'abs', 'name' => 'counts_as', 'type' => 'list', 'values' => $absData['otherAbs'], 'topvalue' => array('val' => '0', 'name' => 'None') ),
   array ( 'prefix' => 'abs', 'name' => 'counts_as_present', 'type' => 'check', 'value' => $absData['counts_as_present'] ), 
   array ( 'prefix' => 'abs', 'name' => 'show_in_remainder', 'type' => 'check', 'value' => $absData['show_in_remainder'] ), 
   array ( 'prefix' => 'abs', 'name' => 'show_totals', 'type' => 'check', 'value' => $absData['show_totals'] ), 
   array ( 'prefix' => 'abs', 'name' => 'approval_required', 'type' => 'check', 'value' => $absData['approval_required'] ), 
   array ( 'prefix' => 'abs', 'name' => 'manager_only', 'type' => 'check', 'value' => $absData['manager_only'] ), 
   array ( 'prefix' => 'abs', 'name' => 'hide_in_profile', 'type' => 'check', 'value' => $absData['hide_in_profile'] ), 
   array ( 'prefix' => 'abs', 'name' => 'confidential', 'type' => 'check', 'value' => $absData['confidential'] ), 
);

$groups = $G->getAll();
foreach ($groups as $group)
{
   if ($AG->isAssigned($absData['id'], $group['id'])) $selected = true; else $selected = false;
   $absData['groupsAssigned'][] = array('val' => $group['id'], 'name' => $group['name'], 'selected' => $selected);
}

$absData['groups'] = array (
   array ( 'prefix' => 'abs', 'name' => 'groups', 'type' => 'listmulti', 'values' => $absData['groupsAssigned'] ),
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
