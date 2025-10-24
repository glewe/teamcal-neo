<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Data Privacy Controller
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
global $language;
global $LANG;

//-----------------------------------------------------------------------------
// CHECK ENABLED
//
if (!$C->read('gdprPolicyPage')) {
  header("Location: index.php?action=home");
  die();
}

//
// Load policy
//
require_once "languages/" . $language . ".gdpr.php";

$viewData['gdpr_text'] = str_replace("%ENTITY%", $C->read('gdprOrganization'), $LANG['gdpr_start']);
$viewData['gdpr_text'] = str_replace("%CONTROLLER%", nl2br($C->read('gdprController')), $viewData['gdpr_text']);
$viewData['gdpr_text'] = str_replace("%DATAPROTECTIONOFFICER%", nl2br($C->read('gdprOfficer')), $viewData['gdpr_text']);

$sectionNbr = 11;
if ($C->read('gdprFacebook')) {
  $viewData['gdpr_text'] .= sprintf($LANG['gdpr_facebook'], $sectionNbr++);
}
if ($C->read('gdprGoogleAnalytics')) {
  $viewData['gdpr_text'] .= sprintf($LANG['gdpr_google_analytics'], $sectionNbr++);
}
if ($C->read('gdprInstagram')) {
  $viewData['gdpr_text'] .= sprintf($LANG['gdpr_instagram'], $sectionNbr++);
}
if ($C->read('gdprLinkedin')) {
  $viewData['gdpr_text'] .= sprintf($LANG['gdpr_linkedin'], $sectionNbr++);
}
if ($C->read('gdprPaypal')) {
  $viewData['gdpr_text'] .= sprintf($LANG['gdpr_paypal'], $sectionNbr++);
}
if ($C->read('gdprPinterest')) {
  $viewData['gdpr_text'] .= sprintf($LANG['gdpr_pinterest'], $sectionNbr++);
}
if ($C->read('gdprSlideshare')) {
  $viewData['gdpr_text'] .= sprintf($LANG['gdpr_slideshare'], $sectionNbr++);
}
if ($C->read('gdprTumblr')) {
  $viewData['gdpr_text'] .= sprintf($LANG['gdpr_tumblr'], $sectionNbr++);
}
if ($C->read('gdprTwitter')) {
  $viewData['gdpr_text'] .= sprintf($LANG['gdpr_twitter'], $sectionNbr++);
}
if ($C->read('gdprXing')) {
  $viewData['gdpr_text'] .= sprintf($LANG['gdpr_xing'], $sectionNbr++);
}
if ($C->read('gdprYoutube')) {
  $viewData['gdpr_text'] .= sprintf($LANG['gdpr_youtube'], $sectionNbr++);
}

$viewData['gdpr_text'] .= sprintf($LANG['gdpr_end'], $sectionNbr++, $sectionNbr++, $sectionNbr++, $sectionNbr++, $sectionNbr++);

//-----------------------------------------------------------------------------
//
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
