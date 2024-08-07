<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Bulk Edit Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.5.0
 */

//=============================================================================
//
// CHECK URL PARAMETERS
//

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
// CHECK LICENSE
//
$alertData = array();
$showAlert = false;
$licExpiryWarning = $C->read('licExpiryWarning');
$LIC = new License();
$LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);

//=============================================================================
//
// LOAD CONTROLLER RESOURCES
//

//=============================================================================
//
// VARIABLE DEFAULTS
//
$absences = $A->getAll();
$absid = $absences[0]['id'];
$abs = new $A;
$abs->get($absid);
$groups = $G->getAll();
$groupid = 'All';
$users = $U->getAll('lastname', 'firstname', 'ASC', $archive = false, $includeAdmin = true);

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST)) {

  if (isset($_POST['btn_bulkUpdate'])) {
    // ,-------------,
    // | Bulk Update |
    // '-------------'
    $absid = $_POST['hidden_absid'];
    $groupid = $_POST['hidden_groupid'];

    //
    // Sanitize input
    //
    $_POST = sanitize($_POST);

    //
    // Form validation
    //
    $inputError = false;
    if (isset($_POST['txt_selected_' . $absid . '_allowance']) && strlen($_POST['txt_selected_' . $absid . '_allowance']) && !formInputValid('txt_selected_' . $absid . '_allowance', 'numeric')) {
      $inputError = true;
    }
    if (isset($_POST['txt_selected_' . $absid . '_carryover']) && strlen($_POST['txt_selected_' . $absid . '_carryover']) && !formInputValid('txt_selected_' . $absid . '_carryover', 'numeric')) {
      $inputError = true;
    }
    foreach ($users as $user) {
      if (isset($_POST['txt_' . $user['username'] . '_' . $absid . '_allowance']) && strlen($_POST['txt_' . $user['username'] . '_' . $absid . '_allowance']) && !formInputValid('txt_' . $user['username'] . '_' . $absid . '_allowance', 'numeric')) {
        $inputError = true;
      }
      if (isset($_POST['txt_' . $user['username'] . '_' . $absid . '_carryover']) && strlen($_POST['txt_' . $user['username'] . '_' . $absid . '_carryover']) && !formInputValid('txt_' . $user['username'] . '_' . $absid . '_carryover', 'numeric')) {
        $inputError = true;
      }
    }

    if (!$inputError && isset($_POST['chk_userSelected'])) {
      //
      // Loop over all selected users
      //
      $selected_users = $_POST['chk_userSelected'];
      foreach ($selected_users as $su => $value) {
        //
        // Allowance
        //
        $userAllowance = 0;
        if (isset($_POST['txt_selected_' . $absid . '_allowance']) && strlen($_POST['txt_selected_' . $absid . '_allowance'])) {
          $userAllowance = $_POST['txt_selected_' . $absid . '_allowance'];
        } elseif (isset($_POST['txt_' . $value . '_' . $absid . '_allowance']) && strlen($_POST['txt_' . $value . '_' . $absid . '_allowance'])) {
          $userAllowance = $_POST['txt_' . $value . '_' . $absid . '_allowance'];
        }
        //
        // Carryover
        //
        $userCarryover = 0;
        if (isset($_POST['txt_selected_' . $absid . '_carryover']) && strlen($_POST['txt_selected_' . $absid . '_carryover'])) {
          $userCarryover = $_POST['txt_selected_' . $absid . '_carryover'];
        } elseif (isset($_POST['txt_' . $value . '_' . $absid . '_carryover']) && strlen($_POST['txt_' . $value . '_' . $absid . '_carryover'])) {
          $userCarryover = $_POST['txt_' . $value . '_' . $absid . '_carryover'];
        }
        //
        // Save allowance record for this user
        //
        $AL->username = $value;
        $AL->absid = $absid;
        $AL->allowance = $userAllowance;
        $AL->carryover = $userCarryover;
        $AL->save();
        //
        // Log this event
        //
        $LOG->logEvent("logUser", L_USER, "log_user_updated", "Allowance bulk edit");
      }
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['bulkedit_alert_update'];
      $alertData['text'] = $LANG['bulkedit_alert_update_success'];
      $alertData['help'] = '';
    } else {
      //
      // Input validation failed
      //
      $showAlert = true;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['alert_input'];
      $alertData['text'] = $LANG['bulkedit_alert_update_failed'];
      $alertData['help'] = '';
    }
  } elseif (isset($_POST['btn_load'])) {
    // ,------,
    // | Load |
    // '------'
    //
    // Get selected absence type and user group
    //
    $absid = $_POST['sel_absence'];
    $abs->get($absid);
    //
    // Get selected user group and update the users array
    //
    if ($_POST['sel_group'] != "All") {
      $groupid = $_POST['sel_group'];
      $searchUsers = array();
      foreach ($users as $user) {
        if ($UG->isMemberOrManagerOfGroup($user['username'], $groupid)) {
          $searchUsers[] = $user;
        }
      }
      $users = $searchUsers;
    }
  }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['absences'] = $absences;
$viewData['abs'] = $abs;
$viewData['absid'] = $absid;
$viewData['bulkusers'] = array();
$viewData['groupid'] = $groupid;
$viewData['groups'] = $groups;

if ($groupid != "All") {
  $users = $U->getAll('lastname', 'firstname', 'ASC', $archive = false, $includeAdmin = true);
  $searchUsers = array();
  foreach ($users as $user) {
    if ($UG->isMemberOrManagerOfGroup($user['username'], $groupid)) {
      $searchUsers[] = $user;
    }
  }
  $users = $searchUsers;
}

foreach ($users as $user) {
  $thisuser = array();
  $thisuser['username'] = $user['username'];
  if ($user['firstname'] != "") {
    $thisuser['dispname'] = $user['lastname'] . ", " . $user['firstname'];
  }
  else {
    $thisuser['dispname'] = $user['lastname'];
  }
  $thisuser['dispname'] .= ' (' . $user['username'] . ')';

  if ($AL->find($user['username'], $absid)) {
    $allowance = $AL->allowance;
    $carryover = $AL->carryover;
  } else {
    //
    // No allowance record yet. Let's create one.
    //
    if (!$allowance = $A->getAllowance($absid)) {
      //
      // There is zero global allowance (unlimited). Save 365 in personal for the year.
      //
      $allowance = 365;
    }
    $carryover = 0;
    $AL->username = $user['username'];
    $AL->absid = $absid;
    $AL->allowance = $allowance;
    $AL->carryover = $carryover;
    $AL->save();
  }
  $thisuser['allowance'] = $allowance;
  $thisuser['carryover'] = $carryover;
  $viewData['bulkusers'][] = $thisuser;
}

//=============================================================================
//
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
