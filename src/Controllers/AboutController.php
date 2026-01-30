<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;

/**
 * About Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class AboutController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    $viewData                   = [];
    $viewData['pageHelp']       = $this->allConfig['pageHelp'];
    $viewData['showAlerts']     = $this->allConfig['showAlerts'];
    $viewData['versionCompare'] = $this->allConfig['versionCompare'];

    $alertData        = [];
    $showAlert        = false;
    $licExpiryWarning = $this->allConfig['licExpiryWarning'];

    $LIC = new LicenseModel($this->DB->db, $this->CONF);
    $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);

    // Prepare Alert
    $alertHtml = '';
    if (
      ($showAlert && $viewData['showAlerts'] != "none") &&
      ($viewData['showAlerts'] == "all" || $viewData['showAlerts'] == "warnings" && ($alertData['type'] == "warning" || $alertData['type'] == "danger"))
    ) {
      $alertHtml = createAlertBox($alertData);
    }

    // Get Release Info
    $releases = [];
    ob_start();
    require WEBSITE_ROOT . '/doc/releaseinfo.php';
    ob_end_clean();
    // $releases is now available from releaseinfo.php

    // Show View
    $this->render('about', [
      'viewData'   => $viewData,
      'alertData'  => $alertData,
      'showAlert'  => $showAlert,
      'alertHtml'  => $alertHtml,
      'releases'   => $releases,
      'controller' => 'about',
      'CONF'       => $this->CONF,
      'LANG'       => $this->LANG
    ]);
  }
}
