<?php
/**
 * english.php
 * 
 * Language file (English)
 *
 * @category TeamCal Neo 
 * @version 1.3.003
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

//=============================================================================
//
// LeAF LANGUAGE
// The following are the Lewe Appliation Framework language entries. To easier
// update the framework at a later point, go to the bottom of this file to 
// enter your application language strings to keep them separate.
//
$LANG['locale'] = 'en_US';
$LANG['html_locale'] = 'en';

//
// Common
//
$LANG['action'] = 'Action';
$LANG['all'] = 'All';
$LANG['attention'] = 'Attention';
$LANG['auto'] = 'Automatic';
$LANG['avatar'] = 'Avatar';
$LANG['back_to_top'] = 'Back to top';
$LANG['blue'] = 'Blue';
$LANG['close_this_message'] = 'Close this message';
$LANG['cookie_message'] = 'This application uses cookies to ensure you get the best experience out of it.';
$LANG['cookie_dismiss'] = 'Got it!';
$LANG['cookie_learnMore'] = '[More info...]';
$LANG['custom'] = 'Custom';
$LANG['cyan'] = 'Cyan';
$LANG['description'] = 'Description';
$LANG['diagram'] = 'Diagram';
$LANG['display'] = 'Display';
$LANG['from'] = 'From';
$LANG['general'] = 'General';
$LANG['green'] = 'Green';
$LANG['grey'] = 'Grey';
$LANG['group'] = 'Group';
$LANG['license'] = 'TeamCal Neo License';
$LANG['magenta'] = 'Magenta';
$LANG['monthnames'] = array (
   1 => "January",
   "February",
   "March",
   "April",
   "May",
   "June",
   "July",
   "August",
   "September",
   "October",
   "November",
   "December" 
);
$LANG['monthShort'] = array (
   1 => "Jan",
   "Feb",
   "Mar",
   "Apr",
   "May",
   "Jun",
   "Jul",
   "Aug",
   "Sep",
   "Oct",
   "Nov",
   "Dec" 
);
$LANG['name'] = 'Name';
$LANG['none'] = 'None';
$LANG['options'] = 'Options';
$LANG['orange'] = 'Orange';
$LANG['period'] = 'Period';
$LANG['period_custom'] = 'Custom';
$LANG['period_month'] = 'Current month';
$LANG['period_quarter'] = 'Current quarter';
$LANG['period_half'] = 'Current half year';
$LANG['period_year'] = 'Current year';
$LANG['purple'] = 'Purple';
$LANG['red'] = 'Red';
$LANG['region'] = 'Region';
$LANG['role'] = 'Role';
$LANG['role_admin'] = 'Administrator';
$LANG['role_director'] = 'Director';
$LANG['role_manager'] = 'Manager';
$LANG['role_assistant'] = 'Assistant';
$LANG['role_user'] = 'User';
$LANG['role_public'] = 'Public';
$LANG['scale'] = 'Scale';
$LANG['search'] = 'Search';
$LANG['select_all'] = 'Select all';
$LANG['settings'] = 'Settings';
$LANG['smart'] = 'Smart';
$LANG['to'] = 'To';
$LANG['today'] = 'Today';
$LANG['total'] = 'Total';
$LANG['type'] = 'Type';
$LANG['user'] = 'User';
$LANG['weekdayShort'] = array (
   1 => "Mo",
   "Tu",
   "We",
   "Th",
   "Fr",
   "Sa",
   "Su" 
);
$LANG['weekdayLong'] = array (
   1 => "Monday",
   "Tuesday",
   "Wednesday",
   "Thursday",
   "Friday",
   "Saturday",
   "Sunday" 
);
$LANG['yellow'] = 'Yellow';

//
// About
//
$LANG['about_version'] = 'Version';
$LANG['about_copyright'] = 'Copyright';
$LANG['about_license'] = 'License';
$LANG['about_forum'] = 'Forum';
$LANG['about_tracker'] = 'Issue Tracker';
$LANG['about_documentation'] = 'Documentation';
$LANG['about_credits'] = 'Credits go to';
$LANG['about_for'] = 'for';
$LANG['about_and'] = 'and';
$LANG['about_majorUpdateAvailable'] = 'Major update available...';
$LANG['about_minorUpdateAvailable'] = 'Minor or patch update available...';
$LANG['about_misc'] = 'many users for testing and suggesting...';
$LANG['about_view_releaseinfo'] = 'Show/Hide Releaseinfo &raquo;';
$LANG['about_vote'] = 'Vote for TeamCal Neo';
$LANG['about_vote_comment'] = 'If you like TeamCal Neo, please vote for it <a href="http://www.lewe.com/teamcal-neo/#tcnvote" target="_blank">here...</a>';

//
// Alerts
//
$LANG['alert_alert_title'] = 'ALERT';
$LANG['alert_danger_title'] = 'ERROR';
$LANG['alert_info_title'] = 'INFORMATION';
$LANG['alert_success_title'] = 'SUCCESS';
$LANG['alert_warning_title'] = 'WARNING';

$LANG['alert_captcha_wrong'] = 'Captcha code wrong';
$LANG['alert_captcha_wrong_text'] = 'The Captcha code was incorrect. Please try again.';
$LANG['alert_captcha_wrong_help'] = 'The Captcha code must be entered as displayed. Capitalization is not relevant.';

$LANG['alert_controller_not_found_subject'] = 'Controller not found';
$LANG['alert_controller_not_found_text'] = 'The controller "%1%" could not be found.';
$LANG['alert_controller_not_found_help'] = 'Please check your installation. The file does not exist or you may not have permission to read it.';

$LANG['alert_decl_before_date'] = ": Absence changes before the following date are not allowed: ";
$LANG['alert_decl_group_threshold'] = ": Group absence threshold reached for your group(s): ";
$LANG['alert_decl_period'] = ": Absence changes in the following period are not allowed: ";
$LANG['alert_decl_total_threshold'] = ": Total absence threshold reached.";

$LANG['alert_input'] = 'Input validation failed';
$LANG['alert_input_alpha'] = 'This field allows alphabetical characters only.';
$LANG['alert_input_alpha_numeric'] = 'This field allows alphanumerical characters only.';
$LANG['alert_input_alpha_numeric_dash'] = 'This field allows alphanumerical characters only plus dash and underscore.';
$LANG['alert_input_alpha_numeric_dash_blank'] = 'This field allows alphanumerical characters only plus blank, dash and underscore.';
$LANG['alert_input_alpha_numeric_dash_blank_dot'] = 'This field allows alphanumerical characters only plus blank, dot, dash and underscore.';
$LANG['alert_input_alpha_numeric_dash_blank_special'] = 'This field allows alphanumerical characters only plus blank, dash, underscore and the
      special characters \'!@#$%^&*().';
$LANG['alert_input_alpha_numeric_dot_at'] = 'This field allows alphanumerical characters, dots and the @ sign.';
$LANG['alert_input_date'] = 'The date must be in ISO 8601 format, e.g. 2014-01-01.';
$LANG['alert_input_email'] = 'The E-mail address is invalid.';
$LANG['alert_input_equal'] = 'The value of this field must be the same as in the field "%s".';
$LANG['alert_input_equal_string'] = 'The string in this field must be "%s".';
$LANG['alert_input_exact_length'] = 'The input of this field must be exactly "%s" characters.';
$LANG['alert_input_greater_than'] = 'The value of this field must be greater than the field "%s".';
$LANG['alert_input_hexadecimal'] = 'This field allows hexadecimal characters only.';
$LANG['alert_input_ip_address'] = 'The input of this field is not a valid IP address.';
$LANG['alert_input_less_than'] = 'The value of this field must be less than the field "%s".';
$LANG['alert_input_match'] = 'The field "%s" must match field "%s".';
$LANG['alert_input_max_length'] = 'The input of this field can have a maximum of "%s" characters.';
$LANG['alert_input_min_length'] = 'The input of this field must have a minimum of "%s" characters.';
$LANG['alert_input_numeric'] = 'The input of this field must be numeric.';
$LANG['alert_input_phone_number'] = 'The input in this field must be a valid phone number, e.g. (555) 123 4567 oder +49 172 123 4567.';
$LANG['alert_input_pwdlow'] = 'The password must be at least 4 characters long and can contain small and capital letters, numbers and the following special characters: !@#$%^&*()';
$LANG['alert_input_pwdmedium'] = 'The password must be at least 6 characters long, must contain at least one small letter, at least one capital letter and at least one number. 
      Allowed are small and capital letters, numbers and the following special characters: !@#$%^&*()';
$LANG['alert_input_pwdhigh'] = 'The password must be at least 8 characters long, must contain at least one small letter, at least one capital letter, at least one number and
      at least one special character. Allowed are small and capital letters, numbers and the following special characters: !@#$%^&*()';
$LANG['alert_input_regex_match'] = 'The input of this field did not match the regular expression "%s".';
$LANG['alert_input_required'] = 'This field is mandatory.';
$LANG['alert_input_validation_subject'] = 'Input validation';

$LANG['alert_maintenance_subject'] = 'Site Under Maintenance';
$LANG['alert_maintenance_text'] = 'The site is currently set to "Under Maintenance". Regular users will not be able to use any feature.';
$LANG['alert_maintenance_help'] = 'As an administrator you can set the site back to active under Administration -> Framework Configuration -> System.';

$LANG['alert_no_data_subject'] = 'Invalid Data';
$LANG['alert_no_data_text'] = 'This operation was requested with invalid or insufficient data.';
$LANG['alert_no_data_help'] = 'The operation failed due to missing or invalid data.';

$LANG['alert_not_allowed_subject'] = 'Access not allowed';
$LANG['alert_not_allowed_text'] = 'You do not have permission to access this page or feature.';
$LANG['alert_not_allowed_help'] = 'If you are not logged in, then public access to this page is not allowed. If you are logged in, your account role is not permitted to view this page.';

$LANG['alert_perm_invalid'] = 'The new permission scheme name "%1%" is invalid. Choose upper or lower case characters or numbers from 0 to 9. Don\'t use blanks.';
$LANG['alert_perm_exists'] = 'The permission scheme "%1%" already exists. Use a different name or delete the old one first.';
$LANG['alert_perm_default'] = 'The "Default" permission scheme cannot be reset to itself.';

$LANG['alert_reg_subject'] = 'User Registration';
$LANG['alert_reg_approval_needed'] = 'Your verification was successful. However, your account needs to be finally activated by an administrator. A mail has been sent to him/her.';
$LANG['alert_reg_success'] = 'Your verification was successful. You can now log in and use the application.';
$LANG['alert_reg_mismatch'] = 'The submitted verification code does not match the one we have on record. A mail has been sent to the admin to review your case.';
$LANG['alert_reg_no_user'] = 'The username cannot be found. Are you sure it was registered?';
$LANG['alert_reg_no_vcode'] = 'A verification could not be found. Are you sure it was registered?';

$LANG['alert_upl_img_subject'] = 'Upload Images';
$LANG['alert_upl_doc_subject'] = 'Upload Documents';

//
// Attachments
//
$LANG['att_title'] = 'Attachments';
$LANG['att_tab_files'] = 'Files';
$LANG['att_tab_upload'] = 'Upload';
$LANG['att_col_file'] = 'File';
$LANG['att_col_owner'] = 'Owner';
$LANG['att_col_shares'] = 'Shares';

$LANG['att_confirm_delete'] = 'Are you sure you want to delete the selected files?';
$LANG['att_extensions'] = 'Allowed extensions';
$LANG['att_file'] = 'Upload File';
$LANG['att_file_comment'] = 'You can upload a custom file. The size of the file is limited to %d KBytes and the allowed formats are "%s".';
$LANG['att_maxsize'] = 'Maximum filesize';
$LANG['att_shareWith'] = 'Share with';
$LANG['att_shareWith_comment'] = 'Select the groups or users you want to share this file with. Note, that these users must have access to this upload page to retrieve the file.';
$LANG['att_shareWith_all'] = 'All';
$LANG['att_shareWith_group'] = 'Group';
$LANG['att_shareWith_role'] = 'Role';
$LANG['att_shareWith_user'] = 'User';

//
// Buttons
//
$LANG['btn_activate'] = "Activate";
$LANG['btn_add'] = 'Add';
$LANG['btn_apply'] = 'Apply';
$LANG['btn_archive_selected'] = 'Archive selected';
$LANG['btn_assign'] = 'Assign';
$LANG['btn_assign_all'] = 'Assign All';
$LANG['btn_backup'] = 'Backup';
$LANG['btn_calendar'] = 'Calendar';
$LANG['btn_cancel'] = 'Cancel';
$LANG['btn_clear'] = 'Clear';
$LANG['btn_clear_all'] = 'Clear All';
$LANG['btn_close'] = 'Close';
$LANG['btn_configure'] = 'Configure';
$LANG['btn_confirm'] = 'Confirm';
$LANG['btn_confirm_all'] = 'Confirm All';
$LANG['btn_create'] = 'Create';
$LANG['btn_create_abs'] = 'Create Absence Type';
$LANG['btn_create_group'] = 'Create Group';
$LANG['btn_create_holiday'] = 'Create Holiday';
$LANG['btn_create_region'] = 'Create Region';
$LANG['btn_create_role'] = 'Create Role';
$LANG['btn_create_user'] = 'Create User';
$LANG['btn_delete'] = 'Delete';
$LANG['btn_delete_abs'] = 'Delete Absence Type';
$LANG['btn_delete_all'] = 'Delete All';
$LANG['btn_delete_group'] = 'Delete Group';
$LANG['btn_delete_holiday'] = 'Delete Holiday';
$LANG['btn_delete_records'] = 'Delete Records';
$LANG['btn_delete_role'] = 'Delete Role';
$LANG['btn_delete_selected'] = 'Delete Selected';
$LANG['btn_done'] = 'Done';
$LANG['btn_download_view'] = 'Download/View';
$LANG['btn_edit'] = 'Edit';
$LANG['btn_edit_profile'] = 'Edit Profile';
$LANG['btn_enable'] = 'Enable';
$LANG['btn_export'] = 'Export';
$LANG['btn_group_list'] = 'Show Group List';
$LANG['btn_help'] = 'Help';
$LANG['btn_icon'] = 'Icon...';
$LANG['btn_import'] = 'Import';
$LANG['btn_install'] = 'Install';
$LANG['btn_login'] = 'Login';
$LANG['btn_logout'] = 'Logout';
$LANG['btn_merge'] = 'Merge';
$LANG['btn_next'] = 'Next';
$LANG['btn_optimize_tables'] = 'Optimize Tables';
$LANG['btn_prev'] = 'Prev';
$LANG['btn_refresh'] = 'Refresh';
$LANG['btn_register'] = 'Register';
$LANG['btn_remove'] = 'Remove';
$LANG['btn_reset'] = 'Reset';
$LANG['btn_reset_database'] = 'Reset Database';
$LANG['btn_reset_password'] = 'Reset Password';
$LANG['btn_reset_password_selected'] = 'Reset password of selected';
$LANG['btn_restore'] = 'Restore';
$LANG['btn_restore_selected'] = 'Restore Selected';
$LANG['btn_role_list'] = 'Show Role List';
$LANG['btn_save'] = 'Save';
$LANG['btn_search'] = 'Search';
$LANG['btn_select'] = "Select";
$LANG['btn_send'] = 'Send';
$LANG['btn_show_hide'] = 'Show/Hide';
$LANG['btn_submit'] = 'Submit';
$LANG['btn_switch'] = 'Switch';
$LANG['btn_testdb'] = 'Test Database';
$LANG['btn_transfer'] = 'Transfer';
$LANG['btn_update'] = 'Update';
$LANG['btn_user_list'] = 'Show User List';
$LANG['btn_upload'] = 'Upload';
$LANG['btn_view'] = 'View';

//
// Config
//
$LANG['config_title'] = 'Framework Configuration';

$LANG['config_tab_email'] = 'E-mail';
$LANG['config_tab_footer'] = 'Footer';
$LANG['config_tab_homepage'] = 'Homepage';
$LANG['config_tab_images'] = 'Images';
$LANG['config_tab_login'] = 'Login';
$LANG['config_tab_registration'] = 'Registration';
$LANG['config_tab_system'] = 'System';
$LANG['config_tab_theme'] = 'Theme';
$LANG['config_tab_user'] = 'User';

$LANG['config_activateMessages'] = 'Activate Message Center';
$LANG['config_activateMessages_comment'] = 'This option will activate the Message Center. User can use it to send announcements or e-mails to other
      users or groups. An entry in the Tools menu will be added.';
$LANG['config_adminApproval'] = 'Require Admin Approval';
$LANG['config_adminApproval_comment'] = 'The administrator will receive an e-mail about each user self-registration. He manually needs to confirm the account.';
$LANG['config_allowRegistration'] = 'Allow User Self-Registration';
$LANG['config_allowRegistration_comment'] = 'Allow users to self-register their account. A menu entry will be available in the '.$appTitle.' menu.';
$LANG['config_allowUserTheme'] = 'Allow User Theme';
$LANG['config_allowUserTheme_comment'] = 'Check whether you want each user to be able to select his individual theme.';
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
$LANG['config_cookieConsent_comment'] = 'With this option, a cookie consent confirmation will pop up at the bottom of the screen. This is legally required in the EU.';
$LANG['config_cookieLifetime'] = 'Cookie Lifetime';
$LANG['config_cookieLifetime_comment'] = 'Upon successful login a cookie is stored on the local hard drive of the user. This cookie has a certain 
      lifetime after which it becomes invalid. A new login is necessary. This lifetime can be specified here in seconds (0-999999).';
$LANG['config_defaultLanguage'] = 'Default Language';
$LANG['config_defaultLanguage_comment'] = $appTitle . ' is distributed in English and German. The adminstrator might have added more languages. 
      Chose the default language of your installation here.';
$LANG['config_emailConfirmation'] = 'Require e-mail Confirmation';
$LANG['config_emailConfirmation_comment'] = 'Upon registration the user will receive an e-mail to the address he specified containing a confirmation link. 
      He needs to follow that link to validate his information.';
$LANG['config_emailNotifications'] = 'E-Mail Notifications';
$LANG['config_emailNotifications_comment'] = 'Enable/Disable E-Mail notifications. If you uncheck this option no automated notifications E-Mails are sent. 
      However, this does not apply to self-registration mails and to manually sent mails via the Message Center and the Viewprofile dialog.';
$LANG['config_faCDN'] = 'Fontawesome CDN';
$LANG['config_faCDN_comment'] = 'CDNs (Content Distributed Network) can offer a performance benefit by hosting popular web modules on servers spread 
      across the globe. Fontawesome is such a module. Pulling it from a CDN location also offers an advantage that if the visitor 
      to your webpage has already downloaded a copy of it from the same CDN, it won\'t have to be re-downloaded.<br>This option only works with an
      Internet connection of course. Switch this option off if you are running the application in an environment without Internet connectivity.';
$LANG['config_footerCopyright'] = 'Footer Copyright Name';
$LANG['config_footerCopyright_comment'] = 'Will be displayed in the upper left footer section. Just enter the name, the (current) year will be displayed automatically.';
$LANG['config_footerCopyrightUrl'] = 'Footer Copyright URL';
$LANG['config_footerCopyrightUrl_comment'] = 'Enter the URL to which the footer copyright name shall link to. If none is provided, just the name is displayed.';
$LANG['config_footerSocialLinks'] = 'Social Links';
$LANG['config_footerSocialLinks_comment'] = 'Enter all URLs to the social sites you want to link to from TeamCal Neo\'s footer. Separate them by smicolon. TeamCal Neo will identify them and place the proper icons in the footer.';
$LANG['config_footerViewport'] = 'Show Viewport Size';
$LANG['config_footerViewport_comment'] = 'Checking this option will show the viewport size in the footer.';
$LANG['config_googleAnalytics'] = 'Google Analytics';
$LANG['config_googleAnalytics_comment'] = $appTitle . ' supports Google Analytics. If you run your instance in the Internet and want to use Google Analytics 
      to trace access to it, you can check this box and enter your Google Analytics ID below. The corresponding Javascript code will be added automatically.';
$LANG['config_googleAnalyticsID'] = "Google Analytics ID";
$LANG['config_googleAnalyticsID_comment'] = "If you enabled the Google Analytics feature, enter your Google Analytics ID here in the format UA-999999-99.";
$LANG['config_gracePeriod'] = 'Grace Period';
$LANG['config_gracePeriod_comment'] = 'The amount of time in seconds that a user has to wait after too many bad logins before he can try again.';
$LANG['config_homepage'] = 'Homepage';
$LANG['config_homepage_comment'] = 'Select what page to display as the Home page.';
$LANG['config_homepage_home'] = 'Welcome Page';
$LANG['config_homepage_messages'] = 'Message Page';
$LANG['config_jQueryCDN'] = 'jQuery CDN';
$LANG['config_jQueryCDN_comment'] = 'CDNs (Content Distributed Network) can offer a performance benefit by hosting popular web modules on servers spread 
      across the globe. jQuery is such a module. Pulling it from a CDN location also offers an advantage that if the visitor 
      to your webpage has already downloaded a copy of jQuery from the same CDN, it won\'t have to be re-downloaded.<br>Switch this option off if you are 
      running the application in an environment with no Internet connectivity.';
$LANG['config_jqtheme'] = 'jQuery UI Theme';
$LANG['config_jqtheme_comment'] = $appTitle . ' uses jQuery UI, a popular collection of Javascript utilities. jQuery UI offers themes as well used for the display 
      of the tabbed dialogs and other features. The default theme is "smoothness" which is a neutral gray shaded theme. Try more from the list, some of them are 
      quite colorful. This is a global setting, users cannot choose an indiviual jQuery UI theme.';
$LANG['config_logLanguage'] = "Log Language";
$LANG['config_logLanguage_comment'] = "This setting sets the language for the system log entries.";
$LANG['config_mailFrom'] = 'Mail From';
$LANG['config_mailFrom_comment'] = 'Specify a name to be shown as sender of notification e-mails.';
$LANG['config_mailReply'] = 'Mail Reply-To';
$LANG['config_mailReply_comment'] = 'Specify an e-mail address to reply to for notification e-mails. This field must contain a valid e-mail address. 
      If that is not the case a dummy e-mail address will be saved.';
$LANG['config_mailSMTP'] = 'Use external SMTP server';
$LANG['config_mailSMTP_comment'] = 'Use an external SMTP server instead of the PHP mail() function to send out eMails. This feature requires the PEAR 
      Mail package to be installed on your server. Many hosters install this package by default. It is also necessary for SMTP to work, that your TcNeo 
      server can connect to the selected SMTP server via the usual SMTP ports 25, 465 or 587, using plain SMTP or TLS/SSL protocol, depending on your settings. 
      Some hosters have this communication closed down by firewall rules. You will get a connection error then.';
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
$LANG['config_menuBarInverse'] = 'Menu Bar Inverse';
$LANG['config_menuBarInverse_comment'] = 'With this switch you can inverse the color set of the menu bar. For some themes this is the better choice.';
$LANG['config_noIndex'] = 'No Search Engine Indexing';
$LANG['config_noIndex_comment'] = 'With this switch on, search engine robots are instructed not to index this TeamCal Neo website. Also, no SEO header entries will be generated.';
$LANG['config_permissionScheme'] = 'Permission Scheme';
$LANG['config_permissionScheme_comment'] = 'The permission defines who can do what. The permisson schemes can be configured on the permissions page.';
$LANG['config_pwdStrength'] = 'Password Strength';
$LANG['config_pwdStrength_comment'] = 'The password strength defines how picky you wanna be with the password check. Allowed are small and capital letters, numbers and the following special characters: !@#$%^&*()<br><br> 
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
$LANG['config_showBanner'] = 'Show Banner';
$LANG['config_showBanner_comment'] = 'Checking this option will show a banner between the navbar and the content section displaying the application title and a search box.';
$LANG['config_theme'] = 'Default Theme';
$LANG['config_theme_comment'] = 'Select a theme to change the looks of the application. You can create your own skin by making a renamed
      copy of any of the other theme folders in the \'themes\' directory and adjust the styles to your liking. Your new directory will
      automatically be listed here. Make sure you do not copy the \'bootstrap\' folder. It is just a dummy folder for the core theme.';
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
$LANG['config_userManual_comment'] = $appTitle . '\'s user manual is maintained in English and is available at <a href="https://georgelewe.atlassian.net/wiki/display/TCNEO/" target="_blank">Lewe.com\'s Confluence site</a>.
      If you have written your own manual, enter the link here. It will be displayed in the Help menu as long as the field is not empty.';
$LANG['config_versionCompare'] = 'Version Compare';
$LANG['config_versionCompare_comment'] = 'With this option enabled, TeamCal Neo\'s About page will compare the running version with the newest version available. In order to do so, 
      Internet access is necessary. If you are running TeamCal Neo in an environment where no Internet access is available, switch this option off. Available updates will be shown next to the version number.';
$LANG['config_welcomeText'] = 'Welcome Page Text';
$LANG['config_welcomeText_comment'] = 'Enter a text for the welcome message on the Home Page.';

//
// Config App
//
$LANG['configapp_title'] = 'Application Configuration';

//
// Database
//
$LANG['db_title'] = 'Database Maintenance';
$LANG['db_tab_cleanup'] = 'Clean up';
$LANG['db_tab_delete'] = 'Delete records';
$LANG['db_tab_admin'] = 'Administration';
$LANG['db_tab_optimize'] = 'Optimize tables';
$LANG['db_tab_reset'] = 'Reset database';

$LANG['db_alert_delete'] = 'Record Deletion';
$LANG['db_alert_delete_success'] = 'The delete activities have been performed.';
$LANG['db_alert_failed'] = 'The operation could not be performed. Please check your input.';
$LANG['db_alert_optimize'] = 'Optimize Tables';
$LANG['db_alert_optimize_success'] = 'All database tables were optimized.';
$LANG['db_alert_reset'] = 'Reset Database';
$LANG['db_alert_reset_fail'] = 'One or more queries failed. Your database maybe incomplete or corrupt.';
$LANG['db_alert_reset_success'] = 'Your database was successfully reset with sample data.';
$LANG['db_alert_url'] = 'Save Database URL';
$LANG['db_alert_url_fail'] = 'Please enter a valid URL for the database application.';
$LANG['db_alert_url_success'] = 'The database application URL was saved successfully.';
$LANG['db_application'] = 'Database Administration';
$LANG['db_confirm'] = 'Confirmation';
$LANG['db_dbURL'] = 'Database URL';
$LANG['db_dbURL_comment'] = 'You can specify a direct link to your preferred database management application for this website. The button below will link to it.';
$LANG['db_del_archive'] = 'Delete all archived records';
$LANG['db_del_groups'] = 'Delete all groups';
$LANG['db_del_log'] = 'Delete all system log entries';
$LANG['db_del_messages'] = 'Delete all messages';
$LANG['db_del_orphMessages'] = 'Delete all orphaned messages';
$LANG['db_del_permissions'] = 'Delete custom permission schemes (except "Default")';
$LANG['db_del_users'] = 'Delete all users, their absence templates and daynotes (except "admin")';
$LANG['db_del_confirm_comment'] = 'Please type in "DELETE" to confirm this action:';
$LANG['db_del_what'] = 'What to delete';
$LANG['db_del_what_comment'] = 'Select here what you want to delete.';
$LANG['db_optimize'] = 'Optimize Database Tables';
$LANG['db_optimize_comment'] = 'Reorganizes the physical storage of table data and associated index data, to reduce storage space and improve I/O efficiency when accessing the tables.';
$LANG['db_reset_danger'] = '<strong>Danger!</strong> Resetting the database will delete ALL your data!!';
$LANG['db_resetString'] = 'Database Reset Confirmation String';
$LANG['db_resetString_comment'] = 'Resetting the database will replace all your information with the installation sample database.<br>
      Type the following in the text box to confirm your decision: "YesIAmSure".';

//
// E-Mail
//
$LANG['email_subject_group_changed'] = '%app_name% Group Changed';
$LANG['email_subject_group_created'] = '%app_name% Group Created';
$LANG['email_subject_group_deleted'] = '%app_name% Group Deleted';
$LANG['email_subject_month_changed'] = '%app_name% Month Changed';
$LANG['email_subject_month_created'] = '%app_name% Month Created';
$LANG['email_subject_month_deleted'] = '%app_name% Month Deleted';
$LANG['email_subject_role_changed'] = '%app_name% Role Changed';
$LANG['email_subject_role_created'] = '%app_name% Role Created';
$LANG['email_subject_role_deleted'] = '%app_name% Role Deleted';
$LANG['email_subject_user_account_changed'] = '%app_name% User Account Changed';
$LANG['email_subject_user_account_created'] = '%app_name% User Account Created';
$LANG['email_subject_user_account_deleted'] = '%app_name% User Account Deleted';
$LANG['email_subject_user_account_mismatch'] = '%app_name% User Account Verification Mismatch';
$LANG['email_subject_user_account_needs_approval'] = '%app_name% User Account Needs Approval';
$LANG['email_subject_user_account_registered'] = '%app_name% User Account Registered';

//
// Error Messages
//
$LANG['error_title'] = 'Application Error';

//
// Footer
//
$LANG['footer_home'] = 'Home';
$LANG['footer_help'] = 'Help';
$LANG['footer_about'] = 'About';
$LANG['footer_imprint'] = 'Imprint';

//
// Group
//
$LANG['group_edit_title'] = 'Edit Group: ';
$LANG['group_tab_settings'] = 'Group Settings';
$LANG['group_tab_members'] = 'Group Members';

$LANG['group_alert_edit'] = 'Update group';
$LANG['group_alert_edit_success'] = 'The information for this group was updated.';
$LANG['group_alert_save_failed'] = 'The new information for this group could not be saved. There was invalid input. Please check for error messages.';
$LANG['group_name'] = 'Name';
$LANG['group_name_comment'] = '';
$LANG['group_description'] = 'Description';
$LANG['group_description_comment'] = '';
$LANG['group_managers'] = 'Managers of this group';
$LANG['group_managers_comment'] = 'Select the managers of this group.';
$LANG['group_members'] = 'Members of this group';
$LANG['group_members_comment'] = 'Select the members of this group.';

//
// Groups
//
$LANG['groups_title'] = 'Groups';
$LANG['groups_alert_group_created'] = 'The group was created.';
$LANG['groups_alert_group_created_fail'] = 'The group was not created. Please check the "Create group" dialog for input errors.';
$LANG['groups_alert_group_deleted'] = 'The group was deleted.';
$LANG['groups_confirm_delete'] = 'Are you sure you want to delete this group: ';
$LANG['groups_description'] = 'Description';
$LANG['groups_name'] = 'Name';

//
// Home Page
//
$LANG['home_title'] = 'Welcome to ' . $appTitle;

//
// Imprint
// You can add more arrays here in order to display them on the Imprint page
//
$LANG['imprint'] = array ( 
   array (
      'title' => 'Author',
      'text' => '<i class="fa fa-thumbs-o-up fa-3x pull-left" style="color: #999999;"></i>'.$appTitle.' was created by George Lewe (<a href="http://www.lewe.com/">Lewe.com</a>). 
      '.$appTitle.' also uses free modules by other great people providing those awesome techonolgies to the public. 
      See detailed credits on the <a href="index.php?action=about">About page</a>.',
   ),
   array (
      'title' => 'Content',
      'text' => '<p><i class="fa fa-file-text-o fa-3x pull-left" style="color: #999999;"></i>The application '.$appTitle.' was created by George Lewe (<a href="http://www.lewe.com/">Lewe.com</a>).  
      If you feel that any material is used inappropriately, please contact <a href="http://www.lewe.com/contact/">Lewe.com</a>.</p>
      <p>None of the content, as a whole or in parts may be reproduced, copied or reused in any form or by any means, electronic or mechanical, 
      for any purpose, without the express written permission of George Lewe.</p>',
   ),
   array (
      'title' => 'Links',
      'text' => '<p><i class="fa fa-external-link fa-3x pull-left" style="color: #999999;"></i>All links on '.$appTitle.' are being provided as a convenience 
      and for informational purposes only; they do not constitute an endorsement or an approval by '.$appTitle.' of any of the products, services or opinions 
      of the corporation or organization or individual. '.$appTitle.' bears no responsibility for the accuracy, legality or content of the external site or 
      for that of subsequent links. Contact the external site for questions regarding its content.</p>',
   ),
   array (
      'title' => 'Cookies',
      'text' => '<i class="fa fa-download fa-3x pull-left" style="color: #999999;"></i>This application uses cookies. Cookies are small files with application 
      related information that are stored on your local hard drive. They do not contain any personal information nor are they transmitted anywhere but they are 
      needed to run the application properly.</p>
      <p>In the EU, legislation requires to get your consent for using cookies. By using this application you agree to the usage of cookies.</p>',
   ),
);

if ( $C->read('googleAnalytics') AND $C->read("googleAnalyticsID")) {
   $LANG['imprint'][] = array (
      'title' => 'Google Analytics',
      'text' => '<p><i class="fa fa-google fa-3x pull-left" style="color: #999999;"></i>This website uses Google Analytics, a web analytics service provided by
      Google, Inc. ("Google"). Google Analytics uses "cookies", which are text files placed on your computer, to help the website analyze how users use the site.
      The information generated by the cookie about the use of this website will be transmitted to and stored by Google on servers that may reside in the United States.</p>
      <div class="collapse" id="readmore">
         <p>In case IP-anonymisation is activated on this website, your IP address will be truncated within the area of Member States of the European Union or
         other parties to the Agreement on the European Economic Area. Only in exceptional cases the whole IP address will be first transfered to a Google server
         in the USA and truncated there. The IP-anonymisation is active on this website.</p>
         <p>Google will use this information on behalf of the operator of this website for the purpose of evaluating your use of the website, compiling reports on
         website activity for website operators and providing them other services relating to website activity and internet usage.</p>
         <p>The IP-address, that your Browser conveys within the scope of Google Analytics, will not be associated with any other data held by Google. You may
         refuse the use of cookies by selecting the appropriate settings on your browser, however please note that if you do this you may not be able to use the
         full functionality of this website. You can also opt-out from being tracked by Google Analytics with effect for the future by downloading and installing
         Google Analytics Opt-out Browser Addon for your current web browser: <a href="http://tools.google.com/dlpage/gaoptout?hl=en">http://tools.google.com/dlpage/gaoptout?hl=en</a>.</p>
         <p>As an alternative to the browser Addon or within browsers on mobile devices, you can <a href="javascript:gaOptout()">click this link</a> in order to
         opt-out from being tracked by Google Analytics within this website in the future (the opt-out applies only for the browser in which you set it and within
         this domain). An opt-out cookie will be stored on your device, which means that you\'ll have to click this link again, if you delete your cookies.</p>
      </div>
      <p><a class="btn btn-default" data-toggle="collapse" data-target="#readmore">Read more/less...</a></p>',
   );
}

//
// Log
//
$LANG['log_title'] = 'System Log';
$LANG['log_title_events'] = 'Events';

$LANG['log_clear'] = 'Delete period';
$LANG['log_clear_confirm'] = 'Are you sure you want to delete all events of the currently selected period?<br> 
      Note, that all events of all event types in the selected period will be deleted, even though you might have hidden them in the Log Settings.';
$LANG['log_filterNews'] = 'News';
$LANG['log_filterCalopt'] = 'Calender Options';
$LANG['log_filterConfig'] = 'Config';
$LANG['log_filterDatabase'] = 'Database';
$LANG['log_filterGroup'] = 'Groups';
$LANG['log_filterLogin'] = 'Login';
$LANG['log_filterLoglevel'] = 'Login';
$LANG['log_filterPermission'] = 'Permissions';
$LANG['log_filterRegistration'] = 'Registration';
$LANG['log_filterRole'] = 'Roles';
$LANG['log_filterUser'] = 'User';
$LANG['log_header_when'] = 'Timestamp (UTC)';
$LANG['log_header_type'] = 'Event Type';
$LANG['log_header_user'] = 'User';
$LANG['log_header_event'] = 'Event';
$LANG['log_settings'] = 'Log Settings';
$LANG['log_settings_event'] = 'Event type';
$LANG['log_settings_log'] = 'Log this event type';
$LANG['log_settings_show'] = 'Show this event type';
$LANG['log_sort_asc'] = 'Sort ascending...';
$LANG['log_sort_desc'] = 'Sort descending...';

//
// Login
//
$LANG['login_login'] = 'Login';
$LANG['login_username'] = 'Username:';
$LANG['login_password'] = 'Password:';
$LANG['login_error_0'] = 'Login successful';
$LANG['login_error_1'] = 'Username or password missing';
$LANG['login_error_1_text'] = 'Please provide a valid username and password.';
$LANG['login_error_2'] = 'Username unknown';
$LANG['login_error_2_text'] = 'The username you entered is unknown. Please try again.';
$LANG['login_error_3'] = 'Account disabled';
$LANG['login_error_3_text'] = 'This account is locked or not approved. Please contact your administrator.';
$LANG['login_error_4'] = 'Password incorrect';
$LANG['login_error_4_text'] = 'This was bad attempt number %1%. After %2% bad attempts your account will be locked for %3% seconds.';
$LANG['login_error_6_text'] = 'This account is on hold due to too many bad login attempts. The grace period ends in %1% seconds.';
$LANG['login_error_7'] = 'Username or password incorrect';
$LANG['login_error_7_text'] = 'The username and/or password you entered are not correct. Please try again.';
$LANG['login_error_8'] = 'Account not verified';
$LANG['login_error_8_text'] = 'Your account is not verified yet. You may have received an E-mail with a verification link.';
$LANG['login_error_91'] = 'LDAP error: Password missing';
$LANG['login_error_92'] = 'LDAP error: Authentication/Bind failed';
$LANG['login_error_92_text'] = 'The LDAP authentication/bind failed. Please try again.';
$LANG['login_error_93'] = 'LDAP error: Unable to connect to LDAP server';
$LANG['login_error_93_text'] = 'The LDAP server connection failed. Please try again.';
$LANG['login_error_94'] = 'LDAP error: Start TLS failed';
$LANG['login_error_94_text'] = 'The LDAP start TLS failed. Please try again.';
$LANG['login_error_95'] = 'LDAP error: Username not found';
$LANG['login_error_96'] = 'LDAP error: Search bind failed';
$LANG['login_error_96_text'] = 'The LDAP search bind failed. Please try again.';

//
// Logout
//
$LANG['logout_title'] = 'Logout';
$LANG['logout_comment'] = 'You have been logged out.';

//
// Maintenance
//
$LANG['mtce_title'] = 'Under Maintenance';
$LANG['mtce_text'] = 'The site is currently under maintenance. We apologize for the inconvenience. Please check back at a later time...';

//
// Menu
//
$LANG['mnu_app'] = $appTitle;
$LANG['mnu_app_homepage'] = 'Home page';
$LANG['mnu_app_language'] = 'Language';
$LANG['mnu_view'] = 'View';
$LANG['mnu_view_messages'] = 'Messages';
$LANG['mnu_edit'] = 'Edit';
$LANG['mnu_edit_attachments'] = 'Attachments';
$LANG['mnu_edit_messageedit'] = 'Message Editor';
$LANG['mnu_admin'] = 'Administration';
$LANG['mnu_admin_config'] = 'Framework Configuration';
$LANG['mnu_admin_configapp'] = 'Application Configuration';
$LANG['mnu_admin_database'] = 'Database Maintenance';
$LANG['mnu_admin_env'] = 'Environment';
$LANG['mnu_admin_groups'] = 'Groups';
$LANG['mnu_admin_perm'] = "Permissions";
$LANG['mnu_admin_phpinfo'] = 'PHP Info';
$LANG['mnu_admin_roles'] = 'Roles';
$LANG['mnu_admin_systemlog'] = 'System Log';
$LANG['mnu_admin_users'] = 'Users';
$LANG['mnu_help'] = 'Help';
$LANG['mnu_help_legend'] = 'Legend';
$LANG['mnu_help_help'] = 'User Manual';
$LANG['mnu_help_imprint'] = 'Imprint';
$LANG['mnu_help_about'] = 'About ' . $appTitle;
$LANG['mnu_user_login'] = 'Login';
$LANG['mnu_user_register'] = 'Register';
$LANG['mnu_user_logout'] = 'Logout';
$LANG['mnu_user_profile'] = 'Edit Profile';

//
// Messages
//
$LANG['msg_title'] = 'Messages for: ';
$LANG['msg_title_edit'] = 'Create Message';
$LANG['msg_code'] = 'Captcha Code';
$LANG['msg_code_comment'] = 'Please enter the Captcha code as displayed. Capitalization is not relevant.';
$LANG['msg_code_new'] = 'Load new image';
$LANG['msg_confirm_all_confirm'] = 'Are you sure you want to confirm all your messages?';
$LANG['msg_confirm_confirm'] = 'Are you sure you want to confirm message "%s" ? The message will not be deleted but not pop up at login anymore.';
$LANG['msg_content_type'] = 'Content Type';
$LANG['msg_content_type_desc'] = 'Content types display online news headers in different colors (not available for E-mails).';
$LANG['msg_content_type_info'] = 'Info';
$LANG['msg_content_type_primary'] = 'Primary';
$LANG['msg_content_type_success'] = 'Success';
$LANG['msg_content_type_warning'] = 'Warning';
$LANG['msg_content_type_danger'] = 'Danger';
$LANG['msg_delete_all_confirm'] = 'Are you sure you want to delete all messages ?';
$LANG['msg_delete_confirm'] = 'Are you sure you want to delete message "%s" ?';
$LANG['msg_msg_body'] = 'Body';
$LANG['msg_msg_body_comment'] = 'Enter the body of your message here.';
$LANG['msg_msg_body_sample'] = '...your text here...';
$LANG['msg_msg_sent'] = 'Message sent';
$LANG['msg_msg_sent_text'] = 'Your message was sent to the selected recipients.';
$LANG['msg_msg_title'] = 'Message Title';
$LANG['msg_msg_title_comment'] = 'Enter the title of your message here.';
$LANG['msg_msg_title_sample'] = '...your title here...';
$LANG['msg_no_file_subject'] = 'No file selected';
$LANG['msg_no_file_text'] = 'You have to select at least one file for this operation';
$LANG['msg_no_group_subject'] = 'No group selected';
$LANG['msg_no_group_text'] = 'You have to select at least one group to send the message to.';
$LANG['msg_no_text_subject'] = 'No subject and/or text';
$LANG['msg_no_text_text'] = 'You have to enter a subjcet and a text for the message.';
$LANG['msg_no_user_subject'] = 'No user selected';
$LANG['msg_no_user_text'] = 'You have to select at least one user to send the message to.';
$LANG['msg_sendto'] = 'Recipient';
$LANG['msg_sendto_desc'] = 'Select the recipient(s) of this message.';
$LANG['msg_sendto_all'] = 'All';
$LANG['msg_sendto_group'] = 'Group:';
$LANG['msg_sendto_user'] = 'User:';
$LANG['msg_type'] = 'Message Type';
$LANG['msg_type_desc'] = 'Chose the type of message you want to send.<br>
      A silent message will be put on the user\'s Messages page only.<br>
      A prompt message will cause the Messages page to show automatically when the user logs in.';
$LANG['msg_type_email'] = 'E-mail';
$LANG['msg_type_silent'] = 'Silent Message';
$LANG['msg_type_popup'] = 'Prompt Message';

//
// Modal dialogs
//
$LANG['modal_confirm'] = 'Please Confirm';

//
// Paging
//
$LANG['page_first'] = 'Go to first page...';
$LANG['page_prev'] = 'Go to previous page...';
$LANG['page_page'] = 'Go to page %s...';
$LANG['page_next'] = 'Go to next page...';
$LANG['page_last'] = 'Go to last page...';

//
// Password rules
//
$LANG['password_rules_low'] = '<br>The password strength is currently set to "low", resulting in the following rules:<br>
      - At least 4 characters<br>';
$LANG['password_rules_medium'] = '<br>The password strength is currently set to "medium", resulting in the following rules:<br>
      - At least 6 characters<br>
      - At least 1 capital letter<br>
      - At least 1 small letter<br>
      - At least 1 number<br>';
$LANG['password_rules_high'] = '<br>The password strength is currently set to "high", resulting in the following rules:<br>
      - At least 8 characters<br>
      - At least 1 capital letter<br>
      - At least 1 small letter<br>
      - At least 1 number<br>
      - At least 1 special character<br>';

//
// Permissions
//
$LANG['perm_title'] = 'Permission Scheme Settings';
$LANG['perm_tab_general'] = 'General';
$LANG['perm_tab_features'] = 'Features';

$LANG['perm_active'] = '(Active)';
$LANG['perm_activate_scheme'] = 'Activate scheme';
$LANG['perm_activate_confirm'] = 'Are you sure you want to activate this permission scheme?';
$LANG['perm_create_scheme'] = 'Create scheme';
$LANG['perm_create_scheme_desc'] = 'Type in a name for the new scheme. It will be created and loaded with default settings right away. 
      All changes to the current scheme that have not been applied will be lost.';
$LANG['perm_delete_scheme'] = 'Delete scheme';
$LANG['perm_delete_confirm'] = 'Are you sure you want to delete this permission scheme? The Default scheme will be loaded and activated.';
$LANG['perm_inactive'] = '(Inactive)';
$LANG['perm_reset_scheme'] = 'Reset scheme';
$LANG['perm_reset_confirm'] = 'Are you sure you want to reset the current permission scheme to the "Default" scheme?';
$LANG['perm_save_scheme'] = 'Save scheme';
$LANG['perm_select_scheme'] = 'Select scheme';
$LANG['perm_select_confirm'] = 'When you confirm a new scheme selection, all changes to the current scheme that have not been applied will be lost.';
$LANG['perm_view_by_perm'] = 'View by permission';
$LANG['perm_view_by_role'] = 'View by role';

$LANG['perm_admin_title'] = 'Administration (System)';
$LANG['perm_admin_desc'] = 'Allows to access the system administration pages.';
$LANG['perm_groups_title'] = 'Groups (Edit)';
$LANG['perm_groups_desc'] = 'Allows to list and edit user groups.';
$LANG['perm_groupmemberships_title'] = 'Users (Edit Memberships)';
$LANG['perm_groupmemberships_desc'] = 'Allows to assign users to groups as members or managers.';
$LANG['perm_messageview_title'] = 'Messages (View)';
$LANG['perm_messageview_desc'] = 'Allows to access the messages page. Note, that the messages page only shows messages for the logged in user.';
$LANG['perm_messageedit_title'] = 'Messages (Create)';
$LANG['perm_messageedit_desc'] = 'Allows to create and send messages.';
$LANG['perm_roles_title'] = 'Roles (Edit)';
$LANG['perm_roles_desc'] = 'Allows to list and edit roles.';
$LANG['perm_upload_title'] = 'Attachments';
$LANG['perm_upload_desc'] = 'Allows the access and upload of file attachments.';
$LANG['perm_useraccount_title'] = 'Users (Account Tab)';
$LANG['perm_useraccount_desc'] = 'Allows to edit the Account tab when editing a user profile.';
$LANG['perm_useradmin_title'] = 'Users (List and Add)';
$LANG['perm_useradmin_desc'] = 'Allows to list and add user accounts.';
$LANG['perm_useredit_title'] = 'Users (Edit)';
$LANG['perm_useredit_desc'] = 'Allows editing the own Userprofile.';
$LANG['perm_viewprofile_title'] = 'Users (View)';
$LANG['perm_viewprofile_desc'] = 'Allows to access the view profile page showing basic info like name, phone number etc. Viewing user popups is also 
      dependent on this permission.';

//
// Phpinfo
//
$LANG['phpinfo_title'] = 'PHP Info';

//
// Profile
//
$LANG['profile_create_title'] = 'Create User Profile';
$LANG['profile_create_mail'] = 'Send notification E-mail';
$LANG['profile_create_mail_comment'] = 'Sends a notification E-mail to the created user.';

$LANG['profile_view_title'] = 'Profile of: ';

$LANG['profile_edit_title'] = 'Edit profile: ';
$LANG['profile_tab_account'] = 'Account';
$LANG['profile_tab_avatar'] = 'Avatar';
$LANG['profile_tab_contact'] = 'Contact';
$LANG['profile_tab_custom'] = 'Custom';
$LANG['profile_tab_groups'] = 'Groups';
$LANG['profile_tab_notifications'] = 'Notifications';
$LANG['profile_tab_password'] = 'Password';
$LANG['profile_tab_personal'] = 'Personal';

$LANG['profile_alert_create'] = 'Create user profile';
$LANG['profile_alert_create_success'] = 'The new user account was created.';
$LANG['profile_alert_update'] = 'User profile update';
$LANG['profile_alert_update_success'] = 'The information for this user profile eas updated.';
$LANG['profile_alert_save_failed'] = 'The new information for this user could not be saved. There was invalid input. Please check the tabs for error messages.';
$LANG['profile_avatar'] = 'Avatar';
$LANG['profile_avatar_comment'] = 'If you haven\'t uploaded an own avatar, a default avatar will be used.';
$LANG['profile_avatar_available'] = 'Available Standard Avatars';
$LANG['profile_avatar_available_comment'] = 'Choose one of the available avatars, courtesy of <a href="http://www.iconshock.com/icon_sets/vector-user-icons/" target="_blank">IconShock</a>.';
$LANG['profile_avatar_upload'] = 'Upload avatar';
$LANG['profile_avatar_upload_comment'] = 'You can upload a custom avatar. The size of the file is limited to %d Bytes, the size of the image should be 
      80x80 pixels (will be displayed in those dimensions anyways) and the allowed formats are "%s".';
$LANG['profile_custom1'] = $C->read('userCustom1');
$LANG['profile_custom1_comment'] = 'Enter a text value of maximal 80 characters.';
$LANG['profile_custom2'] = $C->read('userCustom2');
$LANG['profile_custom2_comment'] = 'Enter a text value of maximal 80 characters.';
$LANG['profile_custom3'] = $C->read('userCustom3');
$LANG['profile_custom3_comment'] = 'Enter a text value of maximal 80 characters.';
$LANG['profile_custom4'] = $C->read('userCustom4');
$LANG['profile_custom4_comment'] = 'Enter a text value of maximal 80 characters.';
$LANG['profile_custom5'] = $C->read('userCustom5');
$LANG['profile_custom5_comment'] = 'Enter a text value of maximal 80 characters.';
$LANG['profile_email'] = 'E-mail';
$LANG['profile_email_comment'] = '';
$LANG['profile_facebook'] = 'Facebook';
$LANG['profile_facebook_comment'] = '';
$LANG['profile_firstname'] = 'Firstname';
$LANG['profile_firstname_comment'] = '';
$LANG['profile_gender'] = 'Gender';
$LANG['profile_gender_comment'] = '';
$LANG['profile_gender_male'] = 'Male';
$LANG['profile_gender_female'] = 'Female';
$LANG['profile_google'] = 'Google+';
$LANG['profile_google_comment'] = '';
$LANG['profile_id'] = 'ID';
$LANG['profile_id_comment'] = '';
$LANG['profile_language'] = 'Language';
$LANG['profile_language_comment'] = 'Selects a custom language for the application interface.';
$LANG['profile_lastname'] = 'Lastname';
$LANG['profile_lastname_comment'] = '';
$LANG['profile_linkedin'] = 'LinkedIn';
$LANG['profile_linkedin_comment'] = '';
$LANG['profile_locked'] = '<i class="glyphicon glyphicon-menu glyphicon-lock text-danger" style="font-size: 80%; padding-right: 16px;"></i>Locked';
$LANG['profile_locked_comment'] = 'The account is locked. No login is possible.';
$LANG['profile_managerships'] = 'Manager of';
$LANG['profile_managerships_comment'] = 'Select the groups that this user is manager of. Should the same group be selected here and in the member list, 
      then the manager position is saved.';
$LANG['profile_memberships'] = 'Member of';
$LANG['profile_memberships_comment'] = 'Select the groups that this user is member of. Should the same group be selected here and in the manager list, 
      then the manager position is saved.';
$LANG['profile_menuBar'] = 'Menu Bar Display';
$LANG['profile_menuBar_comment'] = 'With this switch you can inverse the color set of the menu bar. For some themes this is the better choice.';
$LANG['profile_menuBar_default'] = 'Default';
$LANG['profile_menuBar_inverse'] = 'Inverse';
$LANG['profile_menuBar_normal'] = 'Normal';
$LANG['profile_mobilephone'] = 'Mobile';
$LANG['profile_mobilephone_comment'] = '';
$LANG['profile_notifyGroupEvents'] = 'Group Events';
$LANG['profile_notifyGroupEvents_comment'] = 'Select this option if you want to be informed on changes to groups.';
$LANG['profile_notifyRoleEvents'] = 'Role Events';
$LANG['profile_notifyRoleEvents_comment'] = 'Select this option if you want to be informed on changes to roles.';
$LANG['profile_notifyUserEvents'] = 'User Account Events';
$LANG['profile_notifyUserEvents_comment'] = 'Select this option if you want to be informed on changes to user accounts.';
$LANG['profile_onhold'] = '<i class="glyphicon glyphicon-menu glyphicon-time text-warning" style="font-size: 80%; padding-right: 16px;"></i>On hold';
$LANG['profile_onhold_comment'] = 'This status is applied after a user has entered a wrong password too many times. This causes a grace period in which no login is possible. 
      The grace period can be configured on the configuration page. You can manually release the status here as well.';
$LANG['profile_password'] = 'Password';
$LANG['profile_password_comment'] = 'You can enter a new password here. If the field stays empty, the current password will not be changed.<br>
      Allowed are small and capital letters, numbers and the following special characters: !@#$%^&amp;*()';
$LANG['profile_password2'] = 'Confirm password';
$LANG['profile_password2_comment'] = 'Repeat the new password here.';
$LANG['profile_phone'] = 'Phone';
$LANG['profile_phone_comment'] = '';
$LANG['profile_position'] = 'Position';
$LANG['profile_position_comment'] = '';
$LANG['profile_role'] = 'Role';
$LANG['profile_role_comment'] = 'Select the role of this user here. The role defines the permissions of this user.';
$LANG['profile_skype'] = 'Skype';
$LANG['profile_skype_comment'] = '';
$LANG['profile_theme'] = 'Theme';
$LANG['profile_theme_comment'] = 'Selects a custom theme for the application interface.';
$LANG['profile_title'] = 'Title';
$LANG['profile_title_comment'] = '';
$LANG['profile_twitter'] = 'Twitter';
$LANG['profile_twitter_comment'] = '';
$LANG['profile_username'] = 'Loginname';
$LANG['profile_username_comment'] = 'The loginname cannot be changed for existing users.';
$LANG['profile_verify'] = '<i class="glyphicon glyphicon-menu glyphicon-exclamation-sign text-success" style="font-size: 80%; padding-right: 16px;"></i>Verify';
$LANG['profile_verify_comment'] = 'When a user has registered himself but did not use the activation link yet, this status is applied. The account is created but no login is possible yet.
      You can manually release the status here as well.';

//
// Register
//
$LANG['register_title'] = 'User Registration';
$LANG['register_alert_success'] = 'You user account was registered and an E-mail with the corresponding information was sent to you.';
$LANG['register_alert_failed'] = 'Your registration could not be completed. Please check your input.';
$LANG['register_code'] = 'Captcha Code';
$LANG['register_code_comment'] = 'Please enter the Captcha code as displayed. Capitalization is not relevant.';
$LANG['register_code_new'] = 'Load new image';
$LANG['register_email'] = 'E-mail';
$LANG['register_email_comment'] = '';
$LANG['register_firstname'] = 'Firstname';
$LANG['register_firstname_comment'] = '';
$LANG['register_lastname'] = 'Lastname';
$LANG['register_lastname_comment'] = '';
$LANG['register_password'] = 'Password';
$LANG['register_password_comment'] = 'Please enter a password here.<br>
      Allowed are small and capital letters, numbers and the following special characters: !@#$%^&amp;*()';
$LANG['register_password2'] = 'Confirm password';
$LANG['register_password2_comment'] = 'Repeat the password here.';
$LANG['register_username'] = 'Loginname';
$LANG['register_username_comment'] = 'The loginname cannot be changed for existing users.';

//
// Role
//
$LANG['role_edit_title'] = 'Edit Role: ';
$LANG['role_alert_edit'] = 'Update role';
$LANG['role_alert_edit_success'] = 'The information for this role was updated.';
$LANG['role_alert_save_failed'] = 'The new information for this role could not be saved. There was invalid input. Please check for error messages.';
$LANG['role_alert_save_failed_duplicate'] = 'The new information for this role could not be saved. A role with that name already exists.';
$LANG['role_color'] = 'Role Color';
$LANG['role_color_comment'] = 'User icons will be colored based on the role color chosen here.';
$LANG['role_color_danger'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-danger"></span>';
$LANG['role_color_default'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-default"></span>';
$LANG['role_color_info'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-info"></span>';
$LANG['role_color_primary'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-primary"></span>';
$LANG['role_color_success'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-success"></span>';
$LANG['role_color_warning'] = '<span class="glyphicon glyphicon-menu glyphicon-user text-warning"></span>';
$LANG['role_description'] = 'Description';
$LANG['role_description_comment'] = '';
$LANG['role_name'] = 'Name';
$LANG['role_name_comment'] = '';

//
// Roles
//
$LANG['roles_title'] = 'Roles';
$LANG['roles_alert_created'] = 'The role was created.';
$LANG['roles_alert_created_fail_input'] = 'The role was not created. Please check the "Create role" dialog for input validation errors.';
$LANG['roles_alert_created_fail_duplicate'] = 'The role was not created. A role with that name already exists.';
$LANG['roles_alert_deleted'] = 'The role was deleted.';
$LANG['roles_confirm_delete'] = 'Are you sure you want to delete this role: ';
$LANG['roles_description'] = 'Description';
$LANG['roles_name'] = 'Name';

//
// Status Bar
//
$LANG['status_logged_in'] = 'You are logged in as ';
$LANG['status_logged_out'] = 'Not logged in';
$LANG['status_ut_user'] = 'Regular User';
$LANG['status_ut_manager'] = 'Manager of group: ';
$LANG['status_ut_director'] = 'Director';
$LANG['status_ut_assistant'] = 'Assistant';
$LANG['status_ut_admin'] = 'Administrator';

//
// Upload Errors
//
$LANG['upl_error_0'] = 'The file "%s" was successfully uploaded.';
$LANG['upl_error_1'] = 'The uploaded file exceeds the maximum upload filesize directive in the server configuration.';
$LANG['upl_error_2'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
$LANG['upl_error_3'] = 'The file was only partially uploaded.';
$LANG['upl_error_4'] = 'No file was uploaded.';
$LANG['upl_error_10'] = 'Please select a file for upload.';
$LANG['upl_error_11'] = 'Only files with the following extensions are allowed: %s';
$LANG['upl_error_12'] = 'The filename contains invalid characters. Use only alphanumeric characters and separate parts of the name (if needed) with an underscore. A valid filename ends with one dot followed by the extension.';
$LANG['upl_error_13'] = 'The filename exceeds the maximum length of %d characters.';
$LANG['upl_error_14'] = 'The upload directory does not exist!';
$LANG['upl_error_15'] = 'A file with the name "%s" already exists.';
$LANG['upl_error_16'] = 'The uploaded file was renamed to: %s';
$LANG['upl_error_17'] = 'The file "%s" does not exist.';
$LANG['upl_error_18'] = 'An unspecified error occured during upload.';
$LANG['upl_error_19'] = 'The file could not be copied to it\'s destination.';

//
// Users
//
$LANG['users_title'] = 'Users';
$LANG['users_alert_archive_selected_users'] = 'The selected users were archived.';
$LANG['users_alert_archive_selected_users_failed'] = 'One or more of the selected users already exist in the archive. This could be the same user or one with the same username.<br>Please delete these archived users first.';
$LANG['users_alert_delete_selected_users'] = 'The selected users were deleted.';
$LANG['users_alert_reset_password_selected'] = 'The passwords of selected users were reset and a corresponding e-mail was sent to them.';
$LANG['users_alert_restore_selected_users'] = 'The selected users were restored.';
$LANG['users_alert_restore_selected_users_failed'] = 'One or more of the selected users already exist as active users. This could be the same user or one with the same username.<br>Please delete these active users first.';
$LANG['users_attributes'] = 'Attributes';
$LANG['users_attribute_locked'] = 'Account locked';
$LANG['users_attribute_hidden'] = 'Account hidden';
$LANG['users_attribute_onhold'] = 'Account on hold';
$LANG['users_attribute_verify'] = 'Account to be verified';
$LANG['users_confirm_archive'] = 'Are you sure you want to archive the selected users?';
$LANG['users_confirm_delete'] = 'Are you sure you want to delete the selected users?';
$LANG['users_confirm_password'] = 'Are you sure you want to reset the passwords of the selected users?';
$LANG['users_confirm_restore'] = 'Are you sure you want to restore the selected users?';
$LANG['users_created'] = 'Created';
$LANG['users_last_login'] = 'Last Login';
$LANG['users_tab_active'] = 'Active Users';
$LANG['users_tab_archived'] = 'Archived Users';
$LANG['users_user'] = 'User';
?>
