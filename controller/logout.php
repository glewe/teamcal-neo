<?php
/**
 * logout.php
 * 
 * Logout page controller
 *
 * @category TeamCal Neo 
 * @version 0.9.007
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

//=============================================================================
//
// VARIABLE DEFAULTS
//

//=============================================================================
//
// PROCESS FORM
//
$L->logout();
$LOG->log("logLogin", $L->checkLogin(), "log_logout");

//=============================================================================
//
// PREPARE VIEW
//
$viewData['cookie_name'] = $CONF['cookie_name'];

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
