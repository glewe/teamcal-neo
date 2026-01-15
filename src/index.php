<?php
/**
 * Index
 *
 * @author     George Lewe <george@lewe.com>
 * @copyright  Copyright (c) 2014-2024 by George Lewe
 * @link       https://www.lewe.com
 *
 * @package    TeamCal Neo
 * @subpackage Views
 * @since      3.0.0
 */

global $appLanguages;
global $LANG;

//-----------------------------------------------------------------------------
// Set PRODUCTION_MODE to true to suppress PHP errors and warnings.
// Set to false for development and debugging.
//
define('PRODUCTION_MODE', false);
if (PRODUCTION_MODE) {
  error_reporting(0);
  ini_set('display_errors', 0);
} else {
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
}

//-----------------------------------------------------------------------------
// Check if a session already exists
//
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

//-----------------------------------------------------------------------------
// Generate a CSRF token
//
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

//-----------------------------------------------------------------------------
// DEFINES
//
define('VALID_ROOT', 1);
define('WEBSITE_ROOT', __DIR__);

//-----------------------------------------------------------------------------
// COMPOSER AUTOLOADER
//
require_once __DIR__ . "/vendor/autoload.php";

//-----------------------------------------------------------------------------
// LOAD CLASSES
//
spl_autoload_register(function ($class_name) {
  // Skip PHPMailer classes as they are loaded via Composer autoloader
  if (strpos($class_name, 'PHPMailer') === false) {
    require_once 'classes/' . $class_name . '.class.php';
  }
});

//-----------------------------------------------------------------------------
// LOAD CONFIG
//
require_once WEBSITE_ROOT . '/config/config.db.php';
require_once WEBSITE_ROOT . '/config/config.controller.php';
require_once WEBSITE_ROOT . '/config/config.app.php';
require_once WEBSITE_ROOT . '/helpers/language.helper.php';
global $CONF;

//-----------------------------------------------------------------------------
// HELPERS
//
require_once WEBSITE_ROOT . '/helpers/global.helper.php';
require_once WEBSITE_ROOT . '/helpers/model.helper.php';
require_once WEBSITE_ROOT . '/helpers/notification.helper.php';
require_once WEBSITE_ROOT . '/helpers/view.helper.php';
require_once WEBSITE_ROOT . '/helpers/app.helper.php';


//-----------------------------------------------------------------------------
// CHECK INSTALLATION SCRIPT
//
if (file_exists('installation.php')) {
  if (!APP_INSTALLED) {
    header("Location: installation.php");
  } else {
    //
    // Installation.php found after installation
    //
    $errorData['title'] = 'Application Error';
    $errorData['subject'] = 'Installation Script Exists';
    $errorData['text'] = '<p>The installation script "installation.php" still exists in the root directory while "config/config.app.php" indicates that an installation has been performed.</p>
      <p>The application will not start until either one has been resolved:</p>
      <ol>
        <li>Delete or rename "installation.php"</li>
        <li>Set define[\'APP_INSTALLED\'] to 0 in "config/config.app.php"</li>
      </ol>';
    require_once 'views/error.php';
    die();
  }
} elseif (!APP_INSTALLED) {
  //
  // App not installed but Installation.php not found
  //
  $errorData['title'] = 'Application Error';
  $errorData['subject'] = 'Installation Script Not Found';
  $errorData['text'] = '<p>The installation script "installation.php" does not exist in the root directory while "config/config.app.php" indicates that no installation has been performed yet.</p>
    <p>The application will not start until either one has been resolved:</p>
    <ol>
      <li>Recover "installation.php"</li>
      <li>Set define[\'APP_INSTALLED\'] to 0 in "config/config.app.php"</li>
    </ol>';
  require_once 'views/error.php';
  die();
}

//-----------------------------------------------------------------------------
// CLASS INSTANCES
//
// Instantiate primary classes (used by other classes)
//
$DB = new DB($CONF['db_server'], $CONF['db_name'], $CONF['db_user'], $CONF['db_pass']);
$C = new Config($CONF, $DB);
//
// Instantiate secondary classes
//
$G = new Groups($CONF, $DB);
$L = new Login();
$LOG = new Log();
$MSG = new Messages();
$P = new Permissions();
$RO = new Roles();
$U = new Users();
$UG = new UserGroup();
$UL = new Users(); // For the currently logged in user
$UMSG = new UserMessage();
$UO = new UserOption();
//
// Custom classes
//
$A = new Absences();
$AG = new AbsenceGroup();
$AL = new Allowances();
$D = new Daynotes();
$H = new Holidays();
$M = new Months();
$R = new Regions();
$T = new Templates();

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
require_once WEBSITE_ROOT . '/config/config.vars.php';
//
// Load all config records (global in controllers)
//
$allConfig = $C->readAll();

$showAlert = false;
$appTitle = $allConfig['appTitle'];
$language = $allConfig["defaultLanguage"];
$appStatus['maintenance'] = false;
$controller = 'home';
$userData['isLoggedIn'] = false;
$userData['username'] = 'Public';
$userData['roleid'] = '3'; // 3 = Public
$userData['color'] = 'default';
$userData['avatar'] = 'default_male.png';
$userData['defaultMenu'] = $allConfig['defaultMenu'];
//
// Load all permissions into an array so there is no need to query the database for each permission
//
$permissions = $P->getPermissions($allConfig['permissionScheme']);
//
// Check login and make logged in username global
//
if ($luser = $L->checkLogin()) {
  define('L_USER', $luser);
} else {
  define('L_USER', 0);
}
//
// If someone is logged in, overwrite defaults
//
if (L_USER && (!isset($_GET['action']) || isset($_GET['action']) && $_GET['action'] != 'logout')) {
  $userData['isLoggedIn'] = true;
  //
  // Get the user
  //
  $UL->findByName($luser);
  //
  // Fill the user array
  //
  $userData['username'] = $UL->username;
  $userData['roleid'] = $UL->role;
  $userData['fullname'] = $UL->getFullname($UL->username);
  $userData['color'] = getRoleColor($UL->role);

  $userData['avatar'] = $UO->read($UL->username, 'avatar');
  if ($userData['avatar'] && !file_exists(APP_AVATAR_DIR . $userData['avatar'])) {
    $userData['avatar'] = 'default_' . $UO->read($UL->username, 'gender') . '.png';
  }

  $defaultMenu = $UO->read($UL->username, 'defaultMenu');
  if ($defaultMenu) {
    $userData['defaultMenu'] = $defaultMenu;
  }

  $userlang = $UO->read($UL->username, 'language');
  if ($userlang != "default") {
    $language = $userlang;
  }
}

// Ensure language is set
if (!strlen($language)) {
  $language = 'english';
}

// Initialize language system (works for both logged-in and public users)
LanguageLoader::initialize($language);

// Load language files based on configuration
if (defined('USE_SPLIT_LANGUAGE_FILES') && USE_SPLIT_LANGUAGE_FILES && 
    file_exists(WEBSITE_ROOT . '/languages/' . $language . '/core.php')) {
  // Use new split file system - language loading happens per controller
  LanguageLoader::loadForController('core_only');
} else {
  // Fallback to legacy language files
  require_once WEBSITE_ROOT . '/languages/' . $language . '.php';     // Framework
  require_once WEBSITE_ROOT . '/languages/' . $language . '.app.php'; // Application
}

// Now that language is loaded, get login info
if (L_USER && (!isset($_GET['action']) || isset($_GET['action']) && $_GET['action'] != 'logout')) {
  $userData['loginInfo'] = loginInfo();
  //
  // Switch language via menu (only allowed when logged in)
  //
  if (isset($_GET['applang'])) {
    $appLang = sanitize($_GET['applang']);
    if (in_array($appLang, $appLanguages)) {
      $UO->save($luser, "language", $appLang);
      $pieces = explode('&', $_SERVER['QUERY_STRING']);
      array_pop($pieces); // remove the "applang=" piece, otherwise we will always get here and redirect
      $query = implode('&', $pieces);
      header("Location: " . $_SERVER['PHP_SELF'] . "?" . $query);
    }
  }
  //
  // Check for unconfirmed popup messages. If one or more, set controller to message view.
  //
  $messages = $MSG->getAllByUser($UL->username);
  foreach ($messages as $msg) {
    if ($msg['popup']) {
      $controller = 'messages';
      break;
    }
  }
}

//-----------------------------------------------------------------------------
// COMPARE LANGUAGES
// Set condition to true for debug
//
if (defined('COMPARE_LANGUAGES') && COMPARE_LANGUAGES) {
  // Automatically compare all available languages against English
  $errorData = LanguageLoader::compareAllLanguages();
  
  require_once WEBSITE_ROOT . '/views/error.php';
  die();
}

//-----------------------------------------------------------------------------
// LOAD LANGUAGE
//
if (!strlen($language)) {
  $language = 'english';
}

$AV = new Avatar($LANG);

//-----------------------------------------------------------------------------
// DETERMINE CONTROLLER
//
if ($allConfig['underMaintenance']) {
  $appStatus['maintenance'] = true;
  $controller = 'maintenance';
  if (isset($_GET['action'])) {
    $controller = sanitize($_GET['action']);
  }
  if ($userData['roleid'] != 1 && $controller != 'login') {
    $controller = 'maintenance';
  }
} else {
  if (L_USER) {
    if (!$controller = $allConfig['homepage']) {
      $controller = 'home';
    }
  } else {
    if (!$controller = $allConfig['defaultHomepage']) {
      $controller = 'home';
    }
  }
  if (isset($_GET['action'])) {
    $controller = sanitize($_GET['action']);
  }
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER-SPECIFIC LANGUAGE
//
// Phase 2: Load controller-specific language files for optimal performance
if (file_exists(WEBSITE_ROOT . '/languages/' . $language . '/core.php')) {
  $langStats = LanguageLoader::loadForController($controller);
  
  // Optional: Log performance statistics for debugging
  if (defined('DEBUG_LANGUAGE') && DEBUG_LANGUAGE) {
    error_log("Language loading stats for controller '$controller': " . 
              "Files: {$langStats['filesLoaded']}, " .
              "Keys: {$langStats['keysLoaded']}, " .
              "Memory reduction: {$langStats['memoryReduction']}%");
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$htmlData['title'] = $allConfig['appTitle'];
if (isset($CONF['controllers'][$controller])) {
  $htmlData['title'] = $allConfig['appTitle'] . ' - ' . $CONF['controllers'][$controller]->title;
}

$htmlData['description'] = $allConfig['appDescription'];
$htmlData['keywords'] = $allConfig['appKeywords'];
$htmlData['version'] = APP_VER;
$htmlData['author'] = APP_AUTHOR;
$htmlData['copyright'] = APP_COPYRIGHT;
$htmlData['license'] = APP_LICENSE;
$htmlData['locale'] = $LANG['locale'];
$htmlData['jQueryTheme'] = $allConfig['jqtheme'];
$htmlData['cookieConsent'] = (bool)$allConfig['cookieConsent'];
$htmlData['cookieConsentCDN'] = (bool)$allConfig['cookieConsentCDN'];
$htmlData['faCDN'] = (bool)$allConfig['faCDN'];
$htmlData['jQueryCDN'] = (bool)$allConfig['jQueryCDN'];

if ($allConfig['noIndex']) {
  $htmlData['robots'] = 'noindex,nofollow,noopd';
} else {
  $htmlData['robots'] = 'index,follow,noopd';
}
if (L_USER && (!isset($_GET['action']) || isset($_GET['action']) && $_GET['action'] != 'logout')) {
  $userData['loginInfo'] = loginInfo();
} else {
  $userData['loginInfo'] = $LANG['status_logged_out'];
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER
//
if ($allConfig['noCaching']) {
  // Ensure no caching
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  header("Expires: 0");
}
if (file_exists(WEBSITE_ROOT . '/controller/' . $controller . '.php')) {
  require_once WEBSITE_ROOT . '/controller/' . $controller . '.php';
} else {
  //
  // Controller not found
  //
  $alertData['type'] = 'danger';
  $alertData['title'] = $LANG['alert_danger_title'];
  $alertData['subject'] = $LANG['alert_controller_not_found_subject'];
  $alertData['text'] = str_replace('%1%', $controller, $LANG['alert_controller_not_found_text']);
  $alertData['help'] = $LANG['alert_controller_not_found_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
}
