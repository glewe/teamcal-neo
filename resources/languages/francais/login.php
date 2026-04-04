<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings French: Login page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['login_login'] = 'Connexion';
$LANG['login_username'] = 'Nom d\'utilisateur :';
$LANG['login_password'] = 'Mot de passe :';
$LANG['login_authcode'] = 'Code d\'authentification :';
$LANG['login_authcode_comment'] = 'Si vous avez configuré une authentification à deux facteurs, entrez votre code d\'authentification ici.';
$LANG['login_error_0'] = 'Connexion réussie';
$LANG['login_error_1'] = 'Nom d\'utilisateur, mot de passe ou code d\'authentification manquant';
$LANG['login_error_1_text'] = 'Veuillez fournir un nom d\'utilisateur et un mot de passe valides et, si nécessaire, un code d\'authentification valide.';
$LANG['login_error_2'] = 'Nom d\'utilisateur inconnu';
$LANG['login_error_2_text'] = 'Le nom d\'utilisateur que vous avez saisi est inconnu. Veuillez réessayer.';
$LANG['login_error_2fa'] = 'Code d\'authentification incorrect';
$LANG['login_error_2fa_text'] = 'Une authentification à deux facteurs a été configurée pour votre compte. Le code d\'authentification que vous avez saisi ne correspond pas.';
$LANG['login_error_3'] = 'Compte désactivé';
$LANG['login_error_3_text'] = 'Ce compte est verrouillé ou n\'est pas approuvé. Veuillez contacter votre administrateur.';
$LANG['login_error_4'] = 'Mot de passe incorrect';
$LANG['login_error_4_text'] = 'C\'était la tentative infructueuse numéro %1%. Après %2% tentatives infructueuses, votre compte sera verrouillé pendant %3% secondes.';
$LANG['login_error_6_text'] = 'Ce compte est en attente en raison d\'un trop grand nombre de tentatives de connexion échouées. Le délai de grâce se termine dans %1% secondes.';
$LANG['login_error_7'] = 'Nom d\'utilisateur ou mot de passe incorrect';
$LANG['login_error_7_text'] = 'Le nom d\'utilisateur et/ou le mot de passe que vous avez saisis ne sont pas corrects. Veuillez réessayer.';
$LANG['login_error_8'] = 'Compte non vérifié';
$LANG['login_error_8_text'] = 'Votre compte n\'est pas encore vérifié. Vous avez peut-être reçu un e-mail contenant un lien de vérification.';
$LANG['login_error_90'] = 'Erreur LDAP : Extension manquante';
$LANG['login_error_90_text'] = 'L\'extension PHP LDAP n\'est pas chargée. Veuillez l\'activer dans votre configuration php.ini.';
$LANG['login_error_91'] = 'Erreur LDAP : Mot de passe manquant';
$LANG['login_error_92'] = 'Erreur LDAP : Échec de l\'authentification/liaison';
$LANG['login_error_92_text'] = 'L\'authentification/liaison LDAP a échoué. Veuillez réessayer.';
$LANG['login_error_93'] = 'Erreur LDAP : Impossible de se connecter au serveur LDAP';
$LANG['login_error_93_text'] = 'La connexion au serveur LDAP a échoué. Veuillez réessayer.';
$LANG['login_error_94'] = 'Erreur LDAP : Échec de Start TLS';
$LANG['login_error_94_text'] = 'L\'opération Start TLS LDAP a échoué. Veuillez réessayer.';
$LANG['login_error_95'] = 'Erreur LDAP : Nom d\'utilisateur non trouvé';
$LANG['login_error_96'] = 'Erreur LDAP : Échec de Search bind';
$LANG['login_error_96_text'] = 'L\'opération Search bind LDAP a échoué. Veuillez réessayer.';

$LANG['logout_title'] = 'Déconnexion';
$LANG['logout_comment'] = 'Vous avez été déconnecté.';
