<?php
if (!defined('VALID_ROOT')) {
  exit('');
}

/**
 * Login Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Controllers
 * @since 3.0.0
 */

use RobThree\Auth\TwoFactorAuth;

//=============================================================================
//
// LOAD CONTROLLER RESOURCES
//
$tfa = new TwoFactorAuth('TeamCal Neo');

//=============================================================================
//
// VARIABLE DEFAULTS
//
$showAlert = false;
$uname = '';
$pword = '';

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST)) {
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

  if (!$inputError) {
    // ,-------,
    // | Login |
    // '-------'
    if (isset($_POST['btn_login'])) {
      if (isset($_POST['uname'])) {
        $uname = $_POST['uname'];
      }
      if (isset($_POST['pword'])) {
        $pword = $_POST['pword'];
      }

      switch ($L->loginUser($uname, $pword)) {
        case 0:
          //
          // Successful login based on username and password
          // If there is 2FA set up we need to check the second factor as well.
          //
          if ($UO->read($uname, 'secret')) {
            //
            // Remove the login cookie from above for now
            //
            $L->logout();
            $userSecret = openssl_decrypt($UO->read($uname, 'secret'), "AES-128-ECB", APP_LIC_KEY);
            if (isset($_POST['totp'])) {
              $totp = $_POST['totp'];
              $result = $tfa->verifyCode($userSecret, $totp);
              if ($result) {
                //
                // Code matches. Reset the login cookie
                //
                $L->loginUser($uname, $pword);
                $LOG->logEvent("logLogin", $uname, "log_login_success");
                //
                // Check whether we have to force the announcement page to show.
                // This is the case if the user has popup announcements.
                //
                $popups = $UMSG->getAllPopupByUser($uname);
                if (count($popups)) {
                  header("Location: index.php?action=messages");
                } else {
                  header("Location: index.php?action=" . $C->read("homepage"));
                }
                break;
              } else {
                //
                // Code mismatch
                //
                $showAlert = true;
                $alertData['type'] = 'warning';
                $alertData['title'] = $LANG['alert_warning_title'];
                $alertData['subject'] = $LANG['login_error_2fa'];
                $alertData['text'] = $LANG['login_error_2fa_text'];
                $alertData['help'] = '';
                $LOG->logEvent("logLogin", $uname, "log_login_2fa");
                break;
              }
            } else {
              //
              // Authenticator code missing
              //
              $showAlert = true;
              $alertData['type'] = 'warning';
              $alertData['title'] = $LANG['alert_warning_title'];
              $alertData['subject'] = $LANG['login_error_1'];
              $alertData['text'] = $LANG['login_error_1_text'];
              $alertData['help'] = '';
              $LOG->logEvent("logLogin", $uname, "log_login_missing");
              break;
            }
          } else {
            if ($C->read('forceTfa')) {
              //
              // TFA required but no secret for this user yet.
              // First, log out. Then proceed to the 2FA setup page.
              //
              header("Location: index.php?action=setup2fa&profile=" . $uname);
              break;
            } else {
              //
              // Ok to login without TFA
              //
              $LOG->logEvent("logLogin", $uname, "log_login_success");
              //
              // Check whether we have to force the announcement page to show.
              // This is the case if the user has popup announcements.
              //
              $popups = $UMSG->getAllPopupByUser($uname);
              if (count($popups)) {
                header("Location: index.php?action=messages");
              } else {
                header("Location: index.php?action=" . $C->read("homepage"));
              }
              break;
            }
          }

        case 1:
          //
          // Username or password missing
          //
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['login_error_1'];
          $alertData['text'] = $LANG['login_error_1_text'];
          $alertData['help'] = '';
          $LOG->logEvent("logLogin", $uname, "log_login_missing");
          break;

        case 2:
          //
          // Username unknown
          //
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['login_error_2'];
          $alertData['text'] = $LANG['login_error_2_text'];
          $alertData['help'] = '';
          $LOG->logEvent("logLogin", $uname, "log_login_unknown");
          break;

        case 3:
          //
          // Account is locked
          //
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['login_error_3'];
          $alertData['text'] = $LANG['login_error_3_text'];
          $alertData['help'] = '';
          $LOG->logEvent("logLogin", $uname, "log_login_locked");
          break;

        case 4:
        case 5:
          //
          // 4: Password incorrect 1st time
          // 5: Password incorrect 2nd or higher time
          //
          $U->findByName($uname);
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['login_error_4'];
          $alertData['text'] = str_replace('%1%', strval($U->bad_logins), $LANG['login_error_4_text']);
          $alertData['text'] = str_replace('%2%', $C->read("badLogins"), $alertData['text']);
          $alertData['text'] = str_replace('%3%', $C->read("gracePeriod"), $alertData['text']);
          $alertData['help'] = '';
          $LOG->logEvent("logLogin", $uname, "log_login_pwd");
          break;

        case 6:
          //
          // Login disabled due to too many bad login attempts
          //
          $now = date("U");
          $U->findByName($uname);
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['login_error_3'];
          $alertData['text'] = str_replace('%1%', $C->read("gracePeriod"), $LANG['login_error_6_text']);
          $alertData['help'] = '';
          $LOG->logEvent("logLogin", $uname, "log_login_attempts");
          break;

        case 7:
          //
          // Password incorrect (no bad login count)
          //
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['login_error_7'];
          $alertData['text'] = $LANG['login_error_7_text'];
          $alertData['help'] = '';
          $LOG->logEvent("logLogin", $uname, "log_login_pwd");
          break;

        case 8:
          //
          // Account not verified
          //
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['login_error_3'];
          $alertData['text'] = $LANG['login_error_8_text'];
          $alertData['help'] = '';
          $LOG->logEvent("logLogin", $uname, "log_login_not_verified");
          break;

        case 91:
          //
          // LDAP error: password missing
          //
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['login_error_91'];
          $alertData['text'] = $LANG['login_error_1_text'];
          $alertData['help'] = '';
          $LOG->logEvent("logLogin", $uname, "log_login_ldap_pwd_missing");
          break;

        case 92:
          //
          // LDAP error: bind failed
          //
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['login_error_92'];
          $alertData['text'] = $LANG['login_error_92_text'];
          $alertData['help'] = '';
          $LOG->logEvent("logLogin", $uname, "log_login_ldap_bind_failed");
          break;

        case 93:
          //
          // LDAP error: Unable to connect
          //
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['login_error_93'];
          $alertData['text'] = $LANG['login_error_93`_text'];
          $alertData['help'] = '';
          $LOG->logEvent("logLogin", $uname, "log_login_ldap_connect_failed");
          break;

        case 94:
          //
          // LDAP error: Start of TLS encryption failed
          //
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['login_error_94'];
          $alertData['text'] = $LANG['login_error_94_text'];
          $alertData['help'] = '';
          $LOG->logEvent("logLogin", $uname, "log_login_ldap_tls_failed");
          break;

        case 95:
          //
          // LDAP error: Username not found
          //
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['login_error_95'];
          $alertData['text'] = $LANG['login_error_2_text'];
          $alertData['help'] = '';
          $LOG->logEvent("logLogin", $uname, "log_login_ldap_username");
          break;

        case 96:
          //
          // LDAP error: LDAP search bind failed
          //
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['login_error_96'];
          $alertData['text'] = $LANG['login_error_96_text'];
          $alertData['help'] = '';
          $LOG->logEvent("logLogin", $uname, "log_login_ldap_search_bind_failed");
          break;

        default:
          break;
      }
    }
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
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
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
