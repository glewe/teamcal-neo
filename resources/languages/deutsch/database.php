<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings German: Database page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['db_alert_delete'] = 'Datensätze Löschen';
$LANG['db_alert_delete_success'] = 'Die ausgewählten Löschungen wurden durchgeführt.';
$LANG['db_alert_failed'] = 'Die Operation konnte nicht durchgeführt werden. Bitte überprüfe deine Eingaben.';
$LANG['db_alert_cleanup'] = 'Aufräumen';
$LANG['db_alert_cleanup_success'] = 'Die ausgewählten Aufräumarbeiten wurden durchgeführt.';
$LANG['db_alert_optimize'] = 'Tabellen optimieren';
$LANG['db_alert_optimize_success'] = 'Alle Datenbanktabellen wurden optimiert.';
$LANG['db_alert_repair'] = 'Datensätze Reparieren';
$LANG['db_alert_repair_success'] = 'Die ausgewählten Reparaturen wurden durchgeführt.';
$LANG['db_alert_reset'] = 'Datenbank zurücksetzen';
$LANG['db_alert_reset_fail'] = 'Eine oder mehrere SQL Anweisungen sind fehlgeschlagen. Die Datenbank könnte unvollständig oder korrupt sein.';
$LANG['db_alert_reset_success'] = 'Die Datenbank wurde erfolgreich auf die Beispieldaten zurückgesetzt.';
$LANG['db_alert_url'] = 'Datenbank Verwaltungs-URL';
$LANG['db_alert_url_fail'] = 'Bitte gib eine gültige URL für die Datenbankverwaltung ein.';
$LANG['db_alert_url_success'] = 'Die URL zur Datenbankverwaltung wurde erfolgreich gespeichert.';
$LANG['db_application'] = 'Datenbank Verwaltung';
$LANG['db_clean_before'] = 'Bevor-Datum';
$LANG['db_clean_before_comment'] = 'Die oben gewählten Datensätze, die gleich alt oder äter sind als das Datum hier, werden gleöscht.';
$LANG['db_clean_confirm'] = 'Bestätigung';
$LANG['db_clean_confirm_comment'] = 'Bitte gebe hier "CLEANUP" ein, um die Aktion zu bestätigen.';
$LANG['db_clean_daynoteRegions'] = 'Tagesnotiz-Regionen';
$LANG['db_clean_daynoteRegions_comment'] = 'Diese Option prüft, ob es Tagesnotizen ohne Regionszuordnung gibt. Wenn dies der Fall ist, wird die Default Region eingetragen.';
$LANG['db_clean_daynotes'] = 'Tagesnotizen aufräumen...';
$LANG['db_clean_holidays'] = 'Feiertage aufräumen...';
$LANG['db_clean_months'] = 'Regionskalender aufräumen...';
$LANG['db_clean_templates'] = 'Benutzerkalender aufräumen...';
$LANG['db_clean_what'] = 'Was soll aufgeräumt werden';
$LANG['db_clean_what_comment'] = 'Wähle hier, was aufgeräumt werden soll. Alle Datensätze, die gleich alt oder älter sind als das "Bevor-Datum" werden gelöscht.
 Regions- und Benutzerkalender werden nach Monat gelöscht, unabhängig vom Tag. Neuere Datensätze bleiben erhalten.';
$LANG['db_confirm'] = 'Bestätigung';
$LANG['db_dbURL'] = 'Datenbank URL';
$LANG['db_dbURL_comment'] = 'Hier kann ein direkter Link zur bevorzugten Datenbank-Applikation für diese Website angegeben werden. Wenn hier eine gültige URL gespeichert ist, wird eine Schaltfläche angezeigt, die dorthin verlinkt.';
$LANG['db_del_archive'] = 'Alle archivierten Datensätze löschen';
$LANG['db_del_confirm_comment'] = 'Gib bitte "DELETE" ein, um diese Aktion zu bestätigen:';
$LANG['db_del_groups'] = 'Alle Gruppen löschen';
$LANG['db_del_log'] = 'Alle System Log Einträge löschen';
$LANG['db_del_messages'] = 'Alle Benachrichtigungen löschen';
$LANG['db_del_orphMessages'] = 'Verwaiste Benachrichtigungen löschen';
$LANG['db_del_permissions'] = 'Berechtigungsschemen löschen (ausser "Default")';
$LANG['db_del_users'] = 'Alle User inkl. deren Abwesenheiten und Notizen löschen (ausser "admin")';
$LANG['db_del_what'] = 'Was soll gelöscht werden?';
$LANG['db_del_what_comment'] = 'Gib hier an, was gelöscht werden soll.';
$LANG['db_optimize'] = 'Datenbanktabellen optimieren';
$LANG['db_optimize_comment'] = 'Reorganisiert die Tabellendaten und deren Indexinformationen in der Datenbank, um Speicherplatz zu reduzieren und die I/O Effizienz zu verbessern.';
$LANG['db_repair_applying'] = 'Korrekturen werden angewendet...';
$LANG['db_repair_backup_warning'] = 'Es wird dringend empfohlen, deine Datenbank vor dem Anwenden der Korrekturen zu sichern. Reparatur-Operationen sind nicht tabellenübergreifend transaktional und können bei großen Datenbanken viel Zeit in Anspruch nehmen.';
$LANG['db_repair_checking'] = 'Datenbankstruktur wird geprüft...';
$LANG['db_repair_dbStructure'] = 'Datenbankstruktur';
$LANG['db_repair_dbStructure_comment'] = 'Diese Option prüft die Struktur der aktuellen Datenbank gegen die Struktur, die mit dieser Version ausgeliefert wurde. Ein Dialog zeigt die gefundenen Fehler an, damit du die Behebung bestätigen kannst.';
$LANG['db_repair_no_issues'] = 'Keine Probleme gefunden.';
$LANG['db_repair_select_option'] = 'Bitte wähle eine Option.';
$LANG['db_reset_basic'] = 'Basisdaten';
$LANG['db_reset_danger'] = '<strong>Achtung!</strong> Alle aktuellen Daten werden durch das Zurücksetzen gelöscht!!';
$LANG['db_reset_sample'] = 'Basis- plus Beispieldaten';
$LANG['db_resetDataset'] = 'Dataset Auswahl';
$LANG['db_resetDataset_comment'] = 'Wähle das Dataset aus, auf das die Datenbank zurückgesetzt werden soll. "Basic" ist das Standard Dataset der Applikation ohne Beispieldaten. "Sample" ist das Standard Dataset der Applikation mit Beispieldaten.';
$LANG['db_resetString'] = 'Bestätigung';
$LANG['db_resetString_comment'] = 'Das Zurücksetzen der Datenbank wird alle Daten mit den Beispieldaten der Applikation ersetzen.<br>
      Gib den folgenden Text zur Bestätigung ein: "YesIAmSure".';
$LANG['db_tab_admin'] = 'Verwaltung';
$LANG['db_tab_cleanup'] = 'Aufräumen';
$LANG['db_tab_dbinfo'] = 'Datenbank Information';
$LANG['db_tab_delete'] = 'Datensätze löschen';
$LANG['db_tab_optimize'] = 'Tabellen optimieren';
$LANG['db_tab_repair'] = 'Reparieren';
$LANG['db_tab_reset'] = 'Datenbank zurücksetzen';
$LANG['db_tab_tcpimp'] = 'TeamCal Pro Import';
$LANG['db_title'] = 'Datenbank Verwaltung';
$LANG['db_tab_import'] = 'Import aus 5.3.7';
$LANG['db_import_intro'] = '<strong>Du aktualisierst von TeamCal Neo 5.3.7?</strong> Installiere TeamCal Neo 6 als neue Installation (eigener Ordner, eigene Datenbank), melde dich als Administrator an und importiere hier die Daten deiner alten Installation. Die alte Installation und ihre Datenbank werden nur gelesen und bleiben unverändert, du kannst also jederzeit zu ihr zurückkehren.';
$LANG['db_import_folder'] = 'Ordner der alten Installation';
$LANG['db_import_folder_comment'] = 'Der Ordner auf dem Server, der dein altes TeamCal Neo 5.3.7 enthält (mit <code>index.php</code> und <code>config</code>). Gib den vollständigen Pfad oder einen Pfad relativ zu dieser Installation an, z.B. <code>../tcneo5</code>. Die Datenbankverbindung wird aus der <code>.env</code>-Datei gelesen, Avatare und Anhänge werden von dort kopiert. Leer lassen, um nur die Datenbank zu importieren.';
$LANG['db_import_folder_placeholder'] = '../tcneo5';
$LANG['db_import_db_details'] = 'Datenbankverbindung der alten Installation';
$LANG['db_import_db_comment'] = 'Fülle nur aus, was nicht aus dem alten Ordner gelesen werden kann. Ausgefüllte Felder überschreiben die aus dem Ordner gelesenen Werte.';
$LANG['db_import_host'] = 'Datenbankserver';
$LANG['db_import_port'] = 'Port';
$LANG['db_import_name'] = 'Datenbankname';
$LANG['db_import_user'] = 'Benutzername';
$LANG['db_import_pass'] = 'Passwort';
$LANG['db_import_prefix'] = 'Tabellenpräfix';
$LANG['btn_import_check'] = 'Alte Installation prüfen';
$LANG['db_import_checking'] = 'Prüfe ...';
$LANG['db_import_check_ok'] = 'Die alte Installation kann importiert werden. Sie enthält die folgenden Daten:';
$LANG['db_import_col_table'] = 'Tabelle';
$LANG['db_import_col_old'] = 'Alt (5.3.7)';
$LANG['db_import_col_new'] = 'Importiert';
$LANG['db_import_danger'] = '<strong>Dies ersetzt alle Daten in dieser Installation</strong> (Benutzer, Gruppen, Abwesenheiten, Kalender, Einstellungen usw.) durch die Daten der alten Installation. Mach das nur in einer frischen Installation.';
$LANG['db_import_confirm_comment'] = 'Gib bitte "IMPORT" ein, um zu bestätigen.';
$LANG['btn_import_start'] = 'Import starten';
$LANG['db_import_running'] = 'Import läuft';
$LANG['db_import_running_hint'] = 'Bitte lass diese Seite geöffnet, bis der Import abgeschlossen ist. Große Kalender können einige Minuten dauern.';
$LANG['db_import_step_wipe'] = 'Bereite diese Installation vor ...';
$LANG['db_import_step_reference'] = 'Importiere Rollen, Regionen, Feiertage, Abwesenheitstypen, Gruppen, Muster und Berechtigungen ...';
$LANG['db_import_step_config'] = 'Importiere Einstellungen ...';
$LANG['db_import_step_messages'] = 'Importiere Ankündigungen ...';
$LANG['db_import_step_log'] = 'Importiere das Protokoll ...';
$LANG['db_import_step_users'] = 'Importiere Benutzer ...';
$LANG['db_import_step_userdata'] = 'Importiere Benutzeroptionen, Gruppenmitgliedschaften, Ansprüche und Anhänge ...';
$LANG['db_import_step_daynotes'] = 'Importiere Tagesnotizen ...';
$LANG['db_import_step_absence_days'] = 'Importiere Abwesenheiten ...';
$LANG['db_import_step_calendar_days'] = 'Importiere Kalendertage ...';
$LANG['db_import_step_verify'] = 'Überprüfe die importierten Daten ...';
$LANG['db_import_step_files'] = 'Kopiere Avatare und Anhänge ...';
$LANG['db_import_failed'] = 'Der Import ist fehlgeschlagen. Diese Installation wurde in den frischen Zustand zurückgesetzt, die alte Installation wurde nicht verändert.';
$LANG['db_import_done'] = 'Import abgeschlossen.';
$LANG['db_import_done_text'] = 'Benutzer, Gruppen, Abwesenheiten, Kalender und Einstellungen befinden sich jetzt in dieser Installation. Das Administrator-Passwort ist jetzt das aus deiner alten Installation. Einstellungen in der <code>.env</code>-Datei (LDAP, OIDC, APP_SECRET, APPLICATION_URL) gehören nicht zur Datenbank: Kopiere sie aus deiner alten <code>.env</code>-Datei, falls du sie verwendest.';
$LANG['db_import_notes_title'] = 'Datensätze, die nicht übernommen werden konnten:';
$LANG['btn_import_home'] = 'Zur Startseite';
$LANG['db_import_resume_text'] = 'Ein Import wurde gestartet, aber nicht beendet. Du kannst ihn fortsetzen oder abbrechen. Beim Abbrechen wird diese Installation in den frischen Zustand zurückgesetzt.';
$LANG['btn_import_resume'] = 'Import fortsetzen';
$LANG['btn_import_cancel'] = 'Import abbrechen';
$LANG['db_import_cancel_confirm'] = 'Den Import abbrechen und diese Installation in den frischen Zustand zurücksetzen?';
$LANG['db_import_cancelled'] = 'Der Import wurde abgebrochen. Diese Installation ist im frischen Zustand.';
$LANG['db_import_err_folder'] = 'Der Ordner wurde nicht gefunden, ist keine TeamCal Neo Installation, ist diese Installation selbst, oder der Server darf ihn nicht lesen.';
$LANG['db_import_err_no_db'] = 'Die Datenbankverbindung konnte nicht aus dem alten Ordner gelesen werden. Öffne "Datenbankverbindung der alten Installation" und gib Datenbankserver und -name ein.';
$LANG['db_import_err_connect'] = 'Verbindung zur alten Datenbank nicht möglich: %s';
$LANG['db_import_err_session'] = 'Die Import-Sitzung ist abgelaufen. Bitte prüfe die alte Installation erneut.';
$LANG['db_import_err_confirm'] = 'Bitte gib IMPORT im Bestätigungsfeld ein.';
$LANG['db_import_err_restore'] = 'Außerdem konnte diese Installation nicht automatisch zurückgesetzt werden (%s). Verwende den Reiter Datenbank zurücksetzen.';
$LANG['db_import_err_target_schema'] = 'Diese Installation hat nicht die Datenbankstruktur von TeamCal Neo 6. Verwende zuerst den Reiter Reparieren.';
$LANG['db_import_err_target_not_fresh'] = 'Diese Installation enthält bereits Daten. Der Import benötigt eine frische Installation. Verwende zuerst den Reiter Datenbank zurücksetzen mit dem Basis-Datensatz.';
$LANG['db_import_err_source_table'] = 'Tabelle %s wurde in der alten Datenbank nicht gefunden. Prüfe die Datenbankeinstellungen und das Tabellenpräfix.';
$LANG['db_import_err_source_not_537'] = 'Die angegebene Datenbank ist bereits eine TeamCal Neo 6 Datenbank. Gib die Datenbank der alten 5.3.7-Installation an.';
$LANG['db_import_err_source_version'] = 'Die alte Datenbank ist älter als 5.3.7. Aktualisiere die alte Installation zuerst auf 5.3.7 (siehe Upgrade-Informationen).';
$LANG['db_import_err_source_templates'] = 'Die alte Datenbank hat nicht die Tabellenstruktur von 5.3.7.';
$LANG['db_import_err_role_missing'] = '%d Benutzer in der alten Datenbank haben eine Rolle, die nicht existiert. Behebe das zuerst in der alten Installation.';
$LANG['db_import_err_username_case'] = 'Benutzernamen in Tabelle %s unterscheiden sich nur durch Groß-/Kleinschreibung oder Akzente (z.B. "Bob" und "bob"). Benutzernamen müssen in Version 6 eindeutig sein: Benenne in der alten Installation zuerst jeweils einen der beiden um.';
$LANG['db_import_err_role_duplicate'] = 'Die alte Datenbank enthält doppelte Rollennamen. Rollennamen müssen in Version 6 eindeutig sein.';
$LANG['db_import_err_attachment_duplicate'] = 'Die alte Datenbank enthält doppelte Dateinamen bei den Anhängen. Dateinamen müssen in Version 6 eindeutig sein.';
$LANG['db_import_err_group_type'] = 'Die alte Datenbank enthält andere Gruppenmitgliedschaftstypen als Mitglied, Manager und Gast.';
$LANG['db_import_warn_no_folder'] = 'Es wurde kein alter Ordner angegeben: Avatare und Anhänge werden nicht kopiert. Kopiere den Inhalt von public/upload/avatars und public/upload/files aus der alten Installation selbst in diese Installation.';
$LANG['db_import_note_skipped'] = '%d Datensatz/Datensätze aus "%s" wurden nicht importiert, weil sie auf nicht mehr vorhandene Daten verweisen.';
$LANG['db_import_note_business_day'] = '%d Kalendertag-Überschreibung(en) auf "Werktag" ist/sind jetzt der Standard und wird/werden nicht gespeichert.';
$LANG['db_import_config_summary'] = '%d Einstellungen wurden aus der alten Installation übernommen.';
$LANG['db_import_config_skipped'] = 'Diese Einstellungen gibt es in Version 6 nicht mehr und wurden nicht übernommen: %s';
$LANG['db_import_config_excluded'] = 'Diese Einstellungen gehören zur alten Installation und wurden nicht übernommen: %s';
$LANG['db_import_files_avatars'] = 'Avatare';
$LANG['db_import_files_uploads'] = 'Anhänge';
$LANG['db_import_files_summary'] = '%s: %d Datei(en) kopiert, %d bereits vorhanden.';
$LANG['db_import_files_missing'] = '%s: Der Ordner existiert in der alten Installation nicht.';
$LANG['db_import_files_unsafe'] = '%d Datei(en) mit einem Skript-Dateityp wurden nicht kopiert.';
$LANG['db_import_files_failed'] = 'Diese Dateien konnten nicht kopiert werden: %s';
$LANG['db_import_files_skipped'] = 'Es wurde kein alter Ordner angegeben, daher wurden keine Avatare oder Anhänge kopiert. Kopiere public/upload/avatars und public/upload/files selbst.';
