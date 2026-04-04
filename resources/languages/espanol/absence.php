<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings Spanish: Absence
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['abs_list_title'] = 'Tipos de Ausencia';
$LANG['abs_edit_title'] = 'Editar Tipo de Ausencia: ';
$LANG['abs_alert_edit'] = 'Actualizar Tipo de Ausencia';
$LANG['abs_alert_edit_success'] = 'Se actualizó la información de este tipo de ausencia.';
$LANG['abs_alert_created'] = 'Se creó el tipo de ausencia.';
$LANG['abs_alert_created_fail'] = 'No se pudo crear el tipo de ausencia. Por favor, verifique el diálogo "Crear tipo de ausencia" para errores de entrada.';
$LANG['abs_alert_deleted'] = 'Se eliminó el tipo de ausencia.';
$LANG['abs_alert_save_failed'] = 'No se pudo guardar la nueva información para este tipo de ausencia. Hubo una entrada inválida.';
$LANG['abs_allow_active'] = 'Cantidad Restringida';
$LANG['abs_allowance'] = 'Asignación por Año';
$LANG['abs_allowance_comment'] = 'Defina aquí una asignación anual para este tipo de ausencia. Esta cantidad se refiere al año calendario actual. Al ver un perfil de usuario, la sección de conteo de ausencias contendrá la cantidad restante para este tipo de ausencia para el usuario (un valor negativo indicará que el usuario ha utilizado demasiados días de ausencia de este tipo). Si la asignación se establece en 0, no se asume ningún límite.';
$LANG['abs_allowmonth'] = 'Asignación por Mes';
$LANG['abs_allowmonth_comment'] = 'Establezca una asignación para este tipo de ausencia por mes aquí.';
$LANG['abs_allowweek'] = 'Asignación por Semana';
$LANG['abs_allowweek_comment'] = 'Establezca una asignación para este tipo de ausencia por semana aquí.';
$LANG['abs_approval_required'] = 'Aprobación requerida';
$LANG['abs_approval_required_comment'] = 'Marcar esta casilla define que este tipo de ausencia requiere la aprobación del gerente de grupo, director o administrador.';
$LANG['abs_bgcolor'] = 'Color de fondo';
$LANG['abs_bgcolor_comment'] = 'Este es el color de fondo utilizado para este tipo de ausencia.';
$LANG['abs_bgtrans'] = 'Fondo transparente';
$LANG['abs_bgtrans_comment'] = 'Con esta opción activada, el color de fondo será ignorado.';
$LANG['abs_color'] = 'Color de texto';
$LANG['abs_color_comment'] = 'En caso de que se use el símbolo de carácter, este es el color en el que se muestra.';
$LANG['abs_confidential'] = 'Confidencial';
$LANG['abs_confidential_comment'] = 'Marcar esta casilla marca este tipo de ausencia como "confidencial". El público y los usuarios regulares no pueden ver esta ausencia en el calendario, excepto si es la propia ausencia del usuario.';
$LANG['abs_confirm_delete'] = '¿Está seguro de que desea eliminar el tipo de ausencia "%s"?<br>Todas las entradas existentes en las plantillas de usuario se reemplazarán con "Presente".';
$LANG['abs_counts_as'] = 'Cuenta como';
$LANG['abs_counts_as_comment'] = 'Seleccione si las ausencias tomadas de este tipo cuentan contra la asignación de otro tipo de ausencia.';
$LANG['abs_counts_as_present'] = 'Cuenta como presente';
$LANG['abs_counts_as_present_comment'] = 'Marcar esta casilla define que este tipo de ausencia cuenta como "presente".';
$LANG['abs_display'] = 'Mostrar';
$LANG['abs_display_comment'] = '';
$LANG['abs_factor'] = 'Factor';
$LANG['abs_factor_comment'] = 'TeamCal puede contar la cantidad de días tomados por tipo de ausencia. El campo "Factor" ofrece la opción de multiplicar cada ausencia encontrada con un valor de su elección. El predeterminado es 1.';
$LANG['abs_groups'] = 'Asignaciones de grupo';
$LANG['abs_groups_comment'] = 'Seleccione los grupos para los cuales este tipo de ausencia es válido.';
$LANG['abs_hide_in_profile'] = 'Ocultar en perfil';
$LANG['abs_hide_in_profile_comment'] = 'Marcar esta casilla define que los usuarios normales no pueden ver este tipo de ausencia en la pestaña de Ausencias de su perfil.';
$LANG['abs_icon'] = 'Ícono';
$LANG['abs_icon_comment'] = 'El ícono del tipo de ausencia se utiliza en la visualización del calendario.';
$LANG['abs_icon_keyword'] = 'Ingrese una palabra clave...';
$LANG['abs_manager_only'] = 'Solo Gerente de Grupo';
$LANG['abs_manager_only_comment'] = 'Marcar esta casilla define que solo los gerentes de grupo pueden establecer este tipo de ausencia.';
$LANG['abs_name'] = 'Nombre';
$LANG['abs_name_comment'] = 'El nombre del tipo de ausencia se utiliza en listas y descripciones (ej. "Viaje de negocios").';
$LANG['abs_sample'] = 'Muestra de visualización';
$LANG['abs_sample_comment'] = 'Así es como se verá su tipo de ausencia en su calendario según sus ajustes actuales.';
$LANG['abs_show_in_remainder'] = 'Mostrar en Remanente';
$LANG['abs_show_in_remainder_comment'] = 'Marcar esta opción incluirá esta ausencia en la página de Remanente.';
$LANG['abs_symbol'] = 'ID de carácter';
$LANG['abs_symbol_comment'] = 'El ID de carácter del tipo de ausencia se utiliza en correos electrónicos de notificación ya que los íconos de fuente no son compatibles allí.';
$LANG['abs_tab_groups'] = 'Asignaciones de Grupo';
$LANG['abs_takeover'] = 'Habilitar para Toma de Posesión';
$LANG['abs_takeover_comment'] = 'Habilita este tipo de ausencia para ser tomada por otro usuario.';

//
// Absence Icon
//
$LANG['absico_title'] = 'Seleccionar Ícono de Tipo de Ausencia: ';
$LANG['absico_tab_brand'] = 'Íconos de Marca';
$LANG['absico_tab_regular'] = 'Íconos Regulares';
$LANG['absico_tab_solid'] = 'Íconos Sólidos';

//
// Absences Summary
//
$LANG['absum_title'] = 'Resumen de Ausencias %1$s: %2$s';
$LANG['absum_modalYearTitle'] = 'Seleccione el Año para el Resumen';
$LANG['absum_unlimited'] = 'Ilimitado';
$LANG['absum_year'] = 'Año';
$LANG['absum_year_comment'] = 'Seleccione el año para este resumen.';
$LANG['absum_absencetype'] = 'Tipo de Ausencia';
$LANG['absum_contingent'] = 'Contingente';
$LANG['absum_contingent_tt'] = 'El Contingente es el resultado de la Asignación para este año más el Remanente del año pasado.';
$LANG['absum_taken'] = 'Tomado';
$LANG['absum_remainder'] = 'Remanente';
