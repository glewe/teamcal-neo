<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings Spanish: Login page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['login_login'] = 'Iniciar sesión';
$LANG['login_username'] = 'Usuario:';
$LANG['login_password'] = 'Contraseña:';
$LANG['login_authcode'] = 'Código de autenticador:';
$LANG['login_authcode_comment'] = 'En caso de haber configurado una autenticación de dos factores, ingrese su código de autenticador aquí.';
$LANG['login_error_0'] = 'Inicio de sesión exitoso';
$LANG['login_error_1'] = 'Falta usuario, contraseña o código de autenticador';
$LANG['login_error_1_text'] = 'Por favor, proporcione un usuario y contraseña válidos y, si es necesario, un código de autenticador válido.';
$LANG['login_error_2'] = 'Usuario desconocido';
$LANG['login_error_2_text'] = 'El nombre de usuario que ingresó es desconocido. Por favor, inténtelo de nuevo.';
$LANG['login_error_2fa'] = 'Código de autenticación incorrecto';
$LANG['login_error_2fa_text'] = 'Se ha configurado una autenticación de dos factores para su cuenta. El código de autenticación que ingresó no coincide.';
$LANG['login_error_3'] = 'Cuenta deshabilitada';
$LANG['login_error_3_text'] = 'Esta cuenta está bloqueada o no aprobada. Por favor, póngase en contacto con su administrador.';
$LANG['login_error_4'] = 'Contraseña incorrecta';
$LANG['login_error_4_text'] = 'Este fue el intento fallido número %1%. Después de %2% intentos fallidos, su cuenta será bloqueada por %3% segundos.';
$LANG['login_error_6_text'] = 'Esta cuenta está en espera debido a demasiados intentos de inicio de sesión fallidos. El período de gracia termina en %1% segundos.';
$LANG['login_error_7'] = 'Usuario o contraseña incorrectos';
$LANG['login_error_7_text'] = 'El nombre de usuario y/o la contraseña que ingresó no son correctos. Por favor, inténtelo de nuevo.';
$LANG['login_error_8'] = 'Cuenta no verificada';
$LANG['login_error_8_text'] = 'Su cuenta aún no ha sido verificada. Es posible que haya recibido un correo electrónico con un enlace de verificación.';
$LANG['login_error_90'] = 'Error de LDAP: Falta extensión';
$LANG['login_error_90_text'] = 'La extensión PHP LDAP no está cargada. Por favor habilítela en su configuración php.ini.';
$LANG['login_error_91'] = 'Error de LDAP: Falta la contraseña';
$LANG['login_error_92'] = 'Error de LDAP: Falló la autenticación/vinculación';
$LANG['login_error_92_text'] = 'La autenticación/vinculación de LDAP falló. Por favor, inténtelo de nuevo.';
$LANG['login_error_93'] = 'Error de LDAP: No se puede conectar al servidor LDAP';
$LANG['login_error_93_text'] = 'La conexión al servidor LDAP falló. Por favor, inténtelo de nuevo.';
$LANG['login_error_94'] = 'Error de LDAP: Error al iniciar TLS';
$LANG['login_error_94_text'] = 'El inicio de TLS de LDAP falló. Por favor, inténtelo de nuevo.';
$LANG['login_error_95'] = 'Error de LDAP: Usuario no encontrado';
$LANG['login_error_96'] = 'Error de LDAP: Error en la vinculación de búsqueda';
$LANG['login_error_96_text'] = 'La vinculación de búsqueda de LDAP falló. Por favor, inténtelo de nuevo.';

$LANG['logout_title'] = 'Cerrar sesión';
$LANG['logout_comment'] = 'Has cerrado la sesión.';
