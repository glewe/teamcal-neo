<?php
/**
 * useradd.php
 * 
 * Add user page controller
 *
 * @category TeamCal Neo 
 * @version 0.9.006
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
$UP = new Users(); // for the profile to be created
$inputAlert = array();

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
   if (!formInputValid('txt_username', 'required|alpha_numeric')) $inputError = true;
   if (!formInputValid('txt_lastname', 'alpha_numeric_dash')) $inputError = true;
   if (!formInputValid('txt_firstname', 'alpha_numeric_dash')) $inputError = true;
   if (!formInputValid('txt_email', 'required|email')) $inputError = true;
   if (!formInputValid('txt_password', 'required|pwd'.$C->read('pwdStrength'))) $inputError = true;
   if (!formInputValid('txt_password2', 'required|pwd'.$C->read('pwdStrength'))) $inputError = true;
   if (!formInputValid('txt_password2', 'match', 'txt_password'))
   {
      $inputAlert['password2'] = sprintf($LANG['alert_input_match'], $LANG['profile_password2'], $LANG['profile_password']);
      $inputError = true;
   }
    
   if (!$inputError)
   {
      // ,--------,
      // | Create |
      // '--------'
      if (isset($_POST['btn_profileCreate']))
      {
         //
         // Personal
         //
         $UP->username = $_POST['txt_username'];
         $UP->lastname = $_POST['txt_lastname'];
         $UP->firstname = $_POST['txt_firstname'];
         $UP->email = $_POST['txt_email'];
          
         //
         // Account
         //
         $UP->role = 'User';
         $UP->locked = '0';
         $UP->hidden = '0';
         $UP->onhold = '0';
         $UP->verify = '0';
         $UP->bad_logins = '0';
         $UP->grace_start = '0000-00-00 00:00:00.000000';
         $UP->created = date('YmdHis');

         //
         // Default user options
         //
         $UO->save($_POST['txt_username'], 'avatar', 'noavatar_male.png');
         $UO->save($_POST['txt_username'], 'language', 'default');
         $UO->save($_POST['txt_username'], 'theme', 'default');
          
         //
         // Password
         //
         if ( isset($_POST['txt_password']) AND isset($_POST['txt_password2']) AND $_POST['txt_password'] == $_POST['txt_password2'] )
         {
            $UP->password = crypt($_POST['txt_password'], $CONF['salt']);
            $UP->last_pw_change = date('YmdHis');
         }
          
         $UP->create();
          
         //
         // Send notification e-mail to the created uses
         //
         if (isset($_POST['chk_create_mail'])) 
         {
            sendAccountCreatedMail($UP->email, $UP->username, $_POST['txt_password']);
         }
         
         //
         // Send notification e-mails to the subscribers of user events
         //
         if ($C->read("emailNotifications"))
         {
            sendUserEventNotifications("created", $UP->username, $UP->firstname, $UP->lastname);
         }
          
         //
         // Log this event
         //
         $LOG->log("logUser",$L->checkLogin(),"log_user_added", $UP->username);
         
         //
         // Load profile page
         //
         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&profile=" . $UP->username);
         die();
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
      $alertData['text'] = $LANG['profile_alert_save_failed'];
      $alertData['help'] = '';
   }
}

//=============================================================================
//
// PREPARE VIEW
//
$LANG['profile_password_comment'] .= $LANG['password_rules_'.$C->read('pwdStrength')];
$viewData['personal'] = array (
   array ( 'prefix' => 'profile', 'name' => 'username', 'type' => 'text', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' =>  (isset($inputAlert['username'])?$inputAlert['username']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'lastname', 'type' => 'text', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' =>  (isset($inputAlert['lastname'])?$inputAlert['lastname']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'firstname', 'type' => 'text', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' =>  (isset($inputAlert['firstname'])?$inputAlert['firstname']:'') ), 
   array ( 'prefix' => 'profile', 'name' => 'email', 'type' => 'text', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' =>  (isset($inputAlert['email'])?$inputAlert['email']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'password', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' =>  (isset($inputAlert['password'])?$inputAlert['password']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'password2', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' =>  (isset($inputAlert['password2'])?$inputAlert['password2']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'create_mail', 'type' => 'check', 'value' => '0' ),
);

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
