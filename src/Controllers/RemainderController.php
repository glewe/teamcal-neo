<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

/**
 * Remainder Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class RemainderController extends BaseController
{
  private array $viewData = [];

  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['remainder']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $this->viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $this->viewData['showAlerts'] = $this->allConfig['showAlerts'];
    $this->viewData['search']     = '';
    $this->viewData['year']       = date("Y");
    $this->viewData['groupid']    = $_GET['group'] ?? 'all';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;
      if (isset($_POST['btn_search']) && !formInputValid('txt_search', 'required|alpha_numeric_dash')) {
        $inputError = true;
      }

      if (!$inputError) {
        if (isset($_POST['btn_group'])) {
          if ($this->UL->username)
            $this->UO->save($this->UL->username, 'calfilterGroup', $_POST['sel_group']);
          header("Location: index.php?action=remainder&group=" . $_POST['sel_group']);
          die();
        }
        elseif (isset($_POST['btn_search'])) {
          $this->viewData['search'] = $_POST['txt_search'];
        }
        elseif (isset($_POST['btn_year'])) {
          $this->viewData['year'] = $_POST['sel_year'];
        }
        elseif (isset($_POST['btn_reset'])) {
          if ($this->UL->username) {
            $this->UO->deleteUserOption($this->UL->username, 'calfilter');
            $this->UO->deleteUserOption($this->UL->username, 'calfilterGroup');
            $this->UO->deleteUserOption($this->UL->username, 'calfilterSearch');
          }
          header("Location: index.php?action=remainder");
          die();
        }
      }
      else {
        $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_input'], $this->LANG['abs_alert_save_failed']);
        return;
      }
    }

    if (isset($_GET['search']) && $_GET['search'] == "reset") {
      header("Location: index.php?action=remainder");
      die();
    }

    //
    // Fetch users
    //
    if (!empty($this->viewData['search'])) {
      $users = $this->U->getAllLike($this->viewData['search']);
    }
    else {
      $users = $this->U->getAllButHidden();
    }

    //
    // Filter users by group and permissions
    //
    $groupid = $this->viewData['groupid'];
    if ($groupid == "all") {
      $this->viewData['group'] = $this->LANG['all'];
      $users                   = array_filter($users, fn($usr) => ($this->UL->username == $usr['username'] || $this->UL->username == 'admin' || $this->UG->isGroupManagerOfUser($this->UL->username, $usr['username'])));
    }
    else {
      $this->viewData['group'] = $this->G->getNameById($groupid);
      $users                   = array_filter($users, function ($usr) use ($groupid) {
        if (!$this->UG->isMemberOrGuestOfGroup($usr['username'], (string) $groupid))
          return false;
        return ($this->UL->username == 'admin' || $this->UL->username == $usr['username'] || $this->UG->isGroupManagerOfUser($this->UL->username, $usr['username']));
      });
    }

    //
    // Pagination
    //
    if ($limit = $this->allConfig['usersPerPage']) {
      $total = count($users);
      $pages = ceil($total / $limit);
      $page  = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]));
      if ($page < 1)
        $page = 1;
      $offset                  = ($page - 1) * $limit;
      $users                   = array_slice($users, $offset, $limit);
      $this->viewData['page']  = $page;
      $this->viewData['pages'] = $pages;
    }
    else {
      $this->viewData['page']  = 1;
      $this->viewData['pages'] = 1;
    }

    $this->prepareViewData($users);
    $this->render('remainder', $this->viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Prepares the view data.
   *
   * @param array $users Array of users to process
   * @return void
   */
  private function prepareViewData($users) {
    $this->viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];
    $this->viewData['usersPerPage']    = $this->allConfig['usersPerPage'];
    $this->viewData['absences']        = array_filter($this->A->getAll(), fn($abs) => $abs['show_in_remainder']);
    $this->viewData['allGroups']       = $this->G->getAll();
    $this->viewData['holidays']        = $this->H->getAllCustom();
    $countFrom                         = $this->viewData['year'] . '0101';
    $countTo                           = $this->viewData['year'] . '1231';
    $row                               = $this->G->getRowById($this->viewData['groupid']);
    $this->viewData['groups']          = ($this->viewData['groupid'] == 'all') ? $this->viewData['allGroups'] : ($row ? $row : []);

    // Need AllowanceModel
    $AL = new \App\Models\AllowanceModel($this->DB->db, $this->CONF);

    $this->viewData['users'] = [];
    $i                       = 0;
    foreach ($users as $user) {
      $this->viewData['users'][$i]['username'] = $user['username'];
      $this->viewData['users'][$i]['dispname'] = (!empty($user['firstname'])) ? $user['lastname'] . ", " . $user['firstname'] . ' (' . $user['username'] . ')' : $user['lastname'] . ' (' . $user['username'] . ')';
      $this->viewData['users'][$i]['role']     = $this->RO->getNameById($user['role']);
      $this->viewData['users'][$i]['color']    = $this->RO->getColorById($user['role']);

      // Calculate remainder data for each absence type
      $this->viewData['users'][$i]['absences'] = [];
      foreach ($this->viewData['absences'] as $abs) {
        if ($AL->find($user['username'], $abs['id'])) {
          $carryover = $AL->carryover;
          if (!$AL->allowance) {
            // Zero personal allowance will take over global yearly allowance
            $AL->allowance = $abs['allowance'];
            $AL->update();
          }
          $allowance = $AL->allowance;
        }
        else {
          $carryover = 0;
          $allowance = $abs['allowance'];
        }

        $totalAllowance = $allowance + $carryover;
        $taken          = 0;
        if (!$abs['counts_as_present']) {
          $taken = $this->AbsenceService->countAbsence($user['username'], (string) $abs['id'], $countFrom, $countTo, false, false);
        }
        $remainder = $allowance + $carryover - ($taken * $abs['factor']);

        $this->viewData['users'][$i]['absences'][$abs['id']] = [
          'taken'          => $taken,
          'totalAllowance' => $totalAllowance,
          'remainder'      => $remainder
        ];
      }

      $i++;
    }
  }
}
