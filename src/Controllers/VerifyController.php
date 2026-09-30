<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UserModel;

/**
 * Verify Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class VerifyController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    $missingData = false;
    if (
      !isset($_GET['verify']) ||
      !isset($_GET['username']) ||
      strlen($_GET['verify']) <> 32 ||
      !in_array($_GET['username'], $this->userModel->getUsernames())
    ) {
      $missingData = true;
    }

    if ($missingData) {
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
      return;
    }

    $viewData               = [];
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];

    $UA = new UserModel($this->dbModel->db, $this->CONF);
    $UA->findByName("admin");

    $ruser   = trim($_GET['username']);
    $rverify = trim($_GET['verify']);

    $alertData = [];
    $showAlert = false;

    if ($fverify = $this->userOptionModel->read($ruser, "verifycode")) {
      $this->userModel->findByName($ruser);
      $fullname = $this->userModel->firstname . " " . $this->userModel->lastname;

      if ($fverify == $rverify) {
        $this->userOptionModel->deleteUserOption($ruser, "verifycode");

        if ($this->allConfig['adminApproval']) {
          $this->userModel->unverify($this->userModel->username);
          $mailError = '';
          sendAccountNeedsApprovalMail($UA->email, $this->userModel->username, $this->userModel->lastname, $this->userModel->firstname, $mailError);
          $this->logModel->logEvent("logRegistration", $this->userModel->username, "log_user_verify_approval", $this->userModel->username . " (" . $fullname . ")");

          $showAlert            = true;
          $alertData['type']    = 'info';
          $alertData['title']   = $this->LANG['alert_info_title'];
          $alertData['subject'] = $this->LANG['alert_reg_subject'];
          $alertData['text']    = $this->LANG['alert_reg_approval_needed'];
          if (!empty($mailError)) {
            $alertData['text'] .= '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
          }
          $alertData['help']    = (empty($mailError)) ? '' : $this->LANG['contact_administrator'];
        }
        else {
          $this->userModel->unlock($this->userModel->username);
          $this->userModel->unverify($this->userModel->username);
          $this->logModel->logEvent("logRegistration", $this->userModel->username, "log_user_verify_unlocked", $this->userModel->username . " (" . $fullname . ")");

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['alert_reg_subject'];
          $alertData['text']    = $this->LANG['alert_reg_successful'];
          $alertData['help']    = '';
        }
      }
      else {
        $mailError = '';
        sendAccountVerificationMismatchMail($UA->email, $ruser, $fverify, $rverify, $mailError);
        $this->logModel->logEvent("logRegistration", $this->userModel->username, "log_user_verify_mismatch", $this->userModel->username . " (" . $fullname . "): " . $rverify . "<>" . $rverify);

        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_reg_subject'];
        $alertData['text']    = $this->LANG['alert_reg_mismatch'];
        if (!empty($mailError)) {
          $alertData['text'] .= '<br><br><strong>' . $this->LANG['log_email_error'] . '</strong><br>' . $mailError;
        }
        $alertData['help']    = (empty($mailError)) ? '' : $this->LANG['contact_administrator'];
      }
    }
    else {
      if (!$this->userModel->findByName($ruser)) {
        $this->logModel->logEvent("logRegistration", $ruser, "log_user_verify_usr_notexist", $ruser . " : " . $rverify);
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_reg_subject'];
        $alertData['text']    = $this->LANG['alert_reg_no_user'];
        $alertData['help']    = '';
      }
      else {
        $this->logModel->logEvent("logRegistration", $ruser, "log_user_verify_code_notexist", $ruser . " : " . $rverify);
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_reg_subject'];
        $alertData['text']    = $this->LANG['alert_reg_no_vcode'];
        $alertData['help']    = '';
      }
    }

    $viewData['alertData'] = $alertData;
    $viewData['showAlert'] = true;

    $this->render('verify', $viewData);
  }
}
