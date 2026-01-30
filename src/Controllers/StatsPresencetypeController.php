<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

/**
 * Presence type statistics page controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class StatsPresencetypeController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['statspresencetype']->permission)) {
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
    $viewData['period']   = 'year';
    $viewData['from']     = date("Y") . '-01-01';
    $viewData['to']       = date("Y") . '-12-31';
    $viewData['yaxis']    = 'users';

    $colorMap                   = [
      '#0d6efd' => 'blue',
      '#0dcaf0' => 'cyan',
      '#198754' => 'green',
      '#6c757d' => 'grey',
      '#d63384' => 'magenta',
      '#fd7e14' => 'orange',
      '#6f42c1' => 'purple',
      '#dc3545' => 'red',
      '#ffc107' => 'yellow'
    ];
    $rawColor                   = $this->allConfig['statsDefaultColorPresencetype'] ?? 'green';
    $viewData['color']          = $colorMap[$rawColor] ?? $rawColor;
    $viewData['showAsPieChart'] = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;
      if (isset($_POST['btn_apply'])) {
        if (!formInputValid('txt_from', 'date'))
          $inputError = true;
        if (!formInputValid('txt_to', 'date'))
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
          $viewData['period']  = $_POST['sel_period'];
          if ($viewData['period'] == 'custom') {
            $viewData['from'] = $_POST['txt_from'];
            $viewData['to']   = $_POST['txt_to'];
          }
          if (isset($_POST['sel_color'])) {
            $viewData['color'] = $_POST['sel_color'];
          }
          if (isset($_POST['chk_showAsPieChart']) && $_POST['chk_showAsPieChart']) {
            $viewData['showAsPieChart'] = true;
          }
          else {
            $viewData['showAsPieChart'] = false;
          }
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

    switch ($viewData['period']) {
      case 'year':
        $viewData['from'] = date("Y") . '-01-01';
        $viewData['to'] = date("Y") . '-12-31';
        break;
      case 'half':
        if (date("n") <= 6) {
          $viewData['from'] = date("Y") . '-01-01';
          $viewData['to']   = date("Y") . '-06-30';
        }
        else {
          $viewData['from'] = date("Y") . '-07-01';
          $viewData['to']   = date("Y") . '-12-31';
        }
        break;
      case 'quarter':
        if (date("n") <= 3) {
          $viewData['from'] = date("Y") . '-01-01';
          $viewData['to']   = date("Y") . '-03-31';
        }
        elseif (date("n") <= 6) {
          $viewData['from'] = date("Y") . '-04-01';
          $viewData['to']   = date("Y") . '-06-30';
        }
        elseif (date("n") <= 9) {
          $viewData['from'] = date("Y") . '-07-01';
          $viewData['to']   = date("Y") . '-09-30';
        }
        else {
          $viewData['from'] = date("Y") . '-10-01';
          $viewData['to']   = date("Y") . '-12-31';
        }
        break;
      case 'month':
        $viewData['from'] = date("Y") . '-' . date("m") . '-01';
        $myts = strtotime($viewData['from']);
        $viewData['to'] = date("Y") . '-' . date("m") . '-' . sprintf('%02d', date("t", $myts));
        break;
    }

    $viewData['groupName']  = ($viewData['groupid'] == "all") ? $this->LANG['all'] : $this->G->getNameById($viewData['groupid']);
    $viewData['periodName'] = $viewData['from'] . ' - ' . $viewData['to'];
    $labels                 = [];
    $sliceColors            = [];
    $data                   = [];

    $filteredPresences = array_filter($viewData['absences'], function ($abs) {
      return $this->A->get((string) $abs['id']) && $this->A->counts_as_present;
    });

    $countFrom = str_replace('-', '', $viewData['from']);
    $countTo   = str_replace('-', '', $viewData['to']);

    $viewData['total'] = 0;
    foreach ($filteredPresences as $abs) {
      $labels[]      = '"' . $abs['name'] . '"';
      $sliceColors[] = '"#' . $abs['bgcolor'] . '"';
      $count         = 0;
      if ($viewData['groupid'] == "all") {
        foreach ($viewData['groups'] as $group) {
          $users = $this->UG->getAllforGroup((string) $group['id']);
          foreach ($users as $user) {
            $count += $this->AbsenceService->countAbsence($user['username'], (string) $abs['id'], $countFrom, $countTo, false, false);
          }
        }
        $data[]             = $count;
        $viewData['total'] += $count;
      }
      else {
        $users = $this->UG->getAllforGroup((string) $viewData['groupid']);
        foreach ($users as $user) {
          $count += $this->AbsenceService->countAbsence($user['username'], (string) $abs['id'], $countFrom, $countTo, false, false);
        }
        $data[]             = $count;
        $viewData['total'] += $count;
      }
    }

    $viewData['labels']      = implode(',', $labels);
    $viewData['sliceColors'] = implode(',', $sliceColors);
    $viewData['data']        = implode(',', $data);
    $viewData['height']      = $viewData['showAsPieChart'] ? 400 : (count($labels) * 50 + 100);

    $this->render('statspresencetype', $viewData);
  }
}
