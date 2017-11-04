<?php
/**
 * viewprofile.php
 * 
 * View profile page controller
 *
 * @category TeamCal Neo 
 * @version 1.9.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo '<script type="text/javascript">alert("Debug: ");</script>';

//=============================================================================
//
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission))
{
   $alertData['type'] = 'warning';
   $alertData['title'] = $LANG['alert_alert_title'];
   $alertData['subject'] = $LANG['alert_not_allowed_subject'];
   $alertData['text'] = $LANG['alert_not_allowed_text'];
   $alertData['help'] = $LANG['alert_not_allowed_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
   die();
}

//=============================================================================
//
// CHECK URL PARAMETER
//
if (isset($_GET['profile']))
{
   $missingData = FALSE;
   $profile = sanitize($_GET['profile']);
   if (!$U->findByName($profile)) $missingData = TRUE;
}
else
{
   $missingData = TRUE;
}

if ($missingData)
{
   //
   // URL param fail
   //
   $alertData['type'] = 'danger';
   $alertData['title'] = $LANG['alert_danger_title'];
   $alertData['subject'] = $LANG['alert_no_data_subject'];
   $alertData['text'] = $LANG['alert_no_data_text'];
   $alertData['help'] = $LANG['alert_no_data_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
   die();
}

//=============================================================================
//
// LOAD CONTROLLER RESOURCES
//

//=============================================================================
//
// VARIABLE DEFAULTS
//

//=============================================================================
//
// PROCESS FORM
//

//=============================================================================
//
// PREPARE VIEW
//
$U->findByName($profile);
$viewData['username'] = $profile;
$viewData['fullname'] = $U->getFullname($U->username);
$viewData['avatar'] = ($UO->read($U->username, 'avatar')) ? $UO->read($U->username, 'avatar') : 'default_' . $UO->read($U->username, 'gender') . '.png';
$viewData['role'] = $RO->getNameById($U->role);
$viewData['title'] = $UO->read($U->username, 'title');
$viewData['position'] = $UO->read($U->username, 'position');
$viewData['email'] = $U->email;
$viewData['phone'] = $UO->read($U->username, 'phone');
$viewData['mobile'] = $UO->read($U->username, 'mobile');
$viewData['facebook'] = $UO->read($U->username, 'facebook');
$viewData['google'] = $UO->read($U->username, 'google');
$viewData['linkedin'] = $UO->read($U->username, 'linkedin');
$viewData['skype'] = $UO->read($U->username, 'skype');
$viewData['twitter'] = $UO->read($U->username, 'twitter');

$viewData['allowEdit'] = false;
if ( ($userData['isLoggedIn'] AND $userData['username'] == $viewData['username']) OR isAllowed($CONF['controllers']['useredit']->permission))
{
   $viewData['allowEdit'] = true;
}

$viewData['allowAbsum'] = false;
if (isAllowed($CONF['controllers']['absum']->permission))
{
   $viewData['allowAbsum'] = true;
}

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
