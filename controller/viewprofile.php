<?php
/**
 * viewprofile.php
 * 
 * View profile page controller
 *
 * @category TeamCal Neo 
 * @version 0.3.003
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

/**
 * ========================================================================
 * Check URL params
 */
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
   /**
    * URL param fail
    */
   $alertData['type'] = 'danger';
   $alertData['title'] = $LANG['alert_danger_title'];
   $alertData['subject'] = $LANG['alert_no_data_subject'];
   $alertData['text'] = $LANG['alert_no_data_text'];
   $alertData['help'] = $LANG['alert_no_data_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
   die();
}

/**
 * ========================================================================
 * Check if allowed
 */
if (!isAllowed($controller))
{
   $alertData['type'] = 'warning';
   $alertData['title'] = $LANG['alert_alert_title'];
   $alertData['subject'] = $LANG['alert_not_allowed_subject'];
   $alertData['text'] = $LANG['alert_not_allowed_text'];
   $alertData['help'] = $LANG['alert_not_allowed_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
   die();
}

/**
 * ========================================================================
 * Load controller stuff
 */

/**
 * ========================================================================
 * Initialize variables
 */

/**
 * ========================================================================
 * Process form
 */

/**
 * ========================================================================
 * Prepare data for the view
 */
$U->findByName($profile);
$profileData['username'] = $profile;
$profileData['fullname'] = $U->getFullname($U->username);
$profileData['avatar'] = ($UO->read($U->username, 'avatar')) ? $UO->read($U->username, 'avatar') : 'noavatar_' . $UO->read($U->username, 'gender') . '.png';
$profileData['role'] = $RO->getNameById($U->role);
$profileData['title'] = $UO->read($U->username, 'title');
$profileData['position'] = $UO->read($U->username, 'position');
$profileData['email'] = $U->email;
$profileData['phone'] = $UO->read($U->username, 'phone');
$profileData['mobile'] = $UO->read($U->username, 'mobile');
$profileData['facebook'] = $UO->read($U->username, 'facebook');
$profileData['google'] = $UO->read($U->username, 'google');
$profileData['linkedin'] = $UO->read($U->username, 'linkedin');
$profileData['skype'] = $UO->read($U->username, 'skype');
$profileData['twitter'] = $UO->read($U->username, 'twitter');


/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
