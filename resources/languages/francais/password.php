<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings French: Password
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['pwdreq_title'] = 'Réinitialisation du mot de passe';
$LANG['pwdreq_alert_failed'] = 'Veuillez renseigner une adresse e-mail valide.';
$LANG['pwdreq_alert_notfound'] = 'Utilisateur non trouvé';
$LANG['pwdreq_alert_notfound_text'] = 'Aucun compte utilisateur avec cette adresse e-mail n\'a pu être trouvé.';
$LANG['pwdreq_alert_success'] = 'Un e-mail avec les instructions de réinitialisation vous a été envoyé.';
$LANG['pwdreq_email'] = 'E-mail';
$LANG['pwdreq_email_comment'] = 'Saisissez l\'adresse e-mail de votre compte. Un mail avec des instructions pour réinitialiser votre mot de passe vous sera envoyé.';
$LANG['pwdreq_selectUser'] = 'Sélectionner l\'utilisateur';
$LANG['pwdreq_selectUser_comment'] = 'Plusieurs utilisateurs correspondent à cette adresse e-mail. Veuillez choisir le compte à réinitialiser.';

$LANG['password_rules_low'] = '<br>Le niveau de sécurité est "Faible" :<br>
      - Au moins 4 caractères<br>';
$LANG['password_rules_medium'] = '<br>Le niveau de sécurité est "Moyen" :<br>
      - Au moins 6 caractères<br>
      - Au moins 1 majuscule<br>
      - Au moins 1 minuscule<br>
      - Au moins 1 chiffre<br>';
$LANG['password_rules_high'] = '<br>Le niveau de sécurité est "Élevé" :<br>
      - Au moins 8 caractères<br>
      - Au moins 1 majuscule<br>
      - Au moins 1 minuscule<br>
      - Au moins 1 chiffre<br>
      - Au moins 1 caractère spécial<br>';
