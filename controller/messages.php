<?php
/**
 * messages.php
 * 
 * Messages page controller
 *
 * @category TeamCal Neo
 * @version 2.2.3
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo '<script type="text/javascript">alert("Debug: ");</script>';

//=============================================================================
//
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission) OR !$C->read('activateMessages'))
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
$msgData = $MSG->getAllByUser($UL->username);

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
      // ,---------,
      // | Confirm |
      // '---------'
      if (isset($_POST['btn_confirm']) )
      {
         $UMSG->setSilent($_POST['msgId']);
         
         $LOG->log("logMessages", $UL->username, "log_msg_confirmed", $_POST['msgId']);
         header("Location: index.php?action=".$controller);
      }
      // ,-------------,
      // | Confirm All |
      // '-------------'
      else if (isset($_POST['btn_confirm_all']))
      {
         $UMSG->setSilentByUser($UL->username);
            
         //
         // Log this event
         //
         $LOG->log("logMessages", $UL->username, "log_msg_all_confirmed_by", $UL->username);
         header("Location: index.php?action=".$controller);
      }
      // ,--------,
      // | Delete |
      // '--------'
      else if (isset($_POST['btn_delete']) )
      {
         $UMSG->delete($_POST['msgId']);
         deleteOrphanedMessages();
          
         //
         // Log this event
         //
         $LOG->log("logMessages", $UL->username, "log_msg_deleted", $_POST['msgId']);
         header("Location: index.php?action=".$controller);
      }
      // ,------------,
      // | Delete All |
      // '------------'
      else if (isset($_POST['btn_delete_all']) )
      {
         $UMSG->deleteByUser($UL->username);
         deleteOrphanedMessages();
          
         //
         // Log this event
         //
         $LOG->log("logMessages", $UL->username, "log_msg_all_deleted", $UL->username);
         header("Location: index.php?action=".$controller);
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

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
