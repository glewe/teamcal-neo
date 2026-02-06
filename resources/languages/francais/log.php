<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings French: Log page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
//
// Log Page
//
$LANG['log_title'] = 'Journal système';
$LANG['log_title_events'] = 'Événements';
$LANG['log_clear'] = 'Supprimer la période';
$LANG['log_clear_confirm'] = 'Êtes-vous sûr de vouloir supprimer tous les événements de la période sélectionnée ?';
$LANG['log_filterCalopt'] = 'Options du calendrier';
$LANG['log_filterPatterns'] = 'Modèles d\'absence';
$LANG['log_filterConfig'] = 'Configuration';
$LANG['log_filterDatabase'] = 'Base de données';
$LANG['log_filterGroup'] = 'Groupes';
$LANG['log_filterLogin'] = 'Connexion';
$LANG['log_filterLoglevel'] = 'Niveau de journalisation';
$LANG['log_filterNews'] = 'Actualités';
$LANG['log_filterPermission'] = 'Permissions';
$LANG['log_filterRegistration'] = 'Enregistrement';
$LANG['log_filterRole'] = 'Rôles';
$LANG['log_filterUser'] = 'Utilisateur';
$LANG['log_header_event'] = 'Événement';
$LANG['log_header_ip'] = 'IP';
$LANG['log_header_type'] = 'Type d\'événement';
$LANG['log_header_user'] = 'Utilisateur';
$LANG['log_header_when'] = 'Horodatage (UTC)';
$LANG['log_settings'] = 'Paramètres du journal';
$LANG['log_settings_event'] = 'Type d\'événement';
$LANG['log_settings_log'] = 'Enregistrer ce type';
$LANG['log_settings_show'] = 'Afficher ce type';
$LANG['log_sort_asc'] = 'Trier par ordre croissant...';
$LANG['log_sort_desc'] = 'Trier par ordre décroissant...';
$LANG['log_statistics'] = 'Statistiques';
$LANG['log_statistics_timeframe'] = 'Période';
$LANG['log_statistics_last_day'] = 'Aujourd\'hui';
$LANG['log_statistics_last_week'] = 'Cette Semaine';
$LANG['log_statistics_last_month'] = 'Ce Mois';
$LANG['log_statistics_last_quarter'] = 'Ce Trimestre';
$LANG['log_statistics_last_year'] = 'Cette Année';
$LANG['log_statistics_overall'] = 'Global';
$LANG['log_statistics_event_types'] = 'Types d\'Événements';
$LANG['log_statistics_select_all'] = 'Tout sélectionner';
$LANG['log_statistics_unselect_all'] = 'Tout désélectionner';
$LANG['log_statistics_select_types'] = 'Sélectionnez les types d\'événements à afficher dans le graphique';
$LANG['log_statistics_chart_title'] = 'Statistiques des Événements';
$LANG['log_statistics_no_data'] = 'Aucune donnée disponible. Veuillez sélectionner au moins un type d\'événement.';
//
// Log Messages
//
$LANG['log_abs_created'] = 'Type d\'absence créé : ';
$LANG['log_abs_updated'] = 'Type d\'absence mis à jour : ';
$LANG['log_abs_deleted'] = 'Type d\'absence supprimé : ';
$LANG['log_cal_grp_tpl_chg'] = 'Calendrier de groupe modifié : ';
$LANG['log_cal_tplusr_def_tpl'] = 'Modèle de calendrier utilisateur créé : ';
$LANG['log_cal_usr_def_tpl'] = 'Calendrier utilisateur créé : ';
$LANG['log_cal_usr_tpl_chg'] = 'Calendrier utilisateur modifié : ';
$LANG['log_cal_usr_tpl_clr'] = 'Calendrier utilisateur effacé : ';
$LANG['log_calopt'] = 'Options du calendrier modifiées';
$LANG['log_config'] = 'Configuration système modifiée';
$LANG['log_csv_import'] = 'Importation utilisateur CSV : ';
$LANG['log_db_cleanup_before'] = 'Nettoyage base de données avant le ';
$LANG['log_db_delete_archive'] = 'Suppression base de données : Archives effacées';
$LANG['log_db_delete_groups'] = 'Suppression base de données : Tous les groupes';
$LANG['log_db_delete_log'] = 'Suppression base de données : Journal effacé';
$LANG['log_db_delete_msg'] = 'Suppression base de données : Tous les messages';
$LANG['log_db_delete_msg_orph'] = 'Suppression base de données : Tous les messages orphelins';
$LANG['log_db_delete_perm'] = 'Suppression base de données : Tous les schémas de permissions personnalisés';
$LANG['log_db_delete_users'] = 'Suppression base de données : Tous les utilisateurs';
$LANG['log_db_export'] = 'Exportation base de données : ';
$LANG['log_db_optimized'] = 'Base de données optimisée';
$LANG['log_db_reset'] = 'Base de données réinitialisée';
$LANG['log_db_restore'] = 'Base de données restaurée depuis ';
$LANG['log_decl_updated'] = 'Paramètres de refus mis à jour';
$LANG['log_dn_updated'] = 'Note de jour mise à jour : ';
$LANG['log_dn_created'] = 'Note de jour créée : ';
$LANG['log_dn_deleted'] = 'Note de jour supprimée : ';
$LANG['log_group_created'] = 'Groupe créé : ';
$LANG['log_group_updated'] = 'Groupe mis à jour : ';
$LANG['log_group_deleted'] = 'Groupe supprimé : ';
$LANG['log_hol_created'] = 'Jour férié créé : ';
$LANG['log_hol_updated'] = 'Jour férié mis à jour : ';
$LANG['log_hol_deleted'] = 'Jour férié supprimé : ';
$LANG['log_imp_success'] = 'Importation CSV réussie : ';
$LANG['log_log_updated'] = 'Paramètres du journal mis à jour';
$LANG['log_log_cleared'] = 'Journal effacé';
$LANG['log_log_reset'] = 'Paramètres du journal réinitialisés';
$LANG['log_login_2fa'] = 'Code d\'authentification incorrect';
$LANG['log_login_success'] = 'Connexion réussie';
$LANG['log_login_missing'] = 'Nom d\'utilisateur ou mot de passe manquant';
$LANG['log_login_unknown'] = 'Nom d\'utilisateur inconnu';
$LANG['log_login_locked'] = 'Compte verrouillé';
$LANG['log_login_pwd'] = 'Mot de passe incorrect';
$LANG['log_login_attempts'] = 'Trop de tentatives de connexion échouées';
$LANG['log_login_not_verified'] = 'Compte utilisateur non vérifié';
$LANG['log_login_ldap_pwd_missing'] = 'Mot de passe LDAP manquant';
$LANG['log_login_ldap_bind_failed'] = 'Liaison LDAP échouée';
$LANG['log_login_ldap_connect_failed'] = 'Connexion LDAP échouée';
$LANG['log_login_ldap_tls_failed'] = 'Lancement TLS LDAP échoué';
$LANG['log_login_ldap_username'] = 'Utilisateur LDAP non trouvé';
$LANG['log_login_ldap_search_bind_failed'] = 'Recherche liaison LDAP échouée';
$LANG['log_logout'] = 'Déconnexion';
$LANG['log_month_tpl_created'] = 'Modèle de mois créé : ';
$LANG['log_month_tpl_updated'] = 'Modèle de mois mis à jour : ';
$LANG['log_msg_all_confirmed_by'] = 'Toutes les actualités confirmées par ';
$LANG['log_msg_all_deleted_by'] = 'Toutes les actualités supprimées par ';
$LANG['log_msg_confirmed'] = 'Message confirmé : ';
$LANG['log_msg_deleted'] = 'Message supprimé : ';
$LANG['log_msg_email'] = 'E-mail envoyé par ';
$LANG['log_msg_message'] = 'Message envoyé : ';
$LANG['log_pattern_created'] = 'Modèle d\'absence créé : ';
$LANG['log_pattern_updated'] = 'Modèle d\'absence mis à jour : ';
$LANG['log_pattern_deleted'] = 'Modèle d\'absence supprimé : ';
$LANG['log_perm_activated'] = 'Schéma de permissions activé : ';
$LANG['log_perm_deleted'] = 'Schéma de permissions supprimé : ';
$LANG['log_perm_created'] = 'Schéma de permissions créé : ';
$LANG['log_perm_reset'] = 'Schéma de permissions réinitialisé : ';
$LANG['log_perm_changed'] = 'Schéma de permissions modifié : ';
$LANG['log_region_created'] = 'Région créée : ';
$LANG['log_region_deleted'] = 'Région supprimée : ';
$LANG['log_region_ical'] = 'Fichier iCal "';
$LANG['log_region_transferred'] = 'Transfert de région : ';
$LANG['log_region_updated'] = 'Région mise à jour : ';
$LANG['log_role_created'] = 'Rôle créé : ';
$LANG['log_role_updated'] = 'Rôle mis à jour : ';
$LANG['log_role_deleted'] = 'Rôle supprimé : ';
$LANG['log_tcpimp_abs'] = 'Types d\'absence TeamCal Pro importés';
$LANG['log_tcpimp_allo'] = 'Allocations TeamCal Pro importées';
$LANG['log_tcpimp_dayn'] = 'Notes de jour TeamCal Pro importées';
$LANG['log_tcpimp_groups'] = 'Groupes TeamCal Pro importés';
$LANG['log_tcpimp_hols'] = 'Jours fériés TeamCal Pro importés';
$LANG['log_tcpimp_mtpl'] = 'Calendriers de région TeamCal Pro importés';
$LANG['log_tcpimp_regs'] = 'Régions TeamCal Pro importées';
$LANG['log_tcpimp_roles'] = 'Rôles TeamCal Pro importés';
$LANG['log_tcpimp_ugr'] = 'Appartenances aux groupes TeamCal Pro importées';
$LANG['log_tcpimp_users'] = 'Comptes utilisateurs TeamCal Pro importés';
$LANG['log_tcpimp_utpl'] = 'Calendriers utilisateurs TeamCal Pro importés';
$LANG['log_user_2fa_removed'] = 'Secret 2FA supprimé pour : ';
$LANG['log_user_added'] = 'Utilisateur ajouté : ';
$LANG['log_user_updated'] = 'Profil utilisateur mis à jour : ';
$LANG['log_user_deleted'] = 'Utilisateur supprimé : ';
$LANG['log_user_archived_deleted'] = 'Utilisateur archivé supprimé : ';
$LANG['log_user_archived'] = 'Utilisateur archivé : ';
$LANG['log_user_restored'] = 'Utilisateur restauré : ';
$LANG['log_user_registered'] = 'Enregistrement utilisateur : ';
$LANG['log_user_pwd_request'] = 'Jeton de mot de passe envoyé à : ';
$LANG['log_user_pwd_reset'] = 'Mot de passe utilisateur réinitialisé : ';
$LANG['log_user_avatar_uploaded'] = 'Avatar utilisateur téléversé : ';
$LANG['log_user_group_updated'] = 'Type d\'utilisateur et/ou affectation aux groupes mis à jour';
$LANG['log_user_verify_approval'] = 'Utilisateur vérifié, approbation requise : ';
$LANG['log_user_verify_unlocked'] = 'Utilisateur vérifié, déverrouillé et affiché : ';
$LANG['log_user_verify_mismatch'] = 'Le code de vérification utilisateur ne correspond pas : ';
$LANG['log_user_verify_usr_notexist'] = 'L\'utilisateur n\'existe pas : ';
$LANG['log_user_verify_code_notexist'] = 'Le code de vérification n\'existe pas : ';
