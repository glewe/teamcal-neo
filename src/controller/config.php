<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Config Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
global $allConfig;
global $appJqueryUIThemes;
global $appLanguages;
global $C;
global $CONF;
global $controller;
global $LANG;
global $LOG;
global $logLanguages;
global $P;
global $timezones;
global $UL;
global $UO;

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
// CHECK LICENSE
//
$alertData = array();
$showAlert = false;
$licExpiryWarning = $allConfig['licExpiryWarning'];
$LIC = new License();

// Only check license if not already checked in this session to avoid redundant API calls
if (!isset($_SESSION['license_checked']) || (time() - $_SESSION['license_checked']) > 300) { // 5 minute cache
  $LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);
  $_SESSION['license_checked'] = time();
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

  //
  // Sanitize input
  //
  foreach ($_POST as $key => $value) {
    $_POST[$key] = trim($value);
    $sanitizeLater = ['txt_welcomeText', 'txt_gdprController', 'txt_gdprOfficer'];
    if (str_starts_with($key, 'txt_') && !in_array($key, $sanitizeLater)) {
      $_POST[$key] = sanitize($value);
    }
  }

  //
  // CSRF token check
  //
  if (!isset($_POST['csrf_token']) || (isset($_POST['csrf_token']) && $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
    $alertData['type'] = 'warning';
    $alertData['title'] = $LANG['alert_alert_title'];
    $alertData['subject'] = $LANG['alert_csrf_invalid_subject'];
    $alertData['text'] = $LANG['alert_csrf_invalid_text'];
    $alertData['help'] = $LANG['alert_csrf_invalid_help'];
    require_once WEBSITE_ROOT . '/controller/alert.php';
    die();
  }

  //
  // Form validation
  //
  $inputError = false;

  //
  // Validate input data. If something is wrong or missing, set $inputError = true
  //

  if (!$inputError) {

    // ,-------,
    // | Apply |
    // '-------'
    if (isset($_POST['btn_confApply'])) {
      $newConfig = [];

      //
      // General
      //
      if (filter_var($_POST['txt_appURL'], FILTER_VALIDATE_URL)) {
        $newConfig["appURL"] = $_POST['txt_appURL'];
      } else {
        $newConfig["appURL"] = "#";
      }
      $newConfig["appTitle"] = sanitize($_POST['txt_appTitle']);
      $newConfig["appDescription"] = sanitize($_POST['txt_appDescription']);
      $newConfig["appKeywords"] = sanitize($_POST['txt_appKeywords']);
      if ($_POST['sel_defaultLanguage']) {
        $newConfig["defaultLanguage"] = $_POST['sel_defaultLanguage'];
      } else {
        $newConfig["defaultLanguage"] = "english";
      }
      if ($_POST['sel_logLanguage']) {
        $newConfig["logLanguage"] = $_POST['sel_logLanguage'];
      } else {
        $newConfig["logLanguage"] = "english";
      }
      if ($_POST['sel_permissionScheme']) {
        $newConfig["permissionScheme"] = $_POST['sel_permissionScheme'];
      } else {
        $newConfig["permissionScheme"] = "Default";
      }
      if ($_POST['opt_showAlerts']) {
        $newConfig["showAlerts"] = $_POST['opt_showAlerts'];
      }

      $checkboxes = [
        'chk_alertAutocloseDanger' => 'alertAutocloseDanger',
        'chk_alertAutocloseSuccess' => 'alertAutocloseSuccess',
        'chk_alertAutocloseWarning' => 'alertAutocloseWarning',
      ];
      foreach ($checkboxes as $postKey => $settingKey) {
        $newConfig[$settingKey] = (isset($_POST[$postKey]) && $_POST[$postKey]) ? "1" : "0";
      }

      $newConfig["alertAutocloseDelay"] = sanitize($_POST['txt_alertAutocloseDelay']);
      if (isset($_POST['chk_activateMessages']) && $_POST['chk_activateMessages']) {
        $newConfig["activateMessages"] = "1";
      } else {
        $newConfig["activateMessages"] = "0";
      }
      if (isset($_POST['chk_pageHelp']) && $_POST['chk_pageHelp']) {
        $newConfig["pageHelp"] = "1";
      } else {
        $newConfig["pageHelp"] = "0";
      }
      if (strlen($_POST['txt_userManual'])) {
        $myUrl = rtrim($_POST['txt_userManual'], '/') . '/'; // Ensure trailing slash
        $newConfig["userManual"] = urlencode($myUrl);
      } else {
        $newConfig["userManual"] = '';
      }

      //
      // Email
      //
      if (isset($_POST['chk_emailNotifications']) && $_POST['chk_emailNotifications']) {
        $newConfig["emailNotifications"] = "1";
      } else {
        $newConfig["emailNotifications"] = "0";
      }
      if (isset($_POST['chk_emailNoPastNotifications']) && $_POST['chk_emailNoPastNotifications']) {
        $newConfig["emailNoPastNotifications"] = "1";
      } else {
        $newConfig["emailNoPastNotifications"] = "0";
      }
      $newConfig["mailFrom"] = sanitize($_POST['txt_mailFrom']);
      if (validEmail($_POST['txt_mailReply'])) {
        $newConfig["mailReply"] = sanitize($_POST['txt_mailReply']);
      } else {
        $newConfig["mailReply"] = "noreply@teamcalneo.com";
      }
      if (isset($_POST['chk_mailSMTP']) && $_POST['chk_mailSMTP']) {
        $newConfig["mailSMTP"] = "1";
      } else {
        $newConfig["mailSMTP"] = "0";
      }
      $newConfig["mailSMTPhost"] = sanitize($_POST['txt_mailSMTPhost']);
      $newConfig["mailSMTPport"] = intval($_POST['txt_mailSMTPport']);
      $newConfig["mailSMTPusername"] = sanitize($_POST['txt_mailSMTPusername']);
      $newConfig["mailSMTPpassword"] = sanitize($_POST['txt_mailSMTPpassword']);
      if (isset($_POST['chk_mailSMTPSSL']) && $_POST['chk_mailSMTPSSL']) {
        $newConfig["mailSMTPSSL"] = "1";
      } else {
        $newConfig["mailSMTPSSL"] = "0";
      }
      if (isset($_POST['chk_mailSMTPAnonymous']) && $_POST['chk_mailSMTPAnonymous']) {
        $newConfig["mailSMTPAnonymous"] = "1";
      } else {
        $newConfig["mailSMTPAnonymous"] = "0";
      }

      //
      // Footer
      //
      $newConfig["footerCopyright"] = sanitize($_POST['txt_footerCopyright']);
      if (strlen($_POST['txt_footerCopyrightUrl']) && filter_var($_POST['txt_footerCopyrightUrl'], FILTER_VALIDATE_URL)) {
        $newConfig["footerCopyrightUrl"] = sanitize($_POST['txt_footerCopyrightUrl']);
      } else {
        $newConfig["footerCopyrightUrl"] = "";
      }
      if (isset($_POST['chk_footerViewport']) && $_POST['chk_footerViewport']) {
        $newConfig["footerViewport"] = "1";
      } else {
        $newConfig["footerViewport"] = "0";
      }
      $newConfig["footerSocialLinks"] = sanitize($_POST['txt_footerSocialLinks']);

      //
      // Homepage
      //
      if ($_POST['opt_homepage']) {
        $newConfig["homepage"] = $_POST['opt_homepage'];
      }
      if ($_POST['opt_defaultHomepage']) {
        $newConfig["defaultHomepage"] = $_POST['opt_defaultHomepage'];
      }
      $newConfig["welcomeText"] = sanitizeWithAllowedTags($_POST['txt_welcomeText']);

      //
      // License
      //
      $LIC->saveKey(trim(sanitize($_POST['txt_licKey'])));
      if (strlen($_POST['txt_licExpiryWarning'])) {
        $newConfig["licExpiryWarning"] = intval(sanitize($_POST['txt_licExpiryWarning']));
      } else {
        $newConfig["licExpiryWarning"] = 0;
      }

      //
      // Login
      //
      if ($_POST['opt_pwdStrength']) {
        $newConfig["pwdStrength"] = $_POST['opt_pwdStrength'];
      }
      $newConfig["badLogins"] = intval(sanitize($_POST['txt_badLogins']));
      $newConfig["gracePeriod"] = intval(sanitize($_POST['txt_gracePeriod']));
      $newConfig["cookieLifetime"] = intval(sanitize($_POST['txt_cookieLifetime']));
      if (isset($_POST['swi_disableTfa']) && $_POST['swi_disableTfa']) {
        $newConfig["disableTfa"] = "1";
        $newConfig["forceTfa"] = "0"; // With disabled 2FA, forceTfa does not make sense
        $UO->deleteOption("secret"); // Delete 2FA secrets from the user options
      } else {
        $newConfig["disableTfa"] = "0";
        if (isset($_POST['swi_forceTfa']) && $_POST['swi_forceTfa']) {
          $newConfig["forceTfa"] = "1";
        } else {
          $newConfig["forceTfa"] = "0";
        }
      }

      //
      // Registration
      //
      if (isset($_POST['chk_allowRegistration']) && $_POST['chk_allowRegistration']) {
        $newConfig["allowRegistration"] = "1";
      } else {
        $newConfig["allowRegistration"] = "0";
      }
      if (isset($_POST['chk_emailConfirmation']) && $_POST['chk_emailConfirmation']) {
        $newConfig["emailConfirmation"] = "1";
      } else {
        $newConfig["emailConfirmation"] = "0";
      }
      if (isset($_POST['chk_adminApproval']) && $_POST['chk_adminApproval']) {
        $newConfig["adminApproval"] = "1";
      } else {
        $newConfig["adminApproval"] = "0";
      }

      //
      // System
      //
      if (isset($_POST['chk_cookieConsent']) && $_POST['chk_cookieConsent']) {
        $newConfig["cookieConsent"] = "1";
      } else {
        $newConfig["cookieConsent"] = "0";
      }
      if (isset($_POST['chk_cookieConsentCDN']) && $_POST['chk_cookieConsentCDN']) {
        $newConfig["cookieConsentCDN"] = "1";
      } else {
        $newConfig["cookieConsentCDN"] = "0";
      }
      if (isset($_POST['chk_faCDN']) && $_POST['chk_faCDN']) {
        $newConfig["faCDN"] = "1";
      } else {
        $newConfig["faCDN"] = "0";
      }
      if (isset($_POST['chk_jQueryCDN']) && $_POST['chk_jQueryCDN']) {
        $newConfig["jQueryCDN"] = "1";
      } else {
        $newConfig["jQueryCDN"] = "0";
      }
      if ($_POST['sel_timeZone']) {
        $newConfig["timeZone"] = $_POST['sel_timeZone'];
      } else {
        $newConfig["timeZone"] = "UTC";
      }
      if (isset($_POST['chk_googleAnalytics']) && $_POST['chk_googleAnalytics']) {
        if (preg_match('/^G-[A-Z0-9]{10,20}$/', $_POST['txt_googleAnalyticsID'])) {
          $newConfig["googleAnalytics"] = "1";
          $newConfig["googleAnalyticsID"] = $_POST['txt_googleAnalyticsID'];
        } else {
          $newConfig["googleAnalytics"] = "0";
          $newConfig["googleAnalyticsID"] = $_POST['txt_googleAnalyticsID'];
        }
      } else {
        $newConfig["googleAnalytics"] = "0";
        $newConfig["googleAnalyticsID"] = "";
      }
      if (isset($_POST['chk_matomoAnalytics']) && $_POST['chk_matomoAnalytics']) {
        $newConfig["matomoAnalytics"] = "1";
        $newConfig["matomoUrl"] = $_POST['txt_matomoUrl'];
        $newConfig["matomoSiteId"] = $_POST['txt_matomoSiteId'];
      } else {
        $newConfig["matomoAnalytics"] = "0";
        $newConfig["matomoUrl"] = $_POST['txt_matomoUrl'];
        $newConfig["matomoSiteId"] = $_POST['txt_matomoSiteId'];
      }
      if (isset($_POST['chk_noIndex']) && $_POST['chk_noIndex']) {
        $newConfig["noIndex"] = "1";
      } else {
        $newConfig["noIndex"] = "0";
      }
      if (isset($_POST['chk_noCaching']) && $_POST['chk_noCaching']) {
        $newConfig["noCaching"] = "1";
      } else {
        $newConfig["noCaching"] = "0";
      }
      if (isset($_POST['chk_versionCompare']) && $_POST['chk_versionCompare']) {
        $newConfig["versionCompare"] = "1";
      } else {
        $newConfig["versionCompare"] = "0";
      }
      if (isset($_POST['chk_underMaintenance']) && $_POST['chk_underMaintenance']) {
        $newConfig["underMaintenance"] = "1";
      } else {
        $newConfig["underMaintenance"] = "0";
      }

      //
      // Theme
      //
      if (isset($_POST['opt_defaultMenu'])) {
        $newConfig["defaultMenu"] = $_POST['opt_defaultMenu'];
      } else {
        $newConfig["defaultMenu"] = 'navbar';
      }
      if ($_POST['sel_jqtheme']) {
        $newConfig["jqtheme"] = $_POST['sel_jqtheme'];
      } else {
        $newConfig["jqtheme"] = "smoothness";
      }
      if ($_POST['sel_font']) {
        $newConfig["font"] = $_POST['sel_font'];
      } else {
        $newConfig["font"] = "default";
      }

      //
      // User
      //
      $newConfig["userCustom1"] = sanitize($_POST['txt_userCustom1']);
      $newConfig["userCustom2"] = sanitize($_POST['txt_userCustom2']);
      $newConfig["userCustom3"] = sanitize($_POST['txt_userCustom3']);
      $newConfig["userCustom4"] = sanitize($_POST['txt_userCustom4']);
      $newConfig["userCustom5"] = sanitize($_POST['txt_userCustom5']);

      //
      // GDPR
      //
      if (isset($_POST['chk_gdprPolicyPage']) && $_POST['chk_gdprPolicyPage']) {
        $newConfig["gdprPolicyPage"] = "1";
      } else {
        $newConfig["gdprPolicyPage"] = "0";
      }
      $newConfig["gdprOrganization"] = sanitize($_POST['txt_gdprOrganization']);
      $newConfig["gdprController"] = sanitizeWithAllowedTags($_POST['txt_gdprController']);
      $newConfig["gdprOfficer"] = sanitizeWithAllowedTags($_POST['txt_gdprOfficer']);
      if (isset($_POST['chk_gdprFacebook']) && $_POST['chk_gdprFacebook']) {
        $newConfig["gdprFacebook"] = "1";
      } else {
        $newConfig["gdprFacebook"] = "0";
      }
      if (isset($_POST['chk_gdprGoogleAnalytics']) && $_POST['chk_gdprGoogleAnalytics']) {
        $newConfig["gdprGoogleAnalytics"] = "1";
      } else {
        $newConfig["gdprGoogleAnalytics"] = "0";
      }
      if (isset($_POST['chk_gdprInstagram']) && $_POST['chk_gdprInstagram']) {
        $newConfig["gdprInstagram"] = "1";
      } else {
        $newConfig["gdprInstagram"] = "0";
      }
      if (isset($_POST['chk_gdprLinkedin']) && $_POST['chk_gdprLinkedin']) {
        $newConfig["gdprLinkedin"] = "1";
      } else {
        $newConfig["gdprLinkedin"] = "0";
      }
      if (isset($_POST['chk_gdprPaypal']) && $_POST['chk_gdprPaypal']) {
        $newConfig["gdprPaypal"] = "1";
      } else {
        $newConfig["gdprPaypal"] = "0";
      }
      if (isset($_POST['chk_gdprPinterest']) && $_POST['chk_gdprPinterest']) {
        $newConfig["gdprPinterest"] = "1";
      } else {
        $newConfig["gdprPinterest"] = "0";
      }
      if (isset($_POST['chk_gdprSlideshare']) && $_POST['chk_gdprSlideshare']) {
        $newConfig["gdprSlideshare"] = "1";
      } else {
        $newConfig["gdprSlideshare"] = "0";
      }
      if (isset($_POST['chk_gdprTumblr']) && $_POST['chk_gdprTumblr']) {
        $newConfig["gdprTumblr"] = "1";
      } else {
        $newConfig["gdprTumblr"] = "0";
      }
      if (isset($_POST['chk_gdprTwitter']) && $_POST['chk_gdprTwitter']) {
        $newConfig["gdprTwitter"] = "1";
      } else {
        $newConfig["gdprTwitter"] = "0";
      }
      if (isset($_POST['chk_gdprXing']) && $_POST['chk_gdprXing']) {
        $newConfig["gdprXing"] = "1";
      } else {
        $newConfig["gdprXing"] = "0";
      }
      if (isset($_POST['chk_gdprYoutube']) && $_POST['chk_gdprYoutube']) {
        $newConfig["gdprYoutube"] = "1";
      } else {
        $newConfig["gdprYoutube"] = "0";
      }

      //
      // Save all config values in batch
      //
      $C->saveBatch($newConfig);

      //
      // Log this event
      //
      $LOG->logEvent("logConfig", $UL->username, "log_config");

      //
      // Success message
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['config_title'];
      $alertData['text'] = $LANG['config_alert_edit_success'];
      $alertData['help'] = '';

      //
      // Renew CSRF token after successful form processing
      //
      if (isset($_SESSION)) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
    }
    // ,--------------------,
    // | License Activation |
    // '--------------------'
    elseif (isset($_POST['btn_licActivate'])) {
      $response = $LIC->activate();

      if ($response->result == "success") {
        //
        // License activation success
        //
        $showAlert = true;
        $alertData['type'] = 'success';
        $alertData['title'] = $LANG['alert_success_title'];
        $alertData['subject'] = $LANG['alert_license_subject'];
        $alertData['text'] = $LANG['lic_alert_activation_success'];
        $alertData['help'] = '';

        //
        // Renew CSRF token after successful form processing
        //
        if (isset($_SESSION)) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
      } else {
        //
        // License activation failed
        //
        $showAlert = true;
        $alertData['type'] = 'danger';
        $alertData['title'] = $LANG['alert_danger_title'];
        $alertData['subject'] = $LANG['alert_license_subject'];
        $alertData['text'] = $LANG['lic_alert_activation_fail'] . " " . $response->message;
        $alertData['help'] = '';
      }
    }
    // ,-----------------------------,
    // | License Domain Registration |
    // '-----------------------------'
    elseif (isset($_POST['btn_licRegister'])) {
      $response = $LIC->activate();

      if ($response->result == "success") {
        //
        // Domain registration success
        //
        $showAlert = true;
        $alertData['type'] = 'success';
        $alertData['title'] = $LANG['alert_success_title'];
        $alertData['subject'] = $LANG['alert_license_subject'];
        $alertData['text'] = $LANG['lic_alert_registration_success'];
        $alertData['help'] = '';

        //
        // Renew CSRF token after successful form processing
        //
        if (isset($_SESSION)) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
      } else {
        //
        // Domain registration failed
        //
        $showAlert = true;
        $alertData['type'] = 'danger';
        $alertData['title'] = $LANG['alert_danger_title'];
        $alertData['subject'] = $LANG['alert_license_subject'];
        $alertData['text'] = $LANG['lic_alert_registration_fail'] . "<br /><i>" . $response->message . "</i>";
        $alertData['help'] = '';
      }
    }
    // ,--------------------------------,
    // | License Domain De-Registration |
    // '--------------------------------'
    elseif (isset($_POST['btn_licDeregister'])) {
      $response = $LIC->deactivate();
      if (is_object($response) && $response->result == "success") {
        //
        // Domain de-registration success
        //
        $showAlert = true;
        $alertData['type'] = 'success';
        $alertData['title'] = $LANG['alert_success_title'];
        $alertData['subject'] = $LANG['alert_license_subject'];
        $alertData['text'] = $LANG['lic_alert_deregistration_success'];
        $alertData['help'] = '';

        //
        // Renew CSRF token after successful form processing
        //
        if (isset($_SESSION)) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
      } else {
        //
        // Domain de-registration failed
        //
        $showAlert = true;
        $alertData['type'] = 'danger';
        $alertData['title'] = $LANG['alert_danger_title'];
        $alertData['subject'] = $LANG['alert_license_subject'];
        $alertData['text'] = $LANG['lic_alert_deregistration_fail'] . "<br /><i>" . (is_object($response) ? $response->message : $response) . "</i>";
        $alertData['help'] = '';
      }
    }
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_input'];
    $alertData['text'] = $LANG['config_alert_failed'];
    $alertData['help'] = '';
  }
}

//-----------------------------------------------------------------------------
//
// PREPARE VIEW
//

// Load all config values in one query for maximum performance
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];

foreach ($appLanguages as $appLang) {
  $viewData['languageList'][] = array('val' => $appLang, 'name' => ucwords($appLang), 'selected' => ($allConfig['defaultLanguage'] == $appLang) ? true : false);
}

foreach ($logLanguages as $logLang) {
  $viewData['logLanguageList'][] = array('val' => $logLang, 'name' => ucwords($logLang), 'selected' => ($allConfig['logLanguage'] == $logLang) ? true : false);
}

$schemes = $P->getSchemes();

foreach ($schemes as $scheme) {
  $viewData['schemeList'][] = array('val' => $scheme, 'name' => $scheme, 'selected' => ($allConfig['permissionScheme'] == $scheme) ? true : false);
}

$viewData['general'] = array(
  array('prefix' => 'config', 'name' => 'appURL', 'type' => 'text', 'placeholder' => '', 'value' => strip_tags($allConfig["appURL"]), 'maxlength' => '160'),
  array('prefix' => 'config', 'name' => 'appTitle', 'type' => 'text', 'placeholder' => '', 'value' => strip_tags($allConfig["appTitle"]), 'maxlength' => '160'),
  array('prefix' => 'config', 'name' => 'appDescription', 'type' => 'text', 'placeholder' => '', 'value' => strip_tags($allConfig["appDescription"]), 'maxlength' => '160'),
  array('prefix' => 'config', 'name' => 'appKeywords', 'type' => 'text', 'placeholder' => '', 'value' => strip_tags($allConfig["appKeywords"]), 'maxlength' => '160'),
  array('prefix' => 'config', 'name' => 'defaultLanguage', 'type' => 'list', 'values' => $viewData['languageList']),
  array('prefix' => 'config', 'name' => 'logLanguage', 'type' => 'list', 'values' => $viewData['logLanguageList']),
  array('prefix' => 'config', 'name' => 'showAlerts', 'type' => 'radio', 'values' => array('all', 'warnings', 'none'), 'value' => $allConfig["showAlerts"]),
  array('prefix' => 'config', 'name' => 'alertAutocloseSuccess', 'type' => 'check', 'values' => '', 'value' => $allConfig["alertAutocloseSuccess"]),
  array('prefix' => 'config', 'name' => 'alertAutocloseWarning', 'type' => 'check', 'values' => '', 'value' => $allConfig["alertAutocloseWarning"]),
  array('prefix' => 'config', 'name' => 'alertAutocloseDanger', 'type' => 'check', 'values' => '', 'value' => $allConfig["alertAutocloseDanger"]),
  array('prefix' => 'config', 'name' => 'alertAutocloseDelay', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["alertAutocloseDelay"], 'maxlength' => '5'),
  array('prefix' => 'config', 'name' => 'activateMessages', 'type' => 'check', 'values' => '', 'value' => $allConfig["activateMessages"]),
  array('prefix' => 'config', 'name' => 'permissionScheme', 'type' => 'list', 'values' => $viewData['schemeList']),
  array('prefix' => 'config', 'name' => 'userManual', 'type' => 'text', 'placeholder' => '', 'value' => urldecode($allConfig["userManual"]), 'maxlength' => '160'),
  array('prefix' => 'config', 'name' => 'pageHelp', 'type' => 'check', 'values' => '', 'value' => $allConfig["pageHelp"]),
);

$viewData['email'] = array(
  array('prefix' => 'config', 'name' => 'emailNotifications', 'type' => 'check', 'values' => '', 'value' => $allConfig["emailNotifications"]),
  array('prefix' => 'config', 'name' => 'mailFrom', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["mailFrom"], 'maxlength' => '150'),
  array('prefix' => 'config', 'name' => 'mailReply', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["mailReply"], 'maxlength' => '150'),
  array('prefix' => 'config', 'name' => 'mailSMTP', 'type' => 'check', 'values' => '', 'value' => $allConfig["mailSMTP"]),
  array('prefix' => 'config', 'name' => 'mailSMTPhost', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["mailSMTPhost"], 'maxlength' => '80'),
  array('prefix' => 'config', 'name' => 'mailSMTPport', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["mailSMTPport"], 'maxlength' => '8'),
  array('prefix' => 'config', 'name' => 'mailSMTPusername', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["mailSMTPusername"], 'maxlength' => '50'),
  array('prefix' => 'config', 'name' => 'mailSMTPpassword', 'type' => 'password', 'value' => $allConfig["mailSMTPpassword"], 'maxlength' => '50'),
  array('prefix' => 'config', 'name' => 'mailSMTPSSL', 'type' => 'check', 'values' => '', 'value' => $allConfig["mailSMTPSSL"]),
  array('prefix' => 'config', 'name' => 'mailSMTPAnonymous', 'type' => 'check', 'values' => '', 'value' => $allConfig["mailSMTPAnonymous"]),
);

$viewData['footer'] = array(
  array('prefix' => 'config', 'name' => 'footerCopyright', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["footerCopyright"], 'maxlength' => '160'),
  array('prefix' => 'config', 'name' => 'footerCopyrightUrl', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["footerCopyrightUrl"], 'maxlength' => '160'),
  array('prefix' => 'config', 'name' => 'footerSocialLinks', 'type' => 'textarea', 'value' => $allConfig["footerSocialLinks"], 'rows' => 5, 'placeholder' => ""),
  array('prefix' => 'config', 'name' => 'footerViewport', 'type' => 'check', 'values' => '', 'value' => $allConfig["footerViewport"]),
);

$viewData['homepage'] = array(
  array('prefix' => 'config', 'name' => 'defaultHomepage', 'type' => 'radio', 'values' => array('home', 'calendarview'), 'value' => $allConfig["defaultHomepage"]),
  array('prefix' => 'config', 'name' => 'homepage', 'type' => 'radio', 'values' => array('home', 'calendarview', 'messages'), 'value' => $allConfig["homepage"]),
  array('prefix' => 'config', 'name' => 'welcomeText', 'type' => 'textarea-wide', 'value' => $allConfig["welcomeText"], 'rows' => '10', 'placeholder' => ''),
);

$LIC->load();
$viewData['license'] = array(
  array('prefix' => 'config', 'name' => 'licKey', 'type' => 'text', 'placeholder' => '', 'value' => $LIC->readKey(), 'maxlength' => '32'),
  array('prefix' => 'config', 'name' => 'licExpiryWarning', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig['licExpiryWarning'], 'maxlength' => '3'),
);

$viewData['login'] = array(
  array('prefix' => 'config', 'name' => 'pwdStrength', 'type' => 'radio', 'values' => array('low', 'medium', 'high'), 'value' => $allConfig["pwdStrength"]),
  array('prefix' => 'config', 'name' => 'badLogins', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["badLogins"], 'maxlength' => '2'),
  array('prefix' => 'config', 'name' => 'gracePeriod', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["gracePeriod"], 'maxlength' => '3'),
  array('prefix' => 'config', 'name' => 'cookieLifetime', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["cookieLifetime"], 'maxlength' => '6'),
  array('prefix' => 'config', 'name' => 'disableTfa', 'type' => 'switch', 'values' => '', 'value' => $allConfig["disableTfa"]),
  array('prefix' => 'config', 'name' => 'forceTfa', 'type' => 'switch', 'values' => '', 'value' => $allConfig["forceTfa"]),
);

$viewData['registration'] = array(
  array('prefix' => 'config', 'name' => 'allowRegistration', 'type' => 'check', 'values' => '', 'value' => $allConfig["allowRegistration"]),
  array('prefix' => 'config', 'name' => 'emailConfirmation', 'type' => 'check', 'values' => '', 'value' => $allConfig["emailConfirmation"]),
  array('prefix' => 'config', 'name' => 'adminApproval', 'type' => 'check', 'values' => '', 'value' => $allConfig["adminApproval"]),
);

foreach ($timezones as $tz) {
  $viewData['timezoneList'][] = array('val' => $tz, 'name' => $tz, 'selected' => ($allConfig['timeZone'] == $tz) ? true : false);
}
$viewData['system'] = array(
  array('prefix' => 'config', 'name' => 'cookieConsent', 'type' => 'check', 'values' => '', 'value' => $allConfig["cookieConsent"]),
  array('prefix' => 'config', 'name' => 'cookieConsentCDN', 'type' => 'check', 'values' => '', 'value' => $allConfig["cookieConsentCDN"]),
  array('prefix' => 'config', 'name' => 'faCDN', 'type' => 'check', 'values' => '', 'value' => $allConfig["faCDN"]),
  array('prefix' => 'config', 'name' => 'jQueryCDN', 'type' => 'check', 'values' => '', 'value' => $allConfig["jQueryCDN"]),
  array('prefix' => 'config', 'name' => 'timeZone', 'type' => 'list', 'values' => $viewData['timezoneList']),
  array('prefix' => 'config', 'name' => 'googleAnalytics', 'type' => 'check', 'values' => '', 'value' => $allConfig["googleAnalytics"]),
  array('prefix' => 'config', 'name' => 'googleAnalyticsID', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["googleAnalyticsID"], 'maxlength' => '16'),
  array('prefix' => 'config', 'name' => 'matomoAnalytics', 'type' => 'check', 'values' => '', 'value' => $allConfig["matomoAnalytics"]),
  array('prefix' => 'config', 'name' => 'matomoUrl', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["matomoUrl"], 'maxlength' => '160'),
  array('prefix' => 'config', 'name' => 'matomoSiteId', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["matomoSiteId"], 'maxlength' => '160'),
  array('prefix' => 'config', 'name' => 'noIndex', 'type' => 'check', 'values' => '', 'value' => $allConfig["noIndex"]),
  array('prefix' => 'config', 'name' => 'noCaching', 'type' => 'check', 'values' => '', 'value' => $allConfig["noCaching"]),
  array('prefix' => 'config', 'name' => 'versionCompare', 'type' => 'check', 'values' => '', 'value' => $allConfig["versionCompare"]),
  array('prefix' => 'config', 'name' => 'underMaintenance', 'type' => 'check', 'values' => '', 'value' => $allConfig["underMaintenance"]),
);

foreach ($appJqueryUIThemes as $jqueryUITheme) {
  $viewData['jqueryUIThemeList'][] = array('val' => $jqueryUITheme, 'name' => ucwords($jqueryUITheme), 'selected' => ($allConfig['jqtheme'] == $jqueryUITheme) ? true : false);
}
$viewData['fonts'][] = array('val' => 'default', 'name' => 'Default', 'selected' => ($allConfig['font'] == 'default') ? true : false);
$viewData['fonts'][] = array('val' => 'lato', 'name' => 'Lato', 'selected' => ($allConfig['font'] == 'lato') ? true : false);
$viewData['fonts'][] = array('val' => 'montserrat', 'name' => 'Montserrat', 'selected' => ($allConfig['font'] == 'montserrat') ? true : false);
$viewData['fonts'][] = array('val' => 'opensans', 'name' => 'Open Sans', 'selected' => ($allConfig['font'] == 'opensans') ? true : false);
$viewData['fonts'][] = array('val' => 'poppins', 'name' => 'Poppins', 'selected' => ($allConfig['font'] == 'poppins') ? true : false);
$viewData['fonts'][] = array('val' => 'roboto', 'name' => 'Roboto', 'selected' => ($allConfig['font'] == 'roboto') ? true : false);

$viewData['theme'] = array(
  array('prefix' => 'config', 'name' => 'theme', 'type' => 'infoWide', 'value' => ''),
  array('prefix' => 'config', 'name' => 'defaultMenu', 'type' => 'radio', 'values' => array('navbar', 'sidebar'), 'value' => $allConfig["defaultMenu"]),
  array('prefix' => 'config', 'name' => 'jqtheme', 'type' => 'list', 'values' => $viewData['jqueryUIThemeList']),
  array('prefix' => 'config', 'name' => 'jqthemeSample', 'type' => 'date', 'value' => ''),
  array('prefix' => 'config', 'name' => 'font', 'type' => 'list', 'values' => $viewData['fonts']),
);

$viewData['user'] = array(
  array('prefix' => 'config', 'name' => 'userCustom1', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["userCustom1"], 'maxlength' => '50'),
  array('prefix' => 'config', 'name' => 'userCustom2', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["userCustom2"], 'maxlength' => '50'),
  array('prefix' => 'config', 'name' => 'userCustom3', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["userCustom3"], 'maxlength' => '50'),
  array('prefix' => 'config', 'name' => 'userCustom4', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["userCustom4"], 'maxlength' => '50'),
  array('prefix' => 'config', 'name' => 'userCustom5', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["userCustom5"], 'maxlength' => '50'),
);

$viewData['gdpr'] = array(
  array('prefix' => 'config', 'name' => 'gdprPolicyPage', 'type' => 'check', 'values' => '', 'value' => $allConfig["gdprPolicyPage"]),
  array('prefix' => 'config', 'name' => 'gdprOrganization', 'type' => 'text', 'placeholder' => 'ACME Inc.', 'value' => strip_tags($allConfig["gdprOrganization"]), 'maxlength' => '160'),
  array('prefix' => 'config', 'name' => 'gdprController', 'type' => 'textarea', 'value' => $allConfig["gdprController"], 'rows' => 5, 'placeholder' => "ACME Inc.\nStreet\nTown\nCountry\nEmail"),
  array('prefix' => 'config', 'name' => 'gdprOfficer', 'type' => 'textarea', 'value' => $allConfig["gdprOfficer"], 'rows' => 5, 'placeholder' => "John Doe\nPhone\nEmail"),
);

//-----------------------------------------------------------------------------
//
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
