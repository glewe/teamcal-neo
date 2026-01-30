<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings Spanish: Alerts
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['alert_alert_title'] = 'ALERTA';
$LANG['alert_danger_title'] = 'ERROR';
$LANG['alert_info_title'] = 'INFORMACIÓN';
$LANG['alert_success_title'] = 'ÉXITO';
$LANG['alert_warning_title'] = 'ADVERTENCIA';
$LANG['alert_captcha_wrong'] = 'Código Captcha incorrecto';
$LANG['alert_captcha_wrong_text'] = 'El código Captcha fue incorrecto. Por favor, inténtelo de nuevo.';
$LANG['alert_captcha_wrong_help'] = 'El código Captcha debe ser el resultado correcto de la pregunta matemática.';
$LANG['alert_controller_not_found_subject'] = 'Controlador no encontrado';
$LANG['alert_controller_not_found_text'] = 'No se pudo encontrar el controlador "%1%".';
$LANG['alert_controller_not_found_help'] = 'Por favor, compruebe su instalación. El archivo no existe o puede que no tenga permiso para leerlo.';
$LANG['alert_csrf_invalid_subject'] = 'Token de seguridad no válido';
$LANG['alert_csrf_invalid_text'] = 'La solicitud enviada a la aplicación tenía un token de seguridad faltante o no válido.';
$LANG['alert_csrf_invalid_help'] = 'Por favor, recargue la página e inténtelo de nuevo. Si el problema persiste, póngase en contacto con su administrador.';
$LANG['alert_decl_allowmonth_reached'] = 'Se ha superado la cantidad máxima de %1% al mes para este tipo de ausencia.';
$LANG['alert_decl_allowweek_reached'] = 'Se ha superado la cantidad máxima de %1% a la semana para este tipo de ausencia.';
$LANG['alert_decl_allowyear_reached'] = 'Se ha superado la cantidad máxima de %1% al año para este tipo de ausencia.';
$LANG['alert_decl_approval_required'] = 'Este tipo de ausencia requiere aprobación. Se ha ingresado en su calendario pero se ha añadido una nota diaria para indicar que aún no ha sido aprobada. Su gerente ha sido informado por correo.';
$LANG['alert_decl_approval_required_daynote'] = 'Esta ausencia fue solicitada pero aún no ha sido aprobada.';
$LANG['alert_decl_before_date'] = 'No se permiten cambios de ausencia antes de la siguiente fecha: ';
$LANG['alert_decl_group_minpresent'] = 'Umbral de presencia mínima del grupo alcanzado para el/los grupo(s): ';
$LANG['alert_decl_group_maxabsent'] = 'Umbral de ausencia máxima del grupo alcanzado para el/los grupo(s): ';
$LANG['alert_decl_group_threshold'] = 'Umbral de ausencia del grupo alcanzado para su(s) grupo(s): ';
$LANG['alert_decl_holiday_noabsence'] = 'Este día es un festivo que no permite ausencias.';
$LANG['alert_decl_period'] = 'No se permiten cambios de ausencia en el siguiente período: ';
$LANG['alert_decl_takeover'] = 'El tipo de ausencia \'%s\' no está habilitado para la toma de posesión.';
$LANG['alert_decl_total_threshold'] = 'Umbral de ausencia total alcanzado.';
$LANG['alert_imp_subject'] = 'Se encontraron errores de importación CSV';
$LANG['alert_imp_admin'] = 'Línea %s: El nombre de usuario "admin" no está permitido ser importado.';
$LANG['alert_imp_columns'] = 'Línea %s: Hay menos o más de %s columnas.';
$LANG['alert_imp_email'] = 'Línea %s: "%s" no es una dirección de correo electrónico válida.';
$LANG['alert_imp_exists'] = 'Línea %s: El nombre de usuario "%s" ya existe.';
$LANG['alert_imp_firstname'] = 'Línea %s: El nombre "%s" no cumple con el formato permitido (caracteres alfanuméricos, espacio, punto, guion y guion bajo).';
$LANG['alert_imp_gender'] = 'Línea %s: Género incorrecto "%s" (male o female).';
$LANG['alert_imp_lastname'] = 'Línea %s: El apellido "%s" no cumple con el formato permitido (caracteres alfanuméricos, espacio, punto, guion y guion bajo).';
$LANG['alert_imp_username'] = 'Línea %s: El nombre de usuario "%s" no cumple con el formato permitido (caracteres alfanuméricos, punto y @).';
$LANG['alert_input'] = 'Falló la validación de entrada';
$LANG['alert_input_alpha'] = 'Este campo solo permite caracteres alfabéticos.';
$LANG['alert_input_alpha_numeric'] = 'Este campo solo permite caracteres alfanuméricos.';
$LANG['alert_input_alpha_numeric_dash'] = 'Este campo solo permite caracteres alfanuméricos además de guion y guion bajo.';
$LANG['alert_input_alpha_numeric_dash_blank'] = 'Este campo solo permite caracteres alfanuméricos además de espacio, guion y guion bajo.';
$LANG['alert_input_alpha_numeric_dash_blank_dot'] = 'Este campo solo permite caracteres alfanuméricos además de espacio, punto, guion y guion bajo.';
$LANG['alert_input_alpha_numeric_dash_blank_special'] = 'Este campo solo permite caracteres alfanuméricos además de espacio, guion, guion bajo y los caracteres especiales \'!@#$%^&*().';
$LANG['alert_input_ctype_graph'] = 'Este campo solo permite caracteres imprimibles.';
$LANG['alert_input_date'] = 'La fecha debe estar en formato ISO 8601, por ejemplo, 2014-01-01.';
$LANG['alert_input_email'] = 'La dirección de correo electrónico no es válida.';
$LANG['alert_input_equal'] = 'El valor de este campo debe ser el mismo que en el campo "%s".';
$LANG['alert_input_equal_string'] = 'La cadena en este campo debe ser "%s".';
$LANG['alert_input_exact_length'] = 'La entrada de este campo debe tener exactamente "%s" caracteres.';
$LANG['alert_input_greater_than'] = 'El valor de este campo debe ser mayor que el campo "%s".';
$LANG['alert_input_hex_color'] = 'Este campo solo permite un código de color hexadecimal de seis caracteres, por ejemplo, FF5733.';
$LANG['alert_input_hexadecimal'] = 'Este campo solo permite caracteres hexadecimales.';
$LANG['alert_input_ip_address'] = 'La entrada de este campo no es una dirección IP válida.';
$LANG['alert_input_less_than'] = 'El valor de este campo debe ser menor que el campo "%s".';
$LANG['alert_input_match'] = 'El campo "%s" debe coincidir con el campo "%s".';
$LANG['alert_input_max_length'] = 'La entrada de este campo puede tener un máximo de "%s" caracteres.';
$LANG['alert_input_min_length'] = 'La entrada de este campo debe tener un mínimo de "%s" caracteres.';
$LANG['alert_input_numeric'] = 'La entrada de este campo debe ser numérica.';
$LANG['alert_input_phone_number'] = 'La entrada en este campo debe ser un número de teléfono válido, por ejemplo, (555) 123 4567 o +34 912 123 456.';
$LANG['alert_input_pwdlow'] = 'La contraseña debe tener al menos 4 caracteres y puede contener letras minúsculas y mayúsculas, números y los siguientes caracteres especiales: !@#$%^&*().';
$LANG['alert_input_pwdmedium'] = 'La contraseña debe tener al menos 6 caracteres, debe contener al menos una letra minúscula, al menos una letra mayúscula y al menos un número. Se permiten letras minúsculas y mayúsculas, números y los siguientes caracteres especiales: !@#$%^&*().';
$LANG['alert_input_pwdhigh'] = 'La contraseña debe tener al menos 8 caracteres, debe contener al menos una letra minúscula, al menos una letra mayúscula, al menos un número y al menos un carácter especial. Se permiten letras minúsculas y mayúsculas, números y los siguientes caracteres especiales: !@#$%^&*().';
$LANG['alert_input_regex_match'] = 'La entrada de este campo no coincidió con la expresión regular "%s".';
$LANG['alert_input_required'] = 'Este campo es obligatorio.';
$LANG['alert_input_username'] = 'Este campo permite caracteres alfanuméricos, guion, guion bajo, punto y el signo @.';
$LANG['alert_input_validation_subject'] = 'Validación de entrada';
$LANG['alert_license_subject'] = 'Gestión de Licencias';
$LANG['alert_maintenance_subject'] = 'Sitio en Mantenimiento';
$LANG['alert_maintenance_text'] = 'El sitio está configurado actualmente como "En mantenimiento". Los usuarios normales no podrán utilizar ninguna función.';
$LANG['alert_maintenance_help'] = 'Como administrador, puede volver a activar el sitio en Administración -> Ajustes del Sistema -> Sistema.';
$LANG['alert_no_data_subject'] = 'Datos inválidos';
$LANG['alert_no_data_text'] = 'Esta operación fue solicitada con datos inválidos o insuficientes.';
$LANG['alert_no_data_help'] = 'La operación falló debido a datos faltantes o inválidos.';
$LANG['alert_not_allowed_subject'] = 'Acceso no permitido';
$LANG['alert_not_allowed_text'] = 'No tiene permiso para acceder a esta página o función.';
$LANG['alert_not_allowed_help'] = 'Si no ha iniciado sesión, entonces el acceso público a esta página no está permitido. Si ha iniciado sesión, el rol de su cuenta no tiene permitido ver esta página.';
$LANG['alert_not_enabled_subject'] = 'Función no habilitada';
$LANG['alert_not_enabled_text'] = 'Esta función no está habilitada actualmente.';
$LANG['alert_perm_invalid'] = 'El nuevo nombre de esquema de permisos "%1%" no es válido. Elija caracteres en mayúsculas o minúsculas o números del 0 al 9. No use espacios.';
$LANG['alert_perm_exists'] = 'El esquema de permisos "%1%" ya existe. Use un nombre diferente o elimine el antiguo primero.';
$LANG['alert_perm_default'] = 'El esquema de permisos "Default" no puede ser restablecido a sí mismo.';
$LANG['alert_pwdTokenExpired_subject'] = 'Token Expirado';
$LANG['alert_pwdTokenExpired_text'] = 'El token para restablecer su contraseña fue válido durante 24 horas y ha expirado.';
$LANG['alert_pwdTokenExpired_help'] = 'Vaya a la pantalla de inicio de sesión y solicite uno nuevo.';
$LANG['alert_reg_subject'] = 'Registro de Usuario';
$LANG['alert_reg_approval_needed'] = 'Su verificación fue exitosa. Sin embargo, su cuenta debe ser activada finalmente por un administrador. Se le ha enviado un correo.';
$LANG['alert_reg_success'] = 'Su verificación fue exitosa. Ahora puede iniciar sesión y usar la aplicación.';
$LANG['alert_reg_mismatch'] = 'El código de verificación enviado no coincide con el que tenemos registrado. Se ha enviado un correo al administrador para revisar su caso.';
$LANG['alert_reg_no_user'] = 'No se pudo encontrar el nombre de usuario. ¿Está seguro de que fue registrado?';
$LANG['alert_reg_no_vcode'] = 'No se pudo encontrar un código de verificación. ¿Ya fue verificado? Por favor, póngase en contacto con el administrador para comprobar la configuración de su cuenta.';
$LANG['alert_secret_exists_subject'] = 'Autenticación de dos factores ya configurada';
$LANG['alert_secret_exists_text'] = 'Ya se ha configurado una autenticación de dos factores para su cuenta.';
$LANG['alert_secret_exists_help'] = 'Por razones de seguridad, no puede eliminarla o restablecerla usted mismo. Por favor, póngase en contacto con el administrador para que lo haga por usted y pueda iniciar un nuevo proceso de incorporación.';
$LANG['alert_upl_csv_subject'] = 'Subir archivo CSV';
$LANG['alert_upl_doc_subject'] = 'Subir documentos';
$LANG['alert_upl_img_subject'] = 'Subir imágenes';
