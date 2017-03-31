<?php
/**
 * groups.php
 * 
 * Groups page controller
 *
 * @category TeamCal Neo 
 * @version 1.5.000
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
      if ( isset($_POST['btn_groupCreate']) )
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
            $G->name = $viewData['txt_name'];
            $G->description = $viewData['txt_description'];
            $G->create();
      
            //
            // Send notification e-mails to the subscribers of group events
            //
            if ($C->read("emailNotifications"))
            {
               sendGroupEventNotifications("created", $G->name, $G->description);
            }
            
            //
            // Log this event
            //
            $LOG->log("logGroup",$L->checkLogin(),"log_group_created", $G->name." ".$G->description);
                   
            //
            // Success
            //
            $showAlert = TRUE;
            $alertData['type'] = 'success';
            $alertData['title'] = $LANG['alert_success_title'];
            $alertData['subject'] = $LANG['btn_create_group'];
            $alertData['text'] = $LANG['groups_alert_group_created'];
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
            $alertData['subject'] = $LANG['btn_create_group'];
            $alertData['text'] = $LANG['groups_alert_group_created_fail'];
            $alertData['help'] = '';
         }
      }
      // ,--------,
      // | Delete |
      // '--------'
      elseif ( isset($_POST['btn_groupDelete']) )
      {
         $G->delete($_POST['hidden_id']);
         $UG->deleteByGroup($_POST['hidden_id']);
         
         //
         // Send notification e-mails to the subscribers of group events
         //
         if ($C->read("emailNotifications"))
         {
            sendGroupEventNotifications("deleted", $_POST['hidden_name'], $_POST['hidden_description']);
         }
            
         //
         // Log this event
         //
         $LOG->log("logGroup",$L->checkLogin(),"log_group_deleted", $_POST['hidden_name']);
          
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['btn_delete_group'];
         $alertData['text'] = $LANG['groups_alert_group_deleted'];
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
// Default: Get all groups
//
$viewData['groups'] = $G->getAll();
$viewData['searchGroup'] = '';

// ,--------,
// | Search |
// '--------'
// Adjust users by requested search
//
if (isset($_POST['btn_search']))
{
   $searchUsers = array();

   if (isset($_POST['txt_searchGroup']))
   {
      $searchGroup = sanitize($_POST['txt_searchGroup']);
      $viewData['searchGroup'] = $searchGroup;
      $viewData['groups'] = $G->getAllLike($searchGroup);
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
