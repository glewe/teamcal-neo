<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Roles Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2020 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo Pro
 * @subpackage Controllers
 * @since 3.0.0
 */

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
$viewData['txt_name'] = '';
$viewData['txt_description'] = '';

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
    
   //
   // Validate input data. If something is wrong or missing, set $inputError = true
   //
    
   if (!$inputError)
   {
      // ,--------,
      // | Create |
      // '--------'
      if ( isset($_POST['btn_roleCreate']) )
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
         if (!formInputValid('txt_name', 'required|alpha_numeric_dash') OR
             !formInputValid('txt_description', 'alpha_numeric_dash_blank')) 
         {
            $inputError = true;
            $alertData['text'] = $LANG['roles_alert_created_fail_input'];
         }
      
         $viewData['txt_name'] = $_POST['txt_name'];
         if ( isset($_POST['txt_description']) ) $viewData['txt_description'] = $_POST['txt_description'];
         
         if ($RO->getByName($_POST['txt_name']))
         {
            $inputError = true;
            $alertData['text'] = $LANG['roles_alert_created_fail_duplicate'];
         }
         
         if (!$inputError)
         {
            $RO->name = $viewData['txt_name'];
            $RO->description = $viewData['txt_description'];
            $RO->color = 'default';
            $RO->create();
      
            //
            // Send notification e-mails to the subscribers of role events
            //
            if ($C->read("emailNotifications"))
            {
               sendRoleEventNotifications("created", $RO->name, $RO->description);
            }
            
            //
            // Log this event
            //
            $LOG->log("logRole",L_USER,"log_role_created", $RO->name." ".$RO->description);
                   
            //
            // Success
            //
            $showAlert = TRUE;
            $alertData['type'] = 'success';
            $alertData['title'] = $LANG['alert_success_title'];
            $alertData['subject'] = $LANG['btn_create_role'];
            $alertData['text'] = $LANG['roles_alert_created'];
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
            $alertData['subject'] = $LANG['btn_create_role'];
            $alertData['help'] = '';
         }
      }
      // ,--------,
      // | Delete |
      // '--------'
      elseif ( isset($_POST['btn_roleDelete']) )
      {
         //
         // Delete Role
         //
         $RO->delete($_POST['hidden_id']);
         
         //
         // Delete Role in all permission schemes
         //
         $P->deleteRole($_POST['hidden_id']);
         
         //
         // Send notification e-mails to the subscribers of role events
         //
         if ($C->read("emailNotifications"))
         {
            sendRoleEventNotifications("deleted", $_POST['hidden_name'], $_POST['hidden_description']);
         }
            
         //
         // Log this event
         //
         $LOG->log("logRole",L_USER,"log_role_deleted", $_POST['hidden_name']);
          
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['btn_delete_role'];
         $alertData['text'] = $LANG['roles_alert_deleted'];
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
      $alertData['text'] = $LANG['register_alert_failed'];
      $alertData['help'] = '';
   }
}
      
//=============================================================================
//
// PREPARE VIEW
//

//
// Default: Get all roles
//
$viewData['roles'] = $RO->getAll();
$viewData['searchRole'] = '';

// ,--------,
// | Search |
// '--------'
if (isset($_POST['btn_search']))
{
   $searchUsers = array();

   if (isset($_POST['txt_searchRole']))
   {
      $searchRole = sanitize($_POST['txt_searchRole']);
      $viewData['searchRole'] = $searchRole;
      $viewData['roles'] = $RO->getAllLike($searchRole);
   }
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
