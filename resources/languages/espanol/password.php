<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings Spanish: Password
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['pwdreq_title'] = 'Restablecer Contraseña';
$LANG['pwdreq_alert_failed'] = 'Proporcione una dirección de correo electrónico válida.';
$LANG['pwdreq_alert_notfound'] = 'Usuario no encontrado';
$LANG['pwdreq_alert_notfound_text'] = 'No se encontró ninguna cuenta de usuario con esta dirección de correo.';
$LANG['pwdreq_alert_success'] = 'Se envió un correo electrónico con instrucciones para restablecer la contraseña.';
$LANG['pwdreq_email'] = 'Correo electrónico';
$LANG['pwdreq_email_comment'] = 'Ingrese el correo de su cuenta para enviarle las instrucciones.';
$LANG['pwdreq_selectUser'] = 'Seleccionar usuario';
$LANG['pwdreq_selectUser_comment'] = 'Se han encontrado varios usuarios con este correo. Seleccione el usuario deseado.';

$LANG['password_rules_low'] = '<br>La fortaleza de la contraseña está configurada como "baja":<br>
      - Al menos 4 caracteres<br>';
$LANG['password_rules_medium'] = '<br>La fortaleza de la contraseña está configurada como "media":<br>
      - Al menos 6 caracteres<br>
      - Al menos 1 letra mayúscula<br>
      - Al menos 1 letra minúscula<br>
      - Al menos 1 número<br>';
$LANG['password_rules_high'] = '<br>La fortaleza de la contraseña está configurada como "alta":<br>
      - Al menos 8 caracteres<br>
      - Al menos 1 letra mayúscula<br>
      - Al menos 1 letra minúscula<br>
      - Al menos 1 número<br>
      - Al menos 1 carácter especial<br>';
