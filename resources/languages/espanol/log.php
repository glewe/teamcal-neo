<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings Spanish: Log page
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
$LANG['log_title'] = 'Log del Sistema';
$LANG['log_title_events'] = 'Eventos';
$LANG['log_clear'] = 'Eliminar período';
$LANG['log_clear_confirm'] = '¿Está seguro de que desea eliminar todos los eventos del período seleccionado actualmente?';
$LANG['log_filterCalopt'] = 'Opciones de Calendario';
$LANG['log_filterPatterns'] = 'Patrones de Ausencia';
$LANG['log_filterConfig'] = 'Configuración';
$LANG['log_filterDatabase'] = 'Base de datos';
$LANG['log_filterGroup'] = 'Grupos';
$LANG['log_filterLogin'] = 'Inicio de sesión';
$LANG['log_filterLoglevel'] = 'Inicio de sesión';
$LANG['log_filterNews'] = 'Noticias';
$LANG['log_filterPermission'] = 'Permisos';
$LANG['log_filterRegistration'] = 'Registro';
$LANG['log_filterRole'] = 'Roles';
$LANG['log_filterUser'] = 'Usuario';
$LANG['log_header_event'] = 'Evento';
$LANG['log_header_ip'] = 'IP';
$LANG['log_header_type'] = 'Tipo de evento';
$LANG['log_header_user'] = 'Usuario';
$LANG['log_header_when'] = 'Marca de tiempo (UTC)';
$LANG['log_settings'] = 'Ajustes de Log';
$LANG['log_settings_event'] = 'Tipo de evento';
$LANG['log_settings_log'] = 'Registrar este tipo de evento';
$LANG['log_settings_show'] = 'Mostrar este tipo de evento';
$LANG['log_sort_asc'] = 'Orden ascendente...';
$LANG['log_sort_desc'] = 'Orden descendente...';
$LANG['log_statistics'] = 'Estadísticas';
$LANG['log_statistics_timeframe'] = 'Período de Tiempo';
$LANG['log_statistics_last_day'] = 'Hoy';
$LANG['log_statistics_last_week'] = 'Esta Semana';
$LANG['log_statistics_last_month'] = 'Este Mes';
$LANG['log_statistics_last_year'] = 'Este Año';
$LANG['log_statistics_overall'] = 'Total';
$LANG['log_statistics_event_types'] = 'Tipos de Eventos';
$LANG['log_statistics_select_types'] = 'Seleccione los tipos de eventos para mostrar en el gráfico';
$LANG['log_statistics_chart_title'] = 'Estadísticas de Eventos';
$LANG['log_statistics_no_data'] = 'No hay datos disponibles. Por favor, seleccione al menos un tipo de evento.';
//
// Log Messages
//
$LANG['log_abs_created'] = 'Tipo de ausencia creado: ';
$LANG['log_abs_updated'] = 'Tipo de ausencia actualizado: ';
$LANG['log_abs_deleted'] = 'Tipo de ausencia eliminado: ';
$LANG['log_cal_grp_tpl_chg'] = 'Calendario de grupo modificado: ';
$LANG['log_cal_tplusr_def_tpl'] = 'Calendario de usuario de plantilla creado: ';
$LANG['log_cal_usr_def_tpl'] = 'Calendario de usuario creado: ';
$LANG['log_cal_usr_tpl_chg'] = 'Calendario de usuario modificado: ';
$LANG['log_cal_usr_tpl_clr'] = 'Calendario de usuario borrado: ';
$LANG['log_calopt'] = 'Opciones de calendario modificadas';
$LANG['log_config'] = 'Configuración del sistema modificada';
$LANG['log_csv_import'] = 'Importación de usuarios CSV: ';
$LANG['log_db_cleanup_before'] = 'Limpieza de base de datos antes de ';
$LANG['log_db_delete_archive'] = 'Borrado de BD: Registros de archivo borrados';
$LANG['log_db_delete_groups'] = 'Borrado de BD: Todos los grupos';
$LANG['log_db_delete_log'] = 'Borrado de BD: Registros de log borrados';
$LANG['log_db_delete_msg'] = 'Borrado de BD: Todos los mensajes';
$LANG['log_db_delete_msg_orph'] = 'Borrado de BD: Todos los mensajes huérfanos';
$LANG['log_db_delete_perm'] = 'Borrado de BD: Todos los esquemas de permisos personalizados';
$LANG['log_db_delete_users'] = 'Borrado de BD: Todos los usuarios';
$LANG['log_db_export'] = 'Exportación de base de datos: ';
$LANG['log_db_optimized'] = 'Base de datos optimizada';
$LANG['log_db_reset'] = 'Reinicio de base de datos';
$LANG['log_db_restore'] = 'Base de datos restaurada desde ';
$LANG['log_decl_updated'] = 'Ajustes de rechazo actualizados';
$LANG['log_dn_updated'] = 'Nota diaria actualizada: ';
$LANG['log_dn_created'] = 'Nota diaria creada: ';
$LANG['log_dn_deleted'] = 'Nota diaria eliminada: ';
$LANG['log_group_created'] = 'Grupo creado: ';
$LANG['log_group_updated'] = 'Grupo actualizado: ';
$LANG['log_group_deleted'] = 'Grupo eliminado: ';
$LANG['log_hol_created'] = 'Festivo creado: ';
$LANG['log_hol_updated'] = 'Festivo actualizado: ';
$LANG['log_hol_deleted'] = 'Festivo eliminado: ';
$LANG['log_imp_success'] = 'Importación CSV exitosa: ';
$LANG['log_log_updated'] = 'Ajustes de log actualizados';
$LANG['log_log_cleared'] = 'Log borrado';
$LANG['log_log_reset'] = 'Reseteo de ajustes de log';
$LANG['log_login_2fa'] = 'Código de autenticación incorrecto';
$LANG['log_login_success'] = 'Inicio de sesión exitoso';
$LANG['log_login_missing'] = 'Falta usuario o contraseña';
$LANG['log_login_unknown'] = 'Usuario desconocido';
$LANG['log_login_locked'] = 'Cuenta bloqueada';
$LANG['log_login_pwd'] = 'Contraseña incorrecta';
$LANG['log_login_attempts'] = 'Demasiados intentos de inicio de sesión fallidos';
$LANG['log_login_not_verified'] = 'Cuenta de usuario no verificada';
$LANG['log_login_ldap_pwd_missing'] = 'Falta contraseña LDAP';
$LANG['log_login_ldap_bind_failed'] = 'Fallo de enlace LDAP';
$LANG['log_login_ldap_connect_failed'] = 'Fallo de conexión LDAP';
$LANG['log_login_ldap_tls_failed'] = 'Fallo de inicio TLS LDAP';
$LANG['log_login_ldap_username'] = 'Usuario LDAP no encontrado';
$LANG['log_login_ldap_search_bind_failed'] = 'Fallo de búsqueda de enlace LDAP';
$LANG['log_logout'] = 'Cierre de sesión';
$LANG['log_month_tpl_created'] = 'Plantilla de mes creada: ';
$LANG['log_month_tpl_updated'] = 'Plantilla de mes actualizada: ';
$LANG['log_msg_all_confirmed_by'] = 'Todas las noticias confirmadas por ';
$LANG['log_msg_all_deleted_by'] = 'Todas las noticias eliminadas por ';
$LANG['log_msg_confirmed'] = 'Mensaje confirmado: ';
$LANG['log_msg_deleted'] = 'Mensaje eliminado: ';
$LANG['log_msg_email'] = 'Correo electrónico enviado por ';
$LANG['log_msg_message'] = 'Mensaje enviado: ';
$LANG['log_pattern_created'] = 'Patrón de ausencia creado: ';
$LANG['log_pattern_updated'] = 'Patrón de ausencia actualizado: ';
$LANG['log_pattern_deleted'] = 'Patrón de ausencia eliminado: ';
$LANG['log_perm_activated'] = 'Esquema de permisos activado: ';
$LANG['log_perm_deleted'] = 'Esquema de permisos eliminado: ';
$LANG['log_perm_created'] = 'Esquema de permisos creado: ';
$LANG['log_perm_reset'] = 'Esquema de permisos reseteado: ';
$LANG['log_perm_changed'] = 'Esquema de permisos modificado: ';
$LANG['log_region_created'] = 'Región creada: ';
$LANG['log_region_deleted'] = 'Región eliminada: ';
$LANG['log_region_ical'] = 'Archivo iCal "';
$LANG['log_region_transferred'] = 'Transferencia de región: ';
$LANG['log_region_updated'] = 'Región actualizada: ';
$LANG['log_role_created'] = 'Rol creado: ';
$LANG['log_role_updated'] = 'Rol actualizado: ';
$LANG['log_role_deleted'] = 'Rol eliminado: ';
$LANG['log_tcpimp_abs'] = 'Tipos de ausencia de TeamCal Pro importados';
$LANG['log_tcpimp_allo'] = 'Asignaciones de TeamCal Pro importadas';
$LANG['log_tcpimp_dayn'] = 'Notas diarias de TeamCal Pro importadas';
$LANG['log_tcpimp_groups'] = 'Grupos de TeamCal Pro importados';
$LANG['log_tcpimp_hols'] = 'Festivos de TeamCal Pro importados';
$LANG['log_tcpimp_mtpl'] = 'Calendarios de región de TeamCal Pro importados';
$LANG['log_tcpimp_regs'] = 'Regiones de TeamCal Pro importadas';
$LANG['log_tcpimp_roles'] = 'Roles de TeamCal Pro importados';
$LANG['log_tcpimp_ugr'] = 'Membresías de grupo de TeamCal Pro importadas';
$LANG['log_tcpimp_users'] = 'Cuentas de usuario de TeamCal Pro importadas';
$LANG['log_tcpimp_utpl'] = 'Calendarios de usuario de TeamCal Pro importados';
$LANG['log_user_2fa_removed'] = 'Secreto 2FA eliminado para: ';
$LANG['log_user_added'] = 'Usuario añadido: ';
$LANG['log_user_updated'] = 'Perfil de usuario actualizado: ';
$LANG['log_user_deleted'] = 'Usuario eliminado: ';
$LANG['log_user_archived_deleted'] = 'Usuario archivado eliminado: ';
$LANG['log_user_archived'] = 'Usuario archivado: ';
$LANG['log_user_restored'] = 'Usuario restaurado: ';
$LANG['log_user_registered'] = 'Registro de usuario: ';
$LANG['log_user_pwd_request'] = 'Token de contraseña para: ';
$LANG['log_user_pwd_reset'] = 'Reinicio de contraseña de usuario: ';
$LANG['log_user_avatar_uploaded'] = 'Avatar de usuario subido: ';
$LANG['log_user_group_updated'] = 'Tipo de usuario y/o asignación de grupo actualizada';
$LANG['log_user_verify_approval'] = 'Usuario verificado, aprobación necesaria: ';
$LANG['log_user_verify_unlocked'] = 'Usuario verificado, desbloqueado y visible: ';
$LANG['log_user_verify_mismatch'] = 'El código de verificación de usuario no coincide: ';
$LANG['log_user_verify_usr_notexist'] = 'El usuario no existe: ';
$LANG['log_user_verify_code_notexist'] = 'El código de verificación no existe: ';
