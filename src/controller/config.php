<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Config Controller
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
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission)) {
    $alertData['type'] = 'warning';
    $alertData['title'] = $LANG['alert_alert_title'];
    $alertData['subject'] = $LANG['alert_not_allowed_subject'];
    $alertData['text'] = $LANG['alert_not_allowed_text'];
    $alertData['help'] = $LANG['alert_not_allowed_help'];
    require(WEBSITE_ROOT . '/controller/alert.php');
    die();
}

//=============================================================================
//
// CHECK LICENSE
//
$alertData = array();
$showAlert = false;
$licExpiryWarning = $C->read('licExpiryWarning');
$LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);

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
if (!empty($_POST)) {
    //
    // Sanitize input
    //
    //$_POST = sanitize($_POST); // Will cripple CKEditor input

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
            //
            // General
            //
            if (filter_var($_POST['txt_appURL'], FILTER_VALIDATE_URL)) $C->save("appURL", $_POST['txt_appURL']);
            else $C->save("appURL", "#");
            $C->save("appTitle", sanitize($_POST['txt_appTitle']));
            $C->save("appDescription", sanitize($_POST['txt_appDescription']));
            $C->save("appKeywords", sanitize($_POST['txt_appKeywords']));
            if ($_POST['sel_defaultLanguage']) $C->save("defaultLanguage", $_POST['sel_defaultLanguage']);
            else $C->save("defaultLanguage", "english");
            if ($_POST['sel_logLanguage']) $C->save("logLanguage", $_POST['sel_logLanguage']);
            else $C->save("logLanguage", "english");
            if ($_POST['sel_permissionScheme']) $C->save("permissionScheme", $_POST['sel_permissionScheme']);
            else $C->save("permissionScheme", "Default");
            if ($_POST['opt_showAlerts']) $C->save("showAlerts", $_POST['opt_showAlerts']);
            if (isset($_POST['chk_activateMessages']) && $_POST['chk_activateMessages']) $C->save("activateMessages", "1");
            else $C->save("activateMessages", "0");
            if (isset($_POST['chk_showBanner']) && $_POST['chk_showBanner']) $C->save("showBanner", "1");
            else $C->save("showBanner", "0");
            if (isset($_POST['chk_pageHelp']) && $_POST['chk_pageHelp']) $C->save("pageHelp", "1");
            else $C->save("pageHelp", "0");
            if (strlen($_POST['txt_userManual'])) {
                $myUrl = rtrim($_POST['txt_userManual'], '/') . '/'; // Ensure trailing slash
                $C->save("userManual", urlencode($myUrl));
            } else {
                $C->save("userManual", '');
            }

            //
            // Email
            //
            if (isset($_POST['chk_emailNotifications']) && $_POST['chk_emailNotifications']) $C->save("emailNotifications", "1");
            else $C->save("emailNotifications", "0");
            if (isset($_POST['chk_emailNoPastNotifications']) && $_POST['chk_emailNoPastNotifications']) $C->save("emailNoPastNotifications", "1");
            else $C->save("emailNoPastNotifications", "0");
            $C->save("mailFrom", sanitize($_POST['txt_mailFrom']));
            if (validEmail($_POST['txt_mailReply'])) $C->save("mailReply", $_POST['txt_mailReply']);
            else $C->save("mailReply", "noreply@teamcalneo.com");
            if (isset($_POST['chk_mailSMTP']) && $_POST['chk_mailSMTP']) $C->save("mailSMTP", "1");
            else $C->save("mailSMTP", "0");
            $C->save("mailSMTPhost", sanitize($_POST['txt_mailSMTPhost']));
            $C->save("mailSMTPport", intval($_POST['txt_mailSMTPport']));
            $C->save("mailSMTPusername", sanitize($_POST['txt_mailSMTPusername']));
            $C->save("mailSMTPpassword", sanitize($_POST['txt_mailSMTPpassword']));
            if (isset($_POST['chk_mailSMTPSSL']) && $_POST['chk_mailSMTPSSL']) $C->save("mailSMTPSSL", "1");
            else $C->save("mailSMTPSSL", "0");
            if (isset($_POST['chk_mailSMTPAnonymous']) && $_POST['chk_mailSMTPAnonymous']) $C->save("mailSMTPAnonymous", "1");
            else $C->save("mailSMTPAnonymous", "0");

            //
            // Footer
            //
            $C->save("footerCopyright", sanitize($_POST['txt_footerCopyright']));
            if (strlen($_POST['txt_footerCopyrightUrl']) and filter_var($_POST['txt_footerCopyrightUrl'], FILTER_VALIDATE_URL)) $C->save("footerCopyrightUrl", $_POST['txt_footerCopyrightUrl']);
            else $C->save("footerCopyrightUrl", "");
            if (isset($_POST['chk_footerViewport']) && $_POST['chk_footerViewport']) $C->save("footerViewport", "1");
            else $C->save("footerViewport", "0");
            $C->save("footerSocialLinks", sanitize($_POST['txt_footerSocialLinks']));

            //
            // Homepage
            //
            if ($_POST['opt_homepage']) $C->save("homepage", $_POST['opt_homepage']);
            if ($_POST['opt_defaultHomepage']) $C->save("defaultHomepage", $_POST['opt_defaultHomepage']);
            $C->save("welcomeText", $_POST['txt_welcomeText']);

            //
            // License
            //
            $LIC->saveKey(trim($_POST['txt_licKey']));
            if (strlen($_POST['txt_licExpiryWarning'])) {
                $C->save("licExpiryWarning", intval($_POST['txt_licExpiryWarning']));
            } else {
                $C->save("licExpiryWarning", 0);
            }

            //
            // Login
            //
            if ($_POST['opt_pwdStrength']) $C->save("pwdStrength", $_POST['opt_pwdStrength']);
            $C->save("badLogins", intval($_POST['txt_badLogins']));
            $C->save("gracePeriod", intval($_POST['txt_gracePeriod']));
            $C->save("cookieLifetime", intval($_POST['txt_cookieLifetime']));

            //
            // Registration
            //
            if (isset($_POST['chk_allowRegistration']) && $_POST['chk_allowRegistration']) $C->save("allowRegistration", "1");
            else $C->save("allowRegistration", "0");
            if (isset($_POST['chk_emailConfirmation']) && $_POST['chk_emailConfirmation']) $C->save("emailConfirmation", "1");
            else $C->save("emailConfirmation", "0");
            if (isset($_POST['chk_adminApproval']) && $_POST['chk_adminApproval']) $C->save("adminApproval", "1");
            else $C->save("adminApproval", "0");

            //
            // System
            //
            if (isset($_POST['chk_cookieConsent']) && $_POST['chk_cookieConsent']) $C->save("cookieConsent", "1");
            else $C->save("cookieConsent", "0");
            if (isset($_POST['chk_cookieConsentCDN']) && $_POST['chk_cookieConsentCDN']) $C->save("cookieConsentCDN", "1");
            else $C->save("cookieConsentCDN", "0");
            if (isset($_POST['chk_faCDN']) && $_POST['chk_faCDN']) $C->save("faCDN", "1");
            else $C->save("faCDN", "0");
            if (isset($_POST['chk_jQueryCDN']) && $_POST['chk_jQueryCDN']) $C->save("jQueryCDN", "1");
            else $C->save("jQueryCDN", "0");
            if ($_POST['sel_timeZone']) $C->save("timeZone", $_POST['sel_timeZone']);
            else $C->save("timeZone", "UTC");
            if (isset($_POST['chk_googleAnalytics']) && $_POST['chk_googleAnalytics']) {
                if (preg_match('/\bUA-\d{4,10}-\d{1,4}\b/', $_POST['txt_googleAnalyticsID'])) {
                    $C->save("googleAnalytics", "1");
                    $C->save("googleAnalyticsID", $_POST['txt_googleAnalyticsID']);
                }
            } else {
                $C->save("googleAnalytics", "0");
                $C->save("googleAnalyticsID", "");
            }
            if (isset($_POST['chk_noIndex']) && $_POST['chk_noIndex']) $C->save("noIndex", "1");
            else $C->save("noIndex", "0");
            if (isset($_POST['chk_noCaching']) && $_POST['chk_noCaching']) $C->save("noCaching", "1");
            else $C->save("noCaching", "0");
            if (isset($_POST['chk_versionCompare']) && $_POST['chk_versionCompare']) $C->save("versionCompare", "1");
            else $C->save("versionCompare", "0");
            if (isset($_POST['chk_underMaintenance']) && $_POST['chk_underMaintenance']) $C->save("underMaintenance", "1");
            else $C->save("underMaintenance", "0");

            //
            // Theme
            //
            if ($_POST['sel_theme']) $C->save("theme", $_POST['sel_theme']);
            else $C->save("theme", "bootstrap");
            if ($_POST['opt_menuBarBg']) $C->save("menuBarBg", $_POST['opt_menuBarBg']);
            if (isset($_POST['chk_menuBarDark']) && $_POST['chk_menuBarDark']) $C->save("menuBarDark", "1");
            else $C->save("menuBarDark", "0");
            if (isset($_POST['chk_allowUserTheme']) && $_POST['chk_allowUserTheme']) $C->save("allowUserTheme", "1");
            else $C->save("allowUserTheme", "0");
            if ($_POST['sel_jqtheme']) $C->save("jqtheme", $_POST['sel_jqtheme']);
            else $C->save("jqtheme", "smoothness");

            //
            // User
            //
            $C->save("userCustom1", sanitize($_POST['txt_userCustom1']));
            $C->save("userCustom2", sanitize($_POST['txt_userCustom2']));
            $C->save("userCustom3", sanitize($_POST['txt_userCustom3']));
            $C->save("userCustom4", sanitize($_POST['txt_userCustom4']));
            $C->save("userCustom5", sanitize($_POST['txt_userCustom5']));

            //
            // GDPR
            //
            if (isset($_POST['chk_gdprPolicyPage']) && $_POST['chk_gdprPolicyPage']) $C->save("gdprPolicyPage", "1");
            else $C->save("gdprPolicyPage", "0");
            $C->save("gdprOrganization", sanitize($_POST['txt_gdprOrganization']));
            $C->save("gdprController", sanitize($_POST['txt_gdprController']));
            $C->save("gdprOfficer", sanitize($_POST['txt_gdprOfficer']));
            if (isset($_POST['chk_gdprFacebook']) && $_POST['chk_gdprFacebook']) $C->save("gdprFacebook", "1");
            else $C->save("gdprFacebook", "0");
            if (isset($_POST['chk_gdprGoogleAnalytics']) && $_POST['chk_gdprGoogleAnalytics']) $C->save("gdprGoogleAnalytics", "1");
            else $C->save("gdprGoogleAnalytics", "0");
            if (isset($_POST['chk_gdprInstagram']) && $_POST['chk_gdprInstagram']) $C->save("gdprInstagram", "1");
            else $C->save("gdprInstagram", "0");
            if (isset($_POST['chk_gdprLinkedin']) && $_POST['chk_gdprLinkedin']) $C->save("gdprLinkedin", "1");
            else $C->save("gdprLinkedin", "0");
            if (isset($_POST['chk_gdprPaypal']) && $_POST['chk_gdprPaypal']) $C->save("gdprPaypal", "1");
            else $C->save("gdprPaypal", "0");
            if (isset($_POST['chk_gdprPinterest']) && $_POST['chk_gdprPinterest']) $C->save("gdprPinterest", "1");
            else $C->save("gdprPinterest", "0");
            if (isset($_POST['chk_gdprSlideshare']) && $_POST['chk_gdprSlideshare']) $C->save("gdprSlideshare", "1");
            else $C->save("gdprSlideshare", "0");
            if (isset($_POST['chk_gdprTumblr']) && $_POST['chk_gdprTumblr']) $C->save("gdprTumblr", "1");
            else $C->save("gdprTumblr", "0");
            if (isset($_POST['chk_gdprTwitter']) && $_POST['chk_gdprTwitter']) $C->save("gdprTwitter", "1");
            else $C->save("gdprTwitter", "0");
            if (isset($_POST['chk_gdprXing']) && $_POST['chk_gdprXing']) $C->save("gdprXing", "1");
            else $C->save("gdprXing", "0");
            if (isset($_POST['chk_gdprYoutube']) && $_POST['chk_gdprYoutube']) $C->save("gdprYoutube", "1");
            else $C->save("gdprYoutube", "0");

            //
            // Log this event
            //
            $LOG->log("logConfig", $UL->username, "log_config");
            header("Location: index.php?action=config");
            die();
        }
        // ,--------------------,
        // | License Activation |
        // '--------------------'
        else if (isset($_POST['btn_licActivate'])) {
            $response = $LIC->activate();

            if ($response->result == "success") {
                //
                // License activation success
                //
                $showAlert = TRUE;
                $alertData['type'] = 'success';
                $alertData['title'] = $LANG['alert_success_title'];
                $alertData['subject'] = $LANG['alert_license_subject'];
                $alertData['text'] = $LANG['lic_alert_activation_success'];
                $alertData['help'] = '';
            } else {
                //
                // License activation failed
                //
                $showAlert = TRUE;
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
        else if (isset($_POST['btn_licRegister'])) {
            $response = $LIC->activate();

            if ($response->result == "success") {
                //
                // Domain registration success
                //
                $showAlert = TRUE;
                $alertData['type'] = 'success';
                $alertData['title'] = $LANG['alert_success_title'];
                $alertData['subject'] = $LANG['alert_license_subject'];
                $alertData['text'] = $LANG['lic_alert_registration_success'];
                $alertData['help'] = '';
            } else {
                //
                // Domain registration failed
                //
                $showAlert = TRUE;
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
        else if (isset($_POST['btn_licDeregister'])) {
            $response = $LIC->deactivate();

            if ($response->result == "success") {
                //
                // Domain deregistration success
                //
                $showAlert = TRUE;
                $alertData['type'] = 'success';
                $alertData['title'] = $LANG['alert_success_title'];
                $alertData['subject'] = $LANG['alert_license_subject'];
                $alertData['text'] = $LANG['lic_alert_deregistration_success'];
                $alertData['help'] = '';
            } else {
                //
                // Domain deregistration failed
                //
                $showAlert = TRUE;
                $alertData['type'] = 'danger';
                $alertData['title'] = $LANG['alert_danger_title'];
                $alertData['subject'] = $LANG['alert_license_subject'];
                $alertData['text'] = $LANG['lic_alert_deregistration_fail'] . "<br /><i>" . $response->message . "</i>";
                $alertData['help'] = '';
            }
        }
    } else {
        //
        // Input validation failed
        //
        $showAlert = TRUE;
        $alertData['type'] = 'danger';
        $alertData['title'] = $LANG['alert_danger_title'];
        $alertData['subject'] = $LANG['alert_input'];
        $alertData['text'] = $LANG['register_alert_failed'];
        $alertData['help'] = '';
    }
}

//=============================================================================
//
// PREPARE VIEW
//
foreach ($appLanguages as $appLang) {
    $viewData['languageList'][] = array('val' => $appLang, 'name' => proper($appLang), 'selected' => ($C->read("defaultLanguage") == $appLang) ? true : false);
}

foreach ($logLanguages as $logLang) {
    $viewData['logLanguageList'][] = array('val' => $logLang, 'name' => proper($logLang), 'selected' => ($C->read("logLanguage") == $logLang) ? true : false);
}

$schemes = $P->getSchemes();

foreach ($schemes as $scheme) {
    $viewData['schemeList'][] = array('val' => $scheme, 'name' => $scheme, 'selected' => ($C->read("permissionScheme") == $scheme) ? true : false);
}

$viewData['general'] = array(
    array('prefix' => 'config', 'name' => 'appURL', 'type' => 'text', 'placeholder' => '', 'value' => strip_tags($C->read("appURL")), 'maxlength' => '160'),
    array('prefix' => 'config', 'name' => 'appTitle', 'type' => 'text', 'placeholder' => '', 'value' => strip_tags($C->read("appTitle")), 'maxlength' => '160'),
    array('prefix' => 'config', 'name' => 'appDescription', 'type' => 'text', 'placeholder' => '', 'value' => strip_tags($C->read("appDescription")), 'maxlength' => '160'),
    array('prefix' => 'config', 'name' => 'appKeywords', 'type' => 'text', 'placeholder' => '', 'value' => strip_tags($C->read("appKeywords")), 'maxlength' => '160'),
    array('prefix' => 'config', 'name' => 'defaultLanguage', 'type' => 'list', 'values' => $viewData['languageList']),
    array('prefix' => 'config', 'name' => 'logLanguage', 'type' => 'list', 'values' => $viewData['logLanguageList']),
    array('prefix' => 'config', 'name' => 'showAlerts', 'type' => 'radio', 'values' => array('all', 'warnings', 'none'), 'value' => $C->read("showAlerts")),
    array('prefix' => 'config', 'name' => 'activateMessages', 'type' => 'check', 'values' => '', 'value' => $C->read("activateMessages")),
    array('prefix' => 'config', 'name' => 'permissionScheme', 'type' => 'list', 'values' => $viewData['schemeList']),
    array('prefix' => 'config', 'name' => 'userManual', 'type' => 'text', 'placeholder' => '', 'value' => urldecode($C->read("userManual")), 'maxlength' => '160'),
    array('prefix' => 'config', 'name' => 'showBanner', 'type' => 'check', 'values' => '', 'value' => $C->read("showBanner")),
    array('prefix' => 'config', 'name' => 'pageHelp', 'type' => 'check', 'values' => '', 'value' => $C->read("pageHelp")),
);

$viewData['email'] = array(
    array('prefix' => 'config', 'name' => 'emailNotifications', 'type' => 'check', 'values' => '', 'value' => $C->read("emailNotifications")),
    array('prefix' => 'config', 'name' => 'mailFrom', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("mailFrom"), 'maxlength' => '150'),
    array('prefix' => 'config', 'name' => 'mailReply', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("mailReply"), 'maxlength' => '150'),
    array('prefix' => 'config', 'name' => 'mailSMTP', 'type' => 'check', 'values' => '', 'value' => $C->read("mailSMTP")),
    array('prefix' => 'config', 'name' => 'mailSMTPhost', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("mailSMTPhost"), 'maxlength' => '80'),
    array('prefix' => 'config', 'name' => 'mailSMTPport', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("mailSMTPport"), 'maxlength' => '8'),
    array('prefix' => 'config', 'name' => 'mailSMTPusername', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("mailSMTPusername"), 'maxlength' => '50'),
    array('prefix' => 'config', 'name' => 'mailSMTPpassword', 'type' => 'password', 'value' => $C->read("mailSMTPpassword"), 'maxlength' => '50'),
    array('prefix' => 'config', 'name' => 'mailSMTPSSL', 'type' => 'check', 'values' => '', 'value' => $C->read("mailSMTPSSL")),
    array('prefix' => 'config', 'name' => 'mailSMTPAnonymous', 'type' => 'check', 'values' => '', 'value' => $C->read("mailSMTPAnonymous")),
);

$viewData['footer'] = array(
    array('prefix' => 'config', 'name' => 'footerCopyright', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("footerCopyright"), 'maxlength' => '160'),
    array('prefix' => 'config', 'name' => 'footerCopyrightUrl', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("footerCopyrightUrl"), 'maxlength' => '160'),
    array('prefix' => 'config', 'name' => 'footerSocialLinks', 'type' => 'textarea', 'value' => $C->read("footerSocialLinks"), 'rows' => 5, 'placeholder' => ""),
    array('prefix' => 'config', 'name' => 'footerViewport', 'type' => 'check', 'values' => '', 'value' => $C->read("footerViewport")),
);

$viewData['homepage'] = array(
    array('prefix' => 'config', 'name' => 'defaultHomepage', 'type' => 'radio', 'values' => array('home', 'calendarview'), 'value' => $C->read("defaultHomepage")),
    array('prefix' => 'config', 'name' => 'homepage', 'type' => 'radio', 'values' => array('home', 'calendarview', 'messages'), 'value' => $C->read("homepage")),
    array('prefix' => 'config', 'name' => 'welcomeText', 'type' => 'ckeditor', 'value' => $C->read("welcomeText"), 'rows' => '10'),
);

$LIC->load();
$viewData['license'] = array(
    array('prefix' => 'config', 'name' => 'licKey', 'type' => 'text', 'placeholder' => '', 'value' => $LIC->readKey(), 'maxlength' => '32'),
    array('prefix' => 'config', 'name' => 'licExpiryWarning', 'type' => 'text', 'placeholder' => '', 'value' => $C->read('licExpiryWarning'), 'maxlength' => '3'),
);

$viewData['login'] = array(
    array('prefix' => 'config', 'name' => 'pwdStrength', 'type' => 'radio', 'values' => array('low', 'medium', 'high'), 'value' => $C->read("pwdStrength")),
    array('prefix' => 'config', 'name' => 'badLogins', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("badLogins"), 'maxlength' => '2'),
    array('prefix' => 'config', 'name' => 'gracePeriod', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("gracePeriod"), 'maxlength' => '3'),
    array('prefix' => 'config', 'name' => 'cookieLifetime', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("cookieLifetime"), 'maxlength' => '6'),
);

$viewData['registration'] = array(
    array('prefix' => 'config', 'name' => 'allowRegistration', 'type' => 'check', 'values' => '', 'value' => $C->read("allowRegistration")),
    array('prefix' => 'config', 'name' => 'emailConfirmation', 'type' => 'check', 'values' => '', 'value' => $C->read("emailConfirmation")),
    array('prefix' => 'config', 'name' => 'adminApproval', 'type' => 'check', 'values' => '', 'value' => $C->read("adminApproval")),
);

foreach ($appJqueryUIThemes as $jqueryUITheme) {
    $viewData['jqueryUIThemeList'][] = array('val' => $jqueryUITheme, 'name' => proper($jqueryUITheme), 'selected' => ($C->read("jqtheme") == $jqueryUITheme) ? true : false);
}
foreach ($timezones as $tz) {
    $viewData['timezoneList'][] = array('val' => $tz, 'name' => $tz, 'selected' => ($C->read("timeZone") == $tz) ? true : false);
}
$viewData['system'] = array(
    array('prefix' => 'config', 'name' => 'cookieConsent', 'type' => 'check', 'values' => '', 'value' => $C->read("cookieConsent")),
    array('prefix' => 'config', 'name' => 'cookieConsentCDN', 'type' => 'check', 'values' => '', 'value' => $C->read("cookieConsentCDN")),
    array('prefix' => 'config', 'name' => 'faCDN', 'type' => 'check', 'values' => '', 'value' => $C->read("faCDN")),
    array('prefix' => 'config', 'name' => 'jQueryCDN', 'type' => 'check', 'values' => '', 'value' => $C->read("jQueryCDN")),
    array('prefix' => 'config', 'name' => 'timeZone', 'type' => 'list', 'values' => $viewData['timezoneList']),
    array('prefix' => 'config', 'name' => 'googleAnalytics', 'type' => 'check', 'values' => '', 'value' => $C->read("googleAnalytics")),
    array('prefix' => 'config', 'name' => 'googleAnalyticsID', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("googleAnalyticsID"), 'maxlength' => '16'),
    array('prefix' => 'config', 'name' => 'noIndex', 'type' => 'check', 'values' => '', 'value' => $C->read("noIndex")),
    array('prefix' => 'config', 'name' => 'noCaching', 'type' => 'check', 'values' => '', 'value' => $C->read("noCaching")),
    array('prefix' => 'config', 'name' => 'versionCompare', 'type' => 'check', 'values' => '', 'value' => $C->read("versionCompare")),
    array('prefix' => 'config', 'name' => 'underMaintenance', 'type' => 'check', 'values' => '', 'value' => $C->read("underMaintenance")),
);

foreach ($appThemes as $appTheme) {
    $viewData['themeList'][] = array('val' => $appTheme, 'name' => proper($appTheme), 'selected' => ($C->read("theme") == $appTheme) ? true : false);
}

$viewData['theme'] = array(
    array('prefix' => 'config', 'name' => 'theme', 'type' => 'list', 'values' => $viewData['themeList']),
    array('prefix' => 'config', 'name' => 'menuBarBg', 'type' => 'radio', 'values' => $bsBgColors, 'value' => $C->read("menuBarBg")),
    array('prefix' => 'config', 'name' => 'menuBarDark', 'type' => 'check', 'values' => '', 'value' => $C->read("menuBarDark")),
    array('prefix' => 'config', 'name' => 'allowUserTheme', 'type' => 'check', 'values' => '', 'value' => $C->read("allowUserTheme")),
    array('prefix' => 'config', 'name' => 'jqtheme', 'type' => 'list', 'values' => $viewData['jqueryUIThemeList']),
);

$viewData['user'] = array(
    array('prefix' => 'config', 'name' => 'userCustom1', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("userCustom1"), 'maxlength' => '50'),
    array('prefix' => 'config', 'name' => 'userCustom2', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("userCustom2"), 'maxlength' => '50'),
    array('prefix' => 'config', 'name' => 'userCustom3', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("userCustom3"), 'maxlength' => '50'),
    array('prefix' => 'config', 'name' => 'userCustom4', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("userCustom4"), 'maxlength' => '50'),
    array('prefix' => 'config', 'name' => 'userCustom5', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("userCustom5"), 'maxlength' => '50'),
);

$viewData['gdpr'] = array(
    array('prefix' => 'config', 'name' => 'gdprPolicyPage', 'type' => 'check', 'values' => '', 'value' => $C->read("gdprPolicyPage")),
    array('prefix' => 'config', 'name' => 'gdprOrganization', 'type' => 'text', 'placeholder' => 'ACME Inc.', 'value' => strip_tags($C->read("gdprOrganization")), 'maxlength' => '160'),
    array('prefix' => 'config', 'name' => 'gdprController', 'type' => 'textarea', 'value' => $C->read("gdprController"), 'rows' => 5, 'placeholder' => "ACME Inc.\nStreet\nTown\nCountry\nEmail"),
    array('prefix' => 'config', 'name' => 'gdprOfficer', 'type' => 'textarea', 'value' => $C->read("gdprOfficer"), 'rows' => 5, 'placeholder' => "John Doe\nPhone\nEmail"),
);

//=============================================================================
//
// SHOW VIEW
//
require(WEBSITE_ROOT . '/views/header.php');
require(WEBSITE_ROOT . '/views/menu.php');
include(WEBSITE_ROOT . '/views/' . $controller . '.php');
require(WEBSITE_ROOT . '/views/footer.php');
