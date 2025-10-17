<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: Import
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['imp_title'] = 'CSV User Import';
$LANG['imp_file'] = 'CSV File';
$LANG['imp_alert_help'] = 'Find the documentation of the CSV import <a href="https://lewe.gitbook.io/teamcal-neo/administration/users/user-import" target="_blank">here</a>.';
$LANG['imp_alert_success'] = 'CSV import successful';
$LANG['imp_alert_success_text'] = '%s users were successfully imported.';
$LANG['imp_file_comment'] = 'Upload your CSV file. See details about the format <a href="https://lewe.gitbook.io/teamcal-neo/administration/users/user-import" target="_blank">here</a>. The size of the file is limited to %d KBytes and the allowed formats are "%s".';
$LANG['imp_group'] = 'Group';
$LANG['imp_group_comment'] = 'Select the group that the imported users shall be assigned to.';
$LANG['imp_role'] = 'Role';
$LANG['imp_role_comment'] = 'Select the role that the imported users shall be assigned to.';
$LANG['imp_hidden'] = 'Hidden';
$LANG['imp_hidden_comment'] = 'Select wether the imported users shall be set to hidden.';
$LANG['imp_locked'] = 'Locked';
$LANG['imp_locked_comment'] = 'Select wether the imported users shall be set to locked.';
