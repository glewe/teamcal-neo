<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use Jumbojett\OpenIDConnectClient;
use Jumbojett\OpenIDConnectClientException;

/**
 * OIDC Controller
 *
 * Handles the OpenID Connect authentication flow.
 *
 * Action 'oidclogin'    : Redirects the browser to the Identity Provider.
 * Action 'oidccallback' : Receives the IdP response, validates the ID Token,
 *                         looks up the local user account, and sets the session cookie.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     5.3.0
 */
class OidcController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    if (!defined('OIDC_YES') || !OIDC_YES) {
      header('Location: index.php?action=login');
      exit;
    }

    $action = $_GET['action'] ?? '';
    if ($action === 'oidccallback') {
      $this->handleCallback();
    }
    else {
      $this->handleLogin();
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Builds and configures the OpenID Connect client.
   *
   * @return OpenIDConnectClient
   */
  private function buildClient(): OpenIDConnectClient {
    $oidc = new OpenIDConnectClient(OIDC_PROVIDER_URL, OIDC_CLIENT_ID, OIDC_CLIENT_SECRET);
    $oidc->setRedirectURL(OIDC_REDIRECT_URI);
    $oidc->addScope(['openid', 'profile', 'email']);
    return $oidc;
  }

  //---------------------------------------------------------------------------
  /**
   * Initiates the OIDC flow by redirecting to the Identity Provider.
   *
   * The jumbojett library writes the state and nonce to the PHP session and
   * then issues the redirect; execution does not return from authenticate().
   *
   * @return void
   */
  private function handleLogin(): void {
    try {
      $oidc = $this->buildClient();
      $oidc->authenticate(); // redirects to IdP; does not return
    }
    catch (\Throwable $e) {
      $this->logModel->logEvent('logLogin', '', 'log_login_oidc_error', ': ' . $e->getMessage());
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['oidc_error_init'], $this->LANG['oidc_error_init_text']);
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Handles the authorization-code callback from the Identity Provider.
   *
   * Validates state (CSRF), exchanges the code for an ID Token, validates the
   * token (signature, issuer, audience, expiry), looks up the local user
   * account, and on success sets the TeamCal Neo session cookie.
   *
   * @return void
   */
  private function handleCallback(): void {
    try {
      $oidc = $this->buildClient();
      $oidc->authenticate();

      $sub               = (string) ($oidc->getVerifiedClaims('sub') ?? '');
      $preferredUsername = (string) ($oidc->getVerifiedClaims('preferred_username') ?? '');

      if ($sub === '') {
        $this->logModel->logEvent('logLogin', '', 'log_login_oidc_error');
        $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['oidc_error_no_sub'], $this->LANG['oidc_error_no_sub_text']);
        return;
      }

      // Resolve the local account: try permanent sub first, then preferred_username
      $needsSubBinding = false;
      if (!$this->userModel->findByOidcSub($sub)) {
        if ($preferredUsername === '' || !$this->userModel->findByName($preferredUsername)) {
          $this->logModel->logEvent('logLogin', $preferredUsername ?: $sub, 'log_login_oidc_no_account');
          $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['oidc_error_no_account'], $this->LANG['oidc_error_no_account_text']);
          return;
        }
        $needsSubBinding = true;
      }

      // The admin account is local-only — check before binding the sub so no IdP
      // sub is ever written to the admin record
      if ($this->userModel->is_system) {
        $this->logModel->logEvent('logLogin', 'admin', 'log_login_oidc_admin_local_only');
        $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['oidc_error_admin_local_only'], $this->LANG['oidc_error_admin_local_only_text']);
        return;
      }

      // First OIDC login for this user — bind the IdP sub to the local account
      if ($needsSubBinding) {
        $this->userModel->saveOidcSub($this->userModel->username, $sub);
      }

      // Account status checks (same gates as local login, minus password)
      if ($this->userModel->locked) {
        $this->logModel->logEvent('logLogin', $this->userModel->username, 'log_login_locked');
        $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['login_error_3'], $this->LANG['login_error_3_text']);
        return;
      }

      if ($this->userOptionModel->read($this->userModel->username, 'verifycode')) {
        $this->logModel->logEvent('logLogin', $this->userModel->username, 'log_login_not_verified');
        $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['login_error_8'], $this->LANG['login_error_8_text']);
        return;
      }

      // All checks passed — set session cookie and redirect
      $this->loginModel->loginByUsername($this->userModel->username);
      $this->logModel->logEvent('logLogin', $this->userModel->username, 'log_login_success');

      $popups = $this->userMessageModel->getAllPopupByUser($this->userModel->username);
      if (count($popups)) {
        header('Location: index.php?action=messages');
      }
      else {
        header('Location: index.php?action=' . $this->allConfig['homepage']);
      }
      exit;
    }
    catch (OpenIDConnectClientException $e) {
      $this->logModel->logEvent('logLogin', '', 'log_login_oidc_error', ': ' . $e->getMessage());
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['oidc_error_callback'], $this->LANG['oidc_error_callback_text']);
    }
  }
}
