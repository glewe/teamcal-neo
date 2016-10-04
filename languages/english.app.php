<?php
/**
 * english.app.php
 * 
 * Application language file
 *
 * @category TeamCal Neo 
 * @version 0.9.011
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

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
$LANG['absence'] = 'Absence Type';
$LANG['region'] = 'Region';
$LANG['weeknumber'] = 'Calendar week';
$LANG['year'] = 'Year';

//
// Absences
//
$LANG['abs_list_title'] = 'Absence Types';
$LANG['abs_edit_title'] = 'Edit Absence Type: ';
$LANG['abs_icon_title'] = 'Select Absence Type Icon: ';
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
$LANG['abs_manager_only'] = 'Manager Only';
$LANG['abs_manager_only_comment'] = 'Checking this box defines that only managers can set this absence type. Only if the logged in user is the manager of
      the user who\'s calendar is being edited will this absence type be available.';
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
$LANG['alert_decl_allowmonth_reached'] = ": The maximum amount of %1% per month for this absence type is exceeded.";
$LANG['alert_decl_allowweek_reached'] = ": The maximum amount of %1% per week for this absence type is exceeded.";
$LANG['alert_decl_approval_required'] = ": This absence type requires approval. It has been entered in your calendar but a daynote was added to indicate that it is not approved yet. Your manager was informed by mail.";
$LANG['alert_decl_approval_required_daynote'] = "This absence was requested but is not approved yet.";
$LANG['alert_decl_before_date'] = ": Absence changes before the following date are not allowed: ";
$LANG['alert_decl_group_threshold'] = ": Group absence threshold reached for your group(s): ";
$LANG['alert_decl_period'] = ": Absence changes in the following period are not allowed: ";
$LANG['alert_decl_total_threshold'] = ": Total absence threshold reached.";

//
// Buttons
//
$LANG['btn_abs_edit'] = 'Back to Edit';
$LANG['btn_abs_icon'] = 'Select Icon';
$LANG['btn_abs_list'] = 'Absence Type List';
$LANG['btn_absum'] = 'Absence Summary';
$LANG['btn_calendar'] = 'Calendar';
$LANG['btn_cleanup'] = 'Cleanup';
$LANG['btn_create_abs'] = 'Create Absence Type';
$LANG['btn_create_holiday'] = 'Create Holiday';
$LANG['btn_create_region'] = 'Create Region';
$LANG['btn_delete_abs'] = 'Delete Absence Type';
$LANG['btn_delete_holiday'] = 'Delete Holiday';
$LANG['btn_holiday_list'] = 'Holiday List';
$LANG['btn_region_list'] = 'Region List';
$LANG['btn_showcalendar'] = 'Show Calendar';

//
// Calendar
//
$LANG['cal_title'] = 'Calendar %s-%s (Region: %s)';
$LANG['cal_tt_anotherabsence'] = 'Another absence';
$LANG['cal_tt_backward'] = 'Go back one month...';
$LANG['cal_tt_forward'] = 'Go forward one month...';
$LANG['cal_search'] = 'Search User';
$LANG['cal_selAbsence'] = 'Select Absence';
$LANG['cal_selAbsence_comment'] = 'Shows all entries having this absence type for today.';
$LANG['cal_selGroup'] = 'Select Group';
$LANG['cal_selRegion'] = 'Select Region';
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
$LANG['remainder'] = 'Remainder';
$LANG['exp_summary'] = 'Expand Summary section...';
$LANG['col_summary'] = 'Collapse Summary section...';
$LANG['exp_remainder'] = 'Expand Remainder section...';
$LANG['col_remainder'] = 'Collapse Remainder section...';

//
// Calendar Edit
//
$LANG['caledit_title'] = 'Edit month %s-%s for %s';
$LANG['caledit_absenceType'] = 'Absence Type';
$LANG['caledit_absenceType_comment'] = 'Select the absence type for this input.';
$LANG['caledit_alert_out_of_range'] = 'The dates entered were at least partially out of the currently displayed month. No changes were saved.';
$LANG['caledit_alert_save_failed'] = 'The absence information could not be saved. There was invalid input. Please check your last input.';
$LANG['caledit_alert_update'] = 'Update month';
$LANG['caledit_alert_update_all'] = 'All absences were accepted and the calendar was updated accordingly.';
$LANG['caledit_alert_update_partial'] = 'Some absences were not accepted because they violate restrictions set by the management. 
      The following requests were declined:';
$LANG['caledit_alert_update_none'] = 'The absences were not accepted because the requested absences violate restrictions set up by the management. 
      The calendar was not updated.';
$LANG['caledit_clearAbsence'] = 'Clear';
$LANG['caledit_clearDaynotes'] = 'Clear Daynotes';
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

//
// Calendar Options
//
$LANG['calopt_title'] = 'Calendar Options';

$LANG['calopt_tab_display'] = 'Display';
$LANG['calopt_tab_filter'] = 'Filter';
$LANG['calopt_tab_options'] = 'Settings';
$LANG['calopt_tab_remainder'] = 'Remainder';
$LANG['calopt_tab_stats'] = 'Statistics';
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
$LANG['calopt_includeSummary'] = 'Include Summary';
$LANG['calopt_includeSummary_comment'] = 'Checking this option will add an expandable summary section at the bottom of each month, showing the sums of all absences.';
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
$LANG['calopt_showAvatars'] = 'Show Avatars';
$LANG['calopt_showAvatars_comment'] = 'Checking this option will show a user avatar pop-up when moving the mouse over the user avatar icon.'; 
$LANG['calopt_showRoleIcons'] = 'Show Role Icons';
$LANG['calopt_showRoleIcons_comment'] = 'Checking this option will show an icons next to the users\' name indicating the users\' role.';
$LANG['calopt_showSummary'] = 'Expand Summary';
$LANG['calopt_showSummary_comment'] = 'Checking this option will show/expand the summary section by default.';
$LANG['calopt_showUserRegion'] = 'Show regional holidays per user';
$LANG['calopt_showUserRegion_comment'] = 'If this option is on, the calendar will show the regional holidays in each user row based on the default region 
      set for the user. These holidays might then differ from the global regional holidays shown in the month header. This offers a better view on regional 
      holiday differences if you manage users from different regions. Note, that this might might be a bit confusing depending on the amount of users and regions. 
      Check it out and pick your choice.';
$LANG['calopt_showWeekNumbers'] = 'Show Week Numbers';
$LANG['calopt_showWeekNumbers_comment'] = 'Checking this option will add a line to the calendar display showing the week of the year number.';
$LANG['calopt_statsScale'] = 'Diagram Scale';
$LANG['calopt_statsScale_comment'] = 'Select a diagram scale option for the statistics pages.
      <ul>
         <li>Automatic: The diagram\'s max value is the actual maximal value.</li>
         <li>Smart: The diagram\'s max value is the actual maximal value plus the Smart Value.</li>
      </ul>';
$LANG['calopt_statsSmartValue'] = 'Diagram Scale Smart Value';
$LANG['calopt_statsSmartValue_comment'] = 'The smart value is added to the maximal value read and the sum is used as the diagram\'s scale maximum.<br>This value only applies if the diagram scale is set to "'.$LANG['smart'].'".';
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

//
// Database
//
$LANG['db_tab_tcpimp'] = 'TeamCal Pro Import';
$LANG['db_clean_what'] = 'What to clean up';
$LANG['db_clean_what_comment'] = 'Select here what you want to clean up. All records selected here that are equal or older then the "Before Date" will be deleted. 
      Region and user calendars are deleted by month, independently from the day you enter. Newer records will stay in place.';
$LANG['db_clean_daynotes'] = 'Clean up daynotes before...';
$LANG['db_clean_holidays'] = 'Clean up holidays before...';
$LANG['db_clean_months'] = 'Clean up region calendars before...';
$LANG['db_clean_templates'] = 'Clean up user calendars before...';
$LANG['db_clean_before'] = 'Before Date';
$LANG['db_clean_before_comment'] = 'Records from the above checked tables will be deleted when they are equal or older than the date selected here.';
$LANG['db_clean_confirm'] = 'Confirmation';
$LANG['db_clean_confirm_comment'] = 'Please type in "CLEANUP" to confirm this action.';
$LANG['db_tcpimp'] = 'TeamCal Pro Import';
$LANG['db_tcpimp_comment'] = 'If you have used TeamCal Pro before and want to import data from it, you can do so by clicking the Import button below.';
$LANG['db_tcpimp2'] = 'However...';

//
// Daynote
//
$LANG['dn_title'] = 'Daynote';
$LANG['dn_title_for'] = 'for';
$LANG['dn_alert_create'] = 'Create Daynote';
$LANG['dn_alert_create_success'] = 'The daynote was created successfully.';
$LANG['dn_alert_update'] = 'Update Daynote';
$LANG['dn_alert_update_success'] = 'The daynote was updated successfully.';
$LANG['dn_color'] = 'Daynote Color';
$LANG['dn_color_comment'] = 'Select a color for this daynote. This color will be used for the background of the daynote popup.';
$LANG['dn_color_danger'] = '<i class="fa fa-square text-danger"></i>';
$LANG['dn_color_default'] = '<i class="fa fa-square text-default"></i>';
$LANG['dn_color_info'] = '<i class="fa fa-square text-info"></i>';
$LANG['dn_color_primary'] = '<i class="fa fa-square text-primary"></i>';
$LANG['dn_color_success'] = '<i class="fa fa-square text-success"></i>';
$LANG['dn_color_warning'] = '<i class="fa fa-square text-warning"></i>';
$LANG['dn_confirm_delete'] = 'Are you sure you want to delete this daynote?';
$LANG['dn_date'] = 'Daynote Date';
$LANG['dn_date_comment'] = 'Select a date for this daynote.';
$LANG['dn_daynote'] = 'Daynote Text';
$LANG['dn_daynote_comment'] = 'Enter the text of the daynote.';
$LANG['dn_daynote_placeholder'] = 'Enter your daynote here...';
$LANG['dn_enddate'] = 'Daynote End Date';
$LANG['dn_enddate_comment'] = 'If a date is entered here, the daynote will be copied/deleted to all days from start to end. This date must be greater than the Daynote Date.';

//
// Declination
//
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

//
// E-Mail
//
$LANG['email_subject_absence_approval'] = $CONF['app_name'] . ' Absence Approval Needed';
$LANG['email_subject_month_changed'] = $CONF['app_name'] . ' Month Changed';
$LANG['email_subject_month_created'] = $CONF['app_name'] . ' Month Created';
$LANG['email_subject_month_deleted'] = $CONF['app_name'] . ' Month Deleted';
$LANG['email_subject_usercal_changed'] = $CONF['app_name'] . ' User Calendar Changed';

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
$LANG['hol_name'] = 'Name';
$LANG['hol_name_comment'] = 'Enter a name for the holiday here.';

//
// Log
//
$LANG['log_filterCalopt'] = 'Calender Options';

//
// Menu
//
$LANG['mnu_view_calendar'] = 'Calendar (Month)';
$LANG['mnu_view_stats'] = 'Statistics';
$LANG['mnu_view_stats_absences'] = 'Absence Statistics';
$LANG['mnu_view_stats_abstype'] = 'Absence Type Statistics';
$LANG['mnu_view_stats_absum'] = 'User Absence Summary';
$LANG['mnu_view_stats_presences'] = 'Presence Statistics';
$LANG['mnu_view_stats_remainder'] = 'Remainder Statistics';
$LANG['mnu_view_year'] = 'Calendar (Year)';
$LANG['mnu_edit_calendaredit'] = 'Personal Calendar';
$LANG['mnu_edit_monthedit'] = 'Region Calendar';
$LANG['mnu_admin_absences'] = 'Absence Types';
$LANG['mnu_admin_calendaroptions'] = 'Calendar Options';
$LANG['mnu_admin_declination'] = 'Declination Management';
$LANG['mnu_admin_holidays'] = 'Holidays';
$LANG['mnu_admin_regions'] = 'Regions';
$LANG['mnu_help_vote'] = 'Vote for TeamCal Neo';

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
// Permissions
//
$LANG['perm_absenceedit_title'] = 'Absence Types (Edit)';
$LANG['perm_absenceedit_desc'] = 'Allows to list and edit absence types.';
$LANG['perm_absum_title'] = 'Absence Summary (View)';
$LANG['perm_absum_desc'] = 'Allows to view the absence summary page for users.';
$LANG['perm_calendaredit_title'] = 'Calendar (Edit)';
$LANG['perm_calendaredit_desc'] = 'Allows to open the calendar editor. This permission is required to edit any user calendars.';
$LANG['perm_calendareditall_title'] = 'Calendar (Edit All)';
$LANG['perm_calendareditall_desc'] = 'Allows to edit the calendars of all users.';
$LANG['perm_calendareditgroup_title'] = 'Calendar (Edit Group)';
$LANG['perm_calendareditgroup_desc'] = 'Allows to edit all user calendars of own groups.';
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
$LANG['perm_holidays_title'] = 'Holidays (Edit)';
$LANG['perm_holidays_desc'] = 'Allows to list and edit holidays.';
$LANG['perm_regions_title'] = 'Regions (Edit)';
$LANG['perm_regions_desc'] = 'Allows to list and edit regions and their holidays.';
$LANG['perm_statistics_title'] = 'Statistics (View)';
$LANG['perm_statistics_desc'] = 'Allows to view the statistics page.';

//
// Profile
//
$LANG['profile_tab_absences'] = 'Absences';
$LANG['profile_abs_name'] = 'Absence Type';
$LANG['profile_abs_allowance'] = 'Allowance';
$LANG['profile_abs_carryover'] = 'Carryover';
$LANG['profile_abs_carryover_tt'] = 'The Carryover field also allows negative values and can be used to reduce the allowance for this user for the current year.';
$LANG['profile_abs_taken'] = 'Taken';
$LANG['profile_abs_factor'] = 'Factor';
$LANG['profile_abs_remainder'] = 'Remainder';
$LANG['profile_notifyAbsenceEvents'] = 'Absence Events';
$LANG['profile_notifyAbsenceEvents_comment'] = 'Select this option if you want to be informed on changes to absence types.';
$LANG['profile_notifyHolidayEvents'] = 'Holidays Events';
$LANG['profile_notifyHolidayEvents_comment'] = 'Select this option if you want to be informed on changes to holidays.';
$LANG['profile_notifyMonthEvents'] = 'Month Template Events';
$LANG['profile_notifyMonthEvents_comment'] = 'Select this option if you want to be informed on changes to month templates.';
$LANG['profile_notifyUserCalEvents'] = 'User Calendar Events';
$LANG['profile_notifyUserCalEvents_comment'] = 'Select this option if you want to be informed on changes to user calendars.';

//
// Region
//
$LANG['region_edit_title'] = 'Edit Group: ';
$LANG['region_alert_edit'] = 'Update group';
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
$LANG['stats_customColor_comment'] = 'Select a custom color for the diagram.<br>This value only applies if "'.$LANG['custom'].'" was selected in the Color list.';
$LANG['stats_endDate'] = 'End Date';
$LANG['stats_endDate_comment'] = 'Select a custom end date for the statistic. This date only applies if "'.$LANG['custom'].'" was selected in the Period list.';
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
$LANG['stats_year'] = 'Year';
$LANG['stats_year_comment'] = 'Select the year for this statistics.';
$LANG['stats_startDate'] = 'Start Date';
$LANG['stats_startDate_comment'] = 'Select a custom start date for the statistic.<br>This date only applies if "'.$LANG['custom'].'" was selected in the Period list.';

//
// TeamCal Pro Import
//
$LANG['tcpimp_title'] = 'TeamCal Pro Import';
$LANG['tcpimp_tab_info'] = 'Information';
$LANG['tcpimp_tab_tcpdb'] = 'TeamCal Pro Database';
$LANG['tcpimp_tab_import'] = 'Import';

$LANG['tcpimp_add'] = 'Add to TeamCal Neo records';
$LANG['tcpimp_btn_add_all'] = 'Add all';
$LANG['tcpimp_btn_replace_all'] = 'Replace all';
$LANG['tcpimp_from'] = 'TeamCal Pro 3.6.019 (or higher)';
$LANG['tcpimp_no'] = 'Do not import';
$LANG['tcpimp_replace'] = 'Replace TeamCal Neo records';
$LANG['tcpimp_to'] = 'TeamCal Neo 1.0.000';

$LANG['tcpimp_confirm_import'] = 'Are you sure you want to start the import? This will apply changes to your current database. It is highly recommended to make a backup first.';

$LANG['tcpimp_alert_title'] = 'TeamCal Pro Import';
$LANG['tcpimp_alert_fail'] = 'One or more database queries failed or no or incorrect tables were selected. Please check your data and apply the necessary manual adjustments. You may also reset to the sample data in Database Management.';
$LANG['tcpimp_alert_success'] = 'Your TeamCal Pro import was successfull. Please check your data and apply the necessary manual adjustments.';
$LANG['tcpimp_alert_success_help'] = 'The following tables were imported:';

$LANG['tcpimp_info'] = '<p>TeamCal Neo has been completely rewritten. Specifically the database has seen many structural changes. It is not compatible anymore to TeamCal Pro.
      You can only import core data from TeamCal Pro. You have to still adjust TeamCal Neo settings after.</p>
      <p>It is important that you have upgraded your TeamCal Pro instance to the latest release '.$LANG['tcpimp_from'].' before you attempt these imports.</p>
      <p>Your TeamCal Pro database will not be changed, just read.</p>';

$LANG['tcpimp_tcp_dbName'] = 'TeamCal Pro Database Name';
$LANG['tcpimp_tcp_dbName_comment'] = 'Specify the name of the TeamCal Pro database. This needs to be an existing database.';
$LANG['tcpimp_tcp_dbUser'] = 'TeamCal Pro Database User';
$LANG['tcpimp_tcp_dbUser_comment'] = 'Specify the username to log in to your TeamCal Pro database.';
$LANG['tcpimp_tcp_dbPassword'] = 'TeamCal Pro Database Password';
$LANG['tcpimp_tcp_dbPassword_comment'] = 'Specify the password to log in to your TeamCal Pro database.';
$LANG['tcpimp_tcp_dbPrefix'] = 'TeamCal Pro Database Table Prefix';
$LANG['tcpimp_tcp_dbPrefix_comment'] = 'Specify a prefix for your TeamCal Pro database tables or leave empty for none. E.g. "tcpro_".';
$LANG['tcpimp_tcp_dbServer'] = 'TeamCal Pro Database Server';
$LANG['tcpimp_tcp_dbServer_comment'] = 'Specify the URL of the TeamCal Pro database server.';

$LANG['tcpimp_abs'] = 'Absence Types';
$LANG['tcpimp_abs_comment'] = '<p>The "counts_as" relations between absence types cannot be imported. You need to set them up manually after import.<br>
      This import is needed if you also want to import the following tables:</p>
      <ul>
         <li>Allowances</li>
      </ul>';
$LANG['tcpimp_allo'] = 'Allowances';
$LANG['tcpimp_allo_comment'] = '<p>To import the allowances you also need to import the following tables:</p>
      <ul>
         <li>Absence Types</li>
         <li>User Accounts</li>
      </ul>';
$LANG['tcpimp_dayn'] = 'Daynotes';
$LANG['tcpimp_dayn_comment'] = '<p>To import the daynotes you also need to import the following tables:</p>
      <ul>
         <li>Regions</li>
         <li>User Accounts</li>
      </ul>';
$LANG['tcpimp_groups'] = 'Groups';
$LANG['tcpimp_groups_comment'] = '<p>All groups will be imported<br>
      This import is needed if you also want to import the following tables:</p>
      <ul>
         <li>Group Memberships</li>
      </ul>';
$LANG['tcpimp_hols'] = 'Holidays';
$LANG['tcpimp_hols_comment'] = '<p>All holidays will be imported.<br>
      This import is needed if you also want to import the following tables:</p>
      <ul>
         <li>Month Templates</li>
      </ul>';
$LANG['tcpimp_mtpl'] = 'Region Calendars';
$LANG['tcpimp_mtpl_comment'] = '<p>To import the region calendars you also need to import the following tables:</p>
      <ul>
         <li>Holidays</li>
         <li>Regions</li>
      </ul>';
$LANG['tcpimp_regs'] = 'Regions';
$LANG['tcpimp_regs_comment'] = '<p>All regions will be imported<br>
      This import is needed if you also want to import the following tables:</p>
      <ul>
         <li>Month Templates</li>
      </ul>';
$LANG['tcpimp_roles'] = 'Roles';
$LANG['tcpimp_roles_comment'] = 'The roles "Director" and "Assistant" will be added. Roles will not be replaced. If you chose to import the user accounts as well, these two roles will be assigned accordingly.';
$LANG['tcpimp_ugr'] = 'Group Memberships';
$LANG['tcpimp_ugr_comment'] = '<p>To import the user group assignments you also need to import the following tables:</p>
      <ul>
         <li>Groups</li>
         <li>User Accounts</li>
      </ul>';
$LANG['tcpimp_users'] = 'User Accounts';
$LANG['tcpimp_users_comment'] = '<p>User accounts and basic user options will be imported. Avatars will not be imported. If you don\'t import the TeamCal Pro roles, roles will be mapped to "Administrator" or "User".<br>
      This import is needed if you also want to import the following tables:</p>
      <ul>
         <li>Allowances</li>
         <li>Group Memberships</li>
      </ul>';
$LANG['tcpimp_utpl'] = 'User Calendars';
$LANG['tcpimp_utpl_comment'] = '<p>To import the user calendars you also need to import the following tables:</p>
      <ul>
         <li>Absence Types</li>
         <li>User Accounts</li>
      </ul>';

//
// Year
//
$LANG['year_title'] = 'Year Calendar %s for %s (Region: %s)';
$LANG['year_selRegion'] = 'Select Region';
$LANG['year_selUser'] = 'Select User';
$LANG['year_showyearmobile'] = '<p>The Year Calendar serves the purpose of seeing the whole year "on first sight". On mobile devices with smaller screen sizes this
      is not possible without horizontal scrolling.</p><p>Click the "Show calendar" button below to enable this display and accept horizontal scrolling.</p>';
?>
