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
      !in_array($_GET['username'], $this->U->getUsernames())
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

    $UA = new UserModel($this->DB->db, $this->CONF);
    $UA->findByName("admin");

    $ruser   = trim($_GET['username']);
    $rverify = trim($_GET['verify']);

    $alertData = [];
    $showAlert = false;

    if ($fverify = $this->UO->read($ruser, "verifycode")) {
      $this->U->findByName($ruser);
      $fullname = $this->U->firstname . " " . $this->U->lastname;

      if ($fverify == $rverify) {
        $this->UO->deleteUserOption($ruser, "verifycode");

        if ($this->allConfig['adminApproval']) {
          $this->U->unverify($this->U->username);
          $mailError = '';
          sendAccountNeedsApprovalMail($UA->email, $this->U->username, $this->U->lastname, $this->U->firstname, $mailError);
          $this->LOG->logEvent("logRegistration", $this->U->username, "log_user_verify_approval", $this->U->username . " (" . $fullname . ")");

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
          $this->U->unlock($this->U->username);
          $this->U->unverify($this->U->username);
          $this->LOG->logEvent("logRegistration", $this->U->username, "log_user_verify_unlocked", $this->U->username . " (" . $fullname . ")");

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
        $this->LOG->logEvent("logRegistration", $this->U->username, "log_user_verify_mismatch", $this->U->username . " (" . $fullname . "): " . $rverify . "<>" . $rverify);

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
      if (!$this->U->findByName($ruser)) {
        $this->LOG->logEvent("logRegistration", $ruser, "log_user_verify_usr_notexist", $ruser . " : " . $rverify);
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_reg_subject'];
        $alertData['text']    = $this->LANG['alert_reg_no_user'];
        $alertData['help']    = '';
      }
      else {
        $this->LOG->logEvent("logRegistration", $ruser, "log_user_verify_code_notexist", $ruser . " : " . $rverify);
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
