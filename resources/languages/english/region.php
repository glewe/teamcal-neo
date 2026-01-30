<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: Region
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['region_edit_title'] = 'Edit Region: ';
$LANG['region_alert_edit'] = 'Update region';
$LANG['region_alert_edit_success'] = 'The information for this region was updated.';
$LANG['region_alert_save_failed'] = 'The new information for this region could not be saved. There was invalid input. Please check for error messages.';
$LANG['region_name'] = 'Name';
$LANG['region_name_comment'] = '';
$LANG['region_description'] = 'Description';
$LANG['region_description_comment'] = '';
$LANG['region_viewOnlyRoles'] = 'View Only Roles';
$LANG['region_viewOnlyRoles_comment'] = 'The roles selected here can only see this region in the calendar view. They cannot enter absences for this region.';

$LANG['regions_title'] = 'Regions';
$LANG['regions_tab_list'] = 'List';
$LANG['regions_tab_ical'] = 'iCal Import';
$LANG['regions_tab_transfer'] = 'Transfer Region';
$LANG['regions_alert_transfer_same'] = 'Source and target region for a transfer must be different.';
$LANG['regions_alert_no_file'] = 'No iCal file was selected.';
$LANG['regions_alert_region_created'] = 'The region was created.';
$LANG['regions_alert_region_created_fail'] = 'The region was not created. Please check the "Create region" dialog for input errors.';
$LANG['regions_alert_region_deleted'] = 'The region was deleted.';
$LANG['regions_confirm_delete'] = 'Are you sure you want to delete this region';
$LANG['regions_description'] = 'Description';
$LANG['regions_ical_file'] = 'iCal File';
$LANG['regions_ical_file_comment'] = 'Select an iCal file with whole day events (e.g. school holidays) from a local drive.';
$LANG['regions_ical_holiday'] = 'iCal Holiday';
$LANG['regions_ical_holiday_comment'] = 'Select the holiday to be used for the events in your iCal file.';
$LANG['regions_ical_imported'] = 'The iCal file "%s" was imported into region "%s".';
$LANG['regions_ical_overwrite'] = 'Overwrite';
$LANG['regions_ical_overwrite_comment'] = 'Select here whether existing holidays in the target region shall be overwritten. If not selected, existing entries in the target region will remain.';
$LANG['regions_ical_region'] = 'iCal Region';
$LANG['regions_ical_region_comment'] = 'Select the region into which the events of your iCal file shall be inserted.';
$LANG['regions_transferred'] = 'The region "%s" was transferred into region "%s".';
$LANG['regions_name'] = 'Name';
$LANG['regions_region_a'] = 'Source Region';
$LANG['regions_region_a_comment'] = 'Select the region that shall be transfered into the target region.';
$LANG['regions_region_b'] = 'Target Region';
$LANG['regions_region_b_comment'] = 'Select the region into which the source region shall be transfered.';
$LANG['regions_region_overwrite'] = 'Overwrite';
$LANG['regions_region_overwrite_comment'] = 'Select here whether the source region entries shall overwrite the target region entries. If not selected, existing entries in the target region will remain.';
