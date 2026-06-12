<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: Login page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['login_login'] = 'Login';
$LANG['login_username'] = 'Username:';
$LANG['login_password'] = 'Password:';
$LANG['login_authcode'] = 'Authenticator code:';
$LANG['login_authcode_comment'] = 'In case you have set up a two factor authentication, enter you authenticator code here.';
$LANG['login_error_0'] = 'Login successful';
$LANG['login_error_1'] = 'Username, password or authenticator code missing';
$LANG['login_error_1_text'] = 'Please provide a valid username and password and if necessary a valid authenticator code.';
$LANG['login_error_2'] = 'Username unknown';
$LANG['login_error_2_text'] = 'The username you entered is unknown. Please try again.';
$LANG['login_error_2fa'] = 'Incorrect authentication code';
$LANG['login_error_2fa_text'] = 'A two factor authentication has been set up for your account. The authentication code you entered does not match.';
$LANG['login_error_3'] = 'Account disabled';
$LANG['login_error_3_text'] = 'This account is locked or not approved. Please contact your administrator.';
$LANG['login_error_4'] = 'Password incorrect';
$LANG['login_error_4_text'] = 'This was bad attempt number %1%. After %2% bad attempts your account will be locked for %3% seconds.';
$LANG['login_error_6_text'] = 'This account is on hold due to too many bad login attempts. The grace period ends in %1% seconds.';
$LANG['login_error_7'] = 'Username or password incorrect';
$LANG['login_error_7_text'] = 'The username and/or password you entered are not correct. Please try again.';
$LANG['login_error_8'] = 'Account not verified';
$LANG['login_error_8_text'] = 'Your account is not verified yet. You may have received an E-mail with a verification link.';
$LANG['login_error_90'] = 'LDAP error: Extension missing';
$LANG['login_error_90_text'] = 'The PHP LDAP extension is not loaded. Please enable it in your php.ini configuration.';
$LANG['login_error_91'] = 'LDAP error: Password missing';
$LANG['login_error_92'] = 'LDAP error: Authentication/Bind failed';
$LANG['login_error_92_text'] = 'The LDAP authentication/bind failed. Please try again.';
$LANG['login_error_93'] = 'LDAP error: Unable to connect to LDAP server';
$LANG['login_error_93_text'] = 'The LDAP server connection failed. Please try again.';
$LANG['login_error_94'] = 'LDAP error: Start TLS failed';
$LANG['login_error_94_text'] = 'The LDAP start TLS failed. Please try again.';
$LANG['login_error_95'] = 'LDAP error: Username not found';
$LANG['login_error_96'] = 'LDAP error: Search bind failed';
$LANG['login_error_96_text'] = 'The LDAP search bind failed. Please try again.';

$LANG['login_sso_button'] = 'Login with SSO';
$LANG['login_admin_local'] = 'Administrator login';
$LANG['oidc_error_init'] = 'OIDC: Provider connection error';
$LANG['oidc_error_init_text'] = 'Could not connect to the Identity Provider. Please try again or contact your administrator.';
$LANG['oidc_error_no_sub'] = 'OIDC: Missing subject identifier';
$LANG['oidc_error_no_sub_text'] = 'The Identity Provider did not return a subject identifier (sub claim). Contact your administrator.';
$LANG['oidc_error_no_account'] = 'No local account found';
$LANG['oidc_error_no_account_text'] = 'Your identity was verified at the Identity Provider, but no matching local TeamCal Neo account exists. Contact your administrator.';
$LANG['oidc_local_login_blocked'] = 'SSO login is active';
$LANG['oidc_local_login_blocked_text'] = 'Local login is disabled while SSO is active. Please use the "Login with SSO" button. Only the administrator account can use local login.';
$LANG['oidc_error_admin_local_only'] = 'Admin account is local-only';
$LANG['oidc_error_admin_local_only_text'] = 'The administrator account cannot log in via SSO. Please use the local login form.';
$LANG['oidc_error_callback'] = 'OIDC: Authentication failed';
$LANG['oidc_error_callback_text'] = 'The OIDC authentication could not be completed. Please try again or contact your administrator.';

$LANG['logout_title'] = 'Logout';
$LANG['logout_comment'] = 'You have been logged out.';
