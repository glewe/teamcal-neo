<?php
/**
 * roles.php
 * 
 * Roles page controller
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

/**
 * ========================================================================
 * Load controller stuff
 */

/**
 * ========================================================================
 * Initialize variables
 */
$rolesData['txt_name'] = '';
$rolesData['txt_description'] = '';

/**
 * ========================================================================
 * Process form
 */

/**
 * ,--------,
 * | Create |
 * '--------'
 */
if ( isset($_POST['btn_roleCreate']) )
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
   if (!formInputValid('txt_name', 'required|alpha_numeric_dash') OR
       !formInputValid('txt_description', 'alpha_numeric_dash_blank')) 
   {
      $inputError = true;
      $alertData['text'] = $LANG['roles_alert_created_fail_input'];
   }

   $rolesData['txt_name'] = $_POST['txt_name'];
   if ( isset($_POST['txt_description']) ) $rolesData['txt_description'] = $_POST['txt_description'];
   
   if ($RO->getByName($_POST['txt_name']))
   {
      $inputError = true;
      $alertData['text'] = $LANG['roles_alert_created_fail_duplicate'];
   }
   
   if (!$inputError)
   {
      $RO->name = $rolesData['txt_name'];
      $RO->description = $rolesData['txt_description'];
      $RO->color = 'default';
      $RO->create();

      /**
       * Send notification e-mails to the subscribers of role events
       */
      if ($C->read("emailNotifications"))
      {
         sendRoleEventNotifications("created", $RO->name, $RO->description);
      }
      
      /**
       * Log this event
       */
      $LOG->log("logRole",$L->checkLogin(),"log_role_created", $RO->name." ".$RO->description);
             
      /**
       * Success
       */
      $showAlert = TRUE;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_create_role'];
      $alertData['text'] = $LANG['roles_alert_created'];
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
      $alertData['subject'] = $LANG['btn_create_role'];
      $alertData['help'] = '';
   }
}
/**
 * ,--------,
 * | Delete |
 * '--------'
 */
elseif ( isset($_POST['btn_roleDelete']) )
{
   /**
    * Delete Role
    */
   $RO->delete($_POST['hidden_id']);
   
   /**
    * Delete Role in all permission schemes
    */
   $P->deleteRole($_POST['hidden_id']);
   
   /**
    * Send notification e-mails to the subscribers of role events
    */
   if ($C->read("emailNotifications"))
   {
      sendRoleEventNotifications("deleted", $_POST['hidden_name'], $_POST['hidden_description']);
   }
      
   /**
    * Log this event
    */
   $LOG->log("logRole",$L->checkLogin(),"log_role_deleted", $_POST['hidden_name']);
    
   /**
    * Success
    */
   $showAlert = TRUE;
   $alertData['type'] = 'success';
   $alertData['title'] = $LANG['alert_success_title'];
   $alertData['subject'] = $LANG['btn_delete_role'];
   $alertData['text'] = $LANG['roles_alert_deleted'];
   $alertData['help'] = '';
}

/**
 * ========================================================================
 * Prepare data for the view
 */

/**
 * Default: Get all roles
 */
$rolesData['roles'] = $RO->getAll();
$rolesData['searchRole'] = '';

/**
 * ,--------,
 * | Search |
 * '--------'
 * Adjust users by requested search
*/
if (isset($_POST['btn_search']))
{
   $searchUsers = array();

   if (isset($_POST['txt_searchRole']))
   {
      $searchGroup = sanitize($_POST['txt_searchRole']);
      $rolesData['searchRole'] = $searchRole;
      $rolesData['roles'] = $RO->getAllLike($searchRole);
   }
}



/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
