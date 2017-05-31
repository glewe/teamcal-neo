<?php
/**
 * login.php
 * 
 * Login page controller
 *
 * @category TeamCal Neo 
 * @version 1.5.002
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
$uname = '';
$pword = '';

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
      // ,-------,
      // | Login |
      // '-------'
      if (isset($_POST['btn_login']))
      {
         if (isset($_POST['uname'])) $uname = $_POST['uname'];
         if (isset($_POST['pword'])) $pword = $_POST['pword'];
          
         switch ($L->login($uname, $pword))
         {
            case 0 :
               //
               // Successful login
               //
               $LOG->log("logLogin", $uname, "log_login_success");
                
               //
               // Check for installation file
               //
               if (file_exists("installation.php") && $uname == "admin")
               {
                  //
                  // Installation file still exists.
                  // Add this alert to the admin's notifications
                  //
                  $tstamp = date("YmdHis");
                  $message = "<strong>" . $LANG['err_instfile_title'] . "</strong><br>" . $LANG['err_instfile'] . "<br><br>" . "[".APP_NAME."]</span>";
                  $MSG->create($tstamp, 'admin', $message, '1', 'danger');
               }
               
               //
               // Check whether we have to force the announcement page to show.
               // This is the case if the user has popup announcements.
               //
               $popups = $UMSG->getAllPopupByUser($uname);
               if ( count($popups) )
               {
                  header("Location: index.php?action=messages");
               }
               else 
               { 
                  header("Location: index.php?action=" . $C->read("homepage"));
               }
               break;
            
            case 1 :
               //
               // Username or password missing
               //
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['login_error_1'];
               $alertData['text'] = $LANG['login_error_1_text'];
               $LOG->log("logLogin", $uname, "log_login_missing");
               break;
            
            case 2 :
               //
               // Username unknown
               //
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['login_error_2'];
               $alertData['text'] = $LANG['login_error_2_text'];
               $LOG->log("logLogin", $uname, "log_login_unknown");
               break;
            
            case 3 :
               //
               // Account is locked
               //
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['login_error_3'];
               $alertData['text'] = $LANG['login_error_3_text'];
               $LOG->log("logLogin", $uname, "log_login_locked");
               break;
            
            case 4 :
            case 5 :
               //
               // 4: Password incorrect 1st time
               // 5: Password incorrect 2nd or higher time
               //
               $U->findByName($uname);
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['login_error_4'];
               $alertData['text'] = str_replace('%1%', strval($U->bad_logins), $LANG['login_error_4_text']);
               $alertData['text'] = str_replace('%2%', $C->read("badLogins"), $alertData['text']);
               $alertData['text'] = str_replace('%3%', $C->read("gracePeriod"), $alertData['text']);
               $LOG->log("logLogin", $uname, "log_login_pwd");
               break;
            
            case 6 :
               //
               // Login disabled due to too many bad login attempts
               //
               $now = date("U");
               $U->findByName($uname);
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['login_error_3'];
               $alertData['text'] = str_replace('%1%', $C->read("gracePeriod"), $LANG['login_error_6_text']);
               $LOG->log("logLogin", $uname, "log_login_attempts");
               break;
            
            case 7 :
               //
               // Password incorrect (no bad login count)
               //
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['login_error_7'];
               $alertData['text'] = $LANG['login_error_7_text'];
               $LOG->log("logLogin", $uname, "log_login_pwd");
               break;
            
            case 8 :
               //
               // Account not verified
               //
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['login_error_3'];
               $alertData['text'] = $LANG['login_error_8_text'];
               $LOG->log("logLogin", $uname, "log_login_not_verified");
               break;
            
            case 91 :
               //
               // LDAP error: password missing
               //
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['login_error_91'];
               $alertData['text'] = $LANG['login_error_1_text'];
               $LOG->log("logLogin", $uname, "log_login_ldap_pwd_missing");
               break;
            
            case 92 :
               //
               // LDAP error: bind failed
               //
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['login_error_92'];
               $alertData['text'] = $LANG['login_error_92_text'];
               $LOG->log("logLogin", $uname, "log_login_ldap_bind_failed");
               break;
            
            case 93 :
               //
               // LDAP error: Unable to connect
               //
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['login_error_93'];
               $alertData['text'] = $LANG['login_error_93`_text'];
               $LOG->log("logLogin", $uname, "log_login_ldap_connect_failed");
               break;
            
            case 94 :
               //
               // LDAP error: Start of TLS encryption failed
               //
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['login_error_94'];
               $alertData['text'] = $LANG['login_error_94_text'];
               $LOG->log("logLogin", $uname, "log_login_ldap_tls_failed");
               break;
            
            case 95 :
               //
               // LDAP error: Username not found
               //
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['login_error_95'];
               $alertData['text'] = $LANG['login_error_2_text'];
               $LOG->log("logLogin", $uname, "log_login_ldap_username");
               break;
            
            case 96 :
               //
               // LDAP error: LDAP search bind failed
               //
               $showAlert = TRUE;
               $alertData['type'] = 'warning';
               $alertData['title'] = $LANG['alert_warning_title'];
               $alertData['subject'] = $LANG['login_error_96'];
               $alertData['text'] = $LANG['login_error_96_text'];
               $LOG->log("logLogin", $uname, "log_login_ldap_search_bind_failed");
               break;
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

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
