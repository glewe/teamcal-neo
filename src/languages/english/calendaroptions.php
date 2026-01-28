<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: Calendar Options
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['calopt_title'] = 'Calendar Options';

$LANG['calopt_tab_display'] = 'Display';
$LANG['calopt_tab_filter'] = 'Filter';
$LANG['calopt_tab_options'] = 'Options';
$LANG['calopt_tab_remainder'] = 'Remainder';
$LANG['calopt_tab_stats'] = 'Statistics';
$LANG['calopt_tab_summary'] = 'Summary';

$LANG['calopt_alert_edit_success'] = 'The calendar options were updated.';
$LANG['calopt_alert_failed'] = 'The calendar options could not be updated. Please check your input.';
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
      That way they can be used for managing purposes only. This switch does not affect birthday notes.';
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
$LANG['calopt_monitorAbsence_comment'] = 'If you select one or more absence types here, their Remainder/Allowance counts will be shown in the user name field of the calendar.';
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
$LANG['calopt_repeatHeaderCount_comment'] = 'Specifies the amount of user lines in the calendar before the month header is repeated for better readability. If set to 0, the month header will not be repeated.';
$LANG['calopt_satBusi'] = 'Saturday is a Business Day';
$LANG['calopt_satBusi_comment'] = 'By default, Saturday and Sunday are weekend days and displayed accordingly in the calendar. Check this option if you want to make Saturday a business day.';
$LANG['calopt_showAvatars'] = 'Show Avatars';
$LANG['calopt_showAvatars_comment'] = 'Checking this option will show a user avatar pop-up when moving the mouse over the user avatar icon.';
$LANG['calopt_showMonths'] = 'Show Multiple Months';
$LANG['calopt_showMonths_comment'] = 'Enter the number of months to show on the calendar page, maximum 12.<br><i>Note: A user can override this value in his settings, which has priority over the default value.</i>';
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
 holiday differences if you manage users from different regions. Note, that this might be a bit confusing depending on the amount of users and regions. Check it out and pick your choice.';
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
$LANG['calopt_summaryAbsenceTextColor'] = 'Absence Text Color';
$LANG['calopt_summaryAbsenceTextColor_comment'] = 'Here you can set the color for the absence counts in the summary section. Leave the field empty for the default color.';
$LANG['calopt_summaryPresenceTextColor'] = 'Presence Text Color';
$LANG['calopt_summaryPresenceTextColor_comment'] = 'Here you can set the color for the presence counts in the summary section. Leave the field empty for the default color.';
$LANG['calopt_sunBusi'] = 'Sunday is a Business Day';
$LANG['calopt_sunBusi_comment'] = 'By default, Saturday and Sunday are weekend days and displayed accordingly in the calendar.
      Check this option if you want to make Sunday a business day.';
$LANG['calopt_supportMobile'] = 'Support Mobile Devices';
$LANG['calopt_supportMobile_comment'] = 'With this switch on, TeamCal Neo will prepare the calendar tables (View and Edit) for a specific screen width so that no horizontal scrolling is necessary.
      The user can select his screen width.<br>
      Switch this off if the calendar is only viewed on full size computer screens (greater than 1024 pixels in width). The calendar will still be displayed then but horizontal scrolling will be necessary.';
$LANG['calopt_symbolAsIcon'] = 'Absence Type Character ID as Icon';
$LANG['calopt_symbolAsIcon_comment'] = 'With this option the character ID will be used in the calendar display instead of it\'s icon.';
$LANG['calopt_takeover'] = 'Enable Absence Take-over';
$LANG['calopt_takeover_comment'] = 'With this option enabled, the logged in user can take over absences from other users if he/she can edit the corresponding calendar. Take-over absences are NOT validated
      against any rules. They are removed from the other user and set for the logged in user. Note, that you have to enable each absence type for take-over as well.';
$LANG['calopt_todayBorderColor'] = 'Today Border Color';
$LANG['calopt_todayBorderColor_comment'] = 'Specifies the color in hexadecimal of the left and right border of the today column.';
$LANG['calopt_todayBorderSize'] = 'Today Border Size';
$LANG['calopt_todayBorderSize_comment'] = 'Specifies the size (thickness) in pixels of the left and right border of the today column.';
$LANG['calopt_trustedRoles'] = 'Trusted Roles';
$LANG['calopt_trustedRoles_comment'] = 'Select the roles that can view confidential absences and daynotes.<br>
      <i>Note: You can exclude the role "Administrator" here but the user "admin" functions as a superuser and can always see all data.</i>';
$LANG['calopt_usersPerPage'] = 'Number of users per page';
$LANG['calopt_usersPerPage_comment'] = 'If you maintain a large amount of users in TeamCal Neo you might want to use paging in the calendar display.
      Indicate how much users you want to display on each page. A value of 0 will disable paging. In case you chose paging, there will be paging
      buttons at the bottom of each page.';
