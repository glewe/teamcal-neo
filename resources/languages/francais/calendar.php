<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings French: Calendar
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['cal_title'] = 'Calendrier %s-%s (Région : %s)';
$LANG['cal_tt_absent'] = 'Absent';
$LANG['cal_tt_anotherabsence'] = 'Autre absence';
$LANG['cal_tt_backward'] = 'Reculer d\'un mois...';
$LANG['cal_tt_clicktoedit'] = 'Cliquer pour modifier...';
$LANG['cal_tt_forward'] = 'Avancer d\'un mois...';
$LANG['cal_tt_onemore'] = 'Afficher un mois de plus...';
$LANG['cal_tt_oneless'] = 'Afficher un mois de moins...';
$LANG['cal_search'] = 'Rechercher un utilisateur';
$LANG['cal_selAbsence'] = 'Sélectionner l\'absence';
$LANG['cal_selAbsence_comment'] = 'Affiche toutes les entrées ayant ce type d\'absence pour aujourd\'hui.';
$LANG['cal_selGroup'] = 'Sélectionner le groupe';
$LANG['cal_selMonth'] = 'Sélectionner le mois';
$LANG['cal_selRegion'] = 'Sélectionner la région';
$LANG['cal_selWidth'] = 'Sélectionner la largeur d\'écran';
$LANG['cal_selWidth_comment'] = 'Sélectionnez la largeur de votre écran en pixels pour que le tableau du calendrier s\'y ajuste. Si votre largeur n\'est pas dans la liste, sélectionnez la valeur supérieure suivante.
      <br>Il semble que vous utilisiez actuellement un écran d\'une largeur de <span id="currentwidth"></span> pixels. Rechargez la page pour vérifier à nouveau ce dialogue.';
$LANG['cal_switchFullmonthView'] = 'Passer à la vue du mois complet';
$LANG['cal_switchSplitmonthView'] = 'Passer à la vue du mois fractionné';
$LANG['cal_summary'] = 'Résumé';
$LANG['cal_businessDays'] = 'Jours ouvrables dans le mois';
$LANG['cal_caption_weeknumber'] = 'Semaine';
$LANG['cal_caption_name'] = 'Nom';
$LANG['cal_img_alt_edit_month'] = 'Modifier les jours fériés pour ce mois...';
$LANG['cal_img_alt_edit_cal'] = 'Modifier le calendrier pour cette personne...';
$LANG['cal_birthday'] = 'Anniversaire';
$LANG['cal_age'] = 'Âge';

$LANG['sum_present'] = 'Présent';
$LANG['sum_absent'] = 'Absent';
$LANG['sum_delta'] = 'Différence';
$LANG['sum_absence_summary'] = 'Résumé des absences';
$LANG['sum_business_day_count'] = 'jours ouvrables';
$LANG['exp_summary'] = 'Développer la section Résumé...';
$LANG['col_summary'] = 'Réduire la section Résumé...';
$LANG['exp_remainder'] = 'Développer la section Reste...';
$LANG['col_remainder'] = 'Réduire la section Reste...';

$LANG['caledit_title'] = 'Modifier le mois %s-%s pour %s';
$LANG['caledit_absencePattern'] = 'Modèle d\'absence';
$LANG['caledit_absencePattern_comment'] = 'Sélectionnez le modèle d\'absence à appliquer à ce mois.';
$LANG['caledit_absencePatternSkipHolidays'] = 'Ignorer les jours fériés';
$LANG['caledit_absencePatternSkipHolidays_comment'] = 'Lors de la définition des absences du modèle, ignorer les jours fériés qui ne comptent pas comme jours ouvrables.';
$LANG['caledit_absenceType'] = 'Type d\'absence';
$LANG['caledit_absenceType_comment'] = 'Sélectionnez le type d\'absence pour cette saisie.';
$LANG['caledit_alert_out_of_range'] = 'Les dates saisies étaient au moins partiellement en dehors du mois actuellement affiché. Aucune modification n\'a été enregistrée.';
$LANG['caledit_alert_save_failed'] = 'Les informations d\'absence n\'ont pas pu être sauvegardées. Une saisie était invalide. Veuillez vérifier votre dernière saisie.';
$LANG['caledit_alert_update'] = 'Mettre à jour le mois';
$LANG['caledit_alert_update_all'] = 'Toutes les absences ont été acceptées et le calendrier a été mis à jour en conséquence.';
$LANG['caledit_alert_update_group'] = 'Les absences de groupe ont été définies pour tous les utilisateurs du groupe.';
$LANG['caledit_alert_update_group_cleared'] = 'Les absences de groupe ont été effacées pour tous les utilisateurs du groupe.';
$LANG['caledit_alert_update_partial'] = 'Certaines absences n\'ont pas été acceptées car elles violent les restrictions définies par la direction. Les demandes suivantes ont été refusées :';
$LANG['caledit_alert_update_none'] = 'Les absences n\'ont pas été acceptées car les absences demandées violent les restrictions définies par la direction. Le calendrier n\'a pas été mis à jour.';
$LANG['caledit_clearAbsence'] = 'Effacer';
$LANG['caledit_clearAbsences'] = 'Effacer les absences';
$LANG['caledit_clearDaynotes'] = 'Effacer les notes de jour';
$LANG['caledit_confirm_clearall'] = 'Êtes-vous sûr de vouloir effacer toutes les absences de ce mois ?<br><br><strong>Année :</strong> %s<br><strong>Mois :</strong> %s<br><strong>Utilisateur :</strong> %s';
$LANG['caledit_confirm_savegroup'] = '<p><strong class="text-danger">Attention !</strong><br>La sauvegarde des absences de groupe n\'effectuera aucune vérification d\'approbation individuelle.<br>
      Toutes les absences seront définies pour chaque utilisateur du groupe sélectionné. Vous pouvez toutefois choisir de ne pas écraser les absences individuelles existantes ci-dessous.</p>
      <p><strong>Année :</strong> %s<br><strong>Mois :</strong> %s<br><strong>Groupe :</strong> %s</p>';
$LANG['caledit_currentAbsence'] = 'Absence actuelle';
$LANG['caledit_endDate'] = 'Date de fin';
$LANG['caledit_endDate_comment'] = 'Sélectionnez la date de fin (doit être dans ce mois).';
$LANG['caledit_keepExisting'] = 'Conserver les absences existantes des utilisateurs';
$LANG['caledit_Pattern'] = 'Modèle';
$LANG['caledit_PatternTitle'] = 'Sélectionner le modèle d\'absence';
$LANG['caledit_Period'] = 'Période';
$LANG['caledit_PeriodTitle'] = 'Sélectionner la période d\'absence';
$LANG['caledit_Recurring'] = 'Récurrent';
$LANG['caledit_RecurringTitle'] = 'Sélectionner l\'absence récurrente';
$LANG['caledit_recurrence'] = 'Récurrence';
$LANG['caledit_recurrence_comment'] = 'Sélectionnez la récurrence';
$LANG['caledit_selGroup'] = 'Sélectionner le groupe';
$LANG['caledit_selRegion'] = 'Sélectionner la région';
$LANG['caledit_selUser'] = 'Sélectionner l\'utilisateur';
$LANG['caledit_startDate'] = 'Date de début';
$LANG['caledit_startDate_comment'] = 'Sélectionnez la date de début (doit être dans ce mois).';
