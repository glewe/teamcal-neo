<?php

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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

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

  if (!$inputError) {

    //
    // Display
    //
    $C->save("todayBorderColor", sanitize($_POST['txt_todayBorderColor']));
    $C->save("todayBorderSize", intval($_POST['txt_todayBorderSize']));
    $C->save("pastDayColor", sanitize($_POST['txt_pastDayColor']));
    if (isset($_POST['chk_showWeekNumbers']) && $_POST['chk_showWeekNumbers']) {
      $C->save("showWeekNumbers", "1");
    } else {
      $C->save("showWeekNumbers", "0");
    }
    $C->save("repeatHeaderCount", intval($_POST['txt_repeatHeaderCount']));
    $C->save("usersPerPage", intval($_POST['txt_usersPerPage']));
    if (isset($_POST['chk_showAvatars']) && $_POST['chk_showAvatars']) {
      $C->save("showAvatars", "1");
    } else {
      $C->save("showAvatars", "0");
    }
    if (isset($_POST['chk_showRoleIcons']) && $_POST['chk_showRoleIcons']) {
      $C->save("showRoleIcons", "1");
    } else {
      $C->save("showRoleIcons", "0");
    }
    if (isset($_POST['chk_showTooltipCount']) && $_POST['chk_showTooltipCount']) {
      $C->save("showTooltipCount", "1");
    } else {
      $C->save("showTooltipCount", "0");
    }
    if (isset($_POST['chk_supportMobile']) && $_POST['chk_supportMobile']) {
      $C->save("supportMobile", "1");
    } else {
      $C->save("supportMobile", "0");
    }
    if (isset($_POST['chk_symbolAsIcon']) && $_POST['chk_symbolAsIcon']) {
      $C->save("symbolAsIcon", "1");
    } else {
      $C->save("symbolAsIcon", "0");
    }
    if ($_POST['sel_monitorAbsence']) {
      $C->save("monitorAbsence", $_POST['sel_monitorAbsence']);
    } else {
      $C->save("monitorAbsence", 0);
    }
    if (strlen($_POST['txt_calendarFontSize'])) {
      $C->save("calendarFontSize", intval($_POST['txt_calendarFontSize']));
    } else {
      $C->save("calendarFontSize", 100);
    }
    if (strlen($_POST['txt_showMonths'])) {
      $postValue = intval($_POST['txt_showMonths']);
      if ($postValue < 1) {
        $postValue = 1;
      } elseif ($postValue > 12) {
        $postValue = 12;
      }
      $C->save("showMonths", $postValue);
    } else {
      $C->save("showMonths", 1);
    }
    if (isset($_POST['chk_regionalHolidays']) && $_POST['chk_regionalHolidays']) {
      $C->save("regionalHolidays", "1");
    } else {
      $C->save("regionalHolidays", "0");
    }
    $C->save("regionalHolidaysColor", sanitize($_POST['txt_regionalHolidaysColor']));
    if (isset($_POST['chk_sortByOrderKey']) && $_POST['chk_sortByOrderKey']) {
      $C->save("sortByOrderKey", "1");
    } else {
      $C->save("sortByOrderKey", "0");
    }

    //
    // Filter
    //
    if (isset($_POST['chk_hideDaynotes']) && $_POST['chk_hideDaynotes']) {
      $C->save("hideDaynotes", "1");
    } else {
      $C->save("hideDaynotes", "0");
    }
    if (isset($_POST['chk_hideManagers']) && $_POST['chk_hideManagers']) {
      $C->save("hideManagers", "1");
    } else {
      $C->save("hideManagers", "0");
    }
    if (isset($_POST['chk_hideManagerOnlyAbsences'])) {
      $C->save("hideManagerOnlyAbsences", "1");
    } else {
      $C->save("hideManagerOnlyAbsences", "0");
    }
    if (isset($_POST['chk_showUserRegion']) && $_POST['chk_showUserRegion']) {
      $C->save("showUserRegion", "1");
    } else {
      $C->save("showUserRegion", "0");
    }
    if (isset($_POST['sel_trustedRoles'])) {
      foreach ($_POST['sel_trustedRoles'] as $role) {
        $arrTrustedRoles[] = $role;
      }
      $trustedRoles = implode(',', $arrTrustedRoles);
      $C->save("trustedRoles", $trustedRoles);
    }

    //
    // Options
    //
    if ($_POST['opt_firstDayOfWeek']) {
      $C->save("firstDayOfWeek", $_POST['opt_firstDayOfWeek']);
    }
    if (isset($_POST['chk_satBusi']) && $_POST['chk_satBusi']) {
      $C->save("satBusi", "1");
    } else {
      $C->save("satBusi", "0");
    }
    if (isset($_POST['chk_sunBusi']) && $_POST['chk_sunBusi']) {
      $C->save("sunBusi", "1");
    } else {
      $C->save("sunBusi", "0");
    }
    if ($_POST['sel_defregion']) {
      $C->save("defregion", $_POST['sel_defregion']);
    } else {
      $C->save("defregion", "default");
    }
    if (isset($_POST['chk_showRegionButton']) && $_POST['chk_showRegionButton']) {
      $C->save("showRegionButton", "1");
    } else {
      $C->save("showRegionButton", "0");
    }
    if ($_POST['opt_defgroupfilter']) {
      $C->save("defgroupfilter", $_POST['opt_defgroupfilter']);
    } else {
      $C->save("defgroupfilter", 'All');
    }
    if (isset($_POST['chk_currentYearOnly']) && $_POST['chk_currentYearOnly']) {
      $C->save("currentYearOnly", "1");
    } else {
      $C->save("currentYearOnly", "0");
    }
    if (isset($_POST['sel_currentYearRoles'])) {
      foreach ($_POST['sel_currentYearRoles'] as $role) {
        $arrCurrYearRoles[] = $role;
      }
      $currYearRoles = implode(',', $arrCurrYearRoles);
      $C->save("currYearRoles", $currYearRoles);
    }

    if (isset($_POST['chk_takeover']) && $_POST['chk_takeover']) {
      $C->save("takeover", "1");
    } else {
      $C->save("takeover", "0");
    }
    if (isset($_POST['chk_notificationsAllGroups']) && $_POST['chk_notificationsAllGroups']) {
      $C->save("notificationsAllGroups", "1");
    } else {
      $C->save("notificationsAllGroups", "0");
    }
    if (isset($_POST['chk_managerOnlyIncludesAdministrator']) && $_POST['chk_managerOnlyIncludesAdministrator']) {
      $C->save("managerOnlyIncludesAdministrator", "1");
    } else {
      $C->save("managerOnlyIncludesAdministrator", "0");
    }

    //
    // Statistics
    //
    if ($_POST['sel_statsDefaultColorAbsences']) {
      $C->save("statsDefaultColorAbsences", $_POST['sel_statsDefaultColorAbsences']);
    } else {
      $C->save("statsDefaultColorAbsences", "red");
    }
    if ($_POST['sel_statsDefaultColorPresences']) {
      $C->save("statsDefaultColorPresences", $_POST['sel_statsDefaultColorPresences']);
    } else {
      $C->save("statsDefaultColorPresences", "green");
    }
    if ($_POST['sel_statsDefaultColorAbsencetype']) {
      $C->save("statsDefaultColorAbsencetype", $_POST['sel_statsDefaultColorAbsencetype']);
    } else {
      $C->save("statsDefaultColorAbsencetype", "cyan");
    }
    if ($_POST['sel_statsDefaultColorRemainder']) {
      $C->save("statsDefaultColorRemainder", $_POST['sel_statsDefaultColorRemainder']);
    } else {
      $C->save("statsDefaultColorRemainder", "orange");
    }

    //
    // Summary
    //
    if (isset($_POST['chk_includeSummary']) && $_POST['chk_includeSummary']) {
      $C->save("includeSummary", "1");
    } else {
      $C->save("includeSummary", "0");
    }
    if (isset($_POST['chk_showSummary']) && $_POST['chk_showSummary']) {
      $C->save("showSummary", "1");
    } else {
      $C->save("showSummary", "0");
    }
    $C->save("summaryAbsenceTextColor", sanitize($_POST['txt_summaryAbsenceTextColor']));
    $C->save("summaryPresenceTextColor", sanitize($_POST['txt_summaryPresenceTextColor']));

    //
    // Renew CSRF token after successful form processing
    //
    if (isset($_SESSION)) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

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
    // Renew CSRF token after successful form processing
    //
    if (isset($_SESSION)) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

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
}

// ========================================================================
// Prepare data for the view
//
//
// Display
//
$absences = $A->getAll();
$caloptData['absenceList'][] = array('val' => 0, 'name' => $LANG['none'], 'selected' => (!$C->read("monitorAbsence")) ? true : false);
foreach ($absences as $abs) {
  $caloptData['absenceList'][] = array('val' => $abs['id'], 'name' => $abs['name'], 'selected' => ($C->read("monitorAbsence") == $abs['id']) ? true : false);
}
$caloptData['display'] = array(
  array('label' => $LANG['calopt_todayBorderColor'], 'prefix' => 'calopt', 'name' => 'todayBorderColor', 'type' => 'color', 'value' => $C->read("todayBorderColor"), 'maxlength' => '6'),
  array('label' => $LANG['calopt_todayBorderSize'], 'prefix' => 'calopt', 'name' => 'todayBorderSize', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("todayBorderSize"), 'maxlength' => '2'),
  array('label' => $LANG['calopt_pastDayColor'], 'prefix' => 'calopt', 'name' => 'pastDayColor', 'type' => 'color', 'value' => $C->read("pastDayColor"), 'maxlength' => '6'),
  array('label' => $LANG['calopt_showWeekNumbers'], 'prefix' => 'calopt', 'name' => 'showWeekNumbers', 'type' => 'check', 'values' => '', 'value' => $C->read("showWeekNumbers")),
  array('label' => $LANG['calopt_repeatHeaderCount'], 'prefix' => 'calopt', 'name' => 'repeatHeaderCount', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("repeatHeaderCount"), 'maxlength' => '4'),
  array('label' => $LANG['calopt_usersPerPage'], 'prefix' => 'calopt', 'name' => 'usersPerPage', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("usersPerPage"), 'maxlength' => '4'),
  array('label' => $LANG['calopt_showAvatars'], 'prefix' => 'calopt', 'name' => 'showAvatars', 'type' => 'check', 'values' => '', 'value' => $C->read("showAvatars")),
  array('label' => $LANG['calopt_showRoleIcons'], 'prefix' => 'calopt', 'name' => 'showRoleIcons', 'type' => 'check', 'values' => '', 'value' => $C->read("showRoleIcons")),
  array('label' => $LANG['calopt_showTooltipCount'], 'prefix' => 'calopt', 'name' => 'showTooltipCount', 'type' => 'check', 'values' => '', 'value' => $C->read("showTooltipCount")),
  array('label' => $LANG['calopt_supportMobile'], 'prefix' => 'calopt', 'name' => 'supportMobile', 'type' => 'check', 'values' => '', 'value' => $C->read("supportMobile")),
  array('label' => $LANG['calopt_symbolAsIcon'], 'prefix' => 'calopt', 'name' => 'symbolAsIcon', 'type' => 'check', 'values' => '', 'value' => $C->read("symbolAsIcon")),
  array('label' => $LANG['calopt_monitorAbsence'], 'prefix' => 'calopt', 'name' => 'monitorAbsence', 'type' => 'list', 'values' => $caloptData['absenceList']),
  array('label' => $LANG['calopt_calendarFontSize'], 'prefix' => 'calopt', 'name' => 'calendarFontSize', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("calendarFontSize"), 'maxlength' => '3'),
  array('label' => $LANG['calopt_showMonths'], 'prefix' => 'calopt', 'name' => 'showMonths', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("showMonths"), 'maxlength' => '2'),
  array('label' => $LANG['calopt_regionalHolidays'], 'prefix' => 'calopt', 'name' => 'regionalHolidays', 'type' => 'check', 'values' => '', 'value' => $C->read("regionalHolidays")),
  array('label' => $LANG['calopt_regionalHolidaysColor'], 'prefix' => 'calopt', 'name' => 'regionalHolidaysColor', 'type' => 'color', 'value' => $C->read("regionalHolidaysColor"), 'maxlength' => '6'),
  array('label' => $LANG['calopt_sortByOrderKey'], 'prefix' => 'calopt', 'name' => 'sortByOrderKey', 'type' => 'check', 'values' => '', 'value' => $C->read("sortByOrderKey")),
);

//
// Filter
//
$roles = $RO->getAll();
$arrTrustedRoles = explode(',', $C->read("trustedRoles"));
foreach ($roles as $role) {
  $caloptData['roleList'][] = array('val' => $role['id'], 'name' => $role['name'], 'selected' => (in_array($role['id'], $arrTrustedRoles)) ? true : false);
}
$caloptData['filter'] = array(
  array('label' => $LANG['calopt_hideManagers'], 'prefix' => 'calopt', 'name' => 'hideManagers', 'type' => 'check', 'values' => '', 'value' => $C->read("hideManagers")),
  array('label' => $LANG['calopt_hideDaynotes'], 'prefix' => 'calopt', 'name' => 'hideDaynotes', 'type' => 'check', 'values' => '', 'value' => $C->read("hideDaynotes")),
  array('label' => $LANG['calopt_hideManagerOnlyAbsences'], 'prefix' => 'calopt', 'name' => 'hideManagerOnlyAbsences', 'type' => 'check', 'values' => '', 'value' => $C->read("hideManagerOnlyAbsences")),
  array('label' => $LANG['calopt_showUserRegion'], 'prefix' => 'calopt', 'name' => 'showUserRegion', 'type' => 'check', 'values' => '', 'value' => $C->read("showUserRegion")),
  array('label' => $LANG['calopt_trustedRoles'], 'prefix' => 'calopt', 'name' => 'trustedRoles', 'type' => 'listmulti', 'values' => $caloptData['roleList']),
);

//
// Options
//
$regions = $R->getAllNames();
foreach ($regions as $region) {
  $caloptData['regionList'][] = array('val' => $region, 'name' => $region, 'selected' => ($C->read("defregion") == $region) ? true : false);
}
$arrCurrYearRoles = explode(',', $C->read("currYearRoles"));
foreach ($roles as $role) {
  $caloptData['roleList2'][] = array('val' => $role['id'], 'name' => $role['name'], 'selected' => (in_array($role['id'], $arrCurrYearRoles)) ? true : false);
}
$caloptData['options'] = array(
  array('label' => $LANG['calopt_firstDayOfWeek'], 'prefix' => 'calopt', 'name' => 'firstDayOfWeek', 'type' => 'radio', 'values' => array('1', '7'), 'value' => $C->read("firstDayOfWeek")),
  array('label' => $LANG['calopt_satBusi'], 'prefix' => 'calopt', 'name' => 'satBusi', 'type' => 'check', 'values' => '', 'value' => $C->read("satBusi")),
  array('label' => $LANG['calopt_sunBusi'], 'prefix' => 'calopt', 'name' => 'sunBusi', 'type' => 'check', 'values' => '', 'value' => $C->read("sunBusi")),
  array('label' => $LANG['calopt_defregion'], 'prefix' => 'calopt', 'name' => 'defregion', 'type' => 'list', 'values' => $caloptData['regionList']),
  array('label' => $LANG['calopt_showRegionButton'], 'prefix' => 'calopt', 'name' => 'showRegionButton', 'type' => 'check', 'values' => '', 'value' => $C->read("showRegionButton")),
  array('label' => $LANG['calopt_defgroupfilter'], 'prefix' => 'calopt', 'name' => 'defgroupfilter', 'type' => 'radio', 'values' => array('all', 'allbygroup'), 'value' => $C->read("defgroupfilter")),
  array('label' => $LANG['calopt_currentYearOnly'], 'prefix' => 'calopt', 'name' => 'currentYearOnly', 'type' => 'check', 'values' => '', 'value' => $C->read("currentYearOnly")),
  array('label' => $LANG['calopt_currentYearRoles'], 'prefix' => 'calopt', 'name' => 'currentYearRoles', 'type' => 'listmulti', 'values' => $caloptData['roleList2']),
  array('label' => $LANG['calopt_takeover'], 'prefix' => 'calopt', 'name' => 'takeover', 'type' => 'check', 'values' => '', 'value' => $C->read("takeover")),
  array('label' => $LANG['calopt_notificationsAllGroups'], 'prefix' => 'calopt', 'name' => 'notificationsAllGroups', 'type' => 'check', 'values' => '', 'value' => $C->read("notificationsAllGroups")),
  array('label' => $LANG['calopt_managerOnlyIncludesAdministrator'], 'prefix' => 'calopt', 'name' => 'managerOnlyIncludesAdministrator', 'type' => 'check', 'values' => '', 'value' => $C->read("managerOnlyIncludesAdministrator")),
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
    $statsColorArray[$statsPage][] = array('val' => $hex, 'name' => $LANG[$color], 'selected' => ($C->read("statsDefaultColor" . $statsPage) == $hex) ? true : false);
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
  array('label' => $LANG['calopt_includeSummary'], 'prefix' => 'calopt', 'name' => 'includeSummary', 'type' => 'check', 'values' => '', 'value' => $C->read("includeSummary")),
  array('label' => $LANG['calopt_showSummary'], 'prefix' => 'calopt', 'name' => 'showSummary', 'type' => 'check', 'values' => '', 'value' => $C->read("showSummary")),
  array('label' => $LANG['calopt_summaryAbsenceTextColor'], 'prefix' => 'calopt', 'name' => 'summaryAbsenceTextColor', 'type' => 'color', 'value' => $C->read("summaryAbsenceTextColor"), 'maxlength' => '6'),
  array('label' => $LANG['calopt_summaryPresenceTextColor'], 'prefix' => 'calopt', 'name' => 'summaryPresenceTextColor', 'type' => 'color', 'value' => $C->read("summaryPresenceTextColor"), 'maxlength' => '6'),
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
        'required' => isset($field['required']) ? $field['required'] : false
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
