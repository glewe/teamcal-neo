<?php
/**
 * groupedit.php
 * 
 * Group edit page controller
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
 * Check URL params
 */
$GG = new Groups(); // for the profile to be created or updated
if (isset($_GET['id']))
{
   $missingData = FALSE;
   $id = sanitize($_GET['id']);
   if (!$GG->getById($id)) $missingData = TRUE;
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
}

/**
 * ========================================================================
 * Load controller stuff
 */
$groupData['id'] = $GG->id;
$groupData['name'] = $GG->name;
$groupData['description'] = $GG->description;

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
   $groupData['id'] = $_POST['hidden_id'];
   $groupData['name'] = $_POST['txt_name'];
   $groupData['description'] = $_POST['txt_description'];
     
   /**
    * Form validation
    */
   $inputError = false;
   if (isset($_POST['btn_groupUpdate']))
   {
      if (!formInputValid('txt_name', 'required|alpha_numeric_dash')) $inputError = true;
      if (!formInputValid('txt_description', 'alpha_numeric_dash_blank')) $inputError = true;
   }
    
   if (!$inputError)
   {
      /**
       * ,--------,
       * | Update |
       * '--------'
       */
      if (isset($_POST['btn_groupUpdate']))
      {
         $GG->name = $_POST['txt_name'];
         $GG->description = $_POST['txt_description'];
          
         $GG->update($_POST['hidden_id']);
          
         /**
          * Send notification e-mails to the subscribers of user events
          */
         if ($C->read("emailNotifications"))
         {
            sendGroupEventNotifications("changed", $GG->name, $GG->description);
         }
          
         /**
          * Log this event
          */
         $LOG->log("logGroup",$L->checkLogin(),"log_group_updated", $GG->name);
          
         /**
          * Success
          */
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['group_alert_edit'];
         $alertData['text'] = $LANG['group_alert_edit_success'];
         $alertData['help'] = '';
         
         /**
          * Load new info for the view
          */
         $groupData['name'] = $GG->name;
         $groupData['description'] = $GG->description;
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
      $alertData['text'] = $LANG['group_alert_save_failed'];
      $alertData['help'] = '';
   }
}

/**
 * ========================================================================
 * Prepare data for the view
 */

$groupData['group'] = array (
   array ( 'prefix' => 'group', 'name' => 'name', 'type' => 'text', 'value' => $groupData['name'], 'maxlength' => '40', 'mandatory' => true, 'error' =>  (isset($inputAlert['name'])?$inputAlert['name']:'') ),
   array ( 'prefix' => 'group', 'name' => 'description', 'type' => 'text', 'value' => $groupData['description'], 'maxlength' => '100', 'error' =>  (isset($inputAlert['description'])?$inputAlert['description']:'') ),
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
