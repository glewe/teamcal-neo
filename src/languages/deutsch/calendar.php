<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings German: Calendar
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */

//
// Cal
//
$LANG['cal_age'] = 'Alter';
$LANG['cal_birthday'] = 'Geburtstag';
$LANG['cal_businessDays'] = 'Arbeitstage im Monat';
$LANG['cal_caption_name'] = 'Name';
$LANG['cal_caption_weeknumber'] = 'Kalenderwoche';
$LANG['cal_img_alt_edit_cal'] = 'Kalendar für diese Person editieren...';
$LANG['cal_img_alt_edit_month'] = 'Feiertage für diesen Monat editieren...';
$LANG['cal_search'] = 'Nutzer suchen';
$LANG['cal_selAbsence'] = 'Abwesenheit auswählen';
$LANG['cal_selAbsence_comment'] = 'Zeigt alle Einträge an, die am heutigen Tage diese Abwesenheit eingetragen haben.';
$LANG['cal_selGroup'] = 'Gruppe auswählen';
$LANG['cal_selMonth'] = 'Monat auswählen';
$LANG['cal_selRegion'] = 'Region auswählen';
$LANG['cal_selWidth'] = 'Bildschirmbreite auswählen';
$LANG['cal_selWidth_comment'] = 'Wähle deine Bildschirmbreite in Pixel so dass die Kalendertabelle sich daran anpasst. Sollte deine Breite nicht in der Liste sein, wähle die nächst höhere.
      <br>Im Moment scheinst du eine Breite von <span id="currentwidth"></span> Pixeln zu benutzen. Lade die Seite neu und öffne diesen Dialog erneut, um sicher zu gehen.';
$LANG['cal_switchFullmonthView'] = "Wechsel zu Vollmonatsansicht";
$LANG['cal_switchSplitmonthView'] = 'Wechsel zu Split-Monatsansicht';
$LANG['cal_summary'] = 'Summen';
$LANG['cal_title'] = 'Kalender %s-%s (Region: %s)';
$LANG['cal_tt_absent'] = 'Abwesend';
$LANG['cal_tt_anotherabsence'] = 'Eine andere Abwesenheit';
$LANG['cal_tt_backward'] = 'Einen Monat zurück...';
$LANG['cal_tt_clicktoedit'] = 'Klicke zum Bearbeiten...';
$LANG['cal_tt_forward'] = 'Einen Monat vorwärts...';
$LANG['cal_tt_oneless'] = 'Einen Monat weniger anzeigen...';
$LANG['cal_tt_onemore'] = 'Einen Monat mehr anzeigen...';

//
// Caledit
//
$LANG['caledit_absencePattern'] = 'Abwesenheitsmuster';
$LANG['caledit_absencePatternSkipHolidays'] = 'Feiertage überspringen';
$LANG['caledit_absencePatternSkipHolidays_comment'] = 'Beim setzen der Abwesenheiten überspringe Feiertage, die nicht als Werktag zählen.';
$LANG['caledit_absencePattern_comment'] = 'Wähle das Abwesenheitsmuster aus, das für diesen Monat angewendet werden soll.';
$LANG['caledit_absenceType'] = 'Abwesenheitstyp';
$LANG['caledit_absenceType_comment'] = 'Wähle den Abwesenheitstyp für diese Eingabe aus.';
$LANG['caledit_alert_out_of_range'] = 'Die Datumsangaben war zumindest teilweise außerhalb des angezeigten Monats. Es wurden keine Änderungen gespeichert.';
$LANG['caledit_alert_save_failed'] = 'Die Abwesenheitsinformationen konnten nicht gespeichert werden. Es gab fehlerhafte Eingaben. Bitte prüfe die letzte Eingabe.';
$LANG['caledit_alert_update'] = 'Monat aktualisieren';
$LANG['caledit_alert_update_all'] = 'Alle Abwesenheiten wurden akzeptiert und der Monat entsprechend aktualisiert.';
$LANG['caledit_alert_update_group'] = 'Die Gruppenabwesenheiten wurden bei allen Benutzern der Gruppe eingetragen.';
$LANG['caledit_alert_update_none'] = 'Keine der Abwesenheiten wurde akzeptiert und der Monat nicht aktualisiert. Die abgelehnten Abwesenheiten wurden an einen Manager zur Bestätigung geschickt.';
$LANG['caledit_alert_update_partial'] = 'Einige Abwesenheiten konnten nicht akzeptiert werden, weil sie vom Management konfigurierte Regeln verletzen. Die folgenden Abwesenheiten wurden abgelehnt:';
$LANG['caledit_clearAbsence'] = 'Löschen';
$LANG['caledit_clearAbsences'] = 'Abwesenheiten löschen';
$LANG['caledit_clearDaynotes'] = 'Tagesnotizen löschen';
$LANG['caledit_confirm_clearall'] = 'Bist du sicher, dass du alle Abwesenheiten für diesen Monat löschen willst?<br><br><strong>Jahr:</strong> %s<br><strong>Monat:</strong> %s<br><strong>Nutzer:</strong> %s';
$LANG['caledit_confirm_savegroup'] = '<p><strong class="text-danger">Achtung!</strong><br>Das Speichern von Gruppenabwesenheiten erfolgt ohne Ablehnungsprüfung.<br>
      Die Abwesenheiten werden für alle Benutzer der ausgewählten Gruppe eingetragen. Du kannst aber unten auswählen, ob bereits existierende individuelle Abwesenheiten erhalten bleiben sollen.</p>
      <p><strong>Jahr:</strong> %s<br><strong>Monat:</strong> %s<br><strong>Gruppe:</strong> %s</p>';
$LANG['caledit_currentAbsence'] = 'Aktuell';
$LANG['caledit_endDate'] = 'Ende Datum';
$LANG['caledit_endDate_comment'] = 'Wähle das Enddatum aus (muss in diesem Monat sein).';
$LANG['caledit_keepExisting'] = 'Individuelle Abwesenheiten beibehalten';
$LANG['caledit_Pattern'] = 'Muster';
$LANG['caledit_PatternTitle'] = 'Abwesenheitsmuster auswählen';
$LANG['caledit_Period'] = 'Zeitraum';
$LANG['caledit_PeriodTitle'] = 'Abwesenheitszeitraum auswählen';
$LANG['caledit_recurrence'] = 'Wiederholung';
$LANG['caledit_recurrence_comment'] = 'Wähle die Wiederholung aus';
$LANG['caledit_Recurring'] = 'Wiederholung';
$LANG['caledit_RecurringTitle'] = 'Abwesenheitswiederholung auswählen';
$LANG['caledit_selGroup'] = 'Gruppe auswählen';
$LANG['caledit_selRegion'] = 'Region auswählen';
$LANG['caledit_selUser'] = 'Benutzer auswählen';
$LANG['caledit_startDate'] = 'Start Datum';
$LANG['caledit_startDate_comment'] = 'Wähle das Startdatum aus (muss in diesem Monat sein).';
$LANG['caledit_title'] = 'Bearbeitung von Monat %s-%s für %s';

//
// Col
//
$LANG['col_remainder'] = 'Resttage ausblenden...';
$LANG['col_summary'] = 'Zusammenfassung ausblenden...';

//
// Exp
//
$LANG['exp_remainder'] = 'Resttage einblenden...';
$LANG['exp_summary'] = 'Zusammenfassung einblenden...';

//
// Sum
//
$LANG['sum_absence_summary'] = 'Abwesenheiten im einzelnen';
$LANG['sum_absent'] = 'Abwesend';
$LANG['sum_business_day_count'] = 'Arbeitstage';
$LANG['sum_delta'] = 'Delta';
$LANG['sum_present'] = 'Anwesend';
