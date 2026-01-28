<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Calendar Options Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
global $allConfig;
global $C;
global $CONF;
global $controller;
global $LANG;
global $LOG;
global $RO;
global $R;
global $A;
global $UL;

// ========================================================================
// Check if allowed
//
if (!isAllowed($CONF['controllers'][$controller]->permission)) {
  $alertData['type'] = 'warning';
  $alertData['title'] = $LANG['alert_alert_title'];
  $alertData['subject'] = $LANG['alert_not_allowed_subject'];
  $alertData['text'] = $LANG['alert_not_allowed_text'];
  $alertData['help'] = $LANG['alert_not_allowed_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//=============================================================================
// Check license
//
$date = new DateTime();
$weekday = $date->format('N');
if ($weekday == rand(1, 7)) {
  $alertData = array();
  $showAlert = false;
  $licExpiryWarning = $C->read('licExpiryWarning');
  $LIC = new License();
  $LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);
}

// ========================================================================
// Load controller stuff
//

// ========================================================================
// Initialize variables
//
$arrTrustedRoles = array();
$arrCurrYearRoles = array();

// ========================================================================
// Process form
//
//
// ,-------,
// | Apply |
// '-------'
//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && isset($_POST['btn_apply'])) {

  //
  // Sanitize input
  //
  $_POST = sanitize($_POST);

  //
  // CSRF token check
  //
  if (!isset($_POST['csrf_token']) || (isset($_POST['csrf_token']) && $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
    $alertData['type'] = 'warning';
    $alertData['title'] = $LANG['alert_alert_title'];
    $alertData['subject'] = $LANG['alert_csrf_invalid_subject'];
    $alertData['text'] = $LANG['alert_csrf_invalid_text'];
    $alertData['help'] = $LANG['alert_csrf_invalid_help'];
    require_once WEBSITE_ROOT . '/controller/alert.php';
    die();
  }

  //
  // Form validation
  //
  $inputError = false;

  //
  // Validate input data. If something is wrong or missing, set $inputError = true
  //
  if (!formInputValid('txt_pastDayColor', 'hex_color')) {
    $inputError = true;
  }
  if (!formInputValid('txt_regionalHolidaysColor', 'hex_color')) {
    $inputError = true;
  }
  if (!formInputValid('txt_todayBorderColor', 'hex_color')) {
    $inputError = true;
  }
  if (!formInputValid('txt_summaryAbsenceTextColor', 'hex_color')) {
    $inputError = true;
  }
  if (!formInputValid('txt_summaryPresenceTextColor', 'hex_color')) {
    $inputError = true;
  }

  if (!$inputError) {
    $newConfig = [];

    //
    // Display
    //
    $newConfig["todayBorderColor"] = ltrim(sanitize($_POST['txt_todayBorderColor']), '#');
    $newConfig["todayBorderSize"] = intval($_POST['txt_todayBorderSize']);
    $newConfig["pastDayColor"] = ltrim(sanitize($_POST['txt_pastDayColor']), '#');
    if (isset($_POST['chk_showWeekNumbers']) && $_POST['chk_showWeekNumbers']) {
      $newConfig["showWeekNumbers"] = "1";
    } else {
      $newConfig["showWeekNumbers"] = "0";
    }
    $newConfig["repeatHeaderCount"] = intval($_POST['txt_repeatHeaderCount']);
    $newConfig["usersPerPage"] = intval($_POST['txt_usersPerPage']);
    if (isset($_POST['chk_showAvatars']) && $_POST['chk_showAvatars']) {
      $newConfig["showAvatars"] = "1";
    } else {
      $newConfig["showAvatars"] = "0";
    }
    if (isset($_POST['chk_showRoleIcons']) && $_POST['chk_showRoleIcons']) {
      $newConfig["showRoleIcons"] = "1";
    } else {
      $newConfig["showRoleIcons"] = "0";
    }
    if (isset($_POST['chk_showTooltipCount']) && $_POST['chk_showTooltipCount']) {
      $newConfig["showTooltipCount"] = "1";
    } else {
      $newConfig["showTooltipCount"] = "0";
    }
    if (isset($_POST['chk_supportMobile']) && $_POST['chk_supportMobile']) {
      $newConfig["supportMobile"] = "1";
    } else {
      $newConfig["supportMobile"] = "0";
    }
    if (isset($_POST['chk_symbolAsIcon']) && $_POST['chk_symbolAsIcon']) {
      $newConfig["symbolAsIcon"] = "1";
    } else {
      $newConfig["symbolAsIcon"] = "0";
    }
    if (isset($_POST['sel_monitorAbsence'])) {
      if (is_array($_POST['sel_monitorAbsence'])) {
        $newConfig["monitorAbsence"] = implode(',', $_POST['sel_monitorAbsence']);
      } else {
        $newConfig["monitorAbsence"] = $_POST['sel_monitorAbsence'];
      }
    } else {
      $newConfig["monitorAbsence"] = 0;
    }
    if (strlen($_POST['txt_calendarFontSize'])) {
      $newConfig["calendarFontSize"] = intval($_POST['txt_calendarFontSize']);
    } else {
      $newConfig["calendarFontSize"] = 100;
    }
    if (strlen($_POST['txt_showMonths'])) {
      $postValue = intval($_POST['txt_showMonths']);
      if ($postValue < 1) {
        $postValue = 1;
      } elseif ($postValue > 12) {
        $postValue = 12;
      }
      $newConfig["showMonths"] = $postValue;
    } else {
      $newConfig["showMonths"] = 1;
    }
    if (isset($_POST['chk_regionalHolidays']) && $_POST['chk_regionalHolidays']) {
      $newConfig["regionalHolidays"] = "1";
    } else {
      $newConfig["regionalHolidays"] = "0";
    }
    $newConfig["regionalHolidaysColor"] = ltrim(sanitize($_POST['txt_regionalHolidaysColor']), '#');
    if (isset($_POST['chk_sortByOrderKey']) && $_POST['chk_sortByOrderKey']) {
      $newConfig["sortByOrderKey"] = "1";
    } else {
      $newConfig["sortByOrderKey"] = "0";
    }

    //
    // Filter
    //
    if (isset($_POST['chk_hideDaynotes']) && $_POST['chk_hideDaynotes']) {
      $newConfig["hideDaynotes"] = "1";
    } else {
      $newConfig["hideDaynotes"] = "0";
    }
    if (isset($_POST['chk_hideManagers']) && $_POST['chk_hideManagers']) {
      $newConfig["hideManagers"] = "1";
    } else {
      $newConfig["hideManagers"] = "0";
    }
    if (isset($_POST['chk_hideManagerOnlyAbsences'])) {
      $newConfig["hideManagerOnlyAbsences"] = "1";
    } else {
      $newConfig["hideManagerOnlyAbsences"] = "0";
    }
    if (isset($_POST['chk_showUserRegion']) && $_POST['chk_showUserRegion']) {
      $newConfig["showUserRegion"] = "1";
    } else {
      $newConfig["showUserRegion"] = "0";
    }
    if (isset($_POST['sel_trustedRoles'])) {
      foreach ($_POST['sel_trustedRoles'] as $role) {
        $arrTrustedRoles[] = $role;
      }
      $trustedRoles = implode(',', $arrTrustedRoles);
      $newConfig["trustedRoles"] = $trustedRoles;
    }

    //
    // Options
    //
    if ($_POST['opt_firstDayOfWeek']) {
      $newConfig["firstDayOfWeek"] = $_POST['opt_firstDayOfWeek'];
    }
    if (isset($_POST['chk_satBusi']) && $_POST['chk_satBusi']) {
      $newConfig["satBusi"] = "1";
    } else {
      $newConfig["satBusi"] = "0";
    }
    if (isset($_POST['chk_sunBusi']) && $_POST['chk_sunBusi']) {
      $newConfig["sunBusi"] = "1";
    } else {
      $newConfig["sunBusi"] = "0";
    }
    if ($_POST['sel_defregion']) {
      $newConfig["defregion"] = $_POST['sel_defregion'];
    } else {
      $newConfig["defregion"] = "default";
    }
    if (isset($_POST['chk_showRegionButton']) && $_POST['chk_showRegionButton']) {
      $newConfig["showRegionButton"] = "1";
    } else {
      $newConfig["showRegionButton"] = "0";
    }
    if ($_POST['opt_defgroupfilter']) {
      $newConfig["defgroupfilter"] = $_POST['opt_defgroupfilter'];
    } else {
      $newConfig["defgroupfilter"] = 'All';
    }
    if (isset($_POST['chk_currentYearOnly']) && $_POST['chk_currentYearOnly']) {
      $newConfig["currentYearOnly"] = "1";
    } else {
      $newConfig["currentYearOnly"] = "0";
    }
    if (isset($_POST['sel_currentYearRoles'])) {
      foreach ($_POST['sel_currentYearRoles'] as $role) {
        $arrCurrYearRoles[] = $role;
      }
      $currYearRoles = implode(',', $arrCurrYearRoles);
      $newConfig["currYearRoles"] = $currYearRoles;
    }

    if (isset($_POST['chk_takeover']) && $_POST['chk_takeover']) {
      $newConfig["takeover"] = "1";
    } else {
      $newConfig["takeover"] = "0";
    }
    if (isset($_POST['chk_notificationsAllGroups']) && $_POST['chk_notificationsAllGroups']) {
      $newConfig["notificationsAllGroups"] = "1";
    } else {
      $newConfig["notificationsAllGroups"] = "0";
    }
    if (isset($_POST['chk_managerOnlyIncludesAdministrator']) && $_POST['chk_managerOnlyIncludesAdministrator']) {
      $newConfig["managerOnlyIncludesAdministrator"] = "1";
    } else {
      $newConfig["managerOnlyIncludesAdministrator"] = "0";
    }

    //
    // Statistics
    //
    if ($_POST['sel_statsDefaultColorAbsences']) {
      $newConfig["statsDefaultColorAbsences"] = $_POST['sel_statsDefaultColorAbsences'];
    } else {
      $newConfig["statsDefaultColorAbsences"] = "red";
    }
    if ($_POST['sel_statsDefaultColorPresences']) {
      $newConfig["statsDefaultColorPresences"] = $_POST['sel_statsDefaultColorPresences'];
    } else {
      $newConfig["statsDefaultColorPresences"] = "green";
    }
    if ($_POST['sel_statsDefaultColorAbsencetype']) {
      $newConfig["statsDefaultColorAbsencetype"] = $_POST['sel_statsDefaultColorAbsencetype'];
    } else {
      $newConfig["statsDefaultColorAbsencetype"] = "cyan";
    }
    if ($_POST['sel_statsDefaultColorRemainder']) {
      $newConfig["statsDefaultColorRemainder"] = $_POST['sel_statsDefaultColorRemainder'];
    } else {
      $newConfig["statsDefaultColorRemainder"] = "orange";
    }

    //
    // Summary
    //
    if (isset($_POST['chk_includeSummary']) && $_POST['chk_includeSummary']) {
      $newConfig["includeSummary"] = "1";
    } else {
      $newConfig["includeSummary"] = "0";
    }
    if (isset($_POST['chk_showSummary']) && $_POST['chk_showSummary']) {
      $newConfig["showSummary"] = "1";
    } else {
      $newConfig["showSummary"] = "0";
    }
    $newConfig["summaryAbsenceTextColor"] = ltrim(sanitize($_POST['txt_summaryAbsenceTextColor']), '#');
    $newConfig["summaryPresenceTextColor"] = ltrim(sanitize($_POST['txt_summaryPresenceTextColor']), '#');

    //
    // Save all config values in batch
    //
    $C->saveBatch($newConfig);
    $allConfig = $C->readAll();

    //
    // Log this event
    //
    $LOG->logEvent("logCalendarOptions", $UL->username, "log_calopt");

    //
    // Success message
    //
    $showAlert = true;
    $alertData['type'] = 'success';
    $alertData['title'] = $LANG['alert_success_title'];
    $alertData['subject'] = $LANG['calopt_title'];
    $alertData['text'] = $LANG['calopt_alert_edit_success'];
    $alertData['help'] = '';
  } else {

    //
    // Error message
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['calopt_title'];
    $alertData['text'] = $LANG['calopt_alert_failed'];
    $alertData['help'] = '';
  }
  //
  // Renew CSRF token after form processing
  //
  if (isset($_SESSION)) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
}

// ========================================================================
// Prepare data for the view
//
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];

//
// Display
//
$absences = $A->getAll();
$monAbsArr = explode(',', $allConfig['monitorAbsence']);
$caloptData['absenceList'][] = array('val' => 0, 'name' => $LANG['none'], 'selected' => (in_array('0', $monAbsArr) || empty($allConfig['monitorAbsence'])) ? true : false);
foreach ($absences as $abs) {
  $caloptData['absenceList'][] = array('val' => $abs['id'], 'name' => $abs['name'], 'selected' => (in_array($abs['id'], $monAbsArr)) ? true : false);
}
$caloptData['display'] = array(
  array('label' => $LANG['calopt_todayBorderColor'], 'prefix' => 'calopt', 'name' => 'todayBorderColor', 'type' => 'coloris', 'value' => (!empty($allConfig['todayBorderColor']) ? '#' . $allConfig['todayBorderColor'] : ''), 'maxlength' => '6', 'error' => (isset($inputAlert['todayBorderColor']) ? $inputAlert['todayBorderColor'] : '')),
  array('label' => $LANG['calopt_todayBorderSize'], 'prefix' => 'calopt', 'name' => 'todayBorderSize', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig['todayBorderSize'], 'maxlength' => '2'),
  array('label' => $LANG['calopt_pastDayColor'], 'prefix' => 'calopt', 'name' => 'pastDayColor', 'type' => 'coloris', 'value' => (!empty($allConfig['pastDayColor']) ? '#' . $allConfig['pastDayColor'] : ''), 'maxlength' => '6', 'error' => (isset($inputAlert['pastDayColor']) ? $inputAlert['pastDayColor'] : '')),
  array('label' => $LANG['calopt_showWeekNumbers'], 'prefix' => 'calopt', 'name' => 'showWeekNumbers', 'type' => 'check', 'values' => '', 'value' => $allConfig['showWeekNumbers']),
  array('label' => $LANG['calopt_repeatHeaderCount'], 'prefix' => 'calopt', 'name' => 'repeatHeaderCount', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig['repeatHeaderCount'], 'maxlength' => '4'),
  array('label' => $LANG['calopt_usersPerPage'], 'prefix' => 'calopt', 'name' => 'usersPerPage', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig['usersPerPage'], 'maxlength' => '4'),
  array('label' => $LANG['calopt_showAvatars'], 'prefix' => 'calopt', 'name' => 'showAvatars', 'type' => 'check', 'values' => '', 'value' => $allConfig['showAvatars']),
  array('label' => $LANG['calopt_showRoleIcons'], 'prefix' => 'calopt', 'name' => 'showRoleIcons', 'type' => 'check', 'values' => '', 'value' => $allConfig['showRoleIcons']),
  array('label' => $LANG['calopt_showTooltipCount'], 'prefix' => 'calopt', 'name' => 'showTooltipCount', 'type' => 'check', 'values' => '', 'value' => $allConfig['showTooltipCount']),
  array('label' => $LANG['calopt_supportMobile'], 'prefix' => 'calopt', 'name' => 'supportMobile', 'type' => 'check', 'values' => '', 'value' => $allConfig['supportMobile']),
  array('label' => $LANG['calopt_symbolAsIcon'], 'prefix' => 'calopt', 'name' => 'symbolAsIcon', 'type' => 'check', 'values' => '', 'value' => $allConfig['symbolAsIcon']),
  array('label' => $LANG['calopt_monitorAbsence'], 'prefix' => 'calopt', 'name' => 'monitorAbsence', 'type' => 'listmulti', 'values' => $caloptData['absenceList']),
  array('label' => $LANG['calopt_calendarFontSize'], 'prefix' => 'calopt', 'name' => 'calendarFontSize', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig['calendarFontSize'], 'maxlength' => '3'),
  array('label' => $LANG['calopt_showMonths'], 'prefix' => 'calopt', 'name' => 'showMonths', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig['showMonths'], 'maxlength' => '2'),
  array('label' => $LANG['calopt_regionalHolidays'], 'prefix' => 'calopt', 'name' => 'regionalHolidays', 'type' => 'check', 'values' => '', 'value' => $allConfig['regionalHolidays']),
  array('label' => $LANG['calopt_regionalHolidaysColor'], 'prefix' => 'calopt', 'name' => 'regionalHolidaysColor', 'type' => 'coloris', 'value' => (!empty($allConfig['regionalHolidaysColor']) ? '#' . $allConfig['regionalHolidaysColor'] : ''), 'maxlength' => '6', 'error' => (isset($inputAlert['regionalHolidaysColor']) ? $inputAlert['regionalHolidaysColor'] : '')),
  array('label' => $LANG['calopt_sortByOrderKey'], 'prefix' => 'calopt', 'name' => 'sortByOrderKey', 'type' => 'check', 'values' => '', 'value' => $allConfig['sortByOrderKey']),
);

//
// Filter
//
$roles = $RO->getAll();
$arrTrustedRoles = explode(',', $allConfig['trustedRoles']);
foreach ($roles as $role) {
  $caloptData['roleList'][] = array('val' => $role['id'], 'name' => $role['name'], 'selected' => (in_array($role['id'], $arrTrustedRoles)) ? true : false);
}
$caloptData['filter'] = array(
  array('label' => $LANG['calopt_hideManagers'], 'prefix' => 'calopt', 'name' => 'hideManagers', 'type' => 'check', 'values' => '', 'value' => $allConfig['hideManagers']),
  array('label' => $LANG['calopt_hideDaynotes'], 'prefix' => 'calopt', 'name' => 'hideDaynotes', 'type' => 'check', 'values' => '', 'value' => $allConfig['hideDaynotes']),
  array('label' => $LANG['calopt_hideManagerOnlyAbsences'], 'prefix' => 'calopt', 'name' => 'hideManagerOnlyAbsences', 'type' => 'check', 'values' => '', 'value' => $allConfig['hideManagerOnlyAbsences']),
  array('label' => $LANG['calopt_showUserRegion'], 'prefix' => 'calopt', 'name' => 'showUserRegion', 'type' => 'check', 'values' => '', 'value' => $allConfig['showUserRegion']),
  array('label' => $LANG['calopt_trustedRoles'], 'prefix' => 'calopt', 'name' => 'trustedRoles', 'type' => 'listmulti', 'values' => $caloptData['roleList']),
);

//
// Options
//
$regions = $R->getAllNames();
foreach ($regions as $region) {
  $caloptData['regionList'][] = array('val' => $region, 'name' => $region, 'selected' => ($allConfig['defregion'] == $region) ? true : false);
}
$arrCurrYearRoles = explode(',', $allConfig['currYearRoles']);
foreach ($roles as $role) {
  $caloptData['roleList2'][] = array('val' => $role['id'], 'name' => $role['name'], 'selected' => (in_array($role['id'], $arrCurrYearRoles)) ? true : false);
}
$caloptData['options'] = array(
  array('label' => $LANG['calopt_firstDayOfWeek'], 'prefix' => 'calopt', 'name' => 'firstDayOfWeek', 'type' => 'radio', 'values' => array('1', '7'), 'value' => $allConfig['firstDayOfWeek']),
  array('label' => $LANG['calopt_satBusi'], 'prefix' => 'calopt', 'name' => 'satBusi', 'type' => 'check', 'values' => '', 'value' => $allConfig['satBusi']),
  array('label' => $LANG['calopt_sunBusi'], 'prefix' => 'calopt', 'name' => 'sunBusi', 'type' => 'check', 'values' => '', 'value' => $allConfig['sunBusi']),
  array('label' => $LANG['calopt_defregion'], 'prefix' => 'calopt', 'name' => 'defregion', 'type' => 'list', 'values' => $caloptData['regionList']),
  array('label' => $LANG['calopt_showRegionButton'], 'prefix' => 'calopt', 'name' => 'showRegionButton', 'type' => 'check', 'values' => '', 'value' => $allConfig['showRegionButton']),
  array('label' => $LANG['calopt_defgroupfilter'], 'prefix' => 'calopt', 'name' => 'defgroupfilter', 'type' => 'radio', 'values' => array('all', 'allbygroup'), 'value' => $allConfig['defgroupfilter']),
  array('label' => $LANG['calopt_currentYearOnly'], 'prefix' => 'calopt', 'name' => 'currentYearOnly', 'type' => 'check', 'values' => '', 'value' => $allConfig['currentYearOnly']),
  array('label' => $LANG['calopt_currentYearRoles'], 'prefix' => 'calopt', 'name' => 'currentYearRoles', 'type' => 'listmulti', 'values' => $caloptData['roleList2']),
  array('label' => $LANG['calopt_takeover'], 'prefix' => 'calopt', 'name' => 'takeover', 'type' => 'check', 'values' => '', 'value' => $allConfig['takeover']),
  array('label' => $LANG['calopt_notificationsAllGroups'], 'prefix' => 'calopt', 'name' => 'notificationsAllGroups', 'type' => 'check', 'values' => '', 'value' => $allConfig['notificationsAllGroups']),
  array('label' => $LANG['calopt_managerOnlyIncludesAdministrator'], 'prefix' => 'calopt', 'name' => 'managerOnlyIncludesAdministrator', 'type' => 'check', 'values' => '', 'value' => $allConfig['managerOnlyIncludesAdministrator']),
);

//
// Statistics
//
$statsPages = array('Absences', 'Presences', 'Absencetype', 'Remainder');
$colors = array(
  'blue' => '#0000ff',
  'cyan' => '#00ffff',
  'green' => '#00d000',
  'grey' => '#808080',
  'magenta' => '#ff00ff',
  'orange' => '#ffa500',
  'purple' => '#800080',
  'red' => '#ff0000',
  'yellow' => '#ffff00',
);
foreach ($statsPages as $statsPage) {
  $statsColorArray[$statsPage] = array();
  foreach ($colors as $color => $hex) {
    $statsColorArray[$statsPage][] = array('val' => $hex, 'name' => $LANG[$color], 'selected' => ($allConfig['statsDefaultColor' . $statsPage] == $hex) ? true : false);
  }
}
$caloptData['stats'] = array(
  array('label' => $LANG['calopt_statsDefaultColorAbsences'], 'prefix' => 'calopt', 'name' => 'statsDefaultColorAbsences', 'type' => 'list', 'values' => $statsColorArray['Absences']),
  array('label' => $LANG['calopt_statsDefaultColorPresences'], 'prefix' => 'calopt', 'name' => 'statsDefaultColorPresences', 'type' => 'list', 'values' => $statsColorArray['Presences']),
  array('label' => $LANG['calopt_statsDefaultColorAbsencetype'], 'prefix' => 'calopt', 'name' => 'statsDefaultColorAbsencetype', 'type' => 'list', 'values' => $statsColorArray['Absencetype']),
  array('label' => $LANG['calopt_statsDefaultColorRemainder'], 'prefix' => 'calopt', 'name' => 'statsDefaultColorRemainder', 'type' => 'list', 'values' => $statsColorArray['Remainder']),
);

//
// Summary
//
$caloptData['summary'] = array(
  array('label' => $LANG['calopt_includeSummary'], 'prefix' => 'calopt', 'name' => 'includeSummary', 'type' => 'check', 'values' => '', 'value' => $allConfig['includeSummary']),
  array('label' => $LANG['calopt_showSummary'], 'prefix' => 'calopt', 'name' => 'showSummary', 'type' => 'check', 'values' => '', 'value' => $allConfig['showSummary']),
  array('label' => $LANG['calopt_summaryAbsenceTextColor'], 'prefix' => 'calopt', 'name' => 'summaryAbsenceTextColor', 'type' => 'coloris', 'value' => (!empty($allConfig['summaryAbsenceTextColor']) ? '#' . $allConfig['summaryAbsenceTextColor'] : ''), 'maxlength' => '7'),
  array('label' => $LANG['calopt_summaryPresenceTextColor'], 'prefix' => 'calopt', 'name' => 'summaryPresenceTextColor', 'type' => 'coloris', 'value' => (!empty($allConfig['summaryPresenceTextColor']) ? '#' . $allConfig['summaryPresenceTextColor'] : ''), 'maxlength' => '7'),
);

//
// Prepare all form fields for the view
//
$viewData['formFields'] = array();
$sections = ['display', 'filter', 'options', 'stats', 'summary'];
foreach ($sections as $section) {
  $viewData['formFields'][$section] = array();
  if (!empty($caloptData[$section])) {
    foreach ($caloptData[$section] as $field) {
      $viewData['formFields'][$section][] = array(
        'label' => $field['label'],
        'prefix' => $field['prefix'],
        'name' => $field['name'],
        'type' => $field['type'],
        'value' => isset($field['value']) ? $field['value'] : '',
        'maxlength' => isset($field['maxlength']) ? $field['maxlength'] : '',
        'placeholder' => isset($field['placeholder']) ? $field['placeholder'] : '',
        'options' => isset($field['options']) ? $field['options'] : [],
        'values' => (isset($field['values']) && is_array($field['values'])) ? $field['values'] : [],
        'help' => isset($field['help']) ? $field['help'] : '',
        'required' => isset($field['required']) ? $field['required'] : false,
        'error' => isset($field['error']) ? $field['error'] : '',
      );
    }
  }
}

// ========================================================================
//
// Show view
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
if (isset($viewData) && is_array($viewData)) {
  extract($viewData);
}
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
