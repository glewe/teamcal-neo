<?php
/**
 * register.php
 * 
 * Register page controller
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
 * Check URL params
 */

/**
 * ========================================================================
 * Check if allowed
 */
if (!$C->read('allowRegistration'))
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
$UR = new Users(); // for the profile to be registered
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
    * Form validation
    */
   $inputError = false;
   if (!formInputValid('txt_username', 'required|alpha_numeric')) $inputError = true;
   if (!formInputValid('txt_lastname', 'required|alpha_numeric_dash')) $inputError = true;
   if (!formInputValid('txt_firstname', 'required|alpha_numeric_dash')) $inputError = true;
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
      /**
       * ,----------,
       * | Register |
       * '----------'
       */
      if (isset($_POST['btn_register']))
      {
         /**
          * Personal
          */
         $UR->username = $_POST['txt_username'];
         $UR->lastname = $_POST['txt_lastname'];
         $UR->firstname = $_POST['txt_firstname'];
         $UR->email = $_POST['txt_email'];
          
         /**
          * Account
          */
         $UR->role = 'User';
         $UR->locked = '0';
         $UR->hidden = '0';
         $UR->onhold = '0';
         $UR->verify = '1';
         $UR->bad_logins = '0';
         $UR->grace_start = '0000-00-00 00:00:00.000000';
         $UR->created = date('YmdHis');

         /**
          * Password
          */
         $UR->password = crypt($_POST['txt_password'], $CONF['salt']);
         $UR->last_pw_change = date('YmdHis');
          
         $UR->create();
         $UO->save($_POST['txt_username'], 'avatar', 'noavatar_male.png');
         
         /**
          * Verify code
          */
         $alphanum = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
         $verifycode = substr(str_shuffle($alphanum), 0, 32);
         $UO->create($U->username, "verifycode", $verifycode);
          
         /**
          * Send notification to user
          */
         sendAccountRegisteredMail($UR->email, $UR->username, $UR->lastname, $UR->firstname, $verifycode);
         
         /**
          * Log this event
          */
         $LOG->log("logUser",$L->checkLogin(),"log_user_registered", $UR->username);
         
         /**
          * Success
          */
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['register_title'];
         $alertData['text'] = $LANG['register_alert_success'];
         $alertData['help'] = '';
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
      $alertData['text'] = $LANG['register_alert_failed'];
      $alertData['help'] = '';
   }
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$LANG['profile_password_comment'] .= $LANG['password_rules_'.$C->read('pwdStrength')];
$registerData['personal'] = array (
   array ( 'prefix' => 'register', 'name' => 'username', 'type' => 'text', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' =>  (isset($inputAlert['username'])?$inputAlert['username']:'') ),
   array ( 'prefix' => 'register', 'name' => 'lastname', 'type' => 'text', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' =>  (isset($inputAlert['lastname'])?$inputAlert['lastname']:'') ),
   array ( 'prefix' => 'register', 'name' => 'firstname', 'type' => 'text', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' =>  (isset($inputAlert['firstname'])?$inputAlert['firstname']:'') ), 
   array ( 'prefix' => 'register', 'name' => 'email', 'type' => 'text', 'value' => '', 'maxlength' => '80', 'mandatory' => true, 'error' =>  (isset($inputAlert['email'])?$inputAlert['email']:'') ),
   array ( 'prefix' => 'register', 'name' => 'password', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' =>  (isset($inputAlert['password'])?$inputAlert['password']:'') ),
   array ( 'prefix' => 'register', 'name' => 'password2', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'mandatory' => true, 'error' =>  (isset($inputAlert['password2'])?$inputAlert['password2']:'') ),
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
