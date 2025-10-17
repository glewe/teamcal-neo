<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: Profile
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['profile_2fa_optional'] = 'TeamCal Neo allows you to set up a second factor authentication, e.g. using Google or Microsoft Authenticator on your mobil device.<br>
Click the button below to start the onboarding process. (This can only be done by the user himself.)';
$LANG['profile_abs_allowance'] = 'Allowance';
$LANG['profile_abs_allowance_tt'] = 'Specify an individual yearly allowance. 0 will take over the global value (in brackets).';
$LANG['profile_abs_allowance_tt2'] = 'Individual personal allowance. Global value in brackets.';
$LANG['profile_abs_carryover'] = 'Carryover';
$LANG['profile_abs_carryover_tt'] = 'The Carryover field also allows negative values and can be used to reduce the allowance for this user for the current year.';
$LANG['profile_abs_factor'] = 'Factor';
$LANG['profile_abs_name'] = 'Absence Type';
$LANG['profile_abs_remainder'] = 'Remainder';
$LANG['profile_abs_taken'] = 'Taken';
$LANG['profile_alert_archive_user'] = 'The user were archived.';
$LANG['profile_alert_archive_user_failed'] = 'The user already exist in the archive. This could be the same user or one with the same username.<br>Please delete the archived user first.';
$LANG['profile_alert_create'] = 'Create user profile';
$LANG['profile_alert_create_success'] = 'The new user account was created.';
$LANG['profile_alert_delete_user'] = 'The selected user was deleted.';
$LANG['profile_alert_save_failed'] = 'The new information for this user could not be saved. There was invalid input. Please check the tabs for error messages.';
$LANG['profile_alert_update'] = 'User profile update';
$LANG['profile_alert_update_success'] = 'The information for this user profile was updated.';
$LANG['profile_avatar'] = 'Avatar';
$LANG['profile_avatar_available'] = 'Available Standard Avatars';
$LANG['profile_avatar_available_comment'] = 'Choose one of the available avatars, courtesy of <a href="https://www.iconshock.com/icon_sets/vector-user-icons/" target="_blank">IconShock</a>.';
$LANG['profile_avatar_comment'] = 'If you haven\'t uploaded an own avatar, a default avatar will be used.';
$LANG['profile_avatar_upload'] = 'Upload avatar';
$LANG['profile_avatar_upload_comment'] = 'You can upload a custom avatar. The size of the file is limited to %d Bytes, the size of the image should be
 80x80 pixels (will be displayed in those dimensions anyways) and the allowed formats are "%s".';
$LANG['profile_calendarMonths'] = 'Calendar Months';
$LANG['profile_calendarMonths_comment'] = 'Select the amount of months to display on the calendar page.';
$LANG['profile_calendarMonths_default'] = 'Default';
$LANG['profile_calendarMonths_one'] = 'One';
$LANG['profile_calendarMonths_two'] = 'Two';
$LANG['profile_calfilterGroup'] = 'Default Group Filter';
$LANG['profile_calfilterGroup_comment'] = 'The calendar view can be filtered to a single group. This can be set here or on the calendar page itself.';
$LANG['profile_confirm_archive'] = 'Are you sure you want to archive this user?';
$LANG['profile_confirm_delete'] = 'Are you sure you want to delete this user?';
$LANG['profile_create_mail'] = 'Send notification E-mail';
$LANG['profile_create_mail_comment'] = 'Sends a notification E-mail to the created user.';
$LANG['profile_create_title'] = 'Create User Profile';
$LANG['profile_custom1'] = 'User custom field 1';
$LANG['profile_custom1_comment'] = 'Enter a text value of maximal 80 characters.';
$LANG['profile_custom2'] = 'User custom field 2';
$LANG['profile_custom2_comment'] = 'Enter a text value of maximal 80 characters.';
$LANG['profile_custom3'] = 'User custom field 3';
$LANG['profile_custom3_comment'] = 'Enter a text value of maximal 80 characters.';
$LANG['profile_custom4'] = 'User custom field 4';
$LANG['profile_custom4_comment'] = 'Enter a text value of maximal 80 characters.';
$LANG['profile_custom5'] = 'User custom field 5';
$LANG['profile_custom5_comment'] = 'Enter a text value of maximal 80 characters.';
$LANG['profile_defaultMenu'] = 'Menu Position';
$LANG['profile_defaultMenu_comment'] = 'The TeamCal Neo menu can either be shown horizontally at the top or vertically on the left. The vertical menu is only suited for wide screens while the horizontal menu
 also adjusts to narrow screens (responsive).';
$LANG['profile_defaultMenu_navbar'] = 'Horizontal Top';
$LANG['profile_defaultMenu_sidebar'] = 'Vertical Left';
$LANG['profile_edit_title'] = 'Edit profile: ';
$LANG['profile_email'] = 'E-mail';
$LANG['profile_email_comment'] = '';
$LANG['profile_facebook'] = 'Facebook';
$LANG['profile_facebook_comment'] = '';
$LANG['profile_firstname'] = 'Firstname';
$LANG['profile_firstname_comment'] = '';
$LANG['profile_gender'] = 'Gender';
$LANG['profile_gender_comment'] = '';
$LANG['profile_gender_female'] = 'Female';
$LANG['profile_gender_male'] = 'Male';
$LANG['profile_google'] = 'Google+';
$LANG['profile_google_comment'] = '';
$LANG['profile_guestships'] = 'Show in other groups';
$LANG['profile_guestships_comment'] = 'Show the calendar of this user in the selected groups, even if not a member (called a "guest membership"). Use this feature if the user is ' .
  'not a member but the absences are still important to see along with those of the selected groups.<br><i>Guest users will be shown in italic font in the calendar</i>.';
$LANG['profile_hidden'] = '<i class="far fa-eye-slash text-info" style="padding-right: 8px;"></i>Hide in calendar';
$LANG['profile_hidden_comment'] = 'With this option you can keep the user active but hide him in the calendar. The absences will still be counted in the statistics though. If that is
      not wanted, consider archiving this user.';
$LANG['profile_id'] = 'ID';
$LANG['profile_id_comment'] = '';
$LANG['profile_language'] = 'Language';
$LANG['profile_language_comment'] = 'Selects a custom language for the application interface.';
$LANG['profile_lastname'] = 'Lastname';
$LANG['profile_lastname_comment'] = '';
$LANG['profile_linkedin'] = 'LinkedIn';
$LANG['profile_linkedin_comment'] = '';
$LANG['profile_locked'] = '<i class="fas fa-lock text-danger" style="padding-right: 8px;"></i>Locked';
$LANG['profile_locked_comment'] = 'The account is locked. No login is possible.';
$LANG['profile_managerships'] = 'Manager of';
$LANG['profile_managerships_comment'] = 'Select the groups that this user is manager of. Should the same group be selected here and in the member list, then the manager position is saved.<br>
      The group manager will get group related notifications, e.g. absence approval requests.<br>
      Group managers do not have more permissions than members. Permissions are managed with roles.<br>
      <a href="https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/623738881/Group+Manager+Permission" target="_blank">Read more...</a>';
$LANG['profile_memberships'] = 'Member of';
$LANG['profile_memberships_comment'] = 'Select the groups that this user is member of. Should the same group be selected here and in the manager list, then the manager position is saved.';
$LANG['profile_menuBar'] = 'Menu Bar Display';
$LANG['profile_menuBar_comment'] = 'With this switch you can inverse the color set of the menu bar. For some themes this is the better choice.';
$LANG['profile_menuBar_default'] = 'Default';
$LANG['profile_menuBar_inverse'] = 'Inverse';
$LANG['profile_menuBar_normal'] = 'Normal';
$LANG['profile_mobilephone'] = 'Mobile';
$LANG['profile_mobilephone_comment'] = '';
$LANG['profile_notify'] = 'E-Mail Notifications';
$LANG['profile_notify_comment'] = 'Select the event types that you would like to be notified about via E-Mail. This includes add, change and delete actions. Add/remove entries by using Ctrl + Click.';
$LANG['profile_notifyAbsenceEvents'] = 'Absence Events';
$LANG['profile_notifyCalendarEvents'] = 'Calendar Events';
$LANG['profile_notifyGroupEvents'] = 'Group Events';
$LANG['profile_notifyHolidayEvents'] = 'Holidays Events';
$LANG['profile_notifyMonthEvents'] = 'Month Template Events';
$LANG['profile_notifyNone'] = 'None';
$LANG['profile_notifyRoleEvents'] = 'Role Events';
$LANG['profile_notifyUserCalEvents'] = 'User Calendar Events';
$LANG['profile_notifyUserCalEventsOwn'] = 'User Calendar Events (only my own)';
$LANG['profile_notifyUserCalGroups'] = 'User Calendar Event Groups';
$LANG['profile_notifyUserCalGroups_comment'] = 'If you have selected "' . $LANG['profile_notifyUserCalEvents'] . '" in the event list above, select here for which of your groups you want to receive these notifications.';
$LANG['profile_notifyUserEvents'] = 'User Account Events';
$LANG['profile_onhold'] = '<i class="far fa-clock text-warning" style="padding-right: 8px;"></i>On hold';
$LANG['profile_onhold_comment'] = 'This status is applied after a user has entered a wrong password too many times. This causes a grace period in which no login is possible.
 The grace period can be configured on the configuration page. You can manually release the status here as well.';
$LANG['profile_orderkey'] = 'Order Key';
$LANG['profile_orderkey_comment'] = 'You can use this text field to assign a sort value for this user other than the lastname (default). The order key is used to sort users in the calendar view if the administrator has switched that option on.';
$LANG['profile_password'] = 'Password';
$LANG['profile_password2'] = 'Confirm password';
$LANG['profile_password2_comment'] = 'Repeat the new password here.';
$LANG['profile_password_comment'] = 'You can enter a new password here. If the field stays empty, the current password will not be changed.<br>
      Allowed are small and capital letters, numbers and the following special characters: !@#$%^&amp;*().';
$LANG['profile_phone'] = 'Phone';
$LANG['profile_phone_comment'] = '';
$LANG['profile_position'] = 'Position';
$LANG['profile_position_comment'] = '';
$LANG['profile_region'] = 'Region';
$LANG['profile_region_comment'] = 'Select the region of this user here.';
$LANG['profile_remove2fa'] = 'Remove 2FA';
$LANG['profile_remove2fa_comment'] = 'You have already registered a second factor authenticator app. For security reasons, the onboarding information is not available anymore.
 If you need to remove this setup, e.g. when you replace your mobile device and need to do a new onboarding again, check this box and update your profile. The onboarding link will then be displayed here.';
$LANG['profile_role'] = 'Role';
$LANG['profile_role_comment'] = 'Select the role of this user here. The role defines the permissions of this user.';
$LANG['profile_showMonths'] = 'Show Multiple Months';
$LANG['profile_showMonths_comment'] = 'Enter the number of months to show on the calendar page, maximum 12.';
$LANG['profile_skype'] = 'Skype';
$LANG['profile_skype_comment'] = '';
$LANG['profile_tab_absences'] = 'Absences';
$LANG['profile_tab_account'] = 'Account';
$LANG['profile_tab_avatar'] = 'Avatar';
$LANG['profile_tab_contact'] = 'Contact';
$LANG['profile_tab_custom'] = 'Custom';
$LANG['profile_tab_groups'] = 'Groups';
$LANG['profile_tab_notifications'] = 'Notifications';
$LANG['profile_tab_password'] = 'Password';
$LANG['profile_tab_personal'] = 'Personal';
$LANG['profile_tab_tfa'] = '2FA';
$LANG['profile_theme'] = 'Theme';
$LANG['profile_theme_comment'] = 'Selects a custom theme for the application interface.';
$LANG['profile_title'] = 'Title';
$LANG['profile_title_comment'] = '';
$LANG['profile_twitter'] = 'X (Twitter)';
$LANG['profile_twitter_comment'] = '';
$LANG['profile_username'] = 'Loginname';
$LANG['profile_username_comment'] = 'The loginname cannot be changed for existing users.';
$LANG['profile_verify'] = '<i class="fas fa-exclamation-circle text-success" style="padding-right: 8px;"></i>Verify';
$LANG['profile_verify_comment'] = 'When a user has registered himself but did not use the activation link yet, this status is applied. The account is created but no login is possible yet.
      You can manually release the status here as well.';
$LANG['profile_view_title'] = 'Profile of: ';

//
// Setup 2FA
//
$LANG['setup2fa_title'] = 'Setup Two Factor Authentication for:';
$LANG['setup2fa_alert_input'] = 'Please enter a six digit numeric value in the authenticator code field.';
$LANG['setup2fa_alert_input_help'] = 'Please note that any previously generated barcode and secret code is not valid anymore for security reasons.
 Should you have created an entry in your authenticator app with it, you need to remove it and add a new one with the new information on this page.';
$LANG['setup2fa_alert_mismatch'] = 'The code you provided is incorrect.';
$LANG['setup2fa_alert_success'] = 'Your two factor authentication was successfully set up. Please proceed to the login page to log in again.';
$LANG['setup2fa_comment'] = 'In your authenticator app, add a new entry by scanning the above barcode (if one is shown) or entering the secret key manually.
 After adding the new entry, enter the next code generated by your app into the field below and click verify.';
$LANG['setup2fa_required_comment'] = 'The administrator has set up TeamCal Neo so that a two factor authentication is required to log in. Here you can do the onboarding process.
 You will need a mobile device and an authenticator app like Google Authenticator or Microsoft Authenticator.';
$LANG['setup2fa_totp'] = 'Your authenticator code<br><i>(six digits, numeric)</i>';
