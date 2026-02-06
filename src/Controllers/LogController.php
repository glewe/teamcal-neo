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

    if (isset($_GET['method']) && $_GET['method'] === 'getStats') {
      $this->handleAjaxStats();
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
      elseif (isset($_POST['btn_statsFilter'])) {
        $this->handleStatsFilter($showAlert, $alertData);
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

    //
    // Prepare statistics data
    //
    $statsTimeframe = 'last_week';
    if (isset($_POST['sel_statsTimeframe'])) {
      $statsTimeframe = $_POST['sel_statsTimeframe'];
    }
    elseif (isset($this->allConfig['statsTimeframe'])) {
      $statsTimeframe = $this->allConfig['statsTimeframe'];
    }

    $statsTypes = [];
    if (isset($_POST['chk_statsTypes'])) {
      $statsTypes = $_POST['chk_statsTypes'];
    }
    elseif (isset($this->allConfig['statsTypes'])) {
      $statsTypesStr = $this->allConfig['statsTypes'];
      if (!empty($statsTypesStr)) {
        $statsTypes = explode(',', $statsTypesStr);
      }
    }

    //
    // Calculate date range based on timeframe
    //
    $statsRange = $this->getStatsRange($statsTimeframe);
    $statsFrom = $statsRange['from'];
    $statsTo = $statsRange['to'];

    //
    // Get statistics data
    //
    $statsData = [];
    if (!empty($statsTypes)) {
      $statsData = $this->LOG->getStatistics($statsFrom, $statsTo, $statsTypes, $statsRange['granularity']);
    }

    $viewData['statsTimeframe'] = $statsTimeframe;
    $viewData['statsTypes'] = $statsTypes;
    $viewData['statsData'] = json_encode($statsData);

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
   * 
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
   * 
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
   * 
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
   * 
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

  //---------------------------------------------------------------------------
  /**
   * Handles statistics filter settings.
   *
   * @param bool  $showAlert Reference to show alert flag
   * @param array $alertData Reference to alert data array
   * 
   * @return void
   */
  private function handleStatsFilter(&$showAlert, &$alertData) {
    $statsBatchConfigs = [];
    
    if (isset($_POST['sel_statsTimeframe'])) {
      $statsBatchConfigs['statsTimeframe'] = $_POST['sel_statsTimeframe'];
    }
    
    if (isset($_POST['chk_statsTypes']) && is_array($_POST['chk_statsTypes'])) {
      $statsBatchConfigs['statsTypes'] = implode(',', $_POST['chk_statsTypes']);
    }
    else {
      $statsBatchConfigs['statsTypes'] = '';
    }
    
    $this->C->saveBatch($statsBatchConfigs);
  }

  //---------------------------------------------------------------------------
  /**
   * Returns the date range and granularity for a given timeframe.
   *
   * @param string $statsTimeframe The timeframe identifier
   *
   * @return array The start and end timestamps and granularity
   */
  private function getStatsRange(string $statsTimeframe): array {
    $statsFrom = '';
    $statsTo = date('Y-m-d') . ' 23:59:59';
    $granularity = 'day';

    switch ($statsTimeframe) {
      case 'last_day':
        // Today: from 00:00:00 today to 23:59:59 today
        $statsFrom = date('Y-m-d') . ' 00:00:00';
        $statsTo = date('Y-m-d') . ' 23:59:59';
        $granularity = 'hour';
        break;
      case 'last_week':
        // This Week: from Monday to Sunday
        $statsFrom = date('Y-m-d', strtotime('monday this week')) . ' 00:00:00';
        $statsTo = date('Y-m-d', strtotime('sunday this week')) . ' 23:59:59';
        break;
      case 'last_month':
        // This Month: from 1st to last day of current month
        $statsFrom = date('Y-m-01') . ' 00:00:00';
        $statsTo = date('Y-m-t') . ' 23:59:59';
        break;
      case 'last_year':
        // This Year: from Jan 1 to Dec 31 of current year
        $statsFrom = date('Y-01-01') . ' 00:00:00';
        $statsTo = date('Y-12-31') . ' 23:59:59';
        break;
      case 'last_quarter':
        // This Quarter: from 1st day of current quarter to last day of current quarter
        $logToday = dateInfo(date("Y"), date("m"), date("d"));
        $statsFrom = $logToday['firstOfQuarter'] . ' 00:00:00';
        $statsTo = $logToday['lastOfQuarter'] . ' 23:59:59';
        break;
      case 'overall':
        // Overall: from the earliest recorded entry to now
        $statsFrom = $this->LOG->getMinTimestamp();
        $statsTo = date('Y-m-d H:i:s');
        break;
      default:
        // Default to This Week
        $statsFrom = date('Y-m-d', strtotime('monday this week')) . ' 00:00:00';
        $statsTo = date('Y-m-d', strtotime('sunday this week')) . ' 23:59:59';
    }

    return ['from' => $statsFrom, 'to' => $statsTo, 'granularity' => $granularity];
  }

  //---------------------------------------------------------------------------
  /**
   * Handles AJAX request for statistics data.
   *
   * @return void
   */
  private function handleAjaxStats(): void {
    $statsTimeframe = $_POST['timeframe'] ?? 'last_week';
    $statsTypes = $_POST['types'] ?? [];

    $statsRange = $this->getStatsRange($statsTimeframe);
    $statsData = $this->LOG->getStatistics($statsRange['from'], $statsRange['to'], $statsTypes, $statsRange['granularity']);

    header('Content-Type: application/json');
    echo json_encode($statsData);
    exit;
  }
}
