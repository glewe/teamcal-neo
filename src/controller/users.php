<?php

/**
 * Users Controller
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
global $U;
global $UO;
global $UG;
global $G;

//-----------------------------------------------------------------------------
// CHECK PERMISSION
//
if (isset($controller) && $controller !== '' && isset($CONF['controllers'][$controller]) && isset($CONF['controllers'][$controller]->permission)) {
  if (!isAllowed($CONF['controllers'][$controller]->permission)) {
    $alertData['type'] = 'warning';
    $alertData['title'] = $LANG['alert_alert_title'];
    $alertData['subject'] = $LANG['alert_not_allowed_subject'];
    $alertData['text'] = $LANG['alert_not_allowed_text'];
    $alertData['help'] = $LANG['alert_not_allowed_help'];
    require_once WEBSITE_ROOT . '/controller/alert.php';
    die();
  }
} else {
  // Controller or permission not set, deny access
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
$date = new DateTime();
$weekday = $date->format('N');
if ($weekday === rand(1, 7)) {
  $alertData = array();
  $showAlert = false;
  $licExpiryWarning = $C->read('licExpiryWarning');
  $LIC = new License();
  $LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$U1 = new Users(); // used for sending out notifications
$viewData['searchUser'] = '';
$viewData['searchGroup'] = 'All';
$viewData['searchRole'] = 'All';

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
  if (!isset($_POST['csrf_token']) || ($_POST['csrf_token'] ?? '') !== ($_SESSION['csrf_token'] ?? '')) {
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

  if ($inputError === false) {
    // ,----------,
    // | Activate |
    // '----------'
    if (isset($_POST['btn_userActivate'])) {
      $selected_users = [];
      if (isset($_POST['chk_userActive']) && is_array($_POST['chk_userActive'])) {
        $selected_users = $_POST['chk_userActive'];
      }
      foreach ($selected_users as $su => $value) {
        $U->unhide($value);
        $U->unhold($value);
        $U->unlock($value);
        $U->unverify($value);
      }
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_activate_selected'];
      $alertData['text'] = $LANG['users_alert_activate_selected_users'];
      $alertData['help'] = '';
    }
    // ,---------,
    // | Archive |
    // '---------'
    elseif (isset($_POST['btn_userArchive']) && isset($_POST['chk_userActive'])) {
      $selected_users = [];
      if (isset($_POST['chk_userActive']) && is_array($_POST['chk_userActive'])) {
        $selected_users = $_POST['chk_userActive'];
      }
      //
      // Check if one or more users already exists in any archive table.
      // If so, we will not archive anything.
      //
      $exists = false;
      foreach ($selected_users as $su => $value) {
        if (!archiveUser($value)) {
          $exists = true;
        }
      }
      if (!$exists) {
        //
        // Success
        //
        $showAlert = true;
        $alertData['type'] = 'success';
        $alertData['title'] = $LANG['alert_success_title'];
        $alertData['subject'] = $LANG['btn_archive_selected'];
        $alertData['text'] = $LANG['users_alert_archive_selected_users'];
        $alertData['help'] = '';
      } else {
        //
        // Failed, at least partially
        //
        $showAlert = true;
        $alertData['type'] = 'danger';
        $alertData['title'] = $LANG['alert_danger_title'];
        $alertData['subject'] = $LANG['btn_archive_selected'];
        $alertData['text'] = $LANG['users_alert_archive_selected_users_failed'];
        $alertData['help'] = '';
      }
    }
    // ,---------,
    // | Restore |
    // '---------'
    elseif (isset($_POST['btn_profileRestore']) && isset($_POST['chk_userArchived'])) {
      $selected_users = [];
      if (isset($_POST['chk_userArchived']) && is_array($_POST['chk_userArchived'])) {
        $selected_users = $_POST['chk_userArchived'];
      }
      //
      // Check if one or more users already exists in any active table.
      // If so, we will not restore anything.
      //
      $exists = false;
      foreach ($selected_users as $su => $value) {
        if (!restoreUser($value)) {
          $exists = true;
        }
      }
      if (!$exists) {
        //
        // Success
        //
        $showAlert = true;
        $alertData['type'] = 'success';
        $alertData['title'] = $LANG['alert_success_title'];
        $alertData['subject'] = $LANG['btn_restore_selected'];
        $alertData['text'] = $LANG['users_alert_restore_selected_users'];
        $alertData['help'] = '';
      } else {
        //
        // Failed, at least partially
        //
        $showAlert = true;
        $alertData['type'] = 'danger';
        $alertData['title'] = $LANG['alert_danger_title'];
        $alertData['subject'] = $LANG['btn_restore_selected'];
        $alertData['text'] = $LANG['users_alert_restore_selected_users_failed'];
        $alertData['help'] = '';
      }
    }
    // ,---------------,
    // | Delete Active |
    // '---------------'
    elseif (isset($_POST['btn_profileDelete']) && isset($_POST['chk_userActive'])) {
      $selected_users = [];
      if (isset($_POST['chk_userActive']) && is_array($_POST['chk_userActive'])) {
        $selected_users = $_POST['chk_userActive'];
      }
      foreach ($selected_users as $su => $value) {
        //
        // Send notification e-mails to the subscribers of user events. In this case,
        // send before delete while we can still access info from the user.
        //
        if ($C->read("emailNotifications")) {
          $U1->findByName($value);
          sendUserEventNotifications("deleted", $U1->username, $U1->firstname, $U1->lastname);
        }
        deleteUser($value, false);
      }
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_delete_selected'];
      $alertData['text'] = $LANG['users_alert_delete_selected_users'];
      $alertData['help'] = '';
    }
    // ,-----------------,
    // | Delete Archived |
    // '-----------------'
    elseif (isset($_POST['btn_profileDeleteArchived']) && isset($_POST['chk_userArchived'])) {
      $selected_users = [];
      if (isset($_POST['chk_userArchived']) && is_array($_POST['chk_userArchived'])) {
        $selected_users = $_POST['chk_userArchived'];
      }
      foreach ($selected_users as $su => $value) {
        deleteUser($value, $fromArchive = true);
      }
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_delete_selected'];
      $alertData['text'] = $LANG['users_alert_delete_selected_users'];
      $alertData['help'] = '';
    }
    // ,----------------,
    // | Reset Password |
    // '----------------'
    elseif (isset($_POST['btn_userResetPassword']) && isset($_POST['chk_userActive'])) {
      $selected_users = [];
      if (isset($_POST['chk_userActive']) && is_array($_POST['chk_userActive'])) {
        $selected_users = $_POST['chk_userActive'];
      }
      foreach ($selected_users as $su => $value) {
        //
        // Find user and reset password
        //
        $U->findByName($value);
        $token = hash('sha512', 'PasswordResetRequestFor' . $U->username);
        $expiryDateTime = date('YmdHis', strtotime(date('YmdHis') . ' +1 day'));
        $UO->save($U->username, 'pwdTokenExpiry', $expiryDateTime);
        sendPasswordResetMail($U->email, $U->username, $U->lastname, $U->firstname, $token);
        //
        // Log this event
        //
        $LOG->logEvent("logUser", L_USER, "log_user_pwd_reset", $U->username);
      }
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_reset_password_selected'];
      $alertData['text'] = $LANG['users_alert_reset_password_selected'];
      $alertData['help'] = '';
    }
    // ,------------,
    // | Remove 2FA |
    // '------------'
    elseif (isset($_POST['btn_userRemoveSecret']) && isset($_POST['chk_userActive'])) {
      $selected_users = [];
      if (isset($_POST['chk_userActive']) && is_array($_POST['chk_userActive'])) {
        $selected_users = $_POST['chk_userActive'];
      }
      foreach ($selected_users as $su => $value) {
        //
        // Find user and reset password
        //
        $U->findByName($value);
        $UO->deleteUserOption($U->username, 'secret');
        //
        // Log this event
        //
        $LOG->logEvent("logUser", L_USER, "log_user_2fa_removed", $U->username);
      }
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_remove_secret_selected'];
      $alertData['text'] = $LANG['users_alert_remove_secret_selected'];
      $alertData['help'] = '';
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
    $alertData['text'] = $LANG['register_alert_failed'];
    $alertData['help'] = '';
  }
  //
  // Regenerate CSRF token after successful POST to prevent replay attacks
  //
  if (function_exists('random_bytes')) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  } else {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//

//
// Default: Get all active users
//
$users = $U->getAll('lastname', 'firstname', 'ASC', $archive = false, $includeAdmin = true);

// ,--------,
// | Search |
// '--------'
// Adjust users by requested search
//
if (isset($_POST['btn_search'])) {
  $searchUsers = array();

  if (isset($_POST['txt_searchUser']) && strlen($_POST['txt_searchUser'] ?? '') !== 0) {
    $searchUser = sanitize($_POST['txt_searchUser'] ?? '');
    $viewData['searchUser'] = $searchUser;
    $users = $U->getAllLike($searchUser);
  }

  if (isset($_POST['sel_searchGroup']) && (($_POST['sel_searchGroup'] ?? '') !== "All")) {
    $searchGroup = sanitize($_POST['sel_searchGroup'] ?? '');
    $viewData['searchGroup'] = $searchGroup;
    foreach ($users as $user) {
      if ($UG->isMemberOrManagerOfGroup($user['username'], $searchGroup)) {
        $searchUsers[] = $user;
      }
    }
    $users = $searchUsers;
  }

  if (isset($_POST['sel_searchRole']) && (($_POST['sel_searchRole'] ?? '') !== "All")) {
    $searchRole = sanitize($_POST['sel_searchRole'] ?? '');
    $viewData['searchRole'] = $searchRole;
    foreach ($users as $user) {
      if ($user['role'] == $searchRole) {
        $searchUsers[] = $user;
      }
    }
    $users = $searchUsers;
  }
}

//
// Load these users for the view
//
$viewData['users'] = array();
$i = 0;
foreach ($users as $user) {
  $U->findByName($user['username']);
  $viewData['users'][$i]['username'] = $user['username'];
  if ($U->firstname != "") {
    $viewData['users'][$i]['dispname'] = $U->lastname . ", " . $U->firstname;
  } else {
    $viewData['users'][$i]['dispname'] = $U->lastname;
  }
  $viewData['users'][$i]['dispname'] .= ' (' . $U->username . ')';
  $viewData['users'][$i]['role'] = $RO->getNameById($U->role);
  $viewData['users'][$i]['color'] = $RO->getColorById($U->role);
  //
  // Determine attributes
  //
  $viewData['users'][$i]['locked'] = false;
  $viewData['users'][$i]['hidden'] = false;
  $viewData['users'][$i]['onhold'] = false;
  $viewData['users'][$i]['verify'] = false;
  if ($U->locked) {
    $viewData['users'][$i]['locked'] = true;
  }
  if ($U->hidden) {
    $viewData['users'][$i]['hidden'] = true;
  }
  if ($U->onhold) {
    $viewData['users'][$i]['onhold'] = true;
  }
  if ($U->verify) {
    $viewData['users'][$i]['verify'] = true;
  }
  $viewData['users'][$i]['created'] = $U->created;
  $viewData['users'][$i]['last_login'] = $U->last_login;
  $i++;
}

//
// Always load all archived users
//
$viewData['users1'] = array();
$i = 0;
$users1 = $U->getAll('lastname', 'firstname', 'ASC', $archive = true);
foreach ($users1 as $user1) {
  $U->findByName($user1['username'], $archive = true);
  $viewData['users1'][$i]['username'] = $user1['username'];
  if ($U->firstname != "") {
    $viewData['users1'][$i]['dispname'] = $U->lastname . ", " . $U->firstname;
  } else {
    $viewData['users1'][$i]['dispname'] = $U->lastname;
  }
  $viewData['users1'][$i]['dispname'] .= ' (' . $U->username . ')';
  $viewData['users1'][$i]['role'] = $RO->getNameById($U->role);
  $viewData['users1'][$i]['color'] = $RO->getColorById($U->role);
  //
  // Determine attributes
  //
  $viewData['users1'][$i]['locked'] = false;
  $viewData['users1'][$i]['hidden'] = false;
  $viewData['users1'][$i]['onhold'] = false;
  $viewData['users1'][$i]['verify'] = false;
  if ($U->locked) {
    $viewData['users1'][$i]['locked'] = true;
  }
  if ($U->hidden) {
    $viewData['users1'][$i]['hidden'] = true;
  }
  if ($U->onhold) {
    $viewData['users1'][$i]['onhold'] = true;
  }
  if ($U->verify) {
    $viewData['users1'][$i]['verify'] = true;
  }
  $viewData['users1'][$i]['created'] = $U->created;
  $viewData['users1'][$i]['last_login'] = $U->last_login;
  $i++;
}

$viewData['groups'] = $G->getAll();
$viewData['roles'] = $RO->getAll();

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
