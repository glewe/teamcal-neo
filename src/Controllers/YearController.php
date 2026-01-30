<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;



/**
 * Year Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class YearController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    // Check URL Parameters
    $missingData = false;
    $yyyy        = '';
    $region      = '';
    $user        = '';

    if (isset($_GET['year']) && isset($_GET['region']) && isset($_GET['user'])) {
      $yyyy = sanitize($_GET['year']);
      if (!is_numeric($yyyy) || strlen($yyyy) != 4 || !checkdate(1, 1, intval($yyyy))) {
        $missingData = true;
      }

      $region = sanitize($_GET['region']);
      if (!$this->R->getById($region)) {
        $missingData = true;
      }

      if (strlen($_GET['user'])) {
        $user = sanitize($_GET['user']);
        if ($user !== 'Public' && !$this->U->exists($user)) {
          $missingData = true;
        }
        if ($user === 'Public') {
          $user = '';
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

    if ($this->allConfig['currentYearOnly'] && $yyyy != date('Y')) {
      header("Location: index.php?action=year&year=" . date('Y') . "&region=" . $region . "&user=" . $user);
      die();
    }

    if (!isAllowed($this->CONF['controllers']['year']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $viewData = [];
    $currDate = date('Y-m-d');
    $users    = $this->U->getAll();

    for ($i = 1; $i <= 12; $i++) {
      if (!$this->M->getMonth($yyyy, (string) $i, $this->R->id)) {
        createMonth($yyyy, (string) $i, 'region', $this->R->id);
        if ($this->allConfig['emailNotifications']) {
          sendMonthEventNotifications("created", $yyyy, (string) $i, $this->R->name);
        }
        $this->LOG->logEvent("logMonth", $this->UL->username, "log_month_tpl_created", $this->M->region . ": " . $this->M->year . "-" . $this->M->month);
      }

      if (strlen($user) && !$this->T->getTemplate($user, $yyyy, (string) $i)) {
        createMonth($yyyy, (string) $i, 'user', $user);
      }

      $viewData['monthInfo'][$i] = dateInfo($yyyy, (string) $i);

      for ($d = 1; $d <= $viewData['monthInfo'][$i]['daysInMonth']; $d++) {
        $viewData['month'][$i][$d]['wday']     = $this->M->getWeekday($yyyy, (string) $i, (string) $d, $this->R->id);
        $viewData['month'][$i][$d]['hol']      = $this->M->getHoliday($yyyy, (string) $i, (string) $d, $this->R->id);
        $viewData['month'][$i][$d]['abs']      = strlen($user) ? $this->T->getAbsence($user, $yyyy, (string) $i, (string) $d) : 0;
        $viewData['month'][$i][$d]['symbol']   = '';
        $viewData['month'][$i][$d]['icon']     = '';
        $viewData['month'][$i][$d]['style']    = '';
        $viewData['month'][$i][$d]['absstyle'] = '';

        $color   = '';
        $bgcolor = '';
        $border  = '';

        if ($viewData['month'][$i][$d]['wday'] == 6 || $viewData['month'][$i][$d]['wday'] == 7) {
          $color   = 'color: #' . $this->H->getColor((string) ($viewData['month'][$i][$d]['wday'] - 4)) . ';';
          $bgcolor = 'background-color: #' . $this->H->getBgColor((string) ($viewData['month'][$i][$d]['wday'] - 4)) . ';';
        }

        if ($viewData['month'][$i][$d]['hol']) {
          $color   = 'color: #' . $this->H->getColor((string) $viewData['month'][$i][$d]['hol']) . ';';
          $bgcolor = 'background-color: #' . $this->H->getBgColor((string) $viewData['month'][$i][$d]['hol']) . ';';
        }

        $loopDate = date('Y-m-d', mktime(0, 0, 0, $i, $d, (int) $yyyy));
        if ($loopDate == $currDate) {
          $border = 'border: ' . $this->allConfig['todayBorderSize'] . 'px solid #' . $this->allConfig['todayBorderColor'] . ';';
        }

        if (strlen($color) || strlen($bgcolor) || strlen($border)) {
          $viewData['month'][$i][$d]['style'] = ' style="' . $color . $bgcolor . $border . '"';
        }

        if ($viewData['month'][$i][$d]['abs']) {
          $this->A->get((string) $viewData['month'][$i][$d]['abs']);
          $viewData['month'][$i][$d]['icon']   = $this->A->icon;
          $viewData['month'][$i][$d]['symbol'] = $this->A->symbol;
          if ($this->A->bgtrans) {
            $bgStyle = "";
          }
          else {
            $bgStyle = "background-color: #" . $this->A->bgcolor . ";";
          }
          $viewData['month'][$i][$d]['absstyle'] = ' style="color: #' . $this->A->color . ';' . $bgStyle . '"';
        }
      }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_region'])) {
        header("Location: index.php?action=year&year=" . $yyyy . "&region=" . $_POST['sel_region'] . "&user=" . $user);
        die();
      }
      elseif (isset($_POST['btn_user'])) {
        header("Location: index.php?action=year&year=" . $yyyy . "&region=" . $region . "&user=" . $_POST['sel_user']);
        die();
      }
    }

    $viewData['pageHelp']         = $this->allConfig['pageHelp'];
    $viewData['showAlerts']       = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly']  = $this->allConfig['currentYearOnly'];
    $viewData['showRegionButton'] = $this->allConfig['showRegionButton'];
    $viewData['symbolAsIcon']     = $this->allConfig['symbolAsIcon'];

    $viewData['username']   = $user ?: 'Public';
    $viewData['fullname']   = strlen($user) ? $this->U->getFullname($user) : $this->LANG['role_public'];
    $viewData['year']       = $yyyy;
    $viewData['regionid']   = $this->R->id;
    $viewData['regionname'] = $this->R->name;
    $viewData['regions']    = $this->R->getAll();
    $viewData['users']      = [];

    foreach ($users as $usr) {
      $allowed = false;
      if ($usr['username'] == $this->UL->username) {
        $allowed = true;
      }
      elseif (!$this->U->isHidden($usr['username'])) {
        if (isAllowed("calendarviewall") || isAllowed("calendarviewgroup") && $this->UG->shareGroups($usr['username'], $this->UL->username)) {
          $allowed = true;
        }
      }
      if ($allowed) {
        $viewData['users'][] = ['username' => $usr['username'], 'lastfirst' => $this->U->getLastFirst($usr['username'])];
      }
    }

    $color                = $this->H->getColor('2');
    $bgcolor              = $this->H->getBgColor('2');
    $viewData['satStyle'] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';
    $color                = $this->H->getColor('3');
    $bgcolor              = $this->H->getBgColor('3');
    $viewData['sunStyle'] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';

    $this->render('year', $viewData);
  }
}
