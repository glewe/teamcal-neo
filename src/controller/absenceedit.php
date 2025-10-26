<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Absence Edit Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

global $AG;
global $C;
global $CONF;
global $controller;
global $G;
global $LANG;
global $LOG;
global $logLanguages;

//-----------------------------------------------------------------------------
// CHECK PERMISSION
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

//-----------------------------------------------------------------------------
// CHECK URL PARAMETERS
//
$AA = new Absences(); // for the absence type to be edited

if (isset($_GET['id'])) {
  $missingData = false;
  $id = sanitize($_GET['id']);
  if (!$AA->get($id)) {
    $missingData = true;
  }
} else {
  $missingData = true;
}

if ($missingData) {
  //
  // URL param fail
  //
  $alertData['type'] = 'danger';
  $alertData['title'] = $LANG['alert_danger_title'];
  $alertData['subject'] = $LANG['alert_no_data_subject'];
  $alertData['text'] = $LANG['alert_no_data_text'];
  $alertData['help'] = $LANG['alert_no_data_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//
// Load only required config keys
$allConfig = $C->readAll();
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
// $inputAlert = array();

//-----------------------------------------------------------------------------
// PROCESS FORM
//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

  //
  // Sanitize input
  //
  $_POST = sanitize($_POST);

  //
  // CSRF token check
  //
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $alertData['type'] = 'warning';
    $alertData['title'] = $LANG['alert_alert_title'];
    $alertData['subject'] = $LANG['alert_csrf_invalid_subject'];
    $alertData['text'] = $LANG['alert_csrf_invalid_text'];
    $alertData['help'] = $LANG['alert_csrf_invalid_help'];
    require_once WEBSITE_ROOT . '/controller/alert.php';
    die();
  }

  //
  // Load sanitized form info for the view
  //
  $viewData['name'] = $_POST['txt_name'];

  //
  // Form validation
  //
  $inputError = false;
  if (isset($_POST['btn_save'])) {
    if (!formInputValid('txt_name', 'required|alpha_numeric_dash_blank')) {
      $inputError = true;
    }
    if (!formInputValid('txt_symbol', 'ctype_graph')) {
      $inputError = true;
    }
    if (!formInputValid('txt_color', 'hex_color')) {
      $inputError = true;
    }
    if (!formInputValid('txt_bgcolor', 'hex_color')) {
      $inputError = true;
    }
    if (!formInputValid('txt_factor', 'numeric|max_length', 4)) {
      $inputError = true;
    }
    if (!formInputValid('txt_allowance', 'numeric|max_length', 3)) {
      $inputError = true;
    }
    if (!formInputValid('txt_allowmonth', 'numeric|max_length', 2)) {
      $inputError = true;
    }
    if (!formInputValid('txt_allowweek', 'numeric|max_length', 1)) {
      $inputError = true;
    }
  }

  if (!$inputError) {
    // ,------,
    // | Save |
    // '------'
    if (isset($_POST['btn_save'])) {
      $AA->id = $_POST['hidden_id'];

      //
      // General
      //
      $AA->name = $_POST['txt_name'];
      if (isset($_POST['txt_symbol'])) {
        $AA->symbol = $_POST['txt_symbol'];
      } else {
        $AA->symbol = strtoupper(substr($_POST['txt_name'], 0, 1));
      }
      $AA->color = ltrim(sanitize($_POST['txt_color']), '#');
      $AA->bgcolor = ltrim(sanitize($_POST['txt_bgcolor']), '#');
      $AA->bgtrans = isset($_POST['chk_bgtrans']) ? '1' : '0';

      //
      // Options
      //
      $AA->factor = $_POST['txt_factor'] ?? '1';
      $AA->allowance = $_POST['txt_allowance'] ?? '';
      $AA->allowmonth = $_POST['txt_allowmonth'] ?? '';
      $AA->allowweek = $_POST['txt_allowweek'] ?? '';
      $AA->counts_as = $_POST['sel_counts_as'] ?? '';

      $checkboxes = [
        'chk_counts_as_present' => 'counts_as_present',
        'chk_approval_required' => 'approval_required',
        'chk_manager_only' => 'manager_only',
        'chk_hide_in_profile' => 'hide_in_profile',
        'chk_confidential' => 'confidential',
        'chk_takeover' => 'takeover',
        'chk_show_in_remainder' => 'show_in_remainder',
      ];

      foreach ($checkboxes as $postKey => $property) {
        $AA->$property = isset($_POST[$postKey]) ? '1' : '0';
      }

      //
      // Group assignments
      //
      $AG->unassignAbs($AA->id);
      if (isset($_POST['sel_groups'])) {
        foreach ($_POST['sel_groups'] as $grp) {
          $AG->assign($AA->id, $grp);
        }
      }

      // Update the record
      $AA->update($AA->id);

      // Send notification and log event
      if ($allConfig['emailNotifications']) {
        sendAbsenceEventNotifications("changed", $AA->name);
      }
      $LOG->logEvent("logAbsence", L_USER, "log_abs_updated", $AA->name);

      // Renew CSRF token after successful form processing
      if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }

      // Success
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['abs_alert_edit'];
      $alertData['text'] = $LANG['abs_alert_edit_success'];
      $alertData['help'] = '';
    }
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_input'];
    $alertData['text'] = $LANG['abs_alert_save_failed'];
    $alertData['help'] = '';
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$viewData['id'] = $AA->id;
$viewData['name'] = $AA->name;
$viewData['symbol'] = $AA->symbol;
$viewData['icon'] = $AA->icon;
$viewData['color'] = $AA->color;
$viewData['bgcolor'] = $AA->bgcolor;
$viewData['bgtrans'] = $AA->bgtrans;

$viewData['factor'] = $AA->factor;
$viewData['allowance'] = $AA->allowance;
$viewData['allowmonth'] = $AA->allowmonth;
$viewData['allowweek'] = $AA->allowweek;

// Pre-fetch all primary absences and build options array
$otherAbs = $AA->getAllPrimaryBut($AA->id);
$viewData['otherAbs'] = [];
$viewData['otherAbs'][] = array('val' => '0', 'name' => 'None', 'selected' => ($AA->counts_as == '0') ? true : false);

// Build a map of absence IDs to names for efficient lookup
$absenceNameMap = array('0' => 'None');
foreach ($otherAbs as $abs) {
  $absenceNameMap[$abs['id']] = $abs['name'];
  $viewData['otherAbs'][] = array('val' => $abs['id'], 'name' => $abs['name'], 'selected' => ($AA->counts_as == $abs['id']) ? true : false);
}

// Use pre-built map instead of calling getName()
$viewData['counts_as']['val'] = $AA->counts_as;
$viewData['counts_as']['name'] = $absenceNameMap[$AA->counts_as] ?? 'None';
$viewData['counts_as_present'] = $AA->counts_as_present;
$viewData['approval_required'] = $AA->approval_required;
$viewData['manager_only'] = $AA->manager_only;
$viewData['hide_in_profile'] = $AA->hide_in_profile;
$viewData['confidential'] = $AA->confidential;
$viewData['takeover'] = $AA->takeover;
$viewData['show_in_remainder'] = $AA->show_in_remainder;

// Fetch all groups and build assignment array
$groups = $G->getAll();
$viewData['groupsAssigned'] = [];
foreach ($groups as $group) {
  $selected = $AG->isAssigned($viewData['id'], $group['id']);
  $viewData['groupsAssigned'][] = array('val' => $group['id'], 'name' => $group['name'], 'selected' => $selected);
}

$viewData['formObjects'] = [
  'general' => [
    ['prefix' => 'abs', 'name' => 'name', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['name'], 'maxlength' => '80', 'mandatory' => true, 'error' => $inputAlert['name'] ?? ''],
    ['prefix' => 'abs', 'name' => 'symbol', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['symbol'], 'maxlength' => '1', 'mandatory' => true, 'error' => $inputAlert['symbol'] ?? ''],
    ['prefix' => 'abs', 'name' => 'color', 'type' => 'coloris', 'value' => (!empty($viewData['color']) ? '#' . $viewData['color'] : ''), 'maxlength' => '6', 'error' => $inputAlert['color'] ?? ''],
    ['prefix' => 'abs', 'name' => 'bgcolor', 'type' => 'coloris', 'value' => (!empty($viewData['bgcolor']) ? '#' . $viewData['bgcolor'] : ''), 'maxlength' => '6', 'error' => $inputAlert['bgcolor'] ?? ''],
    ['prefix' => 'abs', 'name' => 'bgtrans', 'type' => 'check', 'value' => $viewData['bgtrans']],
  ],
  'options' => [
    ['prefix' => 'abs', 'name' => 'factor', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['factor'], 'maxlength' => '4', 'error' => $inputAlert['factor'] ?? ''],
    ['prefix' => 'abs', 'name' => 'allowance', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['allowance'], 'maxlength' => '3', 'error' => $inputAlert['allowance'] ?? ''],
    ['prefix' => 'abs', 'name' => 'allowmonth', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['allowmonth'], 'maxlength' => '2', 'error' => $inputAlert['allowmonth'] ?? ''],
    ['prefix' => 'abs', 'name' => 'allowweek', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['allowweek'], 'maxlength' => '2', 'error' => $inputAlert['allowweek'] ?? ''],
    ['prefix' => 'abs', 'name' => 'counts_as', 'type' => 'list', 'values' => $viewData['otherAbs'], 'topvalue' => ['val' => '0', 'name' => 'None']],
    ['prefix' => 'abs', 'name' => 'counts_as_present', 'type' => 'check', 'value' => $viewData['counts_as_present']],
    ['prefix' => 'abs', 'name' => 'approval_required', 'type' => 'check', 'value' => $viewData['approval_required']],
    ['prefix' => 'abs', 'name' => 'manager_only', 'type' => 'check', 'value' => $viewData['manager_only']],
    ['prefix' => 'abs', 'name' => 'hide_in_profile', 'type' => 'check', 'value' => $viewData['hide_in_profile']],
    ['prefix' => 'abs', 'name' => 'confidential', 'type' => 'check', 'value' => $viewData['confidential']],
    ['prefix' => 'abs', 'name' => 'takeover', 'type' => 'check', 'value' => $viewData['takeover']],
    ['prefix' => 'abs', 'name' => 'show_in_remainder', 'type' => 'check', 'value' => $viewData['show_in_remainder']],
  ],
  'groups' => [
    ['prefix' => 'abs', 'name' => 'groups', 'type' => 'listmulti', 'values' => $viewData['groupsAssigned']],
  ]
];

//
// For future use of toast messages
//
$showToast = false;

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
