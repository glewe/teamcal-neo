<?php
/**
 * logout.php
 * 
 * Logout page controller
 *
 * @category TeamCal Neo 
 * @version 1.9.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

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
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
