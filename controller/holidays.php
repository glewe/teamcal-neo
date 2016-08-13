<?php
/**
 * holidays.php
 * 
 * Holiday list controller
 *
 * @category TeamCal Neo 
 * @version 0.9.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
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
   // ,--------,
   // | Create |
   // '--------'
   if ( isset($_POST['btn_holCreate']) )
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
      if (!formInputValid('txt_name', 'required|alpha_numeric_dash_blank')) $inputError = true;
      if (!formInputValid('txt_description', 'alpha_numeric_dash_blank')) $inputError = true;
       
      $viewData['txt_name'] = $_POST['txt_name'];
      if ( isset($_POST['txt_description']) ) $viewData['txt_description'] = $_POST['txt_description'];
      
      if (!$inputError)
      {
         $HH = new Holidays();
         $HH->name = $viewData['txt_name'];
         $HH->description = $viewData['txt_description'];
         $HH->create();
   
         //
         // Send notification e-mails to the subscribers of group events
         //
         if ($C->read("emailNotifications"))
         {
            sendHolidayEventNotifications("created", $HH->name, $HH->description);
         }
         
         //
         // Log this event
         //
         $LOG->log("logHoliday",$L->checkLogin(),"log_abs_created", $HH->name);
                
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['btn_create_abs'];
         $alertData['text'] = $LANG['hol_alert_created'];
         $alertData['help'] = '';
      }
      else
      {
         //
         // Input validation failed
         //
         $showAlert = TRUE;
         $alertData['type'] = 'danger';
         $alertData['title'] = $LANG['alert_danger_title'];
         $alertData['subject'] = $LANG['btn_create_abs'];
         $alertData['text'] = $LANG['hol_alert_created_fail'];
         $alertData['help'] = '';
      }
   }
   // ,--------,
   // | Delete |
   // '--------'
   elseif ( isset($_POST['btn_holDelete']) )
   {
      $H->delete($_POST['hidden_id']);
      
      //
      // Send notification e-mails to the subscribers of group events
      //
      if ($C->read("emailNotifications"))
      {
         sendHolidayEventNotifications("deleted", $_POST['hidden_name'], $_POST['hidden_description']);
      }
         
      //
      // Log this event
      //
      $LOG->log("logHoliday",$L->checkLogin(),"log_hol_deleted", $_POST['hidden_name']);
       
      //
      // Success
      //
      $showAlert = TRUE;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_delete_holiday'];
      $alertData['text'] = $LANG['hol_alert_deleted'];
      $alertData['help'] = '';
   }
}
      
//=============================================================================
//
// PREPARE VIEW
//
$viewData['holidays'] = $H->getAll();
asort($viewData['holidays']);

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
