<?php
/**
 * absences.php
 * 
 * Absence type list page controller
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
$absData['txt_name'] = '';

/**
 * ========================================================================
 * Process form
 */
/**
 * ,--------,
 * | Create |
 * '--------'
 */
if ( isset($_POST['btn_absCreate']) )
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
   if (!formInputValid('txt_name', 'alpha_numeric_dash_blank')) $inputError = true;

   if ( isset($_POST['txt_name']) ) $absData['txt_name'] = $_POST['txt_name'];
   
   if (!$inputError)
   {
      $AA = new Absences();
      $AA->name = $absData['txt_name'];
      $AA->symbol = strtoupper(substr($absData['txt_name'], 0, 1));
      $AA->create();

      /**
       * Send notification e-mails to the subscribers of group events
       */
      if ($C->read("emailNotifications"))
      {
         sendAbsenceEventNotifications("created", $AA->name);
      }
      
      /**
       * Log this event
       */
      $LOG->log("logAbsence",$L->checkLogin(),"log_abs_created", $AA->name);
             
      /**
       * Success
       */
      $showAlert = TRUE;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_create_abs'];
      $alertData['text'] = $LANG['abs_alert_created'];
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
      $alertData['subject'] = $LANG['btn_create_abs'];
      $alertData['text'] = $LANG['abs_alert_created_fail'];
      $alertData['help'] = '';
   }
}
/**
 * ,--------,
 * | Delete |
 * '--------'
 */
elseif ( isset($_POST['btn_absDelete']) )
{
   $T->replaceAbsId($_POST['hidden_id'], '0');
   $AG->unassignAbs($_POST['hidden_id']);
   $A->delete($_POST['hidden_id']);
   
   /**
    * Send notification e-mails to the subscribers of group events
    */
   if ($C->read("emailNotifications"))
   {
      sendAbsenceEventNotifications("deleted", $_POST['hidden_name']);
   }
      
   /**
    * Log this event
    */
   $LOG->log("logAbsence",$L->checkLogin(),"log_abs_deleted", $_POST['hidden_name']);
    
   /**
    * Success
    */
   $showAlert = TRUE;
   $alertData['type'] = 'success';
   $alertData['title'] = $LANG['alert_success_title'];
   $alertData['subject'] = $LANG['btn_delete_abs'];
   $alertData['text'] = $LANG['abs_alert_deleted'];
   $alertData['help'] = '';
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$absData['absences'] = $A->getAll();

/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
