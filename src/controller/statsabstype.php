<?php
/**
 * Absence type statistics page controller
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

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$viewData['labels'] = "";
$viewData['data'] = "";
$viewData['absences'] = $A->getAll();
$viewData['groups'] = $G->getAll('DESC');
$viewData['groupid'] = 'all';
$viewData['period'] = 'year';
$viewData['from'] = date("Y") . '-01-01';
$viewData['to'] = date("Y") . '-12-31';
$viewData['yaxis'] = 'users';
if ($color = $C->read("statsDefaultColorAbsencetype")) {
  $viewData['color'] = $color;
} else {
  $viewData['color'] = 'cyan';
}
$viewData['showAsPieChart'] = false;

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
      // Read group selection
      //
      $viewData['groupid'] = $_POST['sel_group'];
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
      if (isset($_POST['sel_color'])) {
        $viewData['color'] = $_POST['sel_color'];
      }
      if (isset($_POST['chk_showAsPieChart']) && $_POST['chk_showAsPieChart']) {
        $viewData['showAsPieChart'] = true;
      } else {
        $viewData['showAsPieChart'] = false;
      }
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
if ($viewData['groupid'] == "all") {
  $viewData['groupName'] = $LANG['all'];
} else {
  $viewData['groupName'] = $G->getNameById($_POST['sel_group']);
}
$viewData['periodName'] = $viewData['from'] . ' - ' . $viewData['to'];
$labels = array();
$sliceColors = array();
$data = array();
//
// Loop through all absence types (that count as absent)
//
$viewData['total'] = 0;
foreach ($viewData['absences'] as $abs) {
  if ($A->get($abs['id']) && !$A->counts_as_present) {
    $labels[] = '"' . $abs['name'] . '"';
    $sliceColors[] = '"#' . $abs['bgcolor'] . '"';
    $count = 0;
    if ($viewData['groupid'] == "all") {
      //
      // Count for all groups
      //
      foreach ($viewData['groups'] as $group) {
        $users = $UG->getAllforGroup($group['id']);
        foreach ($users as $user) {
          $countFrom = str_replace('-', '', $viewData['from']);
          $countTo = str_replace('-', '', $viewData['to']);
          $count += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
        }
      }
      $data[] = $count;
      $viewData['total'] += $count;
    } else {
      //
      // Count for a specific groups
      //
      $users = $UG->getAllforGroup($viewData['groupid']);
      foreach ($users as $user) {
        $countFrom = str_replace('-', '', $viewData['from']);
        $countTo = str_replace('-', '', $viewData['to']);
        $count += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
      }
      $data[] = $count;
      $viewData['total'] += $count;
    }
  }
}
//
// Build Chart.js labels and data
//
$viewData['labels'] = implode(',', $labels);
$viewData['sliceColors'] = implode(',', $sliceColors);
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
