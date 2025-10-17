<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings German: Declination
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['decl_Enddate'] = 'Aktivierungs-Endedatum';
$LANG['decl_Enddate_comment'] = 'Wähle das Datum, an dem diese Ablehnungsregel automatisch enden soll. Die Regel ist dann bis einschließlich diesen Tages aktiv.';
$LANG['decl_Message'] = 'Ablehnungsnachricht';
$LANG['decl_Message_comment'] = 'Hier kann eine individuelle Nachricht eingegeben werden, die dem Nutzer bei Ablehnung einer Abwesenheit durch diese Regel angezeigt wird.
 Die konfigurierte Zeitperiode wird direkt dahinter angezeigt.';
$LANG['decl_Period'] = 'Anwendungszeitraum';
$LANG['decl_Period_comment'] = 'Wähle hier, von wann bis wann diese Regel aktiv sein soll. Wenn eine Option mit Start- oder Endedatum gewählt wird, müssen diese unten eingetragen werden.<br>
      <i>Die Regel muss grundsätzlich aktiviert sein, bevor diese Einstellung greift.</i>';
$LANG['decl_Period_nowEnddate'] = 'Von Aktivierung bis Endedatum';
$LANG['decl_Period_nowForever'] = 'Solange Regel aktiv';
$LANG['decl_Period_startdateEnddate'] = 'Von Startdatum bis Endedatum';
$LANG['decl_Period_startdateForever'] = 'Von Startdatum bis Deaktivierung';
$LANG['decl_Startdate'] = 'Aktivierungs-Startdatum';
$LANG['decl_Startdate_comment'] = 'Wähle das Datum, an dem diese Ablehnungsregel automatisch beginnen soll. Die Regel wird an diesem Tag aktiv.';
$LANG['decl_absence'] = 'Aktivieren';
$LANG['decl_absenceEnddate'] = $LANG['decl_Enddate'];
$LANG['decl_absenceEnddate_comment'] = $LANG['decl_Enddate_comment'];
$LANG['decl_absencePeriod'] = $LANG['decl_Period'];
$LANG['decl_absencePeriod_comment'] = $LANG['decl_Period_comment'];
$LANG['decl_absencePeriod_nowEnddate'] = $LANG['decl_Period_nowEnddate'];
$LANG['decl_absencePeriod_nowForever'] = $LANG['decl_Period_nowForever'];
$LANG['decl_absencePeriod_startdateEnddate'] = $LANG['decl_Period_startdateEnddate'];
$LANG['decl_absencePeriod_startdateForever'] = $LANG['decl_Period_startdateForever'];
$LANG['decl_absenceStartdate'] = $LANG['decl_Startdate'];
$LANG['decl_absenceStartdate_comment'] = $LANG['decl_Startdate_comment'];
$LANG['decl_absence_comment'] = 'Aktiviere diesen Schalter, wenn bei Erreichen einer Abwesenheitsgrenze abgelehnt werden soll.';
$LANG['decl_alert_period_missing'] = 'Bei Angabe einer Periode müssen beide Datumsfelder ausgefüllt werden.';
$LANG['decl_alert_period_wrong'] = 'Bei Angabe einer Periode muss das Startdatum vor dem Endedatum liegen.';
$LANG['decl_alert_save'] = 'Ablehnungseinstellungen speichern';
$LANG['decl_alert_save_failed'] = 'Die Einstellungen konnten nicht gespeichert werden. Es gab fehlerhafte Eingaben. Bitte prüfe die Fehlermeldungen.';
$LANG['decl_alert_save_success'] = 'Die neuen Ablehnungseinstellungen wurden gespeichert.';
$LANG['decl_applyto'] = 'Ablehnung anwenden bei';
$LANG['decl_applyto_all'] = 'Bei allen Nutzern (au&szlig;
$LANG['decl_applyto_comment'] = 'Hier kann eingestellt werden, ob Ablehnung nur bei normalen Nutzern geprüft wird oder auch bei Managern und Direktoren. Bei Administratoren wird Ablehnung nicht geprüt.';
$LANG['decl_applyto_regular'] = 'Nur bei normalen Nutzern';
$LANG['decl_base'] = 'Basis der Abwesenheitsrate';
$LANG['decl_base_all'] = 'Alle';
$LANG['decl_base_comment'] = 'Wähle hier, worauf sich die Abwesenheitsrate beziehen soll.';
$LANG['decl_base_group'] = 'Gruppe';
$LANG['decl_before'] = 'Aktivieren';
$LANG['decl_beforeEnddate'] = $LANG['decl_Enddate'];
$LANG['decl_beforeEnddate_comment'] = $LANG['decl_Enddate_comment'];
$LANG['decl_beforePeriod'] = $LANG['decl_Period'];
$LANG['decl_beforePeriod_comment'] = $LANG['decl_Period_comment'];
$LANG['decl_beforePeriod_nowEnddate'] = $LANG['decl_Period_nowEnddate'];
$LANG['decl_beforePeriod_nowForever'] = $LANG['decl_Period_nowForever'];
$LANG['decl_beforePeriod_startdateEnddate'] = $LANG['decl_Period_startdateEnddate'];
$LANG['decl_beforePeriod_startdateForever'] = $LANG['decl_Period_startdateForever'];
$LANG['decl_beforeStartdate'] = $LANG['decl_Startdate'];
$LANG['decl_beforeStartdate_comment'] = $LANG['decl_Startdate_comment'];
$LANG['decl_before_comment'] = 'Abwesenheitsanfragen können abgelehnt werden, wenn sie vor einem bestimmten Datum liegen. Hier kann diese Option aktiviert werden.';
$LANG['decl_beforedate'] = 'Grenzdatum';
$LANG['decl_beforedate_comment'] = 'Hier kann ein individuelles Grenzdatum eingegeben werden. Dies ist nur wirksam, wenn oben die Option "vor Datum" gewählt wurde.';
$LANG['decl_beforeoption'] = 'Grenzdatumoption';
$LANG['decl_beforeoption_comment'] = 'Bei Auswahl von "vor Heute werden Abwesenheitsanfragen in der Vergangenheit abgelehnt. Wenn ein bestimmtes Datum die Grenze
 sein soll, wähle hier "vor Datum" und gebe das Datum unten ein.';
$LANG['decl_beforeoption_date'] = 'vor Datum (nicht eingeschlossen)';
$LANG['decl_beforeoption_today'] = 'vor Heute (nicht eingeschlossen)';
$LANG['decl_label_active'] = 'Aktiv';
$LANG['decl_label_expired'] = 'Abgelaufen';
$LANG['decl_label_inactive'] = 'Inaktiv';
$LANG['decl_label_scheduled'] = 'Geplant';
$LANG['decl_period1'] = 'Aktivieren';
$LANG['decl_period1Enddate'] = $LANG['decl_Enddate'];
$LANG['decl_period1Enddate_comment'] = $LANG['decl_Enddate_comment'];
$LANG['decl_period1Message'] = $LANG['decl_Message'];
$LANG['decl_period1Message_comment'] = $LANG['decl_Message_comment'];
$LANG['decl_period1Period'] = $LANG['decl_Period'];
$LANG['decl_period1Period_comment'] = $LANG['decl_Period_comment'];
$LANG['decl_period1Period_nowEnddate'] = $LANG['decl_Period_nowEnddate'];
$LANG['decl_period1Period_nowForever'] = $LANG['decl_Period_nowForever'];
$LANG['decl_period1Period_startdateEnddate'] = $LANG['decl_Period_startdateEnddate'];
$LANG['decl_period1Period_startdateForever'] = $LANG['decl_Period_startdateForever'];
$LANG['decl_period1Startdate'] = $LANG['decl_Startdate'];
$LANG['decl_period1Startdate_comment'] = $LANG['decl_Startdate_comment'];
$LANG['decl_period1_comment'] = 'Hier kann eine Periode definiert werden, innerhalb derer Abwesenheitsanfragen abgelehnt werden. Start und Ende Datum sind in diesem Fall mit eingeschlossen.
      Die Option kann hier aktiviert werden.';
$LANG['decl_period1end'] = 'Enddatum (einschlie&szlig;
$LANG['decl_period1end_comment'] = 'Gebe hier das Enddatum der Periode ein.';
$LANG['decl_period1start'] = 'Startdatum (einschlie&szlig;
$LANG['decl_period1start_comment'] = 'Gebe hier das Startdatum der Periode ein.';
$LANG['decl_period2'] = 'Aktivieren';
$LANG['decl_period2Enddate'] = $LANG['decl_Enddate'];
$LANG['decl_period2Enddate_comment'] = $LANG['decl_Enddate_comment'];
$LANG['decl_period2Message'] = $LANG['decl_Message'];
$LANG['decl_period2Message_comment'] = $LANG['decl_Message_comment'];
$LANG['decl_period2Period'] = $LANG['decl_Period'];
$LANG['decl_period2Period_comment'] = $LANG['decl_Period_comment'];
$LANG['decl_period2Period_nowEnddate'] = $LANG['decl_Period_nowEnddate'];
$LANG['decl_period2Period_nowForever'] = $LANG['decl_Period_nowForever'];
$LANG['decl_period2Period_startdateEnddate'] = $LANG['decl_Period_startdateEnddate'];
$LANG['decl_period2Period_startdateForever'] = $LANG['decl_Period_startdateForever'];
$LANG['decl_period2Startdate'] = $LANG['decl_Startdate'];
$LANG['decl_period2Startdate_comment'] = $LANG['decl_Startdate_comment'];
$LANG['decl_period2_comment'] = 'Hier kann eine weitere Periode definiert werden, innerhalb derer Abwesenheitsanfragen abgelehnt werden. Start und Ende Datum sind in diesem Fall mit eingeschlossen.
      Die Option kann hier aktiviert werden.';
$LANG['decl_period2end'] = 'Enddatum (einschlie&szlig;
$LANG['decl_period2end_comment'] = 'Gebe hier das Enddatum der Periode ein.';
$LANG['decl_period2start'] = 'Startdatum (einschlie&szlig;
$LANG['decl_period2start_comment'] = 'Gebe hier das Startdatum der Periode ein.';
$LANG['decl_period3'] = 'Aktivieren';
$LANG['decl_period3Enddate'] = $LANG['decl_Enddate'];
$LANG['decl_period3Enddate_comment'] = $LANG['decl_Enddate_comment'];
$LANG['decl_period3Message'] = $LANG['decl_Message'];
$LANG['decl_period3Message_comment'] = $LANG['decl_Message_comment'];
$LANG['decl_period3Period'] = $LANG['decl_Period'];
$LANG['decl_period3Period_comment'] = $LANG['decl_Period_comment'];
$LANG['decl_period3Period_nowEnddate'] = $LANG['decl_Period_nowEnddate'];
$LANG['decl_period3Period_nowForever'] = $LANG['decl_Period_nowForever'];
$LANG['decl_period3Period_startdateEnddate'] = $LANG['decl_Period_startdateEnddate'];
$LANG['decl_period3Period_startdateForever'] = $LANG['decl_Period_startdateForever'];
$LANG['decl_period3Startdate'] = $LANG['decl_Startdate'];
$LANG['decl_period3Startdate_comment'] = $LANG['decl_Startdate_comment'];
$LANG['decl_period3_comment'] = 'Hier kann eine weitere Periode definiert werden, innerhalb derer Abwesenheitsanfragen abgelehnt werden. Start und Ende Datum sind in diesem Fall mit eingeschlossen.
      Die Option kann hier aktiviert werden.';
$LANG['decl_period3end'] = 'Enddatum (einschlie&szlig;
$LANG['decl_period3end_comment'] = 'Gebe hier das Enddatum der Periode ein.';
$LANG['decl_period3start'] = 'Startdatum (einschlie&szlig;
$LANG['decl_period3start_comment'] = 'Gebe hier das Startdatum der Periode ein.';
$LANG['decl_roles'] = 'Anwenden bei Rollen';
$LANG['decl_roles_comment'] = 'Wähle hier die Rollen aus, bei denen die Ablehnungsregeln angewendet werden sollen. Dies wirkt auch auf die "minimal anwesend" und "maximal abwesend" Einstellungen der einzelnen Gruppen.';
$LANG['decl_schedule'] = 'Zeitplan';
$LANG['decl_schedule_nowEnddate'] = 'Von Aktivierung bis %s';
$LANG['decl_schedule_nowForever'] = 'Solange aktiviert';
$LANG['decl_schedule_startdateEnddate'] = 'Von %s bis %s';
$LANG['decl_schedule_startdateForever'] = 'Von %s bis Deaktivierung';
$LANG['decl_summary_absence'] = 'Ablehnung von Abwesenheitsanfragen, wenn eine Abwesenheitsgrenze erreicht ist.';
$LANG['decl_summary_before'] = 'Ablehnung von Abwesenheitsanfragen vor einem bestimmten Datum.';
$LANG['decl_summary_period1'] = 'Ablehnung von Abwesenheitsanfragen in einer bestimmten Periode.';
$LANG['decl_summary_period2'] = 'Ablehnung von Abwesenheitsanfragen in einer bestimmten Periode.';
$LANG['decl_summary_period3'] = 'Ablehnung von Abwesenheitsanfragen in einer bestimmten Periode.';
$LANG['decl_tab_absence'] = 'Abwesenheitsgrenze';
$LANG['decl_tab_before'] = 'Datumsgrenze';
$LANG['decl_tab_overview'] = 'übersicht';
$LANG['decl_tab_period1'] = 'Zeitraum 1';
$LANG['decl_tab_period2'] = 'Zeitraum 2';
$LANG['decl_tab_period3'] = 'Zeitraum 3';
$LANG['decl_tab_scope'] = 'Geltungsbereich';
$LANG['decl_threshold'] = 'Abwesenheitsrate (%)';
$LANG['decl_threshold_comment'] = 'Hier kann eine Abwesenheitsrate in Prozent angegeben werden, die nicht unterschritten werden darf.';
$LANG['decl_title'] = 'Ablehnungsmanagement';
$LANG['decl_value'] = 'Wert';
