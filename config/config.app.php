<?php
/**
 * config.app.php
 * 
 * Application based parameters. Don't change anything in this file.
 *
 * @category TeamCal Neo 
 * @version 1.8.000
 * @author George Lewe
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

//=============================================================================
/**
 * ROUTING
 */
$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$fullURL = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$pos = strrpos($fullURL,'/');
define('WEBSITE_URL', substr($fullURL,0,$pos)); //Remove trailing slash
define('APP_AVATAR_DIR', "upload/avatars/");
define('APP_UPL_DIR', "upload/files/");
define('APP_IMP_DIR', "upload/import/");

//=============================================================================
/**
 * TEAMCAL NEO
 */
//
// A flag indicating whether the installation script has been executed.
// 0 = Not run yet
// 1 = Was run
// Set this to 0 if you want to run the installation.php script again.
// If not, you need to delete or rename the installation.php file.
//
define('APP_INSTALLED',"0");

//
// The cookie prefix to be used on the browser client's device
//
define('COOKIE_NAME',"tcneo");

//=============================================================================
/**
 * MANDATORY MODULES
 */
define('BOOTSTRAP_VER', "3.3.7");
define('FONTAWESOME_VER', "4.7.0");
define('JQUERY_VER', "3.1.1");
define('JQUERY_UI_VER', "1.12.1");
define('SECUREIMAGE_VER', "3.6.4");

//=============================================================================
/**
 * OPTIONAL MODULES
 */
//
// Chart.js
// Simple yet flexible JavaScript charting for designers & developers
// http://www.chartjs.org/
//
define('CHARTJS', true);
define('CHARTJS_VER', "2.4.0");

//
// CKEditor
// The best web text editor for everyone
// http://ckeditor.com/
//
define('CKEDITOR', true);
define('CKEDITOR_VER', "4.5.11");

//
// Magnific Popup
// Magnific Popup is a responsive lightbox & dialog script
// http://dimsemenov.com/plugins/magnific-popup/
//
define('MAGNIFICPOPUP', true);
define('MAGNIFICPOPUP_VER', "1.1.0");

//
// Select2
// The jQuery replacement for select boxes
// https://select2.github.io/
//
define('SELECT2', false);
define('SELECT2_VER', "4.0.3");

//
// Syntaxhighlighter
// SyntaxHighlighter is a fully functional self-contained code syntax highlighter developed in JavaScript.
// http://alexgorbatchev.com/SyntaxHighlighter/
//
define('SYNTAXHIGHLIGHTER', false);
define('SYNTAXHIGHLIGHTER_VER', "3.0.83");

//
// X-Editable
// In-place editing with Twitter Bootstrap, jQuery UI or pure jQuery
// https://vitalets.github.io/x-editable/
//
define('XEDITABLE', false);
define('XEDITABLE_VER', "1.5.1");

//=============================================================================
/**
 * FILE UPLOAD
 */
//
// Defines the allowed file types for upload
// Defines the allowed max file sizes for upload
//
$CONF['avatarExtensions'] = array ( 'gif', 'jpg', 'png' );
$CONF['avatarMaxsize'] = 1024 * 100; // 100 KB
$CONF['imgExtensions'] = array ( 'gif', 'jpg', 'png' );
$CONF['impExtensions'] = array ( 'csv' );
$CONF['uplExtensions'] = array ( 'gif', 'jpg', 'png', 'doc', 'docx', 'pdf', 'ppt', 'pptx', 'xls', 'xlsx', 'zip' );
$CONF['uplMaxsize'] = 2048 * 1024; // 2 MB

//=============================================================================
/**
 * LDAP
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
 */
define('LDAP_YES', 0); // Use LDAP authentication
define('LDAP_ADS', 0); // Set to 1 when authenticating against an Active Directory
define('LDAP_HOST', "ldap.mydomain.com"); // LDAP host name
define('LDAP_PORT', "389"); // LDAP port
define('LDAP_PASS', "XXXXXXXX"); // SA associated password
define('LDAP_DIT', "cn=<service account>,ou=fantastic_four,ou=superheroes,dc=marvel,dc=comics"); // Directory Information Tree (Relative Distinguished Name)
define('LDAP_SBASE', "ou=superheroes,ou=characters,dc=marvel,dc=comics"); // Search base, location in the LDAP dirctory to search
define('LDAP_TLS', 0); // To avoid "Undefined index: LDAP_TLS" error message for LDAP bind to Active Directory

//=============================================================================
/**
 * APPLICATION
 *
 * !Do not change anything below this line. It is protected by the license agreement!
 */
define('APP_NAME', "TeamCal Neo");
define('APP_VER', "1.8.000");
define('APP_DATE', "2017-10-31");
define('APP_YEAR', "2014-".date('Y'));
define('APP_AUTHOR', "George Lewe");
define('APP_URL', "http://www.lewe.com");
define('APP_EMAIL', "george@lewe.com");
define('APP_LICENSE', "https://georgelewe.atlassian.net/wiki/x/AoC3Ag");
define('APP_COPYRIGHT', "(c) " . APP_YEAR . " by " . APP_AUTHOR . " (" . APP_URL . ")");
define('APP_POWERED', "Powered by " . APP_NAME . " " . APP_VER . " &copy; " . APP_YEAR . " by <a href=\"http://www.lewe.com\" class=\"copyright\" target=\"_blank\">" . APP_AUTHOR . "</a>");
?>