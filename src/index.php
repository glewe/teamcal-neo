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
  require_once 'classes/' . $class_name . '.class.php';
});

//-----------------------------------------------------------------------------
// LOAD CONFIG
//
require_once WEBSITE_ROOT . '/config/config.db.php';
require_once WEBSITE_ROOT . '/config/config.controller.php';
require_once WEBSITE_ROOT . '/config/config.app.php';
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
  if (!readDef('APP_INSTALLED', "config/config.app.php")) {
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
} elseif (!readDef('APP_INSTALLED', "config/config.app.php")) {
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
$showAlert = false;
$appTitle = $C->read('appTitle');
$language = $C->read("defaultLanguage");
$appStatus['maintenance'] = false;
$controller = 'home';
$userData['isLoggedIn'] = false;
$userData['username'] = 'Public';
$userData['roleid'] = '3'; // 3 = Public
$userData['color'] = 'default';
$userData['avatar'] = 'default_male.png';
$userData['defaultMenu'] = $C->read('defaultMenu');
//
// Load all permissions into an array so there is no need to query the database for each permission
//
$permissions = $P->getPermissions($C->read('permissionScheme'));
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
  if (!strlen($language)) {
    $language = 'english';
  }
  require_once WEBSITE_ROOT . '/languages/' . $language . '.php';     // Framework language
  require_once WEBSITE_ROOT . '/languages/' . $language . '.app.php'; // Application language

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
$checkLanguages = false;
if ($checkLanguages) {
  $lang1 = "english";
  $lang2 = "deutsch";

  require WEBSITE_ROOT . '/languages/' . $lang1 . '.php';     // Framework
  require WEBSITE_ROOT . '/languages/' . $lang1 . '.log.php'; // Log
  require WEBSITE_ROOT . '/languages/' . $lang1 . '.app.php'; // Application
  $lang1Array = $LANG;

  unset($LANG);

  require WEBSITE_ROOT . '/languages/' . $lang2 . '.php';     // Framework
  require WEBSITE_ROOT . '/languages/' . $lang2 . '.log.php'; // Log
  require WEBSITE_ROOT . '/languages/' . $lang2 . '.app.php'; // Application
  $lang2Array = $LANG;

  $errorData['title'] = 'Debug Info';
  $errorData['subject'] = '<h4>Language File Comparison</h4>';
  $errorData['text'] = '<p><strong>The following language keys exist in "' . $lang1 . '" but not in "' . $lang2 . ':</strong></p>';
  foreach ($lang1Array as $key => $val) {
    if (!array_key_exists($key, $lang2Array)) {
      $errorData['text'] .= '<p>[' . $key . ']</p>';
    }
  }

  $errorData['text'] .= '<p><strong>The following language keys exist in "' . $lang2 . '" but not in "' . $lang1 . ':</strong></p>';
  foreach ($lang2Array as $key => $val) {
    if (!array_key_exists($key, $lang1Array)) {
      $errorData['text'] .= '<p>[' . $key . ']</p>';
    }
  }
  require_once WEBSITE_ROOT . '/views/error.php';
  die();
}

//-----------------------------------------------------------------------------
// LOAD LANGUAGE
//
if (!strlen($language)) {
  $language = 'english';
}
require_once WEBSITE_ROOT . '/languages/' . $language . '.php';     // Framework
require_once WEBSITE_ROOT . '/languages/' . $language . '.app.php'; // Application
$AV = new Avatar($LANG);

//-----------------------------------------------------------------------------
// DETERMINE CONTROLLER
//
if ($C->read('underMaintenance')) {
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
    if (!$controller = $C->read("homepage")) {
      $controller = 'home';
    }
  } else {
    if (!$controller = $C->read("defaultHomepage")) {
      $controller = 'home';
    }
  }
  if (isset($_GET['action'])) {
    $controller = sanitize($_GET['action']);
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$htmlData['title'] = $C->read("appTitle");
if (isset($CONF['controllers'][$controller])) {
  $htmlData['title'] = $C->read("appTitle") . ' - ' . $CONF['controllers'][$controller]->title;
}

$htmlData['description'] = $C->read("appDescription");
$htmlData['keywords'] = $C->read("appKeywords");
$htmlData['version'] = APP_VER;
$htmlData['author'] = APP_AUTHOR;
$htmlData['copyright'] = APP_COPYRIGHT;
$htmlData['license'] = APP_LICENSE;
$htmlData['locale'] = $LANG['locale'];
$htmlData['jQueryTheme'] = $C->read("jqtheme");
$htmlData['cookieConsent'] = (bool)$C->read("cookieConsent");
$htmlData['cookieConsentCDN'] = (bool)$C->read("cookieConsentCDN");
$htmlData['faCDN'] = (bool)$C->read("faCDN");
$htmlData['jQueryCDN'] = (bool)$C->read("jQueryCDN");

if ($C->read("noIndex")) {
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
if ($C->read('noCaching')) {
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
