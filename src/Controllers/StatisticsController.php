<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

/**
 * Statistics controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class StatisticsController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    // Check permission (User says there is only one permission 'statistics')
    if (!isAllowed('statistics')) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $viewData = [
        'pageHelp'   => $this->allConfig['pageHelp'],
        'showAlerts' => $this->allConfig['showAlerts'],
        'csrf_token' => $_SESSION['csrf_token'] ?? '',
        'controller' => 'statistics', // For view logic using controller config
    ];

    // Check for AJAX request to fetch tab content
    if (isset($_GET['ajax']) && isset($_GET['tab'])) {
        $this->handleAjaxRequest($_GET['tab']);
        return;
    }

    $this->render('statistics', $viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Handles AJAX requests for specific tabs.
   *
   * @param string $tab The tab identifier
   * @return void
   */
  private function handleAjaxRequest(string $tab): void
  {
      switch ($tab) {
          case 'absence':
              $this->getAbsenceStats();
              break;
          case 'abstype':
              $this->getAbsenceTypeStats();
              break;
          case 'presence':
              $this->getPresenceStats();
              break;
          case 'presencetype':
              $this->getPresenceTypeStats();
              break;
          case 'remainder':
              $this->getRemainderStats();
              break;
          case 'trends':
              $this->getAbsenceTrends();
              break;
          case 'dayofweek':
              $this->getDayOfWeekStats();
              break;
          case 'duration':
              $this->getDurationHistogram();
              break;
          default:
              echo '<div class="alert alert-danger">Invalid tab requested.</div>';
              break;
      }
      exit;
  }

  //---------------------------------------------------------------------------
  /**
   * Renders the alert view.
   *
   * @param string $type    Alert type (success, warning, danger, info)
   * @param string $title   Alert title
   * @param string $subject Alert subject
   * @param string $text    Alert text
   * @param string $help    Alert help text
   *
   * @return void
   */
  protected function renderAlert(string $type, string $title, string $subject, string $text, string $help = ''): void {
    if (isset($_GET['ajax'])) {
      $alertData = [
        'type'    => $type,
        'title'   => $title,
        'subject' => $subject,
        'text'    => $text,
        'help'    => $help
      ];
      $this->render('fragments/alert', ['alertData' => $alertData]);
      return;
    }
    
    parent::renderAlert($type, $title, $subject, $text, $help);
  }

  //---------------------------------------------------------------------------
  /**
   * Get Absence Statistics fragment.
   *
   * @return void
   */
  private function getAbsenceStats(): void {
    
    // Check permission - redundant since main controller checks 'statistics'
    // but good practice if tabs had granular permissions.
    // However, since all use 'statistics', we skip duplicate check.

    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];

    $viewData['labels']   = "";
    $viewData['data']     = "";
    $allAbsences          = $this->A->getAll();
    $viewData['absences'] = array_filter($allAbsences, function ($abs) {
      return !((bool) $abs['counts_as_present']);
    });
    $viewData['groups']   = $this->G->getAll('DESC');
    $viewData['absid']    = 'all';
    $viewData['groupid']  = 'all';
    $viewData['period']   = 'year';
    $viewData['from']     = date("Y") . '-01-01';
    $viewData['to']       = date("Y") . '-12-31';
    $viewData['yaxis']    = 'users';

    $colorMap          = [
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
    $rawColor          = $this->allConfig['statsDefaultColorAbsences'] ?? 'red';
    $viewData['color'] = $colorMap[$rawColor] ?? $rawColor;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);
      // Check CSRF token - assuming it's passed via AJAX (FormData includes hidden inputs)
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
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
          $viewData['absid']   = $_POST['sel_absence'];
          $viewData['groupid'] = $_POST['sel_group'];
          $viewData['yaxis']   = $_POST['opt_yaxis'];
          $viewData['period']  = $_POST['sel_period'];
          if ($viewData['period'] == 'custom') {
            $viewData['from'] = $_POST['txt_from'];
            $viewData['to']   = $_POST['txt_to'];
          }
          $viewData['color'] = $_POST['sel_color'];
        }
        // Do NOT regenerate CSRF token for AJAX partial updates unless we send it back 
        // and update all forms on page. Keeping the session token valid is easier.
        // if (isset($_SESSION)) {
        //   $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        // }
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

    $viewData['absName']     = ($viewData['absid'] == 'all') ? $this->LANG['all'] : $this->A->getName($viewData['absid']);
    $viewData['groupName']   = ($viewData['groupid'] == "all") ? $this->LANG['all'] : $this->G->getNameById($viewData['groupid']);
    $viewData['groupName']  .= ($viewData['yaxis'] == "users") ? ' ' . $this->LANG['stats_byusers'] : ' ' . $this->LANG['stats_bygroups'];
    $viewData['periodName']  = $viewData['from'] . ' - ' . $viewData['to'];

    $labels           = [];
    $data             = [];
    $filteredAbsences = array_filter($viewData['absences'], function ($abs) {
      return $this->A->get((string) $abs['id']) && !$this->A->counts_as_present;
    });
    $countFrom        = str_replace('-', '', $viewData['from']);
    $countTo          = str_replace('-', '', $viewData['to']);

    $viewData['total'] = 0;
    if ($viewData['yaxis'] == 'users') {
      $users = ($viewData['groupid'] == "all")
        ? $this->U->getAll('lastname', 'firstname', 'ASC', false, false)
        : $this->UG->getAllForGroup((string) $viewData['groupid']);

      foreach ($users as &$user) {
        if (!isset($user['firstname']) || !isset($user['lastname'])) {
          $this->U->findByName($user['username']);
          $user['firstname'] = $this->U->firstname;
          $user['lastname']  = $this->U->lastname;
        }
        $labels[] = '"' . ($user['firstname'] ? $user['lastname'] . ", " . $user['firstname'] : $user['lastname']) . '"';
        $count    = 0;
        if ($viewData['absid'] == 'all') {
          foreach ($filteredAbsences as $abs) {
            $count += $this->AbsenceService->countAbsence($user['username'], (string) $abs['id'], $countFrom, $countTo, false, false);
          }
        }
        else {
          $count += $this->AbsenceService->countAbsence($user['username'], (string) $viewData['absid'], $countFrom, $countTo, false, false);
        }
        $data[]             = $count;
        $viewData['total'] += $count;
      }
    }
    else {
      $groups = ($viewData['groupid'] == "all") ? $viewData['groups'] : [$this->G->getRowById($viewData['groupid'])];
      foreach ($groups as $group) {
        $labels[] = '"' . $group['name'] . '"';
        $users    = $this->UG->getAllforGroup((string) $group['id']);
        $count    = 0;
        foreach ($users as $user) {
          if ($viewData['absid'] == 'all') {
            foreach ($filteredAbsences as $abs) {
              $count += $this->AbsenceService->countAbsence($user['username'], (string) $abs['id'], $countFrom, $countTo, false, false);
            }
          }
          else {
            $count += $this->AbsenceService->countAbsence($user['username'], (string) $viewData['absid'], $countFrom, $countTo, false, false);
          }
        }
        $data[]             = $count;
        $viewData['total'] += $count;
      }
    }

    $viewData['labels'] = implode(',', $labels);
    $viewData['data']   = implode(',', $data);
    $viewData['height'] = count($labels) * 50 + 100;
    
    // Set controller for view logic in fragment
    $viewData['controller'] = 'statistics';

    $this->render('fragments/stats_absence', $viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Get Absence Type Statistics fragment.
   *
   * @return void
   */
  private function getAbsenceTypeStats(): void {
    
    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];

    $viewData['labels']         = "";
    $viewData['data']           = "";
    $viewData['absences']       = $this->A->getAll();
    $viewData['groups']         = $this->G->getAll('DESC');
    $viewData['groupid']        = 'all';
    $viewData['period']         = 'year';
    $viewData['from']           = date("Y") . '-01-01';
    $viewData['to']             = date("Y") . '-12-31';
    $viewData['yaxis']          = 'users';
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
    $rawColor                   = $this->allConfig['statsDefaultColorAbsencetype'] ?? 'cyan';
    $viewData['color']          = $colorMap[$rawColor] ?? $rawColor;
    $viewData['showAsPieChart'] = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
         $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
         return;
      }

      $inputError = false;
      if (isset($_POST['btn_apply'])) {
        if (!formInputValid('txt_from', 'date')) $inputError = true;
        if (!formInputValid('txt_to', 'date')) $inputError = true;
        if (!formInputValid('txt_scaleSmart', 'numeric')) $inputError = true;
        if (!formInputValid('txt_scaleMax', 'numeric')) $inputError = true;
        if (!formInputValid('txt_colorHex', 'hexadecimal')) $inputError = true;
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
        } else {
          $viewData['from'] = date("Y") . '-07-01';
          $viewData['to']   = date("Y") . '-12-31';
        }
        break;
      case 'quarter':
        if (date("n") <= 3) {
          $viewData['from'] = date("Y") . '-01-01';
          $viewData['to']   = date("Y") . '-03-31';
        } elseif (date("n") <= 6) {
          $viewData['from'] = date("Y") . '-04-01';
          $viewData['to']   = date("Y") . '-06-30';
        } elseif (date("n") <= 9) {
          $viewData['from'] = date("Y") . '-07-01';
          $viewData['to']   = date("Y") . '-09-30';
        } else {
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

    $filteredAbsences = array_filter($viewData['absences'], function ($abs) {
      return $this->A->get((string) $abs['id']) && !$this->A->counts_as_present;
    });

    $countFrom = str_replace('-', '', $viewData['from']);
    $countTo   = str_replace('-', '', $viewData['to']);

    $viewData['total'] = 0;
    foreach ($filteredAbsences as $abs) {
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

    // Set controller for view logic in fragment
    $viewData['controller'] = 'statistics';

    $this->render('fragments/stats_abstype', $viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Get Presence Statistics fragment.
   *
   * @return void
   */
  private function getPresenceStats(): void {
    
    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];

    $viewData['labels']   = "";
    $viewData['data']     = "";
    $allAbsences          = $this->A->getAll();
    $viewData['absences'] = array_filter($allAbsences, function ($abs) {
      return (bool) $abs['counts_as_present'];
    });
    $viewData['groups']   = $this->G->getAll('DESC');
    $viewData['region']   = '1';
    $viewData['absid']    = 'all';
    $viewData['groupid']  = 'all';
    $viewData['period']   = 'year';
    $viewData['from']     = date("Y") . '-01-01';
    $viewData['to']       = date("Y") . '-12-31';
    $viewData['yaxis']    = 'users';

    $colorMap          = [
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
    $rawColor          = $this->allConfig['statsDefaultColorPresences'] ?? 'green';
    $viewData['color'] = $colorMap[$rawColor] ?? $rawColor;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
         $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
         return;
      }

      $inputError = false;
      if (isset($_POST['btn_apply'])) {
        if (!formInputValid('txt_from', 'date')) $inputError = true;
        if (!formInputValid('txt_to', 'date')) $inputError = true;
        if (!formInputValid('txt_scaleSmart', 'numeric')) $inputError = true;
        if (!formInputValid('txt_scaleMax', 'numeric')) $inputError = true;
        if (!formInputValid('txt_colorHex', 'hexadecimal')) $inputError = true;
      }

      if (!$inputError) {
        if (isset($_POST['btn_apply'])) {
          $viewData['absid']   = $_POST['sel_absence'];
          $viewData['groupid'] = $_POST['sel_group'];
          $viewData['yaxis']   = $_POST['opt_yaxis'];
          $viewData['period']  = $_POST['sel_period'];
          if ($viewData['period'] == 'custom') {
            $viewData['from'] = $_POST['txt_from'];
            $viewData['to']   = $_POST['txt_to'];
          }
          $viewData['color'] = $_POST['sel_color'];
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
        } else {
          $viewData['from'] = date("Y") . '-07-01';
          $viewData['to']   = date("Y") . '-12-31';
        }
        break;
      case 'quarter':
        if (date("n") <= 3) {
          $viewData['from'] = date("Y") . '-01-01';
          $viewData['to']   = date("Y") . '-03-31';
        } elseif (date("n") <= 6) {
          $viewData['from'] = date("Y") . '-04-01';
          $viewData['to']   = date("Y") . '-06-30';
        } elseif (date("n") <= 9) {
          $viewData['from'] = date("Y") . '-07-01';
          $viewData['to']   = date("Y") . '-09-30';
        } else {
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

    $viewData['absName']     = ($viewData['absid'] == 'all') ? $this->LANG['all'] : $this->A->getName($viewData['absid']);
    $viewData['groupName']   = ($viewData['groupid'] == "all") ? $this->LANG['all'] : $this->G->getNameById($viewData['groupid']);
    $viewData['groupName']  .= ($viewData['yaxis'] == "users") ? ' ' . $this->LANG['stats_byusers'] : ' ' . $this->LANG['stats_bygroups'];
    $viewData['periodName']  = $viewData['from'] . ' - ' . $viewData['to'];

    $labels           = [];
    $data             = [];
    $filteredAbsences = array_filter($allAbsences, function ($abs) {
      return !((bool) $abs['counts_as_present']);
    });
    $countFrom        = str_replace('-', '', $viewData['from']);
    $countTo          = str_replace('-', '', $viewData['to']);
    $businessDays     = $this->AbsenceService->countBusinessDays($countFrom, $countTo, $viewData['region']);

    $viewData['total'] = 0;
    if ($viewData['yaxis'] == 'users') {
      $users = ($viewData['groupid'] == "all")
        ? $this->U->getAll('lastname', 'firstname', 'ASC', false, false)
        : $this->UG->getAllForGroup((string) $viewData['groupid']);

      foreach ($users as &$user) {
        if (!isset($user['firstname']) || !isset($user['lastname'])) {
          $this->U->findByName($user['username']);
          $user['firstname'] = $this->U->firstname;
          $user['lastname']  = $this->U->lastname;
        }
        $userAbsences = 0;
        $labels[]     = '"' . ($user['firstname'] ? $user['lastname'] . ", " . $user['firstname'] : $user['lastname']) . '"';
        if ($viewData['absid'] == 'all') {
          foreach ($filteredAbsences as $abs) {
            $userAbsences += $this->AbsenceService->countAbsence($user['username'], (string) $abs['id'], $countFrom, $countTo, false, false);
          }
        }
        else {
          $userAbsences += $this->AbsenceService->countAbsence($user['username'], (string) $viewData['absid'], $countFrom, $countTo, false, false);
        }

        if ($viewData['absid'] == 'all') {
          $userPresences = $businessDays - $userAbsences;
        }
        else {
          $userPresences = $userAbsences;
        }

        $data[]             = $userPresences;
        $viewData['total'] += $userPresences;
      }
    }
    else {
      $groups = ($viewData['groupid'] == "all") ? $viewData['groups'] : [$this->G->getRowById($viewData['groupid'])];
      foreach ($groups as $group) {
        $groupPresences = 0;
        $labels[]       = '"' . $group['name'] . '"';
        $users          = $this->UG->getAllForGroup((string) $group['id']);
        foreach ($users as $user) {
          $userAbsences = 0;
          if ($viewData['absid'] == 'all') {
            foreach ($filteredAbsences as $abs) {
              $userAbsences += $this->AbsenceService->countAbsence($user['username'], (string) $abs['id'], $countFrom, $countTo, false, false);
            }
          }
          else {
            $userAbsences += $this->AbsenceService->countAbsence($user['username'], (string) $viewData['absid'], $countFrom, $countTo, false, false);
          }
          if ($viewData['absid'] == 'all') {
            $userPresences = $businessDays - $userAbsences;
          }
          else {
            $userPresences = $userAbsences;
          }
          $groupPresences += $userPresences;
        }
        $data[]             = $groupPresences;
        $viewData['total'] += $groupPresences;
      }
    }

    $viewData['labels'] = implode(',', $labels);
    $viewData['data']   = implode(',', $data);
    $viewData['height'] = count($labels) * 50 + 100;
    
    // Set controller for view logic in fragment
    $viewData['controller'] = 'statistics';

    $this->render('fragments/stats_presence', $viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Get Presence Type Statistics fragment.
   *
   * @return void
   */
  private function getPresenceTypeStats(): void {
    
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
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
         $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
         return;
      }

      $inputError = false;
      if (isset($_POST['btn_apply'])) {
        if (!formInputValid('txt_from', 'date')) $inputError = true;
        if (!formInputValid('txt_to', 'date')) $inputError = true;
        if (!formInputValid('txt_scaleSmart', 'numeric')) $inputError = true;
        if (!formInputValid('txt_scaleMax', 'numeric')) $inputError = true;
        if (!formInputValid('txt_colorHex', 'hexadecimal')) $inputError = true;
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
        } else {
          $viewData['from'] = date("Y") . '-07-01';
          $viewData['to']   = date("Y") . '-12-31';
        }
        break;
      case 'quarter':
        if (date("n") <= 3) {
          $viewData['from'] = date("Y") . '-01-01';
          $viewData['to']   = date("Y") . '-03-31';
        } elseif (date("n") <= 6) {
          $viewData['from'] = date("Y") . '-04-01';
          $viewData['to']   = date("Y") . '-06-30';
        } elseif (date("n") <= 9) {
          $viewData['from'] = date("Y") . '-07-01';
          $viewData['to']   = date("Y") . '-09-30';
        } else {
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
    
    // Set controller for view logic in fragment
    $viewData['controller'] = 'statistics';

    $this->render('fragments/stats_presencetype', $viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Get Remainder Statistics fragment.
   *
   * @return void
   */
  private function getRemainderStats(): void {
    
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
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
         $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
         return;
      }

      $inputError = false;
      if (isset($_POST['btn_apply'])) {
        if (!formInputValid('txt_periodYear', 'numeric')) $inputError = true;
        if (!formInputValid('txt_scaleSmart', 'numeric')) $inputError = true;
        if (!formInputValid('txt_scaleMax', 'numeric')) $inputError = true;
        if (!formInputValid('txt_colorHex', 'hexadecimal')) $inputError = true;
      }

      if (!$inputError) {
        if (isset($_POST['btn_apply'])) {
          $viewData['groupid'] = $_POST['sel_group'];
          if (isset($_POST['sel_year'])) {
             $viewData['year'] = $_POST['sel_year'];
          }
          $viewData['color']   = $_POST['sel_color'];
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

    // Set controller for view logic in fragment
    $viewData['controller'] = 'statistics';

    $this->render('fragments/stats_remainder', $viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Get Absence Trends Statistics fragment.
   *
   * @return void
   */
  private function getAbsenceTrends(): void {

    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];

    $viewData['absences'] = $this->A->getAll();
    $viewData['groups']   = $this->G->getAll('DESC');

    $viewData['absid']   = 'all';
    $viewData['groupid'] = 'all';
    $viewData['year']    = date("Y");
    $viewData['color']   = 'yellow';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
         $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
         return;
      }

      $inputError = false;
      if (isset($_POST['btn_apply'])) {
          // Add validations if needed
      }

      if (!$inputError) {
        if (isset($_POST['btn_apply'])) {
          $viewData['absid']   = $_POST['sel_absence'];
          $viewData['groupid'] = $_POST['sel_group'];
          if (isset($_POST['sel_year'])) {
             $viewData['year'] = $_POST['sel_year'];
          }
          $viewData['color']   = $_POST['sel_color'];
        }
      }
    }

    $viewData['absName']   = ($viewData['absid'] == 'all') ? $this->LANG['all'] : $this->A->getName($viewData['absid']);
    $viewData['groupName'] = ($viewData['groupid'] == "all") ? $this->LANG['all'] : $this->G->getNameById($viewData['groupid']);

    $labels = [];
    $data   = [];
    $total  = 0;

    // Filter absences
    $filteredAbsences = array_filter($viewData['absences'], function ($abs) {
        return !((bool) $abs['counts_as_present']);
    });

    // Get users depending on group filter
    $users = ($viewData['groupid'] == "all")
        ? $this->U->getAll('lastname', 'firstname', 'ASC', false, false)
        : $this->UG->getAllForGroup((string) $viewData['groupid']);

    for ($m = 1; $m <= 12; $m++) {
        $monthName = date("M", mktime(0, 0, 0, $m, 10)); // Short month name
        $labels[]  = '"' . $monthName . '"';

        $from = $viewData['year'] . '-' . sprintf('%02d', $m) . '-01';
        $to   = $viewData['year'] . '-' . sprintf('%02d', $m) . '-' . date('t', mktime(0, 0, 0, $m, 1, (int)$viewData['year']));
        
        $countFrom = str_replace('-', '', $from);
        $countTo   = str_replace('-', '', $to);

        $monthCount = 0;

        foreach ($users as $user) {
            if ($viewData['absid'] == 'all') {
                foreach ($filteredAbsences as $abs) {
                    $monthCount += $this->AbsenceService->countAbsence($user['username'], (string) $abs['id'], $countFrom, $countTo, false, false);
                }
            } else {
                $monthCount += $this->AbsenceService->countAbsence($user['username'], (string) $viewData['absid'], $countFrom, $countTo, false, false);
            }
        }
        $data[] = $monthCount;
        $total += $monthCount;
    }

    $viewData['labels'] = implode(',', $labels);
    $viewData['data']   = implode(',', $data);
    $viewData['total']  = $total;

    // Set controller for view logic in fragment
    $viewData['controller'] = 'statistics';

    // Temporary LANG keys if not exists (will be properly added in step 5)
    if (!isset($this->LANG['stats_trends_title'])) $this->LANG['stats_trends_title'] = "Absence Trends";
    if (!isset($this->LANG['stats_trends_desc'])) $this->LANG['stats_trends_desc'] = "Monthly absence trends for the selected year.";

    $this->render('fragments/stats_trends', $viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Get Day of Week Statistics fragment.
   *
   * @return void
   */
  private function getDayOfWeekStats(): void {

    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];

    $viewData['absences'] = $this->A->getAll();
    $viewData['groups']   = $this->G->getAll('DESC');

    $viewData['absid']   = 'all';
    $viewData['groupid'] = 'all';
    $viewData['year']    = date("Y");
    $viewData['color']   = 'purple';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
         $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
         return;
      }

      $inputError = false;
      if (isset($_POST['btn_apply'])) {
          // Add validations if needed
      }

      if (!$inputError) {
        if (isset($_POST['btn_apply'])) {
          $viewData['absid']   = $_POST['sel_absence'];
          $viewData['groupid'] = $_POST['sel_group'];
          if (isset($_POST['sel_year'])) {
             $viewData['year'] = $_POST['sel_year'];
          }
          $viewData['color']   = $_POST['sel_color'];
        }
      }
    }

    $viewData['absName']   = ($viewData['absid'] == 'all') ? $this->LANG['all'] : $this->A->getName($viewData['absid']);
    $viewData['groupName'] = ($viewData['groupid'] == "all") ? $this->LANG['all'] : $this->G->getNameById($viewData['groupid']);

    // Initialize counts: 1=Mon, ..., 7=Sun
    $counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0];

    // Determine target absences
    $targetAbsences = [];
    if ($viewData['absid'] == 'all') {
         foreach ($viewData['absences'] as $a) {
             if (!$a['counts_as_present']) $targetAbsences[] = $a['id'];
         }
    } else {
         $targetAbsences[] = $viewData['absid'];
    }

    // Get users depending on group filter
    $users = ($viewData['groupid'] == "all")
        ? $this->U->getAll('lastname', 'firstname', 'ASC', false, false)
        : $this->UG->getAllForGroup((string) $viewData['groupid']);

    foreach ($users as $user) {
        for ($m = 1; $m <= 12; $m++) {
            $month = sprintf("%02d", $m);
            if ($this->T->getTemplate($user['username'], (string)$viewData['year'], $month)) {
                $daysInMonth = date('t', strtotime($viewData['year'] . '-' . $month . '-01'));
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $prop = 'abs' . $d;
                    $absId = $this->T->$prop;
                    if ($absId > 0 && in_array($absId, $targetAbsences)) {
                        $date = $viewData['year'] . '-' . $month . '-' . sprintf("%02d", $d);
                        $dow = (int)date('N', strtotime($date));
                        if (isset($counts[$dow])) {
                            $counts[$dow]++;
                        }
                    }
                }
            }
        }
    }

    $labels = [
        '"' . ($this->LANG['monday'] ?? 'Monday') . '"',
        '"' . ($this->LANG['tuesday'] ?? 'Tuesday') . '"',
        '"' . ($this->LANG['wednesday'] ?? 'Wednesday') . '"',
        '"' . ($this->LANG['thursday'] ?? 'Thursday') . '"',
        '"' . ($this->LANG['friday'] ?? 'Friday') . '"',
        '"' . ($this->LANG['saturday'] ?? 'Saturday') . '"',
        '"' . ($this->LANG['sunday'] ?? 'Sunday') . '"',
    ];

    $viewData['labels'] = implode(',', $labels);
    $viewData['data']   = implode(',', array_values($counts));
    $viewData['total']  = array_sum($counts);

    // Set controller for view logic in fragment
    $viewData['controller'] = 'statistics';

    // Temporary LANG keys
    if (!isset($this->LANG['stats_dayofweek_title'])) $this->LANG['stats_dayofweek_title'] = "Day of Week Stats";
    if (!isset($this->LANG['stats_dayofweek_desc'])) $this->LANG['stats_dayofweek_desc'] = "Total absences by day of the week for the selected year.";

    $this->render('fragments/stats_dayofweek', $viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Get Duration Histogram Statistics fragment.
   *
   * @return void
   */
  private function getDurationHistogram(): void {

    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];

    $viewData['absences'] = $this->A->getAll();
    $viewData['groups']   = $this->G->getAll('DESC');

    $viewData['absid']   = 'all';
    $viewData['groupid'] = 'all';
    $viewData['year']    = date("Y");
    $viewData['color']   = 'orange';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
         $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
         return;
      }

      $inputError = false;
      if (isset($_POST['btn_apply'])) {
          // Add validations if needed
      }

      if (!$inputError) {
        if (isset($_POST['btn_apply'])) {
          $viewData['absid']   = $_POST['sel_absence'];
          $viewData['groupid'] = $_POST['sel_group'];
          if (isset($_POST['sel_year'])) {
             $viewData['year'] = $_POST['sel_year'];
          }
          $viewData['color']   = $_POST['sel_color'];
        }
      }
    }

    $viewData['absName']   = ($viewData['absid'] == 'all') ? $this->LANG['all'] : $this->A->getName($viewData['absid']);
    $viewData['groupName'] = ($viewData['groupid'] == "all") ? $this->LANG['all'] : $this->G->getNameById($viewData['groupid']);

    $buckets = [
        '1'    => 0,
        '2'    => 0,
        '3'    => 0,
        '4-5'  => 0,
        '6-10' => 0,
        '>10'  => 0,
    ];

    // Determine target absences
    $targetAbsences = [];
    if ($viewData['absid'] == 'all') {
         foreach ($viewData['absences'] as $a) {
             if (!$a['counts_as_present']) $targetAbsences[] = $a['id'];
         }
    } else {
         $targetAbsences[] = $viewData['absid'];
    }

    // Get users depending on group filter
    $users = ($viewData['groupid'] == "all")
        ? $this->U->getAll('lastname', 'firstname', 'ASC', false, false)
        : $this->UG->getAllForGroup((string) $viewData['groupid']);

    foreach ($users as $user) {
        $yearAbsences = []; // Flattened array of absence IDs for the whole year
        
        for ($m = 1; $m <= 12; $m++) {
            $month = sprintf("%02d", $m);
            $maxDays = date('t', strtotime($viewData['year'] . '-' . $month . '-01'));

            if ($this->T->getTemplate($user['username'], (string)$viewData['year'], $month)) {
                for ($d = 1; $d <= $maxDays; $d++) {
                    $prop = 'abs' . $d;
                    $yearAbsences[] = $this->T->$prop;
                }
            } else {
                for ($d = 1; $d <= $maxDays; $d++) {
                    $yearAbsences[] = 0;
                }
            }
        }

        // Analyze flattened array for contiguous blocks
        $currentRun = 0;
        foreach ($yearAbsences as $absId) {
            if ($absId > 0 && in_array($absId, $targetAbsences)) {
                $currentRun++;
            } else {
                if ($currentRun > 0) {
                    if ($currentRun == 1) $buckets['1']++;
                    elseif ($currentRun == 2) $buckets['2']++;
                    elseif ($currentRun == 3) $buckets['3']++;
                    elseif ($currentRun >= 4 && $currentRun <= 5) $buckets['4-5']++;
                    elseif ($currentRun >= 6 && $currentRun <= 10) $buckets['6-10']++;
                    else $buckets['>10']++;
                    $currentRun = 0;
                }
            }
        }
        // Check tail
        if ($currentRun > 0) {
            if ($currentRun == 1) $buckets['1']++;
            elseif ($currentRun == 2) $buckets['2']++;
            elseif ($currentRun == 3) $buckets['3']++;
            elseif ($currentRun >= 4 && $currentRun <= 5) $buckets['4-5']++;
            elseif ($currentRun >= 6 && $currentRun <= 10) $buckets['6-10']++;
            else $buckets['>10']++;
        }
    }

    $labels = [
        '"1 '     . ($this->LANG['day'] ?? 'Day') . '"',
        '"2 '     . ($this->LANG['days'] ?? 'Days') . '"',
        '"3 '     . ($this->LANG['days'] ?? 'Days') . '"',
        '"4-5 '   . ($this->LANG['days'] ?? 'Days') . '"',
        '"6-10 '  . ($this->LANG['days'] ?? 'Days') . '"',
        '"> 10 '  . ($this->LANG['days'] ?? 'Days') . '"',
    ];

    $viewData['labels'] = implode(',', $labels);
    $viewData['data']   = implode(',', array_values($buckets));
    $viewData['total']  = array_sum($buckets);

    // Set controller for view logic in fragment
    $viewData['controller'] = 'statistics';

    // Temporary LANG keys
    if (!isset($this->LANG['stats_duration_title'])) $this->LANG['stats_duration_title'] = "Absence Duration Histogram";
    if (!isset($this->LANG['stats_duration_desc'])) $this->LANG['stats_duration_desc'] = "Frequency of absence durations (contiguous days) for the selected year.";

    $this->render('fragments/stats_duration', $viewData);
  }
}
