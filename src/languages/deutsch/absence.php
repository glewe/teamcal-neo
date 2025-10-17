<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings German: Absence
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */

//
// Abs
//
$LANG['abs_alert_created'] = 'Der Abwesenheitstyp wurde angelegt.';
$LANG['abs_alert_created_fail'] = 'Der Abwesenheitstyp wurde nicht angelegt. Bitte überprüfe den "Abwesenheitstyp anlegen" Dialog nach Eingabefehlern.';
$LANG['abs_alert_deleted'] = 'Der Abwesenheitstyp wurde gelöscht.';
$LANG['abs_alert_edit'] = 'Abwesenheitstyp aktualisieren';
$LANG['abs_alert_edit_success'] = 'Die Informationen für diesen Abwesenheitstyp wurden aktualisiert.';
$LANG['abs_alert_save_failed'] = 'Die neuen Informationen für diesen Abwesenheitstyp konnten nicht gespeichert. Es gab fehlerhafte Eingaben. Bitte prüfe die Fehlermeldungen.';
$LANG['abs_allow_active'] = 'Eingeschränkte Anzahl';
$LANG['abs_allowance'] = 'Erlaubte Anzahl pro Jahr';
$LANG['abs_allowance_comment'] = 'Hier kann die erlaubte Anzahl pro Kalenderjahr für diesen Typen gesetzt werden. Im Nutzerprofil
 wird die genommene und noch verbleibende Anzahl angezeigt (Ein negativer Wert in der Anzeige bedeutet, dass der Nutzer die erlaubte Anzahl
 überschritten hat.). Wenn der Wert auf 0 gesetzt wird, gilt eine unbegrenzte Erlaubnis.';
$LANG['abs_allowmonth'] = 'Erlaubte Anzahl pro Monat';
$LANG['abs_allowmonth_comment'] = 'Hier kann die erlaubte Anzahl pro Monat für diesen Typen gesetzt werden. Wenn der Wert auf 0 gesetzt wird, gilt eine unbegrenzte Erlaubnis.';
$LANG['abs_allowweek'] = 'Erlaubte Anzahl pro Woche';
$LANG['abs_allowweek_comment'] = 'Hier kann die erlaubte Anzahl pro Woche für diesen Typen gesetzt werden. Wenn der Wert auf 0 gesetzt wird, gilt eine unbegrenzte Erlaubnis.';
$LANG['abs_approval_required'] = 'Genehmigung erforderlich';
$LANG['abs_approval_required_comment'] = 'Dieser Schalter macht den Typen genehmigungspflichtig durch einen Manager, Direktor oder Administrator. Ein normaler Nutzer wird dann eine
 Fehlermeldung erhalten, wenn er diesen Typen einträgt. Der Manager der Gruppe erhält aber eine E-Mail, dass eine Genehmigung seinerseits erforderlich ist.
  Er kann dann den Kalender dieses Nutzers bearbeiten und die entsprechende Abwesenheit eintragen.';
$LANG['abs_bgcolor'] = 'Hintergrundfarbe';
$LANG['abs_bgcolor_comment'] = 'Die Hintergrundfarbe wird im Kalender benutzt, egal ob Symbol oder Icon gewählt ist. Ein Farbdialog erscheint beim Klicken in das Feld.';
$LANG['abs_bgtrans'] = 'Hintergrund Transparent';
$LANG['abs_bgtrans_comment'] = 'Mit dieser Option wird keine individuelle Hintergrundfarbe gesetzt, sondern die des darunter liegenden Objektes benutzt.';
$LANG['abs_color'] = 'Textfarbe';
$LANG['abs_color_comment'] = 'Wenn das Symbol benutzt wird (kein Icon), wird diese Textfarbe benutzt. Ein Farbdialog erscheint beim Klicken in das Feld.';
$LANG['abs_confidential'] = 'Vertraulich';
$LANG['abs_confidential_comment'] = 'Dieser Schalter macht den Typen "vertraulich". Normale Nutzer können diese Abwesenheit nicht im Kalender
 sehen, außer es ist ihre eigene Abwesenheit. Dies kann für sensitive Abwesenheiten wie "Krankheit" nützlich sein. Es ist möglich, in den Kalenderoptionen
 vertrauenswürdige Rollen zu definieren, die diese Abwesenheiten auch sehen können.';
$LANG['abs_confirm_delete'] = 'Bist du sicher, dass du den Abwesenheitstyp "%s" löschen willst?<br>Alle bestehenden Einträge werden mit "Anwesend" ersetzt.';
$LANG['abs_counts_as'] = 'Zählt als';
$LANG['abs_counts_as_comment'] = 'Hier kann ausgewählt werden, ob die genommenen Tage diese Abwesenheitstyps gegen die Erlaubnis eines anderen Typs zählen.
 Wenn ein anderer Typ gewählt wird, wird die Erlaubnis diese Typs hier nicht in Betracht gezogen, nur die des anderen Typs.<br>
 Beispiel: "Urlaub Halbtag" mit Faktor zählt gegen die Erlaubnis des Typs "Urlaub".';
$LANG['abs_counts_as_present'] = 'Zählt als anwesend';
$LANG['abs_counts_as_present_comment'] = 'Dieser Schalter definiert einen Typen als "anwesend". Dies bietet sich z.B. beim Abwesenheitstyp "Heimarbeit" an.
 Weil die Person arbeitet, möchte man dies nicht als "abwesend" zählen. Mit diesem Schalter aktiviert wird dann der Typ
 in den Summen als anwesend gewertet. Somit würde "Heimarbeit" dann auch nicht in den Abwesenheiten angezeigt.';
$LANG['abs_display'] = 'Anzeige';
$LANG['abs_display_comment'] = '';
$LANG['abs_edit_title'] = 'Abwesenheitstyp bearbeiten: ';
$LANG['abs_factor'] = 'Faktor';
$LANG['abs_factor_comment'] = 'TeamCal kann die genommen Tage dieses Abwesenheitstypen summieren. Das Ergebnis kann im "Abwesenheiten" Reiter des
 Nutzerprofils eingesehen werden. Der "Faktor" hier bietet einen Multiplikator für diesen Abwesenheitstypen für diese Berechnung. Der Standard ist 1.<br>
 Beispiel: Du kannst einen Abwesenheitstypen "Halbtagstraining" anlegen. Du würdest den Faktor dabei logischerweise auf 0.5 setzen, um die korrekte Summe
  genommener Trainingstage zu erhalten. Ein Nutzer, der 10 Halbtagstrainings genommen hat, käme so auf eine Summe von 5 (10// 0.5 = 5) ganzen Trainingstagen.<br>
  Wenn der Faktor auf 0 gesetzt wird, wird er von der Berechnung ausgeschlossen.';
$LANG['abs_groups'] = 'Gruppenzuordnung';
$LANG['abs_groups_comment'] = 'Wähle die Gruppen aus, für die dieser Abwesenheitstyp gültig sein soll. Wenn eine Gruppe nicht
 ausgewählt ist, können Mitglieder dieser Gruppe den Abwesenheitstyp nicht nutzen.';
$LANG['abs_hide_in_profile'] = 'Im Profil verbergen';
$LANG['abs_hide_in_profile_comment'] = 'Dieser Schalter kann benutzt werden, um diesen Typen für normale Nutzer nicht im "Abwesenheiten" Reiter der
 Nutzerprofile anzuzeigen. Nur Manager, Direktoren und Administratoren können ihn dort sehen. Diese Funktion macht Sinn, wenn Manager einen Typen
 nur zum Zwecke von Nachverfolgung nutzt oder die verbleibende Anzahl für den normalen Nutzer uninteressant ist.';
$LANG['abs_icon'] = 'Icon';
$LANG['abs_icon_comment'] = 'Das Icon wird im Kalender benutzt.';
$LANG['abs_icon_keyword'] = 'Gib ein Schlüsselwort ein...';
$LANG['abs_list_title'] = 'Abwesenheitstypen';
$LANG['abs_manager_only'] = 'Nur Gruppen-Manager';
$LANG['abs_manager_only_comment'] = 'Mit diesem Schalter aktiviert können nur Gruppen-Manager diesen Typen setzen. Nur wenn der eingeloggte Benutzer der Gruppem-Manager des Benutzers
 ist, dessen Kalender er bearbeitet, steht dieser Abwesenheitstyp zur Verfügung.';
$LANG['abs_name'] = 'Name';
$LANG['abs_name_comment'] = 'Der Name wird in Listen und Beschreibungen benutzt. Er sollte aussagekräftig sein, z.B. "Dienstreise". Maximal 80 Zeichen.';
$LANG['abs_sample'] = 'Beispielanzeige';
$LANG['abs_sample_comment'] = 'So würde der Abswesenheitstyp im Kalender angezeigt werden basierend auf den aktuellen Einstellungen.<br>
      Hinweis: In den Kalenderoptionen kann eingestellt werden, ob das Icon oder die Zeichen ID für die Anzeige benutzt werden soll.';
$LANG['abs_show_in_remainder'] = 'Verbleibend anzeigen';
$LANG['abs_show_in_remainder_comment'] = 'Mit dieser Option wird der Abwesenheitstyp auf der Verbeleibend-Seite angezeigt.';
$LANG['abs_symbol'] = 'Zeichen ID';
$LANG['abs_symbol_comment'] = 'Die Zeichen ID wird in E-Mails benutzt, da die Font Icons dort nicht unterstützt werden.
 Die Zeichen ID ist ein alphanumerisches Zeichen lang und muss angegeben werden. Allerdings kann das gleiche Zeichen für mehrere
  Abwesenheitstypen benutzt werden. Als Standard wird der Anfangsbuchstabe des Namens eingesetzt, wenn der Abwesenheitstyp angelegt wird.';
$LANG['abs_tab_groups'] = 'Gruppenzuordnung';
$LANG['abs_takeover'] = 'Übernahme aktivieren';
$LANG['abs_takeover_comment'] = 'Ermöglicht, dass dieser Abwesenheitstyp übernommen werden kann. Dazu muss die Abwesenheitsübernahme in TeamCal Neo generell eingeschaltet sein.';

//
// Absico
//
$LANG['absico_tab_brand'] = 'Marken Icons';
$LANG['absico_tab_regular'] = 'Regular Icons';
$LANG['absico_tab_solid'] = 'Solid Icons';
$LANG['absico_title'] = 'Icon-Auswahl: ';

//
// Absum
//
$LANG['absum_absencetype'] = 'Abwesenheitstyp';
$LANG['absum_contingent'] = 'Kontingent';
$LANG['absum_contingent_tt'] = 'Das Kontingent errechnet sich aus der erlaubten Anzahl des aktuellen Jahres plus dem Übertrag vom letzten Jahr. Der Übertrag kann auch negativ sein.';
$LANG['absum_modalYearTitle'] = 'Wähle das Jahr für die Übersicht.';
$LANG['absum_remainder'] = 'Verbleib';
$LANG['absum_taken'] = 'Genommen';
$LANG['absum_title'] = 'Abwesenheitsübersicht %s: %s';
$LANG['absum_unlimited'] = 'Unbegrenzt';
$LANG['absum_year'] = 'Jahr';
$LANG['absum_year_comment'] = 'Wähle das Jahr für die Übersicht.';
