<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use RobThree\Auth\TwoFactorAuth;

/**
 * Login Controller
 *
 * Handles user login.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class LoginController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Executes the login logic.
   *
   * @return void
   */
  public function execute(): void {

    // Load Controller Resources
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];
    $tfa                    = new TwoFactorAuth('TeamCal Neo');

    // Variable Defaults
    $showAlert = false;
    $uname     = '';
    $pword     = '';
    /** @var array<string, string> $alertData */
    $alertData = [];

    // Process Form
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

      // Sanitize input
      $_POST = sanitize($_POST);

      // CSRF token check
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      // Form validation
      // No input errors possible yet as no fields are mandatory besides uname/pword which are checked by loginUser
      // Login
      if (isset($_POST['btn_login'])) {
        if (isset($_POST['uname'])) {
          $uname = $_POST['uname'];
        }
        if (isset($_POST['pword'])) {
          $pword = $_POST['pword'];
        }

        switch ($this->L->loginUser($uname, $pword)) {
          case 0:
            // Successful login
            if ($this->UO->read($uname, 'secret')) {
              // 2FA enabled
              $_SESSION['2fa_user']  = $uname;
              $_SESSION['2fa_pword'] = $pword;
              $this->L->logout();
              header("Location: index.php?action=login2fa");
              exit;
            }
            elseif ($this->allConfig['forceTfa']) {
              // TFA required but not set up
              header("Location: index.php?action=setup2fa&profile=" . $uname);
              break;
            }
            else {
              // Login without TFA
              $this->LOG->logEvent("logLogin", $uname, "log_login_success");
              $popups = $this->UMSG->getAllPopupByUser($uname);
              if (count($popups)) {
                header("Location: index.php?action=messages");
              }
              else {
                header("Location: index.php?action=" . $this->allConfig['homepage']);
              }
              break;
            }

          case 1: // Username or password missing
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_1'];
            $alertData['text'] = $this->LANG['login_error_1_text'];
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_missing");
            break;

          case 2: // Username unknown
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_2'];
            $alertData['text'] = $this->LANG['login_error_2_text'];
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_unknown");
            break;

          case 3: // Account is locked
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_3'];
            $alertData['text'] = $this->LANG['login_error_3_text'];
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_locked");
            break;

          case 4: // Password incorrect 1st time
          case 5: // Password incorrect 2nd or higher time
            $this->U->findByName($uname);
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_4'];
            $alertData['text'] = str_replace('%1%', strval($this->U->bad_logins), $this->LANG['login_error_4_text']);
            $alertData['text'] = str_replace('%2%', $this->allConfig['badLogins'], $alertData['text']);
            $alertData['text'] = str_replace('%3%', $this->allConfig['gracePeriod'], $alertData['text']);
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_pwd");
            break;

          case 6: // Login disabled due to too many bad login attempts
            $now = date("U");
            $this->U->findByName($uname);
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_3'];
            $alertData['text'] = str_replace('%1%', $this->allConfig['gracePeriod'], $this->LANG['login_error_6_text']);
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_attempts");
            break;

          case 7: // Password incorrect (no bad login count)
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_7'];
            $alertData['text'] = $this->LANG['login_error_7_text'];
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_pwd");
            break;

          case 8: // Account not verified
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_3'];
            $alertData['text'] = $this->LANG['login_error_8_text'];
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_not_verified");
            break;

          case 90: // LDAP error: Extension missing
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_90'];
            $alertData['text'] = $this->LANG['login_error_90_text'];
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_ldap_extension_missing");
            break;

          case 91: // LDAP error: password missing
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_91'];
            $alertData['text'] = $this->LANG['login_error_1_text'];
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_ldap_pwd_missing");
            break;

          case 92: // LDAP error: bind failed
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_92'];
            $alertData['text'] = $this->LANG['login_error_92_text'];
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_ldap_bind_failed");
            break;

          case 93: // LDAP error: Unable to connect
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_93'];
            $alertData['text'] = $this->LANG['login_error_93_text'];
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_ldap_connect_failed");
            break;

          case 94: // LDAP error: Start of TLS encryption failed
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_94'];
            $alertData['text'] = $this->LANG['login_error_94_text'];
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_ldap_tls_failed");
            break;

          case 95: // LDAP error: Username not found
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_95'];
            $alertData['text'] = $this->LANG['login_error_2_text'];
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_ldap_username");
            break;

          case 96: // LDAP error: LDAP search bind failed
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['login_error_96'];
            $alertData['text'] = $this->LANG['login_error_96_text'];
            $alertData['help'] = '';
            $this->LOG->logEvent("logLogin", $uname, "log_login_ldap_search_bind_failed");
            break;

          default:
            break;
        }
      }
      // Renew CSRF token
      if (isset($_SESSION)) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
    }

    // Prepare Alert
    $alertHtml = '';
    if ($showAlert && ($viewData['showAlerts'] !== 'none')) {
      if ($viewData['showAlerts'] === 'all' || ($viewData['showAlerts'] === 'warnings' && ($alertData['type'] === 'warning' || $alertData['type'] === 'danger'))) {
        $alertHtml = createAlertBox($alertData);
      }
    }

    // Show View
    $this->render('login', [
      'viewData'   => $viewData,
      'alertData'  => $alertData,
      'showAlert'  => $showAlert,
      'alertHtml'  => $alertHtml, // New variable
      'uname'      => $uname,
      'controller' => 'login',
      'CONF'       => $this->CONF,
      'LANG'       => $this->LANG,
      'csrf_token' => $_SESSION['csrf_token'] ?? ''
    ]);
  }
}
