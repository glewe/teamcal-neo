<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings German: Calendar Options
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['calopt_title'] = 'Kalenderoptionen';

$LANG['calopt_tab_display'] = 'Anzeige';
$LANG['calopt_tab_filter'] = 'Filter';
$LANG['calopt_tab_options'] = 'Optionen';
$LANG['calopt_tab_remainder'] = 'Resttage';
$LANG['calopt_tab_stats'] = 'Statistik';
$LANG['calopt_tab_summary'] = 'Summen';

$LANG['calopt_alert_edit_success'] = 'Die Kalenderoptionen wurden gespeichert.';
$LANG['calopt_alert_failed'] = 'Die Kalenderoptionen konnten nicht gespeichert werden. Bitte überprüfe die Eingaben.';
$LANG['calopt_calendarFontSize'] = 'Kalender Schriftgrö&szlig;e';
$LANG['calopt_calendarFontSize_comment'] = 'Hier kann die Schriftgrö&szlig;e des Monatskalenders mit einem Prozentwert vergrö&szlig;ert oder verkleinert werden, z.B. 80 oder 120.';
$LANG['calopt_currentYearOnly'] = 'Nur aktuelles Jahr';
$LANG['calopt_currentYearOnly_comment'] = 'Mit diesem Schalter wird der Kalender auf das aktuelle Jahr beschränkt. Andere Jahre können nicht angezeigt oder bearbeitet werden.';
$LANG['calopt_currentYearRoles'] = 'Nur aktuelles Jahr Rollen';
$LANG['calopt_currentYearRoles_comment'] = 'Wenn "Nur aktuelles Jahr" ausgewählt ist, kann diese Einschränkung hier bestimmten Rollen zugeordnet werden.';
$LANG['calopt_defgroupfilter'] = 'Default Gruppenfilter';
$LANG['calopt_defgroupfilter_comment'] = 'Auswahl des Default Gruppenfilters für die Kalenderanzeige. Jeder User kann diese Einstellung individuell in seinem Profil ändern.';
$LANG['calopt_defgroupfilter_all'] = 'Alle';
$LANG['calopt_defgroupfilter_allbygroup'] = 'Alle (nach Gruppen)';
$LANG['calopt_defregion'] = 'Default Region für Basiskalendar';
$LANG['calopt_defregion_comment'] = 'Auswahl der Default Region für den Basiskalender. Jeder User kann diese Einstellung individuell in seinem Profil ändern.';
$LANG['calopt_firstDayOfWeek'] = 'Erster Wochentag';
$LANG['calopt_firstDayOfWeek_comment'] = 'Dieser kann auf Montag oder Sonntag gesetzt werden. Die Auswahl wirkt sich auf die Anzeige der Wochennummern aus.';
$LANG['calopt_firstDayOfWeek_1'] = 'Montag';
$LANG['calopt_firstDayOfWeek_7'] = 'Sonntag';
$LANG['calopt_hideDaynotes'] = 'Persönliche Tagesnotizen verbergen';
$LANG['calopt_hideDaynotes_comment'] = 'Mit diesem Schalter können die persönlichen Tagesnotizen vor normalen Nutzern verborgen werden. Nur Manager, Direktoren
      und Administratoren können sie editieren und sehen. So können sie für Managementzwecke genutzt werden. Dieser Schalter beeinflusst nicht die Geburtstagsnotizen.';
$LANG['calopt_hideManagers'] = 'Manager in Alle-nach-Gruppen und Gruppen Anzeige verbergen';
$LANG['calopt_hideManagers_comment'] = 'Mit dieser Option werden alle Manager in der Alle-nach-Gruppen und Gruppen Anzeige verborgen mit Ausnahme der Gruppen, in der sie nur Mitglied sind.';
$LANG['calopt_hideManagerOnlyAbsences'] = 'Management Abwesenheiten verbergen';
$LANG['calopt_hideManagerOnlyAbsences_comment'] = 'Abwesenheitstypen können als "Nur Management" markiert werden, so dass nur Manager und Direktoren sie editieren können.
      Diese Abwesenheiten werden normalen Benutzern angezeigt, sie können sie aber nicht editieren. Mit diesem Schalter können sie die Anzeige für normale Benutzer verbergen.';
$LANG['calopt_includeSummary'] = 'Summen Abschnitt';
$LANG['calopt_includeSummary_comment'] = 'Mit dieser Option wird eine aufklappbare Zusammenfassung unter jedem Monat angezeigt, die die Summen der Abwesenheiten aufführt.';
$LANG['calopt_managerOnlyIncludesAdministrator'] = 'Nur-Manager für Administrator';
$LANG['calopt_managerOnlyIncludesAdministrator_comment'] = 'Nur-Manager Abwesenheitstypen können nur von den Gruppenmanagern gesetzt werden. Mit diesem Schalter können das auch Nutzer mit der Rolle "Administrator" tun.';
$LANG['calopt_monitorAbsence'] = 'Monitor Abwesenheit';
$LANG['calopt_monitorAbsence_comment'] = 'Wählen Sie hier eine oder mehrere Abwesenheitsarten aus. Für jede wird der Verbleibend/Erlaubt-Zähler unter dem Benutzernamen im Kalender angezeigt.';
$LANG['calopt_notificationsAllGroups'] = 'Benachrichtigungen für alle Gruppen';
$LANG['calopt_notificationsAllGroups_comment'] = 'Standardmä&szlig;ig können Nutzer E-mail Benachrichtigungne von Kalenderereignissen nur von eigenen Gruppen abbonieren. Mit dieser Option können sie auch andere Gruppen wählen.<br>
      <i>Hinweis: Wenn diese Option ausgeschaltet wird und Benutzer während ihrer Aktivierung andere Gruppen für Benachrichtigungen ausgewählt hatten, werden diese erst entfernt, wenn deren Profil erneut gespeichert wird.</i>';
$LANG['calopt_pastDayColor'] = 'Vergangenheitsfarbe';
$LANG['calopt_pastDayColor_comment'] = 'Setzt im Monatskalender diese Hintergrundfarbe für die Tage, die in der Vergangenheit liegen.
      Lasse dieses Feld leer, wenn keine Hintergrundfarbe für vergangene Tage benutzt werden soll.';
$LANG['calopt_regionalHolidays'] = 'Regionale Feiertage markieren';
$LANG['calopt_regionalHolidays_comment'] = 'Mit dieser Option werden Feiertage in anderen Regionen als die aktuell angezeigte mit einem farbigen Rahmen gekennzeichnet.';
$LANG['calopt_regionalHolidaysColor'] = 'Rahmenfarbe Regionaler Feiertage';
$LANG['calopt_regionalHolidaysColor_comment'] = 'Setzt die Rahmenfarbe f&uum;r regionale Feiertage.';
$LANG['calopt_repeatHeaderCount'] = 'Kopfzeilen Wiederholungs Zähler';
$LANG['calopt_repeatHeaderCount_comment'] = 'Gibt die Anzahl von Zeilen an, nach der die Monatskopfzeile für bessere Lesbarkeit wiederholt wird. Wenn der Wert auf 0 gesetzt ist, wird die Kopfzeile nicht wiederholt.';
$LANG['calopt_satBusi'] = 'Samstag ist ein Arbeitstag';
$LANG['calopt_satBusi_comment'] = 'Normalerweise sind Samstage und Sonntage Wochenendtage und werden entsprechend im Kalender als solche angezeigt. Hier kann Samstag als Arbeitstag definiert werden.';
$LANG['calopt_showAvatars'] = 'Avatars anzeigen';
$LANG['calopt_showAvatars_comment'] = 'Mit dieser Option wird ein User Avatar in einem Pop-Up angezeigt, wenn die Maus über das User Icon geführt wird.';
$LANG['calopt_showMonths'] = 'Mehrere Monate anzeigen';
$LANG['calopt_showMonths_comment'] = 'Gebe hier an, wieviele Monate auf der Kalenderseite angezeigt werden sollen, maximal 12.<br><i>Hinweis: Ein User kann diesen Wert in seinen Einstellungen auch ändern, der gegenüber dem Standardwert Priorität hat.</i>';
$LANG['calopt_showRegionButton'] = 'Regionsfilter anzeigen';
$LANG['calopt_showRegionButton_comment'] = 'Mit dieser Option wird oberhalb des Kalenders ein Button angezeigt, mit dem eine andere Region gewählt werden kann.
      Wenn nur die Standard Region benutzt wird, kann es Sinn machen, diesen auszublenden.';
$LANG['calopt_showRoleIcons'] = 'Rollen Icons anzeigen';
$LANG['calopt_showRoleIcons_comment'] = 'Mit dieser Option wird neben dem Benutzernamen ein Icon angezeigt, das die User Rolle anzeigt.';
$LANG['calopt_showSummary'] = 'Summen Abschnitt anzeigen';
$LANG['calopt_showSummary_comment'] = 'Mit dieser Option wird der Summen Abschnitt standardmä&szlig;ig aufgeklappt.';
$LANG['calopt_showTooltipCount'] = 'Tooltip Zähler';
$LANG['calopt_showTooltipCount_comment'] = 'Wenn diese Option aktiviert ist, wird die Anzahl der genommenen Abwesenheiten als "(genommen aktueller Monat/genommen aktuelles Jahr)" im Tooltip des Abwesenheitstyps angezeigt, wenn man mit der Maus im Kalender darüber fährt.';
$LANG['calopt_showUserRegion'] = 'Regionale Feiertage pro User anzeigen';
$LANG['calopt_showUserRegion_comment'] = 'Mit dieser Option zeigt der Kalender in jeder Nutzerzeile die regionalen Feiertage der Region an, die in den Optionen des
      Nutzers eingestellt ist. Diese Feiertage können sich von den globalen regionalen Feiertagen unterscheiden, die im Kopf des Kalenders angezeigt werden.
      Diese Option bietet eine bessere Sicht auf die unterschiedlichen regionalen Feiertage unterschiedlicher Nutzer. Die Anzeige mag dabei aber auch unübersichtlicher werden, je nach Anzahl Nutzer und Regionen. Probier es aus.';
$LANG['calopt_showWeekNumbers'] = 'Wochennummern anzeigen';
$LANG['calopt_showWeekNumbers_comment'] = 'Mit dieser Option wird im Kalender eine Zeile mit den Nummern der Kalenderwochen hinzugefügt.';
$LANG['calopt_sortByOrderKey'] = 'Sortierschlüssel benutzen';
$LANG['calopt_sortByOrderKey_comment'] = 'Mit dieser Option werden die Benutzer im Kalender nicht nach Nachname sondern nach deren Sortierschlüssel sortiert. Der Sortierschlüssel ist ein optionales Feld im Benutzerprofil.';
$LANG['calopt_statsDefaultColorAbsences'] = 'Standardfarbe der Abwesenheitsstatistik';
$LANG['calopt_statsDefaultColorAbsences_comment'] = 'Wähle die Standardfarbe für diese Statistik aus.';
$LANG['calopt_statsDefaultColorAbsencetype'] = 'Standardfarbe der Abwesenheitstypstatistik';
$LANG['calopt_statsDefaultColorAbsencetype_comment'] = 'Wähle die Standardfarbe für diese Statistik aus.';
$LANG['calopt_statsDefaultColorPresences'] = 'Standardfarbe der Anwesenheitsstatistik';
$LANG['calopt_statsDefaultColorPresences_comment'] = 'Wähle die Standardfarbe für diese Statistik aus.';
$LANG['calopt_statsDefaultColorPresencetype'] = 'Standardfarbe der Anwesenheitstypstatistik';
$LANG['calopt_statsDefaultColorPresencetype_comment'] = 'Wähle die Standardfarbe für diese Statistik aus.';
$LANG['calopt_statsDefaultColorRemainder'] = 'Standardfarbe der Resttagestatistik';
$LANG['calopt_statsDefaultColorRemainder_comment'] = 'Wähle die Standardfarbe für diese Statistik aus.';
$LANG['calopt_summaryAbsenceTextColor'] = 'Textfarbe der Abwesenheitszahlen';
$LANG['calopt_summaryAbsenceTextColor_comment'] = 'Hier kannst du eine feste Textfarbe f&uum;r die Abwesenheitszahlen in der Summenzeile auswählen. Lass das Feld leer, um die Standardfarbe zu benutzen.';
$LANG['calopt_summaryPresenceTextColor'] = 'Textfarbe der Anwesenheitszahlen';
$LANG['calopt_summaryPresenceTextColor_comment'] = 'Hier kannst du eine feste Textfarbe für die Anwesenheitszahlen in der Summenzeile auswählen. Lass das Feld leer, um die Standardfarbe zu benutzen.';
$LANG['calopt_sunBusi'] = 'Sonntag ist ein Arbeitstag';
$LANG['calopt_sunBusi_comment'] = 'Normalerweise sind Samstage und Sonntage Wochenendtage und werden entsprechend im Kalender als solche angezeigt.
 Hier kann Sonntag als Arbeitstag definiert werden.';
$LANG['calopt_supportMobile'] = 'Unterstützung von Mobilen Geräten';
$LANG['calopt_supportMobile_comment'] = 'Mit dieser Einstellung werden die Kalendertabellen (Ansicht und Bearbeitung) für eine bestimmte Bildschirmbreite erstellt, so dass kein horizontales Scrollen notwendig ist.
 Der Benutzer kann seine Bildschirmgrö&szlig;en wählen.<br>Schalte diese Option aus, wenn der Kalender nur auf gro&szlig;en Bildschirmen genutzt wird (grö&szlig;er 1024 Pixel breit). Der Kalender wird dann immer noch auf kleineren Bildschirmen
 angezeigt, aber horizontales Scrollen ist dann notwendig.';
$LANG['calopt_symbolAsIcon'] = 'Abwesenheitszeichen ID als Icon';
$LANG['calopt_symbolAsIcon_comment'] = 'Mit dieser Option wird die Abwesenheitszeichen ID in der Kalenderanzeige benutzt anstatt des Icons.';
$LANG['calopt_takeover'] = 'Abwesenheitsübernahme aktivieren';
$LANG['calopt_takeover_comment'] = 'Mit dieser Option kann der eingeloggte Nutzer Abwesenheiten von anderen Nutzern übernehmen, wenn er den entsprechenden Kalender edtitieren kann. Abwesenheitsübernahmen unterliegen KEINER
      Regelprüfung. Sie werden vom anderen Nutzer entfernt und beim eingeloggten Nutzer eingetragen.';
$LANG['calopt_todayBorderColor'] = 'Heute Randfarbe';
$LANG['calopt_todayBorderColor_comment'] = 'Gibt die Farbe in Hexadezimal an, in der der rechte und linke Rand der Heute Spalte erscheint.';
$LANG['calopt_todayBorderSize'] = 'Heute Randstärke';
$LANG['calopt_todayBorderSize_comment'] = 'Gibt die Dicke in Pixel an, in der der rechte und linke Rand der Heute Spalte erscheint.';
$LANG['calopt_trustedRoles'] = 'Vertrauenswürdige Rollen';
$LANG['calopt_trustedRoles_comment'] = 'Wähle die Rollen, die als "vertraulich" gekennzeichnete Abwesenheiten und Tagesnotizen sehen dürfen.<br>
      <i>Hinweis: Die Rolle "Administrator" kann hier zwar ausgeschlossen werden, der Benutzer "admin" jedoch gilt als Superuser und kann immer alle Daten sehen.</i>';
$LANG['calopt_usersPerPage'] = 'Anzahl User pro Seite';
$LANG['calopt_usersPerPage_comment'] = 'Wenn du eine gro&szlig;e Anzahl an Usern in TeamCal Neo pflegst, bietet es sich an, die Kalenderanzeige in Seiten aufzuteilen.
      Gebe hier an, wieviel User pro Seite angezeigt werden sollen. Ein Wert von 0 zeigt alle User auf einer Seite an. Wenn du eine Seitenaufteilung wählst,
      werden am Ende der Seite Schaltflächen fuer das Blättern angezeigt.';
