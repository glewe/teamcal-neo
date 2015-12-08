<?php
/**
 * english.php
 * 
 * Language file (English)
 *
 * @category TeamCal Neo 
 * @version 0.3.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Common
 */
$LANG['absence'] = 'Absence Type';
$LANG['action'] = 'Action';
$LANG['all'] = 'All';
$LANG['auto'] = 'Automatic';
$LANG['avatar'] = 'Avatar';
$LANG['back_to_top'] = 'Back to top';
$LANG['blue'] = 'Blue';
$LANG['custom'] = 'Custom';
$LANG['cyan'] = 'Cyan';
$LANG['description'] = 'Description';
$LANG['diagram'] = 'Diagram';
$LANG['display'] = 'Display';
$LANG['from'] = 'From';
$LANG['general'] = 'General';
$LANG['green'] = 'Green';
$LANG['group'] = 'Group';
$LANG['license'] = 'Not available yet...';
$LANG['magenta'] = 'Magenta';
$LANG['monthnames'] = array (
   1 => "January",
   "February",
   "March",
   "April",
   "May",
   "June",
   "July",
   "August",
   "September",
   "October",
   "November",
   "December" 
);
$LANG['name'] = 'Name';
$LANG['none'] = 'None';
$LANG['options'] = 'Options';
$LANG['orange'] = 'Orange';
$LANG['period'] = 'Period';
$LANG['period_custom'] = 'Custom';
$LANG['period_month'] = 'Current month';
$LANG['period_quarter'] = 'Current quarter';
$LANG['period_half'] = 'Current half year';
$LANG['period_year'] = 'Current year';
$LANG['red'] = 'Red';
$LANG['region'] = 'Region';
$LANG['role'] = 'Role';
$LANG['role_admin'] = 'Administrator';
$LANG['role_director'] = 'Director';
$LANG['role_manager'] = 'Manager';
$LANG['role_assistant'] = 'Assistant';
$LANG['role_user'] = 'User';
$LANG['role_public'] = 'Public';
$LANG['scale'] = 'Scale';
$LANG['search'] = 'Search';
$LANG['select_all'] = 'Select all';
$LANG['settings'] = 'Settings';
$LANG['smart'] = 'Smart';
$LANG['to'] = 'To';
$LANG['today'] = 'Today';
$LANG['total'] = 'Total';
$LANG['type'] = 'Type';
$LANG['user'] = 'User';
$LANG['weekdayShort'] = array (
   1 => "Mo",
   "Tu",
   "We",
   "Th",
   "Fr",
   "Sa",
   "Su" 
);
$LANG['weekdayLong'] = array (
   1 => "Monday",
   "Tuesday",
   "Wednesday",
   "Thursday",
   "Friday",
   "Saturday",
   "Sunday" 
);
$LANG['weeknumber'] = 'Calendar week';

/**
 * About
 */
$LANG['about_version'] = 'Version';
$LANG['about_copyright'] = 'Copyright';
$LANG['about_license'] = 'License';
$LANG['about_forum'] = 'Forum';
$LANG['about_tracker'] = 'Issue Tracker';
$LANG['about_credits'] = 'Credits';
$LANG['about_for'] = 'for';
$LANG['about_and'] = 'and';
$LANG['about_misc'] = 'many users for testing and suggesting...';
$LANG['about_view_releaseinfo'] = 'Show/Hide Releaseinfo &raquo;';

/**
 * Absences
 */
$LANG['abs_list_title'] = 'Absence Types';
$LANG['abs_edit_title'] = 'Edit Absence Type: ';
$LANG['abs_icon_title'] = 'Select Absence Type Icon: ';
$LANG['abs_alert_edit'] = 'Update Absence Type';
$LANG['abs_alert_edit_success'] = 'The information for this absence type was updated.';
$LANG['abs_alert_created'] = 'The absence type was created.';
$LANG['abs_alert_created_fail'] = 'The abensce type could not be created. Please check the "Create absence type" dialog for input errors.';
$LANG['abs_alert_deleted'] = 'The absence type was deleted.';
$LANG['abs_alert_save_failed'] = 'The new information for this absence type could not be saved. There was invalid input. Please check for error messages.';
$LANG['abs_allowance'] = 'Allowance';
$LANG['abs_allowance_comment'] = 'Set an allowance for this absence type per year here. This amount refers to the current calendar year. When displaying
      a user profile the absence count section will contain the remaining amount for this absence type for the user (A negative value will indicate that the
      user has used too many absence days of this type.). If allowance is set to 0 no limit is assumed.';
$LANG['abs_approval_required'] = 'Approval required';
$LANG['abs_approval_required_comment'] = 'Checking this box defines that this absence type requires approval by the group manager, director or administrator.
      A regular user choosing this absence type in his calendar will receive an error message telling him so. The group manager of this user will receive
      an e-mail informing him that his approval is required for this request. He can then enter this absence for the user if he approves it.';
$LANG['abs_bgcolor'] = 'Background color';
$LANG['abs_bgcolor_comment'] = 'This is the background color used for this absence type, independent from symbol or icon. Click into the field to open the color picker.';
$LANG['abs_bgtrans'] = 'Background transparent';
$LANG['abs_bgtrans_comment'] = 'With this option on, the background color will be ignored.';
$LANG['abs_color'] = 'Text color';
$LANG['abs_color_comment'] = 'In case the character symbol is used, this is the color it is displayed in. Click into the field to open the color picker.';
$LANG['abs_confidential'] = 'Confidential';
$LANG['abs_confidential_comment'] = 'Checking this box marks this absence type a "confidential". The public and regular users cannot see this absence
      in the calendar, except it is the regular user\'s own absence. This feature is useful if you want to hide sensitive absence types from regular users.';
$LANG['abs_confirm_delete'] = 'Are you sure you want to delete the absence type "%s" ?<br>All existing entries in user templates will be replaced with "Present".';
$LANG['abs_counts_as'] = 'Counts as';
$LANG['abs_counts_as_comment'] = 'Select whether taken absences of this type count against the allowance of another absence type.
      If you select any other absence type the allowance of this absence type is not taken into account, but the allowance of the selected one.<br>
      Example: "Vacation half day" with factor 0.5 counts against the allowance of "Vacation".';
$LANG['abs_counts_as_present'] = 'Counts as present';
$LANG['abs_counts_as_present_comment'] = 'Checking this box defines that this absence type counts as "present". Let\'s say you maintain an absence
      type "Home Office" but since this person is working you do not want to count this as "absent". In that case check the box and all Home Office
      absences count as present in the summary count section. Thus, "Home Office" is also not listed in the absence type list in the summary count.';
$LANG['abs_display'] = 'Display';
$LANG['abs_display_comment'] = '';
$LANG['abs_factor'] = 'Factor';
$LANG['abs_factor_comment'] = 'TeamCal can count the amount of days taken per absence type. You can find the results in the "Absence" tab of the user
      profile dialog. The "Factor" field here offers the option to multiply each found absence with a value of your choice. The default is 1.<br>
      Example: You create an absence type called "Half Day Training". You would want to assign it the factor 0.5 in order to get the total count of
      training days. An employee that has taken 10 half training days would end up with a total of 5 (10 * 0.5 = 5).<br>
      Setting the factor to 0 will exclude the absence type from the count.';
$LANG['abs_groups'] = 'Group assignments';
$LANG['abs_groups_comment'] = 'Select the groups for which this absence type is valid. If a group is not assigned, members of that group cannot
      use this absence type.';
$LANG['abs_hide_in_profile'] = 'Hide in profile';
$LANG['abs_hide_in_profile_comment'] = 'Checking this box defines that regular users cannot see this absence type on the Absences tab of their profile.
      Only Managers, Directors or Administrator will see it there. This feature is useful if a manager wants to use an absence type for tracking
      purposes only or if the remainders are of no interest to regular users.';
$LANG['abs_icon'] = 'Icon';
$LANG['abs_icon_comment'] = 'The absence type icon is used in the calendar display.';
$LANG['abs_manager_only'] = 'Management only';
$LANG['abs_manager_only_comment'] = 'Checking this box defines that this absence type is only available to directors and managers. A regular
      member can see this absence type in his calendar but setting them will be refused. Only his manager or the director can check the boxes for him.
      This feature comes in handy if only the manager or director is supposed to manage this absence, e.g. vacation.';
$LANG['abs_name'] = 'Name';
$LANG['abs_name_comment'] = 'The absence type name is used in lists and descriptions and should tell what this absence type is about, e.g. "Duty trip". It can be 80 characters long.';
$LANG['abs_sample'] = 'Sample display';
$LANG['abs_sample_comment'] = 'This is how your absence type will look in your calendar based on your current settings.';
$LANG['abs_show_in_remainder'] = 'Show in remainder';
$LANG['abs_show_in_remainder_comment'] = 'The Calendar Display offers an expandable section to display the remaining allowance for each absence type for
      each user for the current year. Use this switch to decide which absence types shall be included in that display. If none of the absence types is
      marked for display in the remainder section then no expand/collapse button will be visible in the calendar display even though showing the remainder
      is generally switched on.<br>Note: It does not seem to make sense to include an absence type in the remainder display when the Factor is set to 0.
      The allowance and remaining allowance will always be the same.';
$LANG['abs_show_totals'] = 'Show totals';
$LANG['abs_show_totals_comment'] = 'The remainder section can be configured to also include a totals display for the current month. This totals
      section shows the sums of each absence type taken for the month displayed. Use this switch to include this absence type in that section.
      If none of the absence types is marked for display in the totals section then the totals section will not be shown at all.';
$LANG['abs_symbol'] = 'Symbol';
$LANG['abs_symbol_comment'] = 'The absence type symbol is used in the calendar display if no icon is set for this absence type. It is also used in
      notification e-mails. Chose a single character. A symbol is mandatory for each absence type, however, you are not restricted and can use the same
      character for mutliple absence types. The default is "A".';
$LANG['abs_tab_groups'] = 'Group Assignments';

/**
 * Alerts
 */
$LANG['alert_alert_title'] = $CONF['app_name'] . ' Alert';
$LANG['alert_danger_title'] = $CONF['app_name'] . ' Error';
$LANG['alert_info_title'] = $CONF['app_name'] . ' Information';
$LANG['alert_success_title'] = $CONF['app_name'] . ' Success';
$LANG['alert_warning_title'] = $CONF['app_name'] . ' Warning';

$LANG['alert_captcha_wrong'] = 'Captcha code wrong';
$LANG['alert_captcha_wrong_text'] = 'The Captcha code was incorrect. Please try again.';
$LANG['alert_captcha_wrong_help'] = 'The Captcha code must be entered as displayed. Capitalization is not relevant.';

$LANG['alert_controller_not_found_subject'] = 'Controller not found';
$LANG['alert_controller_not_found_text'] = 'The controller "%1%" could not be found.';
$LANG['alert_controller_not_found_help'] = 'Please check your installation. The file does not exist or you may not have permission to read it.';

$LANG['alert_input'] = 'Input validation failed';
$LANG['alert_input_alpha'] = 'This field allows alphabetical characters only.';
$LANG['alert_input_alpha_numeric'] = 'This field allows alphanumerical characters only.';
$LANG['alert_input_alpha_numeric_dash'] = 'This field allows alphanumerical characters only plus dash and underscore.';
$LANG['alert_input_alpha_numeric_dash_blank'] = 'This field allows alphanumerical characters only plus blank, dash and underscore.';
$LANG['alert_input_alpha_numeric_dash_blank_dot'] = 'This field allows alphanumerical characters only plus blank, dot, dash and underscore.';
$LANG['alert_input_alpha_numeric_dash_blank_special'] = 'This field allows alphanumerical characters only plus blank, dash, underscore and the
      special characters \'!@#$%^&*().';
$LANG['alert_input_date'] = 'The date must be in ISO 8601 format, e.g. 2014-01-01.';
$LANG['alert_input_email'] = 'The E-mail address is invalid.';
$LANG['alert_input_equal'] = 'The value of this field must be the same as in the field "%s".';
$LANG['alert_input_equal_string'] = 'The string in this field must be "%s".';
$LANG['alert_input_exact_length'] = 'The input of this field must be exactly "%s" characters.';
$LANG['alert_input_greater_than'] = 'The value of this field must be greater than the field "%s".';
$LANG['alert_input_hexadecimal'] = 'This field allows hexadecimal characters only.';
$LANG['alert_input_ip_address'] = 'The input of this field is not a valid IP address.';
$LANG['alert_input_less_than'] = 'The value of this field must be less than the field "%s".';
$LANG['alert_input_match'] = 'The field "%s" must match field "%s".';
$LANG['alert_input_max_length'] = 'The input of this field can have a maximum of "%s" characters.';
$LANG['alert_input_min_length'] = 'The input of this field must have a minimum of "%s" characters.';
$LANG['alert_input_numeric'] = 'The input of this field must be numeric.';
$LANG['alert_input_phone_number'] = 'The input in this field must be a valid phone number, e.g. (555) 123 4567 oder +49 172 123 4567.';
$LANG['alert_input_pwdlow'] = 'The password must be at least 4 characters long and can contain small and capital letters, numbers and the following special characters: !@#$%^&*()';
$LANG['alert_input_pwdmedium'] = 'The password must be at least 6 characters long, must contain at least one small letter, at least one capital letter and at least one number. 
      Allowed are small and capital letters, numbers and the following special characters: !@#$%^&*()';
$LANG['alert_input_pwdhigh'] = 'The password must be at least 8 characters long, must contain at least one small letter, at least one capital letter, at least one number and
      at least one special character. Allowed are small and capital letters, numbers and the following special characters: !@#$%^&*()';
$LANG['alert_input_regex_match'] = 'The input of this field did not match the regular expression "%s".';
$LANG['alert_input_required'] = 'This field is mandatory.';

$LANG['alert_maintenance_subject'] = 'Site Under Maintenance';
$LANG['alert_maintenance_text'] = 'The site is currently set to "Under Maintenance". Regular users will not be able to use any feature.';
$LANG['alert_maintenance_help'] = 'As an administrator you can set the site back to active under Administration -> Configuration -> System.';

$LANG['alert_no_data_subject'] = 'Invalid Data';
$LANG['alert_no_data_text'] = 'This operation was requested with invalid or insufficient data.';
$LANG['alert_no_data_help'] = 'The operation failed due to missing or invalid data.';

$LANG['alert_not_allowed_subject'] = 'Access not allowed';
$LANG['alert_not_allowed_text'] = 'You do not have permission to access this page.';
$LANG['alert_not_allowed_help'] = 'If you are not logged in, then public access to this page is not allowed. If you are logged in, your account role is not permitted to view this page.';

$LANG['alert_perm_invalid'] = 'The new permission scheme name "%1%" is invalid. Choose upper or lower case characters or numbers from 0 to 9. Don\'t use blanks.';
$LANG['alert_perm_exists'] = 'The permission scheme "%1%" already exists. Use a different name or delete the old one first.';

/**
 * Announcements
 */
$LANG['ann_title'] = 'Announcements for ';
$LANG['ann_confirm_all_confirm'] = 'Are you sure you want to confirm all your popup announcements?';
$LANG['ann_confirm_confirm'] = 'Are you sure you want to confirm announcement "%s" ? The announcement will not be deleted but not pop up at login anymore.';
$LANG['ann_delete_all_confirm'] = 'Are you sure you want to delete all announcements ?';
$LANG['ann_delete_confirm'] = 'Are you sure you want to delete announcement "%s" ?';
$LANG['ann_id'] = 'Announcement ID';
$LANG['ann_bday_title'] = 'Birthday Notification for ';

/**
 * Buttons
 */
$LANG['btn_abs_edit'] = 'Back to Edit';
$LANG['btn_abs_icon'] = 'Select Icon';
$LANG['btn_abs_list'] = 'Absence Type List';
$LANG['btn_activate'] = "Activate";
$LANG['btn_add'] = 'Add';
$LANG['btn_apply'] = 'Apply';
$LANG['btn_archive_selected'] = 'Archive selected';
$LANG['btn_assign'] = 'Assign';
$LANG['btn_assign_all'] = 'Assign All';
$LANG['btn_backup'] = 'Backup';
$LANG['btn_calendar'] = 'Calendar';
$LANG['btn_cancel'] = 'Cancel';
$LANG['btn_clear'] = 'Clear';
$LANG['btn_clear_all'] = 'Clear All';
$LANG['btn_close'] = 'Close';
$LANG['btn_confirm'] = 'Confirm';
$LANG['btn_confirm_all'] = 'Confirm All';
$LANG['btn_create'] = 'Create';
$LANG['btn_create_abs'] = 'Create Absence Type';
$LANG['btn_create_group'] = 'Create Group';
$LANG['btn_create_holiday'] = 'Create Holiday';
$LANG['btn_create_region'] = 'Create Region';
$LANG['btn_create_role'] = 'Create Role';
$LANG['btn_create_user'] = 'Create User';
$LANG['btn_delete'] = 'Delete';
$LANG['btn_delete_abs'] = 'Delete Absence Type';
$LANG['btn_delete_all'] = 'Delete All';
$LANG['btn_delete_group'] = 'Delete Group';
$LANG['btn_delete_holiday'] = 'Delete Holiday';
$LANG['btn_delete_records'] = 'Delete Records';
$LANG['btn_delete_role'] = 'Delete Role';
$LANG['btn_delete_selected'] = 'Delete Selected';
$LANG['btn_done'] = 'Done';
$LANG['btn_edit'] = 'Edit';
$LANG['btn_edit_profile'] = 'Edit Profile';
$LANG['btn_enable'] = 'Enable';
$LANG['btn_export'] = 'Export';
$LANG['btn_group_list'] = 'Show Group List';
$LANG['btn_help'] = 'Help';
$LANG['btn_holiday_list'] = 'Show Holiday List';
$LANG['btn_icon'] = 'Icon...';
$LANG['btn_import'] = 'Import';
$LANG['btn_install'] = 'Install';
$LANG['btn_login'] = 'Login';
$LANG['btn_logout'] = 'Logout';
$LANG['btn_merge'] = 'Merge';
$LANG['btn_next'] = 'Next';
$LANG['btn_optimize_tables'] = 'Optimize Tables';
$LANG['btn_prev'] = 'Prev';
$LANG['btn_refresh'] = 'Refresh';
$LANG['btn_region_list'] = 'Show Region List';
$LANG['btn_register'] = 'Register';
$LANG['btn_remove'] = 'Remove';
$LANG['btn_reset'] = 'Reset';
$LANG['btn_reset_password'] = 'Reset Password';
$LANG['btn_reset_password_selected'] = 'Reset password of selected';
$LANG['btn_restore'] = 'Restore';
$LANG['btn_restore_selected'] = 'Restore Selected';
$LANG['btn_role_list'] = 'Show Role List';
$LANG['btn_save'] = 'Save';
$LANG['btn_search'] = 'Search';
$LANG['btn_select'] = "Select";
$LANG['btn_send'] = 'Send';
$LANG['btn_showcalendar'] = 'Show calendar';
$LANG['btn_submit'] = 'Submit';
$LANG['btn_switch'] = 'Switch';
$LANG['btn_transfer'] = 'Transfer';
$LANG['btn_update'] = 'Update';
$LANG['btn_user_list'] = 'Show User List';
$LANG['btn_upload'] = 'Upload';
$LANG['btn_view'] = 'View';

/**
 * Calendar
 */
$LANG['cal_title'] = 'Calendar %s-%s (Region: %s)';
$LANG['cal_tt_backward'] = 'Go back one month...';
$LANG['cal_tt_forward'] = 'Go forward one month...';
$LANG['cal_search'] = 'Search User';
$LANG['cal_selAbsence'] = 'Select Absence';
$LANG['cal_selAbsence_comment'] = 'Shows all entries having this absence type for today.';
$LANG['cal_selGroup'] = 'Select Group';
$LANG['cal_selRegion'] = 'Select Region';
$LANG['cal_summary'] = 'Summary';

$LANG['cal_caption_weeknumber'] = 'Week';
$LANG['cal_caption_name'] = 'Name';
$LANG['cal_img_alt_edit_month'] = 'Edit holidays for this month...';
$LANG['cal_img_alt_edit_cal'] = 'Edit calender for this person...';
$LANG['cal_birthday'] = 'Birthday';
$LANG['cal_age'] = 'Age';
$LANG['sum_present'] = 'Present';
$LANG['sum_absent'] = 'Absent';
$LANG['sum_delta'] = 'Delta';
$LANG['sum_absence_summary'] = 'Absence Summary';
$LANG['sum_business_day_count'] = 'business days';
$LANG['remainder'] = 'Remainder';
$LANG['exp_summary'] = 'Expand Summary section...';
$LANG['col_summary'] = 'Collapse Summary section...';
$LANG['exp_remainder'] = 'Expand Remainder section...';
$LANG['col_remainder'] = 'Collapse Remainder section...';

/**
 * Calendar Edit
 */
$LANG['caledit_title'] = 'Edit month %s-%s for %s';
$LANG['caledit_absenceType'] = 'Absence Type';
$LANG['caledit_absenceType_comment'] = 'Select the absence type for this input.';
$LANG['caledit_alert_out_of_range'] = 'The dates entered were at least partially out of the currently displayed month. No changes were saved.';
$LANG['caledit_alert_save_failed'] = 'The absence information could not be saved. There was invalid input. Please check your last input.';
$LANG['caledit_alert_update'] = 'Update month';
$LANG['caledit_alert_update_all'] = 'All changes were accepted and the calendar was updated accordingly.';
$LANG['caledit_alert_update_partial'] = 'The changes were only partially accepted because some of the requested absences violate restrictions set 
      up by the management. The calendar was updated with the accepted absences. The following requests were declined:';
$LANG['caledit_alert_update_none'] = 'The changes were not accepted because the requested absences violate restrictions set up by the management. 
      The calendar was not updated.';
$LANG['caledit_clearAbsence'] = 'Clear';
$LANG['caledit_confirm_clearall'] = 'Are you sure you want to clear all absences in this month?<br><br><strong>Year:</strong> %s<br><strong>Month:</strong> %s<br><strong>User:</strong> %s';
$LANG['caledit_currentAbsence'] = 'Current absence';
$LANG['caledit_endDate'] = 'End Date';
$LANG['caledit_endDate_comment'] = 'Select the end date (must be in this month).';
$LANG['caledit_Period'] = 'Period';
$LANG['caledit_PeriodTitle'] = 'Select Absence Period';
$LANG['caledit_Recurring'] = 'Recurring';
$LANG['caledit_RecurringTitle'] = 'Select Recurring Absence';
$LANG['caledit_recurrence'] = 'Recurrence';
$LANG['caledit_recurrence_comment'] = 'Select the recurrence';
$LANG['caledit_selRegion'] = 'Select Region';
$LANG['caledit_selUser'] = 'Select User';
$LANG['caledit_startDate'] = 'Start Date';
$LANG['caledit_startDate_comment'] = 'Select the start date (must be in this month).';

/**
 * Calendar Options
 */
$LANG['calopt_title'] = 'Calendar Options';

$LANG['calopt_tab_display'] = 'Display';
$LANG['calopt_tab_filter'] = 'Filter';
$LANG['calopt_tab_options'] = 'Settings';
$LANG['calopt_tab_remainder'] = 'Remainder';
$LANG['calopt_tab_summary'] = 'Summary';

$LANG['calopt_defgroupfilter'] = 'Default Group Filter';
$LANG['calopt_defgroupfilter_comment'] = ' Select the default group filter for the calendar display. Each user can still change his individual default filter in his profile.';
$LANG['calopt_defgroupfilter_all'] = 'All';
$LANG['calopt_defgroupfilter_allbygroup'] = 'All (by group)';
$LANG['calopt_defregion'] = 'Default Region for Base Calendar';
$LANG['calopt_defregion_comment'] = 'Select the default region for the base calendar to be used. Each user can still change his individual default region in his profile.';
$LANG['calopt_firstDayOfWeek'] = 'First Day of Week';
$LANG['calopt_firstDayOfWeek_comment'] = 'Set this to Monday or Sunday. This setting will be reflected in the week number display.';
$LANG['calopt_firstDayOfWeek_1'] = 'Monday';
$LANG['calopt_firstDayOfWeek_7'] = 'Sunday';
$LANG['calopt_hideDaynotes'] = 'Hide Personal Daynotes';
$LANG['calopt_hideDaynotes_comment'] = 'Switching this on will hide personal daynotes from regular users. Only Managers, Directors and Administrators can edit and see them. 
      That way the can be used for managing purposes only. This switch does not affect birthday notes.';
$LANG['calopt_hideManagers'] = 'Hide Managers in All-by-Group and Group Display';
$LANG['calopt_hideManagers_comment'] = 'Checking this option will hide all managers in the All-by-Group and Group display except in those groups where they are just members.';
$LANG['calopt_hideManagerOnlyAbsences'] = 'Hide Management Only Absences';
$LANG['calopt_hideManagerOnlyAbsences_comment'] = 'Absence types can be marked as "manager-only", making them only editable to managers. 
      These absences are shown to the regular users but they cannot edit them. You can hide these absences to regular users here.';
$LANG['calopt_includeRemainder'] = 'Include Remainder';
$LANG['calopt_includeRemainder_comment'] = 'Checking this option will add an expandable column to the calendar display showing each users\'s remainder per 
      absence type.<br>Note: You need to configure the absence types that you want to be included in the remainder column.';
$LANG['calopt_includeRemainderTotal'] = 'Include Remainder Allowance';
$LANG['calopt_includeRemainderTotal_comment'] = 'Checking this option will add the total allowance per absence type to the expandable remainder display. 
      The value is seperated by a slash.';
$LANG['calopt_includeSummary'] = 'Include Summary';
$LANG['calopt_includeSummary_comment'] = 'Checking this option will add an expandable summary section at the bottom of each month, showing the sums of all absences.';
$LANG['calopt_includeTotals'] = 'Include Remainder Totals';
$LANG['calopt_includeTotals_comment'] = 'Checking this option will add a "totals this month" section in the remainder column showing each users\'s totals 
      per absence type for the month displayed. Note: You need to configure the absence types that you want to be included in the totals column.';
$LANG['calopt_markConfidential'] = 'Mark Confidential Absences';
$LANG['calopt_markConfidential_comment'] = 'Regular users cannot see confidential absences of others. However, with this option 
      checked they will be marked with an "X" in the calendar to show that the person is not present. The type of absence will not be shown.';
$LANG['calopt_pastDayColor'] = 'Past Day Color';
$LANG['calopt_pastDayColor_comment'] = 'Sets a background color that is used for every day in the current month that lies in the past. 
      Delete this value if you don\'t want to color the past days.';
$LANG['calopt_repeatHeaderCount'] = 'Repeat Header Count';
$LANG['calopt_repeatHeaderCount_comment'] = 'Specifies the amount of user lines in the calender before the month header is repeated for better readability.';
$LANG['calopt_satBusi'] = 'Saturday is a Business Day';
$LANG['calopt_satBusi_comment'] = 'By default, Saturday and Sunday are weekend days and displayed accordingly in the calendar. 
      Check this option if you want to make Saturday a business day.';
$LANG['calopt_showMonths'] = 'Amount of Months';
$LANG['calopt_showMonths_comment'] = 'Specify here how many months you want to display in the calendar view by default.';
$LANG['calopt_showMonths_1'] = '1 month';
$LANG['calopt_showMonths_2'] = '2 months';
$LANG['calopt_showMonths_3'] = '3 months';
$LANG['calopt_showMonths_6'] = '6 months';
$LANG['calopt_showMonths_12'] = '12 months';
$LANG['calopt_showRemainder'] = 'Expand Remainder';
$LANG['calopt_showRemainder_comment'] = 'Checking this option will show/expand the remainder section by default.';
$LANG['calopt_showSummary'] = 'Expand Summary';
$LANG['calopt_showSummary_comment'] = 'Checking this option will show/expand the summary section by default.';
$LANG['calopt_showUserRegion'] = 'Show regional holidays per user';
$LANG['calopt_showUserRegion_comment'] = 'If this option is on, the calendar will show the regional holidays in each user row based on the default region 
      set for the user. These holidays might then differ from the global regional holidays shown in the month header. This offers a better view on regional 
      holiday differences if you manage users from different regions. Note, that this might might be a bit confusing depending on the amount of users and regions. 
      Check it out and pick your choice.';
$LANG['calopt_showWeekNumbers'] = 'Show Week Numbers';
$LANG['calopt_showWeekNumbers_comment'] = 'Checking this option will add a line to the calendar display showing the week of the year number.';
$LANG['calopt_sunBusi'] = 'Sunday is a Business Day';
$LANG['calopt_sunBusi_comment'] = 'By default, Saturday and Sunday are weekend days and displayed accordingly in the calendar.
      Check this option if you want to make Sunday a business day.';
$LANG['calopt_supportMobile'] = 'Support Mobile Devices';
$LANG['calopt_supportMobile_comment'] = 'With this switch on, the calendar view will prepare several versions of the month tables for the most
      common screen sizes. The browser will automatically display the one that best fits the screen. Downside is, that the page will take longer to load. 
      Switch this off if the calendar is only viewed on full size computer screens. The calendar will still be displayed on mobile devices but horizontal
      scrolling will be necessary.';
$LANG['calopt_todayBorderColor'] = 'Today Border Color';
$LANG['calopt_todayBorderColor_comment'] = 'Specifies the color in hexadecimal of the left and right border of the today column.';
$LANG['calopt_todayBorderSize'] = 'Today Border Size';
$LANG['calopt_todayBorderSize_comment'] = 'Specifies the size (thickness) in pixel of the left an right border of the today column.';
$LANG['calopt_usersPerPage'] = 'Number of users per page';
$LANG['calopt_usersPerPage_comment'] = 'If you maintain a large amount of users in TeamCal Neo you might want to use paging in the calendar display.
      Indicate how much users you want to display on each page. A value of 0 will disable paging. In case you chose paging, there will be paging
      buttons at the bottom of each page.';
$LANG['calopt_userSearch'] = 'Show User Search Box';
$LANG['calopt_userSearch_comment'] = 'Enable/Disable a user search box in the Calendar view, enabling to search for single users.';

/**
 * Config
 */
$LANG['config_title'] = $appTitle.' Configuration';

$LANG['config_email'] = 'E-mail';
$LANG['config_login'] = 'Login';
$LANG['config_registration'] = 'Registration';
$LANG['config_stats'] = 'Statistics';
$LANG['config_system'] = 'System';
$LANG['config_tab_theme'] = 'Theme';
$LANG['config_user'] = 'User';

$LANG['config_activateMessages'] = 'Activate Message Center';
$LANG['config_activateMessages_comment'] = 'This option will activate the Message Center. User can use it to send announcements or e-mails to other
      users or groups. An entry in the Tools menu will be added.';
$LANG['config_adminApproval'] = 'Require Admin Approval';
$LANG['config_adminApproval_comment'] = 'The administrator will receive an e-mail about each user self-registration. He manually needs to confirm the account.';
$LANG['config_allowRegistration'] = 'Allow User Self-Registration';
$LANG['config_allowRegistration_comment'] = 'Allow users to self-register their account. A menu entry will be available in the '.$appTitle.' menu.';
$LANG['config_allowUserTheme'] = 'Allow User Theme';
$LANG['config_allowUserTheme_comment'] = 'Check whether you want each user to be able to select his individual theme.';
$LANG['config_appTitle'] = 'Application Title';
$LANG['config_appTitle_comment'] = 'Select the application title here. It is used at several locations, e.g. the menu and other pages where the title is referenced.';
$LANG['config_appFooterCpy'] = 'Application Footer Copyright';
$LANG['config_appFooterCpy_comment'] = 'Will be displayed in the upper left footer section.';
$LANG['config_avatarHeight'] = 'Avatar Max Height';
$LANG['config_avatarHeight_comment'] = 'Specifies the maximum height in pixel of the avatar image. Avatar images with a larger height will be 
      resized to this height while adjusting the width proportionally.';
$LANG['config_avatarMaxSize'] = 'Avatar Max Size';
$LANG['config_avatarMaxSize_comment'] = 'Specifies the maximum files size in Bytes for the avatar image file.';
$LANG['config_avatarWidth'] = 'Avatar Max Width';
$LANG['config_avatarWidth_comment'] = 'Specifies the maximum width in pixel of the avatar image. Avatar images with a larger width will be 
      resized to this width while adjusting the height proportionally.';
$LANG['config_badLogins'] = 'Bad Logins';
$LANG['config_badLogins_comment'] = 'Number of bad login attempts that will cause the user status to be set to \'LOCKED\'. The user has to wait as long 
      as the grace period specifies before he can login again. If you set this value to 0 the bad login feature is disabled.';
$LANG['config_cookieLifetime'] = 'Cookie Lifetime';
$LANG['config_cookieLifetime_comment'] = 'Upon successful login a cookie is stored on the local hard drive of the user. This cookie has a certain 
      lifetime after which it becomes invalid. A new login is necessary. This lifetime can be specified here in seconds (0-999999).';
$LANG['config_defaultLanguage'] = 'Default Language';
$LANG['config_defaultLanguage_comment'] = $appTitle . ' is distributed in English and German. The adminstrator might have added more languages. 
      Chose the default language of your installation here.';
$LANG['config_emailConfirmation'] = 'Require e-mail Confirmation';
$LANG['config_emailConfirmation_comment'] = 'Upon registration the user will receive an e-mail to the address he specified containing a confirmation link. 
      He needs to follow that link to validate his information.';
$LANG['config_emailNotifications'] = 'E-Mail Notifications';
$LANG['config_emailNotifications_comment'] = 'Enable/Disable E-Mail notifications. If you uncheck this option no automated notifications E-Mails are sent. 
      However, this does not apply to self-registration mails and to manually sent mails via the Message Center and the Viewprofile dialog.';
$LANG['config_faCDN'] = 'Fontawesome CDN';
$LANG['config_faCDN_comment'] = 'CDNs (Content Distributed Network) can offer a performance benefit by hosting popular web modules on servers spread 
      across the globe. Fontawesome is such a module. Pulling it from a CDN location also offers an advantage that if the visitor 
      to your webpage has already downloaded a copy of it from the same CDN, it won\'t have to be re-downloaded.<br>This option only works with an
      Internet connection of course. Switch this option off if you are running the application in an environment without Internet connectivity.';
$LANG['config_googleAnalytics'] = 'Google Analytics';
$LANG['config_googleAnalytics_comment'] = $appTitle . ' supports Google Analytics. If you run your instance in the Internet and want to use Google Analytics 
      to trace access to it, you can check this box and enter your Google Analytics ID below. The corresponding Javascript code will be added automatically.';
$LANG['config_googleAnalyticsID'] = "Google Analytics ID";
$LANG['config_googleAnalyticsID_comment'] = "If you enabled the Google Analytics feature, enter your Google Analytics ID here in the format UA-999999-99.";
$LANG['config_gracePeriod'] = 'Grace Period';
$LANG['config_gracePeriod_comment'] = 'The amount of time in seconds that a user has to wait after too many bad logins before he can try again.';
$LANG['config_homepage'] = 'Homepage';
$LANG['config_homepage_comment'] = 'Select what page to display as the Home page.';
$LANG['config_homepage_home'] = 'Welcome Page';
$LANG['config_homepage_news'] = 'News Page';
$LANG['config_jQueryCDN'] = 'jQuery CDN';
$LANG['config_jQueryCDN_comment'] = 'CDNs (Content Distributed Network) can offer a performance benefit by hosting popular web modules on servers spread 
      across the globe. jQuery is such a module. Pulling it from a CDN location also offers an advantage that if the visitor 
      to your webpage has already downloaded a copy of jQuery from the same CDN, it won\'t have to be re-downloaded.<br>Switch this option off if you are 
      running the application in an environment with no Internet connectivity.';
$LANG['config_jqtheme'] = 'jQuery UI Theme';
$LANG['config_jqtheme_comment'] = $appTitle . ' uses jQuery UI, a popular collection of Javascript utilities. jQuery UI offers themes as well used for the display 
      of the tabbed dialogs and other features. The default theme is "smoothness" which is a neutral gray shaded theme. Try more from the list, some of them are 
      quite colorful. This is a global setting, users cannot choose an indiviual jQuery UI theme.';
$LANG['config_logLanguage'] = "Log Language";
$LANG['config_logLanguage_comment'] = "This setting sets the language for the system log entries.";
$LANG['config_mailFrom'] = 'Mail From';
$LANG['config_mailFrom_comment'] = 'Specify a name to be shown as sender of notification e-mails.';
$LANG['config_mailReply'] = 'Mail Reply-To';
$LANG['config_mailReply_comment'] = 'Specify an e-mail address to reply to for notification e-mails. This field must contain a valid e-mail address. 
      If that is not the case a dummy e-mail address will be saved.';
$LANG['config_mailSMTP'] = 'Use external SMTP server';
$LANG['config_mailSMTP_comment'] = 'Use an external SMTP server instead of the PHP mail() function to send out eMails. This feature requires the PEAR 
      Mail package to be installed on your server. Many hosters install this package by default. It is also necessary for SMTP to work, that your TcNeo 
      server can connect to the selected SMTP server via the usual SMTP ports 25, 465 or 587, using plain SMTP or TLS/SSL protocol, depending on your settings. 
      Some hosters have this communication closed down by firewall rules. You will get a connection error then.';
$LANG['config_mailSMTPhost'] = 'SMTP Host';
$LANG['config_mailSMTPhost_comment'] = 'Specify the SMTP host name.';
$LANG['config_mailSMTPport'] = 'SMTP Port';
$LANG['config_mailSMTPport_comment'] = 'Specify the SMTP host port.';
$LANG['config_mailSMTPusername'] = 'SMTP Username';
$LANG['config_mailSMTPusername_comment'] = 'Specify the SMTP username.';
$LANG['config_mailSMTPpassword'] = 'SMTP Password';
$LANG['config_mailSMTPpassword_comment'] = 'Specify the SMTP password.';
$LANG['config_mailSMTPSSL'] = 'SMTP TLS/SSL protocol';
$LANG['config_mailSMTPSSL_comment'] = 'Use the TLS/SSL protocol for the SMTP connection';
$LANG['config_menuBarInverse'] = 'Menu Bar Inverse';
$LANG['config_menuBarInverse_comment'] = 'With this switch you can inverse the color set of the menu bar. For some themes this is the better choice.';
$LANG['config_permissionScheme'] = 'Permission Scheme';
$LANG['config_permissionScheme_comment'] = 'The permission defines who can do what. The permisson schemes can be configured on the permissions page.';
$LANG['config_pwdStrength'] = 'Password Strength';
$LANG['config_pwdStrength_comment'] = '<p>The password strength defines how picky you wanna be with the password check. Allowed are small and capital letters, numbers and the following special characters: !@#$%^&*() 
      <ul>
         <li><strong>Low:</strong> At least 4 characters long</li>
         <li><strong>Medium:</strong> At least 6 characters long, one small letter, one capital letter and one number</li>
         <li><strong>High:</strong> At least 8 characters long, one small letter, one capital letter, one number and one special character</li>
      </ul></p>';
$LANG['config_pwdStrength_low'] = 'Low';
$LANG['config_pwdStrength_medium'] = 'Medium';
$LANG['config_pwdStrength_high'] = 'High';
$LANG['config_showAlerts'] = 'Show Alerts';
$LANG['config_showAlerts_comment'] = 'Select what type of alerts will be shown.';
$LANG['config_showAlerts_all'] = 'All (including Success messages)';
$LANG['config_showAlerts_warnings'] = 'Warnings and Errors only';
$LANG['config_showAlerts_none'] = 'None';
$LANG['config_showAvatars'] = 'Show Avatars';
$LANG['config_showAvatars_comment'] = 'Checking this option will show a user avatar pop-up when moving the mouse over the user icon. 
      Note: This feature only works when user icons are switched on.';
$LANG['config_showUserIcons'] = 'Show User Icons';
$LANG['config_showUserIcons_comment'] = 'Checking this option will show user icons to the left of the users\' name indicating the users\' role and gender.';
$LANG['config_statsScale'] = 'Diagram Scale';
$LANG['config_statsScale_comment'] = 'Select a diagram scale option for the statistics pages.
      <ul>
         <li>Automatic: The diagram\'s max value is the actual maximal value.</li>
         <li>Smart: The diagram\'s max value is the actual maximal value plus the Smart Value.</li>
      </ul>';
$LANG['config_statsSmartValue'] = 'Diagram Scale Smart Value';
$LANG['config_statsSmartValue_comment'] = 'The smart value is added to the maximal value read and the sum is used as the diagram\'s scale maximum.<br>This value only applies if the diagram scale is set to "'.$LANG['smart'].'".';
$LANG['config_theme'] = 'Default Theme';
$LANG['config_theme_comment'] = 'Select a theme to change the looks of the application. You can create your own skin by making a renamed
      copy of any of the other theme folders in the \'themes\' directory and adjust the styles to your liking. Your new directory will
      automatically be listed here. Make sure you do not copy the \'bootstrap\' folder. It is just a dummy folder for the core theme.';
$LANG['config_timeZone'] = 'Time Zone';
$LANG['config_timeZone_comment'] = 'If your web server resides in a different time zone than your users you can adjust the user time zone here.';
$LANG['config_underMaintenance'] = 'Under Maintenance';
$LANG['config_underMaintenance_comment'] = 'With this switch the site is set into maintenance mode. Only the admin can login and access pages.
      Regular users will see an "Under Maintenance" message.';
$LANG['config_userCustom1'] = 'User Custom Field 1 Caption';
$LANG['config_userCustom1_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['config_userCustom2'] = 'User Custom Field 2 Caption';
$LANG['config_userCustom2_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['config_userCustom3'] = 'User Custom Field 3 Caption';
$LANG['config_userCustom3_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['config_userCustom4'] = 'User Custom Field 4 Caption';
$LANG['config_userCustom4_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['config_userCustom5'] = 'User Custom Field 5 Caption';
$LANG['config_userCustom5_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['config_userManual'] = 'User Manual';
$LANG['config_userManual_comment'] = $appTitle . '\'s user manual is maintained in English and is available at the '.$appTitle.' community site.
      Translations might be available authored by the community. If your language is available, change the link to it here. If you are interested
      in participating or creating a translation, register at the <a href="https://georgelewe.atlassian.net" target="_blank">'.$appTitle.' community
      site (https://georgelewe.atlassian.net)</a> and create a task in the issue tracker for it. If you leave this field empty, a default link will be used.';
$LANG['config_welcomeIcon'] = 'Welcome Page Icon';
$LANG['config_welcomeIcon_comment'] = 'You can choose to display an icon next to the welcome text. It will be placed at the top left and the text will flow around it. 
      Select the size in the drop down list.';
$LANG['config_welcomeText'] = 'Welcome Page Text';
$LANG['config_welcomeText_comment'] = 'Enter a text for the welcome message on the welcome page. This field allows the usage of the
      HTML tags &lt;em&gt; and &lt;strong&gt;. Line breaks will be translated into &lt;br&gt; tags automatically. All other HTML tags will be stripped.';
$LANG['config_welcomeTitle'] = 'Welcome Page Title';
$LANG['config_welcomeTitle_comment'] = 'Enter a title for the welcome message on the welcome page. This field allows the usage of the 
      HTML tags &lt;em&gt; and &lt;strong&gt;. Line breaks will be translated into &lt;br&gt; tags automatically. All other HTML tags will be stripped.';

/**
 * Database
 */
$LANG['db_title'] = 'Database Maintenance';
$LANG['db_tab_cleanup'] = 'Clean up';
$LANG['db_tab_delete'] = 'Delete records';
$LANG['db_tab_export'] = 'Export/Import';
$LANG['db_tab_optimize'] = 'Optimize tables';

$LANG['db_alert_delete'] = 'Record Deletion';
$LANG['db_alert_delete_success'] = 'The delete activities have been performed.';
$LANG['db_alert_failed'] = 'The operation could not be performed. Please check your input.';
$LANG['db_alert_optimize'] = 'Optimize Tables';
$LANG['db_alert_optimize_success'] = 'All database tables were optimized.';
$LANG['db_confirm'] = 'Confirmation';
$LANG['db_del_archive'] = 'Delete all archived records';
$LANG['db_del_groups'] = 'Delete all groups';
$LANG['db_del_log'] = 'Delete all system log entries';
$LANG['db_del_messages'] = 'Delete all messages';
$LANG['db_del_orphMessages'] = 'Delete all orphaned messages';
$LANG['db_del_permissions'] = 'Delete custom permission schemes (except "Default")';
$LANG['db_del_users'] = 'Delete all users, their absence templates and daynotes (except "admin")';
$LANG['db_del_confirm_comment'] = 'Please type in "DELETE" to confirm this action:';
$LANG['db_del_what'] = 'What to delete';
$LANG['db_del_what_comment'] = 'Select here what you want to delete.';
$LANG['db_export'] = 'Export/Import Database';
$LANG['db_export_comment'] = 'Due to security concerns and to restrictions in PHP based database export/import routines, please use a dedicated application like phpMyAdmin
      to perform exports and imports of the database.';
$LANG['db_optimize'] = 'Optimize Database Tables';
$LANG['db_optimize_comment'] = 'Reorganizes the physical storage of table data and associated index data, to reduce storage space and improve I/O efficiency when accessing the tables.';

/**
 * Declination
 */
$LANG['decl_title'] = 'Declination Management';
$LANG['decl_absence'] = 'Activate';
$LANG['decl_absence_comment'] = 'You can setup an absence threshold declination rule below. Activate this rule here.';
$LANG['decl_alert_period_wrong'] = 'When specifying a period, the start date must be before the end date.';
$LANG['decl_alert_period_missing'] = 'When specifying a period, both date fields must be filled in.';
$LANG['decl_alert_save'] = 'Save Declination Settings';
$LANG['decl_alert_save_success'] = 'The declination settings were saved.';
$LANG['decl_alert_save_failed'] = 'The settings could not be saved. There was invalid input. Please check for error messages.';
$LANG['decl_applyto'] = 'Apply Declination To';
$LANG['decl_applyto_comment'] = 'Select whether the declination management shall apply to regular users only or to managers and directors as well. Declination management does not apply to administrators.';
$LANG['decl_applyto_regular'] = 'Regular users only';
$LANG['decl_applyto_all'] = 'All users (but administrators)';
$LANG['decl_base'] = 'Threshold base';
$LANG['decl_base_comment'] = 'Select the base of the threshold here.';
$LANG['decl_base_all'] = 'All';
$LANG['decl_base_group'] = 'Group';
$LANG['decl_before'] = 'Activate';
$LANG['decl_before_comment'] = 'You can setup the declination of absence requests lying before a certain date below. Activate this rule here.';
$LANG['decl_beforedate'] = 'Before date';
$LANG['decl_beforedate_comment'] = 'Enter a custom before date here. This is only effective if the option "Before date" was selected above.';
$LANG['decl_beforeoption'] = 'Before option';
$LANG['decl_beforeoption_comment'] = 'Select "Before today" to decline absence requests lying in the past. You can also chose a certain date by selecting
      "Before date" and entering the date below.';
$LANG['decl_beforeoption_today'] = 'Before today (not included)';
$LANG['decl_beforeoption_date'] = 'Before date (not included)';
$LANG['decl_period1'] = 'Activate';
$LANG['decl_period1_comment'] = 'You can setup a declination period below. The start and end date you pick here is included in that period.
      Activate this rule here.';
$LANG['decl_period1start'] = 'Start date (including)';
$LANG['decl_period1start_comment'] = 'Enter the start date here.';
$LANG['decl_period1end'] = 'End date (including)';
$LANG['decl_period1end_comment'] = 'Enter the end date here.';
$LANG['decl_period2'] = 'Activate';
$LANG['decl_period2_comment'] = 'You can setup a declination period below. The start and end date you pick here is included in that period.
      Activate this rule here.';
$LANG['decl_period2start'] = 'Start date (including)';
$LANG['decl_period2start_comment'] = 'Enter the start date here.';
$LANG['decl_period2end'] = 'End date (including)';
$LANG['decl_period2end_comment'] = 'Enter the end date here.';
$LANG['decl_period3'] = 'Activate';
$LANG['decl_period3_comment'] = 'You can setup a declination period below. The start and end date you pick here is included in that period.
      Activate this rule here.';
$LANG['decl_period3start'] = 'Start date (including)';
$LANG['decl_period3start_comment'] = 'Enter the start date here.';
$LANG['decl_period3end'] = 'End date (including)';
$LANG['decl_period3end_comment'] = 'Enter the end date here.';
$LANG['decl_roles'] = 'Apply to Roles';
$LANG['decl_roles_comment'] = 'Select the roles to which the declination rules will apply.';
$LANG['decl_tab_absence'] = 'Absence Threshold';
$LANG['decl_tab_before'] = 'Before Date';
$LANG['decl_tab_period1'] = 'Period 1';
$LANG['decl_tab_period2'] = 'Period 2';
$LANG['decl_tab_period3'] = 'Period 3';
$LANG['decl_tab_scope'] = 'Scope';
$LANG['decl_threshold'] = 'Threshold (%)';
$LANG['decl_threshold_comment'] = 'Enter the threshold in percent here. An absence request will be declined if it would cause this threshold to be reached.';

/**
 * E-Mail
 */
$LANG['email_subject_group_changed'] = $CONF['app_name'] . ' Group Changed';
$LANG['email_subject_group_created'] = $CONF['app_name'] . ' Group Created';
$LANG['email_subject_group_deleted'] = $CONF['app_name'] . ' Group Deleted';
$LANG['email_subject_month_changed'] = $CONF['app_name'] . ' Month Changed';
$LANG['email_subject_month_created'] = $CONF['app_name'] . ' Month Created';
$LANG['email_subject_month_deleted'] = $CONF['app_name'] . ' Month Deleted';
$LANG['email_subject_role_changed'] = $CONF['app_name'] . ' Role Changed';
$LANG['email_subject_role_created'] = $CONF['app_name'] . ' Role Created';
$LANG['email_subject_role_deleted'] = $CONF['app_name'] . ' Role Deleted';
$LANG['email_subject_user_account_changed'] = $CONF['app_name'] . ' User Account Changed';
$LANG['email_subject_user_account_created'] = $CONF['app_name'] . ' User Account Created';
$LANG['email_subject_user_account_deleted'] = $CONF['app_name'] . ' User Account Deleted';
$LANG['email_subject_user_account_registered'] = $CONF['app_name'] . ' User Account Registered';

/**
 * Error Messages
 */
$LANG['err_decl_before_date'] = ": Absence changes before the following date are not allowed: ";
$LANG['err_decl_group_threshold'] = ": Group absence threshold reached for your group(s): ";
$LANG['err_decl_period'] = ": Absence changes in the following period are not allowed: ";
$LANG['err_decl_total_threshold'] = ": Total absence threshold reached.";

/**
 * Group
 */
$LANG['group_edit_title'] = 'Edit Group: ';
$LANG['group_alert_edit'] = 'Update group';
$LANG['group_alert_edit_success'] = 'The information for this group was updated.';
$LANG['group_alert_save_failed'] = 'The new information for this group could not be saved. There was invalid input. Please check for error messages.';
$LANG['group_name'] = 'Name';
$LANG['group_name_comment'] = '';
$LANG['group_description'] = 'Description';
$LANG['group_description_comment'] = '';

/**
 * Groups
 */
$LANG['groups_title'] = 'Groups';
$LANG['groups_alert_group_created'] = 'The group was created.';
$LANG['groups_alert_group_created_fail'] = 'The group was not created. Please check the "Create group" dialog for input errors.';
$LANG['groups_alert_group_deleted'] = 'The group was deleted.';
$LANG['groups_confirm_delete'] = 'Are you sure you want to delete this group: ';
$LANG['groups_description'] = 'Description';
$LANG['groups_name'] = 'Name';

/**
 * Holidays
 */
$LANG['hol_edit_title'] = 'Edit Holiday: ';
$LANG['hol_list_title'] = 'Holidays';
$LANG['hol_alert_created'] = 'The holiday was created.';
$LANG['hol_alert_created_fail'] = 'The holiday was not created. Please check the "Create holiday" dialog for input errors.';
$LANG['hol_alert_deleted'] = 'The holiday was deleted.';
$LANG['hol_alert_edit'] = 'Update holiday';
$LANG['hol_alert_edit_success'] = 'The information for this holiday was updated.';
$LANG['hol_alert_save_failed'] = 'The new information for this absence type could not be saved. There was invalid input. Please check for error messages in the "Create holiday" dialog.';
$LANG['hol_bgcolor'] = 'Background color';
$LANG['hol_bgcolor_comment'] = 'This is the background color used for this holiday, independent from symbol or icon. Click into the field to open the color picker.';
$LANG['hol_businessday'] = 'Counts as business day';
$LANG['hol_businessday_comment'] = 'Select whether this holiday counst as a business day. If so, it will be counted against absences.';
$LANG['hol_color'] = 'Text color';
$LANG['hol_color_comment'] = 'This is the text color used for this holiday, independent from symbol or icon. Click into the field to open the color picker.';
$LANG['hol_confirm_delete'] = 'Are you sure you want to delete this holiday: "%s" ?';
$LANG['hol_description'] = 'Description';
$LANG['hol_description_comment'] = 'Enter a description for the holiday here.';
$LANG['hol_name'] = 'Name';
$LANG['hol_name_comment'] = 'Enter a name for the holiday here.';

/**
 * Home Page
 */
$LANG['home_title'] = 'Welcome to ' . $appTitle;

/**
 * Imprint
 * You can add more arrays here in order to display them on the Imprint page
 */
$LANG['imprint'] = array ( 
   array (
      'title' => 'Author',
      'text' => '<i class="fa fa-thumbs-o-up fa-3x pull-left" style="color: #999999;"></i>'.$appTitle.' was created by George Lewe (<a href="http://www.lewe.com/">Lewe.com</a>). 
      It is a reponsive web application based on HTML5 and CSS3. TeamCal Neo uses free modules by other great people providing those awesome techonolgies to the public. 
      See detailed credits on the <a href="index.php?action=about">About page</a>.',
   ),
   array (
      'title' => 'Content',
      'text' => '<p><i class="fa fa-file-text-o fa-3x pull-left" style="color: #999999;"></i>The design and content of '.$appTitle.' was created by George Lewe.  
      Where this is not the case it is properly indicated. If you feel that any material is used inappropriately, please let me know via 
      <a href="http://www.lewe.com/index.php?page=contact">this contact form</a>.</p>
      <p>None of the content, as a whole or in parts may be reproduced, copied or reused in any form or by any means, electronic or mechanical, 
      for any purpose, without the express written permission of George Lewe.</p>',
   ),
   array (
      'title' => 'Links',
      'text' => '<p><i class="fa fa-external-link fa-3x pull-left" style="color: #999999;"></i>All links on '.$appTitle.' are being provided as a convenience 
      and for informational purposes only; they do not constitute an endorsement or an approval by '.$appTitle.' of any of the products, services or opinions 
      of the corporation or organization or individual. '.$appTitle.' bears no responsibility for the accuracy, legality or content of the external site or 
      for that of subsequent links. Contact the external site for answers to questions regarding its content.</p>',
   ),
   array (
      'title' => 'Cookies',
      'text' => '<i class="fa fa-download fa-3x pull-left" style="color: #999999;"></i>This application uses cookies. Cookies are small files with application 
      related information that are stored on your local hard drive. They do not contain any personal information nor are they transmitted anywhere but they are 
      needed to run the application properly.</p>
      <p>In the EU, legislation requires to get your consent for using cookies. By using this application you agree to the usage of cookies.</p>',
   ),
);

if ( $C->read('googleAnalytics') AND $C->read("googleAnalyticsID")) {
   $LANG['imprint'][] = array (
      'title' => 'Google Analytics',
      'text' => '<p><i class="fa fa-google fa-3x pull-left" style="color: #999999;"></i>This website uses Google Analytics, a web analytics service provided by
      Google, Inc. ("Google"). Google Analytics uses "cookies", which are text files placed on your computer, to help the website analyze how users use the site.
      The information generated by the cookie about your use of the website will be transmitted to and stored by Google on servers in the United States.</p>
      <div class="collapse" id="readmore">
         <p>In case IP-anonymisation is activated on this website, your IP address will be truncated within the area of Member States of the European Union or
         other parties to the Agreement on the European Economic Area. Only in exceptional cases the whole IP address will be first transfered to a Google server
         in the USA and truncated there. The IP-anonymisation is active on this website.</p>
         <p>Google will use this information on behalf of the operator of this website for the purpose of evaluating your use of the website, compiling reports on
         website activity for website operators and providing them other services relating to website activity and internet usage.</p>
         <p>The IP-address, that your Browser conveys within the scope of Google Analytics, will not be associated with any other data held by Google. You may
         refuse the use of cookies by selecting the appropriate settings on your browser, however please note that if you do this you may not be able to use the
         full functionality of this website. You can also opt-out from being tracked by Google Analytics with effect for the future by downloading and installing
         Google Analytics Opt-out Browser Addon for your current web browser: <a href="http://tools.google.com/dlpage/gaoptout?hl=en">http://tools.google.com/dlpage/gaoptout?hl=en</a>.</p>
         <p>As an alternative to the browser Addon or within browsers on mobile devices, you can <a href="javascript:gaOptout()">click this link</a> in order to
         opt-out from being tracked by Google Analytics within this website in the future (the opt-out applies only for the browser in which you set it and within
         this domain). An opt-out cookie will be stored on your device, which means that you\'ll have to click this link again, if you delete your cookies.</p>
      </div>
      <p><a class="btn btn-default" data-toggle="collapse" data-target="#readmore">Read more/less...</a></p>',
   );
}


/**
 * Log
 */
$LANG['log_clear'] = 'Delete period';
$LANG['log_clear_confirm'] = 'Are you sure you want to delete all events of the currently selected period?<br> 
      Note, that all events of all event types in the selected period will be deleted, even though you might have hidden them in the Log Settings.';
$LANG['log_title'] = 'System Log';
$LANG['log_title_events'] = 'Events';
$LANG['log_settings'] = 'Log Settings';
$LANG['log_settings_event'] = 'Event type';
$LANG['log_settings_log'] = 'Log this event type';
$LANG['log_settings_show'] = 'Show this event type';
$LANG['log_sort_asc'] = 'Sort ascending...';
$LANG['log_sort_desc'] = 'Sort descending...';
$LANG['log_header_when'] = 'Timestamp (UTC)';
$LANG['log_header_type'] = 'Event Type';
$LANG['log_header_user'] = 'User';
$LANG['log_header_event'] = 'Event';
$LANG['log_filterNews'] = 'News';
$LANG['log_filterCalopt'] = 'Calender Options';
$LANG['log_filterConfig'] = 'Config';
$LANG['log_filterDatabase'] = 'Database';
$LANG['log_filterGroup'] = 'Groups';
$LANG['log_filterLogin'] = 'Login';
$LANG['log_filterLoglevel'] = 'Login';
$LANG['log_filterPermission'] = 'Permissions';
$LANG['log_filterRegistration'] = 'Registration';
$LANG['log_filterRole'] = 'Roles';
$LANG['log_filterUser'] = 'User';

/**
 * Login
 */
$LANG['login_login'] = 'Login';
$LANG['login_username'] = 'Username:';
$LANG['login_password'] = 'Password:';
$LANG['login_error_0'] = 'Login successful';
$LANG['login_error_1'] = 'Username or password missing';
$LANG['login_error_1_text'] = 'Please provide a valid username and password.';
$LANG['login_error_2'] = 'Username unknown';
$LANG['login_error_2_text'] = 'The username you entered is unknown. Please try again.';
$LANG['login_error_3'] = 'Account disabled';
$LANG['login_error_3_text'] = 'This account is locked or not approved. Please contact your administrator.';
$LANG['login_error_4'] = 'Password incorrect';
$LANG['login_error_4_text'] = 'This was bad attempt number %1%. After %2% bad attempts your account will be locked for %3% seconds.';
$LANG['login_error_6_text'] = 'This account is on hold due to too many bad login attempts. The grace period ends in %1% seconds.';
$LANG['login_error_7'] = 'Username or password incorrect';
$LANG['login_error_7_text'] = 'The username and/or password you entered are not correct. Please try again.';
$LANG['login_error_8_text'] = 'Your account is not verified yet. You may have received an E-mail with a verification link.';
$LANG['login_error_91'] = 'LDAP error: Password missing';
$LANG['login_error_92'] = 'LDAP error: Authentication/Bind failed';
$LANG['login_error_92_text'] = 'The LDAP authentication/bind failed. Please try again.';
$LANG['login_error_93'] = 'LDAP error: Unable to connect to LDAP server';
$LANG['login_error_93_text'] = 'The LDAP server connection failed. Please try again.';
$LANG['login_error_94'] = 'LDAP error: Start TLS failed';
$LANG['login_error_94_text'] = 'The LDAP start TLS failed. Please try again.';
$LANG['login_error_95'] = 'LDAP error: Username not found';
$LANG['login_error_96'] = 'LDAP error: Search bind failed';
$LANG['login_error_96_text'] = 'The LDAP search bind failed. Please try again.';

/**
 * Maintenance
 */
$LANG['mtce_title'] = 'Under Maintenance';
$LANG['mtce_text'] = 'The site is currently under maintenance. We apologize for the inconvenience. Please check back at a later time...';

/**
 * Menu
 */
$LANG['mnu_app'] = $appTitle;
$LANG['mnu_app_homepage'] = 'Home page';
$LANG['mnu_app_language'] = 'Language';
$LANG['mnu_view'] = 'View';
$LANG['mnu_view_calendar'] = 'Calendar (Month)';
$LANG['mnu_view_messages'] = 'Messages';
$LANG['mnu_view_stats'] = 'Statistics';
$LANG['mnu_view_stats_absences'] = 'Absence Statistics';
$LANG['mnu_view_stats_presences'] = 'Presence Statistics';
$LANG['mnu_view_year'] = 'Calendar (Year)';
$LANG['mnu_edit'] = 'Edit';
$LANG['mnu_edit_calendaredit'] = 'Personal Calendar';
$LANG['mnu_edit_monthedit'] = 'Region Calendar';
$LANG['mnu_edit_messageedit'] = 'Message Editor';
$LANG['mnu_admin'] = 'Administration';
$LANG['mnu_admin_absences'] = 'Absence Types';
$LANG['mnu_admin_config'] = 'System Configuration';
$LANG['mnu_admin_calendaroptions'] = 'Calendar Options';
$LANG['mnu_admin_database'] = 'Database Maintenance';
$LANG['mnu_admin_declination'] = 'Declination Management';
$LANG['mnu_admin_env'] = 'Environment';
$LANG['mnu_admin_groups'] = 'Groups';
$LANG['mnu_admin_holidays'] = 'Holidays';
$LANG['mnu_admin_perm'] = "Permissions";
$LANG['mnu_admin_phpinfo'] = 'PHP Info';
$LANG['mnu_admin_regions'] = 'Regions';
$LANG['mnu_admin_roles'] = 'Roles';
$LANG['mnu_admin_systemlog'] = 'System Log';
$LANG['mnu_admin_users'] = 'Users';
$LANG['mnu_help'] = 'Help';
$LANG['mnu_help_legend'] = 'Legend';
$LANG['mnu_help_help'] = 'User Manual';
$LANG['mnu_help_imprint'] = 'Imprint';
$LANG['mnu_help_about'] = 'About ' . $appTitle;
$LANG['mnu_user_login'] = 'Login';
$LANG['mnu_user_register'] = 'Register';
$LANG['mnu_user_logout'] = 'Logout';
$LANG['mnu_user_profile'] = 'Edit Profile';

/**
 * Messages
 */
$LANG['msg_title'] = 'Messages for: ';
$LANG['msg_title_edit'] = 'Create Message';
$LANG['msg_code'] = 'Captcha Code';
$LANG['msg_code_desc'] = 'Please enter the Captcha code as displayed. Capitalization is not relevant.';
$LANG['msg_code_new'] = 'Load new image';
$LANG['msg_confirm_all_confirm'] = 'Are you sure you want to confirm all your messages?';
$LANG['msg_confirm_confirm'] = 'Are you sure you want to confirm message "%s" ? The message will not be deleted but not pop up at login anymore.';
$LANG['msg_content_type'] = 'Content Type';
$LANG['msg_content_type_desc'] = 'Content types display online news headers in different colors (not available for E-mails).';
$LANG['msg_content_type_info'] = 'Info';
$LANG['msg_content_type_primary'] = 'Primary';
$LANG['msg_content_type_success'] = 'Success';
$LANG['msg_content_type_warning'] = 'Warning';
$LANG['msg_content_type_danger'] = 'Danger';
$LANG['msg_delete_all_confirm'] = 'Are you sure you want to delete all messages ?';
$LANG['msg_delete_confirm'] = 'Are you sure you want to delete message "%s" ?';
$LANG['msg_msg_body'] = 'Body';
$LANG['msg_msg_body_comment'] = 'Enter the body of your message here.';
$LANG['msg_msg_body_sample'] = '...your text here...';
$LANG['msg_msg_sent'] = 'Message sent';
$LANG['msg_msg_sent_text'] = 'Your message was sent to the selected recipients.';
$LANG['msg_msg_title'] = 'Message Title';
$LANG['msg_msg_title_comment'] = 'Enter the title of your message here.';
$LANG['msg_msg_title_sample'] = '...your title here...';
$LANG['msg_no_group_subject'] = 'No group selected';
$LANG['msg_no_group_text'] = 'You have to select at least one group to send the message to.';
$LANG['msg_no_text_subject'] = 'No subject and/or text';
$LANG['msg_no_text_text'] = 'You have to enter a subjcet and a text for the message.';
$LANG['msg_no_user_subject'] = 'No user selected';
$LANG['msg_no_user_text'] = 'You have to select at least one user to send the message to.';
$LANG['msg_sendto'] = 'Recipient';
$LANG['msg_sendto_desc'] = 'Select the recipient(s) of this message.';
$LANG['msg_sendto_all'] = 'All';
$LANG['msg_sendto_group'] = 'Group:';
$LANG['msg_sendto_user'] = 'User:';
$LANG['msg_type'] = 'Message Type';
$LANG['msg_type_desc'] = 'Chose the type of message you want to send.<br>
      A silent message will be put on the user\'s Messages page only.<br>
      A popup message will cause the Messages page to show automatically when the user logs in.';
$LANG['msg_type_email'] = 'E-mail';
$LANG['msg_type_silent'] = 'Silent Message';
$LANG['msg_type_popup'] = 'Popup Message';

/**
 * Modal dialogs
 */
$LANG['modal_confirm'] = 'Please Confirm';

/**
 * Month
 */
$LANG['monthedit_title'] = 'Month %s-%s (Region: %s)';
$LANG['monthedit_alert_update'] = 'Update month';
$LANG['monthedit_alert_update_success'] = 'The information for this month was updated.';
$LANG['monthedit_clearHoliday'] = 'Clear';
$LANG['monthedit_confirm_clearall'] = 'Are you sure you want to clear all holidays in this month?<br><br><strong>Year:</strong> %s<br><strong>Month:</strong> %s<br><strong>Region:</strong> %s';
$LANG['monthedit_selRegion'] = 'Select Region';
$LANG['monthedit_selUser'] = 'Select User';

/**
 * Password rules
 */
$LANG['password_rules_low'] = '<br>The password strength is currently set to "low", resulting in the following rules:
      <ul>
         <li>At least 4 characters</li>
      </ul>';
$LANG['password_rules_medium'] = '<br>The password strength is currently set to "medium", resulting in the following rules:
      <ul>
         <li>At least 6 characters</li>
         <li>At least 1 capital letter</li>
         <li>At least 1 small letter</li>
         <li>At least 1 number</li>
      </ul>';
$LANG['password_rules_high'] = '<br>The password strength is currently set to "high", resulting in the following rules:
      <ul>
         <li>At least 8 characters</li>
         <li>At least 1 capital letter</li>
         <li>At least 1 small letter</li>
         <li>At least 1 number</li>
         <li>At least 1 special character</li>
      </ul>';

/**
 * Permissions
 */
$LANG['perm_title'] = 'Permission Scheme Settings';
$LANG['perm_tab_general'] = 'General';
$LANG['perm_tab_features'] = 'Features';

$LANG['perm_active'] = '(Active)';
$LANG['perm_activate_scheme'] = 'Activate scheme';
$LANG['perm_activate_confirm'] = 'Are you sure you want to activate this permission scheme?';
$LANG['perm_create_scheme'] = 'Create scheme';
$LANG['perm_create_scheme_desc'] = 'Type in a name for the new scheme. It will be created and loaded with default settings right away. 
      All changes to the current scheme that have not been applied will be lost.';
$LANG['perm_delete_scheme'] = 'Delete scheme';
$LANG['perm_delete_confirm'] = 'Are you sure you want to delete this permission scheme? The Default scheme will be loaded and activated.';
$LANG['perm_inactive'] = '(Inactive)';
$LANG['perm_reset_scheme'] = 'Reset scheme';
$LANG['perm_reset_confirm'] = 'Are you sure you want to reset the current permission scheme? All values will be set to their default?';
$LANG['perm_save_scheme'] = 'Save scheme';
$LANG['perm_select_scheme'] = 'Select scheme';
$LANG['perm_select_confirm'] = 'When you confirm a new scheme selection, all changes to the current scheme that have not been applied will be lost.';
$LANG['perm_view_by_perm'] = 'View by permission';
$LANG['perm_view_by_role'] = 'View by role';

$LANG['perm_absenceedit_title'] = 'Manage Absence Types';
$LANG['perm_absenceedit_desc'] = 'Allows to list and edit absence types.';
$LANG['perm_admin_title'] = 'System Administration';
$LANG['perm_admin_desc'] = 'Allows to access the system administration pages.';
$LANG['perm_calendaredit_title'] = 'Calendar Editor';
$LANG['perm_calendaredit_desc'] = 'Allows to open the calendar editor. This permission is required to edit any user calendars.';
$LANG['perm_calendareditall_title'] = 'Edit All Calendars';
$LANG['perm_calendareditall_desc'] = 'Allows to edit the calendars of all users.';
$LANG['perm_calendareditgroup_title'] = 'Edit Group Calendars';
$LANG['perm_calendareditgroup_desc'] = 'Allows to edit all user calendars of own groups.';
$LANG['perm_calendareditown_title'] = 'Edit Own Calendar';
$LANG['perm_calendareditown_desc'] = 'Allows to edit the own calendar. If you run a central absence management you might want to switch this off for regular users.';
$LANG['perm_calendaroptions_title'] = 'Calendar Options';
$LANG['perm_calendaroptions_desc'] = 'Allows to configure the calendar options.';
$LANG['perm_calendarview_title'] = 'View Calendar';
$LANG['perm_calendarview_desc'] = 'Allows to view the calendar (month and year). If this is not permitted, no calendars can be displayed. Can be used to allow the public
       to view the calendar.';
$LANG['perm_calendarviewall_title'] = 'View All Calendars';
$LANG['perm_calendarviewall_desc'] = 'Allows to view all calendars.';
$LANG['perm_calendarviewgroup_title'] = 'View Group Calendars';
$LANG['perm_calendarviewgroup_desc'] = 'Allows to view all calendars of users that are in the same group as the logged in user.';
$LANG['perm_declination_title'] = 'Declination Management';
$LANG['perm_declination_desc'] = 'Allows to access the declination management page.';
$LANG['perm_groups_title'] = 'Manage Groups';
$LANG['perm_groups_desc'] = 'Allows to list and edit user groups.';
$LANG['perm_groupmemberships_title'] = 'Manage Group Memberships';
$LANG['perm_groupmemberships_desc'] = 'Allows to assign users to groups as members or managers.';
$LANG['perm_holidays_title'] = 'Manage Holidays';
$LANG['perm_holidays_desc'] = 'Allows to list and edit holidays.';
$LANG['perm_messageview_title'] = 'View Messages';
$LANG['perm_messageview_desc'] = 'Allows to access the messages page. Note, that the messages page only shows messages for the logged in user.';
$LANG['perm_messageedit_title'] = 'Create Messages';
$LANG['perm_messageedit_desc'] = 'Allows to create and send messages.';
$LANG['perm_regions_title'] = 'Manage Regions';
$LANG['perm_regions_desc'] = 'Allows to list and edit regions and their holidays.';
$LANG['perm_roles_title'] = 'Manage Roles';
$LANG['perm_roles_desc'] = 'Allows to list and edit roles.';
$LANG['perm_statistics_title'] = 'View Statistics';
$LANG['perm_statistics_desc'] = 'Allows to view the statistics page.';
$LANG['perm_useraccount_title'] = 'Edit User Account Info';
$LANG['perm_useraccount_desc'] = 'Allows to edit the Account tab when editing a user profile.';
$LANG['perm_useradmin_title'] = 'Manage User Accounts';
$LANG['perm_useradmin_desc'] = 'Allows to list and add user accounts.';
$LANG['perm_useredit_title'] = 'Edit User Profile';
$LANG['perm_useredit_desc'] = 'Allows editing the own Userprofile.';
$LANG['perm_viewprofile_title'] = 'View User Profiles';
$LANG['perm_viewprofile_desc'] = 'Allows to access the view profile page showing basic info like name, phone number etc. Viewing user popups is also 
      dependent on this permission.';

/**
 * Phpinfo
 */
$LANG['phpinfo_title'] = 'PHP Info';

/**
 * Profile
 */
$LANG['profile_create_title'] = 'Create User Profile';
$LANG['profile_create_mail'] = 'Send notification E-mail';
$LANG['profile_create_mail_comment'] = 'Sends a notification E-mail to the created user.';

$LANG['profile_view_title'] = 'Profile of: ';

$LANG['profile_edit_title'] = 'Edit profile: ';
$LANG['profile_tab_account'] = 'Account';
$LANG['profile_tab_avatar'] = 'Avatar';
$LANG['profile_tab_contact'] = 'Contact';
$LANG['profile_tab_groups'] = 'Groups';
$LANG['profile_tab_notifications'] = 'Notifications';
$LANG['profile_tab_password'] = 'Password';
$LANG['profile_tab_personal'] = 'Personal';

$LANG['profile_alert_create'] = 'Create user profile';
$LANG['profile_alert_create_success'] = 'The new user account was created.';
$LANG['profile_alert_update'] = 'User profile update';
$LANG['profile_alert_update_success'] = 'The information for this user profile eas updated.';
$LANG['profile_alert_save_failed'] = 'The new information for this user could not be saved. There was invalid input. Please check the tabs for error messages.';
$LANG['profile_avatar'] = 'Avatar';
$LANG['profile_avatar_comment'] = 'If you haven\'t uploaded an own avatar, a default avatar will be used.';
$LANG['profile_avatar_available'] = 'Available Standard Avatars';
$LANG['profile_avatar_available_comment'] = 'Choose one of the available avatars, courtesy of <a href="http://www.iconshock.com/icon_sets/vector-user-icons/" target="_blank">IconShock</a>.';
$LANG['profile_avatar_upload'] = 'Upload avatar';
$LANG['profile_avatar_upload_comment'] = 'You can upload a custom avatar. The size of the file is limited to %d Bytes, the size of the image should be 
      80x80 pixels (will be displayed in those dimensions anyways) and the allowed formats are "%s".';
$LANG['profile_email'] = 'E-mail';
$LANG['profile_email_comment'] = '';
$LANG['profile_facebook'] = 'Facebook';
$LANG['profile_facebook_comment'] = '';
$LANG['profile_firstname'] = 'Firstname';
$LANG['profile_firstname_comment'] = '';
$LANG['profile_gender'] = 'Gender';
$LANG['profile_gender_comment'] = '';
$LANG['profile_gender_male'] = 'Male';
$LANG['profile_gender_female'] = 'Female';
$LANG['profile_google'] = 'Google+';
$LANG['profile_google_comment'] = '';
$LANG['profile_id'] = 'ID';
$LANG['profile_id_comment'] = '';
$LANG['profile_language'] = 'Language';
$LANG['profile_language_comment'] = 'Selects a custom language for the application interface.';
$LANG['profile_lastname'] = 'Lastname';
$LANG['profile_lastname_comment'] = '';
$LANG['profile_linkedin'] = 'LinkedIn';
$LANG['profile_linkedin_comment'] = '';
$LANG['profile_locked'] = 'Locked';
$LANG['profile_locked_comment'] = 'The account is locked. No login is possible.';
$LANG['profile_managerships'] = 'Manager of';
$LANG['profile_managerships_comment'] = 'Select the groups that this user is manager of. Should the same group be selected here and in the member list, 
      then the manager position is saved.';
$LANG['profile_memberships'] = 'Member of';
$LANG['profile_memberships_comment'] = 'Select the groups that this user is member of. Should the same group be selected here and in the manager list, 
      then the manager position is saved.';
$LANG['profile_mobilephone'] = 'Mobile';
$LANG['profile_mobilephone_comment'] = '';
$LANG['profile_onhold'] = 'On hold';
$LANG['profile_onhold_comment'] = 'This status is applied after a user has entered a wrong password too many times. This causes a grace period in which no login is possible. 
      The grace period can be configured on the configuration page. You can manually release the status here as well.';
$LANG['profile_password'] = 'Password';
$LANG['profile_password_comment'] = 'You can enter a new password here. If the field stays empty, the current password will not be changed.<br>
      Allowed are small and capital letters, numbers and the following special characters: !@#$%^&amp;*()';
$LANG['profile_password2'] = 'Confirm password';
$LANG['profile_password2_comment'] = 'Repeat the new password here.';
$LANG['profile_phone'] = 'Phone';
$LANG['profile_phone_comment'] = '';
$LANG['profile_position'] = 'Position';
$LANG['profile_position_comment'] = '';
$LANG['profile_role'] = 'Role';
$LANG['profile_role_comment'] = 'Select the role of this user here. The role defines the permissions of this user.';
$LANG['profile_skype'] = 'Skype';
$LANG['profile_skype_comment'] = '';
$LANG['profile_theme'] = 'Theme';
$LANG['profile_theme_comment'] = 'Selects a custom theme for the application interface.';
$LANG['profile_title'] = 'Title';
$LANG['profile_title_comment'] = '';
$LANG['profile_twitter'] = 'Twitter';
$LANG['profile_twitter_comment'] = '';
$LANG['profile_username'] = 'Loginname';
$LANG['profile_username_comment'] = 'The loginname cannot be changed for existing users.';
$LANG['profile_verify'] = 'Verify';
$LANG['profile_verify_comment'] = 'When a user has registered himself but did not use the activation link yet, this status is applied. The account is created but no login is possible yet.
      You can manually release the status here as well.';

/**
 * Region
 */
$LANG['region_edit_title'] = 'Edit Group: ';
$LANG['region_alert_edit'] = 'Update group';
$LANG['region_alert_edit_success'] = 'The information for this group was updated.';
$LANG['region_alert_save_failed'] = 'The new information for this group could not be saved. There was invalid input. Please check for error messages.';
$LANG['region_name'] = 'Name';
$LANG['region_name_comment'] = '';
$LANG['region_description'] = 'Description';
$LANG['region_description_comment'] = '';

/**
 * Regions
 */
$LANG['regions_title'] = 'Regions';
$LANG['regions_tab_list'] = 'List';
$LANG['regions_tab_ical'] = 'iCal Import';
$LANG['regions_tab_transfer'] = 'Transfer Region';
$LANG['regions_alert_transfer_same'] = 'Source and target region for a transfer must be different.';
$LANG['regions_alert_no_file'] = 'No iCal file was selected.';
$LANG['regions_alert_region_created'] = 'The region was created.';
$LANG['regions_alert_region_created_fail'] = 'The region was not created. Please check the "Create region" dialog for input errors.';
$LANG['regions_alert_region_deleted'] = 'The region was deleted.';
$LANG['regions_confirm_delete'] = 'Are you sure you want to delete this region: ';
$LANG['regions_description'] = 'Description';
$LANG['regions_ical_file'] = 'iCal File';
$LANG['regions_ical_file_comment'] = 'Select an iCal file with whole day events (e.g. school holidays) from a local drive.';
$LANG['regions_ical_holiday'] = 'iCal Holiday';
$LANG['regions_ical_holiday_comment'] = 'Select the holiday to be used for the events in your iCal file.';
$LANG['regions_ical_imported'] = 'The iCal file "%s" was imported into region "%s".';
$LANG['regions_ical_overwrite'] = 'Overwrite';
$LANG['regions_ical_overwrite_comment'] = 'Select here whether existing holidays in the target region shall be overwritten. If not selected, existing entries in 
      the target region will remain.';
$LANG['regions_ical_region'] = 'iCal Region';
$LANG['regions_ical_region_comment'] = 'Select the region into which the events of your iCal file shall be inserted.';
$LANG['regions_transferred'] = 'The region "%s" was transferred into region "%s".';
$LANG['regions_name'] = 'Name';
$LANG['regions_region_a'] = 'Source Region';
$LANG['regions_region_a_comment'] = 'Select the region that shall be transfered into the target region.';
$LANG['regions_region_b'] = 'Target Region';
$LANG['regions_region_b_comment'] = 'Select the region into which the source region shall be transfered.';
$LANG['regions_region_overwrite'] = 'Overwrite';
$LANG['regions_region_overwrite_comment'] = 'Select here whether the source region entries shall overwrite the target region entries. If not selected, existing entries in 
      the target region will remain.';

/**
 * Register
 */
$LANG['register_title'] = 'User Registration';
$LANG['register_alert_success'] = 'You user account was registered and an E-mail with the corresponding information was sent to you.';
$LANG['register_alert_failed'] = 'Your registration could not be completed. Please check your input.';
$LANG['register_email'] = 'E-mail';
$LANG['register_email_comment'] = '';
$LANG['register_firstname'] = 'Firstname';
$LANG['register_firstname_comment'] = '';
$LANG['register_lastname'] = 'Lastname';
$LANG['register_lastname_comment'] = '';
$LANG['register_password'] = 'Password';
$LANG['register_password_comment'] = 'Please enter a password here.<br>
      Allowed are small and capital letters, numbers and the following special characters: !@#$%^&amp;*()';
$LANG['register_password2'] = 'Confirm password';
$LANG['register_password2_comment'] = 'Repeat the password here.';
$LANG['register_username'] = 'Loginname';
$LANG['register_username_comment'] = 'The loginname cannot be changed for existing users.';

/**
 * Role
 */
$LANG['role_edit_title'] = 'Edit Role: ';
$LANG['role_alert_edit'] = 'Update role';
$LANG['role_alert_edit_success'] = 'The information for this role was updated.';
$LANG['role_alert_save_failed'] = 'The new information for this role could not be saved. There was invalid input. Please check for error messages.';
$LANG['role_alert_save_failed_duplicate'] = 'The new information for this role could not be saved. A role with that name already exists.';
$LANG['role_color'] = 'Role Color';
$LANG['role_color_comment'] = 'User icons will be colored based on the role color chosen here.';
$LANG['role_color_danger'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-danger"></span>';
$LANG['role_color_default'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-default"></span>';
$LANG['role_color_info'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-info"></span>';
$LANG['role_color_primary'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-primary"></span>';
$LANG['role_color_success'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-success"></span>';
$LANG['role_color_warning'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-warning"></span>';
$LANG['role_description'] = 'Description';
$LANG['role_description_comment'] = '';
$LANG['role_name'] = 'Name';
$LANG['role_name_comment'] = '';

/**
 * Roles
 */
$LANG['roles_title'] = 'Roles';
$LANG['roles_alert_created'] = 'The role was created.';
$LANG['roles_alert_created_fail_input'] = 'The role was not created. Please check the "Create role" dialog for input validation errors.';
$LANG['roles_alert_created_fail_duplicate'] = 'The role was not created. A role with that name already exists.';
$LANG['roles_alert_deleted'] = 'The role was deleted.';
$LANG['roles_confirm_delete'] = 'Are you sure you want to delete this role: ';
$LANG['roles_description'] = 'Description';
$LANG['roles_name'] = 'Name';

/**
 * Statistics
 */
$LANG['stats_title_absences'] = 'Absence Statistics';
$LANG['stats_title_presences'] = 'Presence Statistics';
$LANG['stats_absenceType'] = 'Absence Type';
$LANG['stats_absenceType_comment'] = 'Select the absence type for the statistic.';
$LANG['stats_bygroups'] = '(Per Group)';
$LANG['stats_byusers'] = '(Per User)';
$LANG['stats_color'] = 'Color';
$LANG['stats_color_comment'] = 'Select the color for the diagram.';
$LANG['stats_customColor'] = 'Custom Color';
$LANG['stats_customColor_comment'] = 'Select a custom color for the diagram.<br>This value only applies if "'.$LANG['custom'].'" was selected in the Color list.';
$LANG['stats_endDate'] = 'End Date';
$LANG['stats_endDate_comment'] = 'Select a custom end date for the statistic. This date only applies if "'.$LANG['custom'].'" was selected in the Period list.';
$LANG['stats_group'] = 'Group';
$LANG['stats_group_comment'] = 'Select the group for the statistic.';
$LANG['stats_modalAbsenceTitle'] = 'Select Absence Type for the Statistic';
$LANG['stats_modalGroupTitle'] = 'Select Group for the Statistic';
$LANG['stats_modalPeriodTitle'] = 'Select Period for the Statistic';
$LANG['stats_modalDiagramTitle'] = 'Select Diagram Options for the Statistic';
$LANG['stats_period'] = 'Period';
$LANG['stats_period_comment'] = 'Select the period for the statistic.';
$LANG['stats_scale'] = 'Scale';
$LANG['stats_scale_comment'] = 'Select the scale for the diagram (The default can be set by the administrator.).
      <ul>
         <li>Automatic: The diagram\'s max value is the maximal absence value.</li>
         <li>Smart: The diagram\'s max value is the maximal absence value plus the Smart Value.</li>
         <li>Custom: The diagram\'s max value can be specified in the field below.</li>
      </ul>';
$LANG['stats_scale_max'] = 'Max Value';
$LANG['stats_scale_max_comment'] = 'Select a custom max value for the diagram. Default value is 30.<br>This value only applies if "'.$LANG['custom'].'" was selected in the Scale list.';
$LANG['stats_scale_smart'] = 'Smart Value';
$LANG['stats_scale_smart_comment'] = 'The smart value is added to the maximal value read and the sum is used as the diagram\'s scale maximum (The default can be set by the administrator.).<br>This value only applies if "'.$LANG['smart'].'" was selected in the Scale list.';
$LANG['stats_total'] = 'Total sum';
$LANG['stats_yaxis'] = 'Diagram Y-axis';
$LANG['stats_yaxis_comment'] = 'Select what the diagram y-axis shall show.';
$LANG['stats_yaxis_groups'] = 'Groups';
$LANG['stats_yaxis_users'] = 'Users';
$LANG['stats_startDate'] = 'Start Date';
$LANG['stats_startDate_comment'] = 'Select a custom start date for the statistic.<br>This date only applies if "'.$LANG['custom'].'" was selected in the Period list.';

/**
 * Status Bar
 */
$LANG['status_logged_in'] = 'You are logged in as ';
$LANG['status_logged_out'] = 'Not logged in';
$LANG['status_ut_user'] = 'Regular User';
$LANG['status_ut_manager'] = 'Manager of group: ';
$LANG['status_ut_director'] = 'Director';
$LANG['status_ut_assistant'] = 'Assistant';
$LANG['status_ut_admin'] = 'Administrator';

/**
 * Upload
 */
$LANG['upload_maxsize'] = 'Maximum filesize';
$LANG['upload_extensions'] = 'Allowed extensions';
$LANG['upload_error_0'] = 'The file "%s" was successfully uploaded.';
$LANG['upload_error_1'] = 'The uploaded file exceeds the maximum upload filesize directive in the server configuration.';
$LANG['upload_error_2'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
$LANG['upload_error_3'] = 'The file was only partially uploaded.';
$LANG['upload_error_4'] = 'No file was uploaded.';
$LANG['upload_error_10'] = 'Please select a file for upload.';
$LANG['upload_error_11'] = 'Only files with the following extensions are allowed: %s';
$LANG['upload_error_12'] = 'The filename contains invalid characters. Use only alphanumeric characters and separate parts of the name (if needed) with an underscore. A valid filename ends with one dot followed by the extension.';
$LANG['upload_error_13'] = 'The filename exceeds the maximum length of %d characters.';
$LANG['upload_error_14'] = 'The upload directory does not exist!';
$LANG['upload_error_15'] = 'A file with the name "%s" already exists.';
$LANG['upload_error_16'] = 'The uploaded file was renamed to: %s';
$LANG['upload_error_17'] = 'The file "%s" does not exist.';

/**
 * Users
 */
$LANG['users_title'] = 'Users';
$LANG['users_alert_archive_selected_users'] = 'The selected users were archived.';
$LANG['users_alert_archive_selected_users_failed'] = 'One or more of the selected users already exist in the archive. This could be the same user or one with the same username.<br>Please delete these archived users first.';
$LANG['users_alert_delete_selected_users'] = 'The selected users were deleted.';
$LANG['users_alert_reset_password_selected'] = 'The passwords of selected users were reset and a corresponding e-mail was sent to them.';
$LANG['users_alert_restore_selected_users'] = 'The selected users were restored.';
$LANG['users_alert_restore_selected_users_failed'] = 'One or more of the selected users already exist as active users. This could be the same user or one with the same username.<br>Please delete these active users first.';
$LANG['users_attributes'] = 'Attributes';
$LANG['users_attribute_locked'] = 'Account locked';
$LANG['users_attribute_hidden'] = 'Account hidden';
$LANG['users_attribute_onhold'] = 'Account on hold';
$LANG['users_attribute_verify'] = 'Account to be verified';
$LANG['users_confirm_archive'] = 'Are you sure you want to archive the selected users?';
$LANG['users_confirm_delete'] = 'Are you sure you want to delete the selected users?';
$LANG['users_confirm_password'] = 'Are you sure you want to reset the passwords of the selected users?';
$LANG['users_confirm_restore'] = 'Are you sure you want to restore the selected users?';
$LANG['users_created'] = 'Created';
$LANG['users_last_login'] = 'Last Login';
$LANG['users_tab_active'] = 'Active Users';
$LANG['users_tab_archived'] = 'Archived Users';
$LANG['users_user'] = 'User';

/**
 * Year
 */
$LANG['year_title'] = 'Year Calendar %s for %s (Region: %s)';
$LANG['year_selRegion'] = 'Select Region';
$LANG['year_selUser'] = 'Select User';
$LANG['year_showyearmobile'] = '<p>The Year Calendar serves the purpose of seeing the whole year "on first sight". On mobile devices with smaller screen sizes this
      is not possible without horizontal scrolling.</p><p>Click the "Show calendar" button below to enable this display and accept horizontal scrolling.</p>';
?>
