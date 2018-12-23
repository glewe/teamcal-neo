<?php
/**
 * messageedit.php
 * 
 * Message editor controller
 *
 * @category TeamCal Neo
 * @version 2.0.0
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
if (!isAllowed($CONF['controllers'][$controller]->permission) OR !$C->read("activateMessages"))
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
require_once (WEBSITE_ROOT . '/addons/securimage/securimage.php');

//=============================================================================
//
// VARIABLE DEFAULTS
//
$securimage = new Securimage();

$viewData['msgtype'] = 'popup';
$viewData['contenttype'] = 'info';
$viewData['sendto'] = 'all';
$viewData['sendToGroup'] = array ();
$viewData['sendToUser'] = array ();
$viewData['subject'] = '';
$viewData['text'] = '';
$viewData['luser'] = $UL->username;

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
      // ,------,
      // | Send |
      // '------'
      if (isset($_POST['btn_send']))
      {
         $viewData['msgtype'] = $_POST['opt_msgtype'];
         $viewData['contenttype'] = $_POST['opt_contenttype'];
         $viewData['sendto'] = $_POST['opt_sendto'];
         $viewData['sendToGroup'] = array ();
         if (isset($_POST['sel_sendToGroup']))
         {
            foreach ( $_POST['sel_sendToGroup'] as $group )
               $viewData['sendToGroup'][] = $group;
         }
         $viewData['sendToUser'] = array ();
         if (isset($_POST['sel_sendToUser']))
         {
            foreach ( $_POST['sel_sendToUser'] as $user )
               $viewData['sendToUser'][] = $user;
         }
         $viewData['subject'] = $_POST['txt_subject'];
         $viewData['text'] = $_POST['txt_text'];
         
         //
         // Get Captcha
         //
         if ($securimage->check($_POST['txt_code']) == false)
         {
            //
            // Captcha code wrong
            //
            $showAlert = TRUE;
            $alertData['type'] = 'warning';
            $alertData['title'] = $LANG['alert_warning_title'];
            $alertData['subject'] = $LANG['alert_captcha_wrong'];
            $alertData['text'] = $LANG['alert_captcha_wrong_text'];
            $alertData['help'] = $LANG['alert_captcha_wrong_help'];
         }
         else if (!strlen($_POST['txt_subject']) or !strlen($_POST['txt_text']))
         {
            //
            // No subject and/or text
            //
            $showAlert = TRUE;
            $alertData['type'] = 'warning';
            $alertData['title'] = $LANG['alert_warning_title'];
            $alertData['subject'] = $LANG['msg_no_text_subject'];
            $alertData['text'] = $LANG['msg_no_text_text'];
            $alertData['help'] = '';
         }
         else if ($_POST['opt_msgtype'] == "email" and $C->read("emailNotifications"))
         {
            //
            // Send as e-Mail
            //
            $viewData['msgtype'] = $_POST['opt_msgtype'];
            $viewData['subject'] = $_POST['txt_subject'];
            $viewData['text'] = $_POST['txt_text'];
            $sendMail = false;
            $to = "";
            switch ($_POST['opt_sendto'])
            {
               case "all" :
                  $users = $U->getAll();
                  foreach ( $users as $user )
                  {
                     if (strlen($user['email'])) $to .= $user['email'] . ',';
                  }
                  $to = rtrim($to,','); // remove the last ","
                  $sendMail = true;
                  break;
               
               case "group" :
                  if (isset($_POST['sel_sendToGroup']))
                  {
                     foreach ( $_POST['sel_sendToGroup'] as $gto )
                     {
                        $groupusers = $UG->getAllForGroup($gto);
                        foreach ( $groupusers as $groupuser )
                        {
                           if (strlen($U->getEmail($groupuser))) $to .= $U->getEmail($groupuser) . ',';
                        }
                        $to = rtrim($to,','); // remove the last ","
                     }
                     $sendMail = true;
                  }
                  else
                  {
                     //
                     // No group selected
                     //
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
                     {
                        if (strlen($U->getEmail($uto))) $to .= $U->getEmail($uto) . ",";
                     }
                     $to = rtrim($to,','); // remove the last ","
                     $sendMail = true;
                  }
                  else
                  {
                     //
                     // No user selected
                     //
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
                  //
                  // Log this event
                  //
                  $LOG->log("logMessages", L_USER, "log_msg_email", $UL->username . " -> " . $to);
                  
                  //
                  // E-mail success
                  //
                  $showAlert = TRUE;
                  $alertData['type'] = 'success';
                  $alertData['title'] = $LANG['alert_success_title'];
                  $alertData['subject'] = $LANG['msg_msg_sent'];
                  $alertData['text'] = $LANG['msg_msg_sent_text'];
                  $alertData['help'] = '';
               }
            }
         }
         elseif ($_POST['opt_msgtype'] == "silent" or $_POST['opt_msgtype'] == "popup")
         {
            $msgsent = false;
            //
            // Send as Pop-Up
            //
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
                        $to .= $gto . ",";
                        $groupusers = $UG->getAllForGroup($gto);
                        foreach ( $groupusers as $groupuser )
                        {
                           $UMSG->add($username, $groupuser['username'], $popup);
                        }
                     }
                     $to = rtrim($to,','); // remove the last ","
                     $to .= ')';
                     $msgsent = true;
                  }
                  else
                  {
                     //
                     // No group selected
                     //
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
                        $to .= $uto . ",";
                        if ($U->findByName($uto)) $UMSG->add($uto, $newsid, $popup);
                     }
                     $to = rtrim($to,','); // remove the last ","
                     $to .= ')';
                     $msgsent = true;
                  }
                  else
                  {
                     //
                     // No user selected
                     //
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
               //
               // Log this event
               //
               $LOG->log("logMessage", $UL->username, "log_msg_message", $tstamp . " " . $UL->username . " -> " . $to);
                
               //
               // Success
               //
               $showAlert = TRUE;
               $alertData['type'] = 'success';
               $alertData['title'] = $LANG['alert_success_title'];
               $alertData['subject'] = $LANG['msg_msg_sent'];
               $alertData['text'] = $LANG['msg_msg_sent_text'];
               $alertData['help'] = '';
            }
         }
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
$viewData['groups'] = $G->getAllNames($excludeHidden = TRUE);
$viewData['users'] = $U->getAll();

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
