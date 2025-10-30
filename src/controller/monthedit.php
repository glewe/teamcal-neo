<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Month Edit Controller
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
global $M;
global $R;
global $H;
global $UL;

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
// CHECK LICENSE
// Checks when the current weekday matches a random number between 1 and 7
//
$date = new DateTime();
$weekday = $date->format('N');
if ($weekday == rand(1, 7)) {
  $alertData = array();
  $showAlert = false;
  $licExpiryWarning = $allConfig['licExpiryWarning'];
  $LIC = new License();
  $LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);
}

//-----------------------------------------------------------------------------
// CHECK URL PARAMETERS
//
if (isset($_GET['month']) && isset($_GET['region'])) {
  $missingData = false;
  $yyyymm = sanitize($_GET['month']);
  $region = sanitize($_GET['region']);
  $viewData['year'] = substr($yyyymm, 0, 4);
  $viewData['month'] = substr($yyyymm, 4, 2);
  if (!is_numeric($yyyymm) || strlen($yyyymm) != 6 || !checkdate(intval($viewData['month']), 1, intval($viewData['year']))) {
    $missingData = true;
  }
  if (!$R->getById($region)) {
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
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];
$viewData['currentYearOnly'] = $allConfig['currentYearOnly'];

//
// Default back to current yearmonth if option is set
//
if ($viewData['currentYearOnly'] && $viewData['year'] != date('Y')) {
  header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . date('Ym') . "&region=" . $region);
  die();
}

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$inputAlert = array();
$viewData['regionid'] = $R->id;
$viewData['regionname'] = $R->name;

//
// See if a template exists. If not, create one.
//
if (!$M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid'])) {
  createMonth($viewData['year'], $viewData['month'], 'region', $viewData['regionid']);
  //
  // Log this event
  //
  $LOG->logEvent("logMonth", L_USER, "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
}

$viewData['dateInfo'] = dateInfo($viewData['year'], $viewData['month']);

//-----------------------------------------------------------------------------
// PROCESS FORM
//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

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

  // ,------,
  // | Save |
  // '------'
  if (isset($_POST['btn_save'])) {
    //
    // Loop thru the radio boxes
    //
    for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
      $key = 'opt_hol_' . $i;
      if (isset($_POST[$key])) {
        $M->setHoliday($viewData['year'], $viewData['month'], $i, $viewData['regionid'], $_POST[$key]);
      }
    }
    //
    // Send notification e-mails to the subscribers of user events
    //
    if ($allConfig['emailNotifications']) {
      sendMonthEventNotifications("changed", $viewData['year'], $viewData['month'], $viewData['regionname']);
    }
    //
    // Log this event
    //
    $LOG->logEvent("logMonth", L_USER, "log_month_tpl_updated", $M->region . " " . $M->year . $M->month);

    //
    // Success
    //
    $showAlert = true;
    $alertData['type'] = 'success';
    $alertData['title'] = $LANG['alert_success_title'];
    $alertData['subject'] = $LANG['monthedit_alert_update'];
    $alertData['text'] = $LANG['monthedit_alert_update_success'];
    $alertData['help'] = '';
  }
  // ,-----------,
  // | Clear All |
  // '-----------'
  elseif (isset($_POST['btn_clearall'])) {
    $M->clearHolidays($viewData['year'], $viewData['month'], $viewData['regionid']);
    //
    // Send notification e-mails to the subscribers of user events
    //
    if ($allConfig['emailNotifications']) {
      sendMonthEventNotifications("changed", $viewData['year'], $viewData['month'], $viewData['regionname']);
    }
    //
    // Log this event
    //
    $LOG->logEvent("logMonth", L_USER, "log_month_tpl_updated", $M->region . " " . $M->year . $M->month);
    //
    // Success
    //
    $showAlert = true;
    $alertData['type'] = 'success';
    $alertData['title'] = $LANG['alert_success_title'];
    $alertData['subject'] = $LANG['monthedit_alert_update'];
    $alertData['text'] = $LANG['monthedit_alert_update_success'];
    $alertData['help'] = '';
  }
  // ,---------------,
  // | Select Region |
  // '---------------'
  elseif (isset($_POST['btn_region'])) {
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $_POST['sel_region']);
    die();
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid']);
$viewData['holidays'] = $H->getAllCustom();
$viewData['dayStyles'] = array();

//
// Pre-cache holiday and weekend colors to avoid repeated lookups
//
$holidayColorCache = array();
foreach ($viewData['holidays'] as $hol) {
  $holidayColorCache[$hol['id']] = array(
    'color' => $H->getColor($hol['id']),
    'bgcolor' => $H->getBgColor($hol['id'])
  );
}
// Also cache weekend colors (indices 2-3 for Saturday/Sunday, calculated as wday - 4)
for ($i = 2; $i <= 3; $i++) {
  $holidayColorCache[$i] = array(
    'color' => $H->getColor($i),
    'bgcolor' => $H->getBgColor($i)
  );
}

//
// Get the holiday and weekend colors
//
for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
  $viewData['dayStyles'][$i] = '';
  $hprop = 'hol' . $i;
  $wprop = 'wday' . $i;
  if ($M->$hprop) {
    //
    // This is a holiday. Get the coloring info from cache.
    //
    if (isset($holidayColorCache[$M->$hprop])) {
      $color = $holidayColorCache[$M->$hprop]['color'];
      $bgcolor = $holidayColorCache[$M->$hprop]['bgcolor'];
      $viewData['dayStyles'][$i] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';
    }
  } elseif ($M->$wprop == 6 || $M->$wprop == 7) {
    //
    // This is a Saturday or Sunday. Get the coloring info from cache.
    //
    $weekendIndex = $M->$wprop - 4;
    if (isset($holidayColorCache[$weekendIndex])) {
      $color = $holidayColorCache[$weekendIndex]['color'];
      $bgcolor = $holidayColorCache[$weekendIndex]['bgcolor'];
      $viewData['dayStyles'][$i] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';
    }
  }
}

$todayDate = getdate(time());
$viewData['yearToday'] = $todayDate['year'];
$viewData['monthToday'] = sprintf("%02d", $todayDate['mon']);
$viewData['regions'] = $R->getAll();
$viewData['showWeekNumbers'] = $allConfig['showWeekNumbers'];
$mobilecols['full'] = $viewData['dateInfo']['daysInMonth'];
$viewData['supportMobile'] = $allConfig['supportMobile'];

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
