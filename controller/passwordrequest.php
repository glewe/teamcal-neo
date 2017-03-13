<?php
/**
 * passwordrequest.php
 * 
 * Password request controller
 *
 * @category TeamCal Neo 
 * @version 1.3.007
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo '<script type="text/javascript">alert("Debug: ");</script>';

//=============================================================================
//
// VARIABLE DEFAULTS
//
$showAlert = FALSE;
$viewData['email'] = '';
$viewData['multipleUsers'] = false;

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
   if (!formInputValid('txt_email', 'required|email')) $inputError = true;
    
   if (!$inputError)
   {
      $email = $_POST['txt_email'];
      $viewData['email'] = $email;
      
      // ,-------,
      // | Reset |
      // '-------'
      if (isset($_POST['btn_request_password']))
      {
         if ($pwdUsers = $U->getAllForEmail($email))
         {
            if (count($pwdUsers) == 1)
            {
               //
               // One user found with the given email address. Create a token.
               //
               $token = hash('md5','PasswordResetRequestFor'.$pwdUsers[0]['username']);
               // To be retrieved later with:
               // $query = "SELECT * FROM users where md5(CONCAT('PasswordResetRequestFor',username))='".$token."'";
               
               sendPasswordResetMail($pwdUsers[0]['email'], $pwdUsers[0]['username'], $pwdUsers[0]['lastname'], $pwdUsers[0]['firstname'], $token);

               //
               // Success
               //
               $showAlert = TRUE;
               $alertData['type'] = 'success';
               $alertData['title'] = $LANG['alert_success_title'];
               $alertData['subject'] = $LANG['pwdreq_title'];
               $alertData['text'] = $LANG['pwdreq_alert_success'];
               $alertData['help'] = '';
            }
            else 
            {
               if (isset($_POST['opt_user']))
               {
                  $pwdUser = $U->findByName($_POST['opt_user']);
                  $token = hash('md5','PasswordResetRequestFor'.$U->username);
                  $expiryDateTime = date('YmdHis', strtotime(date('YmdHis') . ' +1 day'));
                  $UO->save($U->username, 'pwdTokenExpiry', $expiryDateTime);
                  sendPasswordResetMail($U->email, $U->username, $U->lastname, $U->firstname, $token);
                  
                  //
                  // Success
                  //
                  $showAlert = TRUE;
                  $alertData['type'] = 'success';
                  $alertData['title'] = $LANG['alert_success_title'];
                  $alertData['subject'] = $LANG['pwdreq_title'];
                  $alertData['text'] = $LANG['pwdreq_alert_success'];
                  $alertData['help'] = '';
               }
               else 
               {
                  $viewData['multipleUsers'] = true;
                  $viewData['pwdUsers'] = $pwdUsers;
               }
            }
         }
         else 
         {
            //
            // Email not found
            //
            $showAlert = TRUE;
            $alertData['type'] = 'warning';
            $alertData['title'] = $LANG['alert_warning_title'];
            $alertData['subject'] = $LANG['pwdreq_alert_notfound'];
            $alertData['text'] = $LANG['pwdreq_alert_notfound_text'];
            $alertData['help'] = '';
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
      $alertData['text'] = $LANG['pwdreq_alert_failed'];
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
