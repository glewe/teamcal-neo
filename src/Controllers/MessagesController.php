<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;

/**
 * Messages Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class MessagesController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['messages']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    // Check License
    $alertData        = [];
    $showAlert        = false;
    $licExpiryWarning = (int) $this->allConfig['licExpiryWarning'];
    $LIC              = new LicenseModel();
    $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);

    $viewData                = [];
    $viewData['pageHelp']    = $this->allConfig['pageHelp'];
    $viewData['showAlerts']  = $this->allConfig['showAlerts'];
    $viewData['txt_subject'] = '';
    $viewData['txt_message'] = '';
    $viewData['popup']       = 0;

    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];
    $alertData  = [];
    $showAlert  = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_msgCreate'])) {
        $inputError = false;
        if (!formInputValid('txt_subject', 'required|alpha_numeric_dash_blank_special'))
          $inputError = true;
        if (!formInputValid('txt_message', 'required|alpha_numeric_dash_blank_special'))
          $inputError = true;

        $viewData['txt_subject'] = $_POST['txt_subject'];
        $viewData['txt_message'] = $_POST['txt_message'];
        $viewData['popup']       = isset($_POST['chk_popup']) ? 1 : 0;

        if (!$inputError) {
          $timestamp = date('YmdHis');
          $subject   = $viewData['txt_subject'];
          $body      = $viewData['txt_message'];

          // Construct message with signature
          $userAvatar = $this->UO->read($this->UL->username, 'avatar');
          if (!$userAvatar || !file_exists(APP_AVATAR_DIR . $userAvatar)) {
            $userGender = $this->UO->read($this->UL->username, 'gender');
            $userAvatar = 'default_' . $userGender . '.png';
          }
          $signature   = '<img src="' . APP_AVATAR_DIR . $userAvatar . '" width="40" height="40" alt="" style="margin: 0 8px 0 0; text-align:left;"><i>[' . ltrim($this->UL->firstname . " " . $this->UL->lastname) . ']</i>';
          $fullMessage = "<strong>" . $subject . "</strong><br>" . str_replace("\r\n", "<br>", $body) . "<br><br>" . $signature;

          $msgId = $this->MSG->create($timestamp, $fullMessage, 'popup');

          if ($msgId) {
            if (isset($_POST['sel_users'])) {
              foreach ($_POST['sel_users'] as $user) {
                $this->UMSG->add($user, (string) $msgId, (string) $viewData['popup']);
              }
            }
            if (isset($_POST['sel_groups'])) {
              foreach ($_POST['sel_groups'] as $group) {
                $users = $this->UG->getAllForGroup((string) $group);
                foreach ($users as $user) {
                  if (!$this->UMSG->exists($user['username'])) { // Check if user has messages? No, check if this message exists for user?
                    // UserMessage::exists checks if ANY message exists for user.
                    // We want to avoid duplicates. add() handles duplicates.
                    $this->UMSG->add($user['username'], (string) $msgId, (string) $viewData['popup']);
                  }
                }
              }
            }

            $this->LOG->logEvent("logMessage", $this->UL->username, "log_msg_created", $subject);

            $showAlert            = true;
            $alertData['type']    = 'success';
            $alertData['title']   = $this->LANG['alert_success_title'];
            $alertData['subject'] = $this->LANG['btn_create_message'];
            $alertData['text']    = $this->LANG['msg_alert_created'];
            $alertData['help']    = '';
          }
          else {
            $showAlert            = true;
            $alertData['type']    = 'danger';
            $alertData['title']   = $this->LANG['alert_danger_title'];
            $alertData['subject'] = $this->LANG['btn_create_message'];
            $alertData['text']    = $this->LANG['msg_alert_created_fail'];
            $alertData['help']    = '';
          }

          if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
          }
        }
        else {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['btn_create_message'];
          $alertData['text']    = $this->LANG['msg_alert_created_fail'];
          $alertData['help']    = '';
        }
      }
      elseif (isset($_POST['btn_msgDelete'])) {
        $this->UMSG->delete((int) $_POST['hidden_id']);
        $this->LOG->logEvent("logMessage", $this->UL->username, "log_msg_deleted", $_POST['hidden_subject']);

        $showAlert            = true;
        $alertData['type']    = 'success';
        $alertData['title']   = $this->LANG['alert_success_title'];
        $alertData['subject'] = $this->LANG['btn_delete_message'];
        $alertData['text']    = $this->LANG['msg_alert_deleted'];
        $alertData['help']    = '';
      }
      elseif (isset($_POST['btn_msgConfirm'])) {
        $this->UMSG->setSilent((int) $_POST['hidden_id']);
        $this->LOG->logEvent("logMessage", $this->UL->username, "log_msg_confirmed", $_POST['hidden_subject']);

        $showAlert            = true;
        $alertData['type']    = 'success';
        $alertData['title']   = $this->LANG['alert_success_title'];
        $alertData['subject'] = $this->LANG['btn_confirm_message'];
        $alertData['text']    = $this->LANG['msg_alert_confirmed'];
        $alertData['help']    = '';
      }
      elseif (isset($_POST['btn_delete_all'])) {
        $this->UMSG->deleteByUser($this->UL->username);
        $this->LOG->logEvent("logMessage", $this->UL->username, "log_msg_deleted", "All messages");

        $showAlert            = true;
        $alertData['type']    = 'success';
        $alertData['title']   = $this->LANG['alert_success_title'];
        $alertData['subject'] = $this->LANG['btn_delete_all'];
        $alertData['text']    = $this->LANG['msg_alert_deleted'];
        $alertData['help']    = '';
      }
      elseif (isset($_POST['btn_confirm_all'])) {
        $this->UMSG->setSilentByUser($this->UL->username);
        $this->LOG->logEvent("logMessage", $this->UL->username, "log_msg_confirmed", "All messages");

        $showAlert            = true;
        $alertData['type']    = 'success';
        $alertData['title']   = $this->LANG['alert_success_title'];
        $alertData['subject'] = $this->LANG['btn_confirm_all'];
        $alertData['text']    = $this->LANG['msg_alert_confirmed'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $viewData['messages'] = $this->MSG->getAll();
    $viewData['msgData']  = $this->MSG->getAllByUser($this->UL->username); // Mapped for Twig
    $viewData['users']    = $this->U->getAll();
    $viewData['groups']   = $this->G->getAll();

    $this->render('messages', $viewData);
  }
}
