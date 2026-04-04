<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: Absence
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
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
$LANG['absum_title'] = 'Absence Summary %1$s: %2$s';
$LANG['absum_modalYearTitle'] = 'Select the Year for the Summary';
$LANG['absum_unlimited'] = 'Unlimited';
$LANG['absum_year'] = 'Year';
$LANG['absum_year_comment'] = 'Select the year for this summary.';
$LANG['absum_absencetype'] = 'Absence Type';
$LANG['absum_contingent'] = 'Contingent';
$LANG['absum_contingent_tt'] = 'The Contingent is the result of the Allowance for this year plus the Carryover from last year. Note, that the Carryover value can also be negative.';
$LANG['absum_taken'] = 'Taken';
$LANG['absum_remainder'] = 'Remainder';
