<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;



/**
 * Password Request Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class PasswordRequestController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    $viewData                  = [];
    $viewData['pageHelp']      = $this->allConfig['pageHelp'];
    $viewData['showAlerts']    = $this->allConfig['showAlerts'];
    $viewData['email']         = '';
    $viewData['multipleUsers'] = false;

    $alertData = [];
    $showAlert = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;
      if (!formInputValid('txt_email', 'required|email')) {
        $inputError = true;
      }

      if (!$inputError) {
        $email             = $_POST['txt_email'];
        $viewData['email'] = $email;

        if (isset($_POST['btn_request_password'])) {
          if ($pwdUsers = $this->U->getAllForEmail($email)) {
            if (count($pwdUsers) === 1) {
              $token          = bin2hex(random_bytes(32));
              $expiryDateTime = date('YmdHis', strtotime(date('YmdHis') . ' +1 day'));
              $this->UO->save($pwdUsers[0]['username'], 'pwdToken', $token);
              $this->UO->save($pwdUsers[0]['username'], 'pwdTokenExpiry', $expiryDateTime);
              $mailError = '';
              sendPasswordResetMail($pwdUsers[0]['email'], $pwdUsers[0]['username'], $pwdUsers[0]['lastname'], $pwdUsers[0]['firstname'], $token, $mailError);
              $this->LOG->logEvent("logUser", $this->UL->username, "log_user_pwd_request", $pwdUsers[0]['username']);

              $showAlert            = true;
              $alertData['type']    = (empty($mailError)) ? 'success' : 'warning';
              $alertData['title']   = (empty($mailError)) ? $this->LANG['alert_success_title'] : $this->LANG['alert_warning_title'];
              $alertData['subject'] = $this->LANG['pwdreq_title'];
              $alertData['text']    = $this->LANG['pwdreq_alert_success'];
              if (!empty($mailError)) {
                $alertData['text'] .= '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
              }
              $alertData['help'] = (empty($mailError)) ? '' : $this->LANG['contact_administrator'];
            }
            else {
              if (isset($_POST['opt_user'])) {
                $pwdUser        = $this->U->findByName($_POST['opt_user']);
                $token          = bin2hex(random_bytes(32));
                $expiryDateTime = date('YmdHis', strtotime(date('YmdHis') . ' +1 day'));
                $this->UO->save($this->U->username, 'pwdToken', $token);
                $this->UO->save($this->U->username, 'pwdTokenExpiry', $expiryDateTime);
                $mailError = '';
                sendPasswordResetMail($this->U->email, $this->U->username, $this->U->lastname, $this->U->firstname, $token, $mailError);
                $this->LOG->logEvent("logUser", $this->UL->username, "log_user_pwd_request", $this->U->username);

                $showAlert            = true;
                $alertData['type']    = (empty($mailError)) ? 'success' : 'warning';
                $alertData['title']   = (empty($mailError)) ? $this->LANG['alert_success_title'] : $this->LANG['alert_warning_title'];
                $alertData['subject'] = $this->LANG['pwdreq_title'];
                $alertData['text']    = $this->LANG['pwdreq_alert_success'];
                if (!empty($mailError)) {
                  $alertData['text'] .= '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
                }
                $alertData['help'] = (empty($mailError)) ? '' : $this->LANG['contact_administrator'];
              }
              else {
                $viewData['multipleUsers'] = true;
                $viewData['pwdUsers']      = $pwdUsers;
              }
            }
          }
          else {
            $showAlert            = true;
            $alertData['type']    = 'warning';
            $alertData['title']   = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['pwdreq_alert_notfound'];
            $alertData['text']    = $this->LANG['pwdreq_alert_notfound_text'];
            $alertData['help']    = '';
          }
        }

        if (isset($_SESSION)) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
      }
      else {
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_input'];
        $alertData['text']    = $this->LANG['pwdreq_alert_failed'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $this->render('passwordrequest', $viewData);
  }
}
