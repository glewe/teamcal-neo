<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: Database page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['db_alert_delete'] = 'Record Deletion';
$LANG['db_alert_delete_success'] = 'The delete activities have been performed.';
$LANG['db_alert_failed'] = 'The operation could not be performed. Please check your input.';
$LANG['db_alert_cleanup'] = 'Clean up';
$LANG['db_alert_cleanup_success'] = 'The cleanup activities have been performed.';
$LANG['db_alert_optimize'] = 'Optimize Tables';
$LANG['db_alert_optimize_success'] = 'All database tables were optimized.';
$LANG['db_alert_repair'] = 'Repair Database';
$LANG['db_alert_repair_success'] = 'The repair activities have been performed.';
$LANG['db_alert_reset'] = 'Reset Database';
$LANG['db_alert_reset_fail'] = 'One or more queries failed. Your database maybe incomplete or corrupt.';
$LANG['db_alert_reset_success'] = 'Your database was successfully reset with sample data.';
$LANG['db_alert_url'] = 'Save Database URL';
$LANG['db_alert_url_fail'] = 'Please enter a valid URL for the database application.';
$LANG['db_alert_url_success'] = 'The database application URL was saved successfully.';
$LANG['db_application'] = 'Database Administration';
$LANG['db_clean_before'] = 'Before Date';
$LANG['db_clean_before_comment'] = 'Records from the above checked tables will be deleted when they are equal or older than the date selected here.';
$LANG['db_clean_confirm'] = 'Confirmation';
$LANG['db_clean_confirm_comment'] = 'Please type in "CLEANUP" to confirm this action.';
$LANG['db_clean_daynotes'] = 'Clean up daynotes before...';
$LANG['db_clean_holidays'] = 'Clean up holidays before...';
$LANG['db_clean_months'] = 'Clean up region calendars before...';
$LANG['db_clean_templates'] = 'Clean up user calendars before...';
$LANG['db_clean_what'] = 'What to clean up';
$LANG['db_clean_what_comment'] = 'Select here what you want to clean up. All records selected here that are equal or older then the "Before Date" will be deleted. Region and user calendars are deleted by month, independently from the day you enter. Newer records will stay in place.';
$LANG['db_confirm'] = 'Confirmation';
$LANG['db_dbURL'] = 'Database URL';
$LANG['db_dbURL_comment'] = 'You can specify a direct link to your preferred database management application for this website. The button below will link to it.';
$LANG['db_del_archive'] = 'Delete all archived records';
$LANG['db_del_confirm_comment'] = 'Please type in "DELETE" to confirm this action:';
$LANG['db_del_groups'] = 'Delete all groups';
$LANG['db_del_log'] = 'Delete all system log entries';
$LANG['db_del_messages'] = 'Delete all messages';
$LANG['db_del_orphMessages'] = 'Delete all orphaned messages';
$LANG['db_del_permissions'] = 'Delete custom permission schemes (except "Default")';
$LANG['db_del_users'] = 'Delete all users, their absence templates and daynotes (except "admin")';
$LANG['db_del_what'] = 'What to delete';
$LANG['db_del_what_comment'] = 'Select here what you want to delete.';
$LANG['db_optimize'] = 'Optimize Database Tables';
$LANG['db_optimize_comment'] = 'Reorganizes the physical storage of table data and associated index data, to reduce storage space and improve I/O efficiency when accessing the tables.';
$LANG['db_repair_confirm'] = 'Confirmation';
$LANG['db_repair_confirm_comment'] = 'Please type in "REPAIR" to confirm this action.';
$LANG['db_repair_daynoteRegions'] = 'Daynote Regions';
$LANG['db_repair_daynoteRegions_comment'] = 'This option checks whether there are daynotes without a region set. If so, the region will be set to Default.';
$LANG['db_reset_basic'] = 'Basic data';
$LANG['db_reset_danger'] = '<strong>Danger!</strong> Resetting the database will delete ALL your data!!';
$LANG['db_reset_sample'] = 'Basic plus sample data';
$LANG['db_resetDataset'] = 'Dataset to reset to';
$LANG['db_resetDataset_comment'] = 'Select the dataset that the database should be reset to. "Basic" is the default dataset of core data without sample data. "Sample" is the default dataset of core data with sample data.';
$LANG['db_resetString'] = 'Database Reset Confirmation String';
$LANG['db_resetString_comment'] = 'Resetting the database will replace all your information with the installation sample database.<br>Type the following in the text box to confirm your decision: "YesIAmSure".';
$LANG['db_tab_admin'] = 'Administration';
$LANG['db_tab_cleanup'] = 'Clean up';
$LANG['db_tab_dbinfo'] = 'Database Information';
$LANG['db_tab_delete'] = 'Delete records';
$LANG['db_tab_optimize'] = 'Optimize tables';
$LANG['db_tab_repair'] = 'Repair';
$LANG['db_tab_reset'] = 'Reset database';
$LANG['db_tab_tcpimp'] = 'TeamCal Pro Import';
$LANG['db_title'] = 'Database Maintenance';
