<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;

/**
 * Config Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class ConfigController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    $allConfig = $this->allConfig;

    if (!isAllowed($this->CONF['controllers']['config']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    // Check License
    $alertData        = [];
    $showAlert        = false;
    $licExpiryWarning = (int) $allConfig['licExpiryWarning'];
    $LIC              = new LicenseModel();

    if (!isset($_SESSION['license_checked']) || (time() - $_SESSION['license_checked']) > 300) {
      $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);
      $_SESSION['license_checked'] = time();
    }

    $viewData               = [];
    $viewData['pageHelp']   = $allConfig['pageHelp'];
    $viewData['showAlerts'] = $allConfig['showAlerts'];

    $inputAlert = [];
    $alertData  = [];
    $showAlert  = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      foreach ($_POST as $key => $value) {
        $_POST[$key]   = trim($value);
        $sanitizeLater = ['txt_welcomeText', 'txt_gdprController', 'txt_gdprOfficer'];
        if (str_starts_with($key, 'txt_') && !in_array($key, $sanitizeLater)) {
          $_POST[$key] = sanitize($value);
        }
      }

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_confApply'])) {
        $this->handleApply($LIC, $showAlert, $alertData);
        $allConfig = $this->allConfig;
      }
      elseif (isset($_POST['btn_licActivate'])) {
        $this->handleLicenseActivation($LIC, $showAlert, $alertData);
      }
      elseif (isset($_POST['btn_licRegister'])) {
        $this->handleLicenseRegistration($LIC, $showAlert, $alertData);
      }
      elseif (isset($_POST['btn_licDeregister'])) {
        $this->handleLicenseDeregistration($LIC, $showAlert, $alertData);
      }
      elseif (isset($_POST['btn_clearCache'])) {
        $this->handleClearCache($showAlert, $alertData);
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    // Prepare View Data
    $viewData['languageList']      = [];
    $viewData['logLanguageList']   = [];
    $viewData['jqueryUIThemeList'] = [];
    $viewData['schemeList']        = [];
    $viewData['timezoneList']      = [];

    foreach ($this->appLanguages as $appLang) {
      $viewData['languageList'][] = ['val' => $appLang, 'name' => ucwords($appLang), 'selected' => ($allConfig['defaultLanguage'] == $appLang)];
    }
    foreach ($this->logLanguages as $logLang) {
      $viewData['logLanguageList'][] = ['val' => $logLang, 'name' => ucwords($logLang), 'selected' => ($allConfig['logLanguage'] == $logLang)];
    }
    $schemes = $this->P->getSchemes();
    foreach ($schemes as $scheme) {
      $viewData['schemeList'][] = ['val' => $scheme, 'name' => $scheme, 'selected' => ($allConfig['permissionScheme'] == $scheme)];
    }

    $viewData['general'] = [
      ['prefix' => 'config', 'name' => 'appURL', 'type' => 'text', 'placeholder' => '', 'value' => strip_tags($allConfig["appURL"]), 'maxlength' => '160'],
      ['prefix' => 'config', 'name' => 'appTitle', 'type' => 'text', 'placeholder' => '', 'value' => strip_tags($allConfig["appTitle"]), 'maxlength' => '160'],
      ['prefix' => 'config', 'name' => 'appDescription', 'type' => 'text', 'placeholder' => '', 'value' => strip_tags($allConfig["appDescription"]), 'maxlength' => '160'],
      ['prefix' => 'config', 'name' => 'appKeywords', 'type' => 'text', 'placeholder' => '', 'value' => strip_tags($allConfig["appKeywords"]), 'maxlength' => '160'],
      ['prefix' => 'config', 'name' => 'defaultLanguage', 'type' => 'list', 'values' => $viewData['languageList']],
      ['prefix' => 'config', 'name' => 'logLanguage', 'type' => 'list', 'values' => $viewData['logLanguageList']],
      ['prefix' => 'config', 'name' => 'showAlerts', 'type' => 'radio', 'values' => ['all', 'warnings', 'none'], 'value' => $allConfig["showAlerts"]],
      ['prefix' => 'config', 'name' => 'alertAutocloseSuccess', 'type' => 'check', 'values' => '', 'value' => $allConfig["alertAutocloseSuccess"]],
      ['prefix' => 'config', 'name' => 'alertAutocloseWarning', 'type' => 'check', 'values' => '', 'value' => $allConfig["alertAutocloseWarning"]],
      ['prefix' => 'config', 'name' => 'alertAutocloseDanger', 'type' => 'check', 'values' => '', 'value' => $allConfig["alertAutocloseDanger"]],
      ['prefix' => 'config', 'name' => 'alertAutocloseDelay', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["alertAutocloseDelay"], 'maxlength' => '5'],
      ['prefix' => 'config', 'name' => 'activateMessages', 'type' => 'check', 'values' => '', 'value' => $allConfig["activateMessages"]],
      ['prefix' => 'config', 'name' => 'permissionScheme', 'type' => 'list', 'values' => $viewData['schemeList']],
      ['prefix' => 'config', 'name' => 'userManual', 'type' => 'text', 'placeholder' => '', 'value' => urldecode($allConfig["userManual"]), 'maxlength' => '160'],
      ['prefix' => 'config', 'name' => 'pageHelp', 'type' => 'check', 'values' => '', 'value' => $allConfig["pageHelp"]],
    ];

    $viewData['email'] = [
      ['prefix' => 'config', 'name' => 'emailNotifications', 'type' => 'check', 'values' => '', 'value' => $allConfig["emailNotifications"]],
      ['prefix' => 'config', 'name' => 'mailFrom', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["mailFrom"], 'maxlength' => '150'],
      ['prefix' => 'config', 'name' => 'mailReply', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["mailReply"], 'maxlength' => '150'],
      ['prefix' => 'config', 'name' => 'mailSMTP', 'type' => 'check', 'values' => '', 'value' => $allConfig["mailSMTP"]],
      ['prefix' => 'config', 'name' => 'mailSMTPhost', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["mailSMTPhost"], 'maxlength' => '80'],
      ['prefix' => 'config', 'name' => 'mailSMTPport', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["mailSMTPport"], 'maxlength' => '8'],
      ['prefix' => 'config', 'name' => 'mailSMTPusername', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["mailSMTPusername"], 'maxlength' => '50'],
      ['prefix' => 'config', 'name' => 'mailSMTPpassword', 'type' => 'password', 'value' => $allConfig["mailSMTPpassword"], 'maxlength' => '50'],
      ['prefix' => 'config', 'name' => 'mailSMTPSSL', 'type' => 'check', 'values' => '', 'value' => $allConfig["mailSMTPSSL"]],
      ['prefix' => 'config', 'name' => 'mailSMTPAnonymous', 'type' => 'check', 'values' => '', 'value' => $allConfig["mailSMTPAnonymous"]],
    ];

    $viewData['footer'] = [
      ['prefix' => 'config', 'name' => 'footerCopyright', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["footerCopyright"], 'maxlength' => '160'],
      ['prefix' => 'config', 'name' => 'footerCopyrightUrl', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["footerCopyrightUrl"], 'maxlength' => '160'],
      ['prefix' => 'config', 'name' => 'footerSocialLinks', 'type' => 'textarea', 'value' => $allConfig["footerSocialLinks"], 'rows' => 5, 'placeholder' => ""],
    ];

    $viewData['homepage'] = [
      ['prefix' => 'config', 'name' => 'defaultHomepage', 'type' => 'radio', 'values' => ['home', 'calendarview'], 'value' => $allConfig["defaultHomepage"]],
      ['prefix' => 'config', 'name' => 'homepage', 'type' => 'radio', 'values' => ['home', 'calendarview', 'messages'], 'value' => $allConfig["homepage"]],
      ['prefix' => 'config', 'name' => 'welcomeText', 'type' => 'ckeditor', 'value' => $allConfig["welcomeText"], 'rows' => '10', 'placeholder' => ''],
    ];

    $LIC->load();
    $viewData['licStatus']        = $LIC->status();
    $viewData['licDetails']       = $LIC->show($LIC->details, true);
    $viewData['licKey']           = $LIC->readKey();
    $viewData['license']          = [
      ['prefix' => 'config', 'name' => 'licKey', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['licKey'], 'maxlength' => '32'],
      ['prefix' => 'config', 'name' => 'licExpiryWarning', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig['licExpiryWarning'], 'maxlength' => '3'],
    ];
    $viewData['domainRegistered'] = $LIC->domainRegistered();

    $viewData['login'] = [
      ['prefix' => 'config', 'name' => 'pwdStrength', 'type' => 'radio', 'values' => ['low', 'medium', 'high'], 'value' => $allConfig["pwdStrength"]],
      ['prefix' => 'config', 'name' => 'badLogins', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["badLogins"], 'maxlength' => '2'],
      ['prefix' => 'config', 'name' => 'gracePeriod', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["gracePeriod"], 'maxlength' => '3'],
      ['prefix' => 'config', 'name' => 'cookieLifetime', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["cookieLifetime"], 'maxlength' => '6'],
      ['prefix' => 'config', 'name' => 'disableTfa', 'type' => 'switch', 'values' => '', 'value' => $allConfig["disableTfa"]],
      ['prefix' => 'config', 'name' => 'forceTfa', 'type' => 'switch', 'values' => '', 'value' => $allConfig["forceTfa"]],
    ];

    $viewData['registration'] = [
      ['prefix' => 'config', 'name' => 'allowRegistration', 'type' => 'check', 'values' => '', 'value' => $allConfig["allowRegistration"]],
      ['prefix' => 'config', 'name' => 'emailConfirmation', 'type' => 'check', 'values' => '', 'value' => $allConfig["emailConfirmation"]],
      ['prefix' => 'config', 'name' => 'adminApproval', 'type' => 'check', 'values' => '', 'value' => $allConfig["adminApproval"]],
    ];

    foreach ($this->timezones as $tz) {
      $viewData['timezoneList'][] = ['val' => $tz, 'name' => $tz, 'selected' => ($allConfig['timeZone'] == $tz)];
    }
    $viewData['system'] = [
      ['prefix' => 'config', 'name' => 'cookieConsent', 'type' => 'check', 'values' => '', 'value' => $allConfig["cookieConsent"]],
      ['prefix' => 'config', 'name' => 'cookieConsentCDN', 'type' => 'check', 'values' => '', 'value' => $allConfig["cookieConsentCDN"]],
      ['prefix' => 'config', 'name' => 'faCDN', 'type' => 'check', 'values' => '', 'value' => $allConfig["faCDN"]],
      ['prefix' => 'config', 'name' => 'jQueryCDN', 'type' => 'check', 'values' => '', 'value' => $allConfig["jQueryCDN"]],
      ['prefix' => 'config', 'name' => 'timeZone', 'type' => 'list', 'values' => $viewData['timezoneList']],
      ['prefix' => 'config', 'name' => 'googleAnalytics', 'type' => 'check', 'values' => '', 'value' => $allConfig["googleAnalytics"]],
      ['prefix' => 'config', 'name' => 'googleAnalyticsID', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["googleAnalyticsID"], 'maxlength' => '16'],
      ['prefix' => 'config', 'name' => 'matomoAnalytics', 'type' => 'check', 'values' => '', 'value' => $allConfig["matomoAnalytics"]],
      ['prefix' => 'config', 'name' => 'matomoUrl', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["matomoUrl"], 'maxlength' => '160'],
      ['prefix' => 'config', 'name' => 'matomoSiteId', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["matomoSiteId"], 'maxlength' => '160'],
      ['prefix' => 'config', 'name' => 'noIndex', 'type' => 'check', 'values' => '', 'value' => $allConfig["noIndex"]],
      ['prefix' => 'config', 'name' => 'noCaching', 'type' => 'check', 'values' => '', 'value' => $allConfig["noCaching"]],
      ['prefix' => 'config', 'name' => 'versionCompare', 'type' => 'check', 'values' => '', 'value' => $allConfig["versionCompare"]],
      ['prefix' => 'config', 'name' => 'underMaintenance', 'type' => 'check', 'values' => '', 'value' => $allConfig["underMaintenance"]],
    ];

    foreach ($this->appJqueryUIThemes as $jqueryUITheme) {
      $viewData['jqueryUIThemeList'][] = ['val' => $jqueryUITheme, 'name' => ucwords($jqueryUITheme), 'selected' => ($allConfig['jqtheme'] == $jqueryUITheme)];
    }
    $viewData['fonts'][] = ['val' => 'default', 'name' => 'Default', 'selected' => ($allConfig['font'] == 'default')];
    $viewData['fonts'][] = ['val' => 'lato', 'name' => 'Lato', 'selected' => ($allConfig['font'] == 'lato')];
    $viewData['fonts'][] = ['val' => 'montserrat', 'name' => 'Montserrat', 'selected' => ($allConfig['font'] == 'montserrat')];
    $viewData['fonts'][] = ['val' => 'opensans', 'name' => 'Open Sans', 'selected' => ($allConfig['font'] == 'opensans')];
    $viewData['fonts'][] = ['val' => 'poppins', 'name' => 'Poppins', 'selected' => ($allConfig['font'] == 'poppins')];
    $viewData['fonts'][] = ['val' => 'roboto', 'name' => 'Roboto', 'selected' => ($allConfig['font'] == 'roboto')];

    $viewData['theme'] = [
      ['prefix' => 'config', 'name' => 'defaultMenu', 'type' => 'radio', 'values' => ['navbar', 'sidebar'], 'value' => $allConfig["defaultMenu"]],
      ['prefix' => 'config', 'name' => 'jqtheme', 'type' => 'list', 'values' => $viewData['jqueryUIThemeList']],
      ['prefix' => 'config', 'name' => 'jqthemeSample', 'type' => 'date', 'value' => ''],
      ['prefix' => 'config', 'name' => 'font', 'type' => 'list', 'values' => $viewData['fonts']],
    ];

    $viewData['user'] = [
      ['prefix' => 'config', 'name' => 'userCustom1', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["userCustom1"], 'maxlength' => '50'],
      ['prefix' => 'config', 'name' => 'userCustom2', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["userCustom2"], 'maxlength' => '50'],
      ['prefix' => 'config', 'name' => 'userCustom3', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["userCustom3"], 'maxlength' => '50'],
      ['prefix' => 'config', 'name' => 'userCustom4', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["userCustom4"], 'maxlength' => '50'],
      ['prefix' => 'config', 'name' => 'userCustom5', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig["userCustom5"], 'maxlength' => '50'],
    ];

    $viewData['gdpr'] = [
      ['prefix' => 'config', 'name' => 'gdprPolicyPage', 'type' => 'check', 'values' => '', 'value' => $allConfig["gdprPolicyPage"]],
      ['prefix' => 'config', 'name' => 'gdprOrganization', 'type' => 'text', 'placeholder' => 'ACME Inc.', 'value' => strip_tags($allConfig["gdprOrganization"]), 'maxlength' => '160'],
      ['prefix' => 'config', 'name' => 'gdprController', 'type' => 'textarea', 'value' => $allConfig["gdprController"], 'rows' => 5, 'placeholder' => "ACME Inc.\nStreet\nTown\nCountry\nEmail"],
      ['prefix' => 'config', 'name' => 'gdprOfficer', 'type' => 'textarea', 'value' => $allConfig["gdprOfficer"], 'rows' => 5, 'placeholder' => "John Doe\nPhone\nEmail"],
    ];

    $viewData['gdprPlatforms'] = [
      ['key' => 'Facebook', 'icon' => 'fab fa-facebook', 'label' => 'Facebook', 'checked' => $this->C->read('gdprFacebook')],
      ['key' => 'GoogleAnalytics', 'icon' => 'fab fa-google', 'label' => 'Google Analytics', 'checked' => $this->C->read('gdprGoogleAnalytics')],
      ['key' => 'Instagram', 'icon' => 'fab fa-instagram', 'label' => 'Instagram', 'checked' => $this->C->read('gdprInstagram')],
      ['key' => 'Linkedin', 'icon' => 'fab fa-linkedin', 'label' => 'LinkedIn', 'checked' => $this->C->read('gdprLinkedin')],
      ['key' => 'Paypal', 'icon' => 'fab fa-paypal', 'label' => 'Paypal', 'checked' => $this->C->read('gdprPaypal')],
      ['key' => 'Pinterest', 'icon' => 'fab fa-pinterest', 'label' => 'Pinterest', 'checked' => $this->C->read('gdprPinterest')],
      ['key' => 'Slideshare', 'icon' => 'fab fa-slideshare', 'label' => 'Slideshare', 'checked' => $this->C->read('gdprSlideshare')],
      ['key' => 'Tumblr', 'icon' => 'fab fa-tumblr', 'label' => 'Tumblr', 'checked' => $this->C->read('gdprTumblr')],
      ['key' => 'Twitter', 'icon' => 'fab fa-twitter', 'label' => 'X (Twitter)', 'checked' => $this->C->read('gdprTwitter')],
      ['key' => 'Xing', 'icon' => 'fab fa-xing', 'label' => 'Xing', 'checked' => $this->C->read('gdprXing')],
      ['key' => 'Youtube', 'icon' => 'fab fa-youtube', 'label' => 'Youtube', 'checked' => $this->C->read('gdprYoutube')],
    ];

    $this->render('config', $viewData);
  }

  private function handleApply($LIC, &$showAlert, &$alertData) {
    $newConfig                     = [];
    $newConfig["appURL"]           = filter_var($_POST['txt_appURL'], FILTER_VALIDATE_URL) ? $_POST['txt_appURL'] : "#";
    $newConfig["appTitle"]         = sanitize($_POST['txt_appTitle']);
    $newConfig["appDescription"]   = sanitize($_POST['txt_appDescription']);
    $newConfig["appKeywords"]      = sanitize($_POST['txt_appKeywords']);
    $newConfig["defaultLanguage"]  = $_POST['sel_defaultLanguage'] ?? "english";
    $newConfig["logLanguage"]      = $_POST['sel_logLanguage'] ?? "english";
    $newConfig["permissionScheme"] = $_POST['sel_permissionScheme'] ?? "Default";
    $newConfig["showAlerts"]       = $_POST['opt_showAlerts'] ?? "all";

    $checkboxes = [
      'chk_alertAutocloseDanger'  => 'alertAutocloseDanger',
      'chk_alertAutocloseSuccess' => 'alertAutocloseSuccess',
      'chk_alertAutocloseWarning' => 'alertAutocloseWarning',
    ];
    foreach ($checkboxes as $postKey => $settingKey) {
      $newConfig[$settingKey] = (isset($_POST[$postKey]) && $_POST[$postKey]) ? "1" : "0";
    }

    $newConfig["alertAutocloseDelay"] = sanitize($_POST['txt_alertAutocloseDelay']);
    $newConfig["activateMessages"]    = (isset($_POST['chk_activateMessages']) && $_POST['chk_activateMessages']) ? "1" : "0";
    $newConfig["pageHelp"]            = (isset($_POST['chk_pageHelp']) && $_POST['chk_pageHelp']) ? "1" : "0";
    $newConfig["userManual"]          = strlen($_POST['txt_userManual']) ? urlencode(rtrim($_POST['txt_userManual'], '/') . '/') : '';

    $newConfig["emailNotifications"]       = (isset($_POST['chk_emailNotifications']) && $_POST['chk_emailNotifications']) ? "1" : "0";
    $newConfig["emailNoPastNotifications"] = (isset($_POST['chk_emailNoPastNotifications']) && $_POST['chk_emailNoPastNotifications']) ? "1" : "0";
    $newConfig["mailFrom"]                 = sanitize($_POST['txt_mailFrom']);
    $newConfig["mailReply"]                = validEmail($_POST['txt_mailReply']) ? sanitize($_POST['txt_mailReply']) : "noreply@teamcalneo.com";
    $newConfig["mailSMTP"]                 = (isset($_POST['chk_mailSMTP']) && $_POST['chk_mailSMTP']) ? "1" : "0";
    $newConfig["mailSMTPhost"]             = sanitize($_POST['txt_mailSMTPhost']);
    $newConfig["mailSMTPport"]             = intval($_POST['txt_mailSMTPport']);
    $newConfig["mailSMTPusername"]         = sanitize($_POST['txt_mailSMTPusername']);
    $newConfig["mailSMTPpassword"]         = sanitize($_POST['txt_mailSMTPpassword']);
    $newConfig["mailSMTPSSL"]              = (isset($_POST['chk_mailSMTPSSL']) && $_POST['chk_mailSMTPSSL']) ? "1" : "0";
    $newConfig["mailSMTPAnonymous"]        = (isset($_POST['chk_mailSMTPAnonymous']) && $_POST['chk_mailSMTPAnonymous']) ? "1" : "0";

    $newConfig["footerCopyright"]    = sanitize($_POST['txt_footerCopyright']);
    $newConfig["footerCopyrightUrl"] = (strlen($_POST['txt_footerCopyrightUrl']) && filter_var($_POST['txt_footerCopyrightUrl'], FILTER_VALIDATE_URL)) ? sanitize($_POST['txt_footerCopyrightUrl']) : "";
    $newConfig["footerSocialLinks"]  = sanitize($_POST['txt_footerSocialLinks']);

    if ($_POST['opt_homepage'])
      $newConfig["homepage"] = $_POST['opt_homepage'];
    if ($_POST['opt_defaultHomepage'])
      $newConfig["defaultHomepage"] = $_POST['opt_defaultHomepage'];
    $newConfig["welcomeText"] = sanitizeWithAllowedTags($_POST['txt_welcomeText']);

    $LIC->saveKey(trim(sanitize($_POST['txt_licKey'])));
    $newConfig["licExpiryWarning"] = strlen($_POST['txt_licExpiryWarning']) ? intval(sanitize($_POST['txt_licExpiryWarning'])) : 0;

    if ($_POST['opt_pwdStrength'])
      $newConfig["pwdStrength"] = $_POST['opt_pwdStrength'];
    $newConfig["badLogins"]      = intval(sanitize($_POST['txt_badLogins']));
    $newConfig["gracePeriod"]    = intval(sanitize($_POST['txt_gracePeriod']));
    $newConfig["cookieLifetime"] = intval(sanitize($_POST['txt_cookieLifetime']));

    if (isset($_POST['swi_disableTfa']) && $_POST['swi_disableTfa']) {
      $newConfig["disableTfa"] = "1";
      $newConfig["forceTfa"]   = "0";
      $this->UO->deleteOption("secret");
    }
    else {
      $newConfig["disableTfa"] = "0";
      $newConfig["forceTfa"]   = (isset($_POST['swi_forceTfa']) && $_POST['swi_forceTfa']) ? "1" : "0";
    }

    $newConfig["allowRegistration"] = (isset($_POST['chk_allowRegistration']) && $_POST['chk_allowRegistration']) ? "1" : "0";
    $newConfig["emailConfirmation"] = (isset($_POST['chk_emailConfirmation']) && $_POST['chk_emailConfirmation']) ? "1" : "0";
    $newConfig["adminApproval"]     = (isset($_POST['chk_adminApproval']) && $_POST['chk_adminApproval']) ? "1" : "0";

    $newConfig["cookieConsent"]    = (isset($_POST['chk_cookieConsent']) && $_POST['chk_cookieConsent']) ? "1" : "0";
    $newConfig["cookieConsentCDN"] = (isset($_POST['chk_cookieConsentCDN']) && $_POST['chk_cookieConsentCDN']) ? "1" : "0";
    $newConfig["faCDN"]            = (isset($_POST['chk_faCDN']) && $_POST['chk_faCDN']) ? "1" : "0";
    $newConfig["jQueryCDN"]        = (isset($_POST['chk_jQueryCDN']) && $_POST['chk_jQueryCDN']) ? "1" : "0";
    $newConfig["timeZone"]         = $_POST['sel_timeZone'] ?? "UTC";

    if (isset($_POST['chk_googleAnalytics']) && $_POST['chk_googleAnalytics']) {
      if (preg_match('/^G-[A-Z0-9]{10,20}$/', $_POST['txt_googleAnalyticsID'])) {
        $newConfig["googleAnalytics"]   = "1";
        $newConfig["googleAnalyticsID"] = $_POST['txt_googleAnalyticsID'];
      }
      else {
        $newConfig["googleAnalytics"]   = "0";
        $newConfig["googleAnalyticsID"] = $_POST['txt_googleAnalyticsID'];
      }
    }
    else {
      $newConfig["googleAnalytics"]   = "0";
      $newConfig["googleAnalyticsID"] = "";
    }

    if (isset($_POST['chk_matomoAnalytics']) && $_POST['chk_matomoAnalytics']) {
      $newConfig["matomoAnalytics"] = "1";
    }
    else {
      $newConfig["matomoAnalytics"] = "0";
    }
    $newConfig["matomoUrl"]    = $_POST['txt_matomoUrl'];
    $newConfig["matomoSiteId"] = $_POST['txt_matomoSiteId'];

    $newConfig["noIndex"]          = (isset($_POST['chk_noIndex']) && $_POST['chk_noIndex']) ? "1" : "0";
    $newConfig["noCaching"]        = (isset($_POST['chk_noCaching']) && $_POST['chk_noCaching']) ? "1" : "0";
    $newConfig["versionCompare"]   = (isset($_POST['chk_versionCompare']) && $_POST['chk_versionCompare']) ? "1" : "0";
    $newConfig["underMaintenance"] = (isset($_POST['chk_underMaintenance']) && $_POST['chk_underMaintenance']) ? "1" : "0";

    $newConfig["defaultMenu"] = $_POST['opt_defaultMenu'] ?? 'navbar';
    $newConfig["jqtheme"]     = $_POST['sel_jqtheme'] ?? "smoothness";
    $newConfig["font"]        = $_POST['sel_font'] ?? "default";

    $newConfig["userCustom1"] = sanitize($_POST['txt_userCustom1'] ?? '');
    $newConfig["userCustom2"] = sanitize($_POST['txt_userCustom2'] ?? '');
    $newConfig["userCustom3"] = sanitize($_POST['txt_userCustom3'] ?? '');
    $newConfig["userCustom4"] = sanitize($_POST['txt_userCustom4'] ?? '');
    $newConfig["userCustom5"] = sanitize($_POST['txt_userCustom5'] ?? '');

    $newConfig["gdprPolicyPage"]   = (isset($_POST['chk_gdprPolicyPage']) && $_POST['chk_gdprPolicyPage']) ? "1" : "0";
    $newConfig["gdprOrganization"] = sanitize($_POST['txt_gdprOrganization'] ?? '');
    $newConfig["gdprController"]   = sanitizeWithAllowedTags($_POST['txt_gdprController'] ?? '');
    $newConfig["gdprOfficer"]      = sanitizeWithAllowedTags($_POST['txt_gdprOfficer'] ?? '');

    $gdprChecks = ['gdprFacebook', 'gdprGoogleAnalytics', 'gdprInstagram', 'gdprLinkedin', 'gdprPaypal', 'gdprPinterest', 'gdprSlideshare', 'gdprTumblr', 'gdprTwitter', 'gdprXing', 'gdprYoutube'];
    foreach ($gdprChecks as $check) {
      $newConfig[$check] = (isset($_POST['chk_' . $check]) && $_POST['chk_' . $check]) ? "1" : "0";
    }

    $this->C->saveBatch($newConfig);
    $this->_instances['allConfig'] = $this->C->readAll();
    $this->LOG->logEvent("logConfig", $this->UL->username, "log_config");

    $showAlert            = true;
    $alertData['type']    = 'success';
    $alertData['title']   = $this->LANG['alert_success_title'];
    $alertData['subject'] = $this->LANG['config_title'];
    $alertData['text']    = $this->LANG['config_alert_edit_success'];
    $alertData['help']    = '';

    if (isset($_SESSION)) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
  }

  private function handleLicenseActivation($LIC, &$showAlert, &$alertData) {
    $response = $LIC->activate();
    if ($response->result == "success") {
      $showAlert            = true;
      $alertData['type']    = 'success';
      $alertData['title']   = $this->LANG['alert_success_title'];
      $alertData['subject'] = $this->LANG['alert_license_subject'];
      $alertData['text']    = $this->LANG['lic_alert_activation_success'];
      $alertData['help']    = '';
      if (isset($_SESSION))
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    else {
      $showAlert            = true;
      $alertData['type']    = 'danger';
      $alertData['title']   = $this->LANG['alert_danger_title'];
      $alertData['subject'] = $this->LANG['alert_license_subject'];
      $alertData['text']    = $this->LANG['lic_alert_activation_fail'] . " " . $response->message;
      $alertData['help']    = '';
    }
  }

  private function handleLicenseRegistration($LIC, &$showAlert, &$alertData) {
    $response = $LIC->activate();
    if ($response->result == "success") {
      $showAlert            = true;
      $alertData['type']    = 'success';
      $alertData['title']   = $this->LANG['alert_success_title'];
      $alertData['subject'] = $this->LANG['alert_license_subject'];
      $alertData['text']    = $this->LANG['lic_alert_registration_success'];
      $alertData['help']    = '';
      if (isset($_SESSION))
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    else {
      $showAlert            = true;
      $alertData['type']    = 'danger';
      $alertData['title']   = $this->LANG['alert_danger_title'];
      $alertData['subject'] = $this->LANG['alert_license_subject'];
      $alertData['text']    = $this->LANG['lic_alert_registration_fail'] . "<br /><i>" . $response->message . "</i>";
      $alertData['help']    = '';
    }
  }

  private function handleLicenseDeregistration($LIC, &$showAlert, &$alertData) {
    $response = $LIC->deactivate();
    if (is_object($response) && $response->result == "success") {
      $showAlert            = true;
      $alertData['type']    = 'success';
      $alertData['title']   = $this->LANG['alert_success_title'];
      $alertData['subject'] = $this->LANG['alert_license_subject'];
      $alertData['text']    = $this->LANG['lic_alert_deregistration_success'];
      $alertData['help']    = '';
      if (isset($_SESSION))
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    else {
      $showAlert            = true;
      $alertData['type']    = 'danger';
      $alertData['title']   = $this->LANG['alert_danger_title'];
      $alertData['subject'] = $this->LANG['alert_license_subject'];
      $alertData['text']    = $this->LANG['lic_alert_deregistration_fail'] . "<br /><i>" . (is_object($response) ? $response->message : $response) . "</i>";
      $alertData['help']    = '';
    }
  }

  private function handleClearCache(&$showAlert, &$alertData) {
    $cache = $this->container->get('Cache');
    if ($cache->flush()) {
      $showAlert            = true;
      $alertData['type']    = 'success';
      $alertData['title']   = $this->LANG['alert_success_title'];
      $alertData['subject'] = $this->LANG['config_clearCache'];
      $alertData['text']    = $this->LANG['config_alert_cache_cleared'];
      $alertData['help']    = '';
    }
    else {
      $showAlert            = true;
      $alertData['type']    = 'danger';
      $alertData['title']   = $this->LANG['alert_danger_title'];
      $alertData['subject'] = $this->LANG['config_clearCache'];
      $alertData['text']    = $this->LANG['config_alert_cache_failed'];
      $alertData['help']    = '';
    }
  }

}
