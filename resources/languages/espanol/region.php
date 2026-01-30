<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings Spanish: Region
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 5.0.0
 */
$LANG['region_edit_title'] = 'Editar Región: ';
$LANG['region_alert_edit'] = 'Actualizar región';
$LANG['region_alert_edit_success'] = 'La información para esta región fue actualizada.';
$LANG['region_alert_save_failed'] = 'No se pudo guardar la información de la región. Hubo una entrada no válida. Por favor, compruebe si hay mensajes de error.';
$LANG['region_name'] = 'Nombre';
$LANG['region_name_comment'] = '';
$LANG['region_description'] = 'Descripción';
$LANG['region_description_comment'] = '';
$LANG['region_viewOnlyRoles'] = 'Roles de Solo Lectura';
$LANG['region_viewOnlyRoles_comment'] = 'Los roles seleccionados aquí solo pueden ver esta región en la vista de calendario. No pueden ingresar ausencias para esta región.';

$LANG['regions_title'] = 'Regiones';
$LANG['regions_tab_list'] = 'Lista';
$LANG['regions_tab_ical'] = 'Importación iCal';
$LANG['regions_tab_transfer'] = 'Transferir Región';
$LANG['regions_alert_transfer_same'] = 'La región de origen y destino deben ser diferentes.';
$LANG['regions_alert_no_file'] = 'No se seleccionó ningún archivo iCal.';
$LANG['regions_alert_region_created'] = 'La región fue creada.';
$LANG['regions_alert_region_created_fail'] = 'No se pudo crear la región. Por favor, compruebe el cuadro de diálogo "Crear región" para ver los errores de entrada.';
$LANG['regions_alert_region_deleted'] = 'La región fue eliminada.';
$LANG['regions_confirm_delete'] = '¿Está seguro de que desea eliminar esta región?';
$LANG['regions_description'] = 'Descripción';
$LANG['regions_ical_file'] = 'Archivo iCal';
$LANG['regions_ical_file_comment'] = 'Seleccione un archivo iCal con eventos de todo el día (por ejemplo, vacaciones escolares) de una unidad local.';
$LANG['regions_ical_holiday'] = 'Festivo iCal';
$LANG['regions_ical_holiday_comment'] = 'Seleccione el festivo que se utilizará para los eventos de su archivo iCal.';
$LANG['regions_ical_imported'] = 'El archivo iCal "%s" fue importado a la región "%s".';
$LANG['regions_ical_overwrite'] = 'Sobrescribir';
$LANG['regions_ical_overwrite_comment'] = 'Seleccione aquí si desea sobrescribir los festivos existentes en la región de destino. Si no se selecciona, las entradas existentes en la región de destino permanecerán.';
$LANG['regions_ical_region'] = 'Región iCal';
$LANG['regions_ical_region_comment'] = 'Seleccione la región en la que se insertarán los eventos de su archivo iCal.';
$LANG['regions_transferred'] = 'La región "%s" fue transferida a la región "%s".';
$LANG['regions_name'] = 'Nombre';
$LANG['regions_region_a'] = 'Región de Origen';
$LANG['regions_region_a_comment'] = 'Seleccione la región que se transferirá a la región de destino.';
$LANG['regions_region_b'] = 'Región de Destino';
$LANG['regions_region_b_comment'] = 'Seleccione la región en la que se transferirá la región de origen.';
$LANG['regions_region_overwrite'] = 'Sobrescribir';
$LANG['regions_region_overwrite_comment'] = 'Seleccione aquí si las entradas de la región de origen deben sobrescribir las de la región de destino. Si no se selecciona, las entradas existentes en la región de destino permanecerán.';
