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
    $LIC              = new LicenseModel($this->DB->db, $this->CONF);
    $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);

    $PTN = new PatternModel($this->DB->db, $this->CONF);

    $viewData                    = [];
    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];
    $viewData['txt_name']        = '';
    $viewData['txt_description'] = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
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
          $PTN->name        = $viewData['txt_name'];
          $PTN->description = $viewData['txt_description'];
          $PTN->abs1        = $_POST['sel_abs1'];
          $PTN->abs2        = $_POST['sel_abs2'];
          $PTN->abs3        = $_POST['sel_abs3'];
          $PTN->abs4        = $_POST['sel_abs4'];
          $PTN->abs5        = $_POST['sel_abs5'];
          $PTN->abs6        = $_POST['sel_abs6'];
          $PTN->abs7        = $_POST['sel_abs7'];
          $PTN->create();
          $this->LOG->logEvent("logPattern", $this->UL->username, "log_pattern_created", $PTN->name . " " . $PTN->description);

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
        $PTN->delete($_POST['hidden_id']);
        $this->LOG->logEvent("logRole", $this->UL->username, "log_pattern_deleted", $_POST['hidden_name']);

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
    $absences = $this->A->getAll();
    $absData  = [];
    foreach ($absences as $abs) {
      $absId   = (string) $abs['id'];
      $bgStyle = '';
      if (!$this->A->getBgTrans($absId)) {
        $bgColor = $this->A->getBgColor($absId);
        $bgStyle = $bgColor ? $bgColor : 'ffffff';
      }

      $symbol = '';
      if ($this->allConfig['symbolAsIcon']) {
        $symbol = $this->A->getSymbol($absId);
      }
      else {
        $symbol = '<span class="' . $this->A->getIcon($absId) . '"></span>';
      }

      $absData[$absId] = [
        'name'         => $this->A->getName($absId),
        'color'        => $this->A->getColor($absId),
        'bgColor'      => $bgStyle,
        'bgTrans'      => $this->A->getBgTrans($absId),
        'icon'         => $this->A->getIcon($absId),
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

    $viewData['patterns']      = $PTN->getAll();
    $viewData['absences']      = $absData;
    $viewData['weekdayShort']  = $this->LANG['weekdayShort'];
    $viewData['searchPattern'] = '';

    $this->render('patterns', $viewData);
  }
}
