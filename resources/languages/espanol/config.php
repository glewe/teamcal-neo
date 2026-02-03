<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings Spanish: System Settings page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['config_title'] = 'Ajustes del Sistema';

$LANG['config_tab_email'] = 'Correo electrónico';
$LANG['config_tab_footer'] = 'Pie de página';
$LANG['config_tab_homepage'] = 'Página de inicio';
$LANG['config_tab_images'] = 'Imágenes';
$LANG['config_tab_license'] = 'Licencia';
$LANG['config_tab_login'] = 'Inicio de sesión';
$LANG['config_tab_registration'] = 'Registro';
$LANG['config_tab_system'] = 'Sistema';
$LANG['config_tab_theme'] = 'Tema';
$LANG['config_tab_user'] = 'Usuario';
$LANG['config_tab_gdpr'] = 'RGPD';

$LANG['config_activateMessages'] = 'Activar Centro de Mensajes';
$LANG['config_activateMessages_comment'] = 'Esta opción activará el Centro de Mensajes. Los usuarios pueden usarlo para enviar anuncios o correos electrónicos a otros usuarios o grupos.';
$LANG['config_adminApproval'] = 'Requerir aprobación del administrador';
$LANG['config_adminApproval_comment'] = 'El administrador recibirá un correo electrónico sobre cada registro de usuario. Debe confirmar manualmente la cuenta.';
$LANG['config_alert_edit_success'] = 'La configuración fue actualizada. Para que algunos cambios surtan efecto, es posible que deba refrescar la página.';
$LANG['config_alert_failed'] = 'La configuración no pudo ser actualizada. Por favor, verifique su entrada.';
$LANG['config_alertAutocloseDanger'] = 'Cerrar alertas de error automáticamente';
$LANG['config_alertAutocloseDanger_comment'] = 'Seleccione si las alertas de error se cerrarán automáticamente después de la cantidad de milisegundos especificada a continuación.';
$LANG['config_alertAutocloseDelay'] = 'Retraso de cierre automático de alerta';
$LANG['config_alertAutocloseDelay_comment'] = 'Ingrese la cantidad de milisegundos después de los cuales las alertas de los tipos seleccionados anteriormente se cerrarán automáticamente (ej. 4000 = 4 segundos).';
$LANG['config_alertAutocloseSuccess'] = 'Cerrar alertas de éxito automáticamente';
$LANG['config_alertAutocloseSuccess_comment'] = 'Seleccione si las alertas de éxito se cerrarán automáticamente después de la cantidad de milisegundos especificada a continuación.';
$LANG['config_alertAutocloseWarning'] = 'Cerrar alertas de advertencia automáticamente';
$LANG['config_alertAutocloseWarning_comment'] = 'Seleccione si las alertas de advertencia se cerrarán automáticamente después de la cantidad de milisegundos especificada a continuación.';
$LANG['config_allowRegistration'] = 'Permitir autoregistro de usuarios';
$LANG['config_allowRegistration_comment'] = 'Permitir que los usuarios registren sus propias cuentas.';
$LANG['config_appDescription'] = 'Descripción HTML';
$LANG['config_appDescription_comment'] = 'Ingrese una descripción de la aplicación aquí. Se utilizará en el encabezado HTML para los motores de búsqueda.';
$LANG['config_appKeywords'] = 'Palabras clave HTML';
$LANG['config_appKeywords_comment'] = 'Ingrese algunas palabras clave aquí. Se utilizarán en el encabezado HTML para los motores de búsqueda.';
$LANG['config_appTitle'] = 'Nombre de la aplicación';
$LANG['config_appTitle_comment'] = 'Ingrese un título para la aplicación aquí. Se utiliza en varios lugares, ej. el encabezado HTML, menú y otras páginas.';
$LANG['config_appURL'] = 'URL de la aplicación';
$LANG['config_appURL_comment'] = 'Ingrese la URL completa de la aplicación aquí. Se usará en los correos de notificación.';
$LANG['config_badLogins'] = 'Inicios de sesión fallidos';
$LANG['config_badLogins_comment'] = 'Número de intentos de inicio de sesión fallidos que harán que el estado del usuario sea BLOQUEADO.';
$LANG['config_cookieConsent'] = 'Consentimiento de cookies';
$LANG['config_cookieConsent_comment'] = 'Con esta opción, aparecerá una confirmación de consentimiento de cookies en la parte inferior de la pantalla.';
$LANG['config_cookieConsentCDN'] = 'CDN de consentimiento de cookies';
$LANG['config_cookieConsentCDN_comment'] = 'Los CDN pueden ofrecer un beneficio de rendimiento al hospedar módulos web populares en servidores repartidos por todo el mundo.';
$LANG['config_cookieLifetime'] = 'Vida útil de la cookie';
$LANG['config_cookieLifetime_comment'] = 'Tras un inicio de sesión exitoso, se almacena una cookie. Esta cookie tiene una vida útil tras la cual se vuelve inválida.';
$LANG['config_defaultHomepage'] = 'Página de inicio predeterminada';
$LANG['config_defaultHomepage_comment'] = 'Seleccione la página de inicio predeterminada.';
$LANG['config_defaultHomepage_home'] = 'Página de Bienvenida';
$LANG['config_defaultHomepage_calendarview'] = 'Calendario';
$LANG['config_defaultLanguage'] = 'Idioma predeterminado';
$LANG['config_defaultLanguage_comment'] = 'Elija el idioma predeterminado de su instalación aquí.';
$LANG['config_defaultMenu'] = 'Posición del menú';
$LANG['config_defaultMenu_comment'] = 'El menú de TeamCal Neo puede mostrarse horizontalmente en la parte superior o verticalmente a la izquierda.';
$LANG['config_defaultMenu_navbar'] = 'Horizontal superior';
$LANG['config_defaultMenu_sidebar'] = 'Vertical izquierda';
$LANG['config_disableTfa'] = 'Desactivar autenticación de dos factores';
$LANG['config_disableTfa_comment'] = 'Desactiva la función de autenticación de dos factores para todos los usuarios.';
$LANG['config_emailConfirmation'] = 'Requerir confirmación por correo';
$LANG['config_emailConfirmation_comment'] = 'Al registrarse, el usuario recibirá un correo con un enlace de confirmación.';
$LANG['config_emailNotifications'] = 'Notificaciones por correo';
$LANG['config_emailNotifications_comment'] = 'Activar/Desactivar las notificaciones por correo electrónico.';
$LANG['config_faCDN'] = 'Fontawesome CDN';
$LANG['config_faCDN_comment'] = 'Uso de CDN para Fontawesome.';
$LANG['config_font'] = 'Fuente';
$LANG['config_font_comment'] = 'Seleccione la fuente que desea usar.';
$LANG['config_footerCopyright'] = 'Nombre de derechos de autor del pie de página';
$LANG['config_footerCopyright_comment'] = 'Se mostrará en la sección superior izquierda del pie de página.';
$LANG['config_footerCopyrightUrl'] = 'URL de derechos de autor del pie de página';
$LANG['config_footerCopyrightUrl_comment'] = 'Ingrese la URL a la que enlazará el nombre de derechos de autor.';
$LANG['config_footerSocialLinks'] = 'Enlaces sociales';
$LANG['config_footerSocialLinks_comment'] = 'Ingrese las URLs de redes sociales separadas por punto y coma.';
$LANG['config_forceTfa'] = 'Forzar autenticación de dos factores';
$LANG['config_forceTfa_comment'] = 'Obliga a los usuarios a configurar una autenticación de dos factores.';

$LANG['config_gdprController'] = 'Responsable';
$LANG['config_gdprController_comment'] = 'Información sobre el responsable del tratamiento de datos según el RGPD.';
$LANG['config_gdprOfficer'] = 'Delegado de Protección de Datos';
$LANG['config_gdprOfficer_comment'] = 'Nombre del delegado de protección de datos.';
$LANG['config_gdprOrganization'] = 'Organización';
$LANG['config_gdprOrganization_comment'] = 'Nombre de la organización o empresa.';
$LANG['config_gdprPlatforms'] = 'Políticas de plataforma';
$LANG['config_gdprPlatforms_comment'] = 'Marque las plataformas que desea incluir en la política de privacidad.';
$LANG['config_gdprPolicyPage'] = 'Política de privacidad de datos';
$LANG['config_gdprPolicyPage_comment'] = 'Marque para añadir una página de política de privacidad al menú de Ayuda.';

$LANG['config_googleAnalytics'] = 'Google Analytics';
$LANG['config_googleAnalytics_comment'] = 'TeamCal Neo soporta Google Analytics.';
$LANG['config_googleAnalyticsID'] = 'ID de Google Analytics (GA4)';
$LANG['config_googleAnalyticsID_comment'] = 'Ingrese su ID de Google Analytics GA4 en el formato G-... .';
$LANG['config_gracePeriod'] = 'Período de gracia';
$LANG['config_gracePeriod_comment'] = 'El tiempo en segundos que un usuario debe esperar tras demasiados intentos de inicio de sesión fallidos.';
$LANG['config_homepage'] = 'Página de inicio del usuario';
$LANG['config_homepage_comment'] = 'Seleccione qué página mostrar a los usuarios registrados tras el inicio de sesión.';
$LANG['config_homepage_calendarview'] = 'Calendario';
$LANG['config_homepage_home'] = 'Página de Bienvenida';
$LANG['config_homepage_messages'] = 'Página de Mensajes';

$LANG['config_jQueryCDN'] = 'jQuery CDN';
$LANG['config_jQueryCDN_comment'] = 'Uso de CDN para jQuery.';
$LANG['config_jqtheme'] = 'Tema de jQuery UI';
$LANG['config_jqtheme_comment'] = 'TeamCal Neo utiliza jQuery UI para cuadros de diálogo y otras funciones.';
$LANG['config_jqthemeSample'] = 'Muestra del tema jQuery UI';
$LANG['config_jqthemeSample_comment'] = 'Pruebe este selector de fecha para ver el tema actual.';

$LANG['config_licActivate'] = 'Activar licencia';
$LANG['config_licActivate_comment'] = 'Su licencia aún no está activa. Por favor, actívela.';
$LANG['config_licExpiryWarning'] = 'Advertencia de expiración de licencia';
$LANG['config_licExpiryWarning_comment'] = 'Ingrese el número de días antes de la expiración para mostrar una alerta.';
$LANG['config_licKey'] = 'Clave de licencia';
$LANG['config_licKey_comment'] = 'Ingrese su clave de licencia aquí.';
$LANG['config_licRegister'] = 'Registrar dominio';
$LANG['config_licRegister_comment'] = 'Registrar este dominio con la clave de licencia dada.';
$LANG['config_licDeregister'] = 'Eliminar registro de dominio';
$LANG['config_licDeregister_comment'] = 'Puede eliminar el registro de este dominio de su licencia.';

$LANG['config_logLanguage'] = 'Idioma del log';
$LANG['config_logLanguage_comment'] = 'Idioma para las entradas del log del sistema.';
$LANG['config_mailFrom'] = 'Remitente del correo';
$LANG['config_mailFrom_comment'] = 'Nombre que se mostrará como remitente de los correos.';
$LANG['config_mailReply'] = 'Correo para respuestas';
$LANG['config_mailReply_comment'] = 'Dirección de correo electrónico para las respuestas.';
$LANG['config_mailSMTP'] = 'Soporte servidor SMTP externo';
$LANG['config_mailSMTP_comment'] = 'Usar un servidor SMTP externo en lugar de la función mail() de PHP.';

$LANG['config_mailSMTPAnonymous'] = 'SMTP Anónimo';
$LANG['config_mailSMTPAnonymous_comment'] = 'Usar conexión SMTP sin autenticación.';
$LANG['config_mailSMTPhost'] = 'Host SMTP';
$LANG['config_mailSMTPhost_comment'] = 'Nombre del host SMTP.';
$LANG['config_mailSMTPport'] = 'Puerto SMTP';
$LANG['config_mailSMTPport_comment'] = 'Puerto del host SMTP.';
$LANG['config_mailSMTPusername'] = 'Usuario SMTP';
$LANG['config_mailSMTPusername_comment'] = 'Nombre de usuario SMTP.';
$LANG['config_mailSMTPpassword'] = 'Contraseña SMTP';
$LANG['config_mailSMTPpassword_comment'] = 'Contraseña del SMTP.';
$LANG['config_mailSMTPSSL'] = 'Protocolo SMTP TLS/SSL';
$LANG['config_mailSMTPSSL_comment'] = 'Usar el protocolo TLS/SSL para la conexión SMTP.';

$LANG['config_matomoAnalytics'] = 'Matomo Analytics';
$LANG['config_matomoAnalytics_comment'] = 'TeamCal Neo soporta Matomo Analytics.';
$LANG['config_matomoUrl'] = 'URL de Matomo';
$LANG['config_matomoUrl_comment'] = 'URL donde se hospeda el servidor Matomo.';
$LANG['config_matomoSiteId'] = 'Matomo SiteId';
$LANG['config_matomoSiteId_comment'] = 'SiteId configurado para esta aplicación.';

$LANG['config_noCaching'] = 'Sin caché';
$LANG['config_noCaching_comment'] = 'Envía instrucciones de no-caché al servidor web.';
$LANG['config_noIndex'] = 'No indexar por motores de búsqueda';
$LANG['config_noIndex_comment'] = 'Indica a los robots de búsqueda que no indexen este sitio.';
$LANG['config_pageHelp'] = 'Ayuda de página';
$LANG['config_pageHelp_comment'] = 'Muestra un ícono de ayuda en la barra de título de la página.';
$LANG['config_permissionScheme'] = 'Esquema de permisos';
$LANG['config_permissionScheme_comment'] = 'Define quién puede hacer qué.';

$LANG['config_pwdStrength'] = 'Fortaleza de la contraseña';
$LANG['config_pwdStrength_comment'] = 'Define qué tan estricta es la comprobación de la contraseña.';
$LANG['config_pwdStrength_low'] = 'Baja';
$LANG['config_pwdStrength_medium'] = 'Media';
$LANG['config_pwdStrength_high'] = 'Alta';

$LANG['config_showAlerts'] = 'Mostrar alertas';
$LANG['config_showAlerts_comment'] = 'Seleccione qué tipo de alertas se mostrarán.';
$LANG['config_showAlerts_all'] = 'Todas';
$LANG['config_showAlerts_warnings'] = 'Solo advertencias y errores';
$LANG['config_showAlerts_none'] = 'Ninguna';

$LANG['config_timeZone'] = 'Zona horaria';
$LANG['config_timeZone_comment'] = 'Ajuste la zona horaria si el servidor está en una distinta.';
$LANG['config_underMaintenance'] = 'En mantenimiento';
$LANG['config_underMaintenance_comment'] = 'Pone el sitio en modo mantenimiento.';

$LANG['config_userCustom1'] = 'Etiqueta campo personalizado 1';
$LANG['config_userCustom1_comment'] = 'Ingrese el nombre para este campo personalizado.';
$LANG['config_userCustom2'] = 'Etiqueta campo personalizado 2';
$LANG['config_userCustom2_comment'] = 'Ingrese el nombre para este campo personalizado.';
$LANG['config_userCustom3'] = 'Etiqueta campo personalizado 3';
$LANG['config_userCustom3_comment'] = 'Ingrese el nombre para este campo personalizado.';
$LANG['config_userCustom4'] = 'Etiqueta campo personalizado 4';
$LANG['config_userCustom4_comment'] = 'Ingrese el nombre para este campo personalizado.';
$LANG['config_userCustom5'] = 'Etiqueta campo personalizado 5';
$LANG['config_userCustom5_comment'] = 'Ingrese el nombre para este campo personalizado.';

$LANG['config_userManual'] = 'Manual de usuario';
$LANG['config_userManual_comment'] = 'Enlace al manual de usuario.';
$LANG['config_versionCompare'] = 'Comparación de versión';
$LANG['config_versionCompare_comment'] = 'Compara la versión actual con la más reciente disponible.';

$LANG['config_welcomeText'] = 'Texto de la página de bienvenida';
$LANG['config_welcomeText_comment'] = 'Ingrese el texto para el mensaje de bienvenida en la página de inicio.<br><i>Nota: Sus cambios no se guardarán al hacer clic en `Guardar` en la vista de código.</i>';

$LANG['config_clearCache'] = 'Borrar caché';
$LANG['config_clearCache_comment'] = 'Haga clic para borrar el caché de la aplicación.';
$LANG['config_clearCache_confirm'] = '¿Está seguro de que desea borrar el caché de la aplicación?';
$LANG['config_alert_cache_cleared'] = 'El caché de la aplicación se borró con éxito.';
$LANG['config_alert_cache_failed'] = 'No se pudo borrar el caché de la aplicación.';
