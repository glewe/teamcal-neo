<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;
use App\Models\UserModel;
use DateTime;

/**
 * User List Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class UserListController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    // Check Permission
    if (!isAllowed($this->CONF['controllers']['userlist']->permission)) {
      $this->renderAlert(
        'warning',
        (string) ($this->LANG['alert_alert_title'] ?? 'Alert'),
        (string) ($this->LANG['alert_not_allowed_subject'] ?? 'Access Denied'),
        (string) ($this->LANG['alert_not_allowed_text'] ?? 'You do not have permission to access this page.'),
        (string) ($this->LANG['alert_not_allowed_help'] ?? '')
      );
      return;
    }

    // Check License (Randomly)
    $date    = new DateTime();
    $weekday = $date->format('N');
    if ($weekday === (string) random_int(1, 7)) {
      $alertData        = [];
      $showAlert        = false;
      $licExpiryWarning = $this->allConfig['licExpiryWarning'];
      $LIC              = new LicenseModel();
      $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);
    }

    $viewData                = [];
    $viewData['searchGroup'] = 'All';
    $viewData['searchRole']  = 'All';

    $alertData = [];
    $showAlert = false;

    // Process Form
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      // CSRF Check
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        \App\Helpers\LanguageLoader::loadForController('alert');
        $this->renderAlert(
          'warning',
          (string) ($this->LANG['alert_alert_title'] ?? 'Alert'),
          (string) ($this->LANG['alert_csrf_invalid_subject'] ?? 'Security Token Invalid'),
          (string) ($this->LANG['alert_csrf_invalid_text'] ?? 'The security token is missing or invalid.'),
          (string) ($this->LANG['alert_csrf_invalid_help'] ?? 'Please reload the page and try again.')
        );
        return;
      }

      // Handle Actions
      if (isset($_POST['btn_userActivate'])) {
        $this->handleActivate($showAlert, $alertData);
      }
      elseif (isset($_POST['btn_userArchive'])) {
        $this->handleArchive($showAlert, $alertData);
      }
      elseif (isset($_POST['btn_profileRestore'])) {
        $this->handleRestore($showAlert, $alertData);
      }
      elseif (isset($_POST['btn_profileDelete'])) {
        $this->handleDelete($showAlert, $alertData);
      }
      elseif (isset($_POST['btn_profileDeleteArchived'])) {
        $this->handleDeleteArchived($showAlert, $alertData);
      }
      elseif (isset($_POST['btn_userResetPassword'])) {
        $this->handleResetPassword($showAlert, $alertData);
      }
      elseif (isset($_POST['btn_userRemoveSecret'])) {
        $this->handleRemoveSecret($showAlert, $alertData);
      }

      // Renew CSRF
      if (isset($_SESSION)) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
    }

    // Prepare View
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];
    $viewData['alertData']  = $alertData;
    $viewData['showAlert']  = $showAlert;

    // Preload Roles
    $allRoles = $this->roleModel->getAll();
    $roleMap  = [];
    foreach ($allRoles as $role) {
      $roleMap[$role['id']] = [
        'name'  => $role['name'],
        'color' => $role['color']
      ];
    }

    // Get Active Users
    $users = $this->userModel->getAll('lastname', 'firstname', 'ASC', false, true);

    if (isset($_POST['btn_filter'])) {

      if (isset($_POST['sel_searchGroup']) && $_POST['sel_searchGroup'] !== "All") {
        $searchGroup             = sanitize($_POST['sel_searchGroup']);
        $viewData['searchGroup'] = $searchGroup;
        $groupMembers            = $this->userGroupModel->getAllforGroup((string) $searchGroup);
        $groupUsernames          = array_column($groupMembers, 'username');
        $users                   = array_filter($users, function ($user) use ($groupUsernames) {
          return in_array($user['username'], $groupUsernames);
        });
      }

      if (isset($_POST['sel_searchRole']) && $_POST['sel_searchRole'] !== "All") {
        $searchRole             = sanitize($_POST['sel_searchRole']);
        $viewData['searchRole'] = $searchRole;
        $users                  = array_filter($users, function ($user) use ($searchRole) {
          return $user['role_id'] == $searchRole;
        });
      }
    }

    // Prepare Users Data for View
    $viewData['users'] = [];
    $i                 = 0;
    foreach ($users as $user) {
      $viewData['users'][$i]['username']  = $user['username'];
      $firstname                          = trim($user['firstname'] ?? '');
      $lastname                           = trim($user['lastname'] ?? '');
      $viewData['users'][$i]['dispname']  = ($firstname !== "") ? $lastname . ", " . $firstname : ($lastname ?: $user['username']);
      $viewData['users'][$i]['dispname'] .= ' (' . $user['username'] . ')';

      $roleData                       = $roleMap[$user['role_id']] ?? ['name' => '', 'color' => 'default'];
      $viewData['users'][$i]['role']  = $roleData['name'];
      $viewData['users'][$i]['color'] = $roleData['color'];

      $viewData['users'][$i]['locked']     = (bool) ($user['locked'] ?? false);
      $viewData['users'][$i]['hidden']     = (bool) ($user['hidden'] ?? false);
      $viewData['users'][$i]['onhold']     = (bool) ($user['onhold'] ?? false);
      $viewData['users'][$i]['verify']     = (bool) ($user['verify'] ?? false);
      $viewData['users'][$i]['created']    = $user['created'] ?? DEFAULT_TIMESTAMP;
      $viewData['users'][$i]['last_login'] = $user['last_login'] ?? DEFAULT_TIMESTAMP;
      $i++;
    }

    // Get Archived Users
    $viewData['users1'] = [];
    $i                  = 0;
    $users1             = $this->userModel->getAll('lastname', 'firstname', 'ASC', true);
    foreach ($users1 as $user1) {
      $viewData['users1'][$i]['username']  = $user1['username'];
      $firstname                           = trim($user1['firstname'] ?? '');
      $lastname                            = trim($user1['lastname'] ?? '');
      $viewData['users1'][$i]['dispname']  = ($firstname !== "") ? $lastname . ", " . $firstname : ($lastname ?: $user1['username']);
      $viewData['users1'][$i]['dispname'] .= ' (' . $user1['username'] . ')';

      $roleData                        = $roleMap[$user1['role_id']] ?? ['name' => '', 'color' => 'default'];
      $viewData['users1'][$i]['role']  = $roleData['name'];
      $viewData['users1'][$i]['color'] = $roleData['color'];

      $viewData['users1'][$i]['locked']     = (bool) ($user1['locked'] ?? false);
      $viewData['users1'][$i]['hidden']     = (bool) ($user1['hidden'] ?? false);
      $viewData['users1'][$i]['onhold']     = (bool) ($user1['onhold'] ?? false);
      $viewData['users1'][$i]['verify']     = (bool) ($user1['verify'] ?? false);
      $viewData['users1'][$i]['created']    = $user1['created'] ?? DEFAULT_TIMESTAMP;
      $viewData['users1'][$i]['last_login'] = $user1['last_login'] ?? DEFAULT_TIMESTAMP;
      $i++;
    }

    // Preload Avatars/Secrets
    $this->preloadOptions($users, $viewData, 'avatars', 'secrets', false);
    $this->preloadOptions($users1, $viewData, 'archived_avatars', 'archived_secrets', true);

    $viewData['groups'] = $this->groupModel->getAll();
    $viewData['roles']  = $allRoles;

    $this->render('userlist', $viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Activates selected users.
   *
   * @param bool                 $showAlert  Reference to show alert flag
   * @param array<string, mixed> $alertData  Reference to alert data array
   *
   * @return void
   */
  private function handleActivate(bool &$showAlert, array &$alertData): void {
    $selected_users = $_POST['chk_userActive'] ?? [];
    foreach ($selected_users as $su => $value) {
      $this->userModel->activate($value);
    }
    $showAlert            = true;
    $alertData['type']    = 'success';
    $alertData['title']   = $this->LANG['alert_success_title'];
    $alertData['subject'] = $this->LANG['btn_activate_selected'];
    $alertData['text']    = $this->LANG['userlist_alert_activate_selected_users'];
    $alertData['help']    = '';
  }

  //---------------------------------------------------------------------------
  /**
   * Archives selected users.
   *
   * @param bool                 $showAlert  Reference to show alert flag
   * @param array<string, mixed> $alertData  Reference to alert data array
   *
   * @return void
   */
  private function handleArchive(bool &$showAlert, array &$alertData): void {
    $selected_users = $_POST['chk_userActive'] ?? [];
    $exists         = false;
    foreach ($selected_users as $su => $value) {
      if (!$this->userService->archiveUser($value, (string) $this->userLoggedIn->username)) {
        $exists = true;
      }
    }
    $showAlert = true;
    if (!$exists) {
      $alertData['type']    = 'success';
      $alertData['title']   = $this->LANG['alert_success_title'];
      $alertData['subject'] = $this->LANG['btn_archive_selected'];
      $alertData['text']    = $this->LANG['userlist_alert_archive_selected_users'];
    }
    else {
      $alertData['type']    = 'danger';
      $alertData['title']   = $this->LANG['alert_danger_title'];
      $alertData['subject'] = $this->LANG['btn_archive_selected'];
      $alertData['text']    = $this->LANG['userlist_alert_archive_selected_users_failed'];
    }
    $alertData['help'] = '';
  }

  //---------------------------------------------------------------------------
  /**
   * Restores selected users from archive.
   *
   * @param bool                 $showAlert  Reference to show alert flag
   * @param array<string, mixed> $alertData  Reference to alert data array
   *
   * @return void
   */
  private function handleRestore(bool &$showAlert, array &$alertData): void {
    $selected_users = $_POST['chk_userArchived'] ?? [];
    $exists         = false;
    foreach ($selected_users as $su => $value) {
      if (!$this->userService->restoreUser($value, (string) $this->userLoggedIn->username)) {
        $exists = true;
      }
    }
    $showAlert = true;
    if (!$exists) {
      $alertData['type']    = 'success';
      $alertData['title']   = $this->LANG['alert_success_title'];
      $alertData['subject'] = $this->LANG['btn_restore_selected'];
      $alertData['text']    = $this->LANG['userlist_alert_restore_selected_users'];
    }
    else {
      $alertData['type']    = 'danger';
      $alertData['title']   = $this->LANG['alert_danger_title'];
      $alertData['subject'] = $this->LANG['btn_restore_selected'];
      $alertData['text']    = $this->LANG['userlist_alert_restore_selected_users_failed'];
    }
    $alertData['help'] = '';
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes selected users.
   *
   * @param bool                 $showAlert  Reference to show alert flag
   * @param array<string, mixed> $alertData  Reference to alert data array
   *
   * @return void
   */
  private function handleDelete(bool &$showAlert, array &$alertData): void {
    $selected_users = $_POST['chk_userActive'] ?? [];
    foreach ($selected_users as $su => $value) {
      $this->userService->deleteUser($value, false, (bool) ($this->allConfig['emailNotifications'] ?? false), (string) $this->userLoggedIn->username);
    }
    $showAlert            = true;
    $alertData['type']    = 'success';
    $alertData['title']   = $this->LANG['alert_success_title'];
    $alertData['subject'] = $this->LANG['btn_delete_selected'];
    $alertData['text']    = $this->LANG['userlist_alert_delete_selected_users'];
    $alertData['help']    = '';
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes selected archived users.
   *
   * @param bool                 $showAlert  Reference to show alert flag
   * @param array<string, mixed> $alertData  Reference to alert data array
   *
   * @return void
   */
  private function handleDeleteArchived(bool &$showAlert, array &$alertData): void {
    $selected_users = $_POST['chk_userArchived'] ?? [];
    foreach ($selected_users as $su => $value) {
      $this->userService->deleteUser($value, true, false, (string) $this->userLoggedIn->username);
    }
    $showAlert            = true;
    $alertData['type']    = 'success';
    $alertData['title']   = $this->LANG['alert_success_title'];
    $alertData['subject'] = $this->LANG['btn_delete_selected'];
    $alertData['text']    = $this->LANG['userlist_alert_delete_selected_users'];
    $alertData['help']    = '';
  }

  //---------------------------------------------------------------------------
  /**
   * Resets password for selected users.
   *
   * @param bool                 $showAlert  Reference to show alert flag
   * @param array<string, mixed> $alertData  Reference to alert data array
   *
   * @return void
   */
  private function handleResetPassword(bool &$showAlert, array &$alertData): void {
    $selected_users = $_POST['chk_userActive'] ?? [];
    foreach ($selected_users as $su => $value) {
      $this->userModel->findByName($value);
      $token          = bin2hex(random_bytes(32));
      $expiryDateTime = date('YmdHis', strtotime(date('YmdHis') . ' +1 day'));
      $this->userOptionModel->save($this->userModel->username, 'pwdToken', $token);
      $this->userOptionModel->save($this->userModel->username, 'pwdTokenExpiry', $expiryDateTime);
      sendPasswordResetMail($this->userModel->email, $this->userModel->username, $this->userModel->lastname, $this->userModel->firstname, $token);
      $this->logModel->logEvent("logUser", $this->userLoggedIn->username, "log_user_pwd_reset", $this->userModel->username);
    }
    $showAlert            = true;
    $alertData['type']    = 'success';
    $alertData['title']   = $this->LANG['alert_success_title'];
    $alertData['subject'] = $this->LANG['btn_reset_password_selected'];
    $alertData['text']    = $this->LANG['userlist_alert_reset_password_selected'];
    $alertData['help']    = '';
  }

  //---------------------------------------------------------------------------
  /**
   * Removes 2FA secret for selected users.
   *
   * @param bool                 $showAlert  Reference to show alert flag
   * @param array<string, mixed> $alertData  Reference to alert data array
   *
   * @return void
   */
  private function handleRemoveSecret(bool &$showAlert, array &$alertData): void {
    $selected_users = $_POST['chk_userActive'] ?? [];
    foreach ($selected_users as $su => $value) {
      $this->userModel->findByName($value);
      $this->userOptionModel->deleteUserOption($this->userModel->username, 'secret');
      $this->logModel->logEvent("logUser", $this->userLoggedIn->username, "log_user_2fa_removed", $this->userModel->username);
    }
    $showAlert            = true;
    $alertData['type']    = 'success';
    $alertData['title']   = $this->LANG['alert_success_title'];
    $alertData['subject'] = $this->LANG['btn_remove_secret_selected'];
    $alertData['text']    = $this->LANG['userlist_alert_remove_secret_selected'];
    $alertData['help']    = '';
  }

  //---------------------------------------------------------------------------
  /**
   * Preloads user options (avatar, secret) for a list of users.
   *
   * @param array<int, array<string, mixed>> $users     Array of users
   * @param array<string, mixed>             $viewData  Reference to view data array
   * @param string                           $avatarKey Key for avatar data in viewData
   * @param string                           $secretKey Key for secret data in viewData
   * @param bool                             $isArchive Whether querying archived tables
   *
   * @return void
   */
  private function preloadOptions(array $users, array &$viewData, string $avatarKey, string $secretKey, bool $isArchive): void {
    $usernames = array_column($users, 'username');
    $avatars   = ['default' => 'default_male.png'];
    $secrets   = [];

    foreach ($this->userOptionModel->readForUsers($usernames, ['avatar', 'secret'], $isArchive) as $username => $opts) {
      if (isset($opts['avatar'])) {
        $avatars[$username] = $opts['avatar'] ?: 'default_male.png';
      }
      if (isset($opts['secret'])) {
        $secrets[$username] = !empty($opts['secret']);
      }
    }
    $viewData[$avatarKey] = $avatars;
    $viewData[$secretKey] = $secrets;
  }
}
