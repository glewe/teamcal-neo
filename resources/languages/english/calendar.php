<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: Calendar
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
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
$LANG['cal_selWidth_comment'] = 'Select the width of your screen in pixels so the calendar table can adjust to it. If your width is not in the list, select the next higher one.
      <br>It looks like you are currently using a screen with a width of <span id="currentwidth"></span> pixels. Reload the page to check this dialog again to confirm.';
$LANG['cal_switchFullmonthView'] = 'Switch to full month view';
$LANG['cal_switchSplitmonthView'] = 'Switch to split month view';
$LANG['cal_summary'] = 'Summary';
$LANG['cal_businessDays'] = 'Business Days in Month';
$LANG['cal_caption_weeknumber'] = 'Week';
$LANG['cal_caption_name'] = 'Name';
$LANG['cal_img_alt_edit_month'] = 'Edit holidays for this month...';
$LANG['cal_img_alt_edit_cal'] = 'Edit calendar for this person...';
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
$LANG['caledit_alert_update_group_cleared'] = 'The group absences were cleared for all users of the group.';
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
