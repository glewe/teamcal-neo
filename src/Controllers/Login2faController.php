<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use RobThree\Auth\TwoFactorAuth;

/**
 * Login 2FA Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     4.3.0
 */
class Login2faController extends BaseController
{
  private array $viewData = [];

  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isset($_SESSION['2fa_user'])) {
      header('Location: index.php?action=login');
      exit;
    }

    $this->viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $this->viewData['showAlerts'] = $this->allConfig['showAlerts'];
    $tfa                          = new TwoFactorAuth('TeamCal Neo');

    $uname = $_SESSION['2fa_user'];
    $pword = $_SESSION['2fa_pword'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (!isset($_POST['totp']) || !preg_match('/^[0-9]{6}$/', $_POST['totp'])) {
        $this->LOG->logEvent('logLogin', $uname, 'log_login_2fa');
        $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['login_error_2fa'], $this->LANG['login_error_2fa_text']);
      }
      else {
        $encryptedSecret = $this->UO->read($uname, 'secret');
        if (substr($encryptedSecret, 0, 3) === 'v2:') {
          $data       = base64_decode(substr($encryptedSecret, 3));
          $ivLen      = openssl_cipher_iv_length('AES-256-CBC');
          $iv         = substr($data, 0, $ivLen);
          $ciphertext = substr($data, $ivLen);
          $key        = hash('sha256', APP_LIC_KEY, true);
          $userSecret = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        }
        else {
          $userSecret = openssl_decrypt($encryptedSecret, 'AES-128-ECB', APP_LIC_KEY);
        }
        $totp = $_POST['totp'];
        if ($tfa->verifyCode($userSecret, $totp)) {
          $this->L->loginUser($uname, $pword);
          $this->LOG->logEvent('logLogin', $uname, 'log_login_success');
          unset($_SESSION['2fa_user'], $_SESSION['2fa_pword']);

          if (count($this->UMSG->getAllPopupByUser($uname))) {
            header('Location: index.php?action=messages');
          }
          else {
            header('Location: index.php?action=' . $this->allConfig['homepage']);
          }
          exit;
        }
        else {
          $this->LOG->logEvent('logLogin', $uname, 'log_login_2fa');
          $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['login_error_2fa'], $this->LANG['login_error_2fa_text']);
        }
      }

      $this->viewData['showAlert'] = true;
      if (isset($_SESSION)) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
    }

    $this->render('login2fa', $this->viewData);
  }
}
