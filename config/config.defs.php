<?php
/**
 * config.defs.php
 * 
 * Defines
 *
 * @category TeamCal Neo 
 * @version 1.3.000
 * @author George Lewe
 * @copyright Copyright (c) 2014-2016 by George Lewe
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

//=============================================================================
/**
 * SCRIPT VERSIONS
 */ 
define('BOOTSTRAP_VER', '3.3.7');
define('FONTAWESOME_VER', '4.7.0');
define('JQUERY_VER', '3.1.1');
define('JQUERY_UI_VER', '1.12.1');
define('SECUREIMAGE_VER', '3.6.4');

//=============================================================================
/**
 * OPTIONAL ADDONS
 */ 
//
// Chart.js
// Simple yet flexible JavaScript charting for designers & developers
// http://www.chartjs.org/
//
define('CHARTJS', true);
define('CHARTJS_VER', '1.0.2');

//
// CKEditor
// The best web text editor for everyone
// http://ckeditor.com/
//
define('CKEDITOR', true);
define('CKEDITOR_VER', '4.5.11');

//
// Magnific Popup
// Magnific Popup is a responsive lightbox & dialog script
// http://dimsemenov.com/plugins/magnific-popup/
//
define('MAGNIFICPOPUP', true);
define('MAGNIFICPOPUP_VER', '1.1.0');

//
// Select2
// The jQuery replacement for select boxes
// https://select2.github.io/
//
define('SELECT2', false);
define('SELECT2_VER', '4.0.3');

//
// Syntaxhighlighter
// SyntaxHighlighter is a fully functional self-contained code syntax highlighter developed in JavaScript.
// http://alexgorbatchev.com/SyntaxHighlighter/
//
define('SYNTAXHIGHLIGHTER', false);
define('SYNTAXHIGHLIGHTER_VER', '3.0.83');

//
// X-Editable
// In-place editing with Twitter Bootstrap, jQuery UI or pure jQuery
// https://vitalets.github.io/x-editable/
//
define('XEDITABLE', false);
define('XEDITABLE_VER', '1.5.1');

//=============================================================================
/**
 * APPLICATION
 * 
 * !Do not change anything below this line. It is protected by the license agreement!
 */
define('APP_NAME', 'TeamCal Neo');
define('APP_VER', '1.3.000');
define('APP_DATE', '2016-11-24');
define('APP_YEAR', '2014-'.date('Y'));
define('APP_AUTHOR', 'George Lewe');
define('APP_URL', 'http://www.lewe.com');
define('APP_EMAIL', 'george@lewe.com');
define('APP_LICENSE', 'https://georgelewe.atlassian.net/wiki/x/AoC3Ag');
define('APP_COPYRIGHT', '(c) ' . APP_YEAR . ' by ' . APP_AUTHOR . ' (' . APP_URL . ')');
define('APP_POWERED', 'Powered by ' . APP_NAME . ' ' . APP_VER . ' &copy; ' . APP_YEAR . ' by <a href=\'http://www.lewe.com\' class=\'copyright\' target=\'_blank\'>' . APP_AUTHOR . '</a>');
?>