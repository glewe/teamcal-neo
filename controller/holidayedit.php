<?php
/**
 * holidayedit.php
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
$HH = new Holidays(); // for the absence type to be edited

if (isset($_GET['id']))
{
   $missingData = FALSE;
   $id = sanitize($_GET['id']);
   if (!$HH->get($id)) $missingData = TRUE;
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
   $holidayData['id'] = $_POST['hidden_id'];
   $holidayData['name'] = $_POST['txt_name'];
   $holidayData['description'] = $_POST['txt_description'];
   $holidayData['color'] = $_POST['txt_color'];
   $holidayData['bgcolor'] = $_POST['txt_bgcolor'];
   if (isset($_POST['chk_businessday'])) $holidayData['businessday'] = '1'; else $holidayData['businessday'] = '0';
    
   /**
    * Form validation
    */
   $inputError = false;
   if (isset($_POST['btn_holidayUpdate']))
   {
      if (!formInputValid('txt_name', 'required|alpha_numeric_dash_blank')) $inputError = true;
      if (!formInputValid('txt_description', 'alpha_numeric_dash_blank_special')) $inputError = true;
      if (!formInputValid('txt_color', 'required|hexadecimal')) $inputError = true;
      if (!formInputValid('txt_bgcolor', 'required|hexadecimal')) $inputError = true;
   }
    
   if (!$inputError)
   {
      /**
       * ,--------,
       * | Update |
       * '--------'
       */
      if (isset($_POST['btn_holidayUpdate']))
      {
         $id  = $_POST['hidden_id'];
         $HH->name = $_POST['txt_name'];
         $HH->description = $_POST['txt_description'];
         $HH->color = $_POST['txt_color'];
         $HH->bgcolor = $_POST['txt_bgcolor'];
         if (isset($_POST['chk_businessday'])) $HH->businessday = '1'; else $HH->businessday = '0';
         
         $HH->update($id);
         
         /**
          * Send notification e-mails to the subscribers of user events
         */
         if ($C->read("emailNotifications"))
         {
            sendHolidayEventNotifications("changed", $HH->name, $HH->description, $HH->description);
         }
         
         /**
          * Log this event
          */
         $LOG->log("logHoliday",$L->checkLogin(),"log_hol_updated", $HH->name);
          
         /**
          * Success
          */
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['hol_alert_edit'];
         $alertData['text'] = $LANG['hol_alert_edit_success'];
         $alertData['help'] = '';
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
         $alertData['text'] = $LANG['hol_alert_save_failed'];
         $alertData['help'] = '';
      }
   }
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$holidayData['id'] = $HH->id;
$holidayData['name'] = $HH->name;
$holidayData['description'] = $HH->description;
$holidayData['color'] = $HH->color;
$holidayData['bgcolor'] = $HH->bgcolor;
$holidayData['businessday'] = $HH->businessday;

$holidayData['holiday'] = array (
   array ( 'prefix' => 'hol', 'name' => 'name', 'type' => 'text', 'value' => $holidayData['name'], 'maxlength' => '40', 'mandatory' => true, 'error' =>  (isset($inputAlert['name'])?$inputAlert['name']:'') ),
   array ( 'prefix' => 'hol', 'name' => 'description', 'type' => 'text', 'value' => $holidayData['description'], 'maxlength' => '100', 'error' =>  (isset($inputAlert['description'])?$inputAlert['description']:'') ),
   array ( 'prefix' => 'hol', 'name' => 'color', 'type' => 'color', 'value' => $holidayData['color'], 'maxlength' => '6', 'error' =>  (isset($inputAlert['color'])?$inputAlert['color']:'') ),
   array ( 'prefix' => 'hol', 'name' => 'bgcolor', 'type' => 'color', 'value' => $holidayData['bgcolor'], 'maxlength' => '6', 'error' =>  (isset($inputAlert['bgcolor'])?$inputAlert['bgcolor']:'') ),
   array ( 'prefix' => 'hol', 'name' => 'businessday', 'type' => 'check', 'value' => $holidayData['businessday'] ),
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
