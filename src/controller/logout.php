<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Logout Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
global $controller;
global $L;
global $LOG;

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//

//-----------------------------------------------------------------------------
// PROCESS FORM
//
$L->logout();
$LOG->logEvent("logLogin", L_USER, "log_logout");

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$viewData['cookie_name'] = COOKIE_NAME;

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
