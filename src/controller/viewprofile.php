<?php
/**
 * View Profile Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
global $C;
global $CONF;
global $controller;
global $LANG;
global $LOG;
global $U;
global $UO;
global $RO;

//-----------------------------------------------------------------------------
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission)) {
  $alertData['type'] = 'warning';
  $alertData['title'] = $LANG['alert_alert_title'];
  $alertData['subject'] = $LANG['alert_not_allowed_subject'];
  $alertData['text'] = $LANG['alert_not_allowed_text'];
  $alertData['help'] = $LANG['alert_not_allowed_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//-----------------------------------------------------------------------------
// CHECK URL PARAMETER
//
if (isset($_GET['profile'])) {
  $missingData = false;
  $profile = sanitize($_GET['profile']);
  if (!$U->findByName($profile)) {
    $missingData = true;
  }
} else {
  $missingData = true;
}

if ($missingData) {
  //
  // URL param fail
  //
  $alertData['type'] = 'danger';
  $alertData['title'] = $LANG['alert_danger_title'];
  $alertData['subject'] = $LANG['alert_no_data_subject'];
  $alertData['text'] = $LANG['alert_no_data_text'];
  $alertData['help'] = $LANG['alert_no_data_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//

//-----------------------------------------------------------------------------
// PROCESS FORM
//

//-----------------------------------------------------------------------------
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
if (($userData['isLoggedIn'] && $userData['username'] == $viewData['username']) || isAllowed($CONF['controllers']['useredit']->permission)) {
  $viewData['allowEdit'] = true;
}

$viewData['allowAbsum'] = false;
if (isAllowed($CONF['controllers']['absum']->permission)) {
  $viewData['allowAbsum'] = true;
}

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
