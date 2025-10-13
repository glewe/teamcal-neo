<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Model Helper Functions
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

//-----------------------------------------------------------------------------
/**
 * Checks whether an absence type is valid for a given user based on his
 * group memberships
 *
 * @param string $absid Absence ID
 * @param string $username Username
 *
 * @return boolean True or False indicating success
 */
function absenceIsValidForUser(string $absid, string $username): bool {
  global $AG, $UG;

  try {
    //
    // Input validation
    //
    if (empty($absid)) {
      return false;
    }

    //
    // Public access check
    //
    if (empty($username) && isAllowed('calendaredit')) {
      return true;
    }

    //
    // Get all groups for the given user and check if absence is assigned to any
    //
    $userGroups = $UG->getAllForUser($username);

    foreach ($userGroups as $group) {
      if ($AG->isAssigned($absid, $group['groupid'])) {
        return true;
      }
    }

    return false;
  } catch (Exception $e) {
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Archives a user and all related records
 *
 * @param string $username Username to archive
 */
function archiveUser(string $username): bool {
  global $AL, $D, $L, $LOG, $T, $U, $UMSG, $UG, $UO;
  //
  // Do not archive if username exists in any of the archive table
  //
  if (
    $U->exists($username, true) ||
    $UG->exists($username, true) ||
    $UO->exists($username, true) ||
    $T->exists($username, true) ||
    $D->exists($username, true) ||
    $AL->exists($username, true) ||
    $UMSG->exists($username, true)
  ) {
    return false;
  }
  //
  // Get fullname for log
  //
  $U->findByName($username);
  $fullname = trim($U->firstname . " " . $U->lastname);
  //
  // Archive user
  // Archive memberships
  // Archive options
  // Archive templates
  // Archive daynotes
  // Archive allowances
  // Archive messages
  //
  $U->archive($username);
  $UG->archive($username);
  $UO->archive($username);
  $T->archive($username);
  $D->archive($username);
  $AL->archive($username);
  $UMSG->archive($username);
  //
  // Delete user from active tables
  //
  deleteUser($username, false, false);
  //
  // Log this event
  //
  $LOG->logEvent("logUser", $L->checkLogin(), "log_user_archived", $fullname . " (" . $username . ")");
  return true;
}

//-----------------------------------------------------------------------------
/**
 * Deletes all orphaned announcements, meaning those announcements that are
 * not assigned to any user.
 */
function deleteOrphanedMessages(): void {
  global $MSG, $UMSG;
  $messages = $MSG->getAll();
  foreach ($messages as $msg) {
    if (!count($UMSG->getAllByMsgId($msg['id']))) {
      $MSG->delete($msg['id']);
    }
  }
}

//-----------------------------------------------------------------------------
/**
 * Deletes a user and all related records
 *
 * @param string $deluser User to delete
 * @param boolean $fromArchive Flag whether to delete from archive tables
 * @param boolean $sendNotifications Flag whether to send notifications
 */
function deleteUser(string $username, bool $fromArchive = false, bool $sendNotifications = true): void {
  global $AL, $AV, $D, $L, $LOG, $T, $U, $UMSG, $UG, $UO;
  //
  // Get fullname for log
  //
  $U->findByName($username);
  $fullname = trim($U->firstname . " " . $U->lastname);
  //
  // Delete user
  // Delete memberships
  // Delete options
  // Delete messages
  // Delete avatars
  // Delete month templates
  // Delete allowances records
  //
  $U->deleteByName($username, $fromArchive);
  $UG->deleteByUser($username, $fromArchive);
  $UO->deleteByUser($username, $fromArchive);
  $UMSG->deleteByUser($username, $fromArchive);
  if ($fromArchive) {
    $AV->delete($username, $UO->read($username, 'avatar'));
  }
  $T->deleteByUser($username, $fromArchive);
  $D->deleteByUser($username, $fromArchive);
  $AL->deleteByUser($username, $fromArchive);
  //
  // Send notification e-mails
  //
  if ($sendNotifications) {
    sendUserEventNotifications("deleted", $username, $U->firstname, $U->lastname);
  }
  //
  // Log this event
  //
  if ($fromArchive) {
    $LOG->logEvent("logUser", $L->checkLogin(), "log_user_archived_deleted", $fullname . " (" . $username . ")");
  } else {
    $LOG->logEvent("logUser", $L->checkLogin(), "log_user_deleted", $fullname . " (" . $username . ")");
  }
}

//-----------------------------------------------------------------------------
/**
 * Imports users from a CSV file into the database
 *
 * @param string $file CSV file
 * @param boolean $lock Flag indicating whether to lock the user accounts or not
 * @param boolean $hide Flag indicating whether to hide the user accounts or not
 *
 * @return boolean Success flag
 */
function importUsersFromCSV(string $file, bool $lock = true, bool $hide = true): bool {
  //
  // The expected columns are:
  // 0        1         2        3
  // username|firstname|lastname|email
  //
  global $LANG, $L, $LOG;
  $UI = new Users;
  $UOI = new UserOption;
  $fpointer = fopen($file, "r");

  if ($fpointer) {
    while ($arr = fgetcsv($fpointer, 10 * 1024, ";")) {
      if (is_array($arr) && !empty($arr)) {
        if (count($arr) != 4) {
          unset($arr);
          fclose($fpointer);
          return false;
        } else {
          $trimmedUsername = trim($arr[0]);
          $trimmedFirstname = trim($arr[1]);
          $trimmedLastname = trim($arr[2]);
          $trimmedEmail = trim($arr[3]);

          // Validate all fields with length and security checks
          if (
            !$UI->findByName($trimmedUsername) &&
            $trimmedUsername != "admin" &&
            preg_match('/^[a-zA-Z0-9]*$/', $trimmedUsername) &&
            strlen($trimmedUsername) >= 2 && strlen($trimmedUsername) <= 40 &&
            !empty($trimmedFirstname) && strlen($trimmedFirstname) <= 80 &&
            !empty($trimmedLastname) && strlen($trimmedLastname) <= 80 &&
            filter_var($trimmedEmail, FILTER_VALIDATE_EMAIL) && strlen($trimmedEmail) <= 100
          ) {
            $UI->username = $trimmedUsername;
            $UI->firstname = $trimmedFirstname;
            $UI->lastname = $trimmedLastname;
            $UI->email = $trimmedEmail;
            // Set default password - users should change this on first login
            // Consider generating random passwords for better security
            $UI->password = password_hash("password", PASSWORD_DEFAULT);
            $UI->role = '2'; // Default role "User"
            $UI->locked = '0';
            if ($lock) {
              $UI->locked = '1';
            }
            $UI->hidden = '0';
            if ($hide) {
              $UI->hidden = '1';
            }
            $UI->onhold = '0';
            $UI->verify = '0';
            $UI->bad_logins = '0';
            $UI->grace_start = DEFAULT_TIMESTAMP;
            $UI->last_login = DEFAULT_TIMESTAMP;
            $UI->created = date('YmdHis');
            $UI->create();
            //
            // Default user options
            //
            $UOI->save($UI->username, 'gender', 'male');
            $UOI->save($UI->username, 'avatar', 'default_male.png');
            $UOI->save($UI->username, 'language', 'default');
            $fullname = $UI->firstname . " " . $UI->lastname;
            $LOG->logEvent("logUser", $L->checkLogin(), "log_csv_import", $UI->username . " (" . $fullname . ")");
          }
        }
      }
    }
    unset($arr);
    fclose($fpointer);
    return true;
  }
  return false;
}

//-----------------------------------------------------------------------------
/**
 * Checks whether a user is authorized in the active permission scheme.
 *
 * @param string $permission The permission to check.
 *
 * @return boolean True if the user is allowed, false otherwise.
 * @global object $UL User login object.
 * @global object $UO User options object.
 * @global array $permissions Array of permissions.
 *
 * @global bool True if allowed, false if not
 */
function isAllowed(string $permission = ''): bool {
  global $C, $UL, $UO, $permissions;
  if (L_USER) {
    //
    // Someone is logged in.
    // First, check if 2FA required and user hasn't done it yet.
    //
    if (L_USER != 'admin' && $C->read('forceTfa') && !$UO->read(L_USER, 'secret')) {
      return false;
    }
    //
    // Check permission by role.
    //
    $UL->findByName(L_USER);
    return in_array(['permission' => $permission, 'role' => $UL->role], $permissions);
  } else {
    //
    // It's a public user.
    //
    return in_array(['permission' => $permission, 'role' => 3], $permissions);
  }
}

//-----------------------------------------------------------------------------
/**
 * Restores a user and all related records from archive
 *
 * @param string $username Username to restore
 *
 * @return boolean True or False indicating success
 */
function restoreUser(string $username): bool {
  global $AL, $D, $L, $LOG, $T, $U, $UMSG, $UG, $UO;
  //
  // Do not restore if username exists in any of the active tables
  //
  if (
    $U->exists($username) ||
    $UG->exists($username) ||
    $UO->exists($username) ||
    $T->exists($username) ||
    $D->exists($username) ||
    $AL->exists($username) ||
    $UMSG->exists($username)
  ) {
    return false;
  }
  //
  // Get fullname for log
  //
  $U->findByName($username);
  $fullname = trim($U->firstname . " " . $U->lastname);
  //
  // Restore user
  // Restore memberships
  // Restore options
  // Restore templates
  // Restore daynotes
  // Restore allowances
  // Restore announcements
  //
  $U->restore($username);
  $UG->restore($username);
  $UO->restore($username);
  $T->restore($username);
  $D->restore($username);
  $AL->restore($username);
  $UMSG->restore($username);
  //
  // Delete user from archive tables
  //
  deleteUser($username, true, false);
  //
  // Log this event
  //
  $LOG->logEvent("logUser", $L->checkLogin(), "log_user_restored", $fullname . " (" . $username . ")");
  return true;
}
