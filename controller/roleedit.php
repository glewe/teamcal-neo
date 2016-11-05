<?php
/**
 * roleedit.php
 * 
 * Role edit page controller
 *
 * @category TeamCal Neo 
 * @version 1.0.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

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
$RO2 = new Roles(); // for the profile to be created or updated
if (isset($_GET['id']))
{
   $missingData = FALSE;
   $roleid = sanitize($_GET['id']);
   if (!$RO2->getById($roleid)) $missingData = TRUE;
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
$viewData['id'] = $RO2->id;
$viewData['name'] = $RO2->name;
$viewData['description'] = $RO2->description;
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
   // Form validation
   //
   $inputError = false;
   if (!formInputValid('txt_name', 'required|alpha_numeric_dash') OR
    !formInputValid('txt_description', 'alpha_numeric_dash_blank')) 
   {
      $inputError = true;
      $alertData['text'] = $LANG['role_alert_save_failed'];
   }

   //
   // Load sanitized form info for the view
   //
   $viewData['name'] = $_POST['txt_name'];
   $viewData['description'] = $_POST['txt_description'];
    
   if (!$inputError)
   {
      // ,--------,
      // | Update |
      // '--------'
      if (isset($_POST['btn_roleUpdate']))
      {
         $oldName = $RO2->name; 
         $RO2->name = $_POST['txt_name'];
         $RO2->description = $_POST['txt_description'];
         if ($_POST['opt_color']) $RO2->color = $_POST['opt_color'];
          
         $RO2->update($RO2->id);
          
         //
         // Send notification e-mails to the subscribers of user events
         //
         if ($C->read("emailNotifications"))
         {
            sendRoleEventNotifications("changed", $RO2->name.' (ex: '.$oldName.')', $RO2->description);
         }
          
         //
         // Log this event
         //
         $LOG->log("logRole",$L->checkLogin(),"log_role_updated", $RO2->name.' (ex: '.$oldName.')');
          
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['role_alert_edit'];
         $alertData['text'] = $LANG['role_alert_edit_success'];
         $alertData['help'] = '';
         
         //
         // Load new info for the view
         //
         $viewData['name'] = $RO2->name;
         $viewData['description'] = $RO2->description;
         $viewData['color'] = $RO2->color;
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
      $alertData['help'] = '';
   }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['role'] = array (
   array ( 'prefix' => 'role', 'name' => 'name', 'type' => 'text', 'value' => $viewData['name'], 'maxlength' => '40', 'mandatory' => true, 'error' =>  (isset($inputAlert['name'])?$inputAlert['name']:'') ),
   array ( 'prefix' => 'role', 'name' => 'description', 'type' => 'text', 'value' => $viewData['description'], 'maxlength' => '100', 'error' =>  (isset($inputAlert['description'])?$inputAlert['description']:'') ),
   array ( 'prefix' => 'role', 'name' => 'color', 'type' => 'radio', 'values' => array ('default', 'primary', 'info', 'success', 'warning', 'danger'), 'value' => $RO2->getColorByName($viewData['name']) ),
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
