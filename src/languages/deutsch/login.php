<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings German: Login page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
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

//
// Logout
//
$LANG['logout_title'] = 'Logout';
$LANG['logout_comment'] = 'Du bist nun ausgeloggt.';
