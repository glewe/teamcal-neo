<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings German: Alerts
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['alert_alert_title'] = 'ALARM';
$LANG['alert_captcha_wrong'] = 'Captcha Code falsch';
$LANG['alert_captcha_wrong_help'] = 'Der Captcha Code muss das korrekte Ergebnis der mathematischen Frage sein.';
$LANG['alert_captcha_wrong_text'] = 'Der Captcha Code ist falsch. Bitte versuchen Sie es erneut.';
$LANG['alert_controller_not_found_help'] = 'Bitte überprüfe die Installation. Die Datei existiert nicht oder es fehlt die nötige Berechtigung für den Zugriff.';
$LANG['alert_controller_not_found_subject'] = 'Controller nicht gefunden';
$LANG['alert_controller_not_found_text'] = 'Der Controller "%1%" konnte nicht gefunden werden.';
$LANG['alert_csrf_invalid_help'] = 'Bitte lade die Seite neu und versuche es nochmal. Wenn das Problem weiter besteht, kontaktiere deinen Administrator.';
$LANG['alert_csrf_invalid_subject'] = 'Sicherheits-Token Ungültig';
$LANG['alert_csrf_invalid_text'] = 'Die Anfrage enthielt ein fehlendes oder ungültiges Sicherheits-Token.';
$LANG['alert_danger_title'] = 'FEHLER';
$LANG['alert_decl_allowmonth_reached'] = 'Die maximale Anzahl von %1% pro Monat für diesen Abwesenheitstyp wurde erreicht.';
$LANG['alert_decl_allowweek_reached'] = 'Die maximale Anzahl von %1% pro Woche für diesen Abwesenheitstyp wurde erreicht.';
$LANG['alert_decl_allowyear_reached'] = 'Die maximale Anzahl von %1% pro Jahr für diesen Abwesenheitstyp wurde erreicht.';
$LANG['alert_decl_approval_required'] = 'Dieser Abwesenheitstyp benötigt Managerbestätigung. Die Abwesenheit wurde in den Kalender eingetragen aber auch eine Tagesnotiz über die ausstehende Bestätigung. Dein Manager wurde per E-Mail informiert.';
$LANG['alert_decl_approval_required_daynote'] = 'Diese Abwesenheit wurde angefragt, ist aber noch nicht bestätigt.';
$LANG['alert_decl_before_date'] = 'Abwesenheitsänderungen vor dem folgenden Datum sind nicht erlaubt: ';
$LANG['alert_decl_group_maxabsent'] = 'Die maximale Anzahl von abwesenden Mitgliedern wurde für folgende Gruppe/n überschritten: ';
$LANG['alert_decl_group_minpresent'] = 'Die minimale Anzahl von anwesenden Mitgliedern wurde für folgende Gruppe/n unterschritten: ';
$LANG['alert_decl_group_threshold'] = 'Die Abwesenheitsgrenze wurde erreicht für die Gruppe(n): ';
$LANG['alert_decl_holiday_noabsence'] = 'Dieser Tag ist ein Feiertag, der keine Abwesenheiten erlaubt.';
$LANG['alert_decl_period'] = 'Abwesenheitsänderungen in folgendem Zeitraum sind nicht erlaubt: ';
$LANG['alert_decl_takeover'] = 'Abwesenheitstyp \'%s\' nicht für Übernahme zugelassen.';
$LANG['alert_decl_total_threshold'] = 'Die generelle Abwesenheitsgrenze wurde erreicht.';
$LANG['alert_imp_admin'] = 'Zeile %s: Der Benutzername "admin" ist für den Import nicht erlaubt.';
$LANG['alert_imp_columns'] = 'Zeile %s: Es mehr oder weniger als %s Spalten in der Zeile.';
$LANG['alert_imp_email'] = 'Zeile %s: "%s" ist keine gültige E-Mail Adresse.';
$LANG['alert_imp_exists'] = 'Zeile %s: Der Benutzername "%s" existiert bereits.';
$LANG['alert_imp_firstname'] = 'Zeile %s: Der Vorname "%s" entspricht nicht dem erlaubten Format (alphanumerische Zeichen, Leerzeichen, Punkt, Bindestrich, Unterstrich).';
$LANG['alert_imp_gender'] = 'Zeile %s: Falsches Geschlecht "%s" (male or female).';
$LANG['alert_imp_lastname'] = 'Zeile %s: Der Nachname "%s" entspricht nicht dem erlaubten Format (alphanumerische Zeichen, Leerzeichen, Punkt, Bindestrich, Unterstrich).';
$LANG['alert_imp_subject'] = 'CSV Import Fehler aufgetreten';
$LANG['alert_imp_username'] = 'Zeile %s: Der Benutzername "%s" entspricht nicht dem erlaubten Format (alphanumerische Zeichen, Punkt, @).';
$LANG['alert_info_title'] = 'INFORMATION';
$LANG['alert_input'] = 'Eingabevalidierung fehlgeschlagen';
$LANG['alert_input_alpha'] = 'Dieses Feld erlaubt nur eine Eingabe von alphabetischen Zeichen.';
$LANG['alert_input_alpha_numeric'] = 'Dieses Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen.';
$LANG['alert_input_alpha_numeric_dash'] = 'Dieses Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen plus Bindestrich und Unterstrich.';
$LANG['alert_input_alpha_numeric_dash_blank'] = 'Dieses Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen plus Leerzeichen, Bindestrich und Unterstrich.';
$LANG['alert_input_alpha_numeric_dash_blank_dot'] = 'Dieses Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen plus Leerzeichen, Punkt, Bindestrich und Unterstrich.';
$LANG['alert_input_alpha_numeric_dash_blank_special'] = 'Dieses Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen, Leerzeichen, Bindestrich, Unterstrich und die Sonderzeichen \'!@#$%^&*().';
$LANG['alert_input_ctype_graph'] = 'Dieses Feld erlaubt nur eine Eingabe von darstellbaren bzw. druckbaren Zeichen.';
$LANG['alert_input_date'] = 'Das Datum muss um ISO 8601 Format sein, z.b. 2014-01-01.';
$LANG['alert_input_email'] = 'Die eingegebene E-Mail Adresse ist ungültig.';
$LANG['alert_input_equal'] = 'Der Wert in diesem Feld muss gleich dem in Feld "%s" sein.';
$LANG['alert_input_equal_string'] = 'Der Text in diesem Feld muss "%s" lauten.';
$LANG['alert_input_exact_length'] = 'Die Eingabe dieses Feldes muss genau %s Zeichen lang sein.';
$LANG['alert_input_greater_than'] = 'Der Wert in diesem Feld muss größer sein als der im Feld "%s".';
$LANG['alert_input_hex_color'] = 'Diese Feld erlaubt nur eine Eingabe von einem sechs Zeichen langen hexadezimalen Farbcode, e.g. FF5733.';
$LANG['alert_input_hexadecimal'] = 'Diese Feld erlaubt nur eine Eingabe von hexadezimalen Zeichen.';
$LANG['alert_input_ip_address'] = 'Die Eingabe dieses Feldes ist keine gültige IP Adresse.';
$LANG['alert_input_less_than'] = 'Der Wert in diesem Feld muss kleiner sein als der im Feld "%s".';
$LANG['alert_input_match'] = 'Das Feld "%s" muss mit dem Feld "%s" übereinstimmen.';
$LANG['alert_input_max_length'] = 'Die Eingabe dieses Feldes darf maximal %s Zeichen lang sein.';
$LANG['alert_input_min_length'] = 'Die Eingabe dieses Feldes muss minimal %s Zeichen lang sein.';
$LANG['alert_input_numeric'] = 'Die Eingabe dieses Feldes muss numerisch sein.';
$LANG['alert_input_phone_number'] = 'Die Eingabe dieses Feldes muss eine gültige Telefonnummer sein, z.B. (555) 123 4567 oder +49 172 123 4567.';
$LANG['alert_input_pwdhigh'] = 'Das Passwort muss mindestens 8 Zeichen lang sein, mindestens einen Kleinbuchstaben, einen Großbuchstaben, eine Zahl und ein Sonderzeichen enthalten. Erlaubt sind Klein- und Großbuchstaben, Zahlen und die folgenden Sonderzeichen: !@#$%^&amp;*().';
$LANG['alert_input_pwdlow'] = 'Das Passwort muss mindestens 4 Zeichen lang sein. Erlaubt sind Klein- und Großbuchstaben, Zahlen und die folgenden Sonderzeichen: !@#$%^&amp;*().';
$LANG['alert_input_pwdmedium'] = 'Das Passwort muss mindestens 6 Zeichen lang sein, mindestens einen Kleinbuchstaben, einen Großbuchstaben und eine Zahl enthalten. Erlaubt sind Klein- und Großbuchstaben, Zahlen und die folgenden Sonderzeichen: !@#$%^&amp;*().';
$LANG['alert_input_regex_match'] = 'Die Eingabe dieses Feldes entsprach nicht dem regulären Ausdruck "%s".';
$LANG['alert_input_required'] = 'Dieses Feld ist eine Pflichteingabe.';
$LANG['alert_input_username'] = 'Diese Feld erlaubt nur eine Eingabe von alphanumerischen Zeichen, Bindestrich, Unterstrich, Punkt und @.';
$LANG['alert_input_validation_subject'] = 'Eingabevalidierung';
$LANG['alert_license_subject'] = 'Lizenzmanagement';
$LANG['alert_maintenance_help'] = 'Ein Administrator kann die Website wieder aktiv setzten unter Administration -> Framework-Konfiguration -> System.';
$LANG['alert_maintenance_subject'] = 'Website in Wartung';
$LANG['alert_maintenance_text'] = 'Die Website is zurzeit auf "Unter Wartung" gesetzt. Normale Nutzer können keine Funktionen nutzen.';
$LANG['alert_no_data_help'] = 'Die Operation konnte wegen fehlender oder falscher Daten nicht ausgeführt werden.';
$LANG['alert_no_data_subject'] = 'Fehlerhafte Daten';
$LANG['alert_no_data_text'] = 'Es wurden falsche oder unzureichende Daten für diese Operation übermittelt.';
$LANG['alert_not_allowed_help'] = 'Wenn du nicht eingeloggt bist, dann ist öffentlicher Zugriff auf diese Seite nicht erlaubt. Wenn du eingeloggt bist, fehlt deiner Rolle die nötige Berechtigung für den Zugriff.';
$LANG['alert_not_allowed_subject'] = 'Zugriff nicht erlaubt';
$LANG['alert_not_allowed_text'] = 'Du hast nicht die nötige Berechtigung auf diese Seite oder Funktion zuzugreifen.';
$LANG['alert_not_enabled_subject'] = 'Funktion nicht aktiviert';
$LANG['alert_not_enabled_text'] = 'Diese Funktion ist zurzeit nicht aktiviert.';
$LANG['alert_perm_default'] = 'Das "Default" Schema kann nicht auf sich selbst zurückgesetzt werden.';
$LANG['alert_perm_exists'] = 'Das Berechtigungsschema "%1%" existiert bereits. Bitte wähle einen anderen Name oder lösche das existierende zuerst.';
$LANG['alert_perm_invalid'] = 'Das neue Berechtigungsschema "%1%" ist ungültig. Im Namen sind nur Buchstaben und Zahlen erlaubt.';
$LANG['alert_pwdTokenExpired_help'] = 'Gehe zum Login Screen und fordere einen neuen Token an.';
$LANG['alert_pwdTokenExpired_subject'] = 'Token Abgelaufen';
$LANG['alert_pwdTokenExpired_text'] = 'Der Token für das Zurücksetzen des Passworts ist 24 Stunden gültig und ist abgelaufen.';
$LANG['alert_reg_approval_needed'] = 'Die Verifizierung war erfolgreich. Allerdings muss das Nutzerkonto von einem Administrator freigeschaltet werden. Er/Sie wurde per Mail informiert.';
$LANG['alert_reg_mismatch'] = 'Der Verifizierungscode stimmt nicht mit dem überein, der erstellt wurde. Eine Mail wurde an den Admin geschickt, um die Anfrage zu prüfen.';
$LANG['alert_reg_no_user'] = 'Der Benutzername konnte nicht gefunden werden. Wurde er registriert?';
$LANG['alert_reg_no_vcode'] = 'Ein Verifizierungscode konnte nicht gefunden werden. Wurde bereits verifiziert? Kontaktiere den Administrator.';
$LANG['alert_reg_subject'] = 'Nutzerregistrierung';
$LANG['alert_reg_success'] = 'Die Verifizierung war erfolgreich. Du kannst dich nun einloggen und die Applikation nutzen.';
$LANG['alert_secret_exists_help'] = 'Aus Sicherheitsgründen kann nur ein Administrator diese entfernen, damit du ein neues "Onboarding" vornehmen kannst. Bitte kontaktiere deinen Administrator.';
$LANG['alert_secret_exists_subject'] = 'Zwei Faktor Authentifizierung Existiert';
$LANG['alert_secret_exists_text'] = 'Eine zwei Faktor Authentifizierung existiert bereits für dein Konto.';
$LANG['alert_success_title'] = 'ERFOLG';
$LANG['alert_upl_csv_subject'] = 'CSV Datei Hochladen';
$LANG['alert_upl_doc_subject'] = 'Dokumente Hochladen';
$LANG['alert_upl_img_subject'] = 'Bilder Hochladen';
$LANG['alert_warning_title'] = 'WARNUNG';
