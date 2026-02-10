<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\AbsenceModel;

/**
 * Bulk Edit Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.5.0
 */
class BulkEditController extends BaseController
{
  /** @var array<string, mixed> */
  private array $viewData = [];

  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    if (!isAllowed($this->CONF['controllers']['bulkedit']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $this->viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $this->viewData['showAlerts'] = $this->allConfig['showAlerts'];

    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];
    $alertData  = [];
    $showAlert  = false;

    $absences = $this->A->getAll();
    $absid    = (string) $absences[0]['id'];
    $abs      = new AbsenceModel($this->DB->db, $this->CONF);
    $abs->get($absid);
    $groups  = $this->G->getAll();
    $groupid = 'All';
    $users   = $this->U->getAll('lastname', 'firstname', 'ASC', false, false);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_bulkUpdate'])) {
        $absid   = $_POST['hidden_absid'];
        $groupid = $_POST['hidden_groupid'];

        $inputError           = false;
        $selectedAllowanceKey = 'txt_selected_' . $absid . '_allowance';
        $selectedCarryoverKey = 'txt_selected_' . $absid . '_carryover';

        if (($_POST[$selectedAllowanceKey] ?? '') !== '' && !formInputValid($selectedAllowanceKey, 'numeric'))
          $inputError = true;
        if (($_POST[$selectedCarryoverKey] ?? '') !== '' && !formInputValid($selectedCarryoverKey, 'numeric'))
          $inputError = true;

        foreach ($users as $user) {
          if (($_POST['txt_' . $user['username'] . '_' . $absid . '_allowance'] ?? '') !== '' && !formInputValid('txt_' . $user['username'] . '_' . $absid . '_allowance', 'numeric'))
            $inputError = true;
          if (($_POST['txt_' . $user['username'] . '_' . $absid . '_carryover'] ?? '') !== '' && !formInputValid('txt_' . $user['username'] . '_' . $absid . '_carryover', 'numeric'))
            $inputError = true;
        }

        if (!$inputError && isset($_POST['chk_userSelected'])) {
          $selected_users = $_POST['chk_userSelected'];
          $updates        = [];
          foreach ($selected_users as $value) {
            $allowance = (float) (($_POST[$selectedAllowanceKey] ?? '') !== '' ? $_POST[$selectedAllowanceKey] : ($_POST['txt_' . $value . '_' . $absid . '_allowance'] ?? 0));
            $carryover = (float) (($_POST[$selectedCarryoverKey] ?? '') !== '' ? $_POST[$selectedCarryoverKey] : ($_POST['txt_' . $value . '_' . $absid . '_carryover'] ?? 0));

            $updates[] = [
              'username'  => $value,
              'absid'     => $absid,
              'allowance' => $allowance,
              'carryover' => $carryover
            ];
          }
          $this->AL->batchSave($updates);

          foreach ($updates as $update) {
            $this->LOG->logEvent("logUser", $this->UL->username, "log_user_updated", "Allowance bulk edit for user: " . $update['username']);
          }

          if (isset($_SESSION))
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['bulkedit_alert_update'];
          $alertData['text']    = $this->LANG['bulkedit_alert_update_success'];
          $alertData['help']    = '';
        }
        else {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['alert_input'];
          $alertData['text']    = $this->LANG['bulkedit_alert_update_failed'];
          $alertData['help']    = '';
        }
      }
      elseif (isset($_POST['btn_load'])) {
        $absid = $_POST['sel_absence'];
        $abs->get($absid);
        if ($_POST['sel_group'] != "All") {
          $groupid = $_POST['sel_group'];
          $users   = $this->filterUsersByGroup($users, $groupid);
        }
        if (isset($_SESSION))
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
    }

    $this->viewData['absences']  = $absences;
    $this->viewData['abs']       = $abs;
    $this->viewData['absid']     = $absid;
    $this->viewData['bulkusers'] = [];
    $this->viewData['groupid']   = $groupid;
    $this->viewData['groups']    = $groups;

    if ($groupid != "All") {
      $users = $this->filterUsersByGroup($users, $groupid);
    }

    foreach ($users as $user) {
      list($allowance, $carryover)   = $this->getOrCreateAllowance($user, $absid);
      $this->viewData['bulkusers'][] = [
        'username'  => $user['username'],
        'dispname'  => (!empty($user['firstname'])) ? $user['lastname'] . ", " . $user['firstname'] . ' (' . $user['username'] . ')' : $user['lastname'] . ' (' . $user['username'] . ')',
        'allowance' => $allowance,
        'carryover' => $carryover
      ];
    }

    if ($showAlert) {
      $this->viewData['alertData'] = $alertData;
      $this->viewData['showAlert'] = true;
    }

    $this->render('bulkedit', $this->viewData);
  }

  /**
   * Filters a list of users by group ID.
   *
   * @param array<int, array<string, mixed>> $users   List of users to filter
   * @param string|int                      $groupid Group ID to filter by
   *
   * @return array<int, array<string, mixed>> Filtered list of users
   */
  private function filterUsersByGroup(array $users, string|int $groupid): array {
    if ((string) $groupid === "All") {
      return $users;
    }
    return array_filter($users, fn($user) => $this->UG->isMemberOrManagerOfGroup($user['username'] ?? '', (string) $groupid));
  }

  /**
   * Gets the allowance for a user/absence or creates it if it doesn't exist.
   *
   * @param array<string, mixed> $user  User record
   * @param string|int           $absid Absence ID
   *
   * @return array{0: float, 1: float} Array with allowance and carryover
   */
  private function getOrCreateAllowance(array $user, string|int $absid): array {
    if ($this->AL->find((string) $user['username'], $absid)) {
      return [$this->AL->allowance, $this->AL->carryover];
    }
    else {
      $allowance           = (float) ($this->A->getAllowance((string) $absid) ?: 0);
      $carryover           = 0.0;
      $this->AL->username  = (string) $user['username'];
      $this->AL->absid     = (string) $absid;
      $this->AL->allowance = $allowance;
      $this->AL->carryover = $carryover;
      $this->AL->save();
      return [$allowance, $carryover];
    }
  }
}
