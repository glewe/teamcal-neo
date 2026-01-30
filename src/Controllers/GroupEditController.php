<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\GroupModel;

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
          $GG->name         = $_POST['txt_name'];
          $GG->description  = $_POST['txt_description'];
          $GG->minpresent   = (int) $_POST['txt_minpresent'];
          $GG->maxabsent    = (int) $_POST['txt_maxabsent'];
          $GG->minpresentwe = (int) $_POST['txt_minpresentwe'];
          $GG->maxabsentwe  = (int) $_POST['txt_maxabsentwe'];

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
          $viewData['minpresent']   = $GG->minpresent;
          $viewData['maxabsent']    = $GG->maxabsent;
          $viewData['minpresentwe'] = $GG->minpresentwe;
          $viewData['maxabsentwe']  = $GG->maxabsentwe;
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
}
