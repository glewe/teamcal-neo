<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings Spanish: Calendar
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['cal_title'] = 'Calendario %s-%s (Región: %s)';
$LANG['cal_tt_absent'] = 'Ausente';
$LANG['cal_tt_anotherabsence'] = 'Otra ausencia';
$LANG['cal_tt_backward'] = 'Retroceder un mes...';
$LANG['cal_tt_clicktoedit'] = 'Clic para editar...';
$LANG['cal_tt_forward'] = 'Avanzar un mes...';
$LANG['cal_tt_onemore'] = 'Mostrar un mes más...';
$LANG['cal_tt_oneless'] = 'Mostrar un mes menos...';
$LANG['cal_search'] = 'Buscar Usuario';
$LANG['cal_selAbsence'] = 'Seleccionar Ausencia';
$LANG['cal_selAbsence_comment'] = 'Muestra todas las entradas que tienen este tipo de ausencia para hoy.';
$LANG['cal_selGroup'] = 'Seleccionar Grupo';
$LANG['cal_selMonth'] = 'Seleccionar Mes';
$LANG['cal_selRegion'] = 'Seleccionar Región';
$LANG['cal_selWidth'] = 'Seleccionar Ancho de Pantalla';
$LANG['cal_selWidth_comment'] = 'Seleccione el ancho de su pantalla en píxeles para que la tabla del calendario pueda ajustarse a él. Si su ancho no está en la lista, seleccione el siguiente más alto.
      <br>Parece que actualmente está usando una pantalla con un ancho de <span id="currentwidth"></span> píxeles. Recargue la página para consultar este diálogo nuevamente para confirmar.';
$LANG['cal_switchFullmonthView'] = 'Cambiar a vista de mes completo';
$LANG['cal_switchSplitmonthView'] = 'Cambiar a vista de mes dividido';
$LANG['cal_summary'] = 'Resumen';
$LANG['cal_businessDays'] = 'Días laborables en el mes';
$LANG['cal_caption_weeknumber'] = 'Sem';
$LANG['cal_caption_name'] = 'Nombre';
$LANG['cal_img_alt_edit_month'] = 'Editar festivos para este mes...';
$LANG['cal_img_alt_edit_cal'] = 'Editar calendario para esta persona...';
$LANG['cal_birthday'] = 'Cumpleaños';
$LANG['cal_age'] = 'Edad';

$LANG['sum_present'] = 'Presente';
$LANG['sum_absent'] = 'Ausente';
$LANG['sum_delta'] = 'Delta';
$LANG['sum_absence_summary'] = 'Resumen de Ausencias';
$LANG['sum_business_day_count'] = 'días laborables';
$LANG['exp_summary'] = 'Expandir sección de Resumen...';
$LANG['col_summary'] = 'Contraer sección de Resumen...';
$LANG['exp_remainder'] = 'Expandir sección de Remanente...';
$LANG['col_remainder'] = 'Contraer sección de Remanente...';

$LANG['caledit_title'] = 'Editar mes %s-%s para %s';
$LANG['caledit_absencePattern'] = 'Patrón de Ausencia';
$LANG['caledit_absencePattern_comment'] = 'Seleccione el patrón de ausencia que se aplicará a este mes.';
$LANG['caledit_absencePatternSkipHolidays'] = 'Omitir Festivos';
$LANG['caledit_absencePatternSkipHolidays_comment'] = 'Al configurar las ausencias por patrón, omitir los festivos que no cuentan como días laborables.';
$LANG['caledit_absenceType'] = 'Tipo de Ausencia';
$LANG['caledit_absenceType_comment'] = 'Seleccione el tipo de ausencia para esta entrada.';
$LANG['caledit_alert_out_of_range'] = 'Las fechas introducidas estaban al menos parcialmente fuera del mes mostrado actualmente. No se guardaron cambios.';
$LANG['caledit_alert_save_failed'] = 'No se pudo guardar la información de la ausencia. Hubo una entrada inválida. Por favor, compruebe su última entrada.';
$LANG['caledit_alert_update'] = 'Actualizar mes';
$LANG['caledit_alert_update_all'] = 'Todas las ausencias fueron aceptadas y el calendario se actualizó en consecuencia.';
$LANG['caledit_alert_update_group'] = 'Las ausencias de grupo se establecieron para todos los usuarios del grupo.';
$LANG['caledit_alert_update_group_cleared'] = 'Las ausencias de grupo fueron borradas para todos los usuarios del grupo.';
$LANG['caledit_alert_update_partial'] = 'Algunas ausencias no fueron aceptadas porque violan las restricciones establecidas por la dirección. Las siguientes solicitudes fueron rechazadas:';
$LANG['caledit_alert_update_none'] = 'Las ausencias no fueron aceptadas porque las ausencias solicitadas violan las restricciones establecidas por la dirección. El calendario no se actualizó.';
$LANG['caledit_clearAbsence'] = 'Borrar';
$LANG['caledit_clearAbsences'] = 'Borrar Ausencias';
$LANG['caledit_clearDaynotes'] = 'Borrar Notas Diarias';
$LANG['caledit_confirm_clearall'] = '¿Está seguro de que desea borrar todas las ausencias en este mes?<br><br><strong>Año:</strong> %s<br><strong>Mes:</strong> %s<br><strong>Usuario:</strong> %s';
$LANG['caledit_confirm_savegroup'] = '<p><strong class="text-danger">¡Atención!</strong><br>Guardar ausencias de grupo no realizará ninguna comprobación de aprobación individual.<br>
      Todas las ausencias se establecerán para cada usuario en el grupo seleccionado. Sin embargo, puede elegir no sobrescribir las ausencias individuales existentes a continuación.</p>
      <p><strong>Año:</strong> %s<br><strong>Mes:</strong> %s<br><strong>Grupo:</strong> %s</p>';
$LANG['caledit_currentAbsence'] = 'Ausencia actual';
$LANG['caledit_endDate'] = 'Fecha de fin';
$LANG['caledit_endDate_comment'] = 'Seleccione la fecha de fin (debe ser en este mes).';
$LANG['caledit_keepExisting'] = 'Mantener las ausencias de usuario existentes';
$LANG['caledit_Pattern'] = 'Patrón';
$LANG['caledit_PatternTitle'] = 'Seleccionar Patrón de Ausencia';
$LANG['caledit_Period'] = 'Período';
$LANG['caledit_PeriodTitle'] = 'Seleccionar Período de Ausencia';
$LANG['caledit_Recurring'] = 'Recurrente';
$LANG['caledit_RecurringTitle'] = 'Seleccionar Ausencia Recurrente';
$LANG['caledit_recurrence'] = 'Recurrencia';
$LANG['caledit_recurrence_comment'] = 'Seleccione la recurrencia';
$LANG['caledit_selGroup'] = 'Seleccionar Grupo';
$LANG['caledit_selRegion'] = 'Seleccionar Región';
$LANG['caledit_selUser'] = 'Seleccionar Usuario';
$LANG['caledit_startDate'] = 'Fecha de inicio';
$LANG['caledit_startDate_comment'] = 'Seleccione la fecha de inicio (debe ser en este mes).';
