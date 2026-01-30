<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;



/**
 * Remainder statistics page controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class StatsRemainderController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['statsremainder']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];

    $viewData['labels']   = "";
    $viewData['data']     = "";
    $viewData['absences'] = $this->A->getAll();
    $viewData['groups']   = $this->G->getAll('DESC');
    $viewData['groupid']  = 'all';
    $viewData['year']     = date("Y");
    $viewData['from']     = date("Y") . '-01-01';
    $viewData['to']       = date("Y") . '-12-31';
    $viewData['yaxis']    = 'users';
    $viewData['color']    = $this->allConfig['statsDefaultColorRemainder'] ?? 'orange';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;
      if (isset($_POST['btn_apply'])) {
        if (!formInputValid('txt_periodYear', 'numeric'))
          $inputError = true;
        if (!formInputValid('txt_scaleSmart', 'numeric'))
          $inputError = true;
        if (!formInputValid('txt_scaleMax', 'numeric'))
          $inputError = true;
        if (!formInputValid('txt_colorHex', 'hexadecimal'))
          $inputError = true;
      }

      if (!$inputError) {
        if (isset($_POST['btn_apply'])) {
          $viewData['groupid'] = $_POST['sel_group'];
          $viewData['year']    = $_POST['sel_year'];
          $viewData['color']   = $_POST['sel_color'];
        }
        if (isset($_SESSION)) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
      }
      else {
        $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_input'], $this->LANG['abs_alert_save_failed']);
        return;
      }
    }

    $viewData['from']       = $viewData['year'] . '-01-01';
    $viewData['to']         = $viewData['year'] . '-12-31';
    $viewData['groupName']  = ($viewData['groupid'] == "all") ? $this->LANG['all'] : $this->G->getNameById($viewData['groupid']);
    $viewData['periodName'] = $viewData['from'] . ' - ' . $viewData['to'];

    $labels        = [];
    $dataAllowance = [];
    $dataRemainder = [];

    $filteredAbsences = array_filter($viewData['absences'], function ($abs) {
      return $this->A->get((string) $abs['id']) && !$this->A->counts_as_present && intval($this->A->allowance) > 0;
    });

    $countFrom = str_replace('-', '', $viewData['from']);
    $countTo   = str_replace('-', '', $viewData['to']);

    $viewData['total'] = 0;
    foreach ($filteredAbsences as $abs) {
      $labels[]         = '"' . $abs['name'] . '"';
      $absenceAllowance = intval($this->A->allowance);

      if ($viewData['groupid'] == "all") {
        $totalAbsenceAllowance = 0;
        $totalGroupRemainder   = 0;
        foreach ($viewData['groups'] as $group) {
          $users                  = $this->UG->getAllforGroup((string) $group['id']);
          $userCount              = count($users);
          $totalAbsenceAllowance += $absenceAllowance * $userCount;
          $groupRemainder         = $absenceAllowance * $userCount;
          foreach ($users as $user) {
            $groupRemainder -= $this->AbsenceService->countAbsence($user['username'], (string) $abs['id'], $countFrom, $countTo, false, false);
          }
          $totalGroupRemainder += $groupRemainder;
        }
        $dataAllowance[]    = $totalAbsenceAllowance;
        $dataRemainder[]    = $totalGroupRemainder;
        $viewData['total'] += $totalGroupRemainder;
      }
      else {
        $users           = $this->UG->getAllforGroup((string) $viewData['groupid']);
        $userCount       = count($users);
        $groupRemainder  = $absenceAllowance * $userCount;
        $dataAllowance[] = $groupRemainder;
        foreach ($users as $user) {
          $groupRemainder -= $this->AbsenceService->countAbsence($user['username'], (string) $abs['id'], $countFrom, $countTo, false, false);
        }
        $dataRemainder[]    = $groupRemainder;
        $viewData['total'] += $groupRemainder;
      }
    }

    $viewData['labels']        = implode(',', $labels);
    $viewData['dataAllowance'] = implode(',', $dataAllowance);
    $viewData['dataRemainder'] = implode(',', $dataRemainder);
    $viewData['height']        = count($labels) * 80 + 100;

    $this->render('statsremainder', $viewData);
  }
}
