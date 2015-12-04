<?php
/**
 * groups.php
 * 
 * Groups page controller
 *
 * @category TeamCal Neo 
 * @version 0.3.004
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

/**
 * ========================================================================
 * Initialize variables
 */
$groupsData['txt_name'] = '';
$groupsData['txt_description'] = '';

/**
 * ========================================================================
 * Process form
 */

/**
 * ,--------,
 * | Create |
 * '--------'
 */
if ( isset($_POST['btn_groupCreate']) )
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

   $groupsData['txt_name'] = $_POST['txt_name'];
   if ( isset($_POST['txt_description']) ) $groupsData['txt_description'] = $_POST['txt_description'];
   
   if (!$inputError)
   {
      $G->name = $groupsData['txt_name'];
      $G->description = $groupsData['txt_description'];
      $G->create();

      /**
       * Send notification e-mails to the subscribers of group events
       */
      if ($C->read("emailNotifications"))
      {
         sendGroupEventNotifications("created", $G->name, $G->description);
      }
      
      /**
       * Log this event
       */
      $LOG->log("logGroup",$L->checkLogin(),"log_group_created", $G->name." ".$G->description);
             
      /**
       * Success
       */
      $showAlert = TRUE;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_create_group'];
      $alertData['text'] = $LANG['groups_alert_group_created'];
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
      $alertData['subject'] = $LANG['btn_create_group'];
      $alertData['text'] = $LANG['groups_alert_group_created_fail'];
      $alertData['help'] = '';
   }
}
/**
 * ,--------,
 * | Delete |
 * '--------'
 */
elseif ( isset($_POST['btn_groupDelete']) )
{
   $G->delete($_POST['hidden_id']);
   $UG->deleteByGroup($_POST['hidden_id']);
   
   /**
    * Send notification e-mails to the subscribers of group events
    */
   if ($C->read("emailNotifications"))
   {
      sendGroupEventNotifications("deleted", $_POST['hidden_name'], $_POST['hidden_description']);
   }
      
   /**
    * Log this event
    */
   $LOG->log("logGroup",$L->checkLogin(),"log_group_deleted", $_POST['hidden_name']);
    
   /**
    * Success
    */
   $showAlert = TRUE;
   $alertData['type'] = 'success';
   $alertData['title'] = $LANG['alert_success_title'];
   $alertData['subject'] = $LANG['btn_delete_group'];
   $alertData['text'] = $LANG['groups_alert_group_deleted'];
   $alertData['help'] = '';
}

/**
 * ========================================================================
 * Prepare data for the view
 */

/**
 * Default: Get all groups
 */
$groupsData['groups'] = $G->getAll();
$groupsData['searchGroup'] = '';

/**
 * ,--------,
 * | Search |
 * '--------'
 * Adjust users by requested search
*/
if (isset($_POST['btn_search']))
{
   $searchUsers = array();

   if (isset($_POST['txt_searchGroup']))
   {
      $searchGroup = sanitize($_POST['txt_searchGroup']);
      $groupsData['searchGroup'] = $searchGroup;
      $groupsData['groups'] = $G->getAllLike($searchGroup);
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
