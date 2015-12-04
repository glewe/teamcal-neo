<?php
/**
 * messages.php
 * 
 * Messages page controller
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

/**
 * ========================================================================
 * Load controller stuff
 */

/**
 * ========================================================================
 * Initialize variables
 */
$msgData = $MSG->getAllByUser($UL->username);

/**
 * ========================================================================
 * Process form
 */
/**
 * ,---------,
 * | Confirm |
 * '---------'
 */
if (isset($_POST['btn_confirm']) )
{
   $UMSG->setSilent($_POST['msgId']);
   
   $LOG->log("logMessages", $UL->username, "log_msg_confirmed", $_POST['msgId']);
   header("Location: index.php?action=".$controller);
}
/**
 * ,-------------,
 * | Confirm all |
 * '-------------'
 */
else if (isset($_POST['btn_confirm_all']))
{
   $UMSG->setSilentByUser($UL->username);
      
   /**
    * Log this event
    */
   $LOG->log("logMessages", $UL->username, "log_msg_all_confirmed_by", $UL->username);
   header("Location: index.php?action=".$controller);
}
/**
 * ,--------,
 * | Delete |
 * '--------'
 */
else if (isset($_POST['btn_delete']) )
{
   $UMSG->delete($_POST['umId']);
   deleteOrphanedMessages();
    
   $LOG->log("logMessages", $UL->username, "log_msg_deleted", $_POST['msgId']);
   header("Location: index.php?action=".$controller);
}
/**
 * ,------------,
 * | Delete all |
 * '------------'
 */
else if (isset($_POST['btn_delete_all']) )
{
   $UMSG->deleteByUser($UL->username);
   deleteOrphanedMessages();
    
   $LOG->log("logMessages", $UL->username, "log_msg_all_deleted", $UL->username);
   header("Location: index.php?action=".$controller);
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
