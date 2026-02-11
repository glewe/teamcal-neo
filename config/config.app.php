<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Application Configuration
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 1.0.0
 */

/**
 * ----------------------------------------------------------------------------
 * ROUTING
 * ----------------------------------------------------------------------------
 *
 * Application URL (optional)
 * Set this if the auto-detection fails, e.g. behind a reverse proxy
 * define('APPLICATION_URL', 'http://your-domain.com/tcneo/');
 */
define('APPLICATION_URL', '');

// @phpstan-ignore-next-line
if (APPLICATION_URL !== '') {
  $websiteUrl = APPLICATION_URL;
  if (substr($websiteUrl, -1) !== '/') {
    $websiteUrl .= '/';
  }
  define('WEBSITE_URL', $websiteUrl);
}
else {
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
  $fullURL  = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  $pos      = strrpos($fullURL, '/');
  define('WEBSITE_URL', substr($fullURL, 0, $pos + 1));
}

/**
 * ----------------------------------------------------------------------------
 * DIRECTORY LOCATIONS AND FILE SPECS
 * ----------------------------------------------------------------------------
 */
define('APP_AVATAR_DIR', "public/upload/avatars/");
define('APP_UPL_DIR', "public/upload/files/");
define('APP_IMP_DIR', "public/upload/import/");

$CONF['avatarExtensions'] = array('gif', 'jpg', 'png');
$CONF['avatarMaxsize']    = 2048 * 100; // 100 KB
$CONF['imgExtensions']    = array('gif', 'jpg', 'png');
$CONF['impExtensions']    = array('csv');
$CONF['uplExtensions']    = array('gif', 'jpg', 'png', 'doc', 'docx', 'pdf', 'ppt', 'pptx', 'xls', 'xlsx', 'zip');
$CONF['uplMaxsize']       = 2048 * 1024; // 2 MB

/**
 * ----------------------------------------------------------------------------
 * INSTALLATION
 * ----------------------------------------------------------------------------
 *
 * A flag indicating whether the installation script has been executed.
 * 0 = Not run yet
 * 1 = Was run
 * Set this to 0 if you want to run the installation.php script again.
 * If not, you need to delete or rename the installation.php file.
 */
define('APP_INSTALLED', "1");

/**
 * ----------------------------------------------------------------------------
 * COOKIE
 * ----------------------------------------------------------------------------
 *
 * The cookie name to be used on the browser client's device
 */
define('COOKIE_NAME', "teamcalneo");

/**
 * ----------------------------------------------------------------------------
 * LANGUAGE DEBUGGING
 * ----------------------------------------------------------------------------
 *
 * COMPARE_LANGUAGES - set to true to compare all language files
 * DEBUG_LANGUAGE - set to true to write language statistics to the error log
 *
 */
define('COMPARE_LANGUAGES', false);
define('DEBUG_LANGUAGE', false);

/**
 * ----------------------------------------------------------------------------
 * ADDONS
 * ----------------------------------------------------------------------------
 */

/**
 * Bootstrap
 *
 * Powerful, extensible, and feature-packed frontend toolkit.
 * https://getbootstrap.com/
 */
define('BOOTSTRAP_VER', "5.3.8");

/**
 * Bootstrap Icons
 *
 * Free, high quality, open source icon library with over 2,000 icons.
 * https://icons.getbootstrap.com/
 */
define('BOOTSTRAP_ICONS_VER', "1.13.1");

/**
 * Chart.js
 *
 * Simple yet flexible JavaScript charting for designers & developers
 * https://www.chartjs.org/
 */
define('CHARTJS_VER', "4.5.1");

/**
 * Summernote (because Ckeditor5 still sucks)
 *
 * Super simple WYSIWYG editor for Bootstrap
 * https://summernote.org/
 */
define('SUMMERNOTE_VER', "0.9.0");

/**
 * Coloris
 *
 * Elegant Color Picker for the Modern Web
 * https://coloris.js.org/
 */
define('COLORIS_VER', "0.25.0");

/**
 * Cookie Consent by Silktide
 *
 * https://silktide.com/cookieconsent
 */
define('COOKIECONSENT_VER', "3.1.1");

/**
 * Datatables
 *
 * DataTables is a Javascript HTML table enhancing library.
 * https://datatables.net/
 */
define('DATATABLES_VER', "2.3.6");

/**
 * FontAwesome
 *
 * The internet's favorite icon library & toolkit.
 * https://fontawesome.com/
 */
define('FONTAWESOME_VER', "7.1.0");

/**
 * jQuery
 *
 * jQuery is a fast, small, and feature-rich JavaScript library.
 * https://jquery.com/
 */
define('JQUERY_VER', "3.7.1");

/**
 * jQuery UI
 *
 * jQuery UI is a curated set of user interface interactions, effects, widgets, and themes built on top of the jQuery JavaScript library.
 * https://jqueryui.com/
 */
define('JQUERY_UI_VER', "1.14.2");

/**
 * Magnific Popup
 *
 * Magnific Popup is a responsive lightbox & dialog script
 * https://dimsemenov.com/plugins/magnific-popup/
 */
define('MAGNIFICPOPUP_VER', "1.2.0");

/**
 * ----------------------------------------------------------------------------
 * LDAP
 * ----------------------------------------------------------------------------
 *
 * You can enable LDAP user authentication in this section. Please read the
 * requirements below.
 *
 * PHP Requirements
 * You will need to get and compile LDAP client libraries from either
 * OpenLDAP or Bind9.net in order to compile PHP with LDAP support.
 *
 * PHP Installation
 * LDAP support in PHP is not enabled by default. You will need to use the
 * --with-ldap[=DIR] configuration option when compiling PHP to enable LDAP
 * support. DIR is the LDAP base install directory. To enable SASL support,
 * be sure --with-ldap-sasl[=DIR] is used, and that sasl.h exists on the system.
 *
 * The following settings are utilizing the free online LDAP server provided by
 * forumsys.com. You can use this server for testing purposes. The server provides
 * a few test users as documented here:
 * https://www.forumsys.com/2022/05/10/online-ldap-test-server/
 *
 * The sample database that comes with TeamCal Neo contains a user with username
 * 'einstein'. In the TeamCal Neo database this user has the password 'Qwer!1234'.
 * This user also exists in the forumsys LDAP server with password 'password'.
 * Set LDAP_YES to 1 here. You can then login with einstein/password.
 *
 * Note, that the 'admin' user will always authenticate against the TeamCal Neo database.
 *
 */
define('LDAP_YES', 0);                                       // Use LDAP authentication
define('LDAP_ADS', 0);                                       // Set to 1 when authenticating against an Active Directory
define('LDAP_HOST', "ldap.forumsys.com");                    // LDAP host name
define('LDAP_PORT', "389");                                  // LDAP port
define('LDAP_PASS', "password");                             // SA associated password
define('LDAP_DIT', "cn=read-only-admin,dc=example,dc=com");  // Directory Information Tree (Relative Distinguished Name)
define('LDAP_SBASE', "dc=example,dc=com");                   // Search base, location in the LDAP directory to search
define('LDAP_TLS', 0);                                       // To avoid "Undefined index: LDAP_TLS" error message for LDAP bind to Active Directory
define('LDAP_CHECK_ANONYMOUS_BIND', 0);                      // Set to 1 to check the LDAP server's 'anonymous bind' setting. Connection will be refused if not allowed.
define('LDAP_SEARCH_BIND', 0);                               // Set to 1 to if you want to enable search bind (try disabling this when you get search bind errors)

/**
 * ----------------------------------------------------------------------------
 * APPLICATION INFORMATION
 * ----------------------------------------------------------------------------
 *
 * !Do not change anything below this line. It is protected by the license agreement!
 */
define('APP_NAME', "TeamCal Neo");
define('APP_VER', "5.0.2");
define('APP_BUILD', "25");
define('APP_DATE', "2026-02-11");
define('APP_YEAR', "2014-" . date('Y'));
define('APP_AUTHOR', "George Lewe");
define('APP_URL', "https://www.lewe.com");
define('APP_EMAIL', "george@lewe.com");
define('APP_LICENSE', "https://lewe.gitbook.io/teamcal-neo/readme/teamcal-neo-license/");
define('APP_COPYRIGHT', "(c) " . APP_YEAR . " by " . APP_AUTHOR . " (" . APP_URL . ")");
define('APP_POWERED', "Powered by " . APP_NAME . " " . APP_VER . " (Build " . APP_BUILD . ") &copy; " . APP_YEAR . " by <a href=\"https://www.lewe.com\" class=\"copyright\" target=\"_blank\">" . APP_AUTHOR . "</a>");
define('APP_LIC_KEY', "5e091d9b9cbf36.90197318");
define('APP_LIC_SRV', "https://lic.lewe.com");
define('APP_LIC_ITM', "TeamCal Neo");
