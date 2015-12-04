<?php
/**
 * deutsch.php
 * 
 * Language file (German)
 *
 * @category TeamCal Neo 
 * @version 0.3.004
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Common
 */
$LANG['absence'] = 'Abwesenheitstyp';
$LANG['action'] = 'Aktion';
$LANG['all'] = 'Alle';
$LANG['auto'] = 'Automatisch';
$LANG['avatar'] = 'Avatar';
$LANG['back_to_top'] = 'Zur&uuml;ck zum Anfang';
$LANG['custom'] = 'Individuell';
$LANG['description'] = 'Beschreibung';
$LANG['display'] = 'Anzeige';
$LANG['from'] = 'Von';
$LANG['general'] = 'Allgemein';
$LANG['group'] = 'Gruppe';
$LANG['license'] = 'Noch nicht verf&uuml;gbar...';
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
$LANG['name'] = 'Name';
$LANG['none'] = 'Keine/r';
$LANG['options'] = 'Optionen';
$LANG['period'] = 'Periode';
$LANG['period_custom'] = 'Individuell';
$LANG['period_month'] = 'Aktueller Monat';
$LANG['period_quarter'] = 'Aktuelles Quartal';
$LANG['period_half'] = 'Aktuelles Halbjahr';
$LANG['period_year'] = 'Aktuelles Jahr';
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
$LANG['weeknumber'] = 'Kalenderwoche';

/**
 * About page
 */
$LANG['about_version'] = 'Version';
$LANG['about_copyright'] = 'Copyright';
$LANG['about_license'] = 'Lizenz';
$LANG['about_credits'] = 'Dank an';
$LANG['about_for'] = 'f&uuml;r';
$LANG['about_and'] = 'und';
$LANG['about_misc'] = 'viele Nutzer f&uuml;r Tests und Vorschl&auml;ge...';
$LANG['about_view_releaseinfo'] = 'Releaseinfo &raquo;';

/**
 * Absences
 */
$LANG['abs_list_title'] = 'Abwesenheitstypen';
$LANG['abs_edit_title'] = 'Abwesenheitstyp bearbeiten: ';
$LANG['abs_icon_title'] = 'Icon-Auswahl: ';
$LANG['abs_alert_edit'] = 'Abwesenheitstyp aktualisieren';
$LANG['abs_alert_edit_success'] = 'Die Informationen f&uuml;r diesen Abwesenheitstyp wurden aktualisiert.';
$LANG['abs_alert_created'] = 'Der Abwesenheitstyp wurde angelegt.';
$LANG['abs_alert_created_fail'] = 'Der Abwesenheitstyp wurde nicht angelegt. Bitte &uuml;berpr&uuml;fe den "Abwesenheitstyp anlegen" Dialog nach Eingabefehlern.';
$LANG['abs_alert_deleted'] = 'Der Abwesenheitstyp wurde gel&ouml;scht.';
$LANG['abs_alert_save_failed'] = 'Die neuen Informationen f&uuml;r diesen Abwesenheitstyp konnten nicht gespeichert. Es gab fehlerhafte Eingaben. Bitte pr&uuml;fe die Fehlermeldungen.';
$LANG['abs_allowance'] = 'Erlaubte Anzahl';
$LANG['abs_allowance_comment'] = 'Hier kann die erlaubte Anzahl pro Kalenderjahr f&uuml;r diesen Typen gesetzt werden. Im Nutzerprofil 
      wird die genommene und noch verbleibende Anzahl angezeigt (Ein negativer Wert in der Anzeige bedeutet, dass der Nutzer die erlaubte Anzahl 
      &uuml;berschritten hat.). Wenn der Wert auf 0 gesetzt wird, gilt eine unbegrenzte Erlaubnis.';
$LANG['abs_approval_required'] = 'Genehmigung erforderlich';
$LANG['abs_approval_required_comment'] = 'Dieser Schalter macht den Typen genehmigungspflichtig durch einen Manager, Direktor oder Administrator. Ein normaler Nutzer wird dann eine 
      Fehlermeldung erhalten, wenn er diesen Typen eintr&auml;gt. Der Manager der Gruppe erh&auml;lt aber eine E-Mail, dass eine Genehmigung seinerseits erforderlich ist. 
      Er kann dann den Kalender dieses Nutzers bearbeiten und die entsprechende Abwesenheit eintragen.';
$LANG['abs_bgcolor'] = 'Hintergrundfarbe';
$LANG['abs_bgcolor_comment'] = 'Die Hintergrundfarbe wird im Kalender benutzt, egal ob Symbol oder Icon gew&auml;hlt ist. Ein Farbdialog erscheint beim Klicken in das Feld.';
$LANG['abs_bgtrans'] = 'Hintergrund Transparent';
$LANG['abs_bgtrans_comment'] = 'Mit dieser Option wird keine individuelle Hintergrundfarbe gesetzt, sondern die des darunter liegenden Objektes benutzt.';
$LANG['abs_color'] = 'Textfarbe';
$LANG['abs_color_comment'] = 'Wenn das Symbol benutzt wird (kein Icon), wird diese Textfarbe benutzt. Ein Farbdialog erscheint beim Klicken in das Feld.';
$LANG['abs_confidential'] = 'Vertraulich';
$LANG['abs_confidential_comment'] = 'Dieser Schalter macht den Typen "vertraulich". Normale Nutzer k&ouml;nnen diese Abwesenheit nicht im Kalender 
      sehen, ausser es ist ihre eigene Abwesenheit. Dies kann f&uuml;r sensitive Abwesenheiten wie "Krankheit" n&uuml;tzlich sein.';
$LANG['abs_confirm_delete'] = 'Bist du sicher, dass du den Abwesenheitstyp "%s" l&ouml;schen willst?<br>Alle bestehenden Eintr&auml;ge werden mit "Anwesend" ersetzt.';
$LANG['abs_counts_as'] = 'Z&auml;hlt als';
$LANG['abs_counts_as_comment'] = 'Hier kann ausgew&auml;hlt werden, ob die genommenen Tage diese Abwesenheitstyps gegen die Erlaubnis eines anderen Typs z&auml;hlen. 
      Wenn ein anderer Typ gew&auml;hlt wird, wird die Erlaubnis diese Typs hier nicht in Betracht gezogen, nur die des anderen Typs.<br>
      Beispiel: "Urlaub Halbtag" mit Faktor z&auml;hlt gegen die Erlaubnis des Typs "Urlaub".';
$LANG['abs_counts_as_present'] = 'Z&auml;hlt als anwesend';
$LANG['abs_counts_as_present_comment'] = 'Dieser Schalter definiert einen Typen als "anwesend". Dies bietet sich z.B. beim Abwesenheitstyp 
      "Heimarbeit" an. Weil die Person arbeitet, m&ouml;chte man dies nicht als "abwesend" z&auml;hlen. Mit diesem Schalter aktiviert wird dann der Typ 
      in den Summen als anwesend gewertet. Somit w&uuml;rde "Heimarbeit" dann auch nicht in den Abwesenheiten angezeigt.';
$LANG['abs_display'] = 'Anzeige';
$LANG['abs_display_comment'] = '';
$LANG['abs_factor'] = 'Faktor';
$LANG['abs_factor_comment'] = 'TeamCal kann die genommen Tage dieses Abwesenheitstypen summieren. Das Ergebnis kann im "Abwesenheiten" Reiter des 
      Nutzerprofils eingesehen werden. Der "Faktor" hier bietet einen Multiplikator f&uuml;r diesen Abwesenheitstypen f&uuml;r diese Berechnung. Der Standard ist 1.<br>
      Beispiel: Du kannst einen Abwesenheitstypen "Halbtagstraining" anlegen. Du w&uuml;rdest den Faktor dabei logischerweise auf 0.5 setzen, um die korrekte Summe 
      genommener Trainingstage zu erhalten. Ein Nutzer, der 10 Halbtagstrainings genommen hat, k&auml;me so auf eine Summe von 5 (10 * 0.5 = 5) ganzen Trainingstagen.<br>
      Wenn der Faktor auf 0 gesetzt wird, wird er von der Berechnung ausgeschlossen.';
$LANG['abs_groups'] = 'Gruppenzuordnung';
$LANG['abs_groups_comment'] = 'W&auml;hle die Gruppen aus, f&uuml;r die dieser Abwesenheitstyp g&uuml;ltig sein soll. Wenn eine Gruppe nicht 
      ausgew&auml;hlt ist, k&ouml;nnen Mitglieder dieser Gruppe den Abwesenheitstyp nicht nutzen.';
$LANG['abs_hide_in_profile'] = 'Im Profil verbergen';
$LANG['abs_hide_in_profile_comment'] = 'Dieser Schalter kann benutzt werden, um diesen Typen f&uuml;r normale Nutzer nicht im "Abwesenheiten" Reiter der 
      Nutzerprofile anzuzeigen. Nur Manager, Direktoren und Administratoren k&ouml;nnen ihn dort sehen. Diese Funktion macht Sinn, wenn Manager einen Typen 
      nur zum Zwecke von Nachverfolgung nutzt oder die verbleibende Anzahl f&uuml;r den normalen Nutzer uninteressant ist.';
$LANG['abs_icon'] = 'Icon';
$LANG['abs_icon_comment'] = 'Das Icon wird im Kalender benutzt.';
$LANG['abs_manager_only'] = 'Nur Management';
$LANG['abs_manager_only_comment'] = 'Mit diesem Schalter aktiviert k&ouml;nnen nur Manager und Direktoren diesen Typen setzen. Ein normaler 
      Nutzer kann den Abwesenheitstypen zwar sehen, aber nicht setzen. Diese Funktion macht Sinn, wenn z.B. nur Manager und Direktoren einen 
      Typen wie Urlaub" managen.';
$LANG['abs_name'] = 'Name';
$LANG['abs_name_comment'] = 'Der Name wird in Listen und Beschreibungen benutzt. Er sollte aussagekr&auml;ftig sein, z.B. "Dienstreise". Maximal 80 Zeichen.';
$LANG['abs_sample'] = 'Beispielanzeige';
$LANG['abs_sample_comment'] = 'So w&uuml;rde der Abswesenheitstyp im Kalender angezeigt werden basierend auf den aktuellen Einstellungen.';
$LANG['abs_show_in_remainder'] = 'Verbleibende anzeigen';
$LANG['abs_show_in_remainder_comment'] = 'Im Kalender gibt es eine aufklappbare "Verbleibend" Anzeige f&uuml;r alle Abwesenheitstypen pro Jahr pro Nutzer. 
      Mit diesem Schalter kann bestimmt werden, ob dieser Typ in der Anzeige enthalten sein soll. Wenn kein Abwesenheitstyp f&uuml;r diese Anzeige 
      aktiviert it, ist die Anzeige auch nicht sichtbar, auch wenn die Anzeige grunds&auml;tzlich in der Konfiguration eingeschaltet ist<br>
      Hinweis: Es macht keinen Sinn, einen Typen in der Verbleibend-Anzeige anzuzeigen, wenn der Faktor auf 0 gesetzt ist. Die erlaubte und 
      verbleibende Anzahl wird dann immer gleich sein.';
$LANG['abs_show_totals'] = 'Summen anzeigen';
$LANG['abs_show_totals_comment'] = 'Die Verbleibend-Anzeige kann konfiguriert werden, so dass sie die genommenen Tage pro Monat anzeigt. Dieser Wert zeigt 
      die Summe der genommenen Tage dieses Typen f&uuml;r den angezeigten Monat an. Dieser Schalter aktiviert diesen Typen daf&uuml;r. 
      Wenn kein Abwesenheitstyp dafuer aktiviert ist, wird der Summenteil nicht angezeigt.';
$LANG['abs_symbol'] = 'Symbol';
$LANG['abs_symbol_comment'] = 'Das Symbol wird im Kalender angezeigt, wenn kein Icon gesetzt wurde. Es wird ausserdem in E-Mails benutzt. 
      Das Symbol ist ein alphanumerisches Zeichen lang und muss angegeben werden. Allerdings kann das gleiche Symbol f&uuml;r mehrere 
      Abwesenheitstypen benutzt werden. Als Standard wird der Anfangsbuchstabe des Namens eingesetzt.';
$LANG['abs_tab_groups'] = 'Gruppenzuordnung';

/**
 * Alerts
 */
$LANG['alert_alert_title'] = $CONF['app_name'] . ' Alarm';
$LANG['alert_danger_title'] = $CONF['app_name'] . ' Fehler';
$LANG['alert_info_title'] = $CONF['app_name'] . ' Information';
$LANG['alert_success_title'] = $CONF['app_name'] . ' Erfolg';
$LANG['alert_warning_title'] = $CONF['app_name'] . ' Warnung';

$LANG['alert_captcha_wrong'] = 'Captcha Code falsch';
$LANG['alert_captcha_wrong_text'] = 'Der Sicherheitscode wurde falsch eingegeben. Bitte versuchen Sie es erneut.';
$LANG['alert_captcha_wrong_help'] = 'Der Sicherheitscode muss genau wie angezeigt eingegeben werden. Gro&szlig;- und Kleinschreibung spielt dabei keine Rolle.';

$LANG['alert_controller_not_found_subject'] = 'Controller nicht gefunden';
$LANG['alert_controller_not_found_text'] = 'Der Controller "%1%" konnte nicht gefunden werden.';
$LANG['alert_controller_not_found_help'] = 'Bitte &uuml;berpr&uuml;fe die Installation. Die Datei existiert nicht oder es fehlt die n&ouml;tige Berechtigung f&uuml;r den Zugriff.';

$LANG['alert_input'] = 'Eingabevalidierung fehlgeschlagen';
$LANG['alert_input_alpha'] = 'Diese Feld erlaubt nur eine Eingabe von alphabetischen Zeichen.';
$LANG['alert_input_alpha_numeric'] = 'Diese Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen.';
$LANG['alert_input_alpha_numeric_dash'] = 'Diese Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen plus Bindestrich und Unterstrich.';
$LANG['alert_input_alpha_numeric_dash_blank'] = 'Diese Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen plus Leerzeichen, Bindestrich und Unterstrich.';
$LANG['alert_input_alpha_numeric_dash_blank_dot'] = 'Diese Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen plus Leerzeichen, Punkt, Bindestrich und Unterstrich.';
$LANG['alert_input_alpha_numeric_dash_blank_special'] = 'Diese Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen, Leerzeichen, Bindestrich, Unterstrich und die 
      Sonderzeichen \'!@#$%^&*().';
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

$LANG['alert_maintenance_subject'] = 'Website in Wartung';
$LANG['alert_maintenance_text'] = 'Die Website is zurzeit auf "Unter Wartung" gesetzt. Normale Nutzer k&ouml;nnen keine Funktionen nutzen.';
$LANG['alert_maintenance_help'] = 'Ein Administrator kann die Website wieder aktiv setzten unter Administration -> Konfiguration -> System.';

$LANG['alert_no_data_subject'] = 'Fehlerhafte Daten';
$LANG['alert_no_data_text'] = 'Es wurden falsche oder unzureichende Daten f&uuml;r diese Operation &uuml;bermittelt.';
$LANG['alert_no_data_help'] = 'Die Operation konnte wegen fehlender oder falscher Daten nicht ausfgefuehrt werden.';

$LANG['alert_not_allowed_subject'] = 'Zugriff nicht erlaubt';
$LANG['alert_not_allowed_text'] = 'Du hast nicht die n&ouml;tige Berechtigung auf diese Seite zuzugreifen.';
$LANG['alert_not_allowed_help'] = 'Wenn du nicht eingeloggt bist, dann ist &ouml;ffentlicher Zugriff auf diese Seite nicht erlaubt. Wenn du eingeloggt bist, fehlt deiner Rolle die n&ouml;tige Berechtigung f&uuml;r den Zugriff.';

$LANG['alert_perm_invalid'] = 'Das neue Berechtigungsschema "%1%" ist ung&uuml;ltig. Im Namen sind nur Buchstaben und Zahlen erlaubt.';
$LANG['alert_perm_exists'] = 'Das Berechtigungsschema "%1%" existiert bereits. Bitte w&auml;hle einen anderen Name oder l&ouml;sche das existierende zuerst.';

/**
 * Announcements
 */
$LANG['ann_title'] = 'Nachrichten f&uuml;r ';
$LANG['ann_confirm_all_confirm'] = 'Bist du sicher, dass du alle Popup Nachrichten best&auml;tigen willst?';
$LANG['ann_confirm_confirm'] = 'Bist du sicher, dass du die Nachricht "%s" best&auml;tigen willst? Sie wird nicht gel&ouml;scht aber nicht mehr automatisch angezeigt.';
$LANG['ann_delete_all_confirm'] = 'Bist du sicher, dass du alle Nachrichten l&ouml;schen willst?';
$LANG['ann_delete_confirm'] = 'Bist du sicher, dass du die Nachricht "%s" l&ouml;schen willst?';
$LANG['ann_id'] = 'Nachrichten-ID';
$LANG['ann_bday_title'] = 'Geburtstage am ';

/**
 * Buttons
 */
$LANG['btn_abs_edit'] = 'Bearbeiten';
$LANG['btn_abs_icon'] = 'Icon Ausw&auml;hlen';
$LANG['btn_abs_list'] = 'Abwesenheitstypenliste';
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
$LANG['btn_edit'] = 'Editieren';
$LANG['btn_edit_profile'] = 'Profil bearbeiten';
$LANG['btn_enable'] = 'Aktivieren';
$LANG['btn_export'] = 'Export';
$LANG['btn_group_list'] = 'Gruppenliste';
$LANG['btn_help'] = 'Hilfe';
$LANG['btn_holiday_list'] = 'Feiertagsliste';
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
$LANG['btn_role_list'] = 'Regionsliste';
$LANG['btn_register'] = 'Registrieren';
$LANG['btn_remove'] = 'Entfernen';
$LANG['btn_reset'] = 'Zur&uuml;cksetzen';
$LANG['btn_reset_password'] = 'Passwort zur&uuml;cksetzen';
$LANG['btn_reset_password_selected'] = 'Auswahl Passwort zur&uuml;cksetzen';
$LANG['btn_restore'] = 'Wiederherstellen';
$LANG['btn_restore_selected'] = 'Auswahl wiederherstellen';
$LANG['btn_role_list'] = 'Rollenliste';
$LANG['btn_save'] = 'Speichern';
$LANG['btn_search'] = 'Suchen';
$LANG['btn_select'] = 'Ausw&auml;hlen';
$LANG['btn_send'] = 'Senden';
$LANG['btn_showcalendar'] = 'Kalender anzeigen';
$LANG['btn_submit'] = 'Abschicken';
$LANG['btn_switch'] = 'Anwenden';
$LANG['btn_transfer'] = '&Uuml;bertragen';
$LANG['btn_update'] = 'Aktualisieren';
$LANG['btn_user_list'] = 'Benutzerliste';
$LANG['btn_upload'] = 'Hochladen';
$LANG['btn_view'] = 'Anzeigen';

/**
 * Calendar
 */
$LANG['cal_title'] = 'Kalender %s-%s (Region: %s)';
$LANG['cal_tt_backward'] = 'Einen Monat zur&uuml;ck...';
$LANG['cal_tt_forward'] = 'Einen Monat vorw&auml;rts...';
$LANG['cal_search'] = 'Nutzer suchen';
$LANG['cal_selAbsence'] = 'Abwesenheit ausw&auml;hlen';
$LANG['cal_selAbsence_comment'] = 'Zeigt alle Eintr&auml;ge an, die am heutigen Tage diese Abwesenheit eingetragen haben.';
$LANG['cal_selGroup'] = 'Gruppe ausw&auml;hlen';
$LANG['cal_selRegion'] = 'Region ausw&auml;hlen';
$LANG['cal_summary'] = 'Summen';

$LANG['cal_caption_weeknumber'] = 'Kalenderwoche';
$LANG['cal_caption_name'] = 'Name';
$LANG['cal_img_alt_edit_month'] = 'Feiertage f&uuml;r diesen Monat editieren...';
$LANG['cal_img_alt_edit_cal'] = 'Kalendar f&uuml;r diese Person editieren...';
$LANG['cal_birthday'] = 'Geburtstag';
$LANG['cal_age'] = 'Alter';
$LANG['sum_present'] = 'Anwesend';
$LANG['sum_absent'] = 'Abwesend';
$LANG['sum_delta'] = 'Delta';
$LANG['sum_absence_summary'] = 'Abwesenheiten im einzelnen';
$LANG['sum_business_day_count'] = 'Arbeitstage';
$LANG['remainder'] = 'Resttage';
$LANG['exp_summary'] = 'Zusammenfassung einblenden...';
$LANG['col_summary'] = 'Zusammenfassung ausblenden...';
$LANG['exp_remainder'] = 'Resttage einblenden...';
$LANG['col_remainder'] = 'Resttage ausblenden...';

/**
 * Calendar Edit
 */
$LANG['caledit_title'] = 'Bearbeitung von Monat %s-%s f&uuml;r %s';
$LANG['caledit_absenceType'] = 'Abwesenheitstyp';
$LANG['caledit_absenceType_comment'] = 'W&auml;hle den Abwesenheitstyp f&uuml;r diese Eingabe aus.';
$LANG['caledit_alert_out_of_range'] = 'Die Datumsangaben war zumindest teilweise ausserhalb des angezeigten Monats. Es wurden keine &Auml;nderungen gespeichert.';
$LANG['caledit_alert_save_failed'] = 'Die Abwesenheitsinformationen konnten nicht gespeichert werden. Es gab fehlerhafte Eingaben. Bitte pr&uuml;fe die letzte Eingabe.';
$LANG['caledit_alert_update'] = 'Monat aktualisieren';
$LANG['caledit_alert_update_all'] = 'Alle &Auml;nderungen wurden akzeptiert und der Monat entsprechend aktualisert.';
$LANG['caledit_alert_update_partial'] = 'Nur ein Teil der &Auml;nderungen wurden akzeptiert weil einige vom Management konfigurierte Ablehnungsregeln verletzen. 
      Die folgenden &Auml;nderungen wurden abgelehnt:';
$LANG['caledit_alert_update_none'] = 'Keine der &Auml;nderungen wurden akzeptiert und der Monat nicht aktualisert. 
      Die abgelehnten &Auml;nderungen wurden an einen Manager zur Best&auml;tigung geschickt.';
$LANG['caledit_clearAbsence'] = 'L&ouml;schen';
$LANG['caledit_confirm_clearall'] = 'Bist du sicher, dass du alle Abwesenheiten f&uuml;r diesen Monat l&ouml;schen willst?<br><br><strong>Jahr:</strong> %s<br><strong>Monat:</strong> %s<br><strong>Nutzer:</strong> %s';
$LANG['caledit_currentAbsence'] = 'Aktuell';
$LANG['caledit_endDate'] = 'Ende Datum';
$LANG['caledit_endDate_comment'] = 'W&auml;hle das Enddatum aus (muss in diesem Monat sein).';
$LANG['caledit_Period'] = 'Zeitraum';
$LANG['caledit_PeriodTitle'] = 'Abwesenheitszeitraum ausw&auml;hlen';
$LANG['caledit_Recurring'] = 'Wiederholung';
$LANG['caledit_RecurringTitle'] = 'Abwesenheitswiederholung ausw&auml;hlen';
$LANG['caledit_recurrence'] = 'Wiederholung';
$LANG['caledit_recurrence_comment'] = 'W&auml;hle die Wiederholung aus';
$LANG['caledit_selRegion'] = 'Region ausw&auml;hlen';
$LANG['caledit_selUser'] = 'Benutzer ausw&auml;hlen';
$LANG['caledit_startDate'] = 'Start Datum';
$LANG['caledit_startDate_comment'] = 'W&auml;hle das Startdatum aus (muss in diesem Monat sein).';

/**
 * Calendar Options
 */
$LANG['calopt_title'] = 'Kalenderoptionen';

$LANG['calopt_tab_display'] = 'Anzeige';
$LANG['calopt_tab_filter'] = 'Filter';
$LANG['calopt_tab_options'] = 'Einstellungen';
$LANG['calopt_tab_remainder'] = 'Resttage';
$LANG['calopt_tab_summary'] = 'Summen';

$LANG['calopt_defgroupfilter'] = 'Default Gruppenfilter';
$LANG['calopt_defgroupfilter_comment'] = 'Auswahl des Default Gruppenfilters f&uuml;r die Kalenderanzeige. Jeder User kann diese Einstellung individuell in seinem Profil &auml;ndern.';
$LANG['calopt_defgroupfilter_all'] = 'Alle';
$LANG['calopt_defgroupfilter_allbygroup'] = 'Alle (nach Gruppen)';
$LANG['calopt_defregion'] = 'Default Region f&uuml;r Basiskalendar';
$LANG['calopt_defregion_comment'] = 'Auswahl der Default Region f&uuml;r den Basiskalender. Jeder User kann diese Einstellung individuell in seinem Profil &auml;ndern.';
$LANG['calopt_firstDayOfWeek'] = 'Erster Wochentag';
$LANG['calopt_firstDayOfWeek_comment'] = 'Dieser kann auf Montag oder Sonntag gesetzt werden. Die Auswahl wirkt sich auf die Anzeige der Wochennummern aus.';
$LANG['calopt_firstDayOfWeek_1'] = 'Montag';
$LANG['calopt_firstDayOfWeek_7'] = 'Sonntag';
$LANG['calopt_hideDaynotes'] = 'Pers&ouml;nliche Tagesnotizen verbergen';
$LANG['calopt_hideDaynotes_comment'] = 'Mt diesem Schalter k&ouml;nnen die pers&ouml;nlichen Tagesnotizen vor normalen Nutzern verborgen werden. Nur Manager, Direktoren
      und Administratoren k&ouml;nnen sie editieren und sehen. So k&ouml;nnen sie f&uuml;r Managementzwecke genutzt werden. Dieser Schalter beeinflusst nicht die Geburtstagsnotizen.';
$LANG['calopt_hideManagers'] = 'Manager in Alle-nach-Gruppen und Gruppen Anzeige verbergen';
$LANG['calopt_hideManagers_comment'] = 'Mit dieser Option werden alle Manager in der Alle-nach-Gruppen und Gruppen Anzeige verborgen mit Ausnahme der Gruppen, in der sie nur Mitglied sind.';
$LANG['calopt_hideManagerOnlyAbsences'] = 'Management Abwesenheiten verbergen';
$LANG['calopt_hideManagerOnlyAbsences_comment'] = 'Abwesenheitstypen k&ouml;nnen als "Nur Management" markiert werden, so dass nur Manager und Direktoren sie editieren k&ouml;nnen.
      Diese Abwesenheiten werden normalen Benutzern angezeigt, sie k&ouml;nnen sie aber nicht editieren. Mit diesem Schalter k&ouml;nnen sie die Anzeige f&uuml;r normale Benutzer verbergen.';
$LANG['calopt_includeRemainder'] = 'Resttage Spalte';
$LANG['calopt_includeRemainder_comment'] = 'Mit dieser Option wird im Kalender eine aufklappbare Spalte mit den Resttagen hinzugef&uuml;gt, die f&uuml;r jeden Nutzer die
      verbleibenden Tage pro Abwesenheitstyp anzeigt. Hinweis: Die Abwesenheitstypen, die in der Resttage Anzeige enthalten sein sollen, m&uuml;ssen entsprechend konfiguriert werden.';
$LANG['calopt_includeRemainderTotal'] = 'Resttage Summe';
$LANG['calopt_includeRemainderTotal_comment'] = 'Mit dieser Option werden der Resttage Anzeige die erlaubten Tage pro Abwesenheitstyp hinzugef&uuml;gt
      (getrennt durch einen Schr&auml;gstrich).';
$LANG['calopt_includeSummary'] = 'Summen Abschnitt';
$LANG['calopt_includeSummary_comment'] = 'Mit dieser Option wird eine aufklappbare Zusammenfassung unter jedem Monat angezeigt, die die Summen der Abwesenheiten auff&uuml;hrt.';
$LANG['calopt_includeTotals'] = 'Monatssummen anzeigen';
$LANG['calopt_includeTotals_comment'] = 'Mit dieser Option wird in der Resttage Spalte ein weiterer Bereich mit den Monatssummen pro Abwesenheitstyp angezeigt.<br>
      Hinweis: Die Abwesenheitstypen, die in der Summenanzeige enthalten sein sollen, m&uuml;ssen entsprechend konfiguriert werden.';
$LANG['calopt_markConfidential'] = 'Vertrauliche Abwesenheiten Markieren';
$LANG['calopt_markConfidential_comment'] = 'Normale Nutzer k&ouml;nnen vertrauliche Abwesenheiten anderer Nutzer nicht sehen. Mit dieser
      Option hier k&ouml;nnen diese jedoch mit einem "X" im Kalender gekennzeichnet werden, so dass deren Abwesenheit erkennbar ist.';
$LANG['calopt_pastDayColor'] = 'Vergangenheitsfarbe';
$LANG['calopt_pastDayColor_comment'] = 'Setzt die Hintergrundfarbe f&uuml;r die Tage des aktuellen Monats, die in der Vergangenheit liegen.
      Bei keinem Wert in diesem Feld wird keine Farbe eingesetzt.';
$LANG['calopt_repeatHeaderCount'] = 'Kopfzeilen Wiederholungs Z&auml;hler';
$LANG['calopt_repeatHeaderCount_comment'] = 'Gibt die Anzahl von Zeilen an, nach der die Monatskopfzeile f&uuml;r bessere Lesbarkeit wiederholt wird.';
$LANG['calopt_satBusi'] = 'Samstag ist ein Arbeitstag';
$LANG['calopt_satBusi_comment'] = 'Normalerweise sind Samstage und Sonntage Wochenendtage und werden entsprechend im Kalender als solche angezeigt.
      Hier kann Samstag als Arbeitstag definiert werden.';
$LANG['calopt_showMonths'] = 'Anzahl Monate';
$LANG['calopt_showMonths_comment'] = 'Mit dieser Option wird die Anzahl der Monate angegeben, die standardm&auml;&szlig;ig in der Kalenderansicht dargestellt werden.';
$LANG['calopt_showMonths_1'] = '1 Monat';
$LANG['calopt_showMonths_2'] = '2 Monate';
$LANG['calopt_showMonths_3'] = '3 Monate';
$LANG['calopt_showMonths_6'] = '6 Monate';
$LANG['calopt_showMonths_12'] = '12 Monate';
$LANG['calopt_showRemainder'] = 'Resttage anzeigen';
$LANG['calopt_showRemainder_comment'] = 'Mit dieser Option wird die Resttage Spalte standardm&auml;&szlig;ig aufgeklappt.';
$LANG['calopt_showSummary'] = 'Summen Abschnitt anzeigen';
$LANG['calopt_showSummary_comment'] = 'Mit dieser Option wird der Summen Abschnitt standardm&auml;&szlig;ig aufgeklappt.';
$LANG['calopt_showUserRegion'] = 'Regionale Feiertage pro User anzeigen';
$LANG['calopt_showUserRegion_comment'] = 'Mit dieser Option zeigt der Kalender in jeder Nutzerzeile die regionalen Feiertage der Region an, die in den Optionen des
      Nutzers eingestellt ist. Diese Feiertage k&ouml;nnen sich von den globalen regionalen Feiertagen unterscheiden, die im Kopf des Kalenders angezeigt werden.
      Diese Option bietet eine bessere Sicht auf die unterschiedlichen regionalen Feiertage unterschiedlicher Nutzer. Die Anzeige mag dabei aber auch un&uuml;bersichtlicher
      werden, je nach Anzahl Nutzer und Regionen. Probier es aus.';
$LANG['calopt_showWeekNumbers'] = 'Wochennummern anzeigen';
$LANG['calopt_showWeekNumbers_comment'] = 'Mit dieser Option wird im Kalender eine Zeile mit den Nummern der Kalenderwochen hinzugef&uuml;gt.';
$LANG['calopt_sunBusi'] = 'Sonntag ist ein Arbeitstag';
$LANG['calopt_sunBusi_comment'] = 'Normalerweise sind Samstage und Sonntage Wochenendtage und werden entsprechend im Kalender als solche angezeigt.
      Hier kann Sonntag als Arbeitstag definiert werden.';
$LANG['calopt_supportMobile'] = 'Unterst&uuml;tzung von Mobilen Ger&auml;ten';
$LANG['calopt_supportMobile_comment'] = 'Mit dieser Einstellung bereitet die Kalender Seite gleich mehrere Versionen der Monatstabelle f&uuml;r die gebr&auml;chlichsten
      Bildschirmgr&ouml;&szlig;en vor. Je nach Display zeigt der Browser dann automatisch die richtige an. Nachteil ist, dass die Seite dadurch mehr Zeit zum Laden 
      braucht. Schalte diese Option aus, wenn der Kalender nur auf gro&szlig;en Bildschirmen genutzt wird. Der Kalender wird dann immer noch auf kleineren Bildschirmen
      angezeigt, aber horizontales Scrollen wird dann n&ouml;tig.';
$LANG['calopt_todayBorderColor'] = 'Heute Randfarbe';
$LANG['calopt_todayBorderColor_comment'] = 'Gibt die Farbe in Hexadezimal an, in der der rechte und linke Rand der Heute Spalte erscheint.';
$LANG['calopt_todayBorderSize'] = 'Heute Randst&auml;rke';
$LANG['calopt_todayBorderSize_comment'] = 'Gibt die Dicke in Pixel an, in der der rechte und linke Rand der Heute Spalte erscheint.';
$LANG['calopt_usersPerPage'] = 'Anzahl User pro Seite';
$LANG['calopt_usersPerPage_comment'] = 'Wenn du eine gro&szlig;e Anzahl an Usern in TeamCal Neo pflegst, bietet es sich an, die Kalenderanzeige in Seiten aufzuteilen.
      Gebe hier an, wieviel User pro Seite angezeigt werden sollen. Ein Wert von 0 zeigt alle User auf einer Seite an. Wenn du eine Seitenaufteilung w&auml;hlst,
      werden am Ende der Seite Schaltfl&auml;chen fuer das Bl&auml;ttern angezeigt.';
$LANG['calopt_userSearch'] = 'Nutzer Suchfeld Anzeigen';
$LANG['calopt_userSearch_comment'] = 'Aktivierung/Deaktivierung eines Suchfelds in der Kalenderanzeige, mit dem einzelne Nutzer gesucht werden k&ouml;nnen.';

/**
 * Config
 */
$LANG['config_title'] = $appTitle. ' Konfiguration';

$LANG['config_email'] = 'E-mail';
$LANG['config_login'] = 'Login';
$LANG['config_registration'] = 'Registrierung';
$LANG['config_stats'] = 'Statistik';
$LANG['config_system'] = 'System';
$LANG['config_tab_theme'] = 'Theme';
$LANG['config_user'] = 'Nutzer';

$LANG['config_activateMessages'] = 'Message Center aktivieren';
$LANG['config_activateMessages_comment'] = 'Mit diesem Schalter kann das Message Center aktiviert werden. Nutzer k&ouml;nnen damit anderen Nutzern und Gruppen 
      Nachrichten oder E-Mails schicken. Ein Eintrag im Optionen Menu wird hinzugef&uuml;gt.';
$LANG['config_adminApproval'] = 'Administrator Freischaltung erforderlich';
$LANG['config_adminApproval_comment'] = 'Der Administrator erh&auml;lt eine E-Mail bei einer Neuregistrierung. Er muss den Account manuell freischalten.';
$LANG['config_allowRegistration'] = 'User Selbst-Registration erlauben';
$LANG['config_allowRegistration_comment'] = 'Erlaubt die Registrierung durch den User. Ein zus&auml;tzlicher Menueintrag erscheint im Menu.';
$LANG['config_allowUserTheme'] = 'User Theme';
$LANG['config_allowUserTheme_comment'] = 'W&auml;hle aus, ob jeder User sein eigenes Theme w&auml;hlen kann.';
$LANG['config_appTitle'] = 'Applikations-Titel';
$LANG['config_appTitle_comment'] = 'Hier kann der Applikations-Title eingetragen werden. Er wird an mehreren Stellen benutzt, z.B. im Menu und auf anderen Seiten.';
$LANG['config_appFooterCpy'] = 'Applikation Fu&szlig;zeilen Copyright';
$LANG['config_appFooterCpy_comment'] = 'Wird in der Fu&szlig;zeile oben links angezeigt.';
$LANG['config_avatarHeight'] = 'Avatar Maximale H&ouml;he';
$LANG['config_avatarHeight_comment'] = 'Gibt die H&ouml;he in Pixel von Avatar Bildern an. Avatar Bilder mit gr&ouml;&szlig;erer H&ouml;he werden auf diese 
      H&ouml;he reduziert und die Breite wird proportional angepasst.';
$LANG['config_avatarMaxSize'] = 'Avatar Maximale Dateigr&ouml;&szlig;e';
$LANG['config_avatarMaxSize_comment'] = 'Bestimmt die maximale Dateigr&ouml;&szlig;e f&uuml;r Avatar Dateien in Bytes.';
$LANG['config_avatarWidth'] = 'Avatar Maximale Breite';
$LANG['config_avatarWidth_comment'] = 'Gibt die Breite in Pixel von Avatar Bildern an. Avatar Bilder mit gr&ouml;&szlig;erer Breite werden auf diese Breite 
      reduziert und die H&ouml;he wird proportional angepasst.';
$LANG['config_badLogins'] = 'Ung&uuml;ltige Logins';
$LANG['config_badLogins_comment'] = 'Anzahl der ung&uuml;ltigen Login Versuche bevore der User Status auf \'LOCKED\' gesetzt wird. Der User muss danach solange 
      warten wie in der Schonfrist angegeben, bevor er sich erneut einloggen kann. Wenn dieser Wert auf 0 gesetzt wird, ist diese Funktion deaktiviert.';
$LANG['config_cookieLifetime'] = 'Cookie Lebensdauer';
$LANG['config_cookieLifetime_comment'] = 'Bei erfolgreichem Einloggen wird ein Cookie auf dem lokalen Rechner des Users abgelegt. Dieser Cookie hat eine 
      bestimmte Lebensdauer, nach dem er nicht mehr anerkannt wird. Ein erneutes Login is notwendig. Die Lebensdauer kann hier in Sekunden angegeben werden (0-999999).';
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
$LANG['config_googleAnalytics'] = 'Google Analytics';
$LANG['config_googleAnalytics_comment'] = $appTitle . ' unterst&uuml;tzt Google Analytics. Wenn du deine Instanz im Internet betreibst und den Zugriff 
      von Google Analytics tracken lassen willst, ticke die Checkbox hier und trage deine Google Analytics ID ein. Der entsprechende Javascript Code wird dann eingef&uuml;gt.';
$LANG['config_googleAnalyticsID'] = "Google Analytics ID";
$LANG['config_googleAnalyticsID_comment'] = "Wenn du die Google Analytics Funktion aktiviert hast, trage hier deine Google Analytics ID im Format UA-999999-99 ein.";
$LANG['config_gracePeriod'] = 'Schonfrist';
$LANG['config_gracePeriod_comment'] = 'Zeit in Sekunden, die ein User warten muss, bevor er sich nach zu vielen fehlgeschlagenen Versuchen wieder einloggen kann.';
$LANG['config_homepage'] = 'Homepage';
$LANG['config_homepage_comment'] = 'Diese Option bestimmt, welche Seite als Homepage angezeigt werden soll.';
$LANG['config_homepage_home'] = 'Willkommen Seite';
$LANG['config_homepage_news'] = 'News Seite';
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
$LANG['config_permissionScheme'] = 'Berechtigungsschema';
$LANG['config_permissionScheme_comment'] = 'Hiermit wird das aktive Berechtigungsschema ausgew&auml;hlt. Das Schema kann auf der Berechtigungsschema Seite bearbeitet werden.';
$LANG['config_pwdStrength'] = 'Passwort Sicherheit';
$LANG['config_pwdStrength_comment'] = '<br>Die Passwort Sicherheit bestimmt, welchen Anforderungen das User Passwort gen&uuml;gen muss Erlaubt sind immer Gro&szlig;- und Kleinbuchstaben, Zahlen und die Sonderzeichen: !@#$%^&amp;*() 
      <ul>
         <li><strong>Niedrig:</strong> Mindestens 4 Zeichen</li>
         <li><strong>Mittel:</strong> Mindestens 6 Zeichen, mindestens ein Gro&szlig;buchstabe, ein Kleinbuchstabe und eine Zahl</li>
         <li><strong>Hoch:</strong> Mindestens 8 Zeichen, mindestens ein Gro&szlig;buchstabe, ein Kleinbuchstabe, eine Zahl und ein Sonderzeichen</li>
      </ul>';
$LANG['config_pwdStrength_low'] = 'Niedrig';
$LANG['config_pwdStrength_medium'] = 'Mittel';
$LANG['config_pwdStrength_high'] = 'Hoch';
$LANG['config_showAlerts'] = 'Erfolgs- und Fehlermeldungen';
$LANG['config_showAlerts_comment'] = 'Mit dieser Option kann ausgew&auml;hlt werden, welche Ergebnisnachrichten angezeigt werden.';
$LANG['config_showAlerts_all'] = 'Alle (inkl. Erfolgsnachrichten)';
$LANG['config_showAlerts_warnings'] = 'Nur Warnungen und Fehler';
$LANG['config_showAlerts_none'] = 'Keine';
$LANG['config_showAvatars'] = 'Avatars anzeigen';
$LANG['config_showAvatars_comment'] = 'Mit dieser Option wird ein User Avatar in einem Pop-Up Fenster angezeigt, wenn die Maus &uuml;ber das User Icon gef&uuml;hrt wird. 
      Hinweis: Diese Funktion arbeitet nur, wenn User Icons eingschaltet sind.';
$LANG['config_showUserIcons'] = 'User Icons anzeigen';
$LANG['config_showUserIcons_comment'] = 'Mir dieser Option werden links vom Benutzernamen User Icons angezeigt, die die User Rolle und das Geschlecht anzeigen.';
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
$LANG['config_userManual_comment'] = $appTitle . '\'s Nutzerhandbuch ist in Englisch verf&uuml;gbar auf der Community site. Eventuell sind &Uuml;bersetzungen von 
      anderen Nutzern verf&uuml;gbar. Wenn das so ist, kann der Link dazu hier eingegeben werden. Wenn du selbst an der Mitarbeit oder and einer neuen 
      &Uuml;bersetzung interessiert bist, registriere dich einfach bei der <a href="https://georgelewe.atlassian.net" target="_blank"> Community Site 
      (https://georgelewe.atlassian.net)</a> und &ouml;ffne eine Task im Issue Tracker dazu. Wenn hier kein Eintrag gemacht wird, setzt '.$appTitle.' den Standard Link ein.';
$LANG['config_welcomeIcon'] = 'Willkommen Text Icon';
$LANG['config_welcomeIcon_comment'] = 'Mit dem Willkommenstext kann ein Icon angezeigt werden. Es wird links platziert und der Text flie&szlig;t um es herum. 
      Die Gr&ouml;&szlig;e kann in der Drop Down Liste ausgew&auml;hlt werden.';
$LANG['config_welcomeText'] = 'Willkommen Seite Text';
$LANG['config_welcomeText_comment'] = 'Hier kann ein ein Text f&uuml;r die Willkommen Seite eingegeben werden. Das Feld erlaubt die Verwendung von den HTML Tags &lt;em&gt;
      und &lt;strong&gt;. Zeilenumbr&uuml;che werden automatisch in &lt;br&gt; Tags &uuml;bersetzt. Alle anderen HTML Tags werden entfernt.';
$LANG['config_welcomeTitle'] = 'Willkommen Seite Titel';
$LANG['config_welcomeTitle_comment'] = 'Hier kann ein ein Titel f&uuml;r die Willkommen Seite eingegeben werden. Das Feld erlaubt die Verwendung von den HTML Tags &lt;em&gt;
      und &lt;strong&gt;. Zeilenumbr&uuml;che werden automatisch in &lt;br&gt; Tags &uuml;bersetzt. Alle anderen HTML Tags werden entfernt.';

/**
 * Database
 */
$LANG['db_title'] = 'Datenbank Management';
$LANG['db_tab_cleanup'] = 'Aufr&auml;umen';
$LANG['db_tab_delete'] = 'Datens&auml;tze l&ouml;schen';
$LANG['db_tab_export'] = 'Export/Import';
$LANG['db_tab_optimize'] = 'Tabellen optimieren';

$LANG['db_alert_delete'] = 'Datens&auml;tze L&ouml;schen';
$LANG['db_alert_delete_success'] = 'Die L&ouml;schungen wurden durchgef&uuml;hrt.';
$LANG['db_alert_failed'] = 'Die Operation konnte nicht durchgef&uuml;hrt werden. Bitte &uuml;berpr&uuml;fe deine Eingaben.';
$LANG['db_alert_optimize'] = 'Tabellen optimieren';
$LANG['db_alert_optimize_success'] = 'Alle Datenbanktabellen wurden optimiert.';
$LANG['db_confirm'] = 'Best&auml;tigung';
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
$LANG['db_export'] = 'Datenbankexport und -import';
$LANG['db_export_comment'] = 'Aufgrund von Sicherheitsbedenken und von Unzul&auml;nglichkeiten von PHP-basierten Export- und Importroutinen benutze bitte eine Datenbank
      Applikation wie phpMyAdmin um die Datenbank in Teilen oder ganz zu exportieren und wiederherzustellen.';
$LANG['db_optimize'] = 'Datenbanktabellen optimieren';
$LANG['db_optimize_comment'] = 'Reorganisiert die Tabellendaten und deren Indexinformationen in der Datenbank, um Speicherplatz zu reduzieren und die I/O Effizienz zu verbessern.';

/**
 * Declination
 */
$LANG['decl_title'] = 'Ablehnungsmanagement';
$LANG['decl_absence'] = 'Aktivieren';
$LANG['decl_absence_comment'] = 'Aktiviere diesen Schalter, wenn bei Erreichen einer Abwesenheitsgrenze abgelehnt werden soll.';
$LANG['decl_alert_period_wrong'] = 'Bei Angabe einer Periode muss das Startdatum vor dem Endedatum liegen.';
$LANG['decl_alert_period_missing'] = 'Bei Angabe einer Periode m&uuml;ssen beide Datumsfelder ausgef&uuml;llt werden.';
$LANG['decl_alert_save'] = 'Ablehnungseinstellungen speichern';
$LANG['decl_alert_save_success'] = 'Die neuen Ablehnungseinstellungen wurden gespeichert.';
$LANG['decl_alert_save_failed'] = 'Die Einstellungen konnten nicht gespeichert werden. Es gab fehlerhafte Eingaben. Bitte pr&uuml;fe die Fehlermeldungen.';
$LANG['decl_applyto'] = 'Ablehnung anwenden bei';
$LANG['decl_applyto_comment'] = 'Hier kann eingestellt werden, ob Ablehnung nur bei normalen Nutzern gepr&uuml;ft wird oder auch bei Managern und Direktoren. Bei Administratoren wird Ablehnung nicht gepr&uuml;t.';
$LANG['decl_applyto_regular'] = 'Nur bei normalen Nutzern';
$LANG['decl_applyto_all'] = 'Bei allen Nutzern (au&szlig;er Administratoren)';
$LANG['decl_base'] = 'Basis der Abwesenheitsrate';
$LANG['decl_base_comment'] = 'W&auml;hle hier, worauf sich die Abwesenheitsrate beziehen soll.';
$LANG['decl_base_all'] = 'Alle';
$LANG['decl_base_group'] = 'Gruppe';
$LANG['decl_before'] = 'Aktivieren';
$LANG['decl_before_comment'] = 'Abwesenheitsanfragen k&ouml;nnen abgelehnt werden, wenn sie vor einem bestimmten Datum liegen. Hier kann diese Option aktiviert werden.';
$LANG['decl_beforedate'] = 'Grenzdatum';
$LANG['decl_beforedate_comment'] = 'Hier kann ein individuelles Grenzdatum eingegeben werden. Dies ist nur wirksam, wenn oben die Option "vor Datum" gew&auml;hlt wurde.';
$LANG['decl_beforeoption'] = 'Grenzdatumoption';
$LANG['decl_beforeoption_comment'] = 'Bei Auswahl von "vor Heute werden Abwesenheitsanfragen in der Vergangenheit abgelehnt. Wenn ein bestimmtes Datum die Grenze
      sein soll, w&auml;hle hier "vor Datum" und gebe das Datum unten ein.';
$LANG['decl_beforeoption_today'] = 'vor Heute (nicht eingeschlossen)';
$LANG['decl_beforeoption_date'] = 'vor Datum (nicht eingeschlossen)';
$LANG['decl_period1'] = 'Aktivieren';
$LANG['decl_period1_comment'] = 'Hier kann eine Periode definiert werden, innerhalb derer Abwesenheitsanfragen abgelehnt werden. Start und Ende Datum sind in diesem Fall mit eingeschlossen.
      Die Option kann hier aktiviert werden.';
$LANG['decl_period1start'] = 'Startdatum (einschlie&szlig;lig)';
$LANG['decl_period1start_comment'] = 'Gebe hier das Startdatum der Periode ein.';
$LANG['decl_period1end'] = 'Enddatum (einschlie&szlig;lig)';
$LANG['decl_period1end_comment'] = 'Gebe hier das Enddatum der Periode ein.';
$LANG['decl_period2'] = 'Aktivieren';
$LANG['decl_period2_comment'] = 'Hier kann eine weitere Periode definiert werden, innerhalb derer Abwesenheitsanfragen abgelehnt werden. Start und Ende Datum sind in diesem Fall mit eingeschlossen.
      Die Option kann hier aktiviert werden.';
$LANG['decl_period2start'] = 'Startdatum (einschlie&szlig;lig)';
$LANG['decl_period2start_comment'] = 'Gebe hier das Startdatum der Periode ein.';
$LANG['decl_period2end'] = 'Enddatum (einschlie&szlig;lig)';
$LANG['decl_period2end_comment'] = 'Gebe hier das Enddatum der Periode ein.';
$LANG['decl_period3'] = 'Aktivieren';
$LANG['decl_period3_comment'] = 'Hier kann eine weitere Periode definiert werden, innerhalb derer Abwesenheitsanfragen abgelehnt werden. Start und Ende Datum sind in diesem Fall mit eingeschlossen.
      Die Option kann hier aktiviert werden.';
$LANG['decl_period3start'] = 'Startdatum (einschlie&szlig;lig)';
$LANG['decl_period3start_comment'] = 'Gebe hier das Startdatum der Periode ein.';
$LANG['decl_period3end'] = 'Enddatum (einschlie&szlig;lig)';
$LANG['decl_period3end_comment'] = 'Gebe hier das Enddatum der Periode ein.';
$LANG['decl_roles'] = 'Anwenden bei Rollen';
$LANG['decl_roles_comment'] = 'W&auml;hle hier die Rollen aus, bei denen die Ablehnungsregeln angewendet werden sollen.';
$LANG['decl_tab_absence'] = 'Abwesenheitsgrenze';
$LANG['decl_tab_before'] = 'Datumsgrenze';
$LANG['decl_tab_period1'] = 'Zeitraum 1';
$LANG['decl_tab_period2'] = 'Zeitraum 2';
$LANG['decl_tab_period3'] = 'Zeitraum 3';
$LANG['decl_tab_scope'] = 'Geltungsbereich';
$LANG['decl_threshold'] = 'Abwesenheitsrate (%)';
$LANG['decl_threshold_comment'] = 'Hier kann eine Abwesenheitsrate in Prozent angegeben werden, die nicht unterschritten werden darf.';

/**
 * E-Mail
 */
$LANG['email_subject_group_changed'] = $CONF['app_name'] . ' Gruppe ge&auml;ndert';
$LANG['email_subject_group_created'] = $CONF['app_name'] . ' Gruppe angelegt';
$LANG['email_subject_group_deleted'] = $CONF['app_name'] . ' Gruppe gel&ouml;scht';
$LANG['email_subject_month_created'] = $CONF['app_name'] . ' Monat angelegt';
$LANG['email_subject_month_changed'] = $CONF['app_name'] . ' Monat geändert';
$LANG['email_subject_month_deleted'] = $CONF['app_name'] . ' Monat gelöscht';
$LANG['email_subject_role_changed'] = $CONF['app_name'] . ' Rolle ge&auml;ndert';
$LANG['email_subject_role_created'] = $CONF['app_name'] . ' Rolle angelegt';
$LANG['email_subject_role_deleted'] = $CONF['app_name'] . ' Rolle gel&ouml;scht';
$LANG['email_subject_user_account_changed'] = $CONF['app_name'] . ' Benutzerkonto ge&auml;ndert';
$LANG['email_subject_user_account_created'] = $CONF['app_name'] . ' Benutzerkonto angelegt';
$LANG['email_subject_user_account_deleted'] = $CONF['app_name'] . ' Benutzerkonto gel&ouml;scht';
$LANG['email_subject_user_account_registered'] = $CONF['app_name'] . ' Benutzerkonto registriert';

/**
 * Error messages
 */
$LANG['err_decl_before_date'] = ": Abwesenheits&auml;nderungen vor dem folgendem Datum sind nicht erlaubt: ";
$LANG['err_decl_group_threshold'] = ": Die Abwesenheitsgrenze wurde erreicht für die Gruppe(n): ";
$LANG['err_decl_period'] = ": Abwesenheits&auml;nderungen in folgendem Zeitraum sind nicht erlaubt: ";
$LANG['err_decl_total_threshold'] = ": Die generelle Abwesenheitsgrenze wurde erreicht.";

/**
 * Group
 */
$LANG['group_edit_title'] = 'Gruppe editieren: ';
$LANG['group_alert_edit'] = 'Gruppe aktualisieren';
$LANG['group_alert_edit_success'] = 'Die Informationen f&uuml;r diese Gruppe wurden aktualisiert.';
$LANG['group_alert_save_failed'] = 'Die neuen Informationen f&uuml;r diese Gruppe konnten nicht gespeichert werden. Es gab fehlerhafte Eingaben. Bitte pr&uuml;fe die Fehlermeldungen.';
$LANG['group_name'] = 'Name';
$LANG['group_name_comment'] = '';
$LANG['group_description'] = 'Beschreibung';
$LANG['group_description_comment'] = '';

/**
 * Groups
 */
$LANG['groups_title'] = 'Gruppen';
$LANG['groups_alert_group_created'] = 'Die Gruppe wurde angelegt.';
$LANG['groups_alert_group_created_fail'] = 'Die Gruppe wurde nicht angelegt. Bitte &uuml;berpr&uuml;fe den "Gruppe anlegen" Dialog nach Eingabefehlern.';
$LANG['groups_alert_group_deleted'] = 'Die Gruppe wurde gel&ouml;scht.';
$LANG['groups_confirm_delete'] = 'Bist du sicher, dass du diese Gruppe l&ouml;schen willst?';
$LANG['groups_description'] = 'Beschreibung';
$LANG['groups_name'] = 'Name';

/**
 * Holidays
 */
$LANG['hol_edit_title'] = 'Feiertag bearbeiten: ';
$LANG['hol_list_title'] = 'Feiertage';
$LANG['hol_alert_created'] = 'Der Feiertag wurde angelegt.';
$LANG['hol_alert_created_fail'] = 'Der Feiertag wurde nicht angelegt. Bitte &uuml;berpr&uuml;fe den "Feiertag anlegen" Dialog nach Eingabefehlern.';
$LANG['hol_alert_deleted'] = 'Der Feiertag wurde gel&ouml;scht.';
$LANG['hol_alert_edit'] = 'Feiertag bearbeiten';
$LANG['hol_alert_edit_success'] = 'Die Informationen f&uuml;r diesen Feiertag wurden aktualisiert.';
$LANG['hol_alert_save_failed'] = 'Die neuen Informationen f&uuml;r diesen Feiertag konnten nicht gespeichert werden. Es gab fehlerhafte Eingaben. Bitte pr&uuml;fe die Fehlermeldungen im "Feiertag anlegen" Dialog.';
$LANG['hol_bgcolor'] = 'Hintergundfarbe';
$LANG['hol_bgcolor_comment'] = 'Die Hintergundfarbe wird im Kalender benutzt, egal ob Symbol oder Icon gew&auml;hlt ist. Ein Farbdialog erscheint beim Klicken in das Feld.';
$LANG['hol_businessday'] = 'Z&auml;hlt als Arbeitstag';
$LANG['hol_businessday_comment'] = 'W&auml;hle hier ob dieser Feiertag als Arbeitstag z&auml;hlen soll. Wenn diese Option eingeschaltet ist, wird dieser Tag mit den Abwesenheiten verrechnet.';
$LANG['hol_color'] = 'Textfarbe';
$LANG['hol_color_comment'] = 'W&auml;hle hier die Textfarbe. Ein Farbdialog erscheint beim Klicken in das Feld.';
$LANG['hol_confirm_delete'] = 'Bist du sicher, dass du diesen Feiertag l&ouml;schen willst: ';
$LANG['hol_description'] = 'Beschreibung';
$LANG['hol_description_comment'] = 'Gebe hier eine Beschreibung f&uuml;r den Feiertag ein.';
$LANG['hol_name'] = 'Name';
$LANG['hol_name_comment'] = 'Gebe hier einen Namen f&uuml;r den Feiertag ein.';

/**
 * Home Page
 */
$LANG['home_title'] = 'Willkommen bei ' . $appTitle;

/**
 * Imprint
 * You can add more arrays here in order to display them on the Imprint page
 */
$LANG['imprint'] = array ( 
   array (
      'title' => 'Author',
      'text' => '<i class="fa fa-thumbs-o-up fa-3x pull-left" style="color: #999999;"></i>'.$appTitle.' wurde von George Lewe erstellt (<a href="http://www.lewe.com/">Lewe.com</a>).  
      Die Webapplikation ist reponsiv, geschrieben in HTML5 und CSS3. TeamCal Neo nutzt kostenlose Module von anderen gro&szlig;artigen Entwicklern, die dankenswerter Weise
      ihre Arbeit &ouml;ffentlich verf&uuml;gbar machen. Details dazu befinden sich auf der <a href="index.php?action=about">About Seite</a>.',
   ),
   array (
      'title' => 'Inhalt',
      'text' => '<p><i class="fa fa-file-text-o fa-3x pull-left" style="color: #999999;"></i>Der Inhalt von '.$appTitle.' wurde sorgf&auml;tig vorbereitet und
      erstellt. Wo dieser Inhalt andere Quellen hat, wird auch darauf hingewiesen. Sollte dies nicht der Fall sein, bitte informiere George Lewe mittels dieser <a href="http://www.lewe.com/index.php?page=contact">Kontaktseite</a>.</p>
      <p>Kein Inhalt der Site, ganz oder in Teilen darf vervielf&auml;tigt, reproduziert, kopiert oder wiederwendet werden, in keiner Form, 
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
      applikationsrelevanten Informationen, die auf deiner lokalen Festplatte gespeichert werden. Sie enthalten keine pers&ouml;nlichen Daten und werden auch 
      nicht &uuml;bertragen. Sie sind aber notwendig, damit diese Applikation funktioniert.</p>
      <p>In der EU ist es Gesetz, die Zustimmung des Nutzers dazu zu erhalten. Mit der Nutzung dieser Applikation stimmst du der Benutzung von Cookies zu.</p>',
   ),
);

if ( $C->read('googleAnalytics') AND $C->read("googleAnalyticsID")) {
   $LANG['imprint'][] = array (
      'title' => 'Google Analytics',
      'text' => '<p><i class="fa fa-google fa-3x pull-left" style="color: #999999;"></i>Diese Website benutzt Google Analytics, einen Webanalysedienst der Google Inc.
      ("Google"). Google Analytics verwendet sog. "Cookies", Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Website
      durch Sie erm&ouml;glichen. Die durch den Cookie erzeugten Informationen &uuml;ber Ihre Benutzung dieser Website werden in der Regel an einen Server von Google in den USA
      &uuml;bertragen und dort gespeichert.</p>
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
      
/**
 * Log
 */
$LANG['log_clear'] = 'Periode l&ouml;schen';
$LANG['log_clear_confirm'] = 'Bist du sicher, dass du alle Ereignisse in der angezeigten Periode l&ouml;schen willst?<br>
      Es werden alle Ereignisse jedes Ereignistyps in dieser Periode gel&ouml;scht, auch wenn sie in den Logbuch-Einstellungen verborgen wurden.';
$LANG['log_title'] = 'System Logbuch';
$LANG['log_title_events'] = 'Ereignisse';
$LANG['log_settings'] = 'Logbuch-Einstellungen';
$LANG['log_settings_event'] = 'Ereignistyp';
$LANG['log_settings_log'] = 'Ereignistyp loggen';
$LANG['log_settings_show'] = 'Ereignistyp anzeigen';
$LANG['log_sort_asc'] = 'Aufsteigend sortieren...';
$LANG['log_sort_desc'] = 'Absteigend sortieren...';
$LANG['log_header_when'] = 'Zeitstempel (UTC)';
$LANG['log_header_type'] = 'Ereignistyp';
$LANG['log_header_user'] = 'Nutzer';
$LANG['log_header_event'] = 'Ereignis';
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

/**
 * Login
 */
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
$LANG['login_error_8'] = 'Konto nicht verifiziert. Du solltest eine E-Mail mit einem Verfizierungslink erhalten haben.';
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

/**
 * Maintenance
 */
$LANG['mtce_title'] = 'Unter Wartung';
$LANG['mtce_text'] = 'Wir f&uuml;hren gerade Wartungsarbeiten an der Site durch. Wir entschuldigen die Unanehmlichkeit. Bitte versuche es sp&auml;ter nochmal...';

/**
 * Menu
 */
$LANG['mnu_app'] = $appTitle;
$LANG['mnu_app_homepage'] = 'Startseite';
$LANG['mnu_app_language'] = 'Sprache';
$LANG['mnu_view'] = 'Anzeige';
$LANG['mnu_view_calendar'] = 'Kalender (Monat)';
$LANG['mnu_view_messages'] = 'Benachrichtigungen';
$LANG['mnu_view_statistics'] = 'Statistiken';
$LANG['mnu_view_year'] = 'Kalender (Jahr)';
$LANG['mnu_edit'] = 'Bearbeiten';
$LANG['mnu_edit_calendaredit'] = 'Personenkalender';
$LANG['mnu_edit_monthedit'] = 'Regionenkalender';
$LANG['mnu_edit_messageedit'] = 'Benachrichtigung';
$LANG['mnu_admin'] = 'Administration';
$LANG['mnu_admin_absences'] = 'Abwesenheitstypen';
$LANG['mnu_admin_config'] = 'Konfiguration';
$LANG['mnu_admin_calendaroptions'] = 'Kalenderoptionen';
$LANG['mnu_admin_database'] = 'Datenbankverwaltung';
$LANG['mnu_admin_declination'] = 'Ablehnungsregeln';
$LANG['mnu_admin_env'] = 'Umgebung';
$LANG['mnu_admin_groups'] = 'Gruppen';
$LANG['mnu_admin_holidays'] = 'Feiertage';
$LANG['mnu_admin_perm'] = "Berechtigungsschema";
$LANG['mnu_admin_phpinfo'] = 'PHP Info';
$LANG['mnu_admin_regions'] = 'Regionen';
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

/**
 * Messages
 */
$LANG['msg_title'] = 'Benachrichtigungen f&uuml;r: ';
$LANG['msg_title_edit'] = 'Benachrichtigung Erstellen';
$LANG['msg_code'] = 'Sicherheitscode';
$LANG['msg_code_desc'] = 'Bitte geben Sie den Code ein wie angezeigt. Gro&szlig;- und Kleinschreibung ist nicht relevant.';
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
      Eine Popup Benachrichtigung f&uuml;hrt dazu, dass die Benachrichtigungsseite automatisch angezeigt wird, wenn sich der Benutzer einlogt.';
$LANG['msg_type_email'] = 'E-Mail';
$LANG['msg_type_silent'] = 'Stille Benachrichtigung';
$LANG['msg_type_popup'] = 'Popup Benachrichtigung';

/**
 * Modal dialogs
 */
$LANG['modal_confirm'] = 'Bitte Best&auml;tigen';

/**
 * Month Edit
 */
$LANG['monthedit_title'] = 'Bearbeitung von Monat %s-%s f&uuml;r die Region "%s"';
$LANG['monthedit_alert_update'] = 'Monat aktualisieren';
$LANG['monthedit_alert_update_success'] = 'Der Monat wurden aktualisert.';
$LANG['monthedit_confirm_clearall'] = 'Bist du sicher, dass du alle Feiertage f&uuml;r diesen Monat l&ouml;schen willst?<br><br><strong>Jahr:</strong> %s<br><strong>Monat:</strong> %s<br><strong>Region:</strong> %s';
$LANG['monthedit_clearHoliday'] = 'L&ouml;schen';
$LANG['monthedit_selRegion'] = 'Region ausw&auml;hlen';
$LANG['monthedit_selUser'] = 'Nutzer ausw&auml;hlen';

/**
 * Password rules
 */
$LANG['password_rules_low'] = '<br>Die Passwort Sicherheit ist zurzeit auf "Niedrig" eingestellt. Daraus ergeben sich folgende Regeln:
      <ul>
         <li>Mindestens 4 Zeichen</li>
      </ul>';
$LANG['password_rules_medium'] = '<br>Die Passwort Sicherheit ist zurzeit auf "Medium" eingestellt. Daraus ergeben sich folgende Regeln:
      <ul>
         <li>Mindestens 6 Zeichen</li>
         <li>Mindestens ein Gro&szlig;buchstabe</li>
         <li>Mindestens ein Kleinbuchstabe</li>
         <li>Mindestens eine Zahl</li>
      </ul>';
$LANG['password_rules_high'] = '<br>Die Passwort Sicherheit ist zurzeit auf "Hoch" eingestellt. Daraus ergeben sich folgende Regeln:
      <ul>
         <li>Mindestens 8 Zeichen</li>
         <li>Mindestens ein Gro&szlig;buchstabe</li>
         <li>Mindestens ein Kleinbuchstabe</li>
         <li>Mindestens eine Zahl</li>
         <li>Mindestens ein Sonderzeichen</li>
      </ul>';

/**
 * Permissions
 */
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
$LANG['perm_reset_confirm'] = 'Soll dieses Berechtigungsschema auf den Standard zur&uuml;ckgesetzt werden?';
$LANG['perm_save_scheme'] = 'Schema speichern';
$LANG['perm_select_scheme'] = 'Schema ausw&auml;hlen';
$LANG['perm_select_confirm'] = 'Soll dieses Berechtigungsschema geladen werden? Alle nicht gespeicherten &Auml;nderungen des momentanen Schemas gehen verloren.';
$LANG['perm_view_by_perm'] = 'Nach Berechtigungen anzeigen';
$LANG['perm_view_by_role'] = 'Nach Rollen anzeigen';

$LANG['perm_absenceedit_title'] = 'Abwesenheitstypen Bearbeiten';
$LANG['perm_absenceedit_desc'] = 'Erlaubt das Listen und Bearbeiten von Abwesenheitstypen.';
$LANG['perm_admin_title'] = 'Systemadministration';
$LANG['perm_admin_desc'] = 'Erlaubt den Zugriff auf die Systemadministrationsseiten.';
$LANG['perm_calendaredit_title'] = 'Kalender Editor';
$LANG['perm_calendaredit_desc'] = 'Erlaubt die Benutzung des Kalendereditors. Diese Berechtigung ist mindestens erforderlich, um Kalender zu Bearbeiten.';
$LANG['perm_calendareditall_title'] = 'Alle Kalender Bearbeiten';
$LANG['perm_calendareditall_desc'] = 'Erlaubt die Bearbeitung aller Benutzerkalender.';
$LANG['perm_calendareditgroup_title'] = 'Gruppenkalender Bearbeiten';
$LANG['perm_calendareditgroup_desc'] = 'Erlaubt die Bearbeitung aller Benutzerkalender der eigenen Gruppen.';
$LANG['perm_calendareditown_title'] = 'Eigenen Kalender Bearbeiten';
$LANG['perm_calendareditown_desc'] = 'Erlaubt die Bearbeitung des eigenen Kalenders. Wenn nur eine zentrale Bearbeitung erw&uuml;nscht ist, kann man hiermit die Berechtigung den Nutzern entziehen.';
$LANG['perm_calendaroptions_title'] = 'Kalenderoptionen';
$LANG['perm_calendaroptions_desc'] = 'Erlaubt das Bearbeiten der Kalenderoptionen.';
$LANG['perm_calendarview_title'] = 'Kalender Anzeigen';
$LANG['perm_calendarview_desc'] = 'Erlaubt die generelle Anzeige des Kalenders (Monat und Jahr). Ohne diese Berechtigung kann kein Kalender angezeigt werden. Mit dieser Berechtigung kann 
      nicht angemeldeten Besuchern die Anzeige des Kalenders erlaubt werden.';
$LANG['perm_calendarviewall_title'] = 'Alle Kalender Anzeigen';
$LANG['perm_calendarviewall_desc'] = 'Erlaubt das Anzeigen der Kalender aller Benutzer.';
$LANG['perm_calendarviewgroup_title'] = 'Gruppen Kalender Anzeigen';
$LANG['perm_calendarviewgroup_desc'] = 'Erlaubt das Anzeigen der Kalender von Benutzern in eigenen Gruppen.';
$LANG['perm_declination_title'] = 'Ablehnungsregeln Bearbeiten';
$LANG['perm_declination_desc'] = 'Erlaubt das Bearbeiten der Ablehnungsregeln.';
$LANG['perm_groups_title'] = 'Gruppen Bearbeiten';
$LANG['perm_groups_desc'] = 'Erlaubt as Listen und Bearbeiten von Gruppen.';
$LANG['perm_groupmemberships_title'] = 'Gruppenmitgliedschaften Bearbeiten';
$LANG['perm_groupmemberships_desc'] = 'Erlaubt es, Benutzer als Mitglied oder Manager Gruppen zuzuordnen.';
$LANG['perm_holidays_title'] = 'Feiertage Bearbeiten';
$LANG['perm_holidays_desc'] = 'Erlaubt as Listen und Bearbeiten von Feiertagen.';
$LANG['perm_messageview_title'] = 'Benachrichtigungen Anzeigen';
$LANG['perm_messageview_desc'] = 'Erlaubt den Zugriff auf die Benachrichtigung-Anzeige-Seite.';
$LANG['perm_messageedit_title'] = 'Benachrichtigungen Erstellen';
$LANG['perm_messageedit_desc'] = 'Erlaubt den Zugriff auf die Benachrichtigung-Erstellen-Seite.';
$LANG['perm_regions_title'] = 'Regionen Bearbeiten';
$LANG['perm_regions_desc'] = 'Erlaubt as Listen und Bearbeiten von Regionen und deren Feiertagen.';
$LANG['perm_roles_title'] = 'Rollen Bearbeiten';
$LANG['perm_roles_desc'] = 'Erlaubt as Listen und Bearbeiten von Rollen.';
$LANG['perm_statistics_title'] = 'Statistiken Anzeigen';
$LANG['perm_statistics_desc'] = 'Erlaubt das Anzeigen der Statistik Seite.';
$LANG['perm_useraccount_title'] = 'Nutzerkonto Bearbeiten';
$LANG['perm_useraccount_desc'] = 'Erlaubt das Bearbeiten des Konto Reiters im Nutzerprofil.';
$LANG['perm_useradmin_title'] = 'Benutzerkonten Bearbeiten';
$LANG['perm_useradmin_desc'] = 'Erlaubt das Listen und Hinzuf&uuml;gen von Benutzerkonten.';
$LANG['perm_useredit_title'] = 'Nutzerprofil Bearbeiten';
$LANG['perm_useredit_desc'] = 'Erlaubt das Bearbeiten des eignen Nutzerprofils.';
$LANG['perm_viewprofile_title'] = 'Nutzerprofil Anzeigen';
$LANG['perm_viewprofile_desc'] = 'Erlaubt den Zugriff auf die Nutzerprofil-Anzeige-Seite mit Basisinformationen wie Name oder Telefonnummer. Das Anzeigen von User Popups ist 
      ebenfalls abh&auml;ngig von dieser Berechtigung.';

/**
 * Phpinfo
 */
$LANG['phpinfo_title'] = 'PHP Info';

/**
 * Profile
 */
$LANG['profile_create_title'] = 'Neues Benutzerkonto anlegen';
$LANG['profile_create_mail'] = 'E-Mail an Benutzer senden';
$LANG['profile_create_mail_comment'] = 'Sendet eine E-Mail an den angelegten Benutzer.';

$LANG['profile_view_title'] = 'Benutzerkonto von: ';

$LANG['profile_edit_title'] = 'Benutzerkonto editieren: ';
$LANG['profile_tab_account'] = 'Konto';
$LANG['profile_tab_avatar'] = 'Avatar';
$LANG['profile_tab_contact'] = 'Kontakt';
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
$LANG['profile_mobilephone'] = 'Handy';
$LANG['profile_mobilephone_comment'] = '';
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

/**
 * Region
 */
$LANG['region_edit_title'] = 'Gruppe editieren: ';
$LANG['region_alert_edit'] = 'Gruppe aktualisieren';
$LANG['region_alert_edit_success'] = 'Die Informationen f&uuml;r diese Gruppe wurden aktualisiert.';
$LANG['region_alert_save_failed'] = 'Die neuen Informationen f&uuml;r diese Gruppe konnten nicht gespeichert werden. Es gab fehlerhafte Eingaben. Bitte pr&uuml;fe die Fehlermeldungen.';
$LANG['region_name'] = 'Name';
$LANG['region_name_comment'] = '';
$LANG['region_description'] = 'Beschreibung';
$LANG['region_description_comment'] = '';

/**
 * Regions
 */
$LANG['regions_title'] = 'Regionen';
$LANG['regions_tab_list'] = 'Liste';
$LANG['regions_tab_ical'] = 'iCal Import';
$LANG['regions_tab_transfer'] = 'Region &uuml;bertragen';
$LANG['regions_alert_merge_same'] = 'Die Quell- und Zielregion bei einer &Uuml;bertragung muss unterschiedlich sein.';
$LANG['regions_alert_no_file'] = 'Es wurde keine iCal Datei ausgew&auml;hlt.';
$LANG['regions_alert_region_created'] = 'Die Region wurde angelegt.';
$LANG['regions_alert_region_created_fail'] = 'Die Region wurde nicht angelegt. Bitte &uuml;berpr&uuml;fe den "Region anlegen" Dialog nach Eingabefehlern.';
$LANG['regions_alert_region_deleted'] = 'Die Region wurde gel&ouml;scht.';
$LANG['regions_confirm_delete'] = 'Bist du sicher, dass du diese Region l&ouml;schen willst?';
$LANG['regions_description'] = 'Beschreibung';
$LANG['regions_ical_file'] = 'iCal Datei';
$LANG['regions_ical_file_comment'] = 'W&auml;hle eine iCal Datei von einem lokalen Laufwerk. Die Datei sollte nur Ganztagsereignisse enthalten.';
$LANG['regions_ical_holiday'] = 'iCal Feiertag';
$LANG['regions_ical_holiday_comment'] = 'W&auml;hle den Feiertag aus, der f&uuml;r die iCal Ereignisse im Kalender eingetragen werden soll.';
$LANG['regions_ical_imported'] = 'Die iCal Datei "%s" wurde in die Region "%s" importiert.';
$LANG['regions_ical_overwrite'] = '&Uuml;berschreiben';
$LANG['regions_ical_overwrite_comment'] = 'W&auml;hle hier, ob bestehende Feiertage in der Zielregion &uuml;berschrieben werden sollen. Wenn diese Option nicht eingeschaltet ist,
      bleiben die bestehenden Eintr&auml;ge in der Zielregion erhalten.';
$LANG['regions_ical_region'] = 'iCal Region';
$LANG['regions_ical_region_comment'] = 'W&auml;hle die Region aus, in die die iCal Ereignisse eingetragen werden soll.';
$LANG['regions_transferred'] = 'Die Region "%s" wurde in die Region "%s" &uuml;bertragen.';
$LANG['regions_name'] = 'Name';
$LANG['regions_region_a'] = 'Quellregion';
$LANG['regions_region_a_comment'] = 'W&auml;hle hier die Quellregion, die in die Zielregion &uuml;bertragen werden soll.';
$LANG['regions_region_b'] = 'Zielregion';
$LANG['regions_region_b_comment'] = 'W&auml;hle hier die Zielregion, in die die Quellregion &uuml;bertragen werden soll.';
$LANG['regions_region_overwrite'] = '&Uuml;berschreiben';
$LANG['regions_region_overwrite_comment'] = 'W&auml;hle hier, ob die Eintr&auml;ge in der Zielregion &uuml;berschrieben werden sollen. Wenn diese Option nicht eingeschaltet ist,
      bleiben die bestehenden Eintr&auml;ge in der Zielregion erhalten.';

/**
 * Register
 */
$LANG['register_title'] = 'Benutzer Registrierung';
$LANG['register_alert_success'] = 'Das Benutzerkonto wurde registriert und eine E-mail mit den entsprechenden Informationen versendet.';
$LANG['register_alert_failed'] = 'Die Registrierung ist fehlgeschlagen. Bitte &uuml;ber&uuml;fe die Eingaben.';
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

/**
 * Role
 */
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

/**
 * Roles
 */
$LANG['roles_title'] = 'Rollen';
$LANG['roles_alert_role_created'] = 'Die Rollee wurde angelegt.';
$LANG['roles_alert_role_created_fail_input'] = 'Die Rolle wurde nicht angelegt. Bitte &uuml;berpr&uuml;fe den "Rolle anlegen" Dialog nach Eingabefehlern.';
$LANG['roles_alert_role_created_fail_duplicate'] = 'Die Rolle wurde nicht angelegt. Eine Rolle mit diesem Namen existiert bereits.';
$LANG['roles_alert_role_deleted'] = 'Die Rolle wurde gel&ouml;scht.';
$LANG['roles_confirm_delete'] = 'Bist du sicher, dass du diese Rolle l&ouml;schen willst?';
$LANG['roles_description'] = 'Beschreibung';
$LANG['roles_name'] = 'Name';

/**
 * Statistics
 */
$LANG['stats_title_absences'] = 'Gesamte Abwesenheiten';
$LANG['stats_absenceType'] = 'Abwesenheitstyp';
$LANG['stats_absenceType_comment'] = 'W&auml;hle den Abwesenheitstyp f&uuml;r die Statistik.';
$LANG['stats_endDate'] = 'Eigenes Ende Datum';
$LANG['stats_endDate_comment'] = 'W&auml;hle ein eigenes Enddatum f&uuml;r die Statistik. Damit dieses Datum angewendet wird, muss in der Zeitraum Liste "'.$LANG['custom'].'" ausgew&auml;hlt sein.';
$LANG['stats_modalAbsenceTitle'] = 'W&auml;hle den Abwesenheitstyp f&uuml;r die Statistik.';
$LANG['stats_modalGroupTitle'] = 'W&auml;hle die Gruppe f&uuml;r die Statistik.';
$LANG['stats_modalPeriodTitle'] = 'W&auml;hle den Zeitraum f&uuml;r die Statistik.';
$LANG['stats_modalScaleTitle'] = 'W&auml;hle die Skala f&uuml;r die Statistik.';
$LANG['stats_period'] = 'Zeitraum';
$LANG['stats_period_comment'] = 'W&auml;hle den Zeitraum f&uuml;r die Statistik.';
$LANG['stats_scale'] = 'Skala';
$LANG['stats_scale_comment'] = 'W&auml;hle die Skala f&uuml;r das Diagramm.
      <ul>
         <li>Automatisch: Der Maximalwert des Diagramms ist der maximal gelesene Wert.</li>
         <li>Smart: Der Maximalwert des Diagramms ist der maximal gelesene Wert plus 5.</li>
         <li>Individuell: Der Maximalwert des Diagramms kann im Feld unten selbst angegeben werden.</li>
      </ul>';
$LANG['stats_scale_max'] = 'Maximalwert';
$LANG['stats_scale_max_comment'] = 'W&auml;hle einen eigenen Maximalwert f&uuml;r das Diagramm. Standard ist 30.<br>Damit dieser Wert angewendet wird, muss in der Skala Liste "'.$LANG['custom'].'" ausgew&auml;hlt sein.';
$LANG['stats_scale_smart'] = 'Smartwert';
$LANG['stats_scale_smart_comment'] = 'Der Smartwert wird zum gr&ouml;&szlig;ten gelesenen Wert addiert. Die Summe wird als Maximalwert der Diagrammskala benutzt. Standard ist 4.<br>Damit dieser Wert angewendet wird, muss in der Skala Liste "'.$LANG['smart'].'" ausgew&auml;hlt sein.';
$LANG['stats_startDate'] = 'Eigenes Start Datum';
$LANG['stats_startDate_comment'] = 'W&auml;hle ein eigenes Startdatum f&uuml;r die Statistik.<br>Damit dieses Datum angewendet wird, muss in der Zeitraum Liste "'.$LANG['custom'].'" ausgew&auml;hlt sein.';

/**
 * Status Bar
 */
$LANG['status_logged_in'] = 'Du bist eingeloggt als ';
$LANG['status_logged_out'] = 'Nicht eingeloggt';
$LANG['status_ut_user'] = 'Regul&auml;rer Nutzer';
$LANG['status_ut_manager'] = 'Manager der Gruppe: ';
$LANG['status_ut_director'] = 'Direktor';
$LANG['status_ut_assistant'] = 'Assistent';
$LANG['status_ut_admin'] = 'Administrator';

/**
 * Upload
 */
$LANG['upload_maxsize'] = 'Maximale Dateigr&ouml;&szlig;e';
$LANG['upload_extensions'] = 'Erlaubte Bildformate';
$LANG['upload_error_0'] = 'Die Datei "%s" wurde erfolgreich hochgeladen.';
$LANG['upload_error_1'] = 'Die hochgeladene Datei &uuml;bersteigt die maximale Dateigr&ouml;&szlig;e der Severkonfiguration.';
$LANG['upload_error_2'] = 'Die hochgeladene Datei &uuml;bersteigt die MAX_FILE_SIZE Direktive des HTML Formulars.';
$LANG['upload_error_3'] = 'Die Datei wurde nur teilweise hochgeladen.';
$LANG['upload_error_4'] = 'Es wurde keine Datei hochgeladen.';
$LANG['upload_error_10'] = 'Bitte w&auml;hle eine Datei zum Hochladen aus.';
$LANG['upload_error_11'] = 'Nur Bilddateien der folgenden Formate sind erlaubt: %s';
$LANG['upload_error_12'] = 'Der Dateiname enth&auml;lt ung&uuml;ltige Zeichen. Bitte benutze nur alphanumerische Zeichen und den Unterstrich. Der Dateiname muss mit einem Punkt und der Erweiterung enden.';
$LANG['upload_error_13'] = 'Der Dateiname &uuml;bersteigt die maximale L&auml;nge von %d Zeichen.';
$LANG['upload_error_14'] = 'Das Uploadverzeichnis existiert nicht.';
$LANG['upload_error_15'] = 'Eine Datei mit dem Name "%s" existiert bereits.';
$LANG['upload_error_16'] = 'Die hochgeladene Datei wurde in "%s" umbenannt.';
$LANG['upload_error_17'] = 'Die Datei "%s" existiert nicht.';

/**
 * Users
 */
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

/**
 * Year
 */
$LANG['year_title'] = 'Jahreskalender %s f&uuml;r %s (Region: %s)';
$LANG['year_selRegion'] = 'Region ausw&auml;hlen';
$LANG['year_selUser'] = 'Nutzer ausw&auml;hlen';
$LANG['year_showyearmobile'] = 'Der Jahreskalender dient der &Uuml;bersicht "auf den ersten Blick". Auf mobilen Ger&auml;ten mit geringer Bildschirmbreite kann dies
      ohne horizontales Scrollen nicht erreicht werden.</p><p>Mit dem "Kalender anzeigen" Knopf kann die Anzeige aktiviert und horizontales Scrollen akzeptiert werden.</p>';
?>
