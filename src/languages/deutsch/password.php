<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings German: Password
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */

//
// Password
//
$LANG['password_rules_high'] = '<br>Die Passwort Sicherheit ist zurzeit auf "Hoch" eingestellt. Daraus ergeben sich folgende Regeln:<br>
- Mindestens 8 Zeichen<br>
- Mindestens ein Großbuchstabe<br>
- Mindestens ein Kleinbuchstabe<br>
- Mindestens eine Zahl<br>
- Mindestens ein Sonderzeichen<br>';
$LANG['password_rules_low'] = '<br>Die Passwort Sicherheit ist zurzeit auf "Niedrig" eingestellt. Daraus ergeben sich folgende Regeln:<br>
- Mindestens 4 Zeichen<br>';
$LANG['password_rules_medium'] = '<br>Die Passwort Sicherheit ist zurzeit auf "Medium" eingestellt. Daraus ergeben sich folgende Regeln:<br>
- Mindestens 6 Zeichen<br>
- Mindestens ein Großbuchstabe<br>
- Mindestens ein Kleinbuchstabe<br>
- Mindestens eine Zahl<br>';

//
// Pwdreq
//
$LANG['pwdreq_alert_failed'] = 'Bitte gebe eine gültige E-mail Adresse ein.';
$LANG['pwdreq_alert_notfound'] = 'Benutzer nicht gefunden';
$LANG['pwdreq_alert_notfound_text'] = 'Es wurde kein Nutzer mit dieser E-mail Adresse gefunden.';
$LANG['pwdreq_alert_success'] = 'Eine E-mail mit Instruktionen zum Zurücksetzen des Passworts wurde verschickt.';
$LANG['pwdreq_email'] = 'E-mail';
$LANG['pwdreq_email_comment'] = 'Bitte gebe die E-mail Adresse von deinem Konto ein. Eine E-Mail mit Instruktionen zum Zurücksetzen des Passworts wird an sie versendet.';
$LANG['pwdreq_selectUser'] = 'Benutzer wählen';
$LANG['pwdreq_selectUser_comment'] = 'Es wurden mehrere Benutzer mit der angegebenen E-Mail Adresse gefunden. Bitte wähle den Nutzer, fuer den das Passwort zurückgesetzt werden soll.';
$LANG['pwdreq_title'] = 'Passwort zurücksetzen';
