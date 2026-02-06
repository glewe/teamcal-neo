<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings Spanish: Database page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['db_alert_delete'] = 'Eliminación de registros';
$LANG['db_alert_delete_success'] = 'Se han realizado las actividades de eliminación.';
$LANG['db_alert_failed'] = 'No se pudo realizar la operación. Por favor, verifique su entrada.';
$LANG['db_alert_cleanup'] = 'Limpieza';
$LANG['db_alert_cleanup_success'] = 'Se han realizado las actividades de limpieza.';
$LANG['db_alert_optimize'] = 'Optimizar tablas';
$LANG['db_alert_optimize_success'] = 'Se optimizaron todas las tablas de la base de datos.';
$LANG['db_alert_repair'] = 'Reparar base de datos';
$LANG['db_alert_repair_success'] = 'Se han realizado las actividades de reparación.';
$LANG['db_alert_reset'] = 'Reiniciar base de datos';
$LANG['db_alert_reset_fail'] = 'Una o más consultas fallaron. Su base de datos podría estar incompleta o corrupta.';
$LANG['db_alert_reset_success'] = 'Su base de datos fue reiniciada con éxito con datos de muestra.';
$LANG['db_alert_url'] = 'Guardar URL de la base de datos';
$LANG['db_alert_url_fail'] = 'Por favor, ingrese una URL válida para la aplicación de la base de datos.';
$LANG['db_alert_url_success'] = 'La URL de la aplicación de la base de datos se guardó con éxito.';
$LANG['db_application'] = 'Administración de Base de Datos';
$LANG['db_clean_before'] = 'Antes de la fecha';
$LANG['db_clean_before_comment'] = 'Los registros de las tablas marcadas arriba se eliminarán cuando sean iguales o más antiguos que la fecha seleccionada aquí.';
$LANG['db_clean_confirm'] = 'Confirmación';
$LANG['db_clean_confirm_comment'] = 'Por favor, escriba "CLEANUP" para confirmar esta acción.';
$LANG['db_clean_daynotes'] = 'Limpiar notas diarias antes de...';
$LANG['db_clean_holidays'] = 'Limpiar festivos antes de...';
$LANG['db_clean_months'] = 'Limpiar calendarios de región antes de...';
$LANG['db_clean_templates'] = 'Limpiar calendarios de usuario antes de...';
$LANG['db_clean_what'] = 'Qué limpiar';
$LANG['db_clean_what_comment'] = 'Seleccione aquí qué desea limpiar.';
$LANG['db_confirm'] = 'Confirmación';
$LANG['db_dbURL'] = 'URL de la base de datos';
$LANG['db_dbURL_comment'] = 'Puede especificar un enlace directo a su aplicación de gestión de base de datos preferida para este sitio web. Si se guarda una URL válida aquí, se mostrará un botón que enlaza con ella.';
$LANG['db_del_archive'] = 'Eliminar todos los registros archivados';
$LANG['db_del_confirm_comment'] = 'Por favor, escriba "DELETE" para confirmar esta acción:';
$LANG['db_del_groups'] = 'Eliminar todos los grupos';
$LANG['db_del_log'] = 'Eliminar todas las entradas del log del sistema';
$LANG['db_del_messages'] = 'Eliminar todos los mensajes';
$LANG['db_del_orphMessages'] = 'Eliminar todos los mensajes huérfanos';
$LANG['db_del_permissions'] = 'Eliminar esquemas de permisos personalizados (excepto "Default")';
$LANG['db_del_users'] = 'Eliminar todos los usuarios, sus plantillas de ausencia y notas diarias (excepto "admin")';
$LANG['db_del_what'] = 'Qué eliminar';
$LANG['db_del_what_comment'] = 'Seleccione aquí qué desea eliminar.';
$LANG['db_optimize'] = 'Optimizar tablas de la base de datos';
$LANG['db_optimize_comment'] = 'Reorganiza el almacenamiento físico de los datos de la tabla y los índices asociados.';
$LANG['db_repair_confirm'] = 'Confirmación';
$LANG['db_repair_confirm_comment'] = 'Por favor, escriba "REPAIR" para confirmar esta acción.';
$LANG['db_repair_daynoteRegions'] = 'Regiones de nota diaria';
$LANG['db_repair_daynoteRegions_comment'] = 'Comprueba si hay notas diarias sin región y las asigna a Default.';
$LANG['db_reset_basic'] = 'Datos básicos';
$LANG['db_reset_danger'] = '<strong>¡Peligro!</strong> ¡Reiniciar la base de datos eliminará TODOS sus datos!';
$LANG['db_reset_sample'] = 'Datos básicos más datos de muestra';
$LANG['db_resetDataset'] = 'Conjunto de datos para reiniciar';
$LANG['db_resetDataset_comment'] = 'Seleccione el conjunto de datos al que se debe reiniciar la base de datos.';
$LANG['db_resetString'] = 'Cadena de confirmación de reinicio';
$LANG['db_resetString_comment'] = 'Escriba lo siguiente para confirmar su decisión: "YesIAmSure".';
$LANG['db_tab_admin'] = 'Administración';
$LANG['db_tab_cleanup'] = 'Limpieza';
$LANG['db_tab_dbinfo'] = 'Información de la base de datos';
$LANG['db_tab_delete'] = 'Eliminar registros';
$LANG['db_tab_optimize'] = 'Optimizar tablas';
$LANG['db_tab_repair'] = 'Reparar';
$LANG['db_tab_reset'] = 'Reiniciar base de datos';
$LANG['db_tab_tcpimp'] = 'Importar TeamCal Pro';
$LANG['db_title'] = 'Mantenimiento de Base de Datos';
