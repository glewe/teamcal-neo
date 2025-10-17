<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: User
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['users_title'] = 'Users';
$LANG['users_alert_activate_selected_users'] = 'The selected users were activated.';
$LANG['users_alert_archive_selected_users'] = 'The selected users were archived.';
$LANG['users_alert_archive_selected_users_failed'] = 'One or more of the selected users already exist in the archive. This could be the same user or one with the same username.<br>Please delete these archived users first.';
$LANG['users_alert_delete_selected_users'] = 'The selected users were deleted.';
$LANG['users_alert_remove_secret_selected'] = 'The 2FA secrets of the selected users were removed.';
$LANG['users_alert_reset_password_selected'] = 'The passwords of the selected users were reset and a corresponding e-mail was sent to them.';
$LANG['users_alert_restore_selected_users'] = 'The selected users were restored.';
$LANG['users_alert_restore_selected_users_failed'] = 'One or more of the selected users already exist as active users. This could be the same user or one with the same username.<br>Please delete these active users first.';
$LANG['users_attributes'] = 'Attributes';
$LANG['users_attribute_locked'] = 'Account locked';
$LANG['users_attribute_hidden'] = 'Account hidden';
$LANG['users_attribute_onhold'] = 'Account on hold';
$LANG['users_attribute_secret'] = '2FA set up';
$LANG['users_attribute_verify'] = 'Account to be verified';
$LANG['users_confirm_activate'] = 'Are you sure you want to activate the selected users?<br>This will unhide, unhold, unlock and verify the selected users.';
$LANG['users_confirm_archive'] = 'Are you sure you want to archive the selected users?';
$LANG['users_confirm_delete'] = 'Are you sure you want to delete the selected users?';
$LANG['users_confirm_password'] = 'Are you sure you want to reset the passwords of the selected users? A reset password request email will be sent to them.';
$LANG['users_confirm_restore'] = 'Are you sure you want to restore the selected users?';
$LANG['users_confirm_secret'] = 'Are you sure you want to remove the 2FA secrets of the selected users? The users must/may then repeat the onboarding process.';
$LANG['users_created'] = 'Created';
$LANG['users_last_login'] = 'Last Login';
$LANG['users_tab_active'] = 'Active Users';
$LANG['users_tab_archived'] = 'Archived Users';
$LANG['users_user'] = 'User';
