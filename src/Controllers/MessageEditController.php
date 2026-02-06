<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\CaptchaService;



/**
 * Message Edit Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class MessageEditController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['messageedit']->permission) || !$this->allConfig['activateMessages']) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $captchaService = new CaptchaService();

    $viewData                = [];
    $viewData['pageHelp']    = $this->allConfig['pageHelp'];
    $viewData['showAlerts']  = $this->allConfig['showAlerts'];
    $viewData['msgtype']     = 'popup';
    $viewData['contenttype'] = 'info';
    $viewData['sendto']      = 'all';
    $viewData['sendToGroup'] = [];
    $viewData['sendToUser']  = [];
    $viewData['subject']     = '';
    $viewData['text']        = '';
    $viewData['luser']       = $this->UL->username;

    $allUsers         = $this->U->getAll();
    $userEmails       = [];
    $userDisplayNames = [];
    foreach ($allUsers as $user) {
      if (strlen($user['email'])) {
        $userEmails[$user['username']] = $user['email'];
      }
      if ($user['firstname'] != "") {
        $userDisplayNames[$user['username']] = $user['lastname'] . ", " . $user['firstname'];
      }
      else {
        $userDisplayNames[$user['username']] = $user['lastname'];
      }
    }

    $inputAlert = [];
    $alertData  = [];
    $showAlert  = false;
    $to         = '';
    $msgsent    = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_send'])) {
        $viewData['msgtype']     = $_POST['opt_msgtype'];
        $viewData['contenttype'] = $_POST['opt_contenttype'];
        $viewData['sendto']      = $_POST['opt_sendto'];

        if (isset($_POST['sel_sendToGroup'])) {
          foreach ($_POST['sel_sendToGroup'] as $group) {
            $viewData['sendToGroup'][] = $group;
          }
        }
        if (isset($_POST['sel_sendToUser'])) {
          foreach ($_POST['sel_sendToUser'] as $user) {
            $viewData['sendToUser'][] = $user;
          }
        }
        $viewData['subject'] = $_POST['txt_subject'];
        $viewData['text']    = $_POST['txt_text'];

        if (!$captchaService->verifyHoneypot($_POST) || !$captchaService->verifyAnswer($_POST['txt_code'])) {
          $showAlert            = true;
          $alertData['type']    = 'warning';
          $alertData['title']   = $this->LANG['alert_warning_title'];
          $alertData['subject'] = $this->LANG['alert_captcha_wrong'];
          $alertData['text']    = $this->LANG['alert_captcha_wrong_text'];
          $alertData['help']    = $this->LANG['alert_captcha_wrong_help'];
        }
        elseif (!strlen($_POST['txt_subject']) || !strlen($_POST['txt_text'])) {
          $showAlert            = true;
          $alertData['type']    = 'warning';
          $alertData['title']   = $this->LANG['alert_warning_title'];
          $alertData['subject'] = $this->LANG['msg_no_text_subject'];
          $alertData['text']    = $this->LANG['msg_no_text_text'];
          $alertData['help']    = '';
        }
        elseif ($_POST['opt_msgtype'] == "email") {
          if ($this->allConfig['emailNotifications']) {
            $sendMail = false;
            $toEmails = [];
            switch ($_POST['opt_sendto']) {
              case "all":
                $sendMail = true;
                foreach ($userEmails as $uemail) {
                  $toEmails[] = $uemail;
                }
                break;
              case "group":
                if (isset($_POST['sel_sendToGroup'])) {
                  $sendMail = true;
                  foreach ($_POST['sel_sendToGroup'] as $gto) {
                    $groupusers = $this->UG->getAllForGroup((string) $this->G->getId($gto));
                    foreach ($groupusers as $groupuser) {
                      if (isset($userEmails[$groupuser['username']])) {
                        $toEmails[] = $userEmails[$groupuser['username']];
                      }
                    }
                  }
                }
                else {
                  $showAlert            = true;
                  $alertData['type']    = 'warning';
                  $alertData['title']   = $this->LANG['alert_warning_title'];
                  $alertData['subject'] = $this->LANG['msg_no_group_subject'];
                  $alertData['text']    = $this->LANG['msg_no_group_text'];
                  $alertData['help']    = '';
                }
                break;
              case "user":
                if (isset($_POST['sel_sendToUser'])) {
                  $sendMail = true;
                  foreach ($_POST['sel_sendToUser'] as $uto) {
                    if (isset($userEmails[$uto])) {
                      $toEmails[] = $userEmails[$uto];
                    }
                  }
                }
                else {
                  $showAlert            = true;
                  $alertData['type']    = 'warning';
                  $alertData['title']   = $this->LANG['alert_warning_title'];
                  $alertData['subject'] = $this->LANG['msg_no_user_subject'];
                  $alertData['text']    = $this->LANG['msg_no_user_text'];
                  $alertData['help']    = '';
                }
                break;
            }

            if ($sendMail) {
              $from = strlen($this->UL->email) ? ltrim(mb_encode_mimeheader($this->UL->firstname . " " . $this->UL->lastname)) . " <" . $this->UL->email . ">" : '';
              $to   = implode(',', $toEmails);
              $mailError = '';
              if (sendEmail($to, stripslashes($_POST['txt_subject']), stripslashes($_POST['txt_text']), $from, $mailError)) {
                $this->LOG->logEvent("logMessage", $this->UL->username, "log_msg_email", $this->UL->username . " -> " . $to);
                $showAlert            = true;
                $alertData['type']    = 'success';
                $alertData['title']   = $this->LANG['alert_success_title'];
                $alertData['subject'] = $this->LANG['msg_msg_sent'];
                $alertData['text']    = $this->LANG['msg_msg_sent_text'];
                $alertData['help']    = '';
              }
              else {
                $showAlert            = true;
                $alertData['type']    = 'danger';
                $alertData['title']   = $this->LANG['alert_danger_title'];
                $alertData['subject'] = $this->LANG['msg_msg_sent_failed'];
                $alertData['text']    = $this->LANG['msg_msg_sent_failed_text'];
                if (!empty($mailError)) {
                  $alertData['text'] .= '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
                }
                $alertData['help'] = $this->LANG['contact_administrator'];
              }
            }
          }
          else {
            $showAlert            = true;
            $alertData['type']    = 'warning';
            $alertData['title']   = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['msg_email_off_subject'];
            $alertData['text']    = $this->LANG['msg_email_off_text'];
            $alertData['help']    = '';
          }
        }
        elseif ($_POST['opt_msgtype'] == "silent" || $_POST['opt_msgtype'] == "popup") {
          $msgsent = false;
          $tstamp  = date("YmdHis");
          $mmsg    = str_replace("\r\n", "<br>", $_POST['txt_text']);

          $userAvatar = $this->UO->read($this->UL->username, 'avatar');
          if (!$userAvatar || !file_exists(APP_AVATAR_DIR . $userAvatar)) {
            $userGender = $this->UO->read($this->UL->username, 'gender');
            $userAvatar = 'default_' . $userGender . '.png';
          }
          $signature = '<img src="' . APP_AVATAR_DIR . $userAvatar . '" width="40" height="40" alt="" style="margin: 0 8px 0 0; text-align:left;"><i>[' . ltrim($this->UL->firstname . " " . $this->UL->lastname) . ']</i>';
          $message   = "<strong>" . $_POST['txt_subject'] . "</strong><br>" . $mmsg . "<br><br>" . $signature;

          $newsid = $this->MSG->create($tstamp, $message, $_POST['opt_contenttype']);
          $popup  = ($_POST['opt_msgtype'] == "popup") ? '1' : '0';

          switch ($_POST['opt_sendto']) {
            case "all":
              $to = "all";
              $usernames = $this->U->getUsernames();
              foreach ($usernames as $username) {
                $this->UMSG->add($username, (string) $newsid, $popup);
              }
              $msgsent = true;
              break;
            case "group":
              if (isset($_POST['sel_sendToGroup'])) {
                $to = " Groups (";
                foreach ($_POST['sel_sendToGroup'] as $gto) {
                  $to         .= $gto . ",";
                  $groupusers  = $this->UG->getAllForGroup((string) $this->G->getId($gto));
                  foreach ($groupusers as $groupuser) {
                    $this->UMSG->add($groupuser['username'], (string) $newsid, $popup);
                  }
                }
                $to      = rtrim($to, ',') . ')';
                $msgsent = true;
              }
              else {
                $showAlert            = true;
                $alertData['type']    = 'warning';
                $alertData['title']   = $this->LANG['alert_warning_title'];
                $alertData['subject'] = $this->LANG['msg_no_group_subject'];
                $alertData['text']    = $this->LANG['msg_no_group_text'];
                $alertData['help']    = '';
              }
              break;
            case "user":
              if (isset($_POST['sel_sendToUser'])) {
                $to = " Users (";
                foreach ($_POST['sel_sendToUser'] as $uto) {
                  $to .= $uto . ",";
                  if ($this->U->findByName($uto)) {
                    $this->UMSG->add($uto, (string) $newsid, $popup);
                  }
                }
                $to      = rtrim($to, ',') . ')';
                $msgsent = true;
              }
              else {
                $showAlert            = true;
                $alertData['type']    = 'warning';
                $alertData['title']   = $this->LANG['alert_warning_title'];
                $alertData['subject'] = $this->LANG['msg_no_user_subject'];
                $alertData['text']    = $this->LANG['msg_no_user_text'];
                $alertData['help']    = '';
              }
              break;
          }

          if ($msgsent) {
            $this->LOG->logEvent("logMessage", $this->UL->username, "log_msg_message", ": " . $this->UL->username . " -> " . $to);
            $showAlert            = true;
            $alertData['type']    = 'success';
            $alertData['title']   = $this->LANG['alert_success_title'];
            $alertData['subject'] = $this->LANG['msg_msg_sent'];
            $alertData['text']    = $this->LANG['msg_msg_sent_text'];
            $alertData['help']    = '';
          }
        }
      }

      if (isset($_SESSION)) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
    }

    $viewData['alertData']        = $alertData;
    $viewData['showAlert']        = $showAlert;
    $viewData['captcha_question'] = $captchaService->generateQuestion();

    $viewData['groups']           = $this->G->getAllNames();
    $viewData['users']            = $allUsers;
    $viewData['userDisplayNames'] = $userDisplayNames;

    $this->render('messageedit', $viewData);
  }
}
