<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UserModel;
use RobThree\Auth\TwoFactorAuth;

/**
 * Setup 2FA Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.7.0
 */
class Setup2faController extends BaseController
{
  private array $viewData = [];

  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if ($this->allConfig['disableTfa']) {
      $this->renderAlert('info', $this->LANG['alert_info_title'], $this->LANG['alert_not_enabled_subject'], $this->LANG['alert_not_enabled_text']);
      return;
    }

    $UP          = new UserModel($this->DB->db, $this->CONF);
    $missingData = false;
    $profile     = '';
    if (isset($_GET['profile'])) {
      $profile = sanitize($_GET['profile']);
      if (!$UP->findByName($profile)) {
        $missingData = true;
      }
    }
    else {
      $missingData = true;
    }

    if ($missingData) {
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
      return;
    }

    if ($this->UL->username != $profile) {
      $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    if ($this->UO->read($profile, 'secret')) {
      $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['alert_secret_exists_subject'], $this->LANG['alert_secret_exists_text'], $this->LANG['alert_secret_exists_help']);
      return;
    }

    $tfa    = new TwoFactorAuth('TeamCal Neo');
    $secret = $tfa->createSecret();
    $bcode  = $tfa->getQRCodeImageAsDataUri($this->UL->username, $secret);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;
      if (isset($_POST['btn_verify']) && !formInputValid('txt_totp', 'numeric')) {
        $inputError = true;
      }

      if (!$inputError) {
        if (isset($_POST['btn_verify'])) {
          $secret = $_POST['hidden_s'];
          $totp   = $_POST['txt_totp'];
          if ($tfa->verifyCode($secret, $totp)) {
            $ivLen      = openssl_cipher_iv_length('AES-256-CBC');
            $iv         = random_bytes($ivLen);
            $key        = hash('sha256', APP_LIC_KEY, true);
            $ciphertext = openssl_encrypt($secret, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
            $this->UO->save($profile, 'secret', 'v2:' . base64_encode($iv . $ciphertext));
            $this->LOG->logEvent("logUser", $this->UL->username, "log_user_updated", $UP->username);
            $this->L->logout();
            $this->renderAlert('success', $this->LANG['alert_success_title'], $this->LANG['profile_alert_update'], $this->LANG['setup2fa_alert_success']);
            return;
          }
          else {
            $secret = $_POST['hidden_s'];
            $bcode  = $tfa->getQRCodeImageAsDataUri($this->UL->email, $secret);
            $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['alert_input'], $this->LANG['setup2fa_alert_mismatch']);
          }
        }
        if (isset($_SESSION)) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
      }
      else {
        $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_input'], $this->LANG['setup2fa_alert_input'], $this->LANG['setup2fa_alert_input_help']);
      }
    }

    $this->viewData['profile']  = $profile;
    $this->viewData['fullname'] = $UP->firstname . ' ' . $UP->lastname . ' (' . $UP->username . ')';
    $this->viewData['secret']   = $secret;
    $this->viewData['bcode']    = $bcode;

    $this->render('setup2fa', $this->viewData);
  }
}
