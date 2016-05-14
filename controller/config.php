<?php
/**
 * config.php
 * 
 * Framework config page controller
 *
 * @category TeamCal Neo 
 * @version 0.5.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

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
if (!empty($_POST))
{
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
    
   if (!$inputError)
   {
      // ,-------,
      // | Apply |
      // '-------'
      if (isset($_POST['btn_confApply']))
      {
         //
         // General
         //
         if (filter_var($_POST['txt_appURL'], FILTER_VALIDATE_URL)) $C->save("appURL",$_POST['txt_appURL']); else $C->save("appURL", "#");  
         $C->save("appTitle", sanitize($_POST['txt_appTitle']));
         $C->save("appDescription", sanitize($_POST['txt_appDescription']));
         $C->save("appFooterCpy", sanitize($_POST['txt_appFooterCpy']));
         if ($_POST['sel_defaultLanguage']) $C->save("defaultLanguage", $_POST['sel_defaultLanguage']); else $C->save("defaultLanguage", "english");
         if ($_POST['sel_logLanguage']) $C->save("logLanguage", $_POST['sel_logLanguage']); else $C->save("logLanguage", "english");
         if ($_POST['sel_permissionScheme']) $C->save("permissionScheme", $_POST['sel_permissionScheme']); else $C->save("permissionScheme", "Default");
         if ($_POST['opt_showAlerts']) $C->save("showAlerts", $_POST['opt_showAlerts']);
         if (isset($_POST['chk_activateMessages']) && $_POST['chk_activateMessages']) $C->save("activateMessages", "1"); else $C->save("activateMessages", "0");
         if (isset($_POST['chk_showBanner']) && $_POST['chk_showBanner']) $C->save("showBanner", "1"); else $C->save("showBanner", "0");
         if ( isset($_POST['chk_showSize']) && $_POST['chk_showSize'] ) $C->save("showSize","1"); else $C->save("showSize","0");
         if (strlen($_POST['txt_userManual']))
         {
            $myUrl = rtrim($_POST['txt_userManual'], '/') . '/'; // Ensure trailing slash
            $C->save("userManual", urlencode($myUrl));
         }
         else
         {
            $C->save("userManual", '');
         }
          
         //
         // Email
         //
         if ( isset($_POST['chk_emailNotifications']) && $_POST['chk_emailNotifications'] ) $C->save("emailNotifications","1"); else $C->save("emailNotifications","0");
         if ( isset($_POST['chk_emailNoPastNotifications']) && $_POST['chk_emailNoPastNotifications'] ) $C->save("emailNoPastNotifications","1"); else $C->save("emailNoPastNotifications","0");
         $C->save("mailFrom",sanitize($_POST['txt_mailFrom']));
         if (validEmail($_POST['txt_mailReply'])) $C->save("mailReply",$_POST['txt_mailReply']); else $C->save("mailReply","noreply@teamcalneo.com");
         if ( isset($_POST['chk_mailSMTP']) && $_POST['chk_mailSMTP'] ) $C->save("mailSMTP","1"); else $C->save("mailSMTP","0");
         $C->save("mailSMTPhost", sanitize($_POST['txt_mailSMTPhost']));
         $C->save("mailSMTPport", intval($_POST['txt_mailSMTPport']));
         $C->save("mailSMTPusername", sanitize($_POST['txt_mailSMTPusername']));
         $C->save("mailSMTPpassword", sanitize($_POST['txt_mailSMTPpassword']));
         if ( isset($_POST['chk_mailSMTPSSL']) && $_POST['chk_mailSMTPSSL'] ) $C->save("mailSMTPSSL","1"); else $C->save("mailSMTPSSL","0");
         
         //
         // Homepage
         //
         if ($_POST['opt_homepage']) $C->save("homepage", $_POST['opt_homepage']);
         $C->save("welcomeText", $_POST['txt_welcomeText']);
          
         //
         // Login
         //
         if ($_POST['opt_pwdStrength']) $C->save("pwdStrength",$_POST['opt_pwdStrength']);
         $C->save("badLogins",intval($_POST['txt_badLogins']));
         $C->save("gracePeriod",intval($_POST['txt_gracePeriod']));
         $C->save("cookieLifetime",intval($_POST['txt_cookieLifetime']));
          
         //
         // Registration
         //
         if ( isset($_POST['chk_allowRegistration']) && $_POST['chk_allowRegistration'] ) $C->save("allowRegistration","1"); else $C->save("allowRegistration","0");
         if ( isset($_POST['chk_emailConfirmation']) && $_POST['chk_emailConfirmation'] ) $C->save("emailConfirmation","1"); else $C->save("emailConfirmation","0");
         if ( isset($_POST['chk_adminApproval']) && $_POST['chk_adminApproval'] ) $C->save("adminApproval","1"); else $C->save("adminApproval","0");
         
         //
         // System
         //
         if (isset($_POST['chk_faCDN']) && $_POST['chk_faCDN']) $C->save("faCDN", "1"); else $C->save("faCDN", "0");
         if (isset($_POST['chk_jQueryCDN']) && $_POST['chk_jQueryCDN']) $C->save("jQueryCDN", "1"); else $C->save("jQueryCDN", "0");
         if ($_POST['sel_jqtheme']) $C->save("jqtheme", $_POST['sel_jqtheme']); else $C->save("jqtheme", "smoothness");
         if ($_POST['sel_timeZone']) $C->save("timeZone",$_POST['sel_timeZone']); else $C->save("timeZone","UTC");
         if ( isset($_POST['chk_googleAnalytics']) && $_POST['chk_googleAnalytics'] ) 
         {
            if (preg_match('/\bUA-\d{4,10}-\d{1,4}\b/', $_POST['txt_googleAnalyticsID'])) 
            {
               $C->save("googleAnalytics","1");
               $C->save("googleAnalyticsID",$_POST['txt_googleAnalyticsID']);
            }
         }
         else 
         {
            $C->save("googleAnalytics","0");
            $C->save("googleAnalyticsID","");
         }
         if ( isset($_POST['chk_underMaintenance']) && $_POST['chk_underMaintenance'] ) $C->save("underMaintenance","1"); else $C->save("underMaintenance","0");
      
         //
         // Theme
         //
         if ($_POST['sel_theme']) $C->save("theme", $_POST['sel_theme']); else $C->save("theme", "bootstrap");
         if (isset($_POST['chk_menuBarInverse']) && $_POST['chk_menuBarInverse']) $C->save("menuBarInverse", "1"); else $C->save("menuBarInverse", "0");
         if (isset($_POST['chk_allowUserTheme']) && $_POST['chk_allowUserTheme']) $C->save("allowUserTheme", "1"); else $C->save("allowUserTheme", "0");
           
         //
         // User
         //
         $C->save("userCustom1",sanitize($_POST['txt_userCustom1']));
         $C->save("userCustom2",sanitize($_POST['txt_userCustom2']));
         $C->save("userCustom3",sanitize($_POST['txt_userCustom3']));
         $C->save("userCustom4",sanitize($_POST['txt_userCustom4']));
         $C->save("userCustom5",sanitize($_POST['txt_userCustom5']));
          
         //
         // Log this event
         //
         $LOG->log("logConfig", $UL->username, "log_config");
         header("Location: index.php?action=config");
         die();
      }
   }
   else
   {
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
foreach ($appLanguages as $appLang)
{
   $viewData['languageList'][] = array ('val' => $appLang, 'name' => proper($appLang), 'selected' => ($C->read("defaultLanguage") == $appLang)?true:false );
}

foreach ($logLanguages as $logLang)
{
   $viewData['logLanguageList'][] = array ('val' => $logLang, 'name' => proper($logLang), 'selected' => ($C->read("logLanguage") == $logLang)?true:false );
}

$schemes = $P->getSchemes();

foreach ($schemes as $scheme)
{
   $viewData['schemeList'][] = array ('val' => $scheme, 'name' => $scheme, 'selected' => ($C->read("permissionScheme") == $scheme)?true:false );
}

$viewData['general'] = array (
   array ( 'prefix' => 'config', 'name' => 'appURL', 'type' => 'text', 'value' => strip_tags($C->read("appURL")), 'maxlength' => '160' ),
   array ( 'prefix' => 'config', 'name' => 'appTitle', 'type' => 'text', 'value' => strip_tags($C->read("appTitle")), 'maxlength' => '160' ),
   array ( 'prefix' => 'config', 'name' => 'appDescription', 'type' => 'text', 'value' => strip_tags($C->read("appDescription")), 'maxlength' => '160' ),
   array ( 'prefix' => 'config', 'name' => 'defaultLanguage', 'type' => 'list', 'values' => $viewData['languageList'] ),
   array ( 'prefix' => 'config', 'name' => 'logLanguage', 'type' => 'list', 'values' => $viewData['logLanguageList'] ),
   array ( 'prefix' => 'config', 'name' => 'showAlerts', 'type' => 'radio', 'values' => array ('all', 'warnings', 'none'), 'value' => $C->read("showAlerts") ),
   array ( 'prefix' => 'config', 'name' => 'activateMessages', 'type' => 'check', 'values' => '', 'value' => $C->read("activateMessages") ),
   array ( 'prefix' => 'config', 'name' => 'permissionScheme', 'type' => 'list', 'values' => $viewData['schemeList'] ),
   array ( 'prefix' => 'config', 'name' => 'userManual', 'type' => 'text', 'value' => urldecode($C->read("userManual")), 'maxlength' => '160' ),
   array ( 'prefix' => 'config', 'name' => 'showBanner', 'type' => 'check', 'values' => '', 'value' => $C->read("showBanner") ),
   array ( 'prefix' => 'config', 'name' => 'showSize', 'type' => 'check', 'values' => '', 'value' => $C->read("showSize") ),
   array ( 'prefix' => 'config', 'name' => 'appFooterCpy', 'type' => 'text', 'value' => $C->read("appFooterCpy"), 'maxlength' => '160' ),
);

$viewData['email'] = array (
   array ( 'prefix' => 'config', 'name' => 'emailNotifications', 'type' => 'check', 'values' => '', 'value' => $C->read("emailNotifications") ),
   array ( 'prefix' => 'config', 'name' => 'mailFrom', 'type' => 'text', 'value' => $C->read("mailFrom"), 'maxlength' => '50' ),
   array ( 'prefix' => 'config', 'name' => 'mailReply', 'type' => 'text', 'value' => $C->read("mailReply"), 'maxlength' => '50' ),
   array ( 'prefix' => 'config', 'name' => 'mailSMTP', 'type' => 'check', 'values' => '', 'value' => $C->read("mailSMTP") ),
   array ( 'prefix' => 'config', 'name' => 'mailSMTPhost', 'type' => 'text', 'value' => $C->read("mailSMTPhost"), 'maxlength' => '80' ),
   array ( 'prefix' => 'config', 'name' => 'mailSMTPport', 'type' => 'text', 'value' => $C->read("mailSMTPport"), 'maxlength' => '8' ),
   array ( 'prefix' => 'config', 'name' => 'mailSMTPusername', 'type' => 'text', 'value' => $C->read("mailSMTPusername"), 'maxlength' => '50' ),
   array ( 'prefix' => 'config', 'name' => 'mailSMTPpassword', 'type' => 'password', 'value' => $C->read("mailSMTPpassword"), 'maxlength' => '50' ),
   array ( 'prefix' => 'config', 'name' => 'mailSMTPSSL', 'type' => 'check', 'values' => '', 'value' => $C->read("mailSMTPSSL") ),
);

$viewData['homepage'] = array (
   array ( 'prefix' => 'config', 'name' => 'homepage', 'type' => 'radio', 'values' => array ('home', 'messages'), 'value' => $C->read("homepage") ),
   array ( 'prefix' => 'config', 'name' => 'welcomeText', 'type' => 'ckeditor', 'value' => $C->read("welcomeText"), 'rows' => '10' ),
);

$viewData['login'] = array (
   array ( 'prefix' => 'config', 'name' => 'pwdStrength', 'type' => 'radio', 'values' => array ('low', 'medium', 'high'), 'value' => $C->read("pwdStrength") ),
   array ( 'prefix' => 'config', 'name' => 'badLogins', 'type' => 'text', 'value' => $C->read("badLogins"), 'maxlength' => '2' ),
   array ( 'prefix' => 'config', 'name' => 'gracePeriod', 'type' => 'text', 'value' => $C->read("gracePeriod"), 'maxlength' => '3' ),
   array ( 'prefix' => 'config', 'name' => 'cookieLifetime', 'type' => 'text', 'value' => $C->read("cookieLifetime"), 'maxlength' => '6' ),
);

$viewData['registration'] = array (
   array ( 'prefix' => 'config', 'name' => 'allowRegistration', 'type' => 'check', 'values' => '', 'value' => $C->read("allowRegistration") ),
   array ( 'prefix' => 'config', 'name' => 'emailConfirmation', 'type' => 'check', 'values' => '', 'value' => $C->read("emailConfirmation") ),
   array ( 'prefix' => 'config', 'name' => 'adminApproval', 'type' => 'check', 'values' => '', 'value' => $C->read("adminApproval") ),
);

foreach ($appJqueryUIThemes as $jqueryUITheme)
{
   $viewData['jqueryUIThemeList'][] = array ('val' => $jqueryUITheme, 'name' => proper($jqueryUITheme), 'selected' => ($C->read("jqtheme") == $jqueryUITheme)?true:false );
}
foreach ($timezones as $tz)
{
   $viewData['timezoneList'][] = array ('val' => $tz, 'name' => $tz, 'selected' => ($C->read("timeZone") == $tz)?true:false );
}
$viewData['system'] = array (
   array ( 'prefix' => 'config', 'name' => 'faCDN', 'type' => 'check', 'values' => '', 'value' => $C->read("faCDN") ),
   array ( 'prefix' => 'config', 'name' => 'jQueryCDN', 'type' => 'check', 'values' => '', 'value' => $C->read("jQueryCDN") ),
   array ( 'prefix' => 'config', 'name' => 'jqtheme', 'type' => 'list', 'values' => $viewData['jqueryUIThemeList'] ),
   array ( 'prefix' => 'config', 'name' => 'timeZone', 'type' => 'list', 'values' => $viewData['timezoneList'] ),
   array ( 'prefix' => 'config', 'name' => 'googleAnalytics', 'type' => 'check', 'values' => '', 'value' => $C->read("googleAnalytics") ),
   array ( 'prefix' => 'config', 'name' => 'googleAnalyticsID', 'type' => 'text', 'value' => $C->read("googleAnalyticsID"), 'maxlength' => '16' ),
   array ( 'prefix' => 'config', 'name' => 'underMaintenance', 'type' => 'check', 'values' => '', 'value' => $C->read("underMaintenance") ),
);

foreach ($appThemes as $appTheme)
{
   $viewData['themeList'][] = array ('val' => $appTheme, 'name' => proper($appTheme), 'selected' => ($C->read("theme") == $appTheme)?true:false );
}

$viewData['theme'] = array (
   array ( 'prefix' => 'config', 'name' => 'theme', 'type' => 'list', 'values' => $viewData['themeList'] ),
   array ( 'prefix' => 'config', 'name' => 'allowUserTheme', 'type' => 'check', 'values' => '', 'value' => $C->read("allowUserTheme") ),
   array ( 'prefix' => 'config', 'name' => 'menuBarInverse', 'type' => 'check', 'values' => '', 'value' => $C->read("menuBarInverse") ),
);

$viewData['user'] = array (
   array ( 'prefix' => 'config', 'name' => 'userCustom1', 'type' => 'text', 'value' => $C->read("userCustom1"), 'maxlength' => '50' ),
   array ( 'prefix' => 'config', 'name' => 'userCustom2', 'type' => 'text', 'value' => $C->read("userCustom2"), 'maxlength' => '50' ),
   array ( 'prefix' => 'config', 'name' => 'userCustom3', 'type' => 'text', 'value' => $C->read("userCustom3"), 'maxlength' => '50' ),
   array ( 'prefix' => 'config', 'name' => 'userCustom4', 'type' => 'text', 'value' => $C->read("userCustom4"), 'maxlength' => '50' ),
   array ( 'prefix' => 'config', 'name' => 'userCustom5', 'type' => 'text', 'value' => $C->read("userCustom5"), 'maxlength' => '50' ),
);

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
