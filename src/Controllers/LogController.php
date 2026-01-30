<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;

/**
 * Log Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class LogController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['log']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    // Check License
    $alertData        = [];
    $showAlert        = false;
    $licExpiryWarning = (int) $this->allConfig['licExpiryWarning'];
    $LIC              = new LicenseModel();
    $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);

    $viewData               = [];
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];

    $viewData['logPeriod']      = '';
    $viewData['logSearchUser']  = '';
    $viewData['logSearchEvent'] = '';
    $sort                       = "DESC";
    if (isset($_GET['sort']) && strtoupper($_GET['sort']) == "ASC") {
      $sort = "ASC";
    }
    $eventsPerPage = 50;
    $currentPage   = 1;
    if (isset($_GET['page'])) {
      $currentPage = intval(sanitize($_GET['page']));
    }

    $logToday = dateInfo(date("Y"), date("m"), date("d"));
    $this->C->save("logto", $logToday['ISO'] . ' 23:59:59.999999');

    $logtypes = [
      'Calendar',
      'CalendarOptions',
      'Config',
      'Database',
      'Daynote',
      'Group',
      'Import',
      'Login',
      'Log',
      'Message',
      'Month',
      'Pattern',
      'Permission',
      'Region',
      'Registration',
      'Role',
      'Upload',
      'User'
    ];

    $inputAlert = [];
    $alertData  = [];
    $showAlert  = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_filter'])) {
        $this->handleRefresh($logToday, $showAlert, $alertData, $viewData);
      }
      elseif (isset($_POST['btn_reset'])) {
        $this->handleReset($logToday, $showAlert, $alertData);
      }
      elseif (isset($_POST['btn_clear'])) {
        $this->handleClear($showAlert, $alertData);
      }
      elseif (isset($_POST['btn_logSave'])) {
        $this->handleSave($logtypes, $showAlert, $alertData);
      }

      if (isset($_SESSION)) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
    }

    $viewData['alertData'] = $alertData;
    $viewData['showAlert'] = $showAlert;

    $periodFrom = $this->allConfig['logfrom'];
    $periodTo   = $this->allConfig['logto'];
    $logPeriod  = $this->allConfig['logperiod'];

    $logType = '%';
    if (isset($this->allConfig['logtype']) && $this->allConfig['logtype'] != '') {
      $logType = $this->allConfig['logtype'];
    }

    $logSearchUser = '%';
    if (isset($this->allConfig['logsearchuser']) && $this->allConfig['logsearchuser'] != '') {
      $logSearchUser = '%';
    }

    $logSearchEvent = '%';
    if (isset($this->allConfig['logsearchevent']) && $this->allConfig['logsearchevent'] != '') {
      $logSearchEvent = '%';
    }

    $events = $this->LOG->read($sort, $periodFrom, $periodTo, $logType, $logSearchUser, $logSearchEvent);

    $viewData['events'] = [];
    if (count($events)) {
      foreach ($events as $event) {
        if ($this->allConfig['logfilter' . substr($event['type'], 3)]) {
          $viewData['events'][] = $event;
        }
      }
    }

    $viewData['types']         = $logtypes;
    $viewData['logperiod']     = $logPeriod;
    $viewData['logfrom']       = substr($periodFrom, 0, 10);
    $viewData['logto']         = substr($periodTo, 0, 10);
    $viewData['logtype']       = $logType;
    $viewData['numEvents']     = count($viewData['events']);
    $viewData['eventsPerPage'] = $eventsPerPage;
    $viewData['sort']          = $sort;
    if ($viewData['numEvents']) {
      $viewData['numPages'] = ceil($viewData['numEvents'] / $viewData['eventsPerPage']);
    }
    else {
      $viewData['numPages'] = 0;
    }
    $viewData['currentPage'] = $currentPage;

    $this->render('log', $viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Refreshes the log filter settings.
   *
   * @param array $logToday  Array containing today's date info
   * @param bool  $showAlert Reference to show alert flag
   * @param array $alertData Reference to alert data array
   * @param array $viewData  Reference to view data array
   * @return void
   */
  private function handleRefresh($logToday, &$showAlert, &$alertData, &$viewData) {
    $refreshBatchConfigs = [];
    switch ($_POST['sel_logPeriod']) {
      case "curr_month":
        $refreshBatchConfigs["logperiod"] = "curr_month";
        $refreshBatchConfigs["logfrom"] = $logToday['firstOfMonth'] . ' 00:00:00.000000';
        $refreshBatchConfigs["logto"] = $logToday['lastOfMonth'] . ' 23:59:59.999999';
        break;
      case "curr_quarter":
        $refreshBatchConfigs["logperiod"] = "curr_quarter";
        $refreshBatchConfigs["logfrom"] = $logToday['firstOfQuarter'] . ' 00:00:00.000000';
        $refreshBatchConfigs["logto"] = $logToday['lastOfQuarter'] . ' 23:59:59.999999';
        break;
      case "curr_half":
        $refreshBatchConfigs["logperiod"] = "curr_half";
        $refreshBatchConfigs["logfrom"] = $logToday['firstOfHalf'] . ' 00:00:00.000000';
        $refreshBatchConfigs["logto"] = $logToday['lastOfHalf'] . ' 23:59:59.999999';
        break;
      case "curr_year":
        $refreshBatchConfigs["logperiod"] = "curr_year";
        $refreshBatchConfigs["logfrom"] = $logToday['firstOfYear'] . ' 00:00:00.000000';
        $refreshBatchConfigs["logto"] = $logToday['lastOfYear'] . ' 23:59:59.999999';
        break;
      case "curr_all":
        $refreshBatchConfigs["logperiod"] = "curr_all";
        $refreshBatchConfigs["logfrom"] = '2004-01-01 00:00:00.000000';
        $refreshBatchConfigs["logto"] = $logToday['ISO'] . ' 23:59:59.999999';
        break;
      case "custom":
      default:
        $viewData['logPeriod'] = 'custom';
        $refreshBatchConfigs["logperiod"] = "custom";
        if (!isset($_POST['txt_logPeriodFrom']) || !isset($_POST['txt_logPeriodTo']) || !strlen($_POST['txt_logPeriodFrom']) || !strlen($_POST['txt_logPeriodTo'])) {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['alert_input_validation_subject'];
          $alertData['text']    = $this->LANG['alert_log_date_missing_text'];
          $alertData['help']    = $this->LANG['alert_log_date_missing_help'];
        }
        elseif (!preg_match("/(\d{4})-(\d{2})-(\d{2})/", $_POST['txt_logPeriodFrom']) || !preg_match("/(\d{4})-(\d{2})-(\d{2})/", $_POST['txt_logPeriodTo'])) {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['alert_input_validation_subject'];
          $alertData['text']    = $this->LANG['alert_date_format_text'];
          $alertData['help']    = $this->LANG['alert_date_format_help'];
        }
        elseif ($_POST['txt_logPeriodFrom'] > $_POST['txt_logPeriodTo']) {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['alert_input_validation_subject'];
          $alertData['text']    = $this->LANG['alert_date_endbeforestart_text'];
          $alertData['help']    = $this->LANG['alert_date_endbeforestart_help'];
        }
        else {
          $refreshBatchConfigs["logfrom"] = $_POST['txt_logPeriodFrom'] . ' 00:00:00.000000';
          $refreshBatchConfigs["logto"]   = $_POST['txt_logPeriodTo'] . ' 23:59:59.999999';
        }
        break;
    }

    $refreshBatchConfigs["logtype"] = $_POST['sel_logType'];

    if (isset($_POST['txt_logSearchUser']) && strlen($_POST['txt_logSearchUser'])) {
      $viewData['logSearchUser']            = sanitize($_POST['txt_logSearchUser']);
      $refreshBatchConfigs["logsearchuser"] = '%' . $viewData['logSearchUser'] . '%';
    }

    if (isset($_POST['txt_logSearchEvent']) && strlen($_POST['txt_logSearchEvent'])) {
      $viewData['logSearchEvent']            = sanitize($_POST['txt_logSearchEvent']);
      $refreshBatchConfigs["logsearchevent"] = '%' . $viewData['logSearchEvent'] . '%';
    }

    $this->C->saveBatch($refreshBatchConfigs);
    $this->_instances['allConfig'] = array_merge($this->allConfig, $refreshBatchConfigs);
  }

  //---------------------------------------------------------------------------
  /**
   * Saves the log settings.
   *
   * @param array $logtypes  Array of log types
   * @param bool  $showAlert Reference to show alert flag
   * @param array $alertData Reference to alert data array
   * @return void
   */
  private function handleSave($logtypes, &$showAlert, &$alertData) {
    $newConfig = [];
    foreach ($logtypes as $lt) {
      $newConfig["log" . $lt]       = (isset($_POST['chk_log' . $lt]) && $_POST['chk_log' . $lt]) ? "1" : "0";
      $newConfig["logfilter" . $lt] = (isset($_POST['chk_logfilter' . $lt]) && $_POST['chk_logfilter' . $lt]) ? "1" : "0";
      $newConfig["logcolor" . $lt]  = $_POST['opt_logcolor' . $lt] ? $_POST['opt_logcolor' . $lt] : "default";
    }
    $this->C->saveBatch($newConfig);
    $this->LOG->logEvent("logLog", $this->UL->username, "log_log_updated");
    header("Location: index.php?action=log");
    die();
  }

  //---------------------------------------------------------------------------
  /**
   * Clears the log entries within the selected period.
   *
   * @param bool  $showAlert Reference to show alert flag
   * @param array $alertData Reference to alert data array
   * @return void
   */
  private function handleClear(&$showAlert, &$alertData) {
    $periodFrom = $this->allConfig['logfrom'];
    $periodTo   = $this->allConfig['logto'];
    $this->LOG->delete($periodFrom, $periodTo);
    $this->LOG->logEvent("logLog", $this->UL->username, "log_log_cleared");
    header("Location: index.php?action=log");
    die();
  }

  //---------------------------------------------------------------------------
  /**
   * Resets the log filter settings to default.
   *
   * @param array $logToday  Array containing today's date info
   * @param bool  $showAlert Reference to show alert flag
   * @param array $alertData Reference to alert data array
   * @return void
   */
  private function handleReset($logToday, &$showAlert, &$alertData) {
    $resetBatchConfigs = [
      "logperiod"      => "curr_all",
      "logfrom"        => '2004-01-01 00:00:00.000000',
      "logto"          => $logToday['ISO'] . ' 23:59:59.999999',
      "logtype"        => "%",
      "logsearchuser"  => "%",
      "logsearchevent" => "%"
    ];
    $this->C->saveBatch($resetBatchConfigs);
    $this->LOG->logEvent("logLog", $this->UL->username, "log_log_reset");
    header("Location: index.php?action=log");
    die();
  }
}
