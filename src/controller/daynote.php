<?php
/**
 * Daynote Controller
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
global $U;
global $R;
global $D;

//-----------------------------------------------------------------------------
// CHECK PERMISSION
//
if (
  !isAllowed($CONF['controllers'][$controller]->permission) ||
  (isset($_GET['for']) && $_GET['for'] == 'all' && !isAllowed('daynoteglobal'))
) {
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
if (isset($_GET['date']) && isset($_GET['for'])) {
  $missingData = false;
  $dnDate = sanitize($_GET['date']);
  $for = sanitize($_GET['for']);
  $region = '1'; // Default
  if ($for == "all") {
    if (isset($_GET['region'])) {
      $region = sanitize($_GET['region']);
    } else {
      $missingData = true;
    }
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

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$viewData['id'] = '';
$viewData['date'] = substr($dnDate, 0, 4) . '-' . substr($dnDate, 4, 2) . '-' . substr($dnDate, 6, 2);
$viewData['month'] = substr($dnDate, 0, 6);
$viewData['enddate'] = '';
$viewData['user'] = $for;
if ($for == 'all') {
  $viewData['userFullname'] = $LANG['all'];
} else {
  $viewData['userFullname'] = $U->getFullname($for);
}
$viewData['region'] = $region;
$viewData['regionName'] = 'Default';
$viewData['daynote'] = '';
$viewData['color'] = 'info';
$viewData['confidential'] = '0';
$viewData['exists'] = false;
$regions = $R->getAll();

//
// If Daynote exists, get it
//
if ($D->get($dnDate, $for, $region)) {
  $viewData['id'] = $D->id;
  $viewData['date'] = substr($D->yyyymmdd, 0, 4) . '-' . substr($D->yyyymmdd, 4, 2) . '-' . substr($D->yyyymmdd, 6, 2);
  $viewData['user'] = $D->username;
  $viewData['region'] = $D->region;
  $viewData['regionName'] = $R->getNameById($D->region);
  $viewData['daynote'] = $D->daynote;
  $viewData['color'] = $D->color;
  $viewData['confidential'] = $D->confidential;
  $viewData['exists'] = true;
}

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
  // Load sanitized input for view
  //
  $viewData['date'] = $_POST['txt_date'];
  $viewData['daynote'] = $_POST['txt_daynote'];
  $viewData['color'] = $_POST['opt_color'];
  if (isset($_POST['chk_confidential'])) {
    $viewData['confidential'] = '1';
  } else {
    $viewData['confidential'] = '0';
  }
  //
  // Form validation
  //
  $inputError = false;
  if (isset($_POST['btn_create']) || isset($_POST['btn_update'])) {
    if (!formInputValid('txt_date', 'required|date')) {
      $inputError = true;
    }
    if (!formInputValid('txt_enddate', 'date')) {
      $inputError = true;
    }
    if (!formInputValid('txt_daynote', 'required')) {
      $inputError = true;
    }
    if (!isset($_POST['sel_regions'])) {
      $inputAlert['regions'] = $LANG['alert_input_required'];
      $inputError = true;
    }
  }

  if (!$inputError) {
    // ,----------------,
    // | Create, Update |
    // '----------------'
    if (isset($_POST['btn_create']) || isset($_POST['btn_update'])) {
      // Single day or range
      $startDate = $dnDate;
      $endDate = $dnDate;
      if (isset($_POST['txt_enddate']) && strlen($_POST['txt_enddate'])) {
        $viewData['enddate'] = $_POST['txt_enddate'];
        $endDate = str_replace('-', '', $viewData['enddate']);
      }
      createDaynotesForRange(
        $startDate,
        $endDate,
        $viewData['user'],
        $_POST['sel_regions'],
        $viewData['daynote'],
        $viewData['color'],
        $viewData['confidential'],
        $D
      );

      //
      // Log this event
      //
      if ($viewData['user'] == 'all') {
        $logentry = $viewData['date'] . "|" . $R->getNameById($viewData['region']) . ": " . substr($viewData['daynote'], 0, 20) . "...";
      } else {
        $logentry = $viewData['date'] . "|" . $viewData['user'] . ": " . substr($viewData['daynote'], 0, 20) . "...";
      }

      if (isset($_POST['btn_create'])) {
        $LOG->logEvent("logDaynote", L_USER, "log_dn_created", $logentry);
        //
        // Success
        //
        $showAlert = true;
        $alertData['type'] = 'success';
        $alertData['title'] = $LANG['alert_success_title'];
        $alertData['subject'] = $LANG['dn_alert_create'];
        $alertData['text'] = $LANG['dn_alert_create_success'];
        $alertData['help'] = '';
      }

      if (isset($_POST['btn_update'])) {
        $LOG->logEvent("logDaynote", L_USER, "log_dn_updated", $logentry);
        //
        // Success
        //
        $showAlert = true;
        $alertData['type'] = 'success';
        $alertData['title'] = $LANG['alert_success_title'];
        $alertData['subject'] = $LANG['dn_alert_update'];
        $alertData['text'] = $LANG['dn_alert_update_success'];
        $alertData['help'] = '';
      }
    }
    // ,--------,
    // | Delete |
    // '--------'
    if (isset($_POST['btn_delete'])) {
      $D->deleteByDateAndUser($dnDate, $viewData['user']);
      if (isset($_POST['txt_enddate'])) {
        $startdate = str_replace('-', '', $_POST['txt_date']);
        $enddate = str_replace('-', '', $_POST['txt_enddate']);
        if ($enddate > $startdate) {
          for ($i = $startdate; $i <= $enddate; $i++) {
            $D->deleteByDateAndUser($i, $viewData['user']);
          }
        }
      }
      //
      // Log this event
      //
      if ($viewData['user'] == 'all') {
        $logentry = $viewData['date'] . "|" . $R->getNameById($viewData['region']) . ": " . substr($viewData['daynote'], 0, 20) . "...";
      } else {
        $logentry = $viewData['date'] . "|" . $viewData['user'] . ": " . substr($viewData['daynote'], 0, 20) . "...";
      }
      $LOG->logEvent("logDaynote", L_USER, "log_dn_deleted", $logentry);
      header("Location: index.php?action=" . $controller . "&date=" . str_replace('-', '', $viewData['date']) . '&for=' . $viewData['user'] . '&region=' . $viewData['region']);
      die();
    }
    //
    // Renew CSRF token after successful form processing
    //
    if (isset($_SESSION)) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_input'];
    $alertData['text'] = $LANG['dn_alert_failed'];
    $alertData['help'] = '';
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
if ($viewData['exists']) {
  //
  // A daynote exists for this user and date. Select each reason it is valid for.
  // If no region is set, select the Default region.
  //
  foreach ($regions as $region) {
    $viewData['regions'][] = array( 'val' => $region['id'], 'name' => $region['name'], 'selected' => ($D->get($dnDate, $for, $region['id'])) ? true : (($region['id'] == $viewData['region']) ? true : false) );
  }
} else {
  //
  // Mo daynote exists for this user and date. Select the Default region.
  //
  foreach ($regions as $region) {
    $viewData['regions'][] = array( 'val' => $region['id'], 'name' => $region['name'], 'selected' => ($region['id'] == $viewData['region']) ? true : false );
  }
}

$viewData['daynote'] = array(
  array( 'prefix' => 'dn', 'name' => 'date', 'type' => 'date', 'value' => $viewData['date'], 'maxlength' => '10', 'mandatory' => true, 'error' => (isset($inputAlert['date']) ? $inputAlert['date'] : '') ),
  array( 'prefix' => 'dn', 'name' => 'enddate', 'type' => 'date', 'value' => $viewData['enddate'], 'maxlength' => '10', 'mandatory' => false, 'error' => (isset($inputAlert['enddate']) ? $inputAlert['enddate'] : '') ),
  array( 'prefix' => 'dn', 'name' => 'daynote', 'type' => 'textarea', 'value' => $viewData['daynote'], 'rows' => '10', 'placeholder' => $LANG['dn_daynote_placeholder'], 'mandatory' => true, 'error' => (isset($inputAlert['daynote']) ? $inputAlert['daynote'] : '') ),
  array( 'prefix' => 'dn', 'name' => 'regions', 'type' => 'listmulti', 'values' => $viewData['regions'], 'mandatory' => true, 'error' => (isset($inputAlert['regions']) ? $inputAlert['regions'] : '') ),
  array( 'prefix' => 'dn', 'name' => 'color', 'type' => 'radio', 'values' => array( 'info', 'success', 'warning', 'danger' ), 'value' => $viewData['color'] ),
  array( 'prefix' => 'dn', 'name' => 'confidential', 'type' => 'check', 'value' => $viewData['confidential'] ),
);

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';

//-----------------------------------------------------------------------------
/**
 * Creates daynotes for a range of dates and regions for a specific user.
 *
 * For each date in the range [$start, $end], deletes any existing daynote for the user,
 * then creates a new daynote for each specified region with the given properties.
 *
 * @param int    $start        Start date in YYYYMMDD format (inclusive)
 * @param int    $end          End date in YYYYMMDD format (inclusive)
 * @param string $user         Username for whom the daynotes are created
 * @param array  $regions      Array of region IDs
 * @param string $daynote      The daynote text/content
 * @param string $color        The color identifier for the daynote
 * @param string $confidential Confidential flag ('0' or '1')
 * @param object $D            Daynote model/object with create and deleteByDateAndUser methods
 *
 * @return void
 */
function createDaynotesForRange($start, $end, $user, $regions, $daynote, $color, $confidential, $D) {
  for ($i = $start; $i <= $end; $i++) {
    $D->deleteByDateAndUser($i, $user);
    foreach ((array)$regions as $reg) {
      $D->yyyymmdd = $i;
      $D->username = $user;
      $D->region = $reg;
      $D->daynote = $daynote;
      $D->color = $color;
      $D->confidential = $confidential;
      $D->create();
    }
  }
}
