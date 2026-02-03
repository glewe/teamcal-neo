<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings French: System Settings page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['config_title'] = 'Paramètres système';

$LANG['config_tab_email'] = 'E-mail';
$LANG['config_tab_footer'] = 'Pied de page';
$LANG['config_tab_homepage'] = 'Page d\'accueil';
$LANG['config_tab_images'] = 'Images';
$LANG['config_tab_license'] = 'Licence';
$LANG['config_tab_login'] = 'Connexion';
$LANG['config_tab_registration'] = 'Enregistrement';
$LANG['config_tab_system'] = 'Système';
$LANG['config_tab_theme'] = 'Thème';
$LANG['config_tab_user'] = 'Utilisateur';
$LANG['config_tab_gdpr'] = 'RGPD';

$LANG['config_activateMessages'] = 'Activer le centre de messages';
$LANG['config_activateMessages_comment'] = 'Cette option activera le centre de messages. Les utilisateurs peuvent l\'utiliser pour envoyer des annonces ou des e-mails à d\'autres utilisateurs ou groupes. Une entrée sera ajoutée au menu Outils.';
$LANG['config_adminApproval'] = 'Approbation de l\'administrateur requise';
$LANG['config_adminApproval_comment'] = 'L\'administrateur recevra un e-mail pour chaque auto-enregistrement d\'utilisateur. Il devra confirmer manuellement le compte.';
$LANG['config_alert_edit_success'] = 'La configuration a été mise à jour. Pour que certains changements prennent effet, vous devrez peut-être rafraîchir la page.';
$LANG['config_alert_failed'] = 'La configuration n\'a pas pu être mise à jour. Veuillez vérifier votre saisie.';
$LANG['config_alertAutocloseDanger'] = 'Fermer automatiquement les alertes d\'erreur';
$LANG['config_alertAutocloseDanger_comment'] = 'Indique si les alertes d\'erreur doivent être fermées automatiquement après le nombre de millisecondes spécifié ci-dessous.';
$LANG['config_alertAutocloseDelay'] = 'Délai de fermeture automatique des alertes';
$LANG['config_alertAutocloseDelay_comment'] = 'Entrez le nombre de millisecondes après lequel les types d\'alertes sélectionnés ci-dessus seront fermés automatiquement (par ex. 4000 = 4 secondes).';
$LANG['config_alertAutocloseSuccess'] = 'Fermer automatiquement les alertes de succès';
$LANG['config_alertAutocloseSuccess_comment'] = 'Indique si les alertes de succès doivent être fermées automatiquement après le nombre de millisecondes spécifié ci-dessous.';
$LANG['config_alertAutocloseWarning'] = 'Fermer automatiquement les alertes d\'avertissement';
$LANG['config_alertAutocloseWarning_comment'] = 'Indique si les alertes d\'avertissement doivent être fermées automatiquement après le nombre de millisecondes spécifié ci-dessous.';
$LANG['config_allowRegistration'] = 'Autoriser l\'auto-enregistrement des utilisateurs';
$LANG['config_allowRegistration_comment'] = 'Autorise les utilisateurs à enregistrer eux-mêmes leur compte. Une entrée de menu sera disponible.';
$LANG['config_appDescription'] = 'Description HTML';
$LANG['config_appDescription_comment'] = 'Entrez ici une description de l\'application. Elle sera utilisée dans l\'en-tête HTML pour les moteurs de recherche.';
$LANG['config_appKeywords'] = 'Mots-clés HTML';
$LANG['config_appKeywords_comment'] = 'Entrez ici quelques mots-clés. Ils seront utilisés dans l\'en-tête HTML pour les moteurs de recherche.';
$LANG['config_appTitle'] = 'Nom de l\'application';
$LANG['config_appTitle_comment'] = 'Entrez ici le titre de l\'application. Il est utilisé à plusieurs endroits, par ex. l\'en-tête HTML, le menu et d\'autres pages.';
$LANG['config_appURL'] = 'URL de l\'application';
$LANG['config_appURL_comment'] = 'Entrez ici l\'URL complète de l\'application. Elle sera utilisée dans les e-mails de notification.';
$LANG['config_badLogins'] = 'Connexions échouées';
$LANG['config_badLogins_comment'] = 'Nombre de tentatives de connexion infructueuses après lequel le statut de l\'utilisateur passe à \'VERROUILLÉ\'. L\'utilisateur devra attendre la fin du délai de grâce avant de pouvoir se reconnecter. Réglez à 0 pour désactiver cette fonctionnalité.';
$LANG['config_cookieConsent'] = 'Consentement aux cookies';
$LANG['config_cookieConsent_comment'] = 'Avec cette option, une confirmation de consentement aux cookies apparaîtra en bas de l\'écran. C\'est une obligation légale dans l\'UE. Cette fonctionnalité nécessite une connexion Internet.';

$LANG['config_cookieLifetime'] = 'Durée de vie du cookie';
$LANG['config_cookieLifetime_comment'] = 'Lors d\'une connexion réussie, un cookie est stocké localement. Il a une durée de vie après laquelle il devient invalide. La durée peut être spécifiée ici en secondes (0-999999).';
$LANG['config_defaultHomepage'] = 'Page d\'accueil par défaut';
$LANG['config_defaultHomepage_comment'] = 'Sélectionnez la page d\'accueil par défaut. Elle est affichée aux utilisateurs anonymes et lors du clic sur l\'icône de l\'application. Attention, si vous choisissez "Calendrier", le rôle "Public" doit avoir les permissions suffisantes.';
$LANG['config_defaultHomepage_home'] = 'Page de bienvenue';
$LANG['config_defaultHomepage_calendarview'] = 'Calendrier';
$LANG['config_defaultLanguage'] = 'Langue par défaut';
$LANG['config_defaultLanguage_comment'] = 'TeamCal Neo est distribué en anglais, allemand, espagnol et français. Choisissez ici la langue par défaut de votre installation.';
$LANG['config_defaultMenu'] = 'Position du menu';
$LANG['config_defaultMenu_comment'] = 'Le menu peut être affiché horizontalement en haut ou verticalement à gauche. Le menu vertical est adapté aux écrans larges. L\'utilisateur peut changer ce paramètre dans son profil.';
$LANG['config_defaultMenu_navbar'] = 'Horizontal en haut';
$LANG['config_defaultMenu_sidebar'] = 'Vertical à gauche';
$LANG['config_disableTfa'] = 'Désactiver l\'authentification à deux facteurs';
$LANG['config_disableTfa_comment'] = 'Désactive la fonctionnalité 2FA pour tous les utilisateurs. Cela supprimera la page de configuration 2FA du profil utilisateur et supprimera tous les secrets existants.';
$LANG['config_emailConfirmation'] = 'Exiger une confirmation par e-mail';
$LANG['config_emailConfirmation_comment'] = 'Lors de l\'enregistrement, l\'utilisateur recevra un e-mail avec un lien de confirmation pour valider ses informations.';
$LANG['config_emailNotifications'] = 'Notifications par e-mail';
$LANG['config_emailNotifications_comment'] = 'Activer/Désactiver les notifications par e-mail automatiques. Cela ne s\'applique pas aux e-mails d\'auto-enregistrement ou aux messages envoyés manuellement.';

$LANG['config_font'] = 'Police';
$LANG['config_font_comment'] = 'Sélectionnez la police à utiliser. Par défaut utilise la police sans-serif du navigateur. Les autres options chargent des polices Google hébergées localement.';
$LANG['config_footerCopyright'] = 'Nom du copyright en pied de page';
$LANG['config_footerCopyright_comment'] = 'Sera affiché en bas à gauche. Entrez juste le nom, l\'année en cours s\'affichera automatiquement.';
$LANG['config_footerCopyrightUrl'] = 'URL du copyright en pied de page';
$LANG['config_footerCopyrightUrl_comment'] = 'Entrez l\'URL vers laquelle le nom du copyright doit pointer.';
$LANG['config_footerSocialLinks'] = 'Liens sociaux';
$LANG['config_footerSocialLinks_comment'] = 'Entrez les URLs vers vos sites sociaux séparées par des points-virgules.';
$LANG['config_forceTfa'] = 'Forcer l\'authentification à deux facteurs';
$LANG['config_forceTfa_comment'] = 'Oblige les utilisateurs à configurer la 2FA. S\'ils ne l\'ont pas fait, ils seront redirigés vers la page de configuration après la connexion.';

$LANG['config_gdprController'] = 'Responsable du traitement';
$LANG['config_gdprController_comment'] = 'Entrez les informations relatives au responsable du traitement au sens du RGPD.';
$LANG['config_gdprOfficer'] = 'Délégué à la protection des données';
$LANG['config_gdprOfficer_comment'] = 'Nom du délégué à la protection des données.';
$LANG['config_gdprOrganization'] = 'Organisation';
$LANG['config_gdprOrganization_comment'] = 'Nom de l\'organisation ou société qui fournit cette instance de TeamCal Neo.';
$LANG['config_gdprPlatforms'] = 'Politiques des plateformes';
$LANG['config_gdprPlatforms_comment'] = 'Cochez les plateformes que vous souhaitez inclure dans la politique de protection des données.';
$LANG['config_gdprPolicyPage'] = 'Politique de confidentialité';
$LANG['config_gdprPolicyPage_comment'] = 'Cocher pour ajouter une page de politique de confidentialité au menu Aide. Les champs Organisation et Responsable doivent être remplis.';
$LANG['config_googleAnalytics'] = 'Google Analytics';
$LANG['config_googleAnalytics_comment'] = 'Active la prise en charge de Google Analytics pour suivre les accès.';
$LANG['config_googleAnalyticsID'] = 'ID Google Analytics (GA4)';
$LANG['config_googleAnalyticsID_comment'] = 'Entrez votre ID GA4 au format G-... .';
$LANG['config_gracePeriod'] = 'Délai de grâce';
$LANG['config_gracePeriod_comment'] = 'Temps d\'attente en secondes après trop de tentatives de connexion échouées.';
$LANG['config_homepage'] = 'Page d\'accueil utilisateur';
$LANG['config_homepage_comment'] = 'Sélectionnez la page à afficher aux utilisateurs enregistrés après la connexion.';
$LANG['config_homepage_calendarview'] = 'Calendrier';
$LANG['config_homepage_home'] = 'Page de bienvenue';
$LANG['config_homepage_messages'] = 'Page des messages';

$LANG['config_jqtheme'] = 'Thème jQuery UI';
$LANG['config_jqtheme_comment'] = 'Sélectionnez le thème pour les dialogues et autres fonctionnalités jQuery UI.';
$LANG['config_jqthemeSample'] = 'Exemple de thème jQuery UI';
$LANG['config_jqthemeSample_comment'] = 'Testez ce sélecteur de date pour voir le thème actuel.';
$LANG['config_licActivate'] = 'Activer la licence';
$LANG['config_licActivate_comment'] = 'Votre licence n\'est pas encore active. Veuillez l\'activer.';
$LANG['config_licExpiryWarning'] = 'Avertissement d\'expiration de licence';
$LANG['config_licExpiryWarning_comment'] = 'Nombre de jours avant l\'expiration pour commencer à afficher une alerte. Réglez à 0 pour aucune alerte.';
$LANG['config_licKey'] = 'Clé de licence';
$LANG['config_licKey_comment'] = 'Entrez votre clé de licence ici. Elle doit être sauvegardée avant toute activation ou enregistrement.';
$LANG['config_licRegister'] = 'Enregistrer le domaine';
$LANG['config_licRegister_comment'] = 'Enregistrez ce domaine pour la clé de licence donnée.';
$LANG['config_licDeregister'] = 'Désenregistrer le domaine';
$LANG['config_licDeregister_comment'] = 'Désenregistrez ce domaine pour déplacer votre instance vers un autre domaine.';
$LANG['config_logLanguage'] = 'Langue du journal';
$LANG['config_logLanguage_comment'] = 'Définit la langue des entrées du journal système.';
$LANG['config_mailFrom'] = 'Expéditeur de l\'e-mail';
$LANG['config_mailFrom_comment'] = 'Nom affiché comme expéditeur des e-mails.';
$LANG['config_mailReply'] = 'Adresse de réponse';
$LANG['config_mailReply_comment'] = 'Adresse e-mail de réponse pour les notifications.';
$LANG['config_mailSMTP'] = 'Utiliser un serveur SMTP externe';
$LANG['config_mailSMTP_comment'] = 'Utilise un serveur SMTP externe au lieu de mail(). Nécessite le package PEAR Mail.';
$LANG['config_mailSMTPAnonymous'] = 'SMTP anonyme';
$LANG['config_mailSMTPAnonymous_comment'] = 'Utilise une connexion SMTP sans authentification.';
$LANG['config_mailSMTPhost'] = 'Hôte SMTP';
$LANG['config_mailSMTPhost_comment'] = 'Nom d\'hôte du serveur SMTP.';
$LANG['config_mailSMTPport'] = 'Port SMTP';
$LANG['config_mailSMTPport_comment'] = 'Port du serveur SMTP.';
$LANG['config_mailSMTPusername'] = 'Utilisateur SMTP';
$LANG['config_mailSMTPusername_comment'] = 'Nom d\'utilisateur SMTP.';
$LANG['config_mailSMTPpassword'] = 'Mot de passe SMTP';
$LANG['config_mailSMTPpassword_comment'] = 'Mot de passe SMTP.';
$LANG['config_mailSMTPSSL'] = 'Protocole SMTP TLS/SSL';
$LANG['config_mailSMTPSSL_comment'] = 'Utilise TLS/SSL pour la connexion SMTP.';
$LANG['config_matomoAnalytics'] = 'Matomo Analytics';
$LANG['config_matomoAnalytics_comment'] = 'Active la prise en charge de Matomo Analytics pour suivre les accès.';
$LANG['config_matomoUrl'] = 'URL Matomo';
$LANG['config_matomoUrl_comment'] = 'URL de votre serveur Matomo.';
$LANG['config_matomoSiteId'] = 'ID de site Matomo';
$LANG['config_matomoSiteId_comment'] = 'L\'ID de site configuré sur votre serveur Matomo.';
$LANG['config_noCaching'] = 'Pas de cache';
$LANG['config_noCaching_comment'] = 'Envoie des instructions de non-mise en cache au serveur web pour éviter des effets indésirables.';
$LANG['config_noIndex'] = 'Pas d\'indexation par les moteurs de recherche';
$LANG['config_noIndex_comment'] = 'Demande aux robots de ne pas indexer ce site.';
$LANG['config_pageHelp'] = 'Aide de page';
$LANG['config_pageHelp_comment'] = 'Affiche une icône d\'aide dans la barre de titre de la page.';
$LANG['config_permissionScheme'] = 'Schéma de permissions';
$LANG['config_permissionScheme_comment'] = 'Définit qui peut faire quoi. Configurable sur la page des permissions.';
$LANG['config_pwdStrength'] = 'Force du mot de passe';
$LANG['config_pwdStrength_comment'] = 'Définit le niveau d\'exigence pour le contrôle des mots de passe.';
$LANG['config_pwdStrength_low'] = 'Faible';
$LANG['config_pwdStrength_medium'] = 'Moyenne';
$LANG['config_pwdStrength_high'] = 'Élevée';
$LANG['config_showAlerts'] = 'Afficher les alertes';
$LANG['config_showAlerts_comment'] = 'Sélectionnez le type d\'alertes à afficher.';
$LANG['config_showAlerts_all'] = 'Tout (incluant les messages de succès)';
$LANG['config_showAlerts_warnings'] = 'Avertissements et erreurs uniquement';
$LANG['config_showAlerts_none'] = 'Aucune';
$LANG['config_timeZone'] = 'Fuseau horaire';
$LANG['config_timeZone_comment'] = 'Ajustez ici le fuseau horaire si votre serveur est dans une zone différente de celle de vos utilisateurs.';
$LANG['config_underMaintenance'] = 'En maintenance';
$LANG['config_underMaintenance_comment'] = 'Active le mode maintenance. Seul l\'admin peut accéder au site.';
$LANG['config_userCustom1'] = 'Libellé du champ personnalisé 1';
$LANG['config_userCustom1_comment'] = 'Libellé affiché dans le dialogue de profil.';
$LANG['config_userCustom2'] = 'Libellé du champ personnalisé 2';
$LANG['config_userCustom2_comment'] = 'Libellé affiché dans le dialogue de profil.';
$LANG['config_userCustom3'] = 'Libellé du champ personnalisé 3';
$LANG['config_userCustom3_comment'] = 'Libellé affiché dans le dialogue de profil.';
$LANG['config_userCustom4'] = 'Libellé du champ personnalisé 4';
$LANG['config_userCustom4_comment'] = 'Libellé affiché dans le dialogue de profil.';
$LANG['config_userCustom5'] = 'Libellé du champ personnalisé 5';
$LANG['config_userCustom5_comment'] = 'Libellé affiché dans le dialogue de profil.';
$LANG['config_userManual'] = 'Manuel utilisateur';
$LANG['config_userManual_comment'] = 'URL de votre propre manuel utilisateur si vous en avez un. S\'affichera dans le menu Aide.';
$LANG['config_versionCompare'] = 'Comparaison de version';
$LANG['config_versionCompare_comment'] = 'Compare la version actuelle avec la dernière version disponible sur la page À propos. Nécessite un accès Internet.';
$LANG['config_welcomeText'] = 'Texte de la page de bienvenue';
$LANG['config_welcomeText_comment'] = 'Texte affiché sur la page d\'accueil.<br><i>Remarque : Vos modifications ne seront pas enregistrées si vous cliquez sur `Enregistrer` en mode code.</i>';

$LANG['config_clearCache'] = 'Effacer le cache';
$LANG['config_clearCache_comment'] = 'Cliquez sur ce bouton pour effacer le cache de l\'application. Cela peut être nécessaire en cas de problèmes d\'affichage.';
$LANG['config_clearCache_confirm'] = 'Êtes-vous sûr de vouloir effacer le cache de l\'application ?';
$LANG['config_alert_cache_cleared'] = 'Cache de l\'application effacé avec succès.';
$LANG['config_alert_cache_failed'] = 'L\'effacement du cache a échoué.';
