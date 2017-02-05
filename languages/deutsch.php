<?php
/**
 * deutsch.php
 * 
 * Language file (German)
 *
 * @category TeamCal Neo 
 * @version 1.3.006
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

//=============================================================================
//
// LeAF LANGUAGE
// The following are the Lewe Appliation Framework language entries. To easier
// update the framework at a later point, go to the bottom of this file to 
// enter your application language strings to keep them separate.
//
$LANG['locale'] = 'de_DE';
$LANG['html_locale'] = 'de';

//
// Common
//
$LANG['action'] = 'Aktion';
$LANG['all'] = 'Alle';
$LANG['attention'] = 'Achtung';
$LANG['auto'] = 'Automatisch';
$LANG['avatar'] = 'Avatar';
$LANG['back_to_top'] = 'Zur&uuml;ck zum Anfang';
$LANG['blue'] = 'Blau';
$LANG['close_this_message'] = 'Diese Nachricht schlie&szlig;en';
$LANG['cookie_message'] = 'Diese Applikation nutzt Cookies um eine optimale Nutzung zu gew&auml;hrleisten.';
$LANG['cookie_dismiss'] = 'Alles klar!';
$LANG['cookie_learnMore'] = '[Mehr dazu...]';
$LANG['custom'] = 'Individuell';
$LANG['cyan'] = 'Cyan';
$LANG['description'] = 'Beschreibung';
$LANG['diagram'] = 'Diagramm';
$LANG['display'] = 'Anzeige';
$LANG['from'] = 'Von';
$LANG['general'] = 'Allgemein';
$LANG['green'] = 'Gr&uuml;n';
$LANG['grey'] = 'Grau';
$LANG['group'] = 'Gruppe';
$LANG['license'] = 'TeamCal Neo Lizenz';
$LANG['magenta'] = 'Magenta';
$LANG['monthnames'] = array (
   1 => "Januar",
   "Februar",
   "M&auml;rz",
   "April",
   "Mai",
   "Juni",
   "Juli",
   "August",
   "September",
   "Oktober",
   "November",
   "Dezember" 
);
$LANG['monthShort'] = array (
   1 => "Jan",
   "Feb",
   "M&auml;r",
   "Apr",
   "Mai",
   "Jun",
   "Jul",
   "Aug",
   "Sep",
   "Okt",
   "Nov",
   "Dez" 
);
$LANG['name'] = 'Name';
$LANG['none'] = 'Keine/r';
$LANG['options'] = 'Optionen';
$LANG['orange'] = 'Orange';
$LANG['period'] = 'Zeitraum';
$LANG['period_custom'] = 'Individuell';
$LANG['period_month'] = 'Aktueller Monat';
$LANG['period_quarter'] = 'Aktuelles Quartal';
$LANG['period_half'] = 'Aktuelles Halbjahr';
$LANG['period_year'] = 'Aktuelles Jahr';
$LANG['purple'] = 'Lila';
$LANG['red'] = 'Rot';
$LANG['region'] = 'Region';
$LANG['role'] = 'Rolle';
$LANG['role_admin'] = 'Administrator';
$LANG['role_director'] = 'Direktor';
$LANG['role_manager'] = 'Manager';
$LANG['role_assistant'] = 'Assistent';
$LANG['role_user'] = 'Nutzer';
$LANG['role_public'] = '&Ouml;ffentlich';
$LANG['scale'] = 'Skala';
$LANG['search'] = 'Suche';
$LANG['select_all'] = 'Alle ausw&auml;hlen';
$LANG['settings'] = 'Einstellungen';
$LANG['smart'] = 'Smart';
$LANG['to'] = 'Bis';
$LANG['today'] = 'Heute';
$LANG['total'] = 'Gesamt';
$LANG['type'] = 'Typ';
$LANG['user'] = 'Nutzer';
$LANG['weekdayShort'] = array (
   1 => "Mo",
   "Di",
   "Mi",
   "Do",
   "Fr",
   "Sa",
   "So" 
);
$LANG['weekdayLong'] = array (
   1 => "Montag",
   "Dienstag",
   "Mittwoch",
   "Donnerstag",
   "Freitag",
   "Samstag",
   "Sonntag" 
);
$LANG['yellow'] = 'Gelb';

//
// About
//
$LANG['about_version'] = 'Version';
$LANG['about_copyright'] = 'Copyright';
$LANG['about_license'] = 'Lizenz';
$LANG['about_forum'] = 'Forum';
$LANG['about_tracker'] = 'Issue Tracker';
$LANG['about_documentation'] = 'Dokumentation';
$LANG['about_credits'] = 'Dank an';
$LANG['about_for'] = 'f&uuml;r';
$LANG['about_and'] = 'und';
$LANG['about_majorUpdateAvailable'] = 'Major Update verf&uuml;gbar...';
$LANG['about_minorUpdateAvailable'] = 'Minor oder Patch Update verf&uuml;gbar...';
$LANG['about_misc'] = 'viele Nutzer f&uuml;r Tests und Vorschl&auml;ge...';
$LANG['about_view_releaseinfo'] = 'Releaseinfo &raquo;';
$LANG['about_vote'] = 'Bewerte TeamCal Neo';
$LANG['about_vote_comment'] = 'Wenn dir TeamCal Neo gef&auml;llt, gib <a href="http://www.lewe.com/teamcal-neo/#tcnvote" target="_blank">hier deine Stimme ab...</a>';

//
// Alerts
//
$LANG['alert_alert_title'] = 'ALARM';
$LANG['alert_danger_title'] = 'FEHLER';
$LANG['alert_info_title'] = 'INFORMATION';
$LANG['alert_success_title'] = 'ERFOLG';
$LANG['alert_warning_title'] = 'WARNUNG';

$LANG['alert_captcha_wrong'] = 'Captcha Code falsch';
$LANG['alert_captcha_wrong_text'] = 'Der Sicherheitscode wurde falsch eingegeben. Bitte versuchen Sie es erneut.';
$LANG['alert_captcha_wrong_help'] = 'Der Sicherheitscode muss genau wie angezeigt eingegeben werden. Gro&szlig;- und Kleinschreibung spielt dabei keine Rolle.';

$LANG['alert_controller_not_found_subject'] = 'Controller nicht gefunden';
$LANG['alert_controller_not_found_text'] = 'Der Controller "%1%" konnte nicht gefunden werden.';
$LANG['alert_controller_not_found_help'] = 'Bitte &uuml;berpr&uuml;fe die Installation. Die Datei existiert nicht oder es fehlt die n&ouml;tige Berechtigung f&uuml;r den Zugriff.';

$LANG['alert_decl_before_date'] = ": Abwesenheits&auml;nderungen vor dem folgendem Datum sind nicht erlaubt: ";
$LANG['alert_decl_group_threshold'] = ": Die Abwesenheitsgrenze wurde erreicht für die Gruppe(n): ";
$LANG['alert_decl_period'] = ": Abwesenheits&auml;nderungen in folgendem Zeitraum sind nicht erlaubt: ";
$LANG['alert_decl_total_threshold'] = ": Die generelle Abwesenheitsgrenze wurde erreicht.";

$LANG['alert_imp_subject'] = 'CSV Import Fehler aufgetreten';
$LANG['alert_imp_admin'] = 'Zeile %s: Der Benutzername "admin" ist f&uuml;r den Import nicht erlaubt.';
$LANG['alert_imp_columns'] = 'Zeile %s: Es mehr oder weniger als %s Spalten in der Zeile.';
$LANG['alert_imp_email'] = 'Zeile %s: "%s" ist keine g&uuml;ltige E-Mail Adresse.';
$LANG['alert_imp_exists'] = 'Zeile %s: Der Benutzername "%s" existiert bereits.';
$LANG['alert_imp_firstname'] = 'Zeile %s: Der Vorname "%s" entspricht nicht dem erlaubten Format (alphanumerische Zeichen, Leerzeichen, Punkt, Bindestrich, Unterstrich).';
$LANG['alert_imp_gender'] = 'Zeile %s: Falsches Geschlecht "%s" (male or female).';
$LANG['alert_imp_lastname'] = 'Zeile %s: Der Nachname "%s" entspricht nicht dem erlaubten Format (alphanumerische Zeichen, Leerzeichen, Punkt, Bindestrich, Unterstrich).';
$LANG['alert_imp_username'] = 'Zeile %s: Der Benutzername "%s" entspricht nicht dem erlaubten Format (alphanumerische Zeichen, Punkt, @).';

$LANG['alert_input'] = 'Eingabevalidierung fehlgeschlagen';
$LANG['alert_input_alpha'] = 'Diese Feld erlaubt nur eine Eingabe von alphabetischen Zeichen.';
$LANG['alert_input_alpha_numeric'] = 'Diese Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen.';
$LANG['alert_input_alpha_numeric_dash'] = 'Diese Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen plus Bindestrich und Unterstrich.';
$LANG['alert_input_alpha_numeric_dash_blank'] = 'Diese Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen plus Leerzeichen, Bindestrich und Unterstrich.';
$LANG['alert_input_alpha_numeric_dash_blank_dot'] = 'Diese Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen plus Leerzeichen, Punkt, Bindestrich und Unterstrich.';
$LANG['alert_input_alpha_numeric_dash_blank_special'] = 'Diese Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen, Leerzeichen, Bindestrich, Unterstrich und die 
      Sonderzeichen \'!@#$%^&*().';
$LANG['alert_input_alpha_numeric_dot_at'] = 'Diese Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen, Punkten und @.';
$LANG['alert_input_date'] = 'Das Datum muss um ISO 8601 Format sein, z.b. 2014-01-01.';
$LANG['alert_input_email'] = 'Die eingegebene E-Mail Adresse ist ung&uuml;ltig.';
$LANG['alert_input_equal'] = 'Der Wert in diesem Feld muss gleich dem in Feld "%s" sein.';
$LANG['alert_input_equal_string'] = 'Der Text in diesem Feld muss "%s" lauten.';
$LANG['alert_input_exact_length'] = 'Die Eingabe dieses Feldes muss genau %s Zeichen lang sein.';
$LANG['alert_input_greater_than'] = 'Der Wert in diesem Feld muss gr&ouml;&szlig;er sein als der im Feld "%s".';
$LANG['alert_input_hexadecimal'] = 'Diese Feld erlaubt nur eine Eingabe von hexadezimalen Zeichen.';
$LANG['alert_input_ip_address'] = 'Die Eingabe dieses Feldes ist keine g&uuml;ltige IP Adresse.';
$LANG['alert_input_less_than'] = 'Der Wert in diesem Feld muss kleiner sein als der im Feld "%s".';
$LANG['alert_input_match'] = 'Das Feld "%s" muss mit dem Feld "%s" &uuml;bereinstimmen.';
$LANG['alert_input_max_length'] = 'Die Eingabe dieses Feldes darf maximal %s Zeichen lang sein.';
$LANG['alert_input_min_length'] = 'Die Eingabe dieses Feldes muss minimal %s Zeichen lang sein.';
$LANG['alert_input_numeric'] = 'Die Eingabe dieses Feldes muss numerisch sein.';
$LANG['alert_input_phone_number'] = 'Die Eingabe dieses Feldes muss eine g&uuml;ltige Telefonnummer sein, z.B. (555) 123 4567 oder +49 172 123 4567.';
$LANG['alert_input_pwdlow'] = 'Das Passwort muss mindestens 4 Zeichen lang sein. Erlaubt sind Klein- und Gro&szlig;buchstaben, Zahlen und die folgenden Sonderzeichen: !@#$%^&amp;*()';
$LANG['alert_input_pwdmedium'] = 'Das Passwort muss mindestens 6 Zeichen lang sein, mindestens einen Kleinbuchstaben, einen Gro&szlig;buchstaben und eine Zahl enthalten. 
      Erlaubt sind Klein- und Gro&szlig;buchstaben, Zahlen und die folgenden Sonderzeichen: !@#$%^&amp;*()';
$LANG['alert_input_pwdhigh'] = 'Das Passwort muss mindestens 8 Zeichen lang sein, mindestens einen Kleinbuchstaben, einen Gro&szlig;buchstaben, eine Zahl und ein Sonderzeichen enthalten. 
      Erlaubt sind Klein- und Gro&szlig;buchstaben, Zahlen und die folgenden Sonderzeichen: !@#$%^&amp;*()';
$LANG['alert_input_regex_match'] = 'Die Eingabe dieses Feldes entsprach nicht dem regul&auml;ren Ausdruck "%s".';
$LANG['alert_input_required'] = 'Dieses Feld ist eine Pflichteingabe.';
$LANG['alert_input_validation_subject'] = 'Eingabevalidierung';

$LANG['alert_maintenance_subject'] = 'Website in Wartung';
$LANG['alert_maintenance_text'] = 'Die Website is zurzeit auf "Unter Wartung" gesetzt. Normale Nutzer k&ouml;nnen keine Funktionen nutzen.';
$LANG['alert_maintenance_help'] = 'Ein Administrator kann die Website wieder aktiv setzten unter Administration -> Framework-Konfiguration -> System.';

$LANG['alert_no_data_subject'] = 'Fehlerhafte Daten';
$LANG['alert_no_data_text'] = 'Es wurden falsche oder unzureichende Daten f&uuml;r diese Operation &uuml;bermittelt.';
$LANG['alert_no_data_help'] = 'Die Operation konnte wegen fehlender oder falscher Daten nicht ausfgefuehrt werden.';

$LANG['alert_not_allowed_subject'] = 'Zugriff nicht erlaubt';
$LANG['alert_not_allowed_text'] = 'Du hast nicht die n&ouml;tige Berechtigung auf diese Seite oder Funktion zuzugreifen.';
$LANG['alert_not_allowed_help'] = 'Wenn du nicht eingeloggt bist, dann ist &ouml;ffentlicher Zugriff auf diese Seite nicht erlaubt. Wenn du eingeloggt bist, fehlt deiner Rolle die n&ouml;tige Berechtigung f&uuml;r den Zugriff.';

$LANG['alert_perm_invalid'] = 'Das neue Berechtigungsschema "%1%" ist ung&uuml;ltig. Im Namen sind nur Buchstaben und Zahlen erlaubt.';
$LANG['alert_perm_default'] = 'Das "Default" Schema kann nicht auf sich selbst zur&uuml;ckgesetzt werden.';
$LANG['alert_perm_exists'] = 'Das Berechtigungsschema "%1%" existiert bereits. Bitte w&auml;hle einen anderen Name oder l&ouml;sche das existierende zuerst.';

$LANG['alert_reg_subject'] = 'Nutzerregistrierung';
$LANG['alert_reg_approval_needed'] = 'Die Verifizierung war erfolgreich. Allerdings muss das Nutzerkonto von einem Administrator freigeschaltet werden. Er/Sie wurde per Mail informiert.';
$LANG['alert_reg_success'] = 'Die Verifizierung war erfolgreich. Du kannst dich nun einloggen und die Applikation nutzen.';
$LANG['alert_reg_mismatch'] = 'Der Verfizierungscode stimmt nicht mit dem &uuml;berein, der erstellt wurde. Eine Mail wurde an den Admin geschickt, um die Anfrage zu pr&uuml;fen.';
$LANG['alert_reg_no_user'] = 'Der Benutzername konnte nicht gefunden werden. Wurde er registriert?';
$LANG['alert_reg_no_vcode'] = 'Ein Verfizierungscode konnte nicht gefunden werden. Wurde er registriert?';

$LANG['alert_upl_img_subject'] = 'Bilder Hochladen';
$LANG['alert_upl_doc_subject'] = 'Dokumente Hochladen';

//
// Attachments
//
$LANG['att_title'] = 'Anh&auml;nge';
$LANG['att_tab_files'] = 'Dateien';
$LANG['att_tab_upload'] = 'Hochladen';
$LANG['att_col_file'] = 'Datei';
$LANG['att_col_owner'] = 'Besitzer';
$LANG['att_col_shares'] = 'Zugriff';

$LANG['att_confirm_delete'] = 'Bist du sicher, dass du die ausgew&auml;hlten Dateien l&ouml;schen m&ouml;chtest?';
$LANG['att_extensions'] = 'Erlaubte Bildformate';
$LANG['att_file'] = 'Datei hochladen';
$LANG['att_file_comment'] = 'Hier kann eine Datei hochgeladen werden. Die Dateigr&ouml;&szlig;e ist limitiert auf %d KBytes und die erlaubten Formate sind "%s".';
$LANG['att_maxsize'] = 'Maximale Dateigr&ouml;&szlig;e';
$LANG['att_shareWith'] = 'Teilen mit';
$LANG['att_shareWith_comment'] = 'W&auml;hle die Gruppen oder Benutzer, mit denen du diese Datei teilen willst. Hinweis: Diese Benutzer m&uuml;ssen Zugriff auf diese Seite habe, um auf die Datei zuzugreifen.';
$LANG['att_shareWith_all'] = 'Alle';
$LANG['att_shareWith_group'] = 'Gruppe';
$LANG['att_shareWith_role'] = 'Rolle';
$LANG['att_shareWith_user'] = 'Benutzer';

//
// Buttons
//
$LANG['btn_activate'] = 'Aktivieren';
$LANG['btn_add'] = 'Hinzuf&uuml;gen';
$LANG['btn_apply'] = 'Anwenden';
$LANG['btn_archive_selected'] = 'Auswahl archivieren';
$LANG['btn_assign'] = 'Zuordnen';
$LANG['btn_assign_all'] = 'Allen zuordnen';
$LANG['btn_backup'] = 'Sichern';
$LANG['btn_calendar'] = 'Kalender';
$LANG['btn_cancel'] = 'Abbrechen';
$LANG['btn_clear'] = 'Entfernen';
$LANG['btn_clear_all'] = 'Alle entfernen';
$LANG['btn_close'] = 'Schlie&szlig;en';
$LANG['btn_configure'] = 'Konfigurieren';
$LANG['btn_confirm'] = 'Best&auml;tigen';
$LANG['btn_confirm_all'] = 'Alle best&auml;tigen';
$LANG['btn_create'] = 'Anlegen';
$LANG['btn_create_abs'] = 'Abwesenheitstyp anlegen';
$LANG['btn_create_group'] = 'Gruppe anlegen';
$LANG['btn_create_holiday'] = 'Feiertag anlegen';
$LANG['btn_create_region'] = 'Region anlegen';
$LANG['btn_create_role'] = 'Rolle anlegen';
$LANG['btn_create_user'] = 'Benutzer anlegen';
$LANG['btn_delete'] = 'L&ouml;schen';
$LANG['btn_delete_abs'] = 'Abwesenheitstyp l&ouml;schen';
$LANG['btn_delete_all'] = 'Alle l&ouml;schen';
$LANG['btn_delete_group'] = 'Gruppe l&ouml;schen';
$LANG['btn_delete_holiday'] = 'Feiertag l&ouml;schen';
$LANG['btn_delete_records'] = 'Datens&auml;tze l&ouml;schen';
$LANG['btn_delete_role'] = 'Rolle l&ouml;schen';
$LANG['btn_delete_selected'] = 'Auswahl l&ouml;schen';
$LANG['btn_done'] = 'Fertig';
$LANG['btn_download_view'] = 'Runterladen/Anzeigen';
$LANG['btn_edit'] = 'Editieren';
$LANG['btn_edit_profile'] = 'Profil bearbeiten';
$LANG['btn_enable'] = 'Aktivieren';
$LANG['btn_export'] = 'Export';
$LANG['btn_group_list'] = 'Gruppenliste';
$LANG['btn_help'] = 'Hilfe';
$LANG['btn_icon'] = 'Icon...';
$LANG['btn_import'] = 'Import';
$LANG['btn_install'] = 'Installation';
$LANG['btn_login'] = 'Login';
$LANG['btn_logout'] = 'Logout';
$LANG['btn_merge'] = 'Verschmelzen';
$LANG['btn_next'] = 'N&auml;ch';
$LANG['btn_optimize_tables'] = 'Tabellen optimieren';
$LANG['btn_prev'] = 'Vorh';
$LANG['btn_refresh'] = 'Aktualisieren';
$LANG['btn_role_list'] = 'Rollenliste';
$LANG['btn_register'] = 'Registrieren';
$LANG['btn_remove'] = 'Entfernen';
$LANG['btn_reset'] = 'Zur&uuml;cksetzen';
$LANG['btn_reset_database'] = 'Datenbank zur&uuml;cksetzen';
$LANG['btn_reset_password'] = 'Passwort zur&uuml;cksetzen';
$LANG['btn_reset_password_selected'] = 'Auswahl Passwort zur&uuml;cksetzen';
$LANG['btn_restore'] = 'Wiederherstellen';
$LANG['btn_restore_selected'] = 'Auswahl wiederherstellen';
$LANG['btn_role_list'] = 'Rollenliste';
$LANG['btn_save'] = 'Speichern';
$LANG['btn_search'] = 'Suchen';
$LANG['btn_select'] = 'Ausw&auml;hlen';
$LANG['btn_send'] = 'Senden';
$LANG['btn_show_hide'] = 'Anzeigen/Verbergen';
$LANG['btn_submit'] = 'Abschicken';
$LANG['btn_switch'] = 'Anwenden';
$LANG['btn_testdb'] = 'Datenbank testen';
$LANG['btn_transfer'] = '&Uuml;bertragen';
$LANG['btn_update'] = 'Aktualisieren';
$LANG['btn_user_list'] = 'Benutzerliste';
$LANG['btn_upload'] = 'Hochladen';
$LANG['btn_view'] = 'Anzeigen';

//
// Config
//
$LANG['config_title'] = 'Framework Konfiguration';

$LANG['config_tab_email'] = 'E-mail';
$LANG['config_tab_footer'] = 'Fu&szlig;zeile';
$LANG['config_tab_homepage'] = 'Startseite';
$LANG['config_tab_images'] = 'Bilder';
$LANG['config_tab_login'] = 'Login';
$LANG['config_tab_registration'] = 'Registrierung';
$LANG['config_tab_system'] = 'System';
$LANG['config_tab_theme'] = 'Theme';
$LANG['config_tab_user'] = 'Nutzer';

$LANG['config_activateMessages'] = 'Message Center aktivieren';
$LANG['config_activateMessages_comment'] = 'Mit diesem Schalter kann das Message Center aktiviert werden. Nutzer k&ouml;nnen damit anderen Nutzern und Gruppen 
      Nachrichten oder E-Mails schicken. Ein Eintrag im Optionen Menu wird hinzugef&uuml;gt.';
$LANG['config_adminApproval'] = 'Administrator Freischaltung erforderlich';
$LANG['config_adminApproval_comment'] = 'Der Administrator erh&auml;lt eine E-Mail bei einer Neuregistrierung. Er muss den Account manuell freischalten.';
$LANG['config_allowRegistration'] = 'User Selbst-Registration erlauben';
$LANG['config_allowRegistration_comment'] = 'Erlaubt die Registrierung durch den User. Ein zus&auml;tzlicher Menueintrag erscheint im Menu.';
$LANG['config_allowUserTheme'] = 'User Theme';
$LANG['config_allowUserTheme_comment'] = 'W&auml;hle aus, ob jeder User sein eigenes Theme w&auml;hlen kann.';
$LANG['config_appDescription'] = 'HTML Beschreibung';
$LANG['config_appDescription_comment'] = 'Hier kann eine Applikations-Beschreibung eingetragen werden. Sie wird im HTML Header benutzt und von Suchmaschinen gelesen.';
$LANG['config_appKeywords'] = 'HTML Schl&uuml;sselw&ouml;rter';
$LANG['config_appKeywords_comment'] = 'Hier k&ouml;nnen Schl&uuml;sselw&ouml;rter eingetragen werden. Sie wird im HTML Header benutzt und von Suchmaschinen gelesen.';
$LANG['config_appTitle'] = 'Applikationsname';
$LANG['config_appTitle_comment'] = 'Hier kann ein Applikations-Title eingetragen werden. Er wird an mehreren Stellen benutzt, z.B. im HTML Header, Menu und auf anderen Seiten.';
$LANG['config_appURL'] = 'Applikations-URL';
$LANG['config_appURL_comment'] = 'Gib die volle Applikations-URL hier ein. Sie wird z.B. in Benachrichtiguns-E-Mails benutzt.';
$LANG['config_badLogins'] = 'Ung&uuml;ltige Logins';
$LANG['config_badLogins_comment'] = 'Anzahl der ung&uuml;ltigen Login Versuche bevore der User Status auf \'LOCKED\' gesetzt wird. Der User muss danach solange 
      warten wie in der Schonfrist angegeben, bevor er sich erneut einloggen kann. Wenn dieser Wert auf 0 gesetzt wird, ist diese Funktion deaktiviert.';
$LANG['config_cookieConsent'] = 'Cookie Zustimmung';
$LANG['config_cookieConsent_comment'] = 'Mit dieser Option wird am unteren Bildschirmrand ein Popup f&uuml;r die Zustimmung zu Cookienutzung angezeigt. 
      Dies ist legale Pflicht in der EU. Dieses Feature erordert eine Internetverbindung.';
$LANG['config_cookieLifetime'] = 'Cookie Lebensdauer';
$LANG['config_cookieLifetime_comment'] = 'Bei erfolgreichem Einloggen wird ein Cookie auf dem lokalen Rechner des Users abgelegt. Dieser Cookie hat eine 
      bestimmte Lebensdauer, nach dem er nicht mehr anerkannt wird. Ein erneutes Login is notwendig. Die Lebensdauer kann hier in Sekunden angegeben werden (0-999999).';
$LANG['config_defaultHomepage'] = 'Standard Startseite';
$LANG['config_defaultHomepage_comment'] = 'Diese Option bestimmt die standard Startseite. Sie wird anonymen Benutzern angezeigt und wenn das Applikationsicon oben links
      angeklickt wird. Achtung, wenn hier "Kalender" gew&auml;hlt wird, sollte "Public" auch View-Rechte f&uuml;r den Kalender haben.';
$LANG['config_defaultHomepage_home'] = 'Willkommen Seite';
$LANG['config_defaultHomepage_calendarview'] = 'Kalender';
$LANG['config_defaultLanguage'] = 'Standard Sprache';
$LANG['config_defaultLanguage_comment'] = $appTitle. ' enth&auml;lt die Sprachen Englisch und Deutsch. Der Administrator hat eventuell weitere Sprachen installiert. 
      Hier kann die Standard Sprache eingestellt werden.';
$LANG['config_emailConfirmation'] = 'E-Mail Best&auml;tigung erforderlich';
$LANG['config_emailConfirmation_comment'] = 'Durch die Registrierung erh&auml;lt der User eine E-Mail an die von ihm angegebene Adresse. Sie enth&auml;lt einen 
      Aktivierungslink, dem er folgen muss, um seine Angaben zu bets&auml;tigen.';
$LANG['config_emailNotifications'] = 'E-Mail Benachrichtigungen';
$LANG['config_emailNotifications_comment'] = 'Aktivierung/Deaktivierung von E-Mail Benachrichtigungen. Wenn diese Option ausgeschaltet ist, werden keine automatischen 
      Benachrichtigungen per E-Mails verschickt. Dies trifft aber nicht auf Selbst-Registrierungsmails und auf manuell gesendete Mails im Message Center und im Viewprofile Dialog zu.';
$LANG['config_faCDN'] = 'Fontawesome CDN';
$LANG['config_faCDN_comment'] = 'CDNs (Content Distributed Network) k&ouml;nnen einen Performance-Vorteil bieten dadurch dass popul&auml;re Web Module von Servern rund 
      um den Globus geladen werden. Fontawesome ist so ein Modul. Wenn es von einem CDN Server geladen wird, von dem das gleiche Modul 
      f&uuml;r den Nutzer schon durch eine andere Anwednung geladen wurde, ist es bereits im Cache des Nutzers und muss nicht nochmal heruntergeladen werden.<br>Diese Option
      funktioniert nat&uuml;rlich nur mit Internetverbindung. Schalte diese Option aus, wenn du '.$appTitle.' in einer Umgebung ohne Internetverbindung betreibst.';
$LANG['config_footerCopyright'] = 'Fu&szlig;zeilen Copyright Name';
$LANG['config_footerCopyright_comment'] = 'Wird in der Fu&szlig;zeile oben links angezeigt. Gib nur den Namen ein, das (aktuelle) Jahr wird autmatisch angezeigt.';
$LANG['config_footerCopyrightUrl'] = 'Fu&szlig;zeilen Copyright URL';
$LANG['config_footerCopyrightUrl_comment'] = 'Gib die URL ein, zu der der Copyright Name verlinken soll. Wenn keine URL angegeben wird, wird nur der Name angezeigt.';
$LANG['config_footerSocialLinks'] = 'Links zu Sozialen Netzwerken';
$LANG['config_footerSocialLinks_comment'] = 'Gebe alle URLs zu sozialen Netzwerken ein, zu denen du von TeamCal Neo\'s Fu&szlig;zeile verlinken m&ouml;chtest. Die URLs m&uuml;ssen durch ein Semikolon getrennt sein.
      TeamCal Neo wird die Netzwerke identifizieren und die entsprechende Icons in der Fu&szlig;zeile anzeigen.';
$LANG['config_footerViewport'] = 'Viewport-Gr&ouml;&szlig;e anzeigen';
$LANG['config_footerViewport_comment'] = 'Mit dieser Option wird im Footer die Viewport-Gr&ouml;&szlig; angezeigt.';
$LANG['config_googleAnalytics'] = 'Google Analytics';
$LANG['config_googleAnalytics_comment'] = $appTitle . ' unterst&uuml;tzt Google Analytics. Wenn du deine Instanz im Internet betreibst und den Zugriff 
      von Google Analytics tracken lassen willst, ticke die Checkbox hier und trage deine Google Analytics ID ein. Der entsprechende Javascript Code wird dann eingef&uuml;gt.';
$LANG['config_googleAnalyticsID'] = "Google Analytics ID";
$LANG['config_googleAnalyticsID_comment'] = "Wenn du die Google Analytics Funktion aktiviert hast, trage hier deine Google Analytics ID im Format UA-999999-99 ein.";
$LANG['config_gracePeriod'] = 'Schonfrist';
$LANG['config_gracePeriod_comment'] = 'Zeit in Sekunden, die ein User warten muss, bevor er sich nach zu vielen fehlgeschlagenen Versuchen wieder einloggen kann.';
$LANG['config_homepage'] = 'Benutzer Startseite';
$LANG['config_homepage_comment'] = 'Diese Option bestimmt, welche Seite registrierten Benutzern nach dem Login angezeigt wird.';
$LANG['config_homepage_calendarview'] = 'Kalender';
$LANG['config_homepage_home'] = 'Willkommen Seite';
$LANG['config_homepage_messages'] = 'Nachrichten Seite';
$LANG['config_jQueryCDN'] = 'jQuery CDN';
$LANG['config_jQueryCDN_comment'] = 'CDNs (Content Distributed Network) k&ouml;nnen einen Performance-Vorteil bieten dadurch dass popul&auml;re Web Module von Servern rund 
      um den Globus geladen werden. jQuery ist so ein Modul. Wenn es von einem CDN Server geladen wird, von dem das gleiche Modul 
      f&uuml;r den Nutzer schon durch eine andere Anwendung geladen wurde, ist es bereits im Cache des Nutzers und muss nicht nochmal heruntergeladen werden.<br>Schalte 
      diese Option aus, wenn du '.$appTitle.' in einer Umgebung ohne Internetverbindung betreibst.';
$LANG['config_jqtheme'] = 'jQuery UI Theme';
$LANG['config_jqtheme_comment'] = $appTitle . ' nutzt jQuery UI, eine popul&auml;re Sammlung von Javascript Tools. jQuery UI bietet auch verschiedene Themes, die die Anzeige 
      der Reiterdialoge u.a. Objekten bestimmen. Das Standard Theme ist "smoothness", ein neutrales Schema mit Graut&ouml;nen. Versuche andere aus der Liste, manche sind 
      recht fabenfroh. Diese Einstellung wirkt global. Nutzer k&ouml;nnen kein eigenes jQuery UI Theme w&auml;hlen.';
$LANG['config_logLanguage'] = "Logbuchsprache";
$LANG['config_logLanguage_comment'] = "Diese Einstellung bestimmt die Sprache der Logbucheintr&auml;ge.";
$LANG['config_mailFrom'] = 'Mail Von';
$LANG['config_mailFrom_comment'] = 'Gibt den Absender Namen von Benachrichtigungs E-Mails an.';
$LANG['config_mailReply'] = 'Mail Antwort';
$LANG['config_mailReply_comment'] = 'Gibt die R&uuml;ckantwort Adresse von Benachrichtigungs E-Mails an. Dieses Feld muss eine g&uuml;ltige E-Mail Adresse enthalten. 
      Wenn das nicht der Fall ist, wird eine Dummy Adresse gespeichert.';
$LANG['config_mailSMTP'] = 'Externen SMTP Server benutzen';
$LANG['config_mailSMTP_comment'] = 'Mit diesm Schalter wird ein externer SMTP Server zum Versenden von E-Mails benutzt anstatt der PHP mail() Funktion. 
      Diese Feature erfordert das PEAR Mail Paket auf dem TcNeo Server. Viele Hoster installieren dieses Paket als Standard. Ausserdem ist es erforderlich, dass sich 
      der Tcro Server per SMTP oder TLS/SSL protocol mit den gebr&auml;chlichen SMTP port 25, 465 und 587 mit dem SMTP Server verbinden kann. Bei einigen Hostern 
      ist dies durch Firewalleinstellungen nicht m&ouml;glich. Es erscheint dann eie Fehlermeldung.';
$LANG['config_mailSMTPhost'] = 'SMTP Host';
$LANG['config_mailSMTPhost_comment'] = 'Gib den SMTP Host Namen an.';
$LANG['config_mailSMTPport'] = 'SMTP Port';
$LANG['config_mailSMTPport_comment'] = 'Gib den SMTP Host Port an.';
$LANG['config_mailSMTPusername'] = 'SMTP Benutzername';
$LANG['config_mailSMTPusername_comment'] = 'Gib den SMTP Benutzernamen an.';
$LANG['config_mailSMTPpassword'] = 'SMTP Passwort';
$LANG['config_mailSMTPpassword_comment'] = 'Gib das SMTP Passwort an.';
$LANG['config_mailSMTPSSL'] = 'SMTP TLS/SSL Protokoll';
$LANG['config_mailSMTPSSL_comment'] = 'TLS/SSL Protokoll f&uuml;r die SMTP Verbindung benutzen.';
$LANG['config_menuBarInverse'] = 'Men&uuml;zeile Invertieren';
$LANG['config_menuBarInverse_comment'] = 'Mit diesem Schalter kann die Farbkombination der Men&uuml;zeile invertiert werden. F&uuml;r einige Themes sieht das besser aus.';
$LANG['config_noIndex'] = 'Keine Suchmaschinen-Indizierung';
$LANG['config_noIndex_comment'] = 'Mit diesem Schalter werden Suchmaschinen angewiesen, diese TeamCal Neo Website nicht zu indizieren. Es werden ausserdem keine SEO Eintr&auml;ge im Header erzeugt.';
$LANG['config_permissionScheme'] = 'Berechtigungsschema';
$LANG['config_permissionScheme_comment'] = 'Hiermit wird das aktive Berechtigungsschema ausgew&auml;hlt. Das Schema kann auf der Berechtigungsschema Seite bearbeitet werden.';
$LANG['config_pwdStrength'] = 'Passwort Sicherheit';
$LANG['config_pwdStrength_comment'] = 'Die Passwort Sicherheit bestimmt, welchen Anforderungen das User Passwort gen&uuml;gen muss. Erlaubt sind immer Gro&szlig;- und Kleinbuchstaben, Zahlen und die Sonderzeichen: !@#$%^&amp;*()<br><br> 
      - <strong>Niedrig:</strong> Mindestens 4 Zeichen<br>
      - <strong>Mittel:</strong> Mindestens 6 Zeichen, mindestens ein Gro&szlig;buchstabe, ein Kleinbuchstabe und eine Zahl<br>
      - <strong>Hoch:</strong> Mindestens 8 Zeichen, mindestens ein Gro&szlig;buchstabe, ein Kleinbuchstabe, eine Zahl und ein Sonderzeichen<br>';
$LANG['config_pwdStrength_low'] = 'Niedrig';
$LANG['config_pwdStrength_medium'] = 'Mittel';
$LANG['config_pwdStrength_high'] = 'Hoch';
$LANG['config_showAlerts'] = 'Erfolgs- und Fehlermeldungen';
$LANG['config_showAlerts_comment'] = 'Mit dieser Option kann ausgew&auml;hlt werden, welche Ergebnisnachrichten angezeigt werden.';
$LANG['config_showAlerts_all'] = 'Alle (inkl. Erfolgsnachrichten)';
$LANG['config_showAlerts_warnings'] = 'Nur Warnungen und Fehler';
$LANG['config_showAlerts_none'] = 'Keine';
$LANG['config_showBanner'] = 'Banner anzeigen';
$LANG['config_showBanner_comment'] = 'Mit dieser Option wird zwischen Menu und Inhalt ein Banner mit dem Applikationstitel und einer Suchbox angezeigt.';
$LANG['config_theme'] = 'Theme';
$LANG['config_theme_comment'] = 'W&auml;hle ein Theme aus, umd das Aussehen der User Interface zu &auml;ndern. Du kannst ein eigenes 
      Theme erstellen, indem du eine Kopie eines Verzeichnis im \'themes\' Ordner anlegst und das Style Sheet entsprechend anpasst. 
      Das neue Verzeichnis wir dann automatisch in dieser Liste hier angezeigt. Kopiere aber nicht das \'bootstrap\' Dummy Verzeichnis.';
$LANG['config_timeZone'] = 'Zeitzone';
$LANG['config_timeZone_comment'] = 'Wenn der Webserver in einer anderen Zeitzone steht als die Nutzer, kann hier die Zeitzone angepasst werden.';
$LANG['config_underMaintenance'] = 'Website in Wartung';
$LANG['config_underMaintenance_comment'] = 'Mit diesem Schalter kann die Website unter Wartung gestellt werden. Nur fuer den Admin Nuzter sind die Seiten verf&uuml;gbar.
      Normale Nutzer bekommen eine "Unter Wartung" Nachricht.';
$LANG['config_userCustom1'] = 'Profilfeld 1 Titel';
$LANG['config_userCustom1_comment'] = 'Gibt den Titel dieses Feldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['config_userCustom2'] = 'Profilfeld 2 Titel';
$LANG['config_userCustom2_comment'] = 'Gibt den Titel dieses Feldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['config_userCustom3'] = 'Profilfeld 3 Titel';
$LANG['config_userCustom3_comment'] = 'Gibt den Titel dieses Feldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['config_userCustom4'] = 'Profilfeld 4 Titel';
$LANG['config_userCustom4_comment'] = 'Gibt den Titel dieses Feldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['config_userCustom5'] = 'Profilfeld 5 Titel';
$LANG['config_userCustom5_comment'] = 'Gibt den Titel dieses Feldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['config_userManual'] = 'Nutzerhandbuch';
$LANG['config_userManual_comment'] = $appTitle . '\'s Nutzerhandbuch ist in Englisch verf&uuml;gbar auf <a href="https://georgelewe.atlassian.net/wiki/display/TCNEO/" target="_blank">Lewe.com\'s Confluence site</a>.
      Solltest du ein eigenes Handbuch geschrieben haben, kannst du den Link hier angeben. Der Link wird im Hilfe Menu angezeigt. Wenn dieses Feld leer ist, wird kein Eintrag im Hilfe Menu angezeigt.';
$LANG['config_versionCompare'] = 'Versionsvergleich';
$LANG['config_versionCompare_comment'] = 'Mit dieser Option &uuml;berpr&uuml;ft TeamCal Neo auf der "&Uuml;ber TeamCal Neo" Seite die laufende Version und vergleicht sie mit der neusten verf&uuml;gbaren. Dazu ben&ouml;tigt
      TeamCal Neo Internetzugriff. Wenn du TeamCal Neo in einer Umgebung ohne Internetzugriff betreibst, schalte diese Option aus. Bei abweichenden Versionen wird dies hinter der Versionsnummer angeziegt.';
$LANG['config_welcomeText'] = 'Willkommen Seite Text';
$LANG['config_welcomeText_comment'] = 'Hier kann ein ein Text f&uuml;r die Startseite eingegeben werden.';

//
// Config App
//
$LANG['configapp_title'] = 'Applikations-Konfiguration';

//
// Database
//
$LANG['db_title'] = 'Datenbank Verwaltung';
$LANG['db_tab_cleanup'] = 'Aufr&auml;umen';
$LANG['db_tab_delete'] = 'Datens&auml;tze l&ouml;schen';
$LANG['db_tab_admin'] = 'Verwaltung';
$LANG['db_tab_optimize'] = 'Tabellen optimieren';
$LANG['db_tab_reset'] = 'Datenbank zur&uuml;cksetzen';

$LANG['db_alert_delete'] = 'Datens&auml;tze L&ouml;schen';
$LANG['db_alert_delete_success'] = 'Die L&ouml;schungen wurden durchgef&uuml;hrt.';
$LANG['db_alert_failed'] = 'Die Operation konnte nicht durchgef&uuml;hrt werden. Bitte &uuml;berpr&uuml;fe deine Eingaben.';
$LANG['db_alert_optimize'] = 'Tabellen optimieren';
$LANG['db_alert_optimize_success'] = 'Alle Datenbanktabellen wurden optimiert.';
$LANG['db_alert_reset'] = 'Datenbank zur&uuml;cksetzen';
$LANG['db_alert_reset_fail'] = 'Eine oder mehrere SQL Anweisungen sind fehlgeschlagen. Die Datenbank k&ouml;nnte unvollst&auml;ndig oder korrupt sein.';
$LANG['db_alert_reset_success'] = 'Die Datenbank wurde erfolgreich auf die Beispieldaten zur&uuml;ckgesetzt.';
$LANG['db_alert_url'] = 'Datenbank Verwaltungs-URL';
$LANG['db_alert_url_fail'] = 'Bitte gib eine g&uuml;ltige URL f&uuml;r die Datenbankverwaltung ein.';
$LANG['db_alert_url_success'] = 'Die URL zur Datenbankverwaltung wurde erfolgreich gespeichert.';
$LANG['db_application'] = 'Datenbank Verwaltung';
$LANG['db_confirm'] = 'Best&auml;tigung';
$LANG['db_dbURL'] = 'Datenbank URL';
$LANG['db_dbURL_comment'] = 'Hier kann ein direkter Link zur bevorzugten Datenbank Applikation angegeben werden. Der Button unten verlinkt dorthin.';
$LANG['db_del_archive'] = 'Alle archivierten Datens&auml;tze l&ouml;schen';
$LANG['db_del_groups'] = 'Alle Gruppen l&ouml;schen';
$LANG['db_del_log'] = 'Alle System Log Eintr&auml;ge l&ouml;schen';
$LANG['db_del_messages'] = 'Alle Benachrichtigungen l&ouml;schen';
$LANG['db_del_orphMessages'] = 'Verwaiste Benachrichtigungen l&ouml;schen';
$LANG['db_del_permissions'] = 'Berechtigungsschemen l&ouml;schen (ausser "Default")';
$LANG['db_del_users'] = 'Alle User inkl. deren Abwesenheiten und Notizen l&ouml;schen (ausser "admin")';
$LANG['db_del_confirm_comment'] = 'Gib bitte "DELETE" ein, um diese Aktion zu best&auml;tigen:';
$LANG['db_del_what'] = 'Was soll gel&ouml;scht werden?';
$LANG['db_del_what_comment'] = 'Gebe hier an, was gel&ouml;scht werden soll.';
$LANG['db_optimize'] = 'Datenbanktabellen optimieren';
$LANG['db_optimize_comment'] = 'Reorganisiert die Tabellendaten und deren Indexinformationen in der Datenbank, um Speicherplatz zu reduzieren und die I/O Effizienz zu verbessern.';
$LANG['db_reset_danger'] = '<strong>Achtung!</strong> Alle aktuellen Daten werden durch das Zur&uuml;cksetzen gel&ouml;scht!!';
$LANG['db_resetString'] = 'Best&auml;tigung';
$LANG['db_resetString_comment'] = 'Das Zur&uuml;cksetzen der Datenbank wird alle Daten mit den Beispieldaten der Applikation ersetzen.<br>
      Gebe den folgenden Text zur Best&auml;tigung ein: "YesIAmSure".';

//
// E-Mail
//
$LANG['email_subject_group_changed'] = '%app_name% Gruppe ge&auml;ndert';
$LANG['email_subject_group_created'] = '%app_name% Gruppe angelegt';
$LANG['email_subject_group_deleted'] = '%app_name% Gruppe gel&ouml;scht';
$LANG['email_subject_month_created'] = '%app_name% Monat angelegt';
$LANG['email_subject_month_changed'] = '%app_name% Monat geändert';
$LANG['email_subject_month_deleted'] = '%app_name% Monat gelöscht';
$LANG['email_subject_role_changed'] = '%app_name% Rolle ge&auml;ndert';
$LANG['email_subject_role_created'] = '%app_name% Rolle angelegt';
$LANG['email_subject_role_deleted'] = '%app_name% Rolle gel&ouml;scht';
$LANG['email_subject_user_account_changed'] = '%app_name% Benutzerkonto ge&auml;ndert';
$LANG['email_subject_user_account_created'] = '%app_name% Benutzerkonto angelegt';
$LANG['email_subject_user_account_deleted'] = '%app_name% Benutzerkonto gel&ouml;scht';
$LANG['email_subject_user_account_needs_approval'] = '%app_name% Benutzerkonto muss aktiviert werden';
$LANG['email_subject_user_account_mismatch'] = '%app_name% Benutzerkonto Verifizierungsfehler';
$LANG['email_subject_user_account_registered'] = '%app_name% Benutzerkonto registriert';

//
// Error messages
//
$LANG['error_title'] = 'Applicationsfehler';

//
// Footer
//
$LANG['footer_home'] = 'Startseite';
$LANG['footer_help'] = 'Hilfe';
$LANG['footer_about'] = '&Uuml;ber';
$LANG['footer_imprint'] = 'Impressum';

//
// Group
//
$LANG['group_edit_title'] = 'Gruppe editieren: ';
$LANG['group_tab_settings'] = 'Einstellungen';
$LANG['group_tab_members'] = 'Mitgliedschaften';

$LANG['group_alert_edit'] = 'Gruppe aktualisieren';
$LANG['group_alert_edit_success'] = 'Die Informationen f&uuml;r diese Gruppe wurden aktualisiert.';
$LANG['group_alert_save_failed'] = 'Die neuen Informationen f&uuml;r diese Gruppe konnten nicht gespeichert werden. Es gab fehlerhafte Eingaben. Bitte pr&uuml;fe die Fehlermeldungen.';
$LANG['group_name'] = 'Name';
$LANG['group_name_comment'] = '';
$LANG['group_description'] = 'Beschreibung';
$LANG['group_description_comment'] = '';
$LANG['group_managers'] = 'Manager dieser Gruppe';
$LANG['group_managers_comment'] = 'W&auml;hle die Manager dieser Gruppe aus.';
$LANG['group_members'] = 'Mitglieder dieser Gruppe';
$LANG['group_members_comment'] = 'W&auml;hle die Mitglieder dieser Gruppe aus.';

//
// Groups
//
$LANG['groups_title'] = 'Gruppen';
$LANG['groups_alert_group_created'] = 'Die Gruppe wurde angelegt.';
$LANG['groups_alert_group_created_fail'] = 'Die Gruppe wurde nicht angelegt. Bitte &uuml;berpr&uuml;fe den "Gruppe anlegen" Dialog nach Eingabefehlern.';
$LANG['groups_alert_group_deleted'] = 'Die Gruppe wurde gel&ouml;scht.';
$LANG['groups_confirm_delete'] = 'Bist du sicher, dass du diese Gruppe l&ouml;schen willst?';
$LANG['groups_description'] = 'Beschreibung';
$LANG['groups_name'] = 'Name';

//
// Home Page
//
$LANG['home_title'] = 'Willkommen bei ' . $appTitle;

//
// Imprint
// You can add more arrays here in order to display them on the Imprint page
//
$LANG['imprint'] = array ( 
   array (
      'title' => 'Author',
      'text' => '<i class="fa fa-thumbs-o-up fa-3x pull-left" style="color: #999999;"></i>'.$appTitle.' wurde von George Lewe erstellt (<a href="http://www.lewe.com/">Lewe.com</a>).  
      '.$appTitle.' nutzt auch kostenlose Module von anderen gro&szlig;artigen Entwicklern, die dankenswerter Weise
      ihre Arbeit &ouml;ffentlich verf&uuml;gbar machen. Details dazu befinden sich auf der <a href="index.php?action=about">About Seite</a>.',
   ),
   array (
      'title' => 'Inhalt',
      'text' => '<p><i class="fa fa-file-text-o fa-3x pull-left" style="color: #999999;"></i>Die Inhalte von '.$appTitle.' wurden sorgf&auml;tig vorbereitet und
      erstellt. Wo andere Quellen benutzt wurde, wird auch darauf hingewiesen. Sollte dies nicht der Fall sein, bitte informiere George Lewe mittels dieses 
      <a href="http://www.lewe.com/contact">Forumlars</a>.</p> 
      <p>Kein Inhalt der Applikation/Site, ganz oder in Teilen darf vervielf&auml;tigt, reproduziert, kopiert oder wiederwendet werden, in keiner Form, 
      elektronisch oder mechanisch, egal f&uuml;r welchen Zweck ohne ausdr&uuml;ckliche Erlaubnis von George Lewe.</p>',
   ),
   array (
      'title' => 'Links',
      'text' => '<p><i class="fa fa-external-link fa-3x pull-left" style="color: #999999;"></i>Alle Links bei '.$appTitle.' werden als Annehmlichkeit und nur
      zu Informationszwecken angeboten. Sie stellen keine Bef&uuml;rwortung oder Akzeptanz der entfernten Inhalte durch '.$appTitle.' dar, weder in Bezug auf
      Produkte, Services oder Meinungen der verlinkten Anbieter. '.$appTitle.' ist nicht verantwortlich f&uuml;r die Richtigkeit oder Rechtm&auml;&szlig;keit
      der verlinkten Inhalte. Bei Fragen oder Bedenken zu den verlinkten Inhalten kontakiere bitte den Anbieter dort.</p>',
   ),
   array (
      'title' => 'Cookies',
      'text' => '<p><i class="fa fa-download fa-3x pull-left" style="color: #999999;"></i>Diese Applikation benutzt Cookies. Cookies sind kleine Dateien mit 
      applikationsrelevanten Informationen, die auf der lokalen Festplatte gespeichert werden. Sie enthalten keine pers&ouml;nlichen Daten und werden auch 
      nicht &uuml;bertragen. Sie sind aber notwendig, damit diese Applikation funktioniert.</p>
      <p>In der EU ist es Gesetz, die Zustimmung des Nutzers dazu zu erhalten. Mit der Nutzung dieser Applikation wird diese Zustimmung vorausgesetzt.</p>',
   ),
);

if ( $C->read('googleAnalytics') AND $C->read("googleAnalyticsID")) {
   $LANG['imprint'][] = array (
      'title' => 'Google Analytics',
      'text' => '<p><i class="fa fa-google fa-3x pull-left" style="color: #999999;"></i>Diese Website benutzt Google Analytics, einen Webanalysedienst der Google Inc.
      ("Google"). Google Analytics verwendet sog. "Cookies", Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Website
      durch Sie erm&ouml;glichen. Die durch den Cookie erzeugten Informationen &uuml;ber die Nutzung dieser Website werden an Server von Google &uuml;bertragen und 
      dort gespeichert. Diese Server k&ouml;nnen sich den USA befinden.</p>
      <div class="collapse" id="readmore">
         <p>Im Falle der Aktivierung der IP-Anonymisierung auf dieser Webseite, wird Ihre IP-Adresse von Google jedoch innerhalb von Mitgliedstaaten der Europ&auml;ischen
         Union oder in anderen Vertragsstaaten des Abkommens &uuml;ber den Europ&auml;ischen Wirtschaftsraum zuvor gek&uuml;rzt. Nur in Ausnahmef&auml;llen wird die volle IP-Adresse an einen
         Server von Google in den USA &uuml;bertragen und dort gek&uuml;rzt. Die IP-Anonymisierung ist auf dieser Website aktiv. Im Auftrag des Betreibers dieser Website wird Google
         diese Informationen benutzen, um Ihre Nutzung der Website auszuwerten, um Reports &uuml;ber die Websiteaktivit&auml;ten zusammenzustellen und um weitere mit der Websitenutzung
         und der Internetnutzung verbundene Dienstleistungen gegen&uuml;ber dem Websitebetreiber zu erbringen.</p>
         <p>Die im Rahmen von Google Analytics von Ihrem Browser &uuml;bermittelte IP-Adresse wird nicht mit anderen Daten von Google zusammengef&uuml;hrt. Sie k&ouml;nnen die Speicherung
         der Cookies durch eine entsprechende Einstellung Ihrer Browser-Software verhindern; wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall gegebenenfalls nicht
         s&auml;mtliche Funktionen dieser Website vollumf&auml;nglich werden nutzen k&ouml;nnen. Sie k&ouml;nnen dar&uuml;ber hinaus die Erfassung der durch das Cookie erzeugten und auf Ihre Nutzung
         der Website bezogenen Daten (inkl. Ihrer IP-Adresse) an Google sowie die Verarbeitung dieser Daten durch Google verhindern, indem sie das unter dem folgenden Link
         verf&uuml;gbare Browser-Plugin herunterladen und installieren: <a href="http://tools.google.com/dlpage/gaoptout?hl=de">http://tools.google.com/dlpage/gaoptout?hl=de</a>.</p>
         <p>Alternativ zum Browser-Add-On oder innerhalb von Browsern auf mobilen Ger&auml;ten, <a id="GAOptOut" title="Google Analytics Opt-Out-Cookie setzen" href="javascript:gaOptout()">
         klicken Sie bitte diesen Link</a>, um die Erfassung durch Google Analytics innerhalb dieser Website zuk&uuml;nftig zu verhindern (das Opt Out funktioniert nur in dem Browser
         und nur f&uuml;r diese Domain). Dabei wird ein Opt-Out-Cookie auf Ihrem Ger&auml;t abgelegt. L&ouml;schen Sie Ihre Cookies in diesem Browser, m&uuml;ssen Sie diesen Link erneut klicken.</p>
      </div>
      <p><a class="btn btn-default" data-toggle="collapse" data-target="#readmore">Mehr/Weniger Details...</a></p>',
   );
}
      
//
// Log
//
$LANG['log_title'] = 'System Logbuch';
$LANG['log_title_events'] = 'Ereignisse';

$LANG['log_clear'] = 'Periode l&ouml;schen';
$LANG['log_clear_confirm'] = 'Bist du sicher, dass du alle Ereignisse in der angezeigten Periode l&ouml;schen willst?<br>
      Es werden alle Ereignisse jedes Ereignistyps in dieser Periode gel&ouml;scht, auch wenn sie in den Logbuch-Einstellungen verborgen wurden.';
$LANG['log_filterNews'] = 'Nachrichten';
$LANG['log_filterCalopt'] = 'Kalenderoptionen';
$LANG['log_filterConfig'] = 'Konfiguration';
$LANG['log_filterDatabase'] = 'Datenbank';
$LANG['log_filterGroup'] = 'Gruppe';
$LANG['log_filterLogin'] = 'Login';
$LANG['log_filterLoglevel'] = 'Login';
$LANG['log_filterPermission'] = 'Berechtigung';
$LANG['log_filterRegistration'] = 'Registrierung';
$LANG['log_filterRole'] = 'Rolle';
$LANG['log_filterUser'] = 'Nutzer';
$LANG['log_header_when'] = 'Zeitstempel (UTC)';
$LANG['log_header_type'] = 'Ereignistyp';
$LANG['log_header_user'] = 'Nutzer';
$LANG['log_header_event'] = 'Ereignis';
$LANG['log_settings'] = 'Logbuch-Einstellungen';
$LANG['log_settings_event'] = 'Ereignistyp';
$LANG['log_settings_log'] = 'Ereignistyp loggen';
$LANG['log_settings_show'] = 'Ereignistyp anzeigen';
$LANG['log_sort_asc'] = 'Aufsteigend sortieren...';
$LANG['log_sort_desc'] = 'Absteigend sortieren...';

//
// Login
//
$LANG['login_login'] = $appTitle . ' Login';
$LANG['login_username'] = 'Benutzername:';
$LANG['login_password'] = 'Passwort:';
$LANG['login_error_0'] = 'Login erfolgreich';
$LANG['login_error_1'] = 'Benutzername oder Passwort nicht angegeben';
$LANG['login_error_1_text'] = 'Bitte gebe einen g&uuml;tigen Benutzernamen und Passwort an.';
$LANG['login_error_2'] = 'Benutzername unbekannt';
$LANG['login_error_2_text'] = 'Der eingegebene Benutzername ist unbekannt. Bitte versuche es erneut.';
$LANG['login_error_3'] = 'Konto deaktiviert';
$LANG['login_error_3_text'] = 'Dieses Konto ist gesperrt bzw. noch nicht best&aum;tigt. Bitte kontaktiere den Administrator.';
$LANG['login_error_4'] = 'Passwort falsch';
$LANG['login_error_4_text'] = 'Das Passwort ist falsch. Dies war Fehlversuch Nummer %1%. Nach %2% Fehlversuchen wird der Account gesperrt f&uuml;r %3% Sekunden.';
$LANG['login_error_6_text'] = 'Dieser Account ist wegen zu vieler falscher Loginversuche vor&uuml;bergehend gesperrt. Die Grace Periode endet in %1% Sekunden.';
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

//
// Maintenance
//
$LANG['mtce_title'] = 'Unter Wartung';
$LANG['mtce_text'] = 'Wir f&uuml;hren gerade Wartungsarbeiten an der Site durch. Wir entschuldigen die Unanehmlichkeit. Bitte versuche es sp&auml;ter nochmal...';

//
// Menu
//
$LANG['mnu_app'] = $appTitle;
$LANG['mnu_app_homepage'] = 'Startseite';
$LANG['mnu_app_language'] = 'Sprache';
$LANG['mnu_view'] = 'Anzeige';
$LANG['mnu_view_messages'] = 'Benachrichtigungen';
$LANG['mnu_edit'] = 'Bearbeiten';
$LANG['mnu_edit_attachments'] = 'Anh&auml;nge';
$LANG['mnu_edit_messageedit'] = 'Benachrichtigung';
$LANG['mnu_admin'] = 'Administration';
$LANG['mnu_admin_config'] = 'Framework-Konfiguration';
$LANG['mnu_admin_configapp'] = 'Applikations-Konfiguration';
$LANG['mnu_admin_database'] = 'Datenbankverwaltung';
$LANG['mnu_admin_env'] = 'Umgebung';
$LANG['mnu_admin_groups'] = 'Gruppen';
$LANG['mnu_admin_perm'] = "Berechtigungen";
$LANG['mnu_admin_phpinfo'] = 'PHP Info';
$LANG['mnu_admin_roles'] = 'Rollen';
$LANG['mnu_admin_systemlog'] = 'System Log';
$LANG['mnu_admin_users'] = 'Nutzer';
$LANG['mnu_help'] = 'Hilfe';
$LANG['mnu_help_legend'] = 'Legende';
$LANG['mnu_help_help'] = 'Nutzerhandbuch';
$LANG['mnu_help_imprint'] = 'Impressum';
$LANG['mnu_help_about'] = '&Uuml;ber '. $appTitle;
$LANG['mnu_user_login'] = 'Login';
$LANG['mnu_user_register'] = 'Registrieren';
$LANG['mnu_user_logout'] = 'Logout';
$LANG['mnu_user_profile'] = 'User Profil';

//
// Messages
//
$LANG['msg_title'] = 'Benachrichtigungen f&uuml;r: ';
$LANG['msg_title_edit'] = 'Benachrichtigung Erstellen';
$LANG['msg_code'] = 'Sicherheitscode';
$LANG['msg_code_comment'] = 'Bitte geben Sie den Code ein wie angezeigt. Gro&szlig;- und Kleinschreibung ist nicht relevant.';
$LANG['msg_code_new'] = 'Neues Bild laden';
$LANG['msg_confirm_all_confirm'] = 'Bist du sicher, dass du alle Benachrichtigungen best&auml;tigen willst?';
$LANG['msg_confirm_confirm'] = 'Bist du sicher, dass du die Benchrichtigung "%s" best&auml;tigen willst? Sie wird nicht gel&ouml;scht aber nicht mehr automatisch angezeigt.';
$LANG['msg_content_type'] = 'Inhaltstyp';
$LANG['msg_content_type_desc'] = 'Mit dem Inhaltstyp kann eine farbliche Kennzeichnung von Benachrichtigungen erreicht werden (nicht bei E-mail).';
$LANG['msg_content_type_info'] = 'Information';
$LANG['msg_content_type_primary'] = 'Prim&auml;r';
$LANG['msg_content_type_success'] = 'Erfolg';
$LANG['msg_content_type_warning'] = 'Warnung';
$LANG['msg_content_type_danger'] = 'Gefahr';
$LANG['msg_delete_all_confirm'] = 'Bist du sicher, dass du alle Benchrichtigungen l&ouml;schen willst?';
$LANG['msg_delete_confirm'] = 'Bist du sicher, dass du die Benchrichtigung "%s" l&ouml;schen willst?';
$LANG['msg_msg_body'] = 'Text';
$LANG['msg_msg_body_comment'] = 'Gib hier den Text der Benachrichtigung ein.';
$LANG['msg_msg_body_sample'] = '...dein Text hier...';
$LANG['msg_msg_title'] = 'Titel';
$LANG['msg_msg_title_comment'] = 'Gib hier den Titel der Benachrichtigung ein.';
$LANG['msg_msg_title_sample'] = '...dien Titel hier...';
$LANG['msg_msg_sent'] = 'Die Benachrichtigung wurde gesendet.';
$LANG['msg_msg_sent_text'] = 'Deine Benachrichtigung wurde an die ausgew&auml;hlten Empf&auml;nger gesendet.';
$LANG['msg_no_file_subject'] = 'Keine Datei ausgew&auml;hlt';
$LANG['msg_no_file_text'] = 'Es muss mindestens eine Datei f&uuml;r diese Operation ausgew&auml;hlt werden.';
$LANG['msg_no_group_subject'] = 'Keine Gruppe ausgew&auml;hlt';
$LANG['msg_no_group_text'] = 'Es muss mindestens eine Gruppe ausgew&auml;hlt werden, an die die Benachrichtigung gesendet werden soll.';
$LANG['msg_no_text_subject'] = 'Kein Betreff und/oder Text';
$LANG['msg_no_text_text'] = 'Es muss ein Betreff und ein Text f&uuml;r die Benachrichtigung eingegeben werden.';
$LANG['msg_no_user_subject'] = 'Kein Benutzer ausgew&auml;hlt';
$LANG['msg_no_user_text'] = 'Es muss mindestens ein Nutzer ausgew&auml;hlt werden, an den die Benachrichtigung gesendet werden soll.';
$LANG['msg_sendto'] = 'Empf&auml;nger';
$LANG['msg_sendto_desc'] = 'W&auml;hle hier den oder die Empf&auml;nger der Benachrichtigung aus.';
$LANG['msg_sendto_all'] = 'Alle';
$LANG['msg_sendto_group'] = 'Gruppe:';
$LANG['msg_sendto_user'] = 'Nutzer:';
$LANG['msg_type'] = 'Benachrichtigungstyp';
$LANG['msg_type_desc'] = 'Hier kann der Benachrichtigungstyp ausgew&auml;hlt werden.<br>
      Eine stille Benachrichtigung wird nur auf die Benachrichtigungsseite gesetzt.<br>
      Eine Prompt Benachrichtigung f&uuml;hrt dazu, dass die Benachrichtigungsseite automatisch angezeigt wird, wenn sich der Benutzer einlogt.';
$LANG['msg_type_email'] = 'E-Mail';
$LANG['msg_type_silent'] = 'Stille Benachrichtigung';
$LANG['msg_type_popup'] = 'Prompt Benachrichtigung';

//
// Modal dialogs
//
$LANG['modal_confirm'] = 'Bitte Best&auml;tigen';

//
// Paging
//
$LANG['page_first'] = 'Zur ersten Seite...';
$LANG['page_prev'] = 'Zur vorherigen Seite...';
$LANG['page_page'] = 'Zur Seite %s...';
$LANG['page_next'] = 'Zur n&auml;chsten Seite...';
$LANG['page_last'] = 'Zur letzten Seite...';

//
// Password rules
//
$LANG['password_rules_low'] = '<br>Die Passwort Sicherheit ist zurzeit auf "Niedrig" eingestellt. Daraus ergeben sich folgende Regeln:<br>
     - Mindestens 4 Zeichen<br>';
$LANG['password_rules_medium'] = '<br>Die Passwort Sicherheit ist zurzeit auf "Medium" eingestellt. Daraus ergeben sich folgende Regeln:<br>
      - Mindestens 6 Zeichen<br>
      - Mindestens ein Gro&szlig;buchstabe<br>
      - Mindestens ein Kleinbuchstabe<br>
      - Mindestens eine Zahl<br>';
$LANG['password_rules_high'] = '<br>Die Passwort Sicherheit ist zurzeit auf "Hoch" eingestellt. Daraus ergeben sich folgende Regeln:<br>
      - Mindestens 8 Zeichen<br>
      - Mindestens ein Gro&szlig;buchstabe<br>
      - Mindestens ein Kleinbuchstabe<br>
      - Mindestens eine Zahl<br>
      - Mindestens ein Sonderzeichen<br>';

//
// Permissions
//
$LANG['perm_title'] = 'Berechtigungsschema';
$LANG['perm_tab_general'] = 'Allgemein';
$LANG['perm_tab_features'] = 'Funktionen';

$LANG['perm_active'] = '(Aktiv)';
$LANG['perm_activate_scheme'] = 'Schema aktivieren';
$LANG['perm_activate_confirm'] = 'Soll dieses Berechtigungsschema aktiviert werden?';
$LANG['perm_create_scheme'] = 'Schema anlegen';
$LANG['perm_create_scheme_desc'] = 'Gib einen Namen f&uuml;r das neue Schema ein. Es wird mit Standardeinstellungen direkt geladen. 
      Alle nicht gespeicherten &Auml;nderungen des momentanen Schemas gehen verloren.';
$LANG['perm_delete_scheme'] = 'Schema l&ouml;schen';
$LANG['perm_delete_confirm'] = 'Soll dieses Berechtigungsschema gel&ouml;scht werden? Dadurch wird das Standard Schema geladen und aktiviert.';
$LANG['perm_inactive'] = '(Inaktiv)';
$LANG['perm_reset_scheme'] = 'Schema zur&uuml;cksetzen';
$LANG['perm_reset_confirm'] = 'Soll dieses Berechtigungsschema auf das "Default" Schema zur&uuml;ckgesetzt werden?';
$LANG['perm_save_scheme'] = 'Schema speichern';
$LANG['perm_select_scheme'] = 'Schema ausw&auml;hlen';
$LANG['perm_select_confirm'] = 'Soll dieses Berechtigungsschema geladen werden? Alle nicht gespeicherten &Auml;nderungen des momentanen Schemas gehen verloren.';
$LANG['perm_view_by_perm'] = 'Nach Berechtigungen anzeigen';
$LANG['perm_view_by_role'] = 'Nach Rollen anzeigen';

$LANG['perm_admin_title'] = 'Administration (System)';
$LANG['perm_admin_desc'] = 'Erlaubt den Zugriff auf die Systemadministrationsseiten.';
$LANG['perm_groups_title'] = 'Gruppen (Bearbeiten)';
$LANG['perm_groups_desc'] = 'Erlaubt as Listen und Bearbeiten von Gruppen.';
$LANG['perm_groupmemberships_title'] = 'Benutzerprofil (Gruppenmitgliedschaften)';
$LANG['perm_groupmemberships_desc'] = 'Erlaubt es, Benutzer als Mitglied oder Manager Gruppen zuzuordnen.';
$LANG['perm_messageview_title'] = 'Benachrichtigungen (Anzeigen)';
$LANG['perm_messageview_desc'] = 'Erlaubt den Zugriff auf die Benachrichtigung-Anzeige-Seite.';
$LANG['perm_messageedit_title'] = 'Benachrichtigungen (Erstellen)';
$LANG['perm_messageedit_desc'] = 'Erlaubt den Zugriff auf die Benachrichtigung-Erstellen-Seite.';
$LANG['perm_roles_title'] = 'Rollen (Bearbeiten)';
$LANG['perm_roles_desc'] = 'Erlaubt das Listen und Bearbeiten von Rollen.';
$LANG['perm_upload_title'] = 'Anh&auml;nge';
$LANG['perm_upload_desc'] = 'Erlaubt den Zugriff auf und das Hochladen von Dateien.';
$LANG['perm_useraccount_title'] = 'Benutzerprofil (Kontoeinstellungen)';
$LANG['perm_useraccount_desc'] = 'Erlaubt das Bearbeiten des Konto Reiters im Nutzerprofil.';
$LANG['perm_useradmin_title'] = 'Benutzerprofil (Anzeigen und Hinzuf&uuml;gen)';
$LANG['perm_useradmin_desc'] = 'Erlaubt das Listen und Hinzuf&uuml;gen von Benutzerkonten.';
$LANG['perm_useredit_title'] = 'Benutzerprofil (Bearbeiten)';
$LANG['perm_useredit_desc'] = 'Erlaubt das Bearbeiten des eignen Nutzerprofils.';
$LANG['perm_userimport_title'] = 'Benutzer Importieren';
$LANG['perm_userimport_desc'] = 'Erlaubt das Importieren von Benutzern via CSV.';
$LANG['perm_viewprofile_title'] = 'Benutzerprofil (Anzeigen)';
$LANG['perm_viewprofile_desc'] = 'Erlaubt den Zugriff auf die Nutzerprofil-Anzeige-Seite mit Basisinformationen wie Name oder Telefonnummer. Das Anzeigen von User Popups ist 
      ebenfalls abh&auml;ngig von dieser Berechtigung.';

//
// Phpinfo
//
$LANG['phpinfo_title'] = 'PHP Info';

//
// Profile
//
$LANG['profile_create_title'] = 'Neues Benutzerkonto anlegen';
$LANG['profile_create_mail'] = 'E-Mail an Benutzer senden';
$LANG['profile_create_mail_comment'] = 'Sendet eine E-Mail an den angelegten Benutzer.';

$LANG['profile_view_title'] = 'Benutzerkonto von: ';

$LANG['profile_edit_title'] = 'Benutzerkonto editieren: ';
$LANG['profile_tab_account'] = 'Konto';
$LANG['profile_tab_avatar'] = 'Avatar';
$LANG['profile_tab_contact'] = 'Kontakt';
$LANG['profile_tab_custom'] = 'Extra';
$LANG['profile_tab_groups'] = 'Gruppen';
$LANG['profile_tab_notifications'] = 'Benachrichtigungen';
$LANG['profile_tab_password'] = 'Passwort';
$LANG['profile_tab_personal'] = 'Person';

$LANG['profile_alert_create'] = 'Benutzer anlegen';
$LANG['profile_alert_create_success'] = 'Das neue Benutzerkonto wurde angelegt.';
$LANG['profile_alert_update'] = 'Benutzerprofil aktualisiert';
$LANG['profile_alert_update_success'] = 'Die Informationen f&uuml;r diesen Benutzer wurden aktualisert.';
$LANG['profile_alert_save_failed'] = 'Die neuen Informationen f&uuml;r diesen Benutzer konnten nicht gespeichert werden. Es gab fehlerhafte Eingaben. Bitte pr&uuml;fe die Tabs nach Fehlermeldungen.';
$LANG['profile_avatar'] = 'Avatar';
$LANG['profile_avatar_comment'] = 'Wenn kein eigener Avatar hochgeladen wurde, wird ein Standard-Avatar angezeigt.';
$LANG['profile_avatar_available'] = 'Verf&uuml;gbare Standard Avatare';
$LANG['profile_avatar_available_comment'] = 'W&auml;hle einen der standard Avatare, erstellt von <a href="http://www.iconshock.com/icon_sets/vector-user-icons/" target="_blank">IconShock</a>.';
$LANG['profile_avatar_upload'] = 'Avatar hochladen';
$LANG['profile_avatar_upload_comment'] = 'Es kann ein individueller Avatar hochlgeaden werden. Die Dateigr&ouml;&szlig;e ist limitiert auf %d Bytes, das Bild sollte 
      80x80 Pixel gro&szlig; sein (wird ohnehin nur in dieser Gr&ouml;&szlig;e dargestellt) und die erlaubten Formate sind "%s".';
$LANG['profile_custom1'] = $C->read('userCustom1');
$LANG['profile_custom1_comment'] = 'Gib einen Text von maximal 80 Zeichen ein.';
$LANG['profile_custom2'] = $C->read('userCustom2');
$LANG['profile_custom2_comment'] = 'Gib einen Text von maximal 80 Zeichen ein.';
$LANG['profile_custom3'] = $C->read('userCustom3');
$LANG['profile_custom3_comment'] = 'Gib einen Text von maximal 80 Zeichen ein.';
$LANG['profile_custom4'] = $C->read('userCustom4');
$LANG['profile_custom4_comment'] = 'Gib einen Text von maximal 80 Zeichen ein.';
$LANG['profile_custom5'] = $C->read('userCustom5');
$LANG['profile_custom5_comment'] = 'Gib einen Text von maximal 80 Zeichen ein.';
$LANG['profile_email'] = 'E-Mail';
$LANG['profile_email_comment'] = '';
$LANG['profile_facebook'] = 'Facebook';
$LANG['profile_facebook_comment'] = '';
$LANG['profile_firstname'] = 'Vorname';
$LANG['profile_firstname_comment'] = '';
$LANG['profile_gender'] = 'Geschlecht';
$LANG['profile_gender_comment'] = '';
$LANG['profile_gender_male'] = 'M&auml;nnlich';
$LANG['profile_gender_female'] = 'Weiblich';
$LANG['profile_google'] = 'Google+';
$LANG['profile_google_comment'] = '';
$LANG['profile_id'] = 'ID';
$LANG['profile_id_comment'] = '';
$LANG['profile_language'] = 'Benutzersprache';
$LANG['profile_language_comment'] = 'Hier kann eine benutzerspezifische Sprache f&uuml;r die Oberfl&auml;che eingestellt werden.';
$LANG['profile_lastname'] = 'Nachname';
$LANG['profile_lastname_comment'] = '';
$LANG['profile_linkedin'] = 'LinkedIn';
$LANG['profile_linkedin_comment'] = '';
$LANG['profile_locked'] = '<i class="glyphicon glyphicon-menu glyphicon-lock text-danger" style="font-size: 80%; padding-right: 16px;"></i>Gesperrt';
$LANG['profile_locked_comment'] = 'Das Konto kann hier gesperrt werden, so dass kein Einloggen m&ouml;glich ist.';
$LANG['profile_managerships'] = 'Manager von';
$LANG['profile_managerships_comment'] = 'W&auml;hle hier die Gruppen, von denen dieser Benutzer Manager ist. Sollte eine Gruppe sowohl hier als auch
      in der Mitgliedsliste gew&auml;hlt werden, wird die Managerposition &uuml;bernommen.';
$LANG['profile_memberships'] = 'Mitglied von';
$LANG['profile_memberships_comment'] = 'W&auml;hle hier die Gruppen, in denen dieser Benutzer Mitglied ist. Sollte eine Gruppe sowohl hier als auch
      in der Managerliste gew&auml;hlt werden, wird die Managerposition &uuml;bernommen.';
$LANG['profile_menuBar'] = 'Men&uuml;zeilenanzeige';
$LANG['profile_menuBar_comment'] = 'Mit diesem Schalter kann die Farbkombination der Men&uuml;zeile invertiert werden. F&uuml;r einige Themes sieht das besser aus.';
$LANG['profile_menuBar_default'] = 'Default';
$LANG['profile_menuBar_inverse'] = 'Invertiert';
$LANG['profile_menuBar_normal'] = 'Normal';
$LANG['profile_mobilephone'] = 'Handy';
$LANG['profile_mobilephone_comment'] = '';
$LANG['profile_notify'] = 'E-Mail Benachrichtigungen';
$LANG['profile_notify_comment'] = 'W&auml;hle die Ereignisse, &uuml;ber die du per E-Mail benachrichtigt werden m&ouml;chtest. Dies beinhaltet das Anlegen, &Auml;ndern und L&ouml;schen. Mit Strg + Klick kannst du Ereignisse an- und abw&auml;hlen.';
$LANG['profile_notifyGroupEvents'] = 'Gruppen-Ereignisse';
$LANG['profile_notifyRoleEvents'] = 'Rollen-Ereignisse';
$LANG['profile_notifyUserEvents'] = 'Benutzerkonten-Ereignisse';
$LANG['profile_onhold'] = '<i class="glyphicon glyphicon-menu glyphicon-time text-warning" style="font-size: 80%; padding-right: 16px;"></i>Tempor&auml;r gesperrt';
$LANG['profile_onhold_comment'] = 'Dieser Zustand tritt ein, wenn der Benutzer zu h&auml;ufig ein falsches Passwort eingegeben hat. Dann wirkt die Schonfrist, 
      in der das Konto gesperrt ist. Die L&auml;nge der Schonfrist kann in der Konfiguration eingestellt werden. Hier kann die Schonfrist manuell wieder aufgehoben werden.';
$LANG['profile_password'] = 'Passwort';
$LANG['profile_password_comment'] = 'Hier kann ein neues Passwort eingegeben werden. Wenn das Feld leer bleibt, wird das aktuelle Passwort nicht ver&auml;ndert.<br>
      Erlaubt sind Gro&szlig;- und Kleinbuchstaben, Zahlen und die Sonderzeichen: !@#$%^&amp;*()';
$LANG['profile_password2'] = 'Passwort wiederholen';
$LANG['profile_password2_comment'] = 'Wiederhole hier das neue Passwort.';
$LANG['profile_phone'] = 'Telefon';
$LANG['profile_phone_comment'] = '';
$LANG['profile_position'] = 'Position';
$LANG['profile_position_comment'] = '';
$LANG['profile_role'] = 'Rolle';
$LANG['profile_role_comment'] = 'Hier kann die Rolle gew&auml;hlt werden, die dieser Benutzer einnehmen soll. Damit sind bestimmte Berechtigungen verkn&uuml;pft.';
$LANG['profile_skype'] = 'Skype';
$LANG['profile_skype_comment'] = '';
$LANG['profile_theme'] = 'Theme';
$LANG['profile_theme_comment'] = 'Hier kann ein benutzerspezifisches Theme f&uuml;r die Oberfl&auml;che eingestellt werden.';
$LANG['profile_title'] = 'Titel';
$LANG['profile_title_comment'] = '';
$LANG['profile_twitter'] = 'Twitter';
$LANG['profile_twitter_comment'] = '';
$LANG['profile_username'] = 'Loginname';
$LANG['profile_username_comment'] = 'Der Loginname kann f&uuml;r existierende Benutzerkonten nicht ge&auml;ndert werden.';
$LANG['profile_verify'] = '<i class="glyphicon glyphicon-menu glyphicon-exclamation-sign text-success" style="font-size: 80%; padding-right: 16px;"></i>Zu verifizieren';
$LANG['profile_verify_comment'] = 'Wenn der Benutzer sich selbst registriert hat, aber die Best&auml;tigung noch nicht durchgef&uuml;hrt hat, gilt dieser Zustand. Der Account ist angelegt
      aber gesperrt. Hier kann dieser Zustand manuell aufgehoben werden.';

//
// Register
//
$LANG['register_title'] = 'Benutzer Registrierung';
$LANG['register_alert_success'] = 'Das Benutzerkonto wurde registriert und eine E-mail mit den entsprechenden Informationen versendet.';
$LANG['register_alert_failed'] = 'Die Registrierung ist fehlgeschlagen. Bitte &uuml;ber&uuml;fe die Eingaben.';
$LANG['register_code'] = 'Sicherheitscode';
$LANG['register_code_comment'] = 'Bitte geben Sie den Code ein wie angezeigt. Gro&szlig;- und Kleinschreibung ist nicht relevant.';
$LANG['register_code_new'] = 'Neues Bild laden';
$LANG['register_email'] = 'E-Mail';
$LANG['register_email_comment'] = '';
$LANG['register_firstname'] = 'Vorname';
$LANG['register_firstname_comment'] = '';
$LANG['register_lastname'] = 'Nachname';
$LANG['register_lastname_comment'] = '';
$LANG['register_password'] = 'Passwort';
$LANG['register_password_comment'] = 'Bitte gebe ein Passwort ein.<br>
      Erlaubt sind Gro&szlig;- und Kleinbuchstaben, Zahlen und die Sonderzeichen: !@#$%^&amp;*()';
$LANG['register_password2'] = 'Passwort wiederholen';
$LANG['register_password2_comment'] = 'Wiederhole hier das Passwort.';
$LANG['register_username'] = 'Loginname';
$LANG['register_username_comment'] = 'Der Loginname kann f&uuml;r existierende Benutzerkonten nicht ge&auml;ndert werden.';

//
// Role
//
$LANG['role_edit_title'] = 'Rolle editieren: ';
$LANG['role_alert_edit'] = 'Rolle aktualisieren';
$LANG['role_alert_edit_success'] = 'Die Informationen f&uuml;r diese Rolle wurden aktualisiert.';
$LANG['role_alert_save_failed'] = 'Die neuen Informationen f&uuml;r diese Rolle konnten nicht gespeichert werden. Es gab fehlerhafte Eingaben. Bitte pr&uuml;fe die Fehlermeldungen.';
$LANG['role_alert_save_failed_duplicate'] = 'Die neuen Informationen f&uuml;r diese Rolle konnten nicht gespeichert werden. Eine Rolle mit dem Namen existiert bereits.';
$LANG['role_color'] = 'Rollenfarbe';
$LANG['role_color_comment'] = 'Benutzer Icons werden entsprechend der Rolle farbig dargestellt.';
$LANG['role_color_danger'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-danger"></span>';
$LANG['role_color_default'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-default"></span>';
$LANG['role_color_info'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-info"></span>';
$LANG['role_color_primary'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-primary"></span>';
$LANG['role_color_success'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-success"></span>';
$LANG['role_color_warning'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-warning"></span>';
$LANG['role_name'] = 'Name';
$LANG['role_name_comment'] = '';
$LANG['role_description'] = 'Beschreibung';
$LANG['role_description_comment'] = '';

//
// Roles
//
$LANG['roles_title'] = 'Rollen';
$LANG['roles_alert_created'] = 'Die Rollee wurde angelegt.';
$LANG['roles_alert_created_fail_input'] = 'Die Rolle wurde nicht angelegt. Bitte &uuml;berpr&uuml;fe den "Rolle anlegen" Dialog nach Eingabefehlern.';
$LANG['roles_alert_created_fail_duplicate'] = 'Die Rolle wurde nicht angelegt. Eine Rolle mit diesem Namen existiert bereits.';
$LANG['roles_alert_deleted'] = 'Die Rolle wurde gel&ouml;scht.';
$LANG['roles_confirm_delete'] = 'Bist du sicher, dass du diese Rolle l&ouml;schen willst?';
$LANG['roles_description'] = 'Beschreibung';
$LANG['roles_name'] = 'Name';

//
// Status Bar
//
$LANG['status_logged_in'] = 'Du bist eingeloggt als ';
$LANG['status_logged_out'] = 'Nicht eingeloggt';
$LANG['status_ut_user'] = 'Regul&auml;rer Nutzer';
$LANG['status_ut_manager'] = 'Manager der Gruppe: ';
$LANG['status_ut_director'] = 'Direktor';
$LANG['status_ut_assistant'] = 'Assistent';
$LANG['status_ut_admin'] = 'Administrator';

//
// Upload Errors
//
$LANG['upl_error_0'] = 'Die Datei "%s" wurde erfolgreich hochgeladen.';
$LANG['upl_error_1'] = 'Die hochgeladene Datei &uuml;bersteigt die maximale Dateigr&ouml;&szlig;e der Severkonfiguration.';
$LANG['upl_error_2'] = 'Die hochgeladene Datei &uuml;bersteigt die MAX_FILE_SIZE Direktive des HTML Formulars.';
$LANG['upl_error_3'] = 'Die Datei wurde nur teilweise hochgeladen.';
$LANG['upl_error_4'] = 'Es wurde keine Datei hochgeladen.';
$LANG['upl_error_10'] = 'Bitte w&auml;hle eine Datei zum Hochladen aus.';
$LANG['upl_error_11'] = 'Nur Dateien der folgenden Formate sind erlaubt: %s';
$LANG['upl_error_12'] = 'Der Dateiname enth&auml;lt ung&uuml;ltige Zeichen. Bitte benutze nur alphanumerische Zeichen und den Unterstrich. Der Dateiname muss mit einem Punkt und der Erweiterung enden.';
$LANG['upl_error_13'] = 'Der Dateiname &uuml;bersteigt die maximale L&auml;nge von %d Zeichen.';
$LANG['upl_error_14'] = 'Das Uploadverzeichnis existiert nicht.';
$LANG['upl_error_15'] = 'Eine Datei mit dem Name "%s" existiert bereits.';
$LANG['upl_error_16'] = 'Die hochgeladene Datei wurde in "%s" umbenannt.';
$LANG['upl_error_17'] = 'Die Datei "%s" existiert nicht.';
$LANG['upl_error_18'] = 'Ein unbestimmter Fehler ist bem Hochladen aufgetreten.';
$LANG['upl_error_19'] = 'Die Datei konnte nicht ins Zielverzeichnis geschrieben werden.';

//
// Users
//
$LANG['users_title'] = 'Benutzerkonten';
$LANG['users_alert_archive_selected_users'] = 'Die ausgew&auml;hlten Nutzer wurden archiviert.';
$LANG['users_alert_archive_selected_users_failed'] = 'Ein oder mehr Benutzer existieren bereits im Archive. Das kann der gleiche Benutzer oder einer mit selbem Benutzernamen sein.<br>Bitte l&ouml;sche diese archivierten Benutzer zuerst.';
$LANG['users_alert_delete_selected_users'] = 'Die ausgew&auml;hlten Nutzer wurden gel&ouml;scht.';
$LANG['users_alert_reset_password_selected'] = 'Die Passw&ouml;rter der ausgew&auml;hlten Nutzer wurden zur&uuml;ckgesetzt und eine entsprechende e-Mail an sie versendet.';
$LANG['users_alert_restore_selected_users'] = 'Die ausgew&auml;hlten Nutzer wurden wiederhergestellt.';
$LANG['users_alert_restore_selected_users_failed'] = 'Ein oder mehr Benutzer existieren bereits als aktive Benutzer. Das kann der gleiche Benutzer oder einer mit selbem Benutzernamen sein.<br>Bitte l&ouml;sche diese aktiven Benutzer zuerst.';
$LANG['users_attributes'] = 'Attribute';
$LANG['users_attribute_locked'] = 'Konto gesperrt';
$LANG['users_attribute_hidden'] = 'Konto verborgen';
$LANG['users_attribute_onhold'] = 'Konto tempor&auml;r gesperrt';
$LANG['users_attribute_verify'] = 'Konto zu verifizieren';
$LANG['users_confirm_archive'] = 'Bist du sicher, dass du die ausgew&auml;hlten Nutzer archivieren m&ouml;chtest?';
$LANG['users_confirm_delete'] = 'Bist du sicher, dass du die ausgew&auml;hlten Nutzer l&ouml;schen m&ouml;chtest?';
$LANG['users_confirm_password'] = 'Bist du sicher, dass du die Passw&ouml;rter der ausgew&auml;hlten Nutzer zur&uuml;cksetzen m&ouml;chtest?';
$LANG['users_confirm_restore'] = 'Bist du sicher, dass du die ausgew&auml;hlten Nutzer wieder aktivieren m&ouml;chtest?';
$LANG['users_created'] = 'Erstellt';
$LANG['users_last_login'] = 'Letztes Login';
$LANG['users_tab_active'] = 'Aktive Nutzer';
$LANG['users_tab_archived'] = 'Archivierte Nutzer';
$LANG['users_user'] = 'Nutzer';

//
// User Import
//
$LANG['imp_title'] = 'CSV Benutzer Import';

$LANG['imp_file'] = 'CSV Datei';
$LANG['imp_alert_help'] = 'Die Dokumentation zum CSV Import findest du <a href="https://georgelewe.atlassian.net/wiki/display/TCNEO/User+Import" target="_blank">hier</a>.';
$LANG['imp_alert_success'] = 'CSV Import erfolgreich';
$LANG['imp_alert_success_text'] = '%s Benutzer wurden erfolgreich importiert.';
$LANG['imp_file_comment'] = 'Lade eine CSV Datei hoch. Details zum Format findest du <a href="https://georgelewe.atlassian.net/wiki/display/TCNEO/User+Import" target="_blank">hier</a>. 
      Die Gr&ouml;&szlig;e der Datei darf bis zu %d KBytes betragen und kann folgende Formate haben: "%s".';
$LANG['imp_group'] = 'Gruppe';
$LANG['imp_group_comment'] = 'W&auml;hle die Gruppe, der die importierten Benutzer zugeordnet werden sollen.';
$LANG['imp_role'] = 'Rolle';
$LANG['imp_role_comment'] = 'W&auml;hle die Rolle, der die importierten Benutzer zugeordnet werden sollen.';
$LANG['imp_hidden'] = 'Verborgen';
$LANG['imp_hidden_comment'] = 'W&auml;hle diese Option, wenn die importierten Benutzer als "verborgen" gekennzeichnet werden sollen.';
$LANG['imp_locked'] = 'Gesperrt';
$LANG['imp_locked_comment'] = 'W&auml;hle diese Option, wenn die importierten Benutzer als "gesperrt" gekennzeichnet werden sollen.';
?>
