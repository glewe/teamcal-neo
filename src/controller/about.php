<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * About Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

$allConfig = $C->readAll();
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];
$viewData['versionCompare'] = $allConfig['versionCompare'];

//=============================================================================
//
// CHECK LICENSE
//
$alertData = array();
$showAlert = false;
$licExpiryWarning = $allConfig['licExpiryWarning'];
$LIC = new License();
$LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);

//=============================================================================
//
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
