<?php
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
global $C;
global $CONF;
global $controller;
global $LANG;
global $LOG;
global $U;
global $UG;
global $A;
global $AL;
global $G;

//-----------------------------------------------------------------------------
// CHECK URL PARAMETERS
//

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
//
$alertData = array();
$showAlert = false;
$licExpiryWarning = $C->read('licExpiryWarning');
$LIC = new License();
$LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$absences = $A->getAll();
$absid = $absences[0]['id'];
$abs = new $A;
$abs->get($absid);
$groups = $G->getAll();
$groupid = 'All';
$users = $U->getAll('lastname', 'firstname', 'ASC', $archive = false, $includeAdmin = true);

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

  if (isset($_POST['btn_bulkUpdate'])) {
    // ,-------------,
    // | Bulk Update |
    // '-------------'

    //
    // Sanitize input
    //
    $_POST = sanitize($_POST);

    $absid = $_POST['hidden_absid'];
    $groupid = $_POST['hidden_groupid'];

    //
    // Form validation
    //
    $inputError = false;
    if (!empty($_POST['txt_selected_' . $absid . '_allowance']) && !formInputValid('txt_selected_' . $absid . '_allowance', 'numeric')) {
      $inputError = true;
    }
    if (!empty($_POST['txt_selected_' . $absid . '_carryover']) && !formInputValid('txt_selected_' . $absid . '_carryover', 'numeric')) {
      $inputError = true;
    }
    foreach ($users as $user) {
      if (!empty($_POST['txt_' . $user['username'] . '_' . $absid . '_allowance']) && !formInputValid('txt_' . $user['username'] . '_' . $absid . '_allowance', 'numeric')) {
        $inputError = true;
      }
      if (!empty($_POST['txt_' . $user['username'] . '_' . $absid . '_carryover']) && !formInputValid('txt_' . $user['username'] . '_' . $absid . '_carryover', 'numeric')) {
        $inputError = true;
      }
    }

    if (!$inputError && isset($_POST['chk_userSelected'])) {
      //
      // Loop over all selected users and collect updates
      //
      $selected_users = $_POST['chk_userSelected'];
      $updates = array();
      foreach ($selected_users as $su => $value) {
        //
        // Allowance
        //
        $userAllowance = 0;
        if (!empty($_POST['txt_selected_' . $absid . '_allowance'])) {
          $userAllowance = $_POST['txt_selected_' . $absid . '_allowance'];
        } elseif (!empty($_POST['txt_' . $value . '_' . $absid . '_allowance'])) {
          $userAllowance = $_POST['txt_' . $value . '_' . $absid . '_allowance'];
        }
        //
        // Carryover
        //
        $userCarryover = 0;
        if (!empty($_POST['txt_selected_' . $absid . '_carryover'])) {
          $userCarryover = $_POST['txt_selected_' . $absid . '_carryover'];
        } elseif (!empty($_POST['txt_' . $value . '_' . $absid . '_carryover'])) {
          $userCarryover = $_POST['txt_' . $value . '_' . $absid . '_carryover'];
        }
        // 
        // Collect update for this user
        //
        $updates[] = array(
          'username' => $value,
          'absid' => $absid,
          'allowance' => $userAllowance,
          'carryover' => $userCarryover
        );
      }
      // 
      // Batch save all updates
      //
      $AL->batchSave($updates);
      //
      // Log each event
      //
      foreach ($updates as $update) {
        $LOG->logEvent("logUser", L_USER, "log_user_updated", "Allowance bulk edit for user: " . $update['username']);
      }
      //
      // Renew CSRF token after successful form processing
      //
      if (isset($_SESSION)) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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
      $users = filterUsersByGroup($users, $groupid, $UG);
    }
    //
    // Renew CSRF token after successful form processing
    //
    if (isset($_SESSION)) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
  }
}

//-----------------------------------------------------------------------------
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
  $users = filterUsersByGroup($users, $groupid, $UG);
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

  list($allowance, $carryover) = getOrCreateAllowance($user, $absid, $A, $AL);
  $thisuser['allowance'] = $allowance;
  $thisuser['carryover'] = $carryover;
  $viewData['bulkusers'][] = $thisuser;
}

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';

//-----------------------------------------------------------------------------
/**
 * Filter users by group membership or management.
 *
 * @param array $users   List of user arrays to filter
 * @param string $groupid  Group ID to filter by ("All" for no filtering)
 * @param object $UG     UserGroup object with isMemberOrManagerOfGroup method
 * @return array         Filtered list of users
 */
function filterUsersByGroup($users, $groupid, $UG): array {
  if ($groupid == "All") return $users;
  $filtered = array();
  foreach ($users as $user) {
    if ($UG->isMemberOrManagerOfGroup($user['username'], $groupid)) {
      $filtered[] = $user;
    }
  }
  return $filtered;
}

//-----------------------------------------------------------------------------
/**
 * Get or create allowance and carryover for a user and absence type.
 *
 * @param array $user    User array (must contain 'username')
 * @param string|int $absid Absence type ID
 * @param object $A      Absences object with getAllowance method
 * @param object $AL     Allowances object with find/save and allowance/carryover properties
 * @return array         [allowance, carryover] for the user and absence type
 */
function getOrCreateAllowance($user, $absid, $A, $AL): array {
  if ($AL->find($user['username'], $absid)) {
    return [$AL->allowance, $AL->carryover];
  } else {
    $allowance = $A->getAllowance($absid);
    if (!$allowance) {
      $allowance = 0;
    }
    $carryover = 0;
    $AL->username = $user['username'];
    $AL->absid = $absid;
    $AL->allowance = $allowance;
    $AL->carryover = $carryover;
    $AL->save();
    return [$allowance, $carryover];
  }
}
