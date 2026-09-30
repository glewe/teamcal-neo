<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\CalendarDayModel;

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
      if (!$this->regionModel->getById($region)) {
        $missingData = true;
      }

      if (strlen($_GET['user'])) {
        $user = sanitize($_GET['user']);
        if ($user !== 'Public' && !$this->userModel->exists($user)) {
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
    $users    = $this->userModel->getAll();

    for ($i = 1; $i <= 12; $i++) {
      $weekdayGrid = CalendarDayModel::buildWeekdayGrid($yyyy, (string) $i);
      $holidayMap  = $this->calendarDayModel->getMonthMap($yyyy, (string) $i, $this->regionModel->id);

      $monthMap = strlen($user) ? $this->absenceDayModel->getMonthMap($user, $yyyy, (string) $i) : [];

      $viewData['monthInfo'][$i] = dateInfo($yyyy, (string) $i);

      for ($d = 1; $d <= $viewData['monthInfo'][$i]['daysInMonth']; $d++) {
        $viewData['month'][$i][$d]['wday']     = $weekdayGrid['wday' . $d];
        $viewData['month'][$i][$d]['hol']      = $holidayMap[$d] ?? 1;
        $viewData['month'][$i][$d]['abs']      = $monthMap[$d] ?? 0;
        $viewData['month'][$i][$d]['symbol']   = '';
        $viewData['month'][$i][$d]['icon']     = '';
        $viewData['month'][$i][$d]['style']    = '';
        $viewData['month'][$i][$d]['absstyle'] = '';

        $color   = '';
        $bgcolor = '';
        $border  = '';

        if ($viewData['month'][$i][$d]['wday'] == 6 || $viewData['month'][$i][$d]['wday'] == 7) {
          $color   = 'color: #' . $this->holidayModel->getColor((string) ($viewData['month'][$i][$d]['wday'] - 4)) . ';';
          $bgcolor = 'background-color: #' . $this->holidayModel->getBgColor((string) ($viewData['month'][$i][$d]['wday'] - 4)) . ';';
        }

        if ($viewData['month'][$i][$d]['hol'] !== 1) {
          $color   = 'color: #' . $this->holidayModel->getColor((string) $viewData['month'][$i][$d]['hol']) . ';';
          $bgcolor = 'background-color: #' . $this->holidayModel->getBgColor((string) $viewData['month'][$i][$d]['hol']) . ';';
        }

        $loopDate = date('Y-m-d', mktime(0, 0, 0, $i, $d, (int) $yyyy));
        if ($loopDate == $currDate) {
          $border = 'border: ' . $this->allConfig['todayBorderSize'] . 'px solid #' . $this->allConfig['todayBorderColor'] . ';';
        }

        if ($color !== '' || $bgcolor !== '' || $border !== '') {
          $viewData['month'][$i][$d]['style'] = ' style="' . $color . $bgcolor . $border . '"';
        }

        if ($viewData['month'][$i][$d]['abs']) {
          $this->absenceModel->get((string) $viewData['month'][$i][$d]['abs']);
          $viewData['month'][$i][$d]['icon']   = $this->absenceModel->icon;
          $viewData['month'][$i][$d]['symbol'] = $this->absenceModel->symbol;
          if ($this->absenceModel->bgtrans) {
            $bgStyle = "";
          }
          else {
            $bgStyle = "background-color: #" . $this->absenceModel->bgcolor . ";";
          }
          $viewData['month'][$i][$d]['absstyle'] = ' style="color: #' . $this->absenceModel->color . ';' . $bgStyle . '"';
        }
      }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
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
    $viewData['fullname']   = strlen($user) ? $this->userModel->getFullname($user) : $this->LANG['role_public'];
    $viewData['year']       = $yyyy;
    $viewData['regionid']   = $this->regionModel->id;
    $viewData['regionname'] = $this->regionModel->name;
    $viewData['regions']    = $this->regionModel->getAll();
    $viewData['users']      = [];

    foreach ($users as $usr) {
      $allowed = false;
      if ($usr['username'] == $this->userLoggedIn->username) {
        $allowed = true;
      }
      elseif (!$this->userModel->isHidden($usr['username'])) {
        if (isAllowed("calendarviewall") || isAllowed("calendarviewgroup") && $this->userGroupModel->shareGroups($usr['username'], $this->userLoggedIn->username)) {
          $allowed = true;
        }
      }
      if ($allowed) {
        $viewData['users'][] = ['username' => $usr['username'], 'lastfirst' => $this->userModel->getLastFirst($usr['username'])];
      }
    }

    $color                = $this->holidayModel->getColor('2');
    $bgcolor              = $this->holidayModel->getBgColor('2');
    $viewData['satStyle'] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';
    $color                = $this->holidayModel->getColor('3');
    $bgcolor              = $this->holidayModel->getBgColor('3');
    $viewData['sunStyle'] = ' style="color: #' . $color . '; background-color: #' . $bgcolor . ';"';

    $this->render('year', $viewData);
  }
}
