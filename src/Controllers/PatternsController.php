<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;
use App\Models\PatternModel;

/**
 * Patterns Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class PatternsController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['patterns']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    // Check License
    $alertData        = [];
    $showAlert        = false;
    $licExpiryWarning = (int) $this->allConfig['licExpiryWarning'];
    $LIC              = new LicenseModel($this->dbModel->db, $this->CONF);
    $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);

    $patternModel = new PatternModel($this->dbModel->db, $this->CONF);

    $viewData                    = [];
    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];
    $viewData['txt_name']        = '';
    $viewData['txt_description'] = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;
      if (isset($_POST['btn_roleCreate']) && $_POST['btn_roleCreate'] === '1') {
        if (!formInputValid('txt_name', 'required|alpha_numeric_dash') || !formInputValid('txt_description', 'alpha_numeric_dash_blank')) {
          $inputError        = true;
          $alertData['text'] = $this->LANG['roles_alert_created_fail_input'];
        }
        $viewData['txt_name'] = htmlspecialchars($_POST['txt_name'] ?? '', ENT_QUOTES, 'UTF-8');
        if (isset($_POST['txt_description'])) {
          $viewData['txt_description'] = htmlspecialchars($_POST['txt_description'], ENT_QUOTES, 'UTF-8');
        }

        if (!$inputError) {
          $patternModel->name        = $viewData['txt_name'];
          $patternModel->description = $viewData['txt_description'];
          $patternModel->create();
          $weekdayMap = [];
          for ($i = 1; $i <= 7; $i++) {
            $weekdayMap[$i] = (int) ($_POST['sel_abs' . $i] ?? 0);
          }
          $patternModel->setWeekdayMap((string) $patternModel->id, $weekdayMap);
          $this->logModel->logEvent("logPattern", $this->userLoggedIn->username, "log_pattern_created", $patternModel->name . " " . $patternModel->description);

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['btn_create_pattern'];
          $alertData['text']    = $this->LANG['roles_alert_created'];
          $alertData['help']    = '';
        }
        else {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['btn_create_pattern'];
          $alertData['help']    = '';
        }
      }
      elseif (isset($_POST['btn_patternDelete'])) {
        $patternModel->delete($_POST['hidden_id']);
        $this->logModel->logEvent("logRole", $this->userLoggedIn->username, "log_pattern_deleted", $_POST['hidden_name']);

        $showAlert            = true;
        $alertData['type']    = 'success';
        $alertData['title']   = $this->LANG['alert_success_title'];
        $alertData['subject'] = $this->LANG['btn_delete_pattern'];
        $alertData['text']    = $this->LANG['ptn_alert_deleted'];
        $alertData['help']    = '';
      }
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    // Prepare absence data for pattern macros
    $absences = $this->absenceModel->getAll();
    $absData  = [];
    foreach ($absences as $abs) {
      $absId   = (string) $abs['id'];
      $bgStyle = '';
      if (!$this->absenceModel->getBgTrans($absId)) {
        $bgColor = $this->absenceModel->getBgColor($absId);
        $bgStyle = $bgColor ? $bgColor : 'ffffff';
      }

      $symbol = '';
      if ($this->allConfig['symbolAsIcon']) {
        $symbol = $this->absenceModel->getSymbol($absId);
      }
      else {
        $symbol = '<span class="' . $this->absenceModel->getIcon($absId) . '"></span>';
      }

      $absData[$absId] = [
        'name'         => $this->absenceModel->getName($absId),
        'color'        => $this->absenceModel->getColor($absId),
        'bgColor'      => $bgStyle,
        'bgTrans'      => $this->absenceModel->getBgTrans($absId),
        'icon'         => $this->absenceModel->getIcon($absId),
        'symbol'       => $symbol,
        'symbolAsIcon' => $this->allConfig['symbolAsIcon']
      ];
    }
    // Add "None" absence (0)
    $absData[0] = [
      'name'         => '',
      'color'        => '000000',
      'bgColor'      => 'ffffff',
      'bgTrans'      => false,
      'icon'         => '',
      'symbol'       => '',
      'symbolAsIcon' => false
    ];

    $viewData['patterns']      = $patternModel->getAll();
    $viewData['absences']      = $absData;
    $viewData['weekdayShort']  = $this->LANG['weekdayShort'];
    $viewData['searchPattern'] = '';

    $this->render('patterns', $viewData);
  }
}
