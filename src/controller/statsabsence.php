<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Absence statistics page controller
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
global $U;
global $UG;
global $A;
global $G;

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
// LOAD CONTROLLER RESOURCES
//
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];
$viewData['currentYearOnly'] = $allConfig['currentYearOnly'];

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$viewData['labels'] = "";
$viewData['data'] = "";
$viewData['absences'] = $A->getAll();
$viewData['groups'] = $G->getAll('DESC');
$viewData['absid'] = 'all';
$viewData['groupid'] = 'all';
$viewData['period'] = 'year';
$viewData['from'] = date("Y") . '-01-01';
$viewData['to'] = date("Y") . '-12-31';
$viewData['yaxis'] = 'users';
if ($color = $allConfig['statsDefaultColorAbsences']) {
  $viewData['color'] = $color;
} else {
  $viewData['color'] = 'red';
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
  // Form validation
  //
  $inputError = false;
  if (isset($_POST['btn_apply'])) {
    if (!formInputValid('txt_from', 'date')) {
      $inputError = true;
    }
    if (!formInputValid('txt_to', 'date')) {
      $inputError = true;
    }
    if (!formInputValid('txt_scaleSmart', 'numeric')) {
      $inputError = true;
    }
    if (!formInputValid('txt_scaleMax', 'numeric')) {
      $inputError = true;
    }
    if (!formInputValid('txt_colorHex', 'hexadecimal')) {
      $inputError = true;
    }
  }

  if (!$inputError) {
    // ,-------,
    // | Apply |
    // '-------'
    if (isset($_POST['btn_apply'])) {
      //
      // Read absence type selection
      //
      $viewData['absid'] = $_POST['sel_absence'];
      //
      // Read group selection
      //
      $viewData['groupid'] = $_POST['sel_group'];
      $viewData['yaxis'] = $_POST['opt_yaxis'];
      //
      // Read period selection
      //
      $viewData['period'] = $_POST['sel_period'];
      if ($viewData['period'] == 'custom') {
        $viewData['from'] = $_POST['txt_from'];
        $viewData['to'] = $_POST['txt_to'];
      }
      //
      // Read diagram options
      //
      $viewData['color'] = $_POST['sel_color'];
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
    $alertData['text'] = $LANG['abs_alert_save_failed'];
    $alertData['help'] = '';
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
switch ($viewData['period']) {
  case 'year':
    $viewData['from'] = date("Y") . '-01-01';
    $viewData['to'] = date("Y") . '-12-31';
    break;

  case 'half':
    if (date("n") <= 6) {
      $viewData['from'] = date("Y") . '-01-01';
      $viewData['to'] = date("Y") . '-06-30';
    } else {
      $viewData['from'] = date("Y") . '-07-01';
      $viewData['to'] = date("Y") . '-12-31';
    }
    break;

  case 'quarter':
    if (date("n") <= 3) {
      $viewData['from'] = date("Y") . '-01-01';
      $viewData['to'] = date("Y") . '-03-31';
    } elseif (date("n") <= 6) {
      $viewData['from'] = date("Y") . '-04-01';
      $viewData['to'] = date("Y") . '-06-30';
    } elseif (date("n") <= 9) {
      $viewData['from'] = date("Y") . '-07-01';
      $viewData['to'] = date("Y") . '-09-30';
    } else {
      $viewData['from'] = date("Y") . '-10-01';
      $viewData['to'] = date("Y") . '-12-31';
    }
    break;

  case 'month':
    $viewData['from'] = date("Y") . '-' . date("m") . '-01';
    $myts = strtotime($viewData['from']);
    $viewData['to'] = date("Y") . '-' . date("m") . '-' . sprintf('%02d', date("t", $myts));
    break;

  case 'custom':
    //
    // Nothing to do. POST variables already read.
    //
    break;

  default:
    break;
}

//
// Button titles
//
if ($viewData['absid'] == 'all') {
  $viewData['absName'] = $LANG['all'];
} else {
  $viewData['absName'] = $A->getName($viewData['absid']);
}

if ($viewData['groupid'] == "all") {
  $viewData['groupName'] = $LANG['all'];
} else {
  $viewData['groupName'] = $G->getNameById($viewData['groupid']);
}

if ($viewData['yaxis'] == "users") {
  $viewData['groupName'] .= ' ' . $LANG['stats_byusers'];
} else {
  $viewData['groupName'] .= ' ' . $LANG['stats_bygroups'];
}

$viewData['periodName'] = $viewData['from'] . ' - ' . $viewData['to'];

$labels = array();
$data = array();

//
// Pre-filter absences to exclude those with counts_as_present = true
//
$filteredAbsences = array_filter($viewData['absences'], function($abs) use ($A) {
  return $A->get($abs['id']) && !$A->counts_as_present;
});

//
// Pre-format date strings to avoid repeated str_replace() calls
//
$countFrom = str_replace('-', '', $viewData['from']);
$countTo = str_replace('-', '', $viewData['to']);

//
// Read data based on yaxis selection
//
if ($viewData['yaxis'] == 'users') {
  //
  // Y-axis: Users
  //
  $viewData['total'] = 0;
  if ($viewData['groupid'] == "all") {
    $users = $U->getAll('lastname', 'firstname', 'ASC', $archive = false, $includeAdmin = false);
  } else {
    $users = $UG->getAllforGroup($viewData['groupid']);
  }
  foreach ($users as $user) {
    if ($user['firstname'] != "") {
      $labels[] = '"' . $user['lastname'] . ", " . $user['firstname'] . '"';
    } else {
      $labels[] = '"' . $user['lastname'] . '"';
    }

    $count = 0;
    if ($viewData['absid'] == 'all') {
      foreach ($filteredAbsences as $abs) {
        $count += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
      }
    } else {
      $count += countAbsence($user['username'], $viewData['absid'], $countFrom, $countTo, false, false);
    }
    $data[] = $count;
    $viewData['total'] += $count;
  }
} else {
  //
  // Y-axis: Groups
  //
  $viewData['total'] = 0;
  if ($viewData['groupid'] == "all") {
    foreach ($viewData['groups'] as $group) {
      $labels[] = '"' . $group['name'] . '"';
      $users = $UG->getAllforGroup($group['id']);
      $count = 0;
      foreach ($users as $user) {
        if ($viewData['absid'] == 'all') {
          foreach ($filteredAbsences as $abs) {
            $count += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
          }
        } else {
          $count += countAbsence($user['username'], $viewData['absid'], $countFrom, $countTo, false, false);
        }
      }
      $data[] = $count;
      $viewData['total'] += $count;
    }
  } else {
    $labels[] = '"' . $G->getNameById($viewData['groupid']) . '"';
    $users = $UG->getAllforGroup($viewData['groupid']);
    $count = 0;
    foreach ($users as $user) {
      if ($viewData['absid'] == 'all') {
        foreach ($filteredAbsences as $abs) {
          $count += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
        }
      } else {
        $count += countAbsence($user['username'], $viewData['absid'], $countFrom, $countTo, false, false);
      }
    }
    $data[] = $count;
    $viewData['total'] += $count;
  }
}

//
// Build Chart.js labels and data
//
$viewData['labels'] = implode(',', $labels);
$viewData['data'] = implode(',', $data);
if (count($labels) <= 10) {
  $viewData['height'] = count($labels) * 20;
} else {
  $viewData['height'] = count($labels) * 10;
}

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
