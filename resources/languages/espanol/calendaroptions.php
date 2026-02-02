<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings Spanish: Calendar Options
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['calopt_title'] = 'Opciones de Calendario';

$LANG['calopt_tab_display'] = 'Mostrar';
$LANG['calopt_tab_filter'] = 'Filtrar';
$LANG['calopt_tab_options'] = 'Opciones';
$LANG['calopt_tab_remainder'] = 'Remanente';
$LANG['calopt_tab_stats'] = 'Estadísticas';
$LANG['calopt_tab_summary'] = 'Resumen';

$LANG['calopt_alert_edit_success'] = 'Se actualizaron las opciones del calendario.';
$LANG['calopt_alert_failed'] = 'No se pudieron actualizar las opciones del calendario. Por favor, verifique su entrada.';
$LANG['calopt_calendarFontSize'] = 'Tamaño de fuente del calendario';
$LANG['calopt_calendarFontSize_comment'] = 'Puede disminuir o aumentar el tamaño de fuente de la vista del calendario mensual aquí ingresando un valor porcentual, por ejemplo, 80 o 120.';
$LANG['calopt_currentYearOnly'] = 'Solo año actual';
$LANG['calopt_currentYearOnly_comment'] = 'Con este interruptor, el calendario se restringirá al año actual. Otros años no se podrán ver ni editar.';
$LANG['calopt_currentYearRoles'] = 'Roles del año actual';
$LANG['calopt_currentYearRoles_comment'] = 'Si se selecciona "Solo año actual", puede asignar esta restricción a ciertos roles aquí.';
$LANG['calopt_defgroupfilter'] = 'Filtro de grupo predeterminado';
$LANG['calopt_defgroupfilter_comment'] = ' Seleccione el filtro de grupo predeterminado para la visualización del calendario. Cada usuario puede cambiar su filtro predeterminado individual en su perfil.';
$LANG['calopt_defgroupfilter_all'] = 'Todo';
$LANG['calopt_defgroupfilter_allbygroup'] = 'Todo (por grupo)';
$LANG['calopt_defregion'] = 'Región predeterminada para el Calendario Base';
$LANG['calopt_defregion_comment'] = 'Seleccione la región predeterminada para el calendario base que se utilizará. Cada usuario puede cambiar su región predeterminada individual en su perfil.';
$LANG['calopt_firstDayOfWeek'] = 'Primer día de la semana';
$LANG['calopt_firstDayOfWeek_comment'] = 'Establézcalo en lunes o domingo. Esta configuración se reflejará en la visualización del número de semana.';
$LANG['calopt_firstDayOfWeek_1'] = 'Lunes';
$LANG['calopt_firstDayOfWeek_7'] = 'Domingo';
$LANG['calopt_hideDaynotes'] = 'Ocultar notas diarias personales';
$LANG['calopt_hideDaynotes_comment'] = 'Al activar esto se ocultarán las notas diarias personales de los usuarios normales. Solo los Directores, Gerentes y Administradores pueden editarlas y verlas.
      De esa manera se pueden usar solo con fines de gestión. Este interruptor no afecta a las notas de cumpleaños.';
$LANG['calopt_hideManagers'] = 'Ocultar gerentes en la vista de Todo por Grupo y Grupo';
$LANG['calopt_hideManagers_comment'] = 'Al marcar esta opción se ocultarán todos los gerentes en la vista de Todo-por-Grupo y Grupo, excepto en aquellos grupos donde sean solo miembros.';
$LANG['calopt_hideManagerOnlyAbsences'] = 'Ocultar ausencias exclusivas para gerencia';
$LANG['calopt_hideManagerOnlyAbsences_comment'] = 'Los tipos de ausencia pueden marcarse como "solo gerente", lo que los hace editables solo para los gerentes.
      Estas ausencias se muestran a los usuarios normales pero no pueden editarlas. Puede ocultar estas ausencias a los usuarios normales aquí.';
$LANG['calopt_includeSummary'] = 'Incluir resumen';
$LANG['calopt_includeSummary_comment'] = 'Al marcar esta opción se añadirá una sección de resumen expandible al final de cada mes, mostrando las sumas de todas las ausencias.';
$LANG['calopt_managerOnlyIncludesAdministrator'] = 'Solo Gerente para Administrador';
$LANG['calopt_managerOnlyIncludesAdministrator_comment'] = 'Los tipos de ausencia solo para gerente solo pueden ser establecidos por gerentes de grupo. Con este interruptor activado, también los usuarios con el rol "Administrador" pueden hacerlo.';
$LANG['calopt_monitorAbsence'] = 'Monitorear ausencia';
$LANG['calopt_monitorAbsence_comment'] = 'Especifique uno o más tipos de ausencia aquí. Para cada uno, el conteo de Remanente/Asignación se mostrará debajo del nombre de usuario en el calendario.';
$LANG['calopt_notificationsAllGroups'] = 'Notificaciones para todos los grupos';
$LANG['calopt_notificationsAllGroups_comment'] = 'Por defecto, los usuarios pueden suscribirse a notificaciones por correo electrónico para eventos del calendario de usuario solo para sus propios grupos. Con este interruptor activado, pueden seleccionar de todos los grupos.<br>
      <i>Nota: Si desactiva esta opción y los usuarios seleccionaron otros grupos para sus notificaciones mientras estaba activada, esa selección no cambiará hasta que su perfil se guarde de nuevo.</i>';
$LANG['calopt_pastDayColor'] = 'Color del día pasado';
$LANG['calopt_pastDayColor_comment'] = 'Establece un color de fondo para los días que quedaron en el pasado en la vista mensual del calendario.
      Deje este campo vacío si no desea colorear los días pasados.';
$LANG['calopt_regionalHolidays'] = 'Marcar festivos regionales';
$LANG['calopt_regionalHolidays_comment'] = 'Con esta opción activada, los festivos en regiones distintas a la seleccionada actualmente se marcarán con un borde coloreado.';
$LANG['calopt_regionalHolidaysColor'] = 'Color del borde del festivo regional';
$LANG['calopt_regionalHolidaysColor_comment'] = 'Establece el color del borde para marcar los festivos regionales.';
$LANG['calopt_repeatHeaderCount'] = 'Repetir encabezado cada N usuarios';
$LANG['calopt_repeatHeaderCount_comment'] = 'Especifica la cantidad de líneas de usuario en el calendario antes de que se repita el encabezado del mes para una mejor legibilidad. Si se establece en 0, el encabezado del mes no se repetirá.';
$LANG['calopt_satBusi'] = 'El sábado es un día laborable';
$LANG['calopt_satBusi_comment'] = 'Por defecto, el sábado y el domingo son días de fin de semana y se muestran como tales en el calendario. Marque esta opción si desea que el sábado sea un día laborable.';
$LANG['calopt_showAvatars'] = 'Mostrar avatares';
$LANG['calopt_showAvatars_comment'] = 'Al marcar esta opción se mostrará una ventana emergente de avatar de usuario al pasar el ratón sobre el ícono de avatar del usuario.';
$LANG['calopt_showMonths'] = 'Mostrar múltiples meses';
$LANG['calopt_showMonths_comment'] = 'Ingrese el número de meses a mostrar en la página del calendario, máximo 12.<br><i>Nota: Un usuario puede sobrescribir este valor en sus ajustes, lo cual tiene prioridad sobre el valor predeterminado.</i>';
$LANG['calopt_showRegionButton'] = 'Mostrar botón de filtro de región';
$LANG['calopt_showRegionButton_comment'] = 'Al marcar esta opción se mostrará el botón de filtro de región en la parte superior del calendario para alternar fácilmente entre diferentes regiones.
      Si solo usa la región estándar, podría tener sentido ocultar el botón desmarcando esta opción.';
$LANG['calopt_showRoleIcons'] = 'Mostrar íconos de rol';
$LANG['calopt_showRoleIcons_comment'] = 'Al marcar esta opción se mostrará un ícono junto al nombre de los usuarios indicando su rol.';
$LANG['calopt_showSummary'] = 'Expandir resumen';
$LANG['calopt_showSummary_comment'] = 'Al marcar esta opción se mostrará/expandirá la sección de resumen de forma predeterminada.';
$LANG['calopt_showTooltipCount'] = 'Contador en globo de ayuda';
$LANG['calopt_showTooltipCount_comment'] = 'Si se marca esta opción, se mostrará la cantidad tomada para un tipo de ausencia como "(tomada mes actual/tomada año actual)" en la información sobre herramientas del tipo de ausencia al pasar el mouse sobre ella en el calendario.';
$LANG['calopt_showUserRegion'] = 'Mostrar festivos regionales por usuario';
$LANG['calopt_showUserRegion_comment'] = 'Si esta opción está activada, el calendario mostrará los festivos regionales en cada fila de usuario según la región establecida por defecto para el usuario. Estos festivos podrían diferir de los festivos regionales globales mostrados en el encabezado del mes. Esto ofrece una mejor vista sobre las diferencias de festivos regionales si gestiona usuarios de diferentes regiones.';
$LANG['calopt_showWeekNumbers'] = 'Mostrar números de semana';
$LANG['calopt_showWeekNumbers_comment'] = 'Al marcar esta opción se añadirá una línea a la visualización del calendario mostrando el número de la semana del año.';
$LANG['calopt_sortByOrderKey'] = 'Clave de orden de usuario';
$LANG['calopt_sortByOrderKey_comment'] = 'Con esta opción activada, los usuarios en el calendario se ordenarán por su clave de orden en lugar de su apellido. La clave de orden es un campo opcional en el perfil de usuario.';
$LANG['calopt_statsDefaultColorAbsences'] = 'Color predeterminado Estadísticas de Ausencia';
$LANG['calopt_statsDefaultColorAbsences_comment'] = 'Seleccione el color predeterminado para esta página de estadísticas.';
$LANG['calopt_statsDefaultColorAbsencetype'] = 'Color predeterminado Estadísticas de Tipo de Ausencia';
$LANG['calopt_statsDefaultColorAbsencetype_comment'] = 'Seleccione el color predeterminado para esta página de estadísticas.';
$LANG['calopt_statsDefaultColorPresences'] = 'Color predeterminado Estadísticas de Presencia';
$LANG['calopt_statsDefaultColorPresences_comment'] = 'Seleccione el color predeterminado para esta página de estadísticas.';
$LANG['calopt_statsDefaultColorPresencetype'] = 'Color predeterminado Estadísticas de Tipo de Presencia';
$LANG['calopt_statsDefaultColorPresencetype_comment'] = 'Seleccione el color predeterminado para esta página de estadísticas.';
$LANG['calopt_statsDefaultColorRemainder'] = 'Color predeterminado Estadísticas de Remanente';
$LANG['calopt_statsDefaultColorRemainder_comment'] = 'Seleccione el color predeterminado para esta página de estadísticas.';
$LANG['calopt_statsDefaultColorTrends'] = 'Color predeterminado Estadísticas de tendencias';
$LANG['calopt_statsDefaultColorTrends_comment'] = 'Seleccione el color predeterminado para esta página de estadísticas.';
$LANG['calopt_statsDefaultColorDayofweek'] = 'Color predeterminado Estadísticas de día de la semana';
$LANG['calopt_statsDefaultColorDayofweek_comment'] = 'Seleccione el color predeterminado para esta página de estadísticas.';
$LANG['calopt_statsDefaultColorDuration'] = 'Color predeterminado Estadísticas de duración';
$LANG['calopt_statsDefaultColorDuration_comment'] = 'Seleccione el color predeterminado para esta página de estadísticas.';
$LANG['calopt_summaryAbsenceTextColor'] = 'Color de texto de ausencia';
$LANG['calopt_summaryAbsenceTextColor_comment'] = 'Aquí puede establecer el color para los conteos de ausencias en la sección de resumen. Deje el campo vacío para el color predeterminado.';
$LANG['calopt_summaryPresenceTextColor'] = 'Color de texto de presencia';
$LANG['calopt_summaryPresenceTextColor_comment'] = 'Aquí puede establecer el color para los conteos de presencias en la sección de resumen. Deje el campo vacío para el color predeterminado.';
$LANG['calopt_sunBusi'] = 'El domingo es un día laborable';
$LANG['calopt_sunBusi_comment'] = 'Por defecto, el sábado y el domingo son días de fin de semana y se muestran como tales en el calendario.
      Marque esta opción si desea que el domingo sea un día laborable.';
$LANG['calopt_supportMobile'] = 'Soporte para dispositivos móviles';
$LANG['calopt_supportMobile_comment'] = 'Con este interruptor activado, TeamCal Neo preparará las tablas del calendario (Ver y Editar) para un ancho de pantalla específico de modo que no sea necesario el desplazamiento horizontal.';
$LANG['calopt_symbolAsIcon'] = 'Identificador de tipo de ausencia como ícono';
$LANG['calopt_symbolAsIcon_comment'] = 'Con esta opción, se usará el identificador de carácter en la visualización del calendario en lugar de su ícono.';
$LANG['calopt_takeover'] = 'Habilitar toma de posesión de ausencia';
$LANG['calopt_takeover_comment'] = 'Con esta opción habilitada, el usuario conectado puede tomar posesión de ausencias de otros usuarios si puede editar el calendario correspondiente. Las ausencias de toma de posesión NO se validan contra ninguna regla.';
$LANG['calopt_todayBorderColor'] = 'Color del borde de hoy';
$LANG['calopt_todayBorderColor_comment'] = 'Especifica el color en hexadecimal del borde izquierdo y derecho de la columna de hoy.';
$LANG['calopt_todayBorderSize'] = 'Tamaño del borde de hoy';
$LANG['calopt_todayBorderSize_comment'] = 'Especifica el tamaño (grosor) en píxeles del borde izquierdo y derecho de la columna de hoy.';
$LANG['calopt_trustedRoles'] = 'Roles de confianza';
$LANG['calopt_trustedRoles_comment'] = 'Seleccione los roles que pueden ver ausencias confidenciales y notas diarias.<br>
      <i>Nota: Puede excluir el rol "Administrador" aquí, pero el usuario "admin" funciona como superusuario y siempre puede ver todos los datos.</i>';
$LANG['calopt_usersPerPage'] = 'Número de usuarios por página';
$LANG['calopt_usersPerPage_comment'] = 'Si mantiene un gran número de usuarios en TeamCal Neo, es posible que desee usar paginación en la visualización del calendario. Indica cuántos usuarios deseas mostrar en cada página.';
