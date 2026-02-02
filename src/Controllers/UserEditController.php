<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UserModel;
use App\Models\UploadModel;

/**
 * User Edit Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class UserEditController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    // Check URL Parameters
    $UP      = new UserModel($this->DB->db, $this->CONF);
    $profile = '';
    if (isset($_GET['profile'])) {
      $profile = sanitize($_GET['profile']);
      if (!$UP->findByName($profile)) {
        $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
        return;
      }
    }
    else {
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
      return;
    }

    // Check Permission
    $allowed = false;
    if ($this->UL->username == $profile || isAllowed($this->CONF['controllers']['useredit']->permission)) {
      $allowed = true;
    }

    if (!$allowed) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $viewData               = [];
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];

    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];
    $absences   = $this->A->getAll();
    if ($this->UL->hasRole($this->UL->username, '1')) {
      $events = ['notifyNone', 'notifyAbsenceEvents', 'notifyCalendarEvents', 'notifyGroupEvents', 'notifyHolidayEvents', 'notifyMonthEvents', 'notifyRoleEvents', 'notifyUserEvents', 'notifyUserCalEvents', 'notifyUserCalEventsOwn'];
    }
    else {
      $events = ['notifyNone', 'notifyAbsenceEvents', 'notifyCalendarEvents', 'notifyHolidayEvents', 'notifyMonthEvents', 'notifyUserCalEvents', 'notifyUserCalEventsOwn'];
    }

    /** @var array<string, string> $alertData */
    $alertData = [];
    $showAlert = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;
      if (isset($_POST['btn_profileUpdate'])) {
        // Validation logic...
        if (!formInputValid('txt_lastname', 'alpha_numeric_dash_blank_dot'))
          $inputError = true;
        if (!formInputValid('txt_firstname', 'alpha_numeric_dash_blank_dot'))
          $inputError = true;
        if (!formInputValid('txt_title', 'alpha_numeric_dash_blank_dot'))
          $inputError = true;
        if (!formInputValid('txt_position', 'alpha_numeric_dash_blank'))
          $inputError = true;
        if (!formInputValid('txt_email', 'required|email'))
          $inputError = true;
        if (!formInputValid('txt_orderkey', 'alpha_numeric_dash_blank_dot'))
          $inputError = true;

        if ((isset($_POST['txt_password']) && strlen($_POST['txt_password'])) || (isset($_POST['txt_password2']) && strlen($_POST['txt_password2']))) {
          if (!formInputValid('txt_password', 'pwd' . $this->allConfig['pwdStrength']))
            $inputError = true;
          if (!formInputValid('txt_password2', 'required|pwd' . $this->allConfig['pwdStrength']))
            $inputError = true;
          if (!formInputValid('txt_password2', 'match', 'txt_password')) {
            $inputAlert['password2'] = sprintf($this->LANG['alert_input_match'], $this->LANG['profile_password2'], $this->LANG['profile_password']);
            $inputError              = true;
          }
        }
        // ... more validation ...
        if (!formInputValid('txt_phone', 'phone_number'))
          $inputError = true;
        if (!formInputValid('txt_mobilephone', 'phone_number'))
          $inputError = true;
        if (!formInputValid('txt_google', 'alpha_numeric_dash'))
          $inputError = true;
        if (!formInputValid('txt_linkedin', 'alpha_numeric_dash'))
          $inputError = true;
        if (!formInputValid('txt_skype', 'alpha_numeric_dash'))
          $inputError = true;
        if (!formInputValid('txt_twitter', 'alpha_numeric_dash'))
          $inputError = true;
        if (!formInputValid('txt_custom1', 'alpha_numeric_dash_blank_special'))
          $inputError = true;
        if (!formInputValid('txt_custom2', 'alpha_numeric_dash_blank_special'))
          $inputError = true;
        if (!formInputValid('txt_custom3', 'alpha_numeric_dash_blank_special'))
          $inputError = true;
        if (!formInputValid('txt_custom4', 'alpha_numeric_dash_blank_special'))
          $inputError = true;
        if (!formInputValid('txt_custom5', 'alpha_numeric_dash_blank_special'))
          $inputError = true;
        if (!formInputValid('txt_showMonths', 'numeric'))
          $inputError = true;
      }

      if (!$inputError) {
        if (isset($_POST['btn_profileUpdate'])) {
          $this->handleUpdate($UP, $profile, $absences, $events, $showAlert, $alertData);
        }
        elseif (isset($_POST['btn_profileArchive'])) {
          $this->handleArchive($profile, $showAlert, $alertData);
        }
        elseif (isset($_POST['btn_profileDelete'])) {
          $this->handleDelete($profile, $showAlert, $alertData);
        }
        elseif (isset($_POST['btn_uploadAvatar'])) {
          $this->handleUpload($profile, $UP, $showAlert, $alertData);
        }
        elseif (isset($_POST['btn_reset'])) {
          $this->handleReset($profile, $UP, $showAlert, $alertData);
        }

        if (isset($_SESSION)) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
      }
      else {
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_input'];
        $alertData['text']    = $this->LANG['profile_alert_save_failed'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    // Prepare View Data (massive block)
    $viewData['profile']  = $profile;
    $viewData['fullname'] = $UP->firstname . ' ' . $UP->lastname . ' (' . $UP->username . ')';
    $viewData['avatar']   = ($this->UO->read($profile, 'avatar')) ? $this->UO->read($profile, 'avatar') : 'default_' . $this->UO->read($profile, 'gender') . '.png';
    if (!file_exists(APP_AVATAR_DIR . $viewData['avatar'])) {
      $viewData['avatar'] = 'default_' . $this->UO->read($profile, 'gender') . '.png';
      $this->UO->save($profile, 'avatar', $viewData['avatar']);
    }
    $viewData['avatar_maxsize'] = $this->CONF['avatarMaxsize'];
    $viewData['avatar_formats'] = implode(', ', $this->CONF['avatarExtensions']);
    $viewData['showingroups']   = $this->UO->read($profile, 'showingroups');
    $viewData['notifycalgroup'] = $this->UO->read($profile, 'notifycalgroup');
    $groups                     = $this->G->getAll();

    // Permissions
    $viewData['perms'] = [
      'userabsences'      => isAllowed('userabsences'),
      'useraccount'       => isAllowed('useraccount'),
      'useravatar'        => isAllowed('useravatar'),
      'usercustom'        => isAllowed('usercustom'),
      'usergroups'        => isAllowed('usergroups'),
      'usernotifications' => isAllowed('usernotifications'),
      'useroptions'       => isAllowed('useroptions'),
      'userallowance'     => isAllowed('userallowance'),
      'groupmemberships'  => isAllowed('groupmemberships'),
    ];

    // Personal
    $viewData['personal'] = [
      ['prefix' => 'profile', 'name' => 'username', 'type' => 'text', 'placeholder' => '', 'value' => $UP->username, 'maxlength' => '80', 'disabled' => true, 'mandatory' => true, 'error' => ($inputAlert['username'] ?? '')],
      ['prefix' => 'profile', 'name' => 'lastname', 'type' => 'text', 'placeholder' => '', 'value' => $UP->lastname, 'maxlength' => '80', 'error' => ($inputAlert['lastname'] ?? '')],
      ['prefix' => 'profile', 'name' => 'firstname', 'type' => 'text', 'placeholder' => '', 'value' => $UP->firstname, 'maxlength' => '80', 'error' => ($inputAlert['firstname'] ?? '')],
      ['prefix' => 'profile', 'name' => 'title', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'title'), 'maxlength' => '80', 'error' => ($inputAlert['title'] ?? '')],
      ['prefix' => 'profile', 'name' => 'position', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'position'), 'maxlength' => '80', 'error' => ($inputAlert['position'] ?? '')],
      ['prefix' => 'profile', 'name' => 'id', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'id'), 'maxlength' => '80', 'error' => ($inputAlert['id'] ?? '')],
      ['prefix' => 'profile', 'name' => 'gender', 'type' => 'radio', 'values' => ['male', 'female'], 'value' => $this->UO->read($profile, 'gender')],
      ['prefix' => 'profile', 'name' => 'orderkey', 'type' => 'text', 'placeholder' => '', 'value' => $UP->order_key, 'maxlength' => '80', 'error' => ($inputAlert['orderkey'] ?? '')],
    ];

    // Contact
    $viewData['contact'] = [
      ['prefix' => 'profile', 'name' => 'email', 'type' => 'text', 'placeholder' => '', 'value' => $UP->email, 'maxlength' => '80', 'mandatory' => true, 'error' => ($inputAlert['email'] ?? '')],
      ['prefix' => 'profile', 'name' => 'phone', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'phone'), 'maxlength' => '80', 'error' => ($inputAlert['phone'] ?? '')],
      ['prefix' => 'profile', 'name' => 'mobilephone', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'mobile'), 'maxlength' => '80', 'error' => ($inputAlert['mobile'] ?? '')],
      ['prefix' => 'profile', 'name' => 'facebook', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'facebook'), 'maxlength' => '80'],
      ['prefix' => 'profile', 'name' => 'instagram', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'instagram'), 'maxlength' => '80'],
      ['prefix' => 'profile', 'name' => 'linkedin', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'linkedin'), 'maxlength' => '80'],
      ['prefix' => 'profile', 'name' => 'tiktok', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'tiktok'), 'maxlength' => '80'],
      ['prefix' => 'profile', 'name' => 'twitter', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'twitter'), 'maxlength' => '80'],
      ['prefix' => 'profile', 'name' => 'xing', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'xing'), 'maxlength' => '80'],
    ];

    // Options
    $selected = false;
    if (!$this->UO->read($viewData['profile'], 'calfilterGroup') || $this->UO->read($viewData['profile'], 'calfilterGroup') == 'All') {
      $selected = true;
    }
    $viewData['calfilterGroups'][] = ['val' => 'all', 'name' => $this->LANG['all'], 'selected' => $selected];
    foreach ($groups as $group) {
      if ($this->UG->isMemberOrManagerOfGroup($viewData['profile'], (string) $group['id'])) {
        $selected = false;
        if ($this->UO->read($viewData['profile'], 'calfilterGroup') == $group['id']) {
          $selected = true;
        }
        $viewData['calfilterGroups'][] = ['val' => $group['id'], 'name' => $group['name'], 'selected' => $selected];
      }
    }

    $viewData['languageList'][] = ['val' => "default", 'name' => "Default", 'selected' => ($this->UO->read($profile, 'language') == "default")];
    foreach ($this->appLanguages as $appLang) {
      $viewData['languageList'][] = ['val' => $appLang, 'name' => ucwords($appLang), 'selected' => ($this->UO->read($profile, 'language') == $appLang)];
    }

    $regions = $this->R->getAll();
    if (!$this->UO->read($profile, 'region')) {
      $this->UO->save($profile, 'region', '1');
    }
    $viewData['regionList'][] = ['val' => "1", 'name' => "Default", 'selected' => ($this->UO->read($profile, 'region') == "1")];
    foreach ($regions as $region) {
      if ($region['id'] != 1) {
        $viewData['regionList'][] = ['val' => $region['id'], 'name' => $region['name'], 'selected' => ($this->UO->read($profile, 'region') == $region['id'])];
      }
    }


    $viewData['defaultMenuList'] = [
      ['val' => 'default', 'name' => $this->LANG['profile_defaultMenu_default'], 'selected' => ($this->UO->read($profile, 'defaultMenu') == 'default')],
      ['val' => 'navbar', 'name' => $this->LANG['profile_defaultMenu_navbar'], 'selected' => ($this->UO->read($profile, 'defaultMenu') == 'navbar')],
      ['val' => 'sidebar', 'name' => $this->LANG['profile_defaultMenu_sidebar'], 'selected' => ($this->UO->read($profile, 'defaultMenu') == 'sidebar')],
    ];

    $viewData['options'] = [
      ['prefix' => 'profile', 'name' => 'language', 'type' => 'list', 'values' => $viewData['languageList']],
      ['prefix' => 'profile', 'name' => 'showMonths', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'showMonths'), 'maxlength' => '2', 'error' => ($inputAlert['showMonths'] ?? '')],
      ['prefix' => 'profile', 'name' => 'calfilterGroup', 'type' => 'list', 'values' => $viewData['calfilterGroups']],
      ['prefix' => 'profile', 'name' => 'region', 'type' => 'list', 'values' => $viewData['regionList']],
      ['prefix' => 'profile', 'name' => 'defaultMenu', 'type' => 'list', 'values' => $viewData['defaultMenuList']],
    ];

    // Avatar
    $viewData['avatars'] = getFiles(APP_AVATAR_DIR, $this->CONF['avatarExtensions'], 'is_');

    // Account
    $roles = $this->RO->getAll();
    foreach ($roles as $role) {
      $viewData['roles'][] = ['val' => $role['id'], 'name' => $role['name'], 'selected' => ($UP->getRole($UP->username) == $role['id'])];
    }
    $viewData['account'] = [
      ['prefix' => 'profile', 'name' => 'role', 'type' => 'list', 'values' => $viewData['roles']],
      ['prefix' => 'profile', 'name' => 'locked', 'type' => 'check', 'values' => '', 'value' => $UP->locked],
      ['prefix' => 'profile', 'name' => 'onhold', 'type' => 'check', 'values' => '', 'value' => $UP->onhold],
      ['prefix' => 'profile', 'name' => 'verify', 'type' => 'check', 'values' => '', 'value' => $UP->verify],
      ['prefix' => 'profile', 'name' => 'hidden', 'type' => 'check', 'values' => '', 'value' => $UP->hidden],
    ];

    // Groups
    $viewData['memberships'][] = ['val' => '0', 'name' => $this->LANG['none'], 'selected' => !$this->UG->isGroupMember($viewData['profile'])];
    foreach ($groups as $group) {
      $viewData['memberships'][] = ['val' => $group['id'], 'name' => $group['name'], 'selected' => $this->UG->isMemberOfGroup($viewData['profile'], (string) $group['id'])];
    }
    $viewData['managerships'][] = ['val' => '0', 'name' => $this->LANG['none'], 'selected' => !$this->UG->isGroupManager($viewData['profile'])];
    foreach ($groups as $group) {
      $viewData['managerships'][] = ['val' => $group['id'], 'name' => $group['name'], 'selected' => $this->UG->isGroupManagerOfGroup($viewData['profile'], (string) $group['id'])];
    }
    $viewData['guestships'][] = ['val' => '0', 'name' => $this->LANG['none'], 'selected' => !$this->UG->isGuest($viewData['profile'])];
    foreach ($groups as $group) {
      $viewData['guestships'][] = ['val' => $group['id'], 'name' => $group['name'], 'selected' => $this->UG->isGuestOfGroup($viewData['profile'], (string) $group['id'])];
    }
    $disabled           = !isAllowed("groupmemberships");
    $viewData['groups'] = [
      ['prefix' => 'profile', 'name' => 'memberships', 'type' => 'listmulti', 'values' => $viewData['memberships'], 'disabled' => $disabled],
      ['prefix' => 'profile', 'name' => 'managerships', 'type' => 'listmulti', 'values' => $viewData['managerships'], 'disabled' => $disabled],
      ['prefix' => 'profile', 'name' => 'guestships', 'type' => 'listmulti', 'values' => $viewData['guestships'], 'disabled' => $disabled],
    ];

    // Password
    $pwComment            = $this->LANG['profile_password_comment'] . $this->LANG['password_rules_' . $this->allConfig['pwdStrength']];
    $viewData['password'] = [
      ['prefix' => 'profile', 'name' => 'password', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'error' => ($inputAlert['password'] ?? ''), 'comment' => $pwComment],
      ['prefix' => 'profile', 'name' => 'password2', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'error' => ($inputAlert['password2'] ?? '')],
    ];

    // Allowances
    $countFrom = date('Y') . '0101';
    $countTo   = date('Y') . '1231';
    foreach ($absences as $abs) {
      if ($this->AL->find($viewData['profile'], (string) $abs['id'])) {
        $allowance = $this->AL->allowance;
        $carryover = $this->AL->carryover;
      }
      else {
        if ($abs['allowance']) {
          $allowance = $abs['allowance'];
        }
        else {
          $allowance = 365;
        }
        $carryover           = 0;
        $this->AL->username  = $viewData['profile'];
        $this->AL->absid     = (string) $abs['id'];
        $this->AL->allowance = $allowance;
        $this->AL->carryover = $carryover;
        $this->AL->save();
      }

      $taken = 0;
      if (!$abs['counts_as_present']) {
        $taken = $this->AbsenceService->countAbsence($viewData['profile'], (string) $abs['id'], $countFrom, $countTo, false, false);
      }

      $remainder         = $allowance + $carryover - ($taken * $abs['factor']);
      $viewData['abs'][] = [
        'id'         => $abs['id'],
        'name'       => $abs['name'],
        'icon'       => $abs['icon'],
        'color'      => $abs['color'],
        'bgcolor'    => $abs['bgcolor'],
        'allowance'  => $allowance,
        'gallowance' => $abs['allowance'],
        'carryover'  => $carryover,
        'taken'      => $taken,
        'factor'     => $abs['factor'],
        'remainder'  => $remainder
      ];
    }

    // Notifications
    foreach ($events as $event) {
      $viewData['events'][] = ['val' => $event, 'name' => $this->LANG['profile_' . $event], 'selected' => $this->UO->read($profile, $event)];
    }
    $nocalgroup = true;
    $ngroups    = [];
    if ($notifyUserCalGroups = $this->UO->read($viewData['profile'], 'notifyUserCalGroups')) {
      $nocalgroup = false;
      $ngroups    = explode(',', $notifyUserCalGroups);
    }
    $viewData['userCalNotifyGroups'][] = ['val' => '0', 'name' => $this->LANG['none'], 'selected' => $nocalgroup];
    if ($this->allConfig['notificationsAllGroups'] || $UP->hasRole($profile, '1')) {
      $ugroups = $this->G->getAll();
      foreach ($ugroups as $ugroup) {
        $viewData['userCalNotifyGroups'][] = ['val' => $ugroup['id'], 'name' => $ugroup['name'], 'selected' => in_array($ugroup['id'], $ngroups)];
      }
    }
    else {
      $ugroups = $this->UG->getAllforUser($viewData['profile']);
      foreach ($ugroups as $ugroup) {
        $viewData['userCalNotifyGroups'][] = ['val' => $ugroup['groupid'], 'name' => $this->G->getNameById((string) $ugroup['groupid']), 'selected' => in_array($ugroup['groupid'], $ngroups)];
      }
    }
    $viewData['notifications'] = [
      ['prefix' => 'profile', 'name' => 'notify', 'type' => 'listmulti', 'values' => $viewData['events'], 'disabled' => false],
      ['prefix' => 'profile', 'name' => 'notifyUserCalGroups', 'type' => 'listmulti', 'values' => $viewData['userCalNotifyGroups'], 'disabled' => false],
    ];

    // Custom
    $viewData['custom'] = [
      ['prefix' => 'profile', 'name' => 'custom1', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'custom1'), 'maxlength' => '80', 'error' => ($inputAlert['custom1'] ?? '')],
      ['prefix' => 'profile', 'name' => 'custom2', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'custom2'), 'maxlength' => '80', 'error' => ($inputAlert['custom2'] ?? '')],
      ['prefix' => 'profile', 'name' => 'custom3', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'custom3'), 'maxlength' => '80', 'error' => ($inputAlert['custom3'] ?? '')],
      ['prefix' => 'profile', 'name' => 'custom4', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'custom4'), 'maxlength' => '80', 'error' => ($inputAlert['custom4'] ?? '')],
      ['prefix' => 'profile', 'name' => 'custom5', 'type' => 'text', 'placeholder' => '', 'value' => $this->UO->read($profile, 'custom5'), 'maxlength' => '80', 'error' => ($inputAlert['custom5'] ?? '')],
    ];

    $this->render('useredit', $viewData);
  }

  // Helper methods for actions...
  //---------------------------------------------------------------------------
  /**
   * Updates user profile.
   *
   * @param UserModel $UP        User Model
   * @param string    $profile   Profile username
   * @param array     $absences  Absences array
   * @param array     $events    Events array
   * @param bool      $showAlert Reference to show alert flag
   * @param array     $alertData Reference to alert data array
   * @return void
   */
  private function handleUpdate($UP, $profile, $absences, $events, &$showAlert, &$alertData) {
    $reloadPage     = false;
    $newUserOptions = [];

    $UP->lastname  = $_POST['txt_lastname'];
    $UP->firstname = $_POST['txt_firstname'];
    $UP->order_key = (isset($_POST['txt_orderkey']) && strlen($_POST['txt_orderkey'])) ? $_POST['txt_orderkey'] : '0';

    $newUserOptions['title']    = $_POST['txt_title'];
    $newUserOptions['position'] = $_POST['txt_position'];
    $newUserOptions['id']       = $_POST['txt_id'];
    $newUserOptions['gender']   = $_POST['opt_gender'] ?? 'male';

    $UP->email                   = $_POST['txt_email'];
    $newUserOptions['phone']     = $_POST['txt_phone'];
    $newUserOptions['mobile']    = $_POST['txt_mobilephone'];
    $newUserOptions['facebook']  = $_POST['txt_facebook'];
    $newUserOptions['instagram'] = $_POST['txt_instagram'];
    $newUserOptions['linkedin']  = $_POST['txt_linkedin'];
    $newUserOptions['tiktok']    = $_POST['txt_tiktok'];
    $newUserOptions['twitter']   = $_POST['txt_twitter'];
    $newUserOptions['xing']      = $_POST['txt_xing'];

    if (isset($_POST['sel_language'])) {
      if ($_POST['sel_language'] != $this->UO->read($profile, 'language')) {
        $reloadPage = true;
      }
      $newUserOptions["language"] = $_POST['sel_language'];
    }
    else {
      $newUserOptions['language'] = 'default';
    }

    $newUserOptions["calendarMonths"] = $_POST['sel_calendarMonths'] ?? 'default';

    if (isset($_POST['txt_showMonths']) && strlen($_POST['txt_showMonths'])) {
      $postValue                    = intval($_POST['txt_showMonths']);
      $newUserOptions["showMonths"] = max(1, min(12, $postValue));
    }
    else {
      $newUserOptions["showMonths"] = 1;
    }

    if (isset($_POST['sel_calfilterGroup'])) {
      $newUserOptions['calfilterGroup'] = $_POST['sel_calfilterGroup'];
    }
    $newUserOptions["region"] = $_POST['sel_region'] ?? '1';
    if (isset($_POST['sel_defaultMenu'])) {
      if ($_POST['sel_defaultMenu'] != $this->UO->read($profile, 'defaultMenu')) {
        if ($profile == $this->UL->username) {
          $reloadPage = true;
        }
      }
      $newUserOptions['defaultMenu'] = $_POST['sel_defaultMenu'];
    }
    else {
      $newUserOptions['defaultMenu'] = 'default';
    }

    if (isAllowed("useraccount")) {
      if (isset($_POST['sel_role']))
        $UP->role = (int) $_POST['sel_role'];
      $UP->locked = (isset($_POST['chk_locked']) && $_POST['chk_locked']) ? 1 : 0;
      $UP->hidden = (isset($_POST['chk_hidden']) && $_POST['chk_hidden']) ? 1 : 0;
      $UP->onhold = (isset($_POST['chk_onhold']) && $_POST['chk_onhold']) ? 1 : 0;
      if (isset($_POST['chk_verify']) && $_POST['chk_verify']) {
        $UP->verify = 1;
      }
      else {
        $UP->verify                   = 0;
        $newUserOptions['verifycode'] = '';
      }
    }

    if (isset($_POST['txt_password']) && strlen($_POST['txt_password']) && isset($_POST['txt_password2']) && strlen($_POST['txt_password2']) && $_POST['txt_password'] == $_POST['txt_password2']) {
      $UP->password       = password_hash(trim($_POST['txt_password']), PASSWORD_DEFAULT);
      $UP->last_pw_change = date("YmdHis");
    }

    if (isAllowed("usergroups") && isAllowed("groupmemberships")) {
      $this->UG->deleteByUser($profile);
      if (isset($_POST['sel_guestships'])) {
        foreach ($_POST['sel_guestships'] as $grp) {
          if ($this->G->getById($grp))
            $this->UG->save($profile, $grp, 'guest');
        }
      }
      if (isset($_POST['sel_memberships'])) {
        foreach ($_POST['sel_memberships'] as $grp) {
          if ($this->G->getById($grp))
            $this->UG->save($profile, $grp, 'member');
        }
      }
      if (isset($_POST['sel_managerships'])) {
        foreach ($_POST['sel_managerships'] as $grp) {
          if ($this->G->getById($grp))
            $this->UG->save($profile, $grp, 'manager');
        }
      }
    }

    if (isset($_POST['opt_avatar'])) {
      $newUserOptions['avatar'] = $_POST['opt_avatar'];
    }
    elseif (
      (!$this->UO->read($profile, 'avatar') && ($this->UO->read($profile, 'gender') == 'male' || $this->UO->read($profile, 'gender') == 'female')) ||
      ($this->UO->read($profile, 'avatar') == 'default_male.png' && $this->UO->read($profile, 'gender') == 'female') ||
      ($this->UO->read($profile, 'avatar') == 'default_female.png' && $this->UO->read($profile, 'gender') == 'male')
    ) {
      $newUserOptions['avatar'] = 'default_' . $this->UO->read($profile, 'gender') . '.png';
    }
    elseif (!$this->UO->read($profile, 'avatar')) {
      $newUserOptions['avatar'] = 'default_male.png';
    }

    if (isAllowed("userabsences") && $profile != 'admin') {
      foreach ($absences as $abs) {
        if (isset($_POST['txt_' . $abs['id'] . '_allowance']) && isset($_POST['txt_' . $abs['id'] . '_carryover'])) {
          $this->AL->username  = $profile;
          $this->AL->absid     = (string) $abs['id'];
          $this->AL->allowance = (float) $_POST['txt_' . $abs['id'] . '_allowance'];
          $this->AL->carryover = (float) $_POST['txt_' . $abs['id'] . '_carryover'];
          $this->AL->save();
        }
      }
    }

    $newUserOptions['notifyNone'] = '1';
    foreach ($events as $event) {
      if ($event !== 'notifyNone')
        $newUserOptions[$event] = '0';
    }
    if (isset($_POST['sel_notify']) && !in_array('notifyNone', $_POST['sel_notify'])) {
      $newUserOptions['notifyNone'] = '0';
      foreach ($_POST['sel_notify'] as $notify) {
        $newUserOptions[$notify] = '1';
      }
    }
    $newUserOptions['notifyUserCalGroups'] = '0';
    if (isset($_POST['sel_notifyUserCalGroups'])) {
      $newUserOptions['notifyUserCalGroups'] = implode(',', $_POST['sel_notifyUserCalGroups']);
    }

    if (isset($_POST['txt_custom1']))
      $newUserOptions['custom1'] = $_POST['txt_custom1'];
    if (isset($_POST['txt_custom2']))
      $newUserOptions['custom2'] = $_POST['txt_custom2'];
    if (isset($_POST['txt_custom3']))
      $newUserOptions['custom3'] = $_POST['txt_custom3'];
    if (isset($_POST['txt_custom4']))
      $newUserOptions['custom4'] = $_POST['txt_custom4'];
    if (isset($_POST['txt_custom5']))
      $newUserOptions['custom5'] = $_POST['txt_custom5'];

    $this->UO->saveBatch($profile, $newUserOptions);

    if (isset($_POST['chk_remove2fa']) && $_POST['chk_remove2fa']) {
      $this->UO->deleteUserOption($profile, 'secret');
    }
    $UP->update($profile);

    if ($this->allConfig['emailNotifications']) {
      sendUserEventNotifications("changed", $UP->username, $UP->firstname, $UP->lastname);
    }

    $this->LOG->logEvent("logUser", $this->UL->username, "log_user_updated", $UP->username);

    if ($reloadPage) {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=useredit&profile=" . $profile);
      die();
    }

    $showAlert            = true;
    $alertData['type']    = 'success';
    $alertData['title']   = $this->LANG['alert_success_title'];
    $alertData['subject'] = $this->LANG['profile_alert_update'];
    $alertData['text']    = $this->LANG['profile_alert_update_success'];
    $alertData['help']    = '';
  }

  //---------------------------------------------------------------------------
  /**
   * Archives a user.
   *
   * @param string $profile   Profile username
   * @param bool   $showAlert Reference to show alert flag
   * @param array  $alertData Reference to alert data array
   * @return void
   */
  private function handleArchive($profile, &$showAlert, &$alertData) {
    $exists = false;
    if (!$this->UserService->archiveUser($profile, (string) $this->UL->username)) {
      $exists = true;
    }
    if (!$exists) {
      $showAlert            = true;
      $alertData['type']    = 'success';
      $alertData['title']   = $this->LANG['alert_success_title'];
      $alertData['subject'] = $this->LANG['btn_archive'];
      $alertData['text']    = $this->LANG['profile_alert_archive_user'];
      $alertData['help']    = '';
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=userlist");
      die();
    }
    else {
      $showAlert            = true;
      $alertData['type']    = 'danger';
      $alertData['title']   = $this->LANG['alert_danger_title'];
      $alertData['subject'] = $this->LANG['btn_archive'];
      $alertData['text']    = $this->LANG['profile_alert_archive_user_failed'];
      $alertData['help']    = '';
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a user.
   *
   * @param string $profile   Profile username
   * @param bool   $showAlert Reference to show alert flag
   * @param array  $alertData Reference to alert data array
   * @return void
   */
  private function handleDelete($profile, &$showAlert, &$alertData) {
    $this->UserService->deleteUser($profile, false, (bool) ($this->allConfig['emailNotifications'] ?? false), (string) $this->UL->username);
    $showAlert            = true;
    $alertData['type']    = 'success';
    $alertData['title']   = $this->LANG['alert_success_title'];
    $alertData['subject'] = $this->LANG['btn_delete_selected'];
    $alertData['text']    = $this->LANG['profile_alert_delete_user'];
    $alertData['help']    = '';
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=userlist");
    die();
  }

  //---------------------------------------------------------------------------
  /**
   * Uploads user avatar.
   *
   * @param string    $profile   Profile username
   * @param UserModel $UP        User Model
   * @param bool      $showAlert Reference to show alert flag
   * @param array     $alertData Reference to alert data array
   * @return void
   */
  private function handleUpload($profile, $UP, &$showAlert, &$alertData) {
    $UPL                    = new UploadModel();
    $UPL->upload_dir        = APP_AVATAR_DIR;
    $UPL->extensions        = $this->CONF['avatarExtensions'];
    $UPL->do_filename_check = "y";
    $UPL->replace           = "y";
    $UPL->the_temp_file     = $_FILES['file_avatar']['tmp_name'];
    $UPL->http_error        = $_FILES['file_avatar']['error'];
    $fileExtension          = getFileExtension($_FILES['file_avatar']['name']);
    $UPL->max_size          = (int) $this->CONF['avatarMaxsize'];
    $UPL->the_file          = $profile . "." . $fileExtension;
    if ($UPL->uploadFile()) {
      $full_path = $UPL->upload_dir . $UPL->file_copy;
      $UPL->getUploadedFileInfo($full_path);
      $this->UO->save($profile, 'avatar', $UPL->uploaded_file['name']);
      $this->LOG->logEvent("logUser", $this->UL->username, "log_user_updated", $UP->username);
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=useredit&profile=" . $profile);
      die();
    }
    else {
      $showAlert            = true;
      $alertData['type']    = 'danger';
      $alertData['title']   = $this->LANG['alert_danger_title'];
      $alertData['subject'] = 'Avatar ' . $this->LANG['btn_upload'];
      $alertData['text']    = $UPL->getErrors();
      $alertData['help']    = '';
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Resets user avatar.
   *
   * @param string    $profile   Profile username
   * @param UserModel $UP        User Model
   * @param bool      $showAlert Reference to show alert flag
   * @param array     $alertData Reference to alert data array
   * @return void
   */
  private function handleReset($profile, $UP, &$showAlert, &$alertData) {
    $this->AV->delete($profile, $this->UO->read($profile, 'avatar'));
    $this->UO->save($profile, 'avatar', 'default_' . $this->UO->read($profile, 'gender') . '.png');
    $this->LOG->logEvent("logUser", $this->UL->username, "log_user_updated", $UP->username);
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=useredit&profile=" . $profile);
    die();
  }
}
