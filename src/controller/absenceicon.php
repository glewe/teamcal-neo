<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Absence Icon Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
global $faIcons;
global $LANG;
global $CONF;
global $controller;

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

  // ,------,
  // | Save |
  // '------'
  if (isset($_POST['btn_save'])) {
    $AA->id = $_POST['hidden_id'] ?? '';
    if (isset($_POST['opt_absIcon'])) {
      $AA->icon = $_POST['opt_absIcon'] ?? '';
    } else {
      $AA->icon = 'times';
    }

    //
    // Update the record
    //
    $AA->update($AA->id);

    //
    // Renew CSRF token after successful form processing
    //
    if (session_status() === PHP_SESSION_ACTIVE) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    //
    // Go back to absence edit page
    //
    header("Location: index.php?action=absenceedit&id=" . $AA->id);
  } elseif (isset($_POST['btn_fa_filter'])) {
    // ,--------,
    // | Filter |
    // '--------'
    $filterString = $_POST['fa_search'] ?? '';
    $allIcons = $faIcons;
    $faIcons = array_filter($allIcons, function ($element) use ($filterString) {
      return strpos($element, $filterString) !== false;
    });
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$viewData['id'] = $AA->id;
$viewData['name'] = $AA->name;
$viewData['icon'] = $AA->icon;

require_once WEBSITE_ROOT . '/helpers/view.helper.php';
$iconSets = splitFaIcons($AA->icon);
$viewData = array_merge($viewData, $iconSets);

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
