<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: Declination
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
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
$LANG['decl_title'] = 'Declination Rules';
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
$LANG['decl_applyto_comment'] = 'Select whether the declination rules shall apply to regular users only or to managers and directors as well. Declination rules do not apply to administrators.';
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
$LANG['decl_beforeoption_1day'] = 'Before 1 day ago';
$LANG['decl_beforeoption_1week'] = 'Before 1 week ago';
$LANG['decl_beforeoption_1month'] = 'Before 1 month ago';
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
