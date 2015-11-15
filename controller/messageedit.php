<?php
/**
 * messageedit.php
 * 
 * Message editor controller
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
if (!isAllowed($controller) OR !$C->read("activateMessages"))
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
require_once (WEBSITE_ROOT . '/addons/securimage/securimage.php');

/**
 * ========================================================================
 * Initialize variables
 */
$securimage = new Securimage();

$messageData['msgtype'] = 'popup';
$messageData['contenttype'] = 'info';
$messageData['sendto'] = 'all';
$messageData['sendToGroup'] = array ();
$messageData['sendToUser'] = array ();
$messageData['subject'] = '';
$messageData['text'] = '';
$messageData['luser'] = $UL->username;

/**
 * ========================================================================
 * Process form
 */
/**
 * ,------,
 * | Send |
 * '------'
 */
if (isset($_POST['btn_send']))
{
   $messageData['msgtype'] = $_POST['opt_msgtype'];
   $messageData['contenttype'] = $_POST['opt_contenttype'];
   $messageData['sendto'] = $_POST['opt_sendto'];
   $messageData['sendToGroup'] = array ();
   if (isset($_POST['sel_sendToGroup']))
   {
      foreach ( $_POST['sel_sendToGroup'] as $group )
         $messageData['sendToGroup'][] = $group;
   }
   $messageData['sendToUser'] = array ();
   if (isset($_POST['sel_sendToUser']))
   {
      foreach ( $_POST['sel_sendToUser'] as $user )
         $messageData['sendToUser'][] = $user;
   }
   $messageData['subject'] = $_POST['txt_subject'];
   $messageData['text'] = $_POST['txt_text'];
   
   /**
    * Get Captcha
    */
   if ($securimage->check($_POST['txt_code']) == false)
   {
      /**
       * Captcha code wrong
       */
      $showAlert = TRUE;
      $alertData['type'] = 'warning';
      $alertData['title'] = $LANG['alert_warning_title'];
      $alertData['subject'] = $LANG['alert_captcha_wrong'];
      $alertData['text'] = $LANG['alert_captcha_wrong_text'];
      $alertData['help'] = $LANG['alert_captcha_wrong_help'];
   }
   else if (!strlen($_POST['txt_subject']) or !strlen($_POST['txt_text']))
   {
      /**
       * No subject and/or text
       */
      $showAlert = TRUE;
      $alertData['type'] = 'warning';
      $alertData['title'] = $LANG['alert_warning_title'];
      $alertData['subject'] = $LANG['msg_no_text_subject'];
      $alertData['text'] = $LANG['msg_no_text_text'];
      $alertData['help'] = '';
   }
   else if ($_POST['opt_msgtype'] == "email" and $C->read("emailNotifications"))
   {
      /**
       * Send as e-Mail
       */
      $messageData['msgtype'] = $_POST['opt_msgtype'];
      $messageData['subject'] = $_POST['txt_subject'];
      $messageData['text'] = $_POST['txt_text'];
      $sendMail = false;
      $to = "";
      switch ($_POST['opt_sendto'])
      {
         case "all" :
            $users = $U->getAll();
            foreach ( $users as $user )
               if (strlen($user['email'])) $to .= $user['email'] . ',';
            $sendMail = true;
            break;
         
         case "group" :
            if (isset($_POST['sel_sendToGroup']))
            {
               foreach ( $_POST['sel_sendToGroup'] as $gto )
               {
                  $groupusers = $UG->getAllForGroup($gto);
                  foreach ( $groupusers as $groupuser )
                     if (strlen($U->getEmail($groupuser))) $to .= $U->getEmail($groupuser) . ',';
               }
               $to = substr($to, 0, strlen($to) - 2); // remove the last ", "
               $sendMail = true;
            }
            else
            {
               /**
                * No group selected
                */
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['msg_no_group_subject'];
               $alertData['text'] = $LANG['msg_no_group_text'];
               $alertData['help'] = '';
            }
            break;
         
         case "user" :
            if (isset($_POST['sel_sendToUser']))
            {
               foreach ( $_POST['sel_sendToUser'] as $uto )
                  if (strlen($U->getEmail($uto))) $to .= $U->getEmail($uto) . ",";
               $to = substr($to, 0, strlen($to) - 2); // remove the last ", "
               $sendMail = true;
            }
            else
            {
               /**
                * No user selected
                */
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['msg_no_user_subject'];
               $alertData['text'] = $LANG['msg_no_user_text'];
               $alertData['help'] = '';
            }
            break;
      }
      
      if ($sendMail)
      {
         if (strlen($UL->email))
         {
            $from = ltrim(mb_encode_mimeheader($UL->firstname . " " . $UL->lastname)) . " <" . $UL->email . ">";
         }
         else
         {
            $from = '';
         }
         
         if (sendEmail($to, stripslashes($_POST['txt_subject']), stripslashes($_POST['txt_text']), $from))
         {
            /**
             * E-mail success
             */
            $showAlert = TRUE;
            $alertData['type'] = 'success';
            $alertData['title'] = $LANG['alert_success_title'];
            $alertData['subject'] = $LANG['msg_msg_sent'];
            $alertData['text'] = $LANG['msg_msg_sent_text'];
            $alertData['help'] = '';
            $LOG->log("logMessages", $L->checkLogin(), "log_msg_email", $UL->username . " -> " . $to);
         }
      }
   }
   elseif ($_POST['opt_msgtype'] == "silent" or $_POST['opt_msgtype'] == "popup")
   {
      $msgsent = false;
      /**
       * Send as Pop-Up
       */
      $tstamp = date("YmdHis");
      $mmsg = str_replace("\r\n", "<br>", $_POST['txt_text']);
      $message = "<strong>" . $_POST['txt_subject'] . "</strong><br>" . $mmsg . "<br><br>[" . ltrim($UL->firstname . " " . $UL->lastname) . "]";
      $newsid = $MSG->create($tstamp, $message, $_POST['opt_contenttype']);
      
      if ($_POST['opt_msgtype'] == "popup") $popup = 1; else $popup = 0;
      
      switch ($_POST['opt_sendto'])
      {
         case "all" :
            $to = "all";
            $usernames = $U->getUsernames();
            foreach ( $usernames as $username )
            {
               $UMSG->add($username, $newsid, $popup);
            }
            $msgsent = true;
            break;
         
         case "group" :
            if (isset($_POST['sel_sendToGroup']))
            {
               $to = " Groups (";
               foreach ( $_POST['sel_sendToGroup'] as $gto )
               {
                  $to .= $gto . ", ";
                  $groupusers = $UG->getAllForGroup($gto);
                  foreach ( $groupusers as $groupuser )
                  {
                     $UMSG->add($username, $groupuser['username'], $popup);
                  }
               }
               $to = substr($to, 0, strlen($to) - 2); // remove the last ", "
               $to .= ')';
               $msgsent = true;
            }
            else
            {
               /**
                * No group selected
                */
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['msg_no_group_subject'];
               $alertData['text'] = $LANG['msg_no_group_text'];
               $alertData['help'] = '';
            }
            break;
         
         case "user" :
            if (isset($_POST['sel_sendToUser']))
            {
               $to = " Users (";
               foreach ( $_POST['sel_sendToUser'] as $uto )
               {
                  $to .= $uto . ", ";
                  if ($U->findByName($uto)) $UMSG->add($username, $uto, $popup);
               }
               $to = substr($to, 0, strlen($to) - 2); // remove the last ", "
               $to .= ')';
               $msgsent = true;
            }
            else
            {
               /**
                * No user selected
                */
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['msg_no_user_subject'];
               $alertData['text'] = $LANG['msg_no_user_text'];
               $alertData['help'] = '';
            }
            break;
      }
      
      if ($msgsent)
      {
         /**
          * Notification success
          */
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['msg_msg_sent'];
         $alertData['text'] = $LANG['msg_msg_sent_text'];
         $alertData['help'] = '';
         $LOG->log("logMessages", $UL->username, "log_msg_message", $tstamp . " " . $UL->username . " -> " . $to);
      }
   }
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$messageData['groups'] = $G->getAllNames($excludeHidden = TRUE);
$messageData['users'] = $U->getAll();

/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
