<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings German: Database page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['db_alert_delete'] = 'Datensätze Löschen';
$LANG['db_alert_delete_success'] = 'Die ausgewählten Löschungen wurden durchgeführt.';
$LANG['db_alert_failed'] = 'Die Operation konnte nicht durchgeführt werden. Bitte überprüfe deine Eingaben.';
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
$LANG['db_clean_daynotes'] = 'Tagesnotizen aufräumen...';
$LANG['db_clean_holidays'] = 'Feiertage aufräumen...';
$LANG['db_clean_months'] = 'Regionskalender aufräumen...';
$LANG['db_clean_templates'] = 'Benutzerkalender aufräumen...';
$LANG['db_clean_what'] = 'Was soll aufgeräumt werden';
$LANG['db_clean_what_comment'] = 'Wähle hier, was aufgeräumt werden soll. Alle Datensätze, die gleich alt oder älter sind als das "Bevor-Datum" werden gelöscht.
 Regions- und Benutzerkalender werden nach Monat gelöscht, unabhängig vom Tag. Neuere Datensätze bleiben erhalten.';
$LANG['db_confirm'] = 'Bestätigung';
$LANG['db_dbURL'] = 'Datenbank URL';
$LANG['db_dbURL_comment'] = 'Hier kann ein direkter Link zur bevorzugten Datenbank Applikation angegeben werden. Der Button unten verlinkt dorthin.';
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
$LANG['db_repair_confirm'] = 'Bestätigung';
$LANG['db_repair_confirm_comment'] = 'Bitte gebe hier "REPAIR" ein, um die Aktion zu bestätigen.';
$LANG['db_repair_daynoteRegions'] = 'Tagesnotiz-Regionen';
$LANG['db_repair_daynoteRegions_comment'] = 'Diese Option prueft, ob es Tagesnotizen ohne Regionszurodnung gibt. Wenn dies der Fall ist, wird die Default Region eingetragen.';
$LANG['db_resetString'] = 'Bestätigung';
$LANG['db_resetString_comment'] = 'Das Zurücksetzen der Datenbank wird alle Daten mit den Beispieldaten der Applikation ersetzen.<br>
      Gib den folgenden Text zur Bestätigung ein: "YesIAmSure".';
$LANG['db_reset_danger'] = '<strong>Achtung!</strong> Alle aktuellen Daten werden durch das Zurücksetzen gelöscht!!';
$LANG['db_tab_admin'] = 'Verwaltung';
$LANG['db_tab_cleanup'] = 'Aufräumen';
$LANG['db_tab_dbinfo'] = 'Datenbank Information';
$LANG['db_tab_delete'] = 'Datensätze löschen';
$LANG['db_tab_optimize'] = 'Tabellen optimieren';
$LANG['db_tab_repair'] = 'Reparieren';
$LANG['db_tab_tcpimp'] = 'TeamCal Pro Import';
$LANG['db_title'] = 'Datenbank Verwaltung';
