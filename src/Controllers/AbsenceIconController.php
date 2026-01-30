<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\AbsenceModel;

/**
 * Absence Icon Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class AbsenceIconController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    resetTabindex();
    if (!isAllowed($this->CONF['controllers']['absenceicon']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $AA          = new AbsenceModel($this->DB->db, $this->CONF);
    $missingData = false;

    if (isset($_GET['id'])) {
      $id = sanitize($_GET['id']);
      if (!$AA->get($id)) {
        $missingData = true;
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

    $currentFaIcons         = $this->faIcons;
    $viewData['csrf_token'] = $_SESSION['csrf_token'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_save'])) {
        $AA->id   = (int) ($_POST['hidden_id'] ?? 0);
        $AA->icon = $_POST['opt_absIcon'] ?? 'times';
        $AA->update((string) $AA->id);

        if (session_status() === PHP_SESSION_ACTIVE) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        header("Location: index.php?action=absenceedit&id=" . $AA->id);
        die();
      }
      elseif (isset($_POST['btn_fa_filter'])) {
        $filterString = $_POST['fa_search'] ?? '';
        if (!empty($filterString)) {
          $currentFaIcons = array_filter($this->faIcons, function ($element) use ($filterString) {
            return strpos($element, $filterString) !== false;
          });
        }
      }
    }

    $viewData['id']   = $AA->id;
    $viewData['name'] = $AA->name;
    $viewData['icon'] = $AA->icon;

    require_once WEBSITE_ROOT . '/src/Helpers/view.helper.php';
    $iconSets = splitFaIcons($AA->icon, $currentFaIcons);
    $viewData = array_merge($viewData, $iconSets);

    $this->render('absenceicon', $viewData);
  }
}
