<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Logout Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2022 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Controllers
 * @since 3.0.0
 */

// echo '<script type="text/javascript">alert("Debug: ");</script>';

//=============================================================================
//
// VARIABLE DEFAULTS
//

//=============================================================================
//
// PROCESS FORM
//
$L->logout();
$LOG->log("logLogin", L_USER, "log_logout");

//=============================================================================
//
// PREPARE VIEW
//
$viewData['cookie_name'] = COOKIE_NAME;

//=============================================================================
//
// SHOW VIEW
//
require(WEBSITE_ROOT . '/views/header.php');
require(WEBSITE_ROOT . '/views/menu.php');
include(WEBSITE_ROOT . '/views/' . $controller . '.php');
require(WEBSITE_ROOT . '/views/footer.php');
