<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\GroupModel;
use App\Models\UploadModel;

/**
 * Group Edit Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class GroupEditController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    $GG          = new GroupModel();
    $missingData = false;

    if (isset($_GET['id'])) {
      $id = sanitize($_GET['id']);
      if (!$GG->getById($id)) {
        $missingData = true;
      }
    }
    else {
      $missingData = true;
    }

    if ($missingData) {
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
      return;
    }

    if (!isAllowed($this->CONF['controllers']['groupedit']->permission) && !$this->UG->isGroupManagerOfGroup($this->UL->username, $GG->id)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $viewData                 = [];
    $viewData['pageHelp']     = $this->allConfig['pageHelp'];
    $viewData['showAlerts']   = $this->allConfig['showAlerts'];
    $viewData['id']           = $GG->id;
    $viewData['name']         = $GG->name;
    $viewData['description']  = $GG->description;
    $viewData['avatar']       = $GG->avatar ? $GG->avatar : 'default_group.png';
    $viewData['minpresent']   = $GG->minpresent;
    $viewData['maxabsent']    = $GG->maxabsent;
    $viewData['minpresentwe'] = $GG->minpresentwe;
    $viewData['maxabsentwe']  = $GG->maxabsentwe;
    $viewData['docurl']       = $this->CONF['controllers']['groupedit']->docurl;
    $viewData['panelColor']   = $this->CONF['controllers']['groupedit']->panelColor;
    $viewData['faIcon']       = $this->CONF['controllers']['groupedit']->faIcon;

    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];
    $alertData  = [];
    $showAlert  = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $viewData['id']          = $_POST['hidden_id'];
      $viewData['name']        = $_POST['txt_name'];
      $viewData['description'] = $_POST['txt_description'];

      $inputError = false;
      if (isset($_POST['btn_groupUpdate'])) {
        if (formInputValid('txt_name', 'required|alpha_numeric_dash') !== true)
          $inputError = true;
        if (formInputValid('txt_description', 'alpha_numeric_dash_blank') !== true)
          $inputError = true;
        if (formInputValid('txt_minpresent', 'numeric') !== true)
          $inputError = true;
        if (formInputValid('txt_maxabsent', 'numeric') !== true)
          $inputError = true;
        if (formInputValid('txt_minpresentwe', 'numeric') !== true)
          $inputError = true;
        if (formInputValid('txt_maxabsentwe', 'numeric') !== true)
          $inputError = true;
      }

      if (!$inputError) {
        if (isset($_POST['btn_groupUpdate'])) {
          $GG->name        = $_POST['txt_name'];
          $GG->description = $_POST['txt_description'];
          // Avatar is handled separately via upload button, but ensure it's preserved or default
          $GG->avatar       = $GG->avatar ? $GG->avatar : 'default_group.png';
          $GG->minpresent   = (int) $_POST['txt_minpresent'];
          $GG->maxabsent    = (int) $_POST['txt_maxabsent'];
          $GG->minpresentwe = (int) $_POST['txt_minpresentwe'];
          $GG->maxabsentwe  = (int) $_POST['txt_maxabsentwe'];

          $avatarUploaded = false;
          if (!empty($_FILES['file_avatar']['name'])) {
            if ($this->handleUpload($GG, $showAlert, $alertData, false)) {
              $avatarUploaded = true;
            }
          }

          if (!$avatarUploaded && isset($_POST['opt_avatar'])) {
            if (strpos($GG->avatar, 'default_') === false && file_exists(APP_AVATAR_DIR . $GG->avatar)) {
              unlink(APP_AVATAR_DIR . $GG->avatar);
            }
            $GG->avatar = $_POST['opt_avatar'];
          }

          $GG->update($_POST['hidden_id']);

          if (isAllowed("groupmemberships") || $this->UG->isGroupManagerOfGroup($this->UL->username, $viewData['id'])) {
            if (isset($_POST['sel_members'])) {
              $this->UG->deleteAllMembers($_POST['hidden_id']);
              foreach ($_POST['sel_members'] as $uname) {
                $this->UG->save($uname, $_POST['hidden_id'], 'member');
              }
            }
            if (isset($_POST['sel_managers'])) {
              $this->UG->deleteAllManagers($_POST['hidden_id']);
              foreach ($_POST['sel_managers'] as $uname) {
                $this->UG->save($uname, $_POST['hidden_id'], 'manager');
              }
            }
          }

          if ($this->allConfig['emailNotifications']) {
            sendGroupEventNotifications("changed", $GG->name, $GG->description);
          }

          $this->LOG->logEvent("logGroup", $this->UL->username, "log_group_updated", $GG->name);

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['group_alert_edit'];
          $alertData['text']    = $this->LANG['group_alert_edit_success'];
          $alertData['help']    = '';

          if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
          }

          $viewData['name']         = $GG->name;
          $viewData['description']  = $GG->description;
          $viewData['avatar']       = $GG->avatar;
          $viewData['minpresent']   = $GG->minpresent;
          $viewData['maxabsent']    = $GG->maxabsent;
          $viewData['minpresentwe'] = $GG->minpresentwe;
          $viewData['maxabsentwe']  = $GG->maxabsentwe;
        }
        elseif (isset($_POST['btn_uploadAvatar'])) {
          $this->handleUpload($GG, $showAlert, $alertData);
        }
        elseif (isset($_POST['btn_resetAvatar'])) {
          $this->handleReset($GG, $showAlert, $alertData);
        }
      }
      else {
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_input'];
        $alertData['text']    = $this->LANG['group_alert_save_failed'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $isGroupAdmin      = isAllowed($this->CONF['controllers']['groupedit']->permission);
    $viewData['group'] = [
      ['prefix' => 'group', 'name' => 'name', 'type' => $isGroupAdmin ? 'text' : 'info', 'placeholder' => '', 'value' => $viewData['name'], 'maxlength' => '40', 'mandatory' => true, 'error' => ($inputAlert['name'] ?? '')],
      ['prefix' => 'group', 'name' => 'description', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['description'], 'maxlength' => '100', 'error' => ($inputAlert['description'] ?? '')],
      ['prefix' => 'group', 'name' => 'minpresent', 'type' => 'text', 'placeholder' => '0', 'value' => $viewData['minpresent'], 'maxlength' => '4', 'error' => ($inputAlert['minpresent'] ?? '')],
      ['prefix' => 'group', 'name' => 'maxabsent', 'type' => 'text', 'placeholder' => '9999', 'value' => $viewData['maxabsent'], 'maxlength' => '4', 'error' => ($inputAlert['maxabsent'] ?? '')],
      ['prefix' => 'group', 'name' => 'minpresentwe', 'type' => 'text', 'placeholder' => '0', 'value' => $viewData['minpresentwe'], 'maxlength' => '4', 'error' => ($inputAlert['minpresentwe'] ?? '')],
      ['prefix' => 'group', 'name' => 'maxabsentwe', 'type' => 'text', 'placeholder' => '9999', 'value' => $viewData['maxabsentwe'], 'maxlength' => '4', 'error' => ($inputAlert['maxabsentwe'] ?? '')],
    ];

    // Avatar
    $viewData['avatars']        = getFiles(APP_AVATAR_DIR, $this->CONF['avatarExtensions'], 'default_group');
    $viewData['avatar_maxsize'] = $this->CONF['avatarMaxsize'];
    $viewData['avatar_formats'] = implode(', ', $this->CONF['avatarExtensions']);

    $isGroupManagerOfGroup = $this->UG->isGroupManagerOfGroup($this->UL->username, (string) $viewData['id']);
    $disabled              = !($isGroupAdmin || $isGroupManagerOfGroup);

    $allUsers      = $this->U->getAll();
    $groupId       = $viewData['id'];
    $groupMembers  = $this->UG->getAllMemberUsernames((string) $groupId);
    $groupManagers = $this->UG->getAllManagerUsernames((string) $groupId);

    $groupMembersMap  = array_flip($groupMembers);
    $groupManagersMap = array_flip($groupManagers);

    $viewData['memberlist']  = [];
    $viewData['managerlist'] = [];
    foreach ($allUsers as $user) {
      $fullname                  = $user['lastname'] . ', ' . $user['firstname'];
      $username                  = $user['username'];
      $viewData['memberlist'][]  = [
        'val'      => $username,
        'name'     => $fullname,
        'selected' => isset($groupMembersMap[$username])
      ];
      $viewData['managerlist'][] = [
        'val'      => $username,
        'name'     => $fullname,
        'selected' => isset($groupManagersMap[$username])
      ];
    }

    $viewData['members']  = [
      ['prefix' => 'group', 'name' => 'members', 'type' => 'listmulti', 'values' => $viewData['memberlist'], 'disabled' => $disabled],
    ];
    $viewData['managers'] = [
      ['prefix' => 'group', 'name' => 'managers', 'type' => 'listmulti', 'values' => $viewData['managerlist'], 'disabled' => $disabled],
    ];

    $this->render('groupedit', $viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Uploads group avatar.
   *
   * @param GroupModel $GG        Group Model
   * @param bool       $showAlert Reference to show alert flag
   * @param array      $alertData Reference to alert data array
   * @return bool
   */
  private function handleUpload($GG, &$showAlert, &$alertData, $redirect = true) {
    $UPL                    = new UploadModel();
    $UPL->upload_dir        = APP_AVATAR_DIR;
    $UPL->extensions        = $this->CONF['avatarExtensions'];
    $UPL->do_filename_check = "y";
    $UPL->replace           = "y";
    $UPL->the_temp_file     = $_FILES['file_avatar']['tmp_name'];
    $UPL->http_error        = $_FILES['file_avatar']['error'];
    $fileExtension          = getFileExtension($_FILES['file_avatar']['name']);
    $UPL->max_size          = (int) $this->CONF['avatarMaxsize'];
    $UPL->the_file          = 'group_' . $GG->id . "." . $fileExtension;
    if ($UPL->uploadFile()) {
      $full_path = $UPL->upload_dir . $UPL->file_copy;
      $UPL->getUploadedFileInfo($full_path);

      if ($GG->avatar && $GG->avatar !== $UPL->uploaded_file['name'] && strpos($GG->avatar, 'default_') === false && file_exists(APP_AVATAR_DIR . $GG->avatar)) {
        unlink(APP_AVATAR_DIR . $GG->avatar);
      }

      $GG->avatar = $UPL->uploaded_file['name'];

      if ($redirect) {
        $GG->update($GG->id);
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=groupedit&id=" . $GG->id);
        die();
      }
      return true;
    }
    else {
      $showAlert            = true;
      $alertData['type']    = 'danger';
      $alertData['title']   = $this->LANG['alert_danger_title'];
      $alertData['subject'] = 'Avatar ' . $this->LANG['btn_upload'];
      $alertData['text']    = $UPL->getErrors();
      $alertData['help']    = '';
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Resets group avatar.
   *
   * @param GroupModel $GG        Group Model
   * @param bool       $showAlert Reference to show alert flag
   * @param array      $alertData Reference to alert data array
   * @return void
   */
  private function handleReset($GG, &$showAlert, &$alertData) {
    // Delete old avatar if it's not default (optional, but good cleanup)
    if ($GG->avatar && strpos($GG->avatar, 'default_') === false && file_exists(APP_AVATAR_DIR . $GG->avatar)) {
      unlink(APP_AVATAR_DIR . $GG->avatar);
    }

    $GG->avatar = 'default_group.png';
    $GG->update($GG->id);

    header("Location: " . $_SERVER['PHP_SELF'] . "?action=groupedit&id=" . $GG->id);
    die();
  }
}
