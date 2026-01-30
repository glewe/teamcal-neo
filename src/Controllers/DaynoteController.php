<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

/**
 * Daynote Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class DaynoteController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    $dnDate = '';
    $for    = '';
    $region = '1';
    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];

    if (!isAllowed($this->CONF['controllers']['daynote']->permission) || (isset($_GET['for']) && $_GET['for'] == 'all' && !isAllowed('daynoteglobal'))) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $missingData = false;
    if (isset($_GET['date']) && isset($_GET['for'])) {
      $dnDate = sanitize($_GET['date']);
      $for    = sanitize($_GET['for']);
      if ($for == "all") {
        if (isset($_GET['region'])) {
          $region = sanitize($_GET['region']);
        }
        else {
          $missingData = true;
        }
      }
    }
    else {
      $missingData = true;
    }

    if ($missingData) {
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
      return;
    }

    $viewData               = [];
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];

    $viewData['id']           = '';
    $viewData['date']         = substr($dnDate, 0, 4) . '-' . substr($dnDate, 4, 2) . '-' . substr($dnDate, 6, 2);
    $viewData['month']        = substr($dnDate, 0, 6);
    $viewData['enddate']      = '';
    $viewData['user']         = $for;
    $viewData['userFullname'] = ($for == 'all') ? $this->LANG['all'] : $this->U->getFullname($for);
    $viewData['region']       = $region;
    $viewData['regionName']   = 'Default';
    $viewData['daynote']      = '';
    $viewData['color']        = 'info';
    $viewData['confidential'] = '0';
    $viewData['exists']       = false;
    $regions                  = $this->R->getAll();

    if ($this->D->get($dnDate, $for, (string) $region)) {
      $viewData['id']           = $this->D->id;
      $viewData['date']         = substr($this->D->yyyymmdd, 0, 4) . '-' . substr($this->D->yyyymmdd, 4, 2) . '-' . substr($this->D->yyyymmdd, 6, 2);
      $viewData['user']         = $this->D->username;
      $viewData['region']       = $this->D->region;
      $viewData['regionName']   = $this->R->getNameById($this->D->region);
      $viewData['daynote']      = $this->D->daynote;
      $viewData['color']        = $this->D->color;
      $viewData['confidential'] = $this->D->confidential;
      $viewData['exists']       = true;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $viewData['date']         = $_POST['txt_date'];
      $viewData['daynote']      = $_POST['txt_daynote'];
      $viewData['color']        = $_POST['opt_color'];
      $viewData['confidential'] = isset($_POST['chk_confidential']) ? '1' : '0';

      $inputError = false;
      if (isset($_POST['btn_create']) || isset($_POST['btn_update'])) {
        if (!formInputValid('txt_date', 'required|date'))
          $inputError = true;
        if (!formInputValid('txt_enddate', 'date'))
          $inputError = true;
        if (!formInputValid('txt_daynote', 'required'))
          $inputError = true;
        if (!isset($_POST['sel_regions'])) {
          $inputAlert['regions'] = $this->LANG['alert_input_required'];
          $inputError            = true;
        }
      }

      if (!$inputError) {
        if (isset($_POST['btn_create']) || isset($_POST['btn_update'])) {
          $startDate = str_replace('-', '', $viewData['date']);
          $endDate   = $startDate;
          if (isset($_POST['txt_enddate']) && strlen($_POST['txt_enddate'])) {
            $viewData['enddate'] = $_POST['txt_enddate'];
            $endDate             = str_replace('-', '', $viewData['enddate']);
          }

          $start = new \DateTime($viewData['date']);
          $end   = new \DateTime($viewData['enddate'] ?: $viewData['date']);
          $end->modify('+1 day');
          $interval = new \DateInterval('P1D');
          $period   = new \DatePeriod($start, $interval, $end);

          foreach ($period as $dt) {
            $formattedDate = $dt->format('Ymd');
            $this->D->deleteByDateAndUser($formattedDate, $viewData['user']);
            foreach ((array) $_POST['sel_regions'] as $reg) {
              $this->D->yyyymmdd     = $formattedDate;
              $this->D->username     = $viewData['user'];
              $this->D->region       = $reg;
              $this->D->daynote      = $viewData['daynote'];
              $this->D->color        = $viewData['color'];
              $this->D->confidential = $viewData['confidential'];
              $this->D->create();
            }
          }

          $logentry = ($viewData['user'] == 'all') ? $viewData['date'] . "|" . $this->R->getNameById($viewData['region']) . ": " . substr($viewData['daynote'], 0, 20) . "..." : $viewData['date'] . "|" . $viewData['user'] . ": " . substr($viewData['daynote'], 0, 20) . "...";

          if (isset($_POST['btn_create'])) {
            $this->LOG->logEvent("logDaynote", $this->UL->username, "log_dn_created", $logentry);
            $this->renderAlert('success', $this->LANG['alert_success_title'], $this->LANG['dn_alert_create'], $this->LANG['dn_alert_create_success']);
          }
          else {
            $this->LOG->logEvent("logDaynote", $this->UL->username, "log_dn_updated", $logentry);
            $this->renderAlert('success', $this->LANG['alert_success_title'], $this->LANG['dn_alert_update'], $this->LANG['dn_alert_update_success']);
          }
        }
        elseif (isset($_POST['btn_delete'])) {
          $this->D->deleteByDateAndUser($dnDate, $viewData['user']);
          if (isset($_POST['txt_enddate']) && strlen((string) $_POST['txt_enddate'])) {
            $start = new \DateTime($viewData['date']);
            $end   = new \DateTime($_POST['txt_enddate']);
            if ($end > $start) {
              $end->modify('+1 day');
              $interval = new \DateInterval('P1D');
              $period   = new \DatePeriod($start, $interval, $end);
              foreach ($period as $dt) {
                $this->D->deleteByDateAndUser($dt->format('Ymd'), $viewData['user']);
              }
            }
          }
          $logentry = ($viewData['user'] == 'all') ? $viewData['date'] . "|" . $this->R->getNameById($viewData['region']) . ": " . substr($viewData['daynote'], 0, 20) . "..." : $viewData['date'] . "|" . $viewData['user'] . ": " . substr($viewData['daynote'], 0, 20) . "...";
          $this->LOG->logEvent("logDaynote", $this->UL->username, "log_dn_deleted", $logentry);
          header("Location: index.php?action=daynote&date=" . str_replace('-', '', $viewData['date']) . '&for=' . $viewData['user'] . '&region=' . $viewData['region']);
          die();
        }

        if (isset($_SESSION)) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
      }
      else {
        $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_input'], $this->LANG['dn_alert_failed']);
      }
    }

    if ($viewData['exists']) {
      foreach ($regions as $region) {
        $viewData['regions'][] = ['val' => $region['id'], 'name' => $region['name'], 'selected' => ($this->D->get($dnDate, $for, (string) $region['id']))];
      }
    }
    else {
      foreach ($regions as $region) {
        $viewData['regions'][] = ['val' => $region['id'], 'name' => $region['name'], 'selected' => ($region['id'] == $viewData['region'])];
      }
    }

    $viewData['daynote'] = [
      ['prefix' => 'dn', 'name' => 'date', 'type' => 'date', 'value' => $viewData['date'], 'maxlength' => '10', 'mandatory' => true, 'error' => ($inputAlert['date'] ?? '')],
      ['prefix' => 'dn', 'name' => 'enddate', 'type' => 'date', 'value' => $viewData['enddate'], 'maxlength' => '10', 'mandatory' => false, 'error' => ($inputAlert['enddate'] ?? '')],
      ['prefix' => 'dn', 'name' => 'daynote', 'type' => 'textarea', 'value' => $viewData['daynote'], 'rows' => '10', 'placeholder' => $this->LANG['dn_daynote_placeholder'], 'mandatory' => true, 'error' => ($inputAlert['daynote'] ?? '')],
      ['prefix' => 'dn', 'name' => 'regions', 'type' => 'listmulti', 'values' => $viewData['regions'], 'mandatory' => true, 'error' => ($inputAlert['regions'] ?? '')],
      ['prefix' => 'dn', 'name' => 'color', 'type' => 'radio', 'values' => ['info', 'success', 'warning', 'danger'], 'value' => $viewData['color']],
      ['prefix' => 'dn', 'name' => 'confidential', 'type' => 'check', 'value' => $viewData['confidential']],
    ];

    $this->render('daynote', $viewData);
  }
}
