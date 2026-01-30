<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

/**
 * Absence Summary Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class AbsenceSummaryController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    if (!isAllowed($this->CONF['controllers']['absum']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $missingData = false;
    $caluser     = '';

    if (isset($_GET['user'])) {
      $caluser = sanitize($_GET['user']);
      if ($caluser !== 'Public' && !$this->U->findByName($caluser)) {
        $missingData = true;
      }
      if ($caluser === 'Public') {
        $caluser = '';
      }
    }
    else {
      $missingData = true;
    }

    if ($missingData) {
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
      return;
    }

    $viewData                    = [];
    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];

    $users            = $this->U->getAll();
    $viewData['year'] = date("Y");

    $alertData = [];
    $showAlert = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_user'])) {
        header('Location: index.php?action=absum&user=' . ($_POST['sel_user'] ?? ''));
        die();
      }
      elseif (isset($_POST['btn_year'])) {
        $viewData['year'] = $_POST['sel_year'] ?? date('Y');
      }

      if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
    }

    $viewData['username'] = $caluser ?: 'Public';
    $viewData['fullname'] = strlen($caluser) ? $this->U->getFullname($caluser) : $this->LANG['role_public'];
    $viewData['users']    = [];
    foreach ($users as $usr) {
      $viewData['users'][] = ['username' => $usr['username'], 'lastfirst' => $this->U->getLastFirst($usr['username'])];
    }
    $viewData['from']     = $viewData['year'] . '-01-01';
    $viewData['to']       = $viewData['year'] . '-12-31';
    $viewData['absences'] = [];
    $absences             = $this->A->getAll();

    foreach ($absences as $abs) {
      $summary     = $this->AbsenceService->getAbsenceSummary($caluser, (string) $abs['id'], (string) $viewData['year']);
      $subabsences = [];
      $subs        = $this->A->getAllSub((string) $abs['id']);
      if ($subs && is_array($subs)) {
        foreach ($subs as $subabs) {
          $subsummary           = $this->AbsenceService->getAbsenceSummary($caluser, (string) $subabs['id'], (string) $viewData['year']);
          $subabs['contingent'] = $subsummary['totalallowance'];
          $subabs['taken']      = $subsummary['taken'];
          $subabs['remainder']  = $subsummary['remainder'];
          $subabsences[]        = $subabs;
        }
      }
      $viewData['absences'][] = [
        'id'          => $abs['id'],
        'icon'        => $abs['icon'],
        'color'       => $abs['color'],
        'bgcolor'     => $abs['bgcolor'],
        'allowance'   => $abs['allowance'],
        'counts_as'   => $abs['counts_as'],
        'name'        => $abs['name'],
        'contingent'  => $summary['totalallowance'],
        'taken'       => $summary['taken'],
        'remainder'   => $summary['remainder'],
        'subabsences' => $subabsences
      ];
    }

    $this->render('absum', $viewData);
  }
}
