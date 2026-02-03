<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: System Settings page
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['config_title'] = 'System Settings';

$LANG['config_tab_email'] = 'E-mail';
$LANG['config_tab_footer'] = 'Footer';
$LANG['config_tab_homepage'] = 'Homepage';
$LANG['config_tab_images'] = 'Images';
$LANG['config_tab_license'] = 'License';
$LANG['config_tab_login'] = 'Login';
$LANG['config_tab_registration'] = 'Registration';
$LANG['config_tab_system'] = 'System';
$LANG['config_tab_theme'] = 'Theme';
$LANG['config_tab_user'] = 'User';
$LANG['config_tab_gdpr'] = 'GDPR';

$LANG['config_activateMessages'] = 'Activate Message Center';
$LANG['config_activateMessages_comment'] = 'This option will activate the Message Center. User can use it to send announcements or e-mails to other
      users or groups. An entry in the Tools menu will be added.';
$LANG['config_adminApproval'] = 'Require Admin Approval';
$LANG['config_adminApproval_comment'] = 'The administrator will receive an e-mail about each user self-registration. He manually needs to confirm the account.';
$LANG['config_alert_edit_success'] = 'The configuration was updated. For some changes to take effect, you may need to refresh the page.';
$LANG['config_alert_failed'] = 'The configuration could not be updated. Please check your input.';
$LANG['config_alertAutocloseDanger'] = 'Close Error Alerts automatically';
$LANG['config_alertAutocloseDanger_comment'] = 'Select whether Error alerts shall be automatically closed after the amount of milliseconds specified below.';
$LANG['config_alertAutocloseDelay'] = 'Alert Autoclose Delay';
$LANG['config_alertAutocloseDelay_comment'] = 'Enter the amount of milliseconds after which alerts the selected alert types above shall be automatically closed (e.g. 4000 = 4 seconds).';
$LANG['config_alertAutocloseSuccess'] = 'Close Success Alerts automatically';
$LANG['config_alertAutocloseSuccess_comment'] = 'Select whether Success alerts shall be automatically closed after the amount of milliseconds specified below.';
$LANG['config_alertAutocloseWarning'] = 'Close Warning Alerts automatically';
$LANG['config_alertAutocloseWarning_comment'] = 'Select whether Warning alerts shall be automatically closed after the amount of milliseconds specified below.';
$LANG['config_allowRegistration'] = 'Allow User Self-Registration';
$LANG['config_allowRegistration_comment'] = 'Allow users to self-register their account. A menu entry will be available in the TeamCal Neo menu.';
$LANG['config_appDescription'] = 'HTML Description';
$LANG['config_appDescription_comment'] = 'Enter an application description here. It will be used in the HTML header for search engines.';
$LANG['config_appKeywords'] = 'HTML Keywords';
$LANG['config_appKeywords_comment'] = 'Enter a few keywords here. They will be used in the HTML header for search engines.';
$LANG['config_appTitle'] = 'Application Name';
$LANG['config_appTitle_comment'] = 'Enter an application title here. It is used at several locations, e.g. the HTML header, menu and other pages where the title is referenced.';
$LANG['config_appURL'] = 'Application URL';
$LANG['config_appURL_comment'] = 'Enter the full application URL here. It will be used in notification emails.';
$LANG['config_badLogins'] = 'Bad Logins';
$LANG['config_badLogins_comment'] = 'Number of bad login attempts that will cause the user status to be set to \'LOCKED\'. The user has to wait as long
 as the grace period specifies before he can login again. If you set this value to 0 the bad login feature is disabled.';
$LANG['config_cookieConsent'] = 'Cookie Consent';
$LANG['config_cookieConsent_comment'] = 'With this option, a cookie consent confirmation will pop up at the bottom of the screen. This is legally required in the EU. This feature requires Internet connectivity.';
$LANG['config_cookieConsentCDN'] = 'Cookie Consent CDN';
$LANG['config_cookieConsentCDN_comment'] = 'CDNs (Content Distributed Network) can offer a performance benefit by hosting popular web modules on servers spread
 across the globe. Cookie Consent is such a module. Pulling it from a CDN location also offers an advantage that if the visitor to your webpage has already
 downloaded a copy of it from the same CDN, it won\'t have to be re-downloaded.<br>Switch this option off if you are running the application in an environment with no Internet connectivity.';
$LANG['config_cookieLifetime'] = 'Cookie Lifetime';
$LANG['config_cookieLifetime_comment'] = 'Upon successful login a cookie is stored on the local hard drive of the user. This cookie has a certain
 lifetime after which it becomes invalid. A new login is necessary. This lifetime can be specified here in seconds (0-999999).';
$LANG['config_defaultHomepage'] = 'Default Homepage';
$LANG['config_defaultHomepage_comment'] = 'Select the default homepage. It is shown to anonymous users and when clicking the application icon
      in the top left. Caution, if you select "Calendar" here, "Public" should have sufficient permissions to view it.';
$LANG['config_defaultHomepage_home'] = 'Welcome Page';
$LANG['config_defaultHomepage_calendarview'] = 'Calendar';
$LANG['config_defaultLanguage'] = 'Default Language';
$LANG['config_defaultLanguage_comment'] = 'TeamCal Neo is distributed in English, German, Spanish and French. The administrator might have added more languages. Chose the default language of your installation here.';
$LANG['config_defaultMenu'] = 'Menu Position';
$LANG['config_defaultMenu_comment'] = 'The TeamCal Neo menu can either be shown horizontally at the top or vertically on the left. The vertical menu is only suited for wide screens while the horizontal menu
 also adjusts to narrow screens (responsive). The user can change this setting individually in his profile.';
$LANG['config_defaultMenu_navbar'] = 'Horizontal Top';
$LANG['config_defaultMenu_sidebar'] = 'Vertical Left';
$LANG['config_disableTfa'] = 'Disable Two Factor Authentication';
$LANG['config_disableTfa_comment'] = 'Disable the Two Factor Authentication feature for all users. This will remove the 2FA setup page from the user profile and also delete all existing user 2FA secrets.';
$LANG['config_emailConfirmation'] = 'Require e-mail Confirmation';
$LANG['config_emailConfirmation_comment'] = 'Upon registration the user will receive an e-mail to the address he specified containing a confirmation link. He needs to follow that link to validate his information.';
$LANG['config_emailNotifications'] = 'E-Mail Notifications';
$LANG['config_emailNotifications_comment'] = 'Enable/Disable E-Mail notifications. If you uncheck this option no automated notifications E-Mails are sent.
 However, this does not apply to self-registration mails and to manually sent mails via the Message Center and the Viewprofile dialog.';
$LANG['config_faCDN'] = 'Fontawesome CDN';
$LANG['config_faCDN_comment'] = 'CDNs (Content Distributed Network) can offer a performance benefit by hosting popular web modules on servers spread
 across the globe. Fontawesome is such a module. Pulling it from a CDN location also offers an advantage that if the visitor
 to your webpage has already downloaded a copy of it from the same CDN, it won\'t have to be re-downloaded.<br>This option only works with an
 Internet connection of course. Switch this option off if you are running the application in an environment without Internet connectivity.';
$LANG['config_font'] = 'Font';
$LANG['config_font_comment'] = 'Select the font to use. Options are:<ul>
      <li>Default <i>(will not load any extra font but use the default sans-serif font of your browser)</i></li>
      <li>... <i>(will load the selected Google font hosted with your TeamCal installation (not from Google))</i></li>
      </ul>';
$LANG['config_footerCopyright'] = 'Footer Copyright Name';
$LANG['config_footerCopyright_comment'] = 'Will be displayed in the upper left footer section. Just enter the name, the (current) year will be displayed automatically.';
$LANG['config_footerCopyrightUrl'] = 'Footer Copyright URL';
$LANG['config_footerCopyrightUrl_comment'] = 'Enter the URL to which the footer copyright name shall link to. If none is provided, just the name is displayed.';
$LANG['config_footerSocialLinks'] = 'Social Links';
$LANG['config_footerSocialLinks_comment'] = 'Enter all URLs to the social sites you want to link to from TeamCal Neo\'s footer. Separate them by semicolon. TeamCal Neo will identify them and place the proper icons in the footer.';
$LANG['config_forceTfa'] = 'Force Two Factor Authentication';
$LANG['config_forceTfa_comment'] = 'Force users to setup a two factor authentication, e.g. via Google or Microsoft Authenticator. If a user has not setup 2FA yet, he will be redirected to the setup page after regular login.';

$LANG['config_gdprController'] = 'Controller';
$LANG['config_gdprController_comment'] = 'Enter the information about the controller for the purposes of the General Data Protection Regulation (GDPR), other data protection laws applicable in Member states of the European Union and other provisions related to data protection.';
$LANG['config_gdprOfficer'] = 'Data Protection Officer';
$LANG['config_gdprOfficer_comment'] = 'Name of the data protection officer of the controller.';
$LANG['config_gdprOrganization'] = 'Organization';
$LANG['config_gdprOrganization_comment'] = 'Name of the organisation oder company that provides this instance of TeamCal Neo.';
$LANG['config_gdprPlatforms'] = 'Platform Policies';
$LANG['config_gdprPlatforms_comment'] = 'Check the platforms that you want to have included in the data protection policy.';
$LANG['config_gdprPolicyPage'] = 'Data Privacy Policy';
$LANG['config_gdprPolicyPage_comment'] = 'Check to add a Data Privacy Policy page to the Help menu.<br>If selected, the fields "Organization", "Controller" and "Data Protection Officer" below must be filled in.<br>
      Below that, you can optionally include policies for certain social networks in case you have linked them in your footer.';
$LANG['config_googleAnalytics'] = 'Google Analytics';
$LANG['config_googleAnalytics_comment'] = 'TeamCal Neo supports Google Analytics. If you run your instance in the Internet and want to use Google Analytics
 to trace access to it, you can check this box and enter your Google Analytics ID below. The corresponding Javascript code will be added automatically.';
$LANG['config_googleAnalyticsID'] = 'Google Analytics ID (GA4)';
$LANG['config_googleAnalyticsID_comment'] = 'If you enabled the Google Analytics feature, enter your Google Analytics GA4 ID here in the format G-... .';
$LANG['config_gracePeriod'] = 'Grace Period';
$LANG['config_gracePeriod_comment'] = 'The amount of time in seconds that a user has to wait after too many bad logins before he can try again.';
$LANG['config_homepage'] = 'User Homepage';
$LANG['config_homepage_comment'] = 'Select what page to display to registered users after login.';
$LANG['config_homepage_calendarview'] = 'Calendar';
$LANG['config_homepage_home'] = 'Welcome Page';
$LANG['config_homepage_messages'] = 'Message Page';
$LANG['config_jQueryCDN'] = 'jQuery CDN';
$LANG['config_jQueryCDN_comment'] = 'CDNs (Content Distributed Network) can offer a performance benefit by hosting popular web modules on servers spread
 across the globe. jQuery is such a module. Pulling it from a CDN location also offers an advantage that if the visitor
 to your webpage has already downloaded a copy of jQuery from the same CDN, it won\'t have to be re-downloaded.<br>Switch this option off if you are
 running the application in an environment with no Internet connectivity.';
$LANG['config_jqtheme'] = 'jQuery UI Theme';
$LANG['config_jqtheme_comment'] = 'TeamCal Neo uses jQuery UI, a popular collection of Javascript utilities. jQuery UI offers themes as well used for the display
 of the tabbed dialogs and other features. The default theme is "smoothness" which is a neutral gray shaded theme. Try more from the list, some of them are
 quite colorful. This is a global setting, users cannot choose an individual jQuery UI theme.';
$LANG['config_jqthemeSample'] = 'jQuery UI Theme Sample';
$LANG['config_jqthemeSample_comment'] = 'Try this date picker to see the currently selected jQiery theme. You may have to reload the page to see the effect.<br>The date you pick here is not saved.';
$LANG['config_licActivate'] = 'Activate License';
$LANG['config_licActivate_comment'] = 'Your license is not active yet. Please activate it.';
$LANG['config_licExpiryWarning'] = 'License Expiry Warning';
$LANG['config_licExpiryWarning_comment'] = 'Enter the number of days before license expiry at which TeamCal Neo should start showing a corresponding alert. Set to 0 for no alert.';
$LANG['config_licKey'] = 'License Key';
$LANG['config_licKey_comment'] = 'Enter your license key here. It was provided to you when you registered your TeamCal Neo instance.<br><i class=\'bi-exclamation-triangle text-warning me-2\'></i>
<i>A new license key must first be saved with the [Save] button before an Activation, Registration or De-Registration can be done.</i>';
$LANG['config_licRegister'] = 'Register Domain';
$LANG['config_licRegister_comment'] = 'This TeamCal Neo domain is not registered for the given license key.<br>If the license key is valid and allows for more than one domain,
 click the "Register" button to add this domain. Otherwise, please enter a different valid license key and click "Apply".';
$LANG['config_licDeregister'] = 'De-Register Domain';
$LANG['config_licDeregister_comment'] = 'You can deregister this TeamCal Neo domain from your license, e.g. to move your instance to a different domain. Deregister it here and then register it from the new domain.';
$LANG['config_logLanguage'] = 'Log Language';
$LANG['config_logLanguage_comment'] = 'This setting sets the language for the system log entries.';
$LANG['config_mailFrom'] = 'Mail From';
$LANG['config_mailFrom_comment'] = 'Specify a name to be shown as sender of notification e-mails.';
$LANG['config_mailReply'] = 'Mail Reply-To';
$LANG['config_mailReply_comment'] = 'Specify an e-mail address to reply to for notification e-mails. This field must contain a valid e-mail address. If that is not the case a dummy e-mail address will be saved.';
$LANG['config_mailSMTP'] = 'Use external SMTP server';
$LANG['config_mailSMTP_comment'] = 'Use an external SMTP server instead of the PHP mail() function to send out eMails. This feature requires the PEAR
 Mail package to be installed on your server. Many hosters install this package by default. It is also necessary for SMTP to work, that your TcNeo
 server can connect to the selected SMTP server via the usual SMTP ports 25, 465 or 587, using plain SMTP or TLS/SSL protocol, depending on your settings.
 Some hosters have this communication closed down by firewall rules. You will get a connection error then.';
$LANG['config_mailSMTPAnonymous'] = 'Anonymous SMTP';
$LANG['config_mailSMTPAnonymous_comment'] = 'Use SMTP connection without authentication.';
$LANG['config_mailSMTPhost'] = 'SMTP Host';
$LANG['config_mailSMTPhost_comment'] = 'Specify the SMTP host name.';
$LANG['config_mailSMTPport'] = 'SMTP Port';
$LANG['config_mailSMTPport_comment'] = 'Specify the SMTP host port.';
$LANG['config_mailSMTPusername'] = 'SMTP Username';
$LANG['config_mailSMTPusername_comment'] = 'Specify the SMTP username.';
$LANG['config_mailSMTPpassword'] = 'SMTP Password';
$LANG['config_mailSMTPpassword_comment'] = 'Specify the SMTP password.';
$LANG['config_mailSMTPSSL'] = 'SMTP TLS/SSL protocol';
$LANG['config_mailSMTPSSL_comment'] = 'Use the TLS/SSL protocol for the SMTP connection';
$LANG['config_matomoAnalytics'] = 'Matomo Analytics';
$LANG['config_matomoAnalytics_comment'] = 'TeamCal Neo supports Matomo Analytics. If you want to track access to your TeamCal Neo application,
 you can check this box and enter your Matomo URL and SiteId below. The corresponding Javascript code will be added automatically.';
$LANG['config_matomoUrl'] = 'Matomo URL';
$LANG['config_matomoUrl_comment'] = 'If you enabled the Matomo Analytics feature, enter the URL where your Matomo server is hosted, e.g. \'mysite.com/matomo\'.';
$LANG['config_matomoSiteId'] = 'Matomo SiteId';
$LANG['config_matomoSiteId_comment'] = 'Enter the Matomo SiteId under which you have configured this TeamCal Neo application at your Matomo server.';
$LANG['config_noCaching'] = 'No Caching';
$LANG['config_noCaching_comment'] = 'In some server-client environments you might experience unwanted caching effects. With this option activated, TeamCal Neo sends No-caching instructions to the web server that might help.';
$LANG['config_noIndex'] = 'No Search Engine Indexing';
$LANG['config_noIndex_comment'] = 'With this switch on, search engine robots are instructed not to index this TeamCal Neo website. Also, no SEO header entries will be generated.';
$LANG['config_pageHelp'] = 'Page Help';
$LANG['config_pageHelp_comment'] = 'With this switch on, a help icon will be displayed in the page title bar, linking to the documentation of this page.';
$LANG['config_permissionScheme'] = 'Permission Scheme';
$LANG['config_permissionScheme_comment'] = 'The permission defines who can do what. The permission schemes can be configured on the permissions page.';
$LANG['config_pwdStrength'] = 'Password Strength';
$LANG['config_pwdStrength_comment'] = 'The password strength defines how picky you want to be with the password check. Allowed are small and capital letters, numbers and the following special characters: !@#$%^&*().<br><br>
         - <strong>Low:</strong> At least 4 characters long<br>
         - <strong>Medium:</strong> At least 6 characters long, one small letter, one capital letter and one number<br>
         - <strong>High:</strong> At least 8 characters long, one small letter, one capital letter, one number and one special character<br>';
$LANG['config_pwdStrength_low'] = 'Low';
$LANG['config_pwdStrength_medium'] = 'Medium';
$LANG['config_pwdStrength_high'] = 'High';
$LANG['config_showAlerts'] = 'Show Alerts';
$LANG['config_showAlerts_comment'] = 'Select what type of alerts will be shown.';
$LANG['config_showAlerts_all'] = 'All (including Success messages)';
$LANG['config_showAlerts_warnings'] = 'Warnings and Errors only';
$LANG['config_showAlerts_none'] = 'None';
$LANG['config_timeZone'] = 'Time Zone';
$LANG['config_timeZone_comment'] = 'If your web server resides in a different time zone than your users you can adjust the user time zone here.';
$LANG['config_underMaintenance'] = 'Under Maintenance';
$LANG['config_underMaintenance_comment'] = 'With this switch the site is set into maintenance mode. Only the admin can login and access pages.
      Regular users will see an "Under Maintenance" message.';
$LANG['config_userCustom1'] = 'User Custom Field 1 Caption';
$LANG['config_userCustom1_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['config_userCustom2'] = 'User Custom Field 2 Caption';
$LANG['config_userCustom2_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['config_userCustom3'] = 'User Custom Field 3 Caption';
$LANG['config_userCustom3_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['config_userCustom4'] = 'User Custom Field 4 Caption';
$LANG['config_userCustom4_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['config_userCustom5'] = 'User Custom Field 5 Caption';
$LANG['config_userCustom5_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['config_userManual'] = 'User Manual';
$LANG['config_userManual_comment'] = 'TeamCal Neo\'s user manual is maintained in English and is available at the <a href="https://lewe.gitbook.io/teamcal-neo/" target="_blank">Lewe Gitbook site</a>.
      If you have written your own manual, enter the link here. It will be displayed in the Help menu as long as the field is not empty.';
$LANG['config_versionCompare'] = 'Version Compare';
$LANG['config_versionCompare_comment'] = 'With this option enabled, TeamCal Neo\'s About page will compare the running version with the newest version available. In order to do so,
 Internet access is necessary. If you are running TeamCal Neo in an environment where no Internet access is available, switch this option off. Available updates will be shown next to the version number.';
$LANG['config_welcomeText'] = 'Welcome Page Text';
$LANG['config_welcomeText_comment'] = 'Enter a text for the welcome message on the Home Page.<br><i>Note: Your changes will not be saved when clicking `Save` in code view.</i>';

$LANG['config_clearCache'] = 'Clear Cache';
$LANG['config_clearCache_comment'] = 'Click this button to clear the application cache. This might be necessary if you encounter display issues or outdated information.';
$LANG['config_clearCache_confirm'] = 'Are you sure you want to clear the application cache?';
$LANG['config_alert_cache_cleared'] = 'Application cache cleared successfully.';
$LANG['config_alert_cache_failed'] = 'Failed to clear application cache.';

