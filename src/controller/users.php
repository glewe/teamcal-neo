<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
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
global $allConfig;
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
  $licExpiryWarning = $allConfig['licExpiryWarning'];
  $LIC = new License();
  $LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
//
// VARIABLE DEFAULTS
//
global $DB;
$U1 = new Users(); // used for sending out notifications
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
        $U->activate($value);
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
        if ($allConfig['emailNotifications']) {
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

// Load all config values in one query for maximum performance
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];

// Preload all roles into a map for O(1) lookups
$allRoles = $RO->getAll();
$roleMap = [];
foreach ($allRoles as $role) {
    $roleMap[$role['id']] = [
        'name' => $role['name'],
        'color' => $role['color']
    ];
}

//
// Default: Get all active users
//
$users = $U->getAll('lastname', 'firstname', 'ASC', $archive = false, $includeAdmin = true);

// ,--------,
// | Search |
// '--------'
// Adjust users by requested search
//
if (isset($_POST['btn_filter'])) {
  // Preload group memberships to avoid N+1 queries in filter
  $allUserGroups = [];
  if (isset($_POST['sel_searchGroup']) && (($_POST['sel_searchGroup'] ?? '') !== "All")) {
    $searchGroup = sanitize($_POST['sel_searchGroup'] ?? '');
    $viewData['searchGroup'] = $searchGroup;

    // Use existing method to get members/managers of the group
    $groupMembers = $UG->getAllforGroup($searchGroup);
    $groupUsernames = array_column($groupMembers, 'username');

    $users = array_filter($users, function($user) use ($groupUsernames) {
      return in_array($user['username'], $groupUsernames);
    });
  }

  if (isset($_POST['sel_searchRole']) && (($_POST['sel_searchRole'] ?? '') !== "All")) {
    $searchRole = sanitize($_POST['sel_searchRole'] ?? '');
    $viewData['searchRole'] = $searchRole;
    $users = array_filter($users, function($user) use ($searchRole) {
      return $user['role'] == $searchRole;
    });
  }
}

//
// Load these users for the view
//
$viewData['users'] = array();
$i = 0;
foreach ($users as $user) {
  // Use data directly from $user array to avoid redundant DB query
  $viewData['users'][$i]['username'] = $user['username'];
  $firstname = trim($user['firstname'] ?? '');
  $lastname = trim($user['lastname'] ?? '');
  if ($firstname !== "") {
    $viewData['users'][$i]['dispname'] = $lastname . ", " . $firstname;
  } else {
    $viewData['users'][$i]['dispname'] = $lastname ?: $user['username'];
  }
  $viewData['users'][$i]['dispname'] .= ' (' . $user['username'] . ')';

  // Use preloaded role map
  $roleData = $roleMap[$user['role']] ?? ['name' => '', 'color' => 'default'];
  $viewData['users'][$i]['role'] = $roleData['name'];
  $viewData['users'][$i]['color'] = $roleData['color'];

  //
  // Determine attributes from $user array
  //
  $viewData['users'][$i]['locked'] = (bool)($user['locked'] ?? false);
  $viewData['users'][$i]['hidden'] = (bool)($user['hidden'] ?? false);
  $viewData['users'][$i]['onhold'] = (bool)($user['onhold'] ?? false);
  $viewData['users'][$i]['verify'] = (bool)($user['verify'] ?? false);
  $viewData['users'][$i]['created'] = $user['created'] ?? DEFAULT_TIMESTAMP;
  $viewData['users'][$i]['last_login'] = $user['last_login'] ?? DEFAULT_TIMESTAMP;
  $i++;
}

//
// Always load all archived users
//
$viewData['users1'] = array();
$i = 0;
$users1 = $U->getAll('lastname', 'firstname', 'ASC', $archive = true);
foreach ($users1 as $user1) {
  // Use data directly from $user1 array to avoid redundant DB query
  $viewData['users1'][$i]['username'] = $user1['username'];
  $firstname = trim($user1['firstname'] ?? '');
  $lastname = trim($user1['lastname'] ?? '');
  if ($firstname !== "") {
    $viewData['users1'][$i]['dispname'] = $lastname . ", " . $firstname;
  } else {
    $viewData['users1'][$i]['dispname'] = $lastname ?: $user1['username'];
  }
  $viewData['users1'][$i]['dispname'] .= ' (' . $user1['username'] . ')';

  // Use preloaded role map
  $roleData = $roleMap[$user1['role']] ?? ['name' => '', 'color' => 'default'];
  $viewData['users1'][$i]['role'] = $roleData['name'];
  $viewData['users1'][$i]['color'] = $roleData['color'];

  //
  // Determine attributes from $user1 array
  //
  $viewData['users1'][$i]['locked'] = (bool)($user1['locked'] ?? false);
  $viewData['users1'][$i]['hidden'] = (bool)($user1['hidden'] ?? false);
  $viewData['users1'][$i]['onhold'] = (bool)($user1['onhold'] ?? false);
  $viewData['users1'][$i]['verify'] = (bool)($user1['verify'] ?? false);
  $viewData['users1'][$i]['created'] = $user1['created'] ?? DEFAULT_TIMESTAMP;
  $viewData['users1'][$i]['last_login'] = $user1['last_login'] ?? DEFAULT_TIMESTAMP;
  $i++;
}

// Preload avatars and secrets for active users to avoid N+1 in view
$activeUsernames = array_column($users, 'username');
$avatars = ['default' => 'default_male.png']; // fallback
$secrets = [];
if (!empty($activeUsernames)) {
  $placeholders = str_repeat('?,', count($activeUsernames) - 1) . '?';
  $query = $DB->db->prepare("
    SELECT username, `option`, value
    FROM {$CONF['db_table_user_option']}
    WHERE `option` IN ('avatar', 'secret') AND username IN ($placeholders)
  ");
  $query->execute($activeUsernames);
  while ($row = $query->fetch()) {
    if ($row['option'] === 'avatar') {
      $avatars[$row['username']] = $row['value'] ?: 'default_male.png';
    } elseif ($row['option'] === 'secret') {
      $secrets[$row['username']] = !empty($row['value']);
    }
  }
}
$viewData['avatars'] = $avatars;
$viewData['secrets'] = $secrets;

// Same for archived users
$archivedUsernames = array_column($users1, 'username');
$archivedAvatars = ['default' => 'default_male.png'];
$archivedSecrets = [];
if (!empty($archivedUsernames)) {
  $placeholders = str_repeat('?,', count($archivedUsernames) - 1) . '?';
  $query = $DB->db->prepare("
    SELECT username, `option`, value
    FROM {$CONF['db_table_archive_user_option']}
    WHERE `option` IN ('avatar', 'secret') AND username IN ($placeholders)
  ");
  $query->execute($archivedUsernames);
  while ($row = $query->fetch()) {
    if ($row['option'] === 'avatar') {
      $archivedAvatars[$row['username']] = $row['value'] ?: 'default_male.png';
    } elseif ($row['option'] === 'secret') {
      $archivedSecrets[$row['username']] = !empty($row['value']);
    }
  }
}
$viewData['archived_avatars'] = $archivedAvatars;
$viewData['archived_secrets'] = $archivedSecrets;

$viewData['groups'] = $G->getAll();
$viewData['roles'] = $allRoles; // Already preloaded

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
