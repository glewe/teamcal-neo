<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Application Strings: English
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

//=============================================================================
//
// APPLICATION LANGUAGE
// Keep your application language entries separate from the framework language
// file and add them here. This makes it easier to update the framework at a
// later point.
//

//
// Common
//
$LANG['absence'] = 'Absence';
$LANG['absences'] = 'Absences';
$LANG['absencetype'] = 'Absence Type';
$LANG['allowance'] = 'Allowance';
$LANG['approval_required'] = 'Approval required';
$LANG['month'] = 'Month';
$LANG['presences'] = 'Presences';
$LANG['region'] = 'Region';
$LANG['remainder'] = 'Remainder';
$LANG['screen'] = 'Screen';
$LANG['taken'] = 'Taken';
$LANG['weekdays'] = 'Weekdays';
$LANG['weekends'] = 'Weekends';
$LANG['weeknumber'] = 'Calendar week';
$LANG['year'] = 'Year';

$LANG['monthnames'] = array(
  1 => "January",
  2 => "February",
  3 => "March",
  4 => "April",
  5 => "May",
  6 => "June",
  7 => "July",
  8 => "August",
  9 => "September",
  10 => "October",
  11 => "November",
  12 => "December",
);

$LANG['widths'] = array(
  'full' => "Full screen (More than 1024 pixels)",
  '1024' => "1024 pixels",
  '800' => "800 pixels",
  '640' => "640 pixels",
  '480' => "480 pixels",
  '400' => "400 pixels",
  '320' => "320 pixels",
  '240' => "240 pixels",
);

//
// Absences
//
$LANG['abs_list_title'] = 'Absence Types';
$LANG['abs_edit_title'] = 'Edit Absence Type: ';
$LANG['abs_alert_edit'] = 'Update Absence Type';
$LANG['abs_alert_edit_success'] = 'The information for this absence type was updated.';
$LANG['abs_alert_created'] = 'The absence type was created.';
$LANG['abs_alert_created_fail'] = 'The abensce type could not be created. Please check the "Create absence type" dialog for input errors.';
$LANG['abs_alert_deleted'] = 'The absence type was deleted.';
$LANG['abs_alert_save_failed'] = 'The new information for this absence type could not be saved. There was invalid input. Please check for error messages.';
$LANG['abs_allow_active'] = 'Restricted Amount';
$LANG['abs_allowance'] = 'Allowance per Year';
$LANG['abs_allowance_comment'] = 'Set an allowance for this absence type per year here. This amount refers to the current calendar year. When displaying
      a user profile the absence count section will contain the remaining amount for this absence type for the user (A negative value will indicate that the
      user has used too many absence days of this type.). If allowance is set to 0 no limit is assumed.';
$LANG['abs_allowmonth'] = 'Allowance per Month';
$LANG['abs_allowmonth_comment'] = 'Set an allowance for this absence type per month here. If allowance is set to 0 no limit is assumed.';
$LANG['abs_allowweek'] = 'Allowance per Week';
$LANG['abs_allowweek_comment'] = 'Set an allowance for this absence type per week here. If allowance is set to 0 no limit is assumed.';
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
      in the calendar, except it is the regular user\'s own absence. This feature is useful if you want to hide sensitive absence types from regular users. You can also define
      trusted roles in the Calendar Options that will also be able to view these absences.';
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
      training days. An employee that has taken 10 half training days would end up with a total of 5 (10// 0.5 = 5).<br>
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
$LANG['abs_icon_keyword'] = 'Enter a keyword...';
$LANG['abs_manager_only'] = 'Group Manager Only';
$LANG['abs_manager_only_comment'] = 'Checking this box defines that only group managers can set this absence type. Only if the logged in user is the group manager of
      the user who\'s calendar is being edited will this absence type be available.';
$LANG['abs_name'] = 'Name';
$LANG['abs_name_comment'] = 'The absence type name is used in lists and descriptions and should tell what this absence type is about, e.g. "Duty trip". It can be 80 characters long.';
$LANG['abs_sample'] = 'Sample display';
$LANG['abs_sample_comment'] = 'This is how your absence type will look in your calendar based on your current settings.<br>
      Note: In the Calendar Options you can configure whether the Icon or the Character ID shall be used for the display.';
$LANG['abs_show_in_remainder'] = 'Show in Remainder';
$LANG['abs_show_in_remainder_comment'] = 'Checking this option will include this absence on the Remainder page.';
$LANG['abs_symbol'] = 'Character ID';
$LANG['abs_symbol_comment'] = 'The absence type character ID is used in notification e-mails since font icons are not supported there. Chose a single character.
 A character ID is mandatory for each absence type, however, you can use the same character for mutliple absence types. The default is
 the first letter of the absence type name when it is created.';
$LANG['abs_tab_groups'] = 'Group Assignments';
$LANG['abs_takeover'] = 'Enable for Take-over';
$LANG['abs_takeover_comment'] = 'Enables this absence type for taken over. Note, that the take-over feature must be enabled in TeamCal Neo for this to have an effect.';

//
// Absence Icon
//
$LANG['absico_title'] = 'Select Absence Type Icon: ';
$LANG['absico_tab_brand'] = 'Brand Icons';
$LANG['absico_tab_regular'] = 'Regular Icons';
$LANG['absico_tab_solid'] = 'Solid Icons';

//
// Absences Summary
//
$LANG['absum_title'] = 'Absence Summary %s: %s';
$LANG['absum_modalYearTitle'] = 'Select the Year for the Summary';
$LANG['absum_unlimited'] = 'Unlimited';
$LANG['absum_year'] = 'Year';
$LANG['absum_year_comment'] = 'Select the year for this summary.';
$LANG['absum_absencetype'] = 'Absence Type';
$LANG['absum_contingent'] = 'Contingent';
$LANG['absum_contingent_tt'] = 'The Contingent is the result of the Allowance for this year plus the Carryover from last year. Note, that the Carryover value can also be negative.';
$LANG['absum_taken'] = 'Taken';
$LANG['absum_remainder'] = 'Remainder';

//
// Alerts
//
$LANG['alert_decl_allowmonth_reached'] = "The maximum amount of %1% per month for this absence type is exceeded.";
$LANG['alert_decl_allowweek_reached'] = "The maximum amount of %1% per week for this absence type is exceeded.";
$LANG['alert_decl_allowyear_reached'] = "The maximum amount of %1% per year for this absence type is exceeded.";
$LANG['alert_decl_approval_required'] = "This absence type requires approval. It has been entered in your calendar but a daynote was added to indicate that it is not approved yet. Your manager was informed by mail.";
$LANG['alert_decl_approval_required_daynote'] = "This absence was requested but is not approved yet.";
$LANG['alert_decl_before_date'] = "Absence changes before the following date are not allowed: ";
$LANG['alert_decl_group_minpresent'] = "Group minimum presence threshold reached for group(s): ";
$LANG['alert_decl_group_maxabsent'] = "Group maximum absence threshold reached for group(s): ";
$LANG['alert_decl_group_threshold'] = "Group absence threshold reached for your group(s): ";
$LANG['alert_decl_holiday_noabsence'] = "This day is a holiday that does not allow absences.";
$LANG['alert_decl_period'] = "Absence changes in the following period are not allowed: ";
$LANG['alert_decl_takeover'] = "Absence type '%s' not enabled for take-over.";
$LANG['alert_decl_total_threshold'] = "Total absence threshold reached.";

//
// Bulk Edit
//
$LANG['bulkedit_title'] = 'Allowances';
$LANG['bulkedit_alert_update'] = 'Allowances Update';
$LANG['bulkedit_alert_update_failed'] = 'The update failed. Make sure that at least one user is selected, that all values are numeric and that each user has a value in both fields.';
$LANG['bulkedit_alert_update_success'] = 'Allowance and carryover values were updated for the selected users.';
$LANG['bulkedit_for_selected'] = 'For all selected';

//
// Buttons
//
$LANG['btn_abs_edit'] = 'Back to Edit';
$LANG['btn_abs_icon'] = 'Select Icon';
$LANG['btn_abs_list'] = 'Absence Type List';
$LANG['btn_absum'] = 'Absence Summary';
$LANG['btn_activate_selected'] = 'Activate selected';
$LANG['btn_calendar'] = 'Calendar';
$LANG['btn_cal_edit'] = 'Edit Calendar';
$LANG['btn_cleanup'] = 'Cleanup';
$LANG['btn_create_abs'] = 'Create Absence Type';
$LANG['btn_create_holiday'] = 'Create Holiday';
$LANG['btn_create_pattern'] = 'Create Pattern';
$LANG['btn_create_region'] = 'Create Region';
$LANG['btn_delete_abs'] = 'Delete Absence Type';
$LANG['btn_delete_holiday'] = 'Delete Holiday';
$LANG['btn_delete_pattern'] = 'Delete Pattern';
$LANG['btn_delete_region'] = 'Delete Region';
$LANG['btn_holiday_list'] = 'Holiday List';
$LANG['btn_pattern_list'] = 'Pattern List';
$LANG['btn_region_calendar'] = 'Region Calendar';
$LANG['btn_region_list'] = 'Region List';
$LANG['btn_showcalendar'] = 'Show Calendar';
$LANG['btn_user_calendar'] = 'User Calendar';

//
// Calendar
//
$LANG['cal_title'] = 'Calendar %s-%s (Region: %s)';
$LANG['cal_tt_absent'] = 'Absent';
$LANG['cal_tt_anotherabsence'] = 'Another absence';
$LANG['cal_tt_backward'] = 'Go back one month...';
$LANG['cal_tt_clicktoedit'] = 'Click to edit...';
$LANG['cal_tt_forward'] = 'Go forward one month...';
$LANG['cal_tt_onemore'] = 'Show one more month...';
$LANG['cal_tt_oneless'] = 'Show one less month...';
$LANG['cal_search'] = 'Search User';
$LANG['cal_selAbsence'] = 'Select Absence';
$LANG['cal_selAbsence_comment'] = 'Shows all entries having this absence type for today.';
$LANG['cal_selGroup'] = 'Select Group';
$LANG['cal_selMonth'] = 'Select Month';
$LANG['cal_selRegion'] = 'Select Region';
$LANG['cal_selWidth'] = 'Select Screen Width';
$LANG['cal_selWidth_comment'] = 'Select the width of your screen in pixel so the calendar table can adjust to it. If your width is not in the list, select the next higher one.
      <br>It looks like you are currently using a screen with a width of <span id="currentwidth"></span> pixels. Reload the page to check this dialog again to confirm.';
$LANG['cal_summary'] = 'Summary';
$LANG['cal_businessDays'] = 'Business Days';

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
$LANG['exp_summary'] = 'Expand Summary section...';
$LANG['col_summary'] = 'Collapse Summary section...';
$LANG['exp_remainder'] = 'Expand Remainder section...';
$LANG['col_remainder'] = 'Collapse Remainder section...';

//
// Calendar Edit
//
$LANG['caledit_title'] = 'Edit month %s-%s for %s';
$LANG['caledit_absencePattern'] = 'Absence Pattern';
$LANG['caledit_absencePattern_comment'] = 'Select the absence pattern to be applied to this month.';
$LANG['caledit_absencePatternSkipHolidays'] = 'Skip Holidays';
$LANG['caledit_absencePatternSkipHolidays_comment'] = 'When setting the pattern absences, skip Holidays that do not count as business days.';
$LANG['caledit_absenceType'] = 'Absence Type';
$LANG['caledit_absenceType_comment'] = 'Select the absence type for this input.';
$LANG['caledit_alert_out_of_range'] = 'The dates entered were at least partially out of the currently displayed month. No changes were saved.';
$LANG['caledit_alert_save_failed'] = 'The absence information could not be saved. There was invalid input. Please check your last input.';
$LANG['caledit_alert_update'] = 'Update month';
$LANG['caledit_alert_update_all'] = 'All absences were accepted and the calendar was updated accordingly.';
$LANG['caledit_alert_update_group'] = 'The group absences were set for all users of the group.';
$LANG['caledit_alert_update_partial'] = 'Some absences were not accepted because they violate restrictions set by the management. The following requests were declined:';
$LANG['caledit_alert_update_none'] = 'The absences were not accepted because the requested absences violate restrictions set up by the management. The calendar was not updated.';
$LANG['caledit_clearAbsence'] = 'Clear';
$LANG['caledit_clearAbsences'] = 'Clear Absences';
$LANG['caledit_clearDaynotes'] = 'Clear Daynotes';
$LANG['caledit_confirm_clearall'] = 'Are you sure you want to clear all absences in this month?<br><br><strong>Year:</strong> %s<br><strong>Month:</strong> %s<br><strong>User:</strong> %s';
$LANG['caledit_confirm_savegroup'] = '<p><strong class="text-danger">Attention!</strong><br>Saving Group absences will not perform any individual approval checks.<br>
      All absences will be set for every user in the selected group. You can, however, select to not overwrite existing individual absences below.</p>
      <p><strong>Year:</strong> %s<br><strong>Month:</strong> %s<br><strong>Group:</strong> %s</p>';
$LANG['caledit_currentAbsence'] = 'Current absence';
$LANG['caledit_endDate'] = 'End Date';
$LANG['caledit_endDate_comment'] = 'Select the end date (must be in this month).';
$LANG['caledit_keepExisting'] = 'Keep existing user absences';
$LANG['caledit_Pattern'] = 'Pattern';
$LANG['caledit_PatternTitle'] = 'Select Absence Pattern';
$LANG['caledit_Period'] = 'Period';
$LANG['caledit_PeriodTitle'] = 'Select Absence Period';
$LANG['caledit_Recurring'] = 'Recurring';
$LANG['caledit_RecurringTitle'] = 'Select Recurring Absence';
$LANG['caledit_recurrence'] = 'Recurrence';
$LANG['caledit_recurrence_comment'] = 'Select the recurrence';
$LANG['caledit_selGroup'] = 'Select Group';
$LANG['caledit_selRegion'] = 'Select Region';
$LANG['caledit_selUser'] = 'Select User';
$LANG['caledit_startDate'] = 'Start Date';
$LANG['caledit_startDate_comment'] = 'Select the start date (must be in this month).';

//
// Calendar Options
//
$LANG['calopt_title'] = 'Calendar Options';

$LANG['calopt_tab_display'] = 'Display';
$LANG['calopt_tab_filter'] = 'Filter';
$LANG['calopt_tab_options'] = 'Options';
$LANG['calopt_tab_remainder'] = 'Remainder';
$LANG['calopt_tab_stats'] = 'Statistics';
$LANG['calopt_tab_summary'] = 'Summary';

$LANG['calopt_calendarFontSize'] = 'Calendar Font Size';
$LANG['calopt_calendarFontSize_comment'] = 'You can decrease or increase the font size of the month calendar view here by entering a percentage value, e.g. 80 or 120.';
$LANG['calopt_currentYearOnly'] = 'Current Year Only';
$LANG['calopt_currentYearOnly_comment'] = 'With this switch, the calendar will be restricted to the current year. Other years cannot be viewed or edited.';
$LANG['calopt_currentYearRoles'] = 'Current Year Roles';
$LANG['calopt_currentYearRoles_comment'] = 'If "Current Year Only" is selected, you can assign this restriction to certain roles here.';
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
$LANG['calopt_includeSummary'] = 'Include Summary';
$LANG['calopt_includeSummary_comment'] = 'Checking this option will add an expandable summary section at the bottom of each month, showing the sums of all absences.';
$LANG['calopt_managerOnlyIncludesAdministrator'] = 'Manager-Only for Administrator';
$LANG['calopt_managerOnlyIncludesAdministrator_comment'] = 'Manager-only absence types can only be set by group managers. With this switch on, also users of role "Administrator" can do so.';
$LANG['calopt_monitorAbsence'] = 'Monitor Absence';
$LANG['calopt_monitorAbsence_comment'] = 'If you select an absence type here, its Remainder/Allowance count will be shown in the user name field of the calendar.';
$LANG['calopt_notificationsAllGroups'] = 'Notifications for All Groups';
$LANG['calopt_notificationsAllGroups_comment'] = 'Per default, users can subscribe to email notifications for user calendar events for own groups only. With this switch on, they can select from all groups.<br>
      <i>Note: Should you switch off this option and users selected other groups for their notifications while it was on, that selection will not change until their profile is saved again.</i>';
$LANG['calopt_pastDayColor'] = 'Past Day Color';
$LANG['calopt_pastDayColor_comment'] = 'Sets a background color for days that lie in the past in month calendar view.
      Leave this field empty if you don\'t want to color the days in the past.';
$LANG['calopt_regionalHolidays'] = 'Mark Regional Holidays';
$LANG['calopt_regionalHolidays_comment'] = 'With this option on, holidays in regions other than the currently selected one will be marked with a colored border.';
$LANG['calopt_regionalHolidaysColor'] = 'Regional Holiday Border Color';
$LANG['calopt_regionalHolidaysColor_comment'] = 'Sets the border color for marking regional holidays.';
$LANG['calopt_repeatHeaderCount'] = 'Repeat Header Count';
$LANG['calopt_repeatHeaderCount_comment'] = 'Specifies the amount of user lines in the calender before the month header is repeated for better readability. If set to 0, the month header will not be repeated.';
$LANG['calopt_satBusi'] = 'Saturday is a Business Day';
$LANG['calopt_satBusi_comment'] = 'By default, Saturday and Sunday are weekend days and displayed accordingly in the calendar. Check this option if you want to make Saturday a business day.';
$LANG['calopt_showAvatars'] = 'Show Avatars';
$LANG['calopt_showAvatars_comment'] = 'Checking this option will show a user avatar pop-up when moving the mouse over the user avatar icon.';
$LANG['calopt_showMonths'] = 'Show Multiple Months';
$LANG['calopt_showMonths_comment'] = 'Enter the number of months to show on the calendar page, maximum 12.';
$LANG['calopt_showRegionButton'] = 'Show Region Filter Button';
$LANG['calopt_showRegionButton_comment'] = 'Checking this option will show the region filter button on top of the calendar for easy switching between different regions.
      If you only use the standard region it might make sense to hide the button by unchecking this option.';
$LANG['calopt_showRoleIcons'] = 'Show Role Icons';
$LANG['calopt_showRoleIcons_comment'] = 'Checking this option will show an icons next to the users\' name indicating the users\' role.';
$LANG['calopt_showSummary'] = 'Expand Summary';
$LANG['calopt_showSummary_comment'] = 'Checking this option will show/expand the summary section by default.';
$LANG['calopt_showTooltipCount'] = 'Tooltip Counter';
$LANG['calopt_showTooltipCount_comment'] = 'Checking this option will show the amount taken for the current absence type and current month in the absence type tooltip.';
$LANG['calopt_showUserRegion'] = 'Show regional holidays per user';
$LANG['calopt_showUserRegion_comment'] = 'If this option is on, the calendar will show the regional holidays in each user row based on the default region
 set for the user. These holidays might then differ from the global regional holidays shown in the month header. This offers a better view on regional
 holiday differences if you manage users from different regions. Note, that this might might be a bit confusing depending on the amount of users and regions. Check it out and pick your choice.';
$LANG['calopt_showWeekNumbers'] = 'Show Week Numbers';
$LANG['calopt_showWeekNumbers_comment'] = 'Checking this option will add a line to the calendar display showing the week of the year number.';
$LANG['calopt_sortByOrderKey'] = 'User Order Key';
$LANG['calopt_sortByOrderKey_comment'] = 'With this option on, the users in the calendar will be sorted by their order key instead of their lastname. The order key is an optional field in the user profile.';
$LANG['calopt_statsDefaultColorAbsences'] = 'Default Color Absence Statistics';
$LANG['calopt_statsDefaultColorAbsences_comment'] = 'Select the default color for this stattistics page.';
$LANG['calopt_statsDefaultColorAbsencetype'] = 'Default Color Absence Type Statistics';
$LANG['calopt_statsDefaultColorAbsencetype_comment'] = 'Select the default color for this stattistics page.';
$LANG['calopt_statsDefaultColorPresences'] = 'Default Color Presence Statistics';
$LANG['calopt_statsDefaultColorPresences_comment'] = 'Select the default color for this stattistics page.';
$LANG['calopt_statsDefaultColorRemainder'] = 'Default Color Remainder Statistics';
$LANG['calopt_statsDefaultColorRemainder_comment'] = 'Select the default color for this stattistics page.';
$LANG['calopt_sunBusi'] = 'Sunday is a Business Day';
$LANG['calopt_sunBusi_comment'] = 'By default, Saturday and Sunday are weekend days and displayed accordingly in the calendar.
      Check this option if you want to make Sunday a business day.';
$LANG['calopt_supportMobile'] = 'Support Mobile Devices';
$LANG['calopt_supportMobile_comment'] = 'With this switch on, TeamCal Neo will prepare the calendar tables (View and Edit) for a specific screen width so that no horizontal scrolling is necessary.
      The user can select his screen width.<br>
      Switch this off if the calendar is only viewed on full size computer screens (greater then 1024 pixels in width). The calendar will still be displayed then but horizontal scrolling will be necessary.';
$LANG['calopt_symbolAsIcon'] = 'Absence Type Character ID as Icon';
$LANG['calopt_symbolAsIcon_comment'] = 'With this option the character ID will be used in the calendar display instead of it\'s icon.';
$LANG['calopt_takeover'] = 'Enable Absence Take-over';
$LANG['calopt_takeover_comment'] = 'With this option enabled, the logged in user can take over absences from other users if he/she can edit the corresponding calendar. Take-over absences are NOT validated
      against any rules. They are removed from the other user and set for the logged in user. Note, that you have to enable each absence type for take-over as well.';
$LANG['calopt_todayBorderColor'] = 'Today Border Color';
$LANG['calopt_todayBorderColor_comment'] = 'Specifies the color in hexadecimal of the left and right border of the today column.';
$LANG['calopt_todayBorderSize'] = 'Today Border Size';
$LANG['calopt_todayBorderSize_comment'] = 'Specifies the size (thickness) in pixel of the left an right border of the today column.';
$LANG['calopt_trustedRoles'] = 'Trusted Roles';
$LANG['calopt_trustedRoles_comment'] = 'Select the roles that can view confidential absences and daynotes.<br>
      <i>Note: You can exclude the role "Administrator" here but the user "admin" functions as a superuser and can always see all data.</i>';
$LANG['calopt_usersPerPage'] = 'Number of users per page';
$LANG['calopt_usersPerPage_comment'] = 'If you maintain a large amount of users in TeamCal Neo you might want to use paging in the calendar display.
      Indicate how much users you want to display on each page. A value of 0 will disable paging. In case you chose paging, there will be paging
      buttons at the bottom of each page.';

//
// Database
//
$LANG['db_tab_repair'] = 'Repair';
$LANG['db_tab_tcpimp'] = 'TeamCal Pro Import';
$LANG['db_clean_what'] = 'What to clean up';
$LANG['db_clean_what_comment'] = 'Select here what you want to clean up. All records selected here that are equal or older then the "Before Date"
 will be deleted. Region and user calendars are deleted by month, independently from the day you enter. Newer records will stay in place.';
$LANG['db_clean_daynotes'] = 'Clean up daynotes before...';
$LANG['db_clean_holidays'] = 'Clean up holidays before...';
$LANG['db_clean_months'] = 'Clean up region calendars before...';
$LANG['db_clean_templates'] = 'Clean up user calendars before...';
$LANG['db_clean_before'] = 'Before Date';
$LANG['db_clean_before_comment'] = 'Records from the above checked tables will be deleted when they are equal or older than the date selected here.';
$LANG['db_clean_confirm'] = 'Confirmation';
$LANG['db_clean_confirm_comment'] = 'Please type in "CLEANUP" to confirm this action.';
$LANG['db_repair_confirm'] = 'Confirmation';
$LANG['db_repair_confirm_comment'] = 'Please type in "REPAIR" to confirm this action.';
$LANG['db_repair_daynoteRegions'] = 'Daynote Regions';
$LANG['db_repair_daynoteRegions_comment'] = 'This option checks whether there are daynotes without a region set. If so, the region will be set to Default.';

//
// Daynote
//
$LANG['dn_title'] = 'Daynote';
$LANG['dn_title_for'] = 'for';
$LANG['dn_alert_create'] = 'Create Daynote';
$LANG['dn_alert_create_success'] = 'The daynote was created successfully.';
$LANG['dn_alert_failed'] = 'The daynote could not be saved. There was invalid input. Please check your last input.';
$LANG['dn_alert_update'] = 'Update Daynote';
$LANG['dn_alert_update_success'] = 'The daynote was updated successfully.';
$LANG['dn_color'] = 'Daynote Color';
$LANG['dn_color_comment'] = 'Select a color for this daynote. This color will be used for the background of the daynote popup.';
$LANG['dn_color_danger'] = '<i class="fas fa-square text-danger"></i>';
$LANG['dn_color_default'] = '<i class="fas fa-square text-default"></i>';
$LANG['dn_color_info'] = '<i class="fas fa-square text-info"></i>';
$LANG['dn_color_primary'] = '<i class="fas fa-square text-primary"></i>';
$LANG['dn_color_success'] = '<i class="fas fa-square text-success"></i>';
$LANG['dn_color_warning'] = '<i class="fas fa-square text-warning"></i>';
$LANG['dn_confidential'] = 'Confidential';
$LANG['dn_confidential_comment'] = 'Checking this box marks this daynote as "confidential". The public and regular users cannot see it
      in the calendar, only admins, manager and the user himself/herself. This feature is useful if you want to hide sensitive daynotes from regular users.
      You might want to use this feature in combination with a confidential absence type on the same day.';
$LANG['dn_confirm_delete'] = 'Are you sure you want to delete this daynote?';
$LANG['dn_date'] = 'Daynote Date';
$LANG['dn_date_comment'] = 'Select a date for this daynote.';
$LANG['dn_daynote'] = 'Daynote Text';
$LANG['dn_daynote_comment'] = 'Enter the text of the daynote.';
$LANG['dn_daynote_placeholder'] = 'Enter your daynote here...';
$LANG['dn_enddate'] = 'Daynote End Date';
$LANG['dn_enddate_comment'] = 'If a date is entered here, the daynote will be copied/deleted to all days from start to end. This date must be greater than the Daynote Date.';
$LANG['dn_regions'] = 'Daynote Regions';
$LANG['dn_regions_comment'] = 'Select the regions for which this daynote shall be visible.';

//
// Declination
//
$LANG['decl_Enddate'] = 'Automatic End Date';
$LANG['decl_Enddate_comment'] = 'Select the date here at which this declination rule shall end. The rule will expire after this day.';
$LANG['decl_Message'] = 'Declination Message';
$LANG['decl_Message_comment'] = 'You can enter a custom declination message here that will be shown to the user when an absence is declined by this rule.
      Your configured date range will be displayed right after that message.';
$LANG['decl_Period'] = 'Active Period';
$LANG['decl_Period_comment'] = 'Select from when to when this declination rule shall be active. If you select an option with a start or end date, enter them below.<br>
      <i>You have to activate this rule at the top before these options will take effect.</i>';
$LANG['decl_Period_nowForever'] = 'As long as activated';
$LANG['decl_Period_nowEnddate'] = 'From activation to end date';
$LANG['decl_Period_startdateForever'] = 'From start date to deactivation';
$LANG['decl_Period_startdateEnddate'] = 'From start date to end date';
$LANG['decl_Startdate'] = 'Automatic Start Date';
$LANG['decl_Startdate_comment'] = 'Select the date here at which this declination rule shall get active. The rule will start being active on this day.';
$LANG['decl_title'] = 'Declination Management';
$LANG['decl_absence'] = 'Activate';
$LANG['decl_absence_comment'] = 'You can setup an absence threshold declination rule below. Activate this rule here.';
$LANG['decl_absenceEnddate'] = $LANG['decl_Enddate'];
$LANG['decl_absenceEnddate_comment'] = $LANG['decl_Enddate_comment'];
$LANG['decl_absencePeriod'] = $LANG['decl_Period'];
$LANG['decl_absencePeriod_comment'] = $LANG['decl_Period_comment'];
$LANG['decl_absencePeriod_nowForever'] = $LANG['decl_Period_nowForever'];
$LANG['decl_absencePeriod_nowEnddate'] = $LANG['decl_Period_nowEnddate'];
$LANG['decl_absencePeriod_startdateForever'] = $LANG['decl_Period_startdateForever'];
$LANG['decl_absencePeriod_startdateEnddate'] = $LANG['decl_Period_startdateEnddate'];
$LANG['decl_absenceStartdate'] = $LANG['decl_Startdate'];
$LANG['decl_absenceStartdate_comment'] = $LANG['decl_Startdate_comment'];
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
$LANG['decl_beforeEnddate'] = $LANG['decl_Enddate'];
$LANG['decl_beforeEnddate_comment'] = $LANG['decl_Enddate_comment'];
$LANG['decl_beforePeriod'] = $LANG['decl_Period'];
$LANG['decl_beforePeriod_comment'] = $LANG['decl_Period_comment'];
$LANG['decl_beforePeriod_nowForever'] = $LANG['decl_Period_nowForever'];
$LANG['decl_beforePeriod_nowEnddate'] = $LANG['decl_Period_nowEnddate'];
$LANG['decl_beforePeriod_startdateForever'] = $LANG['decl_Period_startdateForever'];
$LANG['decl_beforePeriod_startdateEnddate'] = $LANG['decl_Period_startdateEnddate'];
$LANG['decl_beforeStartdate'] = $LANG['decl_Startdate'];
$LANG['decl_beforeStartdate_comment'] = $LANG['decl_Startdate_comment'];
$LANG['decl_label_active'] = 'Active';
$LANG['decl_label_expired'] = 'Expired';
$LANG['decl_label_inactive'] = 'Inactive';
$LANG['decl_label_scheduled'] = 'Scheduled';
$LANG['decl_period1'] = 'Activate';
$LANG['decl_period1_comment'] = 'You can setup a declination period below. The start and end date you pick here is included in that period.
      Activate this rule here.';
$LANG['decl_period1start'] = 'Start date (including)';
$LANG['decl_period1start_comment'] = 'Enter the start date here.';
$LANG['decl_period1end'] = 'End date (including)';
$LANG['decl_period1end_comment'] = 'Enter the end date here.';
$LANG['decl_period1Enddate'] = $LANG['decl_Enddate'];
$LANG['decl_period1Enddate_comment'] = $LANG['decl_Enddate_comment'];
$LANG['decl_period1Message'] = $LANG['decl_Message'];
$LANG['decl_period1Message_comment'] = $LANG['decl_Message_comment'];
$LANG['decl_period1Period'] = $LANG['decl_Period'];
$LANG['decl_period1Period_comment'] = $LANG['decl_Period_comment'];
$LANG['decl_period1Period_nowForever'] = $LANG['decl_Period_nowForever'];
$LANG['decl_period1Period_nowEnddate'] = $LANG['decl_Period_nowEnddate'];
$LANG['decl_period1Period_startdateForever'] = $LANG['decl_Period_startdateForever'];
$LANG['decl_period1Period_startdateEnddate'] = $LANG['decl_Period_startdateEnddate'];
$LANG['decl_period1Startdate'] = $LANG['decl_Startdate'];
$LANG['decl_period1Startdate_comment'] = $LANG['decl_Startdate_comment'];
$LANG['decl_period2'] = 'Activate';
$LANG['decl_period2_comment'] = 'You can setup a declination period below. The start and end date you pick here is included in that period.
      Activate this rule here.';
$LANG['decl_period2start'] = 'Start date (including)';
$LANG['decl_period2start_comment'] = 'Enter the start date here.';
$LANG['decl_period2end'] = 'End date (including)';
$LANG['decl_period2end_comment'] = 'Enter the end date here.';
$LANG['decl_period2Enddate'] = $LANG['decl_Enddate'];
$LANG['decl_period2Enddate_comment'] = $LANG['decl_Enddate_comment'];
$LANG['decl_period2Message'] = $LANG['decl_Message'];
$LANG['decl_period2Message_comment'] = $LANG['decl_Message_comment'];
$LANG['decl_period2Period'] = $LANG['decl_Period'];
$LANG['decl_period2Period_comment'] = $LANG['decl_Period_comment'];
$LANG['decl_period2Period_nowForever'] = $LANG['decl_Period_nowForever'];
$LANG['decl_period2Period_nowEnddate'] = $LANG['decl_Period_nowEnddate'];
$LANG['decl_period2Period_startdateForever'] = $LANG['decl_Period_startdateForever'];
$LANG['decl_period2Period_startdateEnddate'] = $LANG['decl_Period_startdateEnddate'];
$LANG['decl_period2Startdate'] = $LANG['decl_Startdate'];
$LANG['decl_period2Startdate_comment'] = $LANG['decl_Startdate_comment'];
$LANG['decl_period3'] = 'Activate';
$LANG['decl_period3_comment'] = 'You can setup a declination period below. The start and end date you pick here is included in that period.
      Activate this rule here.';
$LANG['decl_period3start'] = 'Start date (including)';
$LANG['decl_period3start_comment'] = 'Enter the start date here.';
$LANG['decl_period3end'] = 'End date (including)';
$LANG['decl_period3end_comment'] = 'Enter the end date here.';
$LANG['decl_period3Enddate'] = $LANG['decl_Enddate'];
$LANG['decl_period3Enddate_comment'] = $LANG['decl_Enddate_comment'];
$LANG['decl_period3Message'] = $LANG['decl_Message'];
$LANG['decl_period3Message_comment'] = $LANG['decl_Message_comment'];
$LANG['decl_period3Period'] = $LANG['decl_Period'];
$LANG['decl_period3Period_comment'] = $LANG['decl_Period_comment'];
$LANG['decl_period3Period_nowForever'] = $LANG['decl_Period_nowForever'];
$LANG['decl_period3Period_nowEnddate'] = $LANG['decl_Period_nowEnddate'];
$LANG['decl_period3Period_startdateForever'] = $LANG['decl_Period_startdateForever'];
$LANG['decl_period3Period_startdateEnddate'] = $LANG['decl_Period_startdateEnddate'];
$LANG['decl_period3Startdate'] = $LANG['decl_Startdate'];
$LANG['decl_period3Startdate_comment'] = $LANG['decl_Startdate_comment'];
$LANG['decl_roles'] = 'Apply to Roles';
$LANG['decl_roles_comment'] = 'Select the roles to which the declination rules will apply. This also applies to the "minimum present" and "maximal absent" settings of each group.';
$LANG['decl_schedule'] = 'Schedule';
$LANG['decl_schedule_nowForever'] = 'As long as activated';
$LANG['decl_schedule_nowEnddate'] = 'From activation until %s';
$LANG['decl_schedule_startdateForever'] = 'From %s until deactivation';
$LANG['decl_schedule_startdateEnddate'] = 'From %s until %s';
$LANG['decl_summary_absence'] = 'Declination of absences if an absence threshold is reached.';
$LANG['decl_summary_before'] = 'Declination of absences before a certain date.';
$LANG['decl_summary_period1'] = 'Declination of absences within a certain period.';
$LANG['decl_summary_period2'] = 'Declination of absences within a certain period.';
$LANG['decl_summary_period3'] = 'Declination of absences within a certain period.';
$LANG['decl_tab_absence'] = 'Absence Threshold';
$LANG['decl_tab_before'] = 'Before Date';
$LANG['decl_tab_overview'] = 'Overview';
$LANG['decl_tab_period1'] = 'Period 1';
$LANG['decl_tab_period2'] = 'Period 2';
$LANG['decl_tab_period3'] = 'Period 3';
$LANG['decl_tab_scope'] = 'Scope';
$LANG['decl_threshold'] = 'Threshold (%)';
$LANG['decl_threshold_comment'] = 'Enter the threshold in percent here. An absence request will be declined if it would cause this threshold to be reached.';
$LANG['decl_value'] = 'Value';

//
// E-Mail
//
$LANG['email_subject_absence_approval'] = APP_NAME . ' Absence Approval Needed';
$LANG['email_subject_month_changed'] = APP_NAME . ' Month Changed';
$LANG['email_subject_month_created'] = APP_NAME . ' Month Created';
$LANG['email_subject_month_deleted'] = APP_NAME . ' Month Deleted';
$LANG['email_subject_usercal_changed'] = APP_NAME . ' User Calendar Changed';

//
// Group
//
$LANG['group_minpresent'] = 'Minimum Present (Weekdays)';
$LANG['group_minpresent_comment'] = 'Enter the minimum amount of present members for this group on weekdays (Monday-Friday). This value is checked when absences are requested.<br>Enter 0 to allow a total absence of the group.';
$LANG['group_maxabsent'] = 'Maximum Absent (Weekdays)';
$LANG['group_maxabsent_comment'] = 'Enter the maximum amount of absent members for this group on weekdays (Monday-Friday). This value is checked when absences are requested.<br>A high value exceeding the amount of group members (e.g. 9999) can be used to allow a total absence of the group.';
$LANG['group_minpresentwe'] = 'Minimum Present (Weekends)';
$LANG['group_minpresentwe_comment'] = 'Enter the minimum amount of present members for this group on weekends (Saturday and Suunday). This value is checked when absences are requested.<br>Enter 0 to allow a total absence of the group on weekends.';
$LANG['group_maxabsentwe'] = 'Maximum Absent (Weekends)';
$LANG['group_maxabsentwe_comment'] = 'Enter the maximum amount of absent members for this group on weekends (Saturday and Suunday). This value is checked when absences are requested.<br>A high value exceeding the amount of group members (e.g. 9999) can be used to allow a total absence of the group on weekends.';

//
// Groups
//
$LANG['groups_minpresent'] = 'Min Present';
$LANG['groups_maxabsent'] = 'Max Absent';
$LANG['groups_minpresentwe'] = 'Min Present WE';
$LANG['groups_maxabsentwe'] = 'Max Absent WE';

//
// Holidays
//
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
$LANG['hol_keepweekendcolor'] = 'Keep weekend color';
$LANG['hol_keepweekendcolor_comment'] = 'The weekend color remains intact if this holiday falls on a Saturday or Sunday.';
$LANG['hol_name'] = 'Name';
$LANG['hol_name_comment'] = 'Enter a name for the holiday here.';
$LANG['hol_noabsence'] = 'No absences allowed';
$LANG['hol_noabsence_comment'] = 'No absences are allowed for this holiday. This will overrule all other declination rules.';

//
// Log
//
$LANG['log_filterCalopt'] = 'Calender Options';
$LANG['log_filterPatterns'] = 'Absence Patterns';

//
// Menu
//
$LANG['mnu_view_calendar'] = 'Calendar (Month)';
$LANG['mnu_view_remainder'] = 'Remainder';
$LANG['mnu_view_year'] = 'Calendar (Year)';
$LANG['mnu_view_stats'] = 'Statistics';
$LANG['mnu_view_stats_absences'] = 'Absence Statistics';
$LANG['mnu_view_stats_abstype'] = 'Absence Type Statistics';
$LANG['mnu_view_stats_absum'] = 'Absence Summary';
$LANG['mnu_view_stats_presences'] = 'Presence Statistics';
$LANG['mnu_view_stats_remainder'] = 'Remainder Statistics';
$LANG['mnu_edit_calendaredit'] = 'Personal Calendar';
$LANG['mnu_edit_monthedit'] = 'Region Calendar';
$LANG['mnu_admin_absences'] = 'Absence Types';
$LANG['mnu_admin_bulkedit'] = 'Allowances';
$LANG['mnu_admin_calendaroptions'] = 'Calendar Options';
$LANG['mnu_admin_declination'] = 'Declination Management';
$LANG['mnu_admin_holidays'] = 'Holidays';
$LANG['mnu_admin_patterns'] = 'Absence Patterns';
$LANG['mnu_admin_regions'] = 'Regions';

//
// Month
//
$LANG['monthedit_title'] = 'Month %s-%s (Region: %s)';
$LANG['monthedit_alert_update'] = 'Update month';
$LANG['monthedit_alert_update_success'] = 'The information for this month was updated.';
$LANG['monthedit_clearHoliday'] = 'Clear';
$LANG['monthedit_confirm_clearall'] = 'Are you sure you want to clear all holidays in this month?<br><br><strong>Year:</strong> %s<br><strong>Month:</strong> %s<br><strong>Region:</strong> %s';
$LANG['monthedit_selRegion'] = 'Select Region';
$LANG['monthedit_selUser'] = 'Select User';

//
// Patterns
//
$LANG['ptn_list_title'] = 'Absence Patterns';
$LANG['ptn_edit_title'] = 'Edit Absence Pattern: ';
$LANG['ptn_alert_edit'] = 'Update Absence Pattern';
$LANG['ptn_alert_edit_success'] = 'The information for this absence pattern was updated.';
$LANG['ptn_alert_created'] = 'The absence pattern was created.';
$LANG['ptn_alert_created_failed'] = 'The absence pattern could not be created. Please check the "Create absence pattern" dialog for input errors.';
$LANG['ptn_alert_deleted'] = 'The absence pattern was deleted.';
$LANG['ptn_alert_exists'] = 'A pattern with that combination already exists as "%s".';
$LANG['ptn_alert_save_failed'] = 'The new information for this absence pattern could not be saved. There was invalid input. Please check for error messages.';
$LANG['ptn_confirm_delete'] = 'Are you sure you want to delete this absence pattern:<br> ';
$LANG['ptn_currentPattern'] = 'Current Pattern';
$LANG['ptn_currentPattern_comment'] = 'This is the current pattern.';
$LANG['ptn_abs1'] = 'Monday Absence Type';
$LANG['ptn_abs1_comment'] = 'Select the absence type for Mondays in this pattern.';
$LANG['ptn_abs2'] = 'Tuesday Absence Type';
$LANG['ptn_abs2_comment'] = 'Select the absence type for Tuesdays in this pattern.';
$LANG['ptn_abs3'] = 'Wednesday Absence Type';
$LANG['ptn_abs3_comment'] = 'Select the absence type for Wednesdays in this pattern.';
$LANG['ptn_abs4'] = 'Thursday Absence Type';
$LANG['ptn_abs4_comment'] = 'Select the absence type for Thursdays in this pattern.';
$LANG['ptn_abs5'] = 'Friday Absence Type';
$LANG['ptn_abs5_comment'] = 'Select the absence type for Fridays in this pattern.';
$LANG['ptn_abs6'] = 'Saturday Absence Type';
$LANG['ptn_abs6_comment'] = 'Select the absence type for Saturdays in this pattern.';
$LANG['ptn_abs7'] = 'Sunday Absence Type';
$LANG['ptn_abs7_comment'] = 'Select the absence type for Sundays in this pattern.';
$LANG['ptn_description'] = 'Description';
$LANG['ptn_description_comment'] = 'Enter a description for the absence pattern here.';
$LANG['ptn_name'] = 'Name';
$LANG['ptn_name_comment'] = 'Enter a name for the absence pattern here.';
$LANG['ptn_pattern'] = 'Pattern';


//
// Permissions
//
$LANG['perm_absenceedit_title'] = 'Absence Types (Edit)';
$LANG['perm_absenceedit_desc'] = 'Allows to list and edit absence types.';
$LANG['perm_absum_title'] = 'Absence Summary (View)';
$LANG['perm_absum_desc'] = 'Allows to view the absence summary page for users.';
$LANG['perm_calendaredit_title'] = 'Calendar (Edit)';
$LANG['perm_calendaredit_desc'] = 'Allows to open the calendar editor. This permission is required to edit any user calendars.';
$LANG['perm_calendareditall_title'] = 'Calendar (Edit All)';
$LANG['perm_calendareditall_desc'] = 'Allows to edit the calendars of all users. User with this permission can set any absence for anybody without validation.';
$LANG['perm_calendareditgroup_title'] = 'Calendar (Edit Group as Member or Manager)';
$LANG['perm_calendareditgroup_desc'] = 'Allows to edit all user calendars of those groups that the role holder is member or manager of.';
$LANG['perm_calendareditgroupmanaged_title'] = 'Calendar (Edit Group as Manager)';
$LANG['perm_calendareditgroupmanaged_desc'] = 'Allows to edit all user calendars of those groups that the role holder is manager of.';
$LANG['perm_calendareditown_title'] = 'Calendar (Edit Own)';
$LANG['perm_calendareditown_desc'] = 'Allows to edit the own calendar. If you run a central absence management you might want to switch this off for regular users.';
$LANG['perm_calendaroptions_title'] = 'Calendar (Options)';
$LANG['perm_calendaroptions_desc'] = 'Allows to configure the calendar options.';
$LANG['perm_calendarview_title'] = 'Calendar (View)';
$LANG['perm_calendarview_desc'] = 'Allows to view the calendar (month and year). If this is not permitted, no calendars can be displayed. Can be used to allow the public
       to view the calendar.';
$LANG['perm_calendarviewall_title'] = 'Calendar (View All)';
$LANG['perm_calendarviewall_desc'] = 'Allows to view all calendars.';
$LANG['perm_calendarviewgroup_title'] = 'Calendar (View Group)';
$LANG['perm_calendarviewgroup_desc'] = 'Allows to view all calendars of users that are in the same group as the logged in user.';
$LANG['perm_daynote_title'] = 'Daynotes (Edit)';
$LANG['perm_daynote_desc'] = 'Allows to edit personal daynotes.';
$LANG['perm_daynoteglobal_title'] = 'Daynotes (Edit Global)';
$LANG['perm_daynoteglobal_desc'] = 'Allows to edit global daynotes for region calendars.';
$LANG['perm_declination_title'] = 'Declination Management';
$LANG['perm_declination_desc'] = 'Allows to access the declination management page.';
$LANG['perm_groupcalendaredit_title'] = 'Group Calendar (Edit)';
$LANG['perm_groupcalendaredit_desc'] = 'Allows to edit group calendars if also the permission feature "Calendar (Edit Group as Member or Manager)" or "Calendar (Edit All)" is granted to the role.<br>
  <i>Note: Group managers can always edit the group calendar of the groups they manage without this permission.</i>';
$LANG['perm_holidays_title'] = 'Holidays (Edit)';
$LANG['perm_holidays_desc'] = 'Allows to list and edit holidays.';
$LANG['perm_manageronlyabsences_title'] = 'Group Manager Absence Types (Edit)';
$LANG['perm_manageronlyabsences_desc'] = 'Allows to edit Group Manager Only absence types.';
$LANG['perm_patternedit_title'] = 'Absence Patterns (Edit)';
$LANG['perm_patternedit_desc'] = 'Allows to manage absence patterns.';
$LANG['perm_regions_title'] = 'Regions (Edit)';
$LANG['perm_regions_desc'] = 'Allows to list and edit regions and their holidays.';
$LANG['perm_remainder_title'] = 'Remainder';
$LANG['perm_remainder_desc'] = 'Allows to view the Remainder page.';
$LANG['perm_statistics_title'] = 'Statistics (View)';
$LANG['perm_statistics_desc'] = 'Allows to view the statistics page.';
$LANG['perm_userabsences_title'] = 'User (Absences Tab)';
$LANG['perm_userabsences_desc'] = 'Enables the Absences tab when editing a user profile.';
$LANG['perm_userallowance_title'] = 'User (Absences Allowances)';
$LANG['perm_userallowance_desc'] = 'Allows to edit individual allowances for absence types in the user profile Absences tab. The tab has to be enabled.';

//
// Profile
//
$LANG['profile_tab_absences'] = 'Absences';
$LANG['profile_abs_name'] = 'Absence Type';
$LANG['profile_abs_allowance'] = 'Allowance';
$LANG['profile_abs_allowance_tt'] = 'Specify an individual yearly allowance. 0 will take over the global value (in brackets).';
$LANG['profile_abs_allowance_tt2'] = 'Individual personal allowance. Global value in brackets.';
$LANG['profile_abs_carryover'] = 'Carryover';
$LANG['profile_abs_carryover_tt'] = 'The Carryover field also allows negative values and can be used to reduce the allowance for this user for the current year.';
$LANG['profile_abs_taken'] = 'Taken';
$LANG['profile_abs_factor'] = 'Factor';
$LANG['profile_abs_remainder'] = 'Remainder';
$LANG['profile_calendarMonths'] = 'Calendar Months';
$LANG['profile_calendarMonths_comment'] = 'Select the amount of months to display on the calendar page.';
$LANG['profile_calendarMonths_default'] = 'Default';
$LANG['profile_calendarMonths_one'] = 'One';
$LANG['profile_calendarMonths_two'] = 'Two';
$LANG['profile_calfilterGroup'] = 'Default Group Filter';
$LANG['profile_calfilterGroup_comment'] = 'The calendar view can be filtered to a single group. This can be set here or on the calendar page itself.';
$LANG['profile_guestships'] = 'Show in other groups';
$LANG['profile_guestships_comment'] = 'Show the calendar of this user in the selected groups, even if not a member (called a "guest membership"). Use this feature if the user is ' .
  'not a member but the absences are still important to see along with those of the selected groups.<br><i>Guest users will be shown in italic font in the calendar</i>.';
$LANG['profile_hidden'] = '<i class="far fa-eye-slash text-info" style="padding-right: 8px;"></i>Hide in calendar';
$LANG['profile_hidden_comment'] = 'With this option you can keep the user active but hide him in the calendar. The absences will still be counted in the statistics though. If that is
      not wanted, consider archiving this user.';
$LANG['profile_notifyAbsenceEvents'] = 'Absence Events';
$LANG['profile_notifyCalendarEvents'] = 'Calendar Events';
$LANG['profile_notifyHolidayEvents'] = 'Holidays Events';
$LANG['profile_notifyMonthEvents'] = 'Month Template Events';
$LANG['profile_notifyNone'] = 'None';
$LANG['profile_notifyUserCalEvents'] = 'User Calendar Events';
$LANG['profile_notifyUserCalEventsOwn'] = 'User Calendar Events (only my own)';
$LANG['profile_notifyUserCalGroups'] = 'User Calendar Event Groups';
$LANG['profile_notifyUserCalGroups_comment'] = 'If you have selected "' . $LANG['profile_notifyUserCalEvents'] . '" in the event list above, select here for which of your groups you want to receive these notifications.';
$LANG['profile_showMonths'] = 'Show Multiple Months';
$LANG['profile_showMonths_comment'] = 'Enter the number of months to show on the calendar page, maximum 12.';

//
// Region
//
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

//
// Regions
//
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

//
// Remainder
//
$LANG['rem_title'] = 'Remainder';
$LANG['rem_legend_taken'] = 'Taken';
$LANG['rem_legend_allowance'] = 'Allowance';
$LANG['rem_legend_remainder'] = 'Remainder';
$LANG['rem_year_comment'] = 'Select the year for this summary.';

//
// Statistics
//
$LANG['stats_title_absences'] = 'Absence Statistics';
$LANG['stats_title_abstype'] = 'Absence Type Statistics';
$LANG['stats_title_presences'] = 'Presence Statistics';
$LANG['stats_title_remainder'] = 'Remainder Statistics';
$LANG['stats_absenceType'] = 'Absence Type';
$LANG['stats_absenceType_comment'] = 'Select the absence type for the statistic.';
$LANG['stats_bygroups'] = '(Per Group)';
$LANG['stats_byusers'] = '(Per User)';
$LANG['stats_color'] = 'Color';
$LANG['stats_color_comment'] = 'Select the color for the diagram.';
$LANG['stats_customColor'] = 'Custom Color';
$LANG['stats_customColor_comment'] = 'Select a custom color for the diagram.<br>This value only applies if "' . $LANG['custom'] . '" was selected in the Color list.';
$LANG['stats_endDate'] = 'End Date';
$LANG['stats_endDate_comment'] = 'Select a custom end date for the statistic. This date only applies if "' . $LANG['custom'] . '" was selected in the Period list.';
$LANG['stats_group'] = 'Group';
$LANG['stats_group_comment'] = 'Select the group for the statistics.';
$LANG['stats_modalAbsenceTitle'] = 'Select Absence Type for the Statistics';
$LANG['stats_modalDiagramTitle'] = 'Select Diagram Options for the Statistics';
$LANG['stats_modalGroupTitle'] = 'Select Group for the Statistics';
$LANG['stats_modalPeriodTitle'] = 'Select Period for the Statistics';
$LANG['stats_modalYearTitle'] = 'Select the Year for the Statistics';
$LANG['stats_period'] = 'Period';
$LANG['stats_period_comment'] = 'Select the period for the statistic.';
$LANG['stats_absences_desc'] = 'This statistics shows all entered absences.';
$LANG['stats_abstype_desc'] = 'This statistics shows all entered absences by type.';
$LANG['stats_presences_desc'] = 'This statistics shows all presences.';
$LANG['stats_remainder_desc'] = 'This statistics shows the remainders of all absence types that have a limited allowance.';
$LANG['stats_showAsPieChart'] = 'Show as Pie Chart';
$LANG['stats_total'] = 'Total sum';
$LANG['stats_yaxis'] = 'Diagram Y-axis';
$LANG['stats_yaxis_comment'] = 'Select what the diagram y-axis shall show.';
$LANG['stats_yaxis_groups'] = 'Groups';
$LANG['stats_yaxis_users'] = 'Users';
$LANG['stats_year'] = 'Year';
$LANG['stats_year_comment'] = 'Select the year for this statistics.';
$LANG['stats_startDate'] = 'Start Date';
$LANG['stats_startDate_comment'] = 'Select a custom start date for the statistic.<br>This date only applies if "' . $LANG['custom'] . '" was selected in the Period list.';

//
// Users
//
$LANG['users_alert_activate_selected_users'] = 'The selected users were activated.';
$LANG['users_confirm_activate'] = 'Are you sure you want to activate the selected users?<br>This will unhide, unhold, unlock and verify the selected users.';

//
// Year
//
$LANG['year_title'] = 'Year Calendar %s for %s (Region: %s)';
$LANG['year_selRegion'] = 'Select Region';
$LANG['year_selUser'] = 'Select User';
$LANG['year_showyearmobile'] = '<p>The Year Calendar serves the purpose of seeing the whole year "on first sight". On mobile devices with smaller screen sizes this
      is not possible without horizontal scrolling.</p><p>Click the "Show calendar" button below to enable this display and accept horizontal scrolling.</p>';
