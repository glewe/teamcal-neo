<?php
/**
 * verify.php
 * 
 * Verify page controller
 *
 * @category TeamCal Neo 
 * @version 0.9.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

//=============================================================================
//
// CHECK URL PARAMETERS
//
$missingData = false;

if (  !isset ($_GET['verify']) OR 
      !isset ($_GET['username']) OR 
      strlen($_GET['verify'])<>32 OR 
      !in_array($_GET['username'],$U->getUsernames()) 
   ) 
{
   $missingData = true;
}

if ($missingData)
{
   //
   // URL param fail
   //
   $alertData['type'] = 'danger';
   $alertData['title'] = $LANG['alert_danger_title'];
   $alertData['subject'] = $LANG['alert_no_data_subject'];
   $alertData['text'] = $LANG['alert_no_data_text'];
   $alertData['help'] = $LANG['alert_no_data_help'];
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
$UA = new Users(); // Used for admin user
$UA->findByName("admin");

//=============================================================================
//
// PROCESS URL
//
$ruser = trim($_GET['username']);
$rverify = trim($_GET['verify']);

if ($fverify = $UO->read($ruser, "verifycode")) 
{
   if ($fverify == $rverify) 
   {
      //
      // Found the user and a matching verify code
      //
      $UO->deleteUserOption($ruser, "verifycode");
      $U->findByName($ruser);
      $fullname = $U->firstname . " " . $U->lastname;
      
      if ($C->read("adminApproval")) 
      {
         //
         // Success but admin needs to approve. Send mail to admin.
         //
         sendAccountNeedsApprovalMail($UA->email, $U->username, $U->lastname, $U->firstname);
          
         //
         // Log this event
         //
         $LOG->log("logRegistration", $U->username, "log_user_verify_approval", $U->username . " (" . $fullname . ")");
         
         //
         // Success but approval needed
         //
         $showAlert = TRUE;
         $alertData['type'] = 'info';
         $alertData['title'] = $LANG['alert_info_title'];
         $alertData['subject'] = $LANG['alert_reg_subject'];
         $alertData['text'] = $LANG['alert_reg_approval_needed'];
         $alertData['help'] = '';
      }
      else 
      {
         //
         // Success and no approval needed. Unlock and unhide user.
         //
         $U->unlock($U->username);
         $U->unverify($U->username);
         
         //
         // Log this event
         //
         $LOG->log("logRegistration", $U->username, "log_user_verify_unlocked", $U->username . " (" . $fullname . ")");
         
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['alert_reg_subject'];
         $alertData['text'] = $LANG['alert_reg_successful'];
         $alertData['help'] = '';
      }
   }
   else 
   {
      //
      // Verify code mismatch
      //
      sendAccountVerificationMismatchMail($UA->email, $ruser, $fverify, $rverify);
      
      //
      // Log this event
      //
      $LOG->log("logRegistration", $U->username, "log_user_verify_mismatch", $U->username . " (" . $fullname . "): ".$rverify."<>".$rverify);
      
      //
      // Verify code mismatch
      //
      $showAlert = TRUE;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['alert_reg_subject'];
      $alertData['text'] = $LANG['alert_reg_mismatch'];
      $alertData['help'] = '';
   }
}
else 
{
   //
   // User or verify code does not exist
   //
   if (!$U->findByName($ruser)) 
   {
      //
      // Log this event
      //
      $LOG->log("logRegistration", $ruser, "log_user_verify_usr_notexist", $ruser." : ".$rverify);
      
      //
      // Failed
      //
      $showAlert = TRUE;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['alert_reg_subject'];
      $alertData['text'] = $LANG['alert_reg_no_user'];
      $alertData['help'] = '';
   }
   else 
   {
      //
      // Log this event
      //
      $LOG->log("logRegistration", $ruser, "log_user_verify_code_notexist", $ruser . " : ".$rverify);
      
      //
      // Failed
      //
      $showAlert = TRUE;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['alert_reg_subject'];
      $alertData['text'] = $LANG['alert_reg_no_vcode'];
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
