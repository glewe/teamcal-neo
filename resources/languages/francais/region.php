<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings French: Region
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['region_edit_title'] = 'Modifier la région : ';
$LANG['region_alert_edit'] = 'Mettre à jour la région';
$LANG['region_alert_edit_success'] = 'Les informations pour cette région ont été mises à jour.';
$LANG['region_alert_save_failed'] = 'Les nouvelles informations n\'ont pas pu être sauvegardées. Veuillez vérifier les erreurs de saisie.';
$LANG['region_name'] = 'Nom';
$LANG['region_name_comment'] = '';
$LANG['region_description'] = 'Description';
$LANG['region_description_comment'] = '';
$LANG['region_viewOnlyRoles'] = 'Rôles en lecture seule';
$LANG['region_viewOnlyRoles_comment'] = 'Les rôles sélectionnés pourront uniquement voir cette région dans le calendrier, sans pouvoir y saisir d\'absences.';

$LANG['regions_title'] = 'Régions';
$LANG['regions_tab_list'] = 'Liste';
$LANG['regions_tab_ical'] = 'Import iCal';
$LANG['regions_tab_transfer'] = 'Transférer la région';
$LANG['regions_alert_transfer_same'] = 'La région source et la région cible doivent être différentes.';
$LANG['regions_alert_no_file'] = 'Aucun fichier iCal sélectionné.';
$LANG['regions_alert_region_created'] = 'La région a été créée.';
$LANG['regions_alert_region_created_fail'] = 'La région n\'a pas été créée. Veuillez vérifier les erreurs de saisie.';
$LANG['regions_alert_region_deleted'] = 'La région a été supprimée.';
$LANG['regions_confirm_delete'] = 'Êtes-vous sûr de vouloir supprimer cette région';
$LANG['regions_description'] = 'Description';
$LANG['regions_ical_file'] = 'Fichier iCal';
$LANG['regions_ical_file_comment'] = 'Sélectionnez un fichier iCal (ex: vacances scolaires) depuis votre disque local.';
$LANG['regions_ical_holiday'] = 'Jour férié iCal';
$LANG['regions_ical_holiday_comment'] = 'Sélectionnez le type de jour férié à utiliser pour les événements du fichier iCal.';
$LANG['regions_ical_imported'] = 'Le fichier iCal "%s" a été importé dans la région "%s".';
$LANG['regions_ical_overwrite'] = 'Écraser';
$LANG['regions_ical_overwrite_comment'] = 'Indique si les jours fériés existants dans la région cible doivent être écrasés.';
$LANG['regions_ical_region'] = 'Région iCal';
$LANG['regions_ical_region_comment'] = 'Sélectionnez la région dans laquelle importer les événements iCal.';
$LANG['regions_transferred'] = 'La région "%s" a été transférée dans la région "%s".';
$LANG['regions_name'] = 'Nom';
$LANG['regions_region_a'] = 'Région source';
$LANG['regions_region_a_comment'] = 'Sélectionnez la région à transférer.';
$LANG['regions_region_b'] = 'Région cible';
$LANG['regions_region_b_comment'] = 'Sélectionnez la région de destination.';
$LANG['regions_region_overwrite'] = 'Écraser';
$LANG['regions_region_overwrite_comment'] = 'Indique si les entrées de la région source doivent écraser celles de la région cible.';
