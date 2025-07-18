<?php
/**
 * Message editor controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
global $C;
global $CONF;
global $controller;
global $LANG;
global $LOG;
global $MSG;
global $U;
global $UG;
global $UL;
global $UMSG;
global $UO;

//-----------------------------------------------------------------------------
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission) || !$C->read("activateMessages")) {
  $alertData['type'] = 'warning';
  $alertData['title'] = $LANG['alert_alert_title'];
  $alertData['subject'] = $LANG['alert_not_allowed_subject'];
  $alertData['text'] = $LANG['alert_not_allowed_text'];
  $alertData['help'] = $LANG['alert_not_allowed_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//
require_once WEBSITE_ROOT . '/addons/securimage/securimage.php';

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$securimage = new Securimage();
$viewData['msgtype'] = 'popup';
$viewData['contenttype'] = 'info';
$viewData['sendto'] = 'all';
$viewData['sendToGroup'] = array();
$viewData['sendToUser'] = array();
$viewData['subject'] = '';
$viewData['text'] = '';
$viewData['luser'] = $UL->username;

//-----------------------------------------------------------------------------
// PROCESS FORM
//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

  //
  // Sanitize input
  //
  $_POST = sanitize($_POST);

  //
  // CSRF token check
  //
  if (!isset($_POST['csrf_token']) || (isset($_POST['csrf_token']) && $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
    $alertData['type'] = 'warning';
    $alertData['title'] = $LANG['alert_alert_title'];
    $alertData['subject'] = $LANG['alert_csrf_invalid_subject'];
    $alertData['text'] = $LANG['alert_csrf_invalid_text'];
    $alertData['help'] = $LANG['alert_csrf_invalid_help'];
    require_once WEBSITE_ROOT . '/controller/alert.php';
    die();
  }

  //
  // Form validation
  //
  $inputError = false;
  //
  // Validate input data. If something is wrong or missing, set $inputError = true
  //
  if (!$inputError) {
    // ,------,
    // | Send |
    // '------'
    if (isset($_POST['btn_send'])) {
      $viewData['msgtype'] = $_POST['opt_msgtype'];
      $viewData['contenttype'] = $_POST['opt_contenttype'];
      $viewData['sendto'] = $_POST['opt_sendto'];
      $viewData['sendToGroup'] = array();
      if (isset($_POST['sel_sendToGroup'])) {
        foreach ($_POST['sel_sendToGroup'] as $group) {
          $viewData['sendToGroup'][] = $group;
        }
      }
      $viewData['sendToUser'] = array();
      if (isset($_POST['sel_sendToUser'])) {
        foreach ($_POST['sel_sendToUser'] as $user) {
          $viewData['sendToUser'][] = $user;
        }
      }
      $viewData['subject'] = $_POST['txt_subject'];
      $viewData['text'] = $_POST['txt_text'];
      //
      // Get Captcha
      //
      if (!$securimage->check($_POST['txt_code'])) {
        //
        // Captcha code wrong
        //
        $showAlert = true;
        $alertData['type'] = 'warning';
        $alertData['title'] = $LANG['alert_warning_title'];
        $alertData['subject'] = $LANG['alert_captcha_wrong'];
        $alertData['text'] = $LANG['alert_captcha_wrong_text'];
        $alertData['help'] = $LANG['alert_captcha_wrong_help'];
      } elseif (!strlen($_POST['txt_subject']) || !strlen($_POST['txt_text'])) {
        //
        // No subject and/or text
        //
        $showAlert = true;
        $alertData['type'] = 'warning';
        $alertData['title'] = $LANG['alert_warning_title'];
        $alertData['subject'] = $LANG['msg_no_text_subject'];
        $alertData['text'] = $LANG['msg_no_text_text'];
        $alertData['help'] = '';
      } elseif ($_POST['opt_msgtype'] == "email") {
        if ($C->read("emailNotifications")) {
          //
          // Send as e-Mail
          //
          $viewData['msgtype'] = $_POST['opt_msgtype'];
          $viewData['subject'] = $_POST['txt_subject'];
          $viewData['text'] = $_POST['txt_text'];
          $sendMail = false;
          $to = "";
          switch ($_POST['opt_sendto']) {
            case "all":
              $users = $U->getAll();
              foreach ($users as $user) {
                if (strlen($user['email'])) {
                  $to .= $user['email'] . ',';
                }
              }
              $to = rtrim($to, ','); // remove the last ","
              $sendMail = true;
              break;

            case "group":
              if (isset($_POST['sel_sendToGroup'])) {
                foreach ($_POST['sel_sendToGroup'] as $gto) {
                  $groupusers = $UG->getAllForGroup($gto);
                  foreach ($groupusers as $groupuser) {
                    if (strlen($U->getEmail($groupuser))) {
                      $to .= $U->getEmail($groupuser) . ',';
                    }
                  }
                  $to = rtrim($to, ','); // remove the last ","
                }
                $sendMail = true;
              } else {
                //
                // No group selected
                //
                $showAlert = true;
                $alertData['type'] = 'warning';
                $alertData['title'] = $LANG['alert_warning_title'];
                $alertData['subject'] = $LANG['msg_no_group_subject'];
                $alertData['text'] = $LANG['msg_no_group_text'];
                $alertData['help'] = '';
              }
              break;

            case "user":
              if (isset($_POST['sel_sendToUser'])) {
                foreach ($_POST['sel_sendToUser'] as $uto) {
                  if (strlen($U->getEmail($uto))) {
                    $to .= $U->getEmail($uto) . ",";
                  }
                }
                $to = rtrim($to, ','); // remove the last ","
                $sendMail = true;
              } else {
                //
                // No user selected
                //
                $showAlert = true;
                $alertData['type'] = 'warning';
                $alertData['title'] = $LANG['alert_warning_title'];
                $alertData['subject'] = $LANG['msg_no_user_subject'];
                $alertData['text'] = $LANG['msg_no_user_text'];
                $alertData['help'] = '';
              }
              break;

            default:
              break;
          }

          if ($sendMail) {
            if (strlen($UL->email)) {
              $from = ltrim(mb_encode_mimeheader($UL->firstname . " " . $UL->lastname)) . " <" . $UL->email . ">";
            } else {
              $from = '';
            }

            if (sendEmail($to, stripslashes($_POST['txt_subject']), stripslashes($_POST['txt_text']), $from)) {
              //
              // Log this event
              //
              $LOG->logEvent("logMessages", L_USER, "log_msg_email", $UL->username . " -> " . $to);
              //
              // E-mail success
              //
              $showAlert = true;
              $alertData['type'] = 'success';
              $alertData['title'] = $LANG['alert_success_title'];
              $alertData['subject'] = $LANG['msg_msg_sent'];
              $alertData['text'] = $LANG['msg_msg_sent_text'];
              $alertData['help'] = '';
            }
          }
        } else {
          //
          // E-mail notifications are switched off
          //
          $showAlert = true;
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['alert_warning_title'];
          $alertData['subject'] = $LANG['msg_email_off_subject'];
          $alertData['text'] = $LANG['msg_email_off_text'];
          $alertData['help'] = '';
        }
      } elseif ($_POST['opt_msgtype'] == "silent" || $_POST['opt_msgtype'] == "popup") {
        $msgsent = false;
        //
        // Send as Pop-Up
        //
        $tstamp = date("YmdHis");
        $mmsg = str_replace("\r\n", "<br>", $_POST['txt_text']);

        if ($userAvatar = $UO->read($UL->username, 'avatar')) {
          if (!file_exists(APP_AVATAR_DIR . $userAvatar)) {
            $userAvatar = 'default_' . $UO->read($UL->username, 'gender') . '.png';
          }
        } else {
          $userAvatar = 'default_' . $UO->read($UL->username, 'gender') . '.png';
        }
        $signature = '<img src="' . APP_AVATAR_DIR . $userAvatar . '" width="40" height="40" alt="" style="margin: 0 8px 0 0; text-align:left;"><i>[' . ltrim($UL->firstname . " " . $UL->lastname) . ']</i>';
        $message = "<strong>" . $_POST['txt_subject'] . "</strong><br>" . $mmsg . "<br><br>" . $signature;
        $newsid = $MSG->create($tstamp, $message, $_POST['opt_contenttype']);
        if ($_POST['opt_msgtype'] == "popup") {
          $popup = 1;
        } else {
          $popup = 0;
        }

        switch ($_POST['opt_sendto']) {
          case "all":
            $to = "all";
            $usernames = $U->getUsernames();
            foreach ($usernames as $username) {
              $UMSG->add($username, $newsid, $popup);
            }
            $msgsent = true;
            break;

          case "group":
            if (isset($_POST['sel_sendToGroup'])) {
              $to = " Groups (";
              foreach ($_POST['sel_sendToGroup'] as $gto) {
                $to .= $gto . ",";
                $groupusers = $UG->getAllForGroup($G->getId($gto));
                foreach ($groupusers as $groupuser) {
                  $UMSG->add($groupuser['username'], $newsid, $popup);
                }
              }
              $to = rtrim($to, ','); // remove the last ","
              $to .= ')';
              $msgsent = true;
            } else {
              //
              // No group selected
              //
              $showAlert = true;
              $alertData['type'] = 'warning';
              $alertData['title'] = $LANG['alert_warning_title'];
              $alertData['subject'] = $LANG['msg_no_group_subject'];
              $alertData['text'] = $LANG['msg_no_group_text'];
              $alertData['help'] = '';
            }
            break;

          case "user":
            if (isset($_POST['sel_sendToUser'])) {
              $to = " Users (";
              foreach ($_POST['sel_sendToUser'] as $uto) {
                $to .= $uto . ",";
                if ($U->findByName($uto)) {
                  $UMSG->add($uto, $newsid, $popup);
                }
              }
              $to = rtrim($to, ','); // remove the last ","
              $to .= ')';
              $msgsent = true;
            } else {
              //
              // No user selected
              //
              $showAlert = true;
              $alertData['type'] = 'warning';
              $alertData['title'] = $LANG['alert_warning_title'];
              $alertData['subject'] = $LANG['msg_no_user_subject'];
              $alertData['text'] = $LANG['msg_no_user_text'];
              $alertData['help'] = '';
            }
            break;

          default:
            break;
        }

        if ($msgsent) {
          //
          // Log this event
          //
          $LOG->logEvent("logMessage", $UL->username, "log_msg_message", ": " . $UL->username . " -> " . $to);
          //
          // Success
          //
          $showAlert = true;
          $alertData['type'] = 'success';
          $alertData['title'] = $LANG['alert_success_title'];
          $alertData['subject'] = $LANG['msg_msg_sent'];
          $alertData['text'] = $LANG['msg_msg_sent_text'];
          $alertData['help'] = '';
        }
      }
    }
    //
    // Renew CSRF token after successful form processing
    //
    if (isset($_SESSION)) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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


//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$viewData['groups'] = $G->getAllNames($excludeHidden = true);
$viewData['users'] = $U->getAll();

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
