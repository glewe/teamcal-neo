<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings French: Alerts
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['alert_alert_title'] = 'ALERTE';
$LANG['alert_danger_title'] = 'ERREUR';
$LANG['alert_info_title'] = 'INFORMATION';
$LANG['alert_success_title'] = 'SUCCÈS';
$LANG['alert_warning_title'] = 'AVERTISSEMENT';
$LANG['alert_captcha_wrong'] = 'Code Captcha erroné';
$LANG['alert_captcha_wrong_text'] = 'Le code Captcha est incorrect. Veuillez réessayer.';
$LANG['alert_captcha_wrong_help'] = 'Le code Captcha doit être le résultat correct de la question mathématique.';
$LANG['alert_controller_not_found_subject'] = 'Contrôleur non trouvé';
$LANG['alert_controller_not_found_text'] = 'Le contrôleur "%1%" n\'a pas pu être trouvé.';
$LANG['alert_controller_not_found_help'] = 'Veuillez vérifier votre installation. Le fichier n\'existe pas ou vous n\'avez pas la permission de le lire.';
$LANG['alert_csrf_invalid_subject'] = 'Jeton de sécurité invalide';
$LANG['alert_csrf_invalid_text'] = 'La demande soumise à l\'application ne comportait pas de jeton de sécurité ou celui-ci était invalide.';
$LANG['alert_csrf_invalid_help'] = 'Veuillez recharger la page et réessayer. Si le problème persiste, veuillez contacter votre administrateur.';
$LANG['alert_decl_allowmonth_reached'] = 'Le montant maximum de %1% par mois pour ce type d\'absence est dépassé.';
$LANG['alert_decl_allowweek_reached'] = 'Le montant maximum de %1% par semaine pour ce type d\'absence est dépassé.';
$LANG['alert_decl_allowyear_reached'] = 'Le montant maximum de %1% par an pour ce type d\'absence est dépassé.';
$LANG['alert_decl_approval_required'] = 'Ce type d\'absence nécessite une approbation. Elle a été inscrite dans votre calendrier mais une note de jour a été ajoutée pour indiquer qu\'elle n\'est pas encore approuvée. Votre responsable a été informé par e-mail.';
$LANG['alert_decl_approval_required_daynote'] = 'Cette absence a été demandée mais n\'est pas encore approuvée.';
$LANG['alert_decl_before_date'] = 'Les modifications d\'absence avant la date suivante ne sont pas autorisées : ';
$LANG['alert_decl_group_minpresent'] = 'Seuil de présence minimale de groupe atteint pour le(s) groupe(s) : ';
$LANG['alert_decl_group_maxabsent'] = 'Seuil d\'absence maximale de groupe atteint pour le(s) groupe(s) : ';
$LANG['alert_decl_group_threshold'] = 'Seuil d\'absence de groupe atteint pour votre/vos groupe(s) : ';
$LANG['alert_decl_holiday_noabsence'] = 'Ce jour est un jour férié qui ne permet pas d\'absences.';
$LANG['alert_decl_period'] = 'Les modifications d\'absence durant la période suivante ne sont pas autorisées : ';
$LANG['alert_decl_takeover'] = 'Le type d\'absence \'%s\' n\'est pas activé pour la reprise.';
$LANG['alert_decl_total_threshold'] = 'Seuil d\'absence totale atteint.';
$LANG['alert_imp_subject'] = 'Erreurs rencontrées lors de l\'importation CSV';
$LANG['alert_imp_admin'] = 'Ligne %s : Le nom d\'utilisateur "admin" ne peut pas être importé.';
$LANG['alert_imp_columns'] = 'Ligne %s : Il y a moins ou plus de %s colonnes.';
$LANG['alert_imp_email'] = 'Ligne %s : "%s" n\'est pas une adresse e-mail valide.';
$LANG['alert_imp_exists'] = 'Ligne %s : Le nom d\'utilisateur "%s" existe déjà.';
$LANG['alert_imp_firstname'] = 'Ligne %s : Le prénom "%s" ne respecte pas le format autorisé (caractères alphanumériques, espace, point, tiret et souligné).';
$LANG['alert_imp_gender'] = 'Ligne %s : Genre incorrect "%s" (male ou female).';
$LANG['alert_imp_lastname'] = 'Ligne %s : Le nom "%s" ne respecte pas le format autorisé (caractères alphanumériques, espace, point, tiret et souligné).';
$LANG['alert_imp_username'] = 'Ligne %s : Le nom d\'utilisateur "%s" ne respecte pas le format autorisé (caractères alphanumériques, point et @).';
$LANG['alert_input'] = 'Échec de la validation de la saisie';
$LANG['alert_input_alpha'] = 'Ce champ n\'autorise que les caractères alphabétiques.';
$LANG['alert_input_alpha_numeric'] = 'Ce champ n\'autorise que les caractères alphanumériques.';
$LANG['alert_input_alpha_numeric_dash'] = 'Ce champ n\'autorise que les caractères alphanumériques ainsi que le tiret et le souligné.';
$LANG['alert_input_alpha_numeric_dash_blank'] = 'Ce champ n\'autorise que les caractères alphanumériques ainsi que l\'espace, le tiret et le souligné.';
$LANG['alert_input_alpha_numeric_dash_blank_dot'] = 'Ce champ n\'autorise que les caractères alphanumériques ainsi que l\'espace, le point, le tiret et le souligné.';
$LANG['alert_input_alpha_numeric_dash_blank_special'] = 'Ce champ n\'autorise que les caractères alphanumériques ainsi que l\'espace, le tiret, le souligné et les caractères spéciaux \'!@#$%^&*().';
$LANG['alert_input_ctype_graph'] = 'Ce champ n\'autorise que les caractères imprimables.';
$LANG['alert_input_date'] = 'La date doit être au format ISO 8601, par ex. 2014-01-01.';
$LANG['alert_input_email'] = 'L\'adresse e-mail est invalide.';
$LANG['alert_input_equal'] = 'La valeur de ce champ doit être la même que dans le champ "%s".';
$LANG['alert_input_equal_string'] = 'La chaîne de caractères dans ce champ doit être "%s".';
$LANG['alert_input_exact_length'] = 'La saisie de ce champ doit comporter exactement "%s" caractères.';
$LANG['alert_input_greater_than'] = 'La valeur de ce champ doit être supérieure à celle du champ "%s".';
$LANG['alert_input_hex_color'] = 'Ce champ n\'autorise qu\'un code couleur hexadécimal à six caractères, par ex. FF5733.';
$LANG['alert_input_hexadecimal'] = 'Ce champ n\'autorise que les caractères hexadécimaux.';
$LANG['alert_input_ip_address'] = 'La saisie de ce champ n\'est pas une adresse IP valide.';
$LANG['alert_input_less_than'] = 'La valeur de ce champ doit être inférieure à celle du champ "%s".';
$LANG['alert_input_match'] = 'Le champ "%s" doit correspondre au champ "%s".';
$LANG['alert_input_max_length'] = 'La saisie de ce champ peut avoir un maximum de "%s" caractères.';
$LANG['alert_input_min_length'] = 'La saisie de ce champ doit avoir un minimum de "%s" caractères.';
$LANG['alert_input_numeric'] = 'La saisie de ce champ doit être numérique.';
$LANG['alert_input_phone_number'] = 'La saisie de ce champ doit être un numéro de téléphone valide, par ex. (555) 123 4567 ou +49 172 123 4567.';
$LANG['alert_input_pwdlow'] = 'Le mot de passe doit comporter au moins 4 caractères et peut contenir des lettres minuscules et majuscules, des chiffres et les caractères spéciaux suivants : !@#$%^&*().';
$LANG['alert_input_pwdmedium'] = 'Le mot de passe doit comporter au moins 6 caractères, contenir au moins une lettre minuscule, au moins une lettre majuscule et au moins un chiffre. Sont autorisés les lettres minuscules et majuscules, les chiffres et les caractères spéciaux suivants : !@#$%^&*().';
$LANG['alert_input_pwdhigh'] = 'Le mot de passe doit comporter au moins 8 caractères, contenir au moins une lettre minuscule, au moins une lettre majuscule, au moins un chiffre et au moins un caractère spécial. Sont autorisés les lettres minuscules et majuscules, les chiffres et les caractères spéciaux suivants : !@#$%^&*().';
$LANG['alert_input_regex_match'] = 'La saisie de ce champ ne correspond pas à l\'expression régulière "%s".';
$LANG['alert_input_required'] = 'Ce champ est obligatoire.';
$LANG['alert_input_username'] = 'Ce champ autorise les caractères alphanumériques, le tiret, le souligné, le point et le signe @.';
$LANG['alert_input_validation_subject'] = 'Validation de la saisie';
$LANG['alert_license_subject'] = 'Gestion des licences';
$LANG['alert_maintenance_subject'] = 'Site en maintenance';
$LANG['alert_maintenance_text'] = 'Le site est actuellement en mode "En maintenance". Les utilisateurs réguliers ne pourront utiliser aucune fonctionnalité.';
$LANG['alert_maintenance_help'] = 'En tant qu\'administrateur, vous pouvez réactiver le site sous Administration -> Paramètres système -> Système.';
$LANG['alert_no_data_subject'] = 'Données invalides';
$LANG['alert_no_data_text'] = 'Cette opération a été demandée avec des données invalides ou insuffisantes.';
$LANG['alert_no_data_help'] = 'L\'opération a échoué en raison de données manquantes ou invalides.';
$LANG['alert_not_allowed_subject'] = 'Accès non autorisé';
$LANG['alert_not_allowed_text'] = 'Vous n\'avez pas la permission d\'accéder à cette page ou à cette fonctionnalité.';
$LANG['alert_not_allowed_help'] = 'Si vous n\'êtes pas connecté, l\'accès public à cette page n\'est pas autorisé. Si vous êtes connecté, le rôle de votre compte ne vous permet pas de consulter cette page.';
$LANG['alert_not_enabled_subject'] = 'Fonctionnalité non activée';
$LANG['alert_not_enabled_text'] = 'Cette fonctionnalité n\'est actuellement pas activée.';
$LANG['alert_perm_invalid'] = 'Le nouveau nom du schéma de permissions "%1%" est invalide. Choisissez des caractères majuscules ou minuscules ou des chiffres de 0 à 9. N\'utilisez pas d\'espaces.';
$LANG['alert_perm_exists'] = 'Le schéma de permissions "%1%" existe déjà. Utilisez un nom différent ou supprimez d\'abord l\'ancien.';
$LANG['alert_perm_default'] = 'Le schéma de permissions "Default" ne peut pas être réinitialisé sur lui-même.';
$LANG['alert_pwdTokenExpired_subject'] = 'Jeton expiré';
$LANG['alert_pwdTokenExpired_text'] = 'Le jeton de réinitialisation de votre mot de passe était valide pendant 24 heures et a expiré.';
$LANG['alert_pwdTokenExpired_help'] = 'Allez à l\'écran de connexion et demandez-en un nouveau.';
$LANG['alert_reg_subject'] = 'Enregistrement de l\'utilisateur';
$LANG['alert_reg_approval_needed'] = 'Votre vérification a réussi. Cependant, votre compte doit être définitivement activé par un administrateur. Un e-mail lui a été envoyé.';
$LANG['alert_reg_success'] = 'Votre vérification a réussi. Vous pouvez maintenant vous connecter et utiliser l\'application.';
$LANG['alert_reg_mismatch'] = 'Le code de vérification soumis ne correspond pas à celui que nous avons enregistré. Un e-mail a été envoyé à l\'administrateur pour examiner votre cas.';
$LANG['alert_reg_no_user'] = 'Le nom d\'utilisateur est introuvable. Êtes-vous sûr qu\'il a été enregistré ?';
$LANG['alert_reg_no_vcode'] = 'Un code de vérification n\'a pas pu être trouvé. A-t-il déjà été vérifié ? Veuillez contacter l\'administrateur pour vérifier les paramètres de votre compte.';
$LANG['alert_secret_exists_subject'] = 'Authentification à deux facteurs déjà configurée';
$LANG['alert_secret_exists_text'] = 'Une authentification à deux facteurs a déjà été configurée pour votre compte.';
$LANG['alert_secret_exists_help'] = 'Pour des raisons de sécurité, vous ne pouvez pas la supprimer ou la réinitialiser vous-même. Veuillez contacter l\'administrateur pour qu\'il le fasse pour vous afin que vous puissiez commencer un nouveau processus d\'inscription.';
$LANG['alert_upl_csv_subject'] = 'Téléversement de fichier CSV';
$LANG['alert_upl_doc_subject'] = 'Téléversement de documents';
$LANG['alert_upl_img_subject'] = 'Téléversement d\'images';
