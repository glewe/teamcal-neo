<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Absence Summary Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Controllers
 * @since 3.0.0
 */

//=============================================================================
//
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

//=============================================================================
//
// CHECK URL PARAMETERS
//
if (isset($_GET['user'])) {
  $missingData = false;
  $caluser = sanitize($_GET['user']);
  if (!$U->findByName($caluser)) {
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

//=============================================================================
//
// LOAD CONTROLLER RESOURCES
//

//=============================================================================
//
// VARIABLE DEFAULTS
//
$users = $U->getAll();
$inputAlert = array();
$viewData['year'] = date("Y");

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST)) {
  //
  // Sanitize input
  //
  $_POST = sanitize($_POST);

  //
  // Form validation
  //
  $inputError = false;

  if (!$inputError) {
    // ,-------------,
    // | Select User |
    // '-------------'
    if (isset($_POST['btn_user'])) {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&user=" . $_POST['sel_user']);
      die();
    }
    // ,-------------,
    // | Select Year |
    // '-------------'
    elseif (isset($_POST['btn_year'])) {
      $viewData['year'] = $_POST['sel_year'];
    }
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_input'];
    $alertData['text'] = $LANG['register_alert_failed'];
    $alertData['help'] = '';
  }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['username'] = $caluser;
$viewData['fullname'] = $U->getFullname($caluser);
$viewData['users'] = array();
foreach ($users as $usr) {
  $viewData['users'][] = array( 'username' => $usr['username'], 'lastfirst' => $U->getLastFirst($usr['username']) );
}
$viewData['from'] = $viewData['year'] . '-01-01';
$viewData['to'] = $viewData['year'] . '-12-31';
$viewData['absences'] = array();
$absences = $A->getAll();
foreach ($absences as $abs) {
  $summary = getAbsenceSummary($caluser, $abs['id'], $viewData['year']);
  $viewData['absences'][] = array(
    'id' => $abs['id'],
    'icon' => $abs['icon'],
    'color' => $abs['color'],
    'bgcolor' => $abs['bgcolor'],
    'allowance' => $abs['allowance'],
    'counts_as' => $abs['counts_as'],
    'name' => $abs['name'],
    'contingent' => $summary['totalallowance'],
    'taken' => $summary['taken'],
    'remainder' => $summary['remainder'],
  );
}

//=============================================================================
//
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
