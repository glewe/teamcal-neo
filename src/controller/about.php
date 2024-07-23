<?php
if (!defined('VALID_ROOT')) { exit(''); }
/**
 * About Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Controllers
 * @since 3.0.0
 */

//=============================================================================
//
// CHECK LICENSE
//
$alertData = array();
$showAlert = false;
$licExpiryWarning = $C->read('licExpiryWarning');
$LIC  = new License();
$LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);

//=============================================================================
//
// SHOW VIEW
//
require WEBSITE_ROOT . '/views/header.php';
require WEBSITE_ROOT . '/views/menu.php';
include WEBSITE_ROOT . '/views/' . $controller . '.php';
require WEBSITE_ROOT . '/views/footer.php';
