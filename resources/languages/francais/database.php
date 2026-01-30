<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings French: Database page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['db_alert_delete'] = 'Suppression d\'enregistrements';
$LANG['db_alert_delete_success'] = 'Les activités de suppression ont été effectuées.';
$LANG['db_alert_failed'] = 'L\'opération n\'a pas pu être effectuée. Veuillez vérifier votre saisie.';
$LANG['db_alert_cleanup'] = 'Nettoyage';
$LANG['db_alert_cleanup_success'] = 'Les activités de nettoyage ont été effectuées.';
$LANG['db_alert_optimize'] = 'Optimiser les tables';
$LANG['db_alert_optimize_success'] = 'Toutes les tables de la base de données ont été optimisées.';
$LANG['db_alert_repair'] = 'Réparer la base de données';
$LANG['db_alert_repair_success'] = 'Les activités de réparation ont été effectuées.';
$LANG['db_alert_reset'] = 'Réinitialiser la base de données';
$LANG['db_alert_reset_fail'] = 'Une ou plusieurs requêtes ont échoué. Votre base de données est peut-être incomplète ou corrompue.';
$LANG['db_alert_reset_success'] = 'Votre base de données a été réinitialisée avec succès avec les données d\'exemple.';
$LANG['db_alert_url'] = 'Sauvegarder l\'URL de la base de données';
$LANG['db_alert_url_fail'] = 'Veuillez saisir une URL valide pour l\'application de gestion de base de données.';
$LANG['db_alert_url_success'] = 'L\'URL de l\'application de gestion de base de données a été sauvegardée avec succès.';
$LANG['db_application'] = 'Administration de la base de données';
$LANG['db_clean_before'] = 'Avant la date';
$LANG['db_clean_before_comment'] = 'Les enregistrements des tables cochées ci-dessus seront supprimés s\'ils sont antérieurs ou égaux à la date sélectionnée ici.';
$LANG['db_clean_confirm'] = 'Confirmation';
$LANG['db_clean_confirm_comment'] = 'Veuillez saisir "CLEANUP" pour confirmer cette action.';
$LANG['db_clean_daynotes'] = 'Nettoyer les notes de jour avant...';
$LANG['db_clean_holidays'] = 'Nettoyer les jours fériés avant...';
$LANG['db_clean_months'] = 'Nettoyer les calendriers de région avant...';
$LANG['db_clean_templates'] = 'Nettoyer les calendriers d\'utilisateur avant...';
$LANG['db_clean_what'] = 'Quoi nettoyer';
$LANG['db_clean_what_comment'] = 'Sélectionnez ici ce que vous souhaitez nettoyer. Tous les enregistrements sélectionnés antérieurs ou égaux à la "Date de fin" seront supprimés. Les calendriers de région et d\'utilisateur sont supprimés par mois, indépendamment du jour saisi.';
$LANG['db_confirm'] = 'Confirmation';
$LANG['db_dbURL'] = 'URL de la base de données';
$LANG['db_dbURL_comment'] = 'Vous pouvez spécifier un lien direct vers votre application de gestion de base de données préférée (ex: phpMyAdmin).';
$LANG['db_del_archive'] = 'Supprimer tous les enregistrements archivés';
$LANG['db_del_confirm_comment'] = 'Veuillez saisir "DELETE" pour confirmer cette action :';
$LANG['db_del_groups'] = 'Supprimer tous les groupes';
$LANG['db_del_log'] = 'Supprimer toutes les entrées du journal système';
$LANG['db_del_messages'] = 'Supprimer tous les messages';
$LANG['db_del_orphMessages'] = 'Supprimer tous les messages orphelins';
$LANG['db_del_permissions'] = 'Supprimer les schémas de permissions personnalisés (sauf "Default")';
$LANG['db_del_users'] = 'Supprimer tous les utilisateurs, leurs modèles d\'absence et notes de jour (sauf "admin")';
$LANG['db_del_what'] = 'Quoi supprimer';
$LANG['db_del_what_comment'] = 'Sélectionnez ici ce que vous souhaitez supprimer.';
$LANG['db_optimize'] = 'Optimiser les tables de la base de données';
$LANG['db_optimize_comment'] = 'Réorganise le stockage physique des données et des index pour réduire l\'espace et améliorer l\'efficacité des accès.';
$LANG['db_repair_confirm'] = 'Confirmation';
$LANG['db_repair_confirm_comment'] = 'Veuillez saisir "REPAIR" pour confirmer cette action.';
$LANG['db_repair_daynoteRegions'] = 'Régions des notes de jour';
$LANG['db_repair_daynoteRegions_comment'] = 'Cette option vérifie s\'il existe des notes de jour sans région définie. Si c\'est le cas, la région par défaut leur sera affectée.';
$LANG['db_reset_basic'] = 'Données de base';
$LANG['db_reset_danger'] = '<strong>Danger !</strong> La réinitialisation de la base de données supprimera TOUTES vos données !!';
$LANG['db_reset_sample'] = 'Données de base plus données d\'exemple';
$LANG['db_resetDataset'] = 'Jeu de données de réinitialisation';
$LANG['db_resetDataset_comment'] = 'Sélectionnez le jeu de données vers lequel réinitialiser. "Basic" contient les données structurelles. "Sample" inclut des exemples d\'utilisateurs et d\'absences.';
$LANG['db_resetString'] = 'Chaîne de confirmation de réinitialisation';
$LANG['db_resetString_comment'] = 'La réinitialisation de la base de données remplacera toutes vos informations par la base d\'exemple.<br>Saisissez "YesIAmSure" dans la zone de texte pour confirmer.';
$LANG['db_tab_admin'] = 'Administration';
$LANG['db_tab_cleanup'] = 'Nettoyage';
$LANG['db_tab_dbinfo'] = 'Informations base de données';
$LANG['db_tab_delete'] = 'Supprimer enregistrements';
$LANG['db_tab_optimize'] = 'Optimiser les tables';
$LANG['db_tab_repair'] = 'Réparer';
$LANG['db_tab_reset'] = 'Réinitialiser la base de données';
$LANG['db_tab_tcpimp'] = 'Importation TeamCal Pro';
$LANG['db_title'] = 'Maintenance de la base de données';
