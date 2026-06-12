<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings German: Login page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */

//
// Login
//
$LANG['login_login'] = 'Login';
$LANG['login_username'] = 'Benutzername:';
$LANG['login_password'] = 'Passwort:';
$LANG['login_authcode'] = 'Authenticator Code:';
$LANG['login_authcode_comment'] = 'Wenn du einen zweiten Faktor für die Authentifizierung eingerichtet hast, gib den Authenticator Code hier ein.';
$LANG['login_error_0'] = 'Login erfolgreich';
$LANG['login_error_1'] = 'Benutzername, Passwort oder Authenticator Code nicht angegeben';
$LANG['login_error_1_text'] = 'Bitte gib einen gültigen Benutzernamen und Passwort an, ggf. auch einen gültigen Authenticator Code.';
$LANG['login_error_2'] = 'Benutzername unbekannt';
$LANG['login_error_2_text'] = 'Der eingegebene Benutzername ist unbekannt. Bitte versuche es erneut.';
$LANG['login_error_2fa'] = 'Falscher Authentication Code';
$LANG['login_error_2fa_text'] = 'Der Authentication Code stimmt nicht überein.';
$LANG['login_error_3'] = 'Konto deaktiviert';
$LANG['login_error_3_text'] = 'Dieses Konto ist gesperrt bzw. noch nicht best&aum;tigt. Bitte kontaktiere den Administrator.';
$LANG['login_error_4'] = 'Passwort falsch';
$LANG['login_error_4_text'] = 'Das Passwort ist falsch. Dies war Fehlversuch Nummer %1%. Nach %2% Fehlversuchen wird der Account gesperrt für %3% Sekunden.';
$LANG['login_error_6_text'] = 'Dieser Account ist wegen zu vieler falscher Loginversuche vorübergehend gesperrt. Die Grace Periode endet in %1% Sekunden.';
$LANG['login_error_7'] = 'Benutzername oder Passwort inkorrekt';
$LANG['login_error_7_text'] = 'Der Benutzername oder das Passwort waren nicht korrekt. Bitte versuche es erneut.';
$LANG['login_error_8'] = 'Konto Verifizierung';
$LANG['login_error_8_text'] = 'Konto nicht verifiziert. Du solltest eine E-Mail mit einem Verfizierungslink erhalten haben.';
$LANG['login_error_90'] = 'LDAP Fehler: Erweiterung fehlt';
$LANG['login_error_90_text'] = 'Die PHP LDAP Erweiterung ist nicht geladen. Bitte aktivieren Sie sie in Ihrer php.ini Konfiguration.';
$LANG['login_error_91'] = 'LDAP Fehler: Passwort fehlt';
$LANG['login_error_92'] = 'LDAP Fehler: Authentifizierung fehlgeschlagen';
$LANG['login_error_92_text'] = 'Die LDAP Authentifizierung/Bindung ist fehlgeschlagen. Bitte versuche es erneut.';
$LANG['login_error_93'] = 'LDAP Fehler: Verbindung zum LDAP Server fehlgeschlagen';
$LANG['login_error_93_text'] = 'Die Verbindung zum LDAP Server ist fehlgeschlagen. Bitte versuche es erneut.';
$LANG['login_error_94'] = 'LDAP Fehler: Start von TLS fehlgeschlagen';
$LANG['login_error_94_text'] = 'Der Start von TLS ist fehlgeschlagen. Bitte versuche es erneut.';
$LANG['login_error_95'] = 'LDAP Fehler: Benutzername nicht gefunden';
$LANG['login_error_96'] = 'LDAP Fehler: "Search bind" fehlgeschlagen';
$LANG['login_error_96_text'] = 'Der LDAP "Search bind" ist fehlgeschlagen. Bitte versuche es erneut.';

$LANG['login_sso_button'] = 'Login mit SSO';
$LANG['login_admin_local'] = 'Administrator-Login';
$LANG['oidc_error_init'] = 'OIDC: Verbindungsfehler zum Anbieter';
$LANG['oidc_error_init_text'] = 'Die Verbindung zum Identity Provider konnte nicht hergestellt werden. Bitte versuche es erneut oder kontaktiere den Administrator.';
$LANG['oidc_error_no_sub'] = 'OIDC: Benutzerkennung fehlt';
$LANG['oidc_error_no_sub_text'] = 'Der Identity Provider hat keine Benutzerkennung (sub-Claim) zurückgegeben. Bitte kontaktiere den Administrator.';
$LANG['oidc_error_no_account'] = 'Kein lokales Konto gefunden';
$LANG['oidc_error_no_account_text'] = 'Deine Identität wurde beim Identity Provider bestätigt, aber es wurde kein passendes lokales TeamCal Neo-Konto gefunden. Bitte kontaktiere den Administrator.';
$LANG['oidc_local_login_blocked'] = 'SSO-Login ist aktiv';
$LANG['oidc_local_login_blocked_text'] = 'Der lokale Login ist deaktiviert, solange SSO aktiv ist. Bitte verwende die Schaltfläche „Login mit SSO". Nur das Administratorkonto kann sich lokal anmelden.';
$LANG['oidc_error_admin_local_only'] = 'Admin-Konto ist nur lokal';
$LANG['oidc_error_admin_local_only_text'] = 'Das Administrator-Konto kann sich nicht per SSO anmelden. Bitte verwende das lokale Anmeldeformular.';
$LANG['oidc_error_callback'] = 'OIDC: Authentifizierung fehlgeschlagen';
$LANG['oidc_error_callback_text'] = 'Die OIDC-Authentifizierung konnte nicht abgeschlossen werden. Bitte versuche es erneut oder kontaktiere den Administrator.';

//
// Logout
//
$LANG['logout_title'] = 'Logout';
$LANG['logout_comment'] = 'Du bist nun ausgeloggt.';
