<?php
declare(strict_types=1);

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
// Set the application environment in you .env file.
// Set to 'production' for production or 'dev' for debugging.
// If you don't have a .env file, copy the .env.example file to .env and adjust the values.
// Note: This can be overridden by the 'productionMode' system setting in the database.
//
$envProductionMode = (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'production');
define('PRODUCTION_MODE', $envProductionMode);

if (PRODUCTION_MODE) {
  error_reporting(0);
  ini_set('display_errors', '0');
}
else {
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
}

//-----------------------------------------------------------------------------
// Check if a session already exists
//
if (session_status() === PHP_SESSION_NONE) {
  $cookieParams = session_get_cookie_params();
  $isSecure     = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
  session_set_cookie_params([
    'lifetime' => $cookieParams['lifetime'],
    'path'     => $cookieParams['path'],
    'domain'   => $cookieParams['domain'],
    'secure'   => $isSecure,
    'httponly' => true,
    'samesite' => 'Strict'
  ]);
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
// LOAD ENVIRONMENT VARIABLES
//
if (file_exists(__DIR__ . '/.env')) {
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();
}

//-----------------------------------------------------------------------------
// IMPORT MODELS
//
use App\Core\Container;
use App\Core\Request;
use App\Core\Router;

use App\Models\AbsenceGroupModel;
use App\Models\AbsenceModel;
use App\Models\AllowanceModel;
use App\Models\AttachmentModel;
use App\Models\AvatarModel;
use App\Models\ConfigModel;
use App\Models\DaynoteModel;
use App\Models\DbModel;
use App\Models\GroupModel;
use App\Models\HolidayModel;
use App\Models\LogModel;
use App\Models\LoginModel;
use App\Models\MessageModel;
use App\Models\MonthModel;
use App\Models\PermissionModel;
use App\Models\RegionModel;
use App\Models\RoleModel;
use App\Models\TemplateModel;
use App\Models\UserAttachmentModel;
use App\Models\UserGroupModel;
use App\Models\UserMessageModel;
use App\Models\UserModel;
use App\Models\UserOptionModel;

//-----------------------------------------------------------------------------
// LOAD CONFIG
//
require_once WEBSITE_ROOT . '/config/config.db.php';
require_once WEBSITE_ROOT . '/config/config.controller.php';
require_once WEBSITE_ROOT . '/config/config.app.php';
require_once WEBSITE_ROOT . '/src/Helpers/LanguageLoader.php';
global $CONF;

//-----------------------------------------------------------------------------
// HELPERS
//
require_once WEBSITE_ROOT . '/src/Helpers/global.helper.php';
require_once WEBSITE_ROOT . '/src/Helpers/notification.helper.php';
require_once WEBSITE_ROOT . '/src/Helpers/view.helper.php';
require_once WEBSITE_ROOT . '/src/Helpers/app.helper.php';


//-----------------------------------------------------------------------------
// CHECK INSTALLATION SCRIPT
//
if (file_exists('installation.php')) {
  // Found installation script: Redirect to it
  header("Location: installation.php");
  die();
}
elseif (!APP_INSTALLED) {
  // App not installed but Installation.php not found
  echo '
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <title>TeamCal Neo Error</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="public/themes/bootstrap/bootstrap.min.css">
  </head>
  <body>
  <div class="container content" style="padding-left: 4px; padding-right: 4px;">
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
      <h2><strong>Application Error!</strong></h2>
      <hr>
      <h3>Installation script not found</h3>
      <p>The installation script "installation.php" does not exist in the root directory while "config/config.app.php" indicates that no installation has been performed yet.</p>
      <p>The application will not start until either one of the following has been resolved:</p>
      <ol>
        <li>Recover "installation.php" <i>(if the installation script has not been executed yet or was unsucessful)</i></li>
        <li>Set <code style="color: blue;">define[\'APP_INSTALLED\', "1"];</code> in "config/config.app.php" <i>(if the installation script has been executed and was successful)</i></li>
      </ol>
    </div>
  </div>
  </body>
  </html>';
  die();
}

//-----------------------------------------------------------------------------
// CLASS INSTANCES (Registered in Container)
//
$container = new Container();

// Configuration
$container->set('CONF', function () use ($CONF) {
  return $CONF;
});

$container->set('allConfig', function () {
  global $allConfig;
  return $allConfig;
});

$container->set('appLanguages', function () {
  global $appLanguages;
  return $appLanguages;
});

$container->set('logLanguages', function () {
  global $logLanguages;
  return $logLanguages;
});

$container->set('timezones', function () {
  global $timezones;
  return $timezones;
});

$container->set('appJqueryUIThemes', function () {
  global $appJqueryUIThemes;
  return $appJqueryUIThemes;
});

$container->set('faIcons', function () {
  global $faIcons;
  return $faIcons;
});

$container->set('alertData', function () {
  global $alertData;
  return $alertData;
});

$container->set('bsColors', function () {
  global $bsColors;
  return $bsColors;
});

$container->set('htmlData', function () {
  global $htmlData;
  return $htmlData;
});

$container->set('LANG', function () {
  global $LANG;
  return $LANG;
});

$container->set('TemplateEngine', function () {
  global $allConfig;
  $options = [
    'cache'       => WEBSITE_ROOT . '/temp/twig',
    'debug'       => !PRODUCTION_MODE,
    'auto_reload' => !PRODUCTION_MODE,
  ];
  return new App\Core\TemplateEngine(WEBSITE_ROOT . '/views', $options);
});

// Services
$container->set('AbsenceService', function ($c) {
  return new App\Services\AbsenceService(
    $c->get('AbsenceModel'),
    $c->get('AllowanceModel'),
    $c->get('ConfigModel'),
    $c->get('DaynoteModel'),
    $c->get('GroupModel'),
    $c->get('HolidayModel'),
    $c->get('MonthModel'),
    $c->get('TemplateModel'),
    $c->get('UserGroupModel'),
    $c->get('UserModel'),
    $c->get('UserLoggedIn'),
    $c->get('LANG')
  );
});

$container->set('UserService', function ($c) {
  return new App\Services\UserService(
    $c->get('UserModel'),
    $c->get('UserGroupModel'),
    $c->get('UserOptionModel'),
    $c->get('TemplateModel'),
    $c->get('DaynoteModel'),
    $c->get('AllowanceModel'),
    $c->get('UserMessageModel'),
    $c->get('LogModel'),
    $c->get('AbsenceGroupModel'),
    $c->get('AvatarModel')
  );
});

// Primary classes
$container->set('Request', function () {
  return new Request();
});

$container->set('DbModel', function ($c) use ($CONF) {
  $db = new DbModel($CONF['db_server'], $CONF['db_name'], $CONF['db_user'], $CONF['db_pass'], $CONF['db_port'] ?? null, $CONF['db_socket'] ?? null);
  if ($c->has('Cache')) {
    $db->setCache($c->get('Cache'));
  }
  return $db;
});

$container->set('Cache', function () {
  return new App\Core\Cache(WEBSITE_ROOT . '/cache');
});

$container->set('ConfigModel', function ($c) use ($CONF) {
  return new ConfigModel($CONF, $c->get('DbModel'), $c->get('Cache'));
});

// Secondary classes
$container->set('GroupModel', function ($c) use ($CONF) {
  return new GroupModel($c->get('DbModel')->db, $CONF);
});

$container->set('LoginModel', function ($c) use ($CONF) {
  return new LoginModel($c->get('ConfigModel'), $CONF);
});

$container->set('LogModel', function ($c) use ($CONF) {
  return new LogModel($c->get('DbModel')->db, $CONF, $c->get('ConfigModel'));
});

$container->set('MessageModel', function ($c) use ($CONF) {
  return new MessageModel($c->get('DbModel')->db, $CONF);
});

$container->set('PermissionModel', function ($c) use ($CONF) {
  return new PermissionModel($c->get('DbModel')->db, $CONF);
});

$container->set('RoleModel', function ($c) use ($CONF) {
  return new RoleModel($c->get('DbModel')->db, $CONF);
});

$container->set('UserModel', function ($c) use ($CONF) {
  return new UserModel($c->get('DbModel')->db, $CONF);
});

$container->set('UserGroupModel', function ($c) use ($CONF) {
  return new UserGroupModel($c->get('DbModel')->db, $CONF);
});

$container->set('UserLoggedIn', function ($c) use ($CONF) {
  return new UserModel($c->get('DbModel')->db, $CONF);
});

$container->set('UserMessageModel', function ($c) use ($CONF) {
  return new UserMessageModel($c->get('DbModel')->db, $CONF);
});

$container->set('UserOptionModel', function ($c) use ($CONF) {
  return new UserOptionModel($c->get('DbModel')->db, $CONF);
});

// Domain models
$container->set('AbsenceModel', function ($c) use ($CONF) {
  return new AbsenceModel($c->get('DbModel')->db, $CONF);
});

$container->set('AbsenceGroupModel', function ($c) use ($CONF) {
  return new AbsenceGroupModel($c->get('DbModel')->db, $CONF);
});

$container->set('AllowanceModel', function ($c) use ($CONF) {
  return new AllowanceModel($c->get('DbModel')->db, $CONF);
});

$container->set('DaynoteModel', function ($c) use ($CONF) {
  return new DaynoteModel($c->get('DbModel')->db, $CONF);
});

$container->set('HolidayModel', function ($c) use ($CONF) {
  return new HolidayModel($c->get('DbModel')->db, $CONF);
});

$container->set('MonthModel', function ($c) use ($CONF) {
  return new MonthModel($c->get('DbModel')->db, $CONF);
});

$container->set('RegionModel', function ($c) use ($CONF) {
  return new RegionModel($c->get('DbModel')->db, $CONF);
});

$container->set('TemplateModel', function ($c) use ($CONF) {
  return new TemplateModel($c->get('DbModel')->db, $CONF);
});

$container->set('AttachmentModel', function ($c) use ($CONF) {
  return new AttachmentModel($c->get('DbModel')->db, $CONF);
});

$container->set('UserAttachmentModel', function ($c) use ($CONF) {
  return new UserAttachmentModel($c->get('DbModel')->db, $CONF);
});

$container->set('AvatarModel', function () use ($CONF) {
  global $LANG;
  return new AvatarModel($LANG, $CONF);
});

// Retrieve instances for global legacy support (temporary)
$DB   = $container->get('DbModel');
$C    = $container->get('ConfigModel');
$G    = $container->get('GroupModel');
$L    = $container->get('LoginModel');
$LOG  = $container->get('LogModel');
$MSG  = $container->get('MessageModel');
$P    = $container->get('PermissionModel');
$RO   = $container->get('RoleModel');
$U    = $container->get('UserModel');
$UG   = $container->get('UserGroupModel');
$UL   = $container->get('UserLoggedIn');
$UMSG = $container->get('UserMessageModel');
$UO   = $container->get('UserOptionModel');
$A    = $container->get('AbsenceModel');
$AG   = $container->get('AbsenceGroupModel');
$AL   = $container->get('AllowanceModel');
$D    = $container->get('DaynoteModel');
$H    = $container->get('HolidayModel');
$M    = $container->get('MonthModel');
$R    = $container->get('RegionModel');
$T    = $container->get('TemplateModel');

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
require_once WEBSITE_ROOT . '/config/config.vars.php';
//
// Load all config records (global in controllers)
//
$allConfig = $C->readAll();

//
// Override production mode if database setting is enabled
//
if (isset($allConfig['productionMode']) && $allConfig['productionMode'] == '1') {
  //
  // Database setting overrides .env setting
  //
  if (!PRODUCTION_MODE) {
    //
    // Switch from dev to production mode
    //
    error_reporting(0);
    ini_set('display_errors', '0');
  }
}
elseif (isset($allConfig['productionMode']) && $allConfig['productionMode'] == '0') {
  //
  // Database explicitly disables production mode
  //
  if (PRODUCTION_MODE) {
    //
    // Switch from production to dev mode
    //
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
  }
}

$language                = $allConfig["defaultLanguage"];
$controller              = 'home';
$userData['isLoggedIn']  = false;
$userData['username']    = 'Public';
$userData['roleid']      = '3'; // 3 = Public
$userData['color']       = 'default';
$userData['avatar']      = 'default_male.png';
$userData['defaultMenu'] = $allConfig['defaultMenu'];
//
// Load all permissions into an array so there is no need to query the database for each permission
//
$permissions = $P->getPermissions($allConfig['permissionScheme']);
//
// Check login and make logged in username global
//
$luser = $L->checkLogin();
if ($luser) {
  define('L_USER', $luser);
}
else {
  define('L_USER', 0);
}
//
// If someone is logged in, overwrite defaults
//
if (L_USER) {
  $userData['isLoggedIn'] = true;
  //
  // Get the user
  //
  $UL->findByName($luser);
  //
  // Fill the user array
  //
  $userData['username'] = $UL->username;
  $userData['roleid']   = $UL->role;
  $userData['fullname'] = $UL->getFullname($UL->username);
  $userData['color']    = getRoleColor($UL->role);

  $userData['avatar'] = $UO->read($UL->username, 'avatar');
  if ($userData['avatar'] && !file_exists(APP_AVATAR_DIR . $userData['avatar'])) {
    $userData['avatar'] = 'default_' . $UO->read($UL->username, 'gender') . '.png';
  }

  $defaultMenu = $UO->read($UL->username, 'defaultMenu');
  if ($defaultMenu && $defaultMenu != 'default') {
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
\App\Helpers\LanguageLoader::initialize($language);


if (file_exists(WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php')) {
  \App\Helpers\LanguageLoader::loadForController('core_only');
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
    if ($msg['um_popup']) {
      $controller = 'messages';
      break;
    }
  }
}

//-----------------------------------------------------------------------------
// LOAD LANGUAGE
//
if (!strlen($language)) {
  $language = 'english';
}

$AV = new AvatarModel($LANG, $CONF);

//-----------------------------------------------------------------------------
// DETERMINE CONTROLLER
//
if ($allConfig['underMaintenance']) {
  $controller = 'maintenance';
  if (isset($_GET['action'])) {
    $controller = sanitize($_GET['action']);
  }
  if ($userData['roleid'] != 1 && $controller != 'login') {
    $controller = 'maintenance';
  }
}
else {
  if (L_USER) {
    if (!$controller = $allConfig['homepage']) {
      $controller = 'home';
    }
  }
  else {
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
if (file_exists(WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php')) {
  $langStats = \App\Helpers\LanguageLoader::loadForController($controller);
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$htmlData['title'] = $allConfig['appTitle'];
if (isset($CONF['controllers'][$controller])) {
  $htmlData['title'] = $allConfig['appTitle'] . ' - ' . $CONF['controllers'][$controller]->title;
}

$htmlData['description']   = $allConfig['appDescription'];
$htmlData['keywords']      = $allConfig['appKeywords'];
$htmlData['version']       = APP_VER;
$htmlData['author']        = APP_AUTHOR;
$htmlData['copyright']     = APP_COPYRIGHT;
$htmlData['license']       = APP_LICENSE;
$htmlData['locale']        = $LANG['locale'];
$htmlData['jQueryTheme']   = $allConfig['jqtheme'];
$htmlData['cookieConsent'] = (bool) $allConfig['cookieConsent'];


if ($allConfig['noIndex']) {
  $htmlData['robots'] = 'noindex,nofollow,noopd';
}
else {
  $htmlData['robots'] = 'index,follow,noopd';
}
if (L_USER && (!isset($_GET['action']) || isset($_GET['action']) && $_GET['action'] != 'logout')) {
  $userData['loginInfo'] = loginInfo();
}
else {
  $userData['loginInfo'] = $LANG['status_logged_out'];
}

//-----------------------------------------------------------------------------
// COMPARE LANGUAGES
// Set condition to true for debug
//
if (defined('COMPARE_LANGUAGES') && COMPARE_LANGUAGES) {
  // Automatically compare all available languages against English
  $errorData = \App\Helpers\LanguageLoader::compareAllLanguages();

  $view = $container->get('TemplateEngine');
  echo $view->render('error', [
    'errorData' => $errorData,
    'allConfig' => $allConfig,
    'htmlData'  => $htmlData,
    'userData'  => $userData,
    'CONF'      => $CONF,
    'LANG'      => $LANG,
  ]);
  die();
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

// Initialize Router
$router = new Router($container);

// Register Routes
$router->add('about', 'App\Controllers\AboutController');
$router->add('absenceedit', 'App\Controllers\AbsenceEditController');
$router->add('absenceicon', 'App\Controllers\AbsenceIconController');
$router->add('absences', 'App\Controllers\AbsencesController');
$router->add('absencesummary', 'App\Controllers\AbsenceSummaryController');
$router->add('absum', 'App\Controllers\AbsenceSummaryController');
$router->add('alert', 'App\Controllers\AlertController');
$router->add('attachments', 'App\Controllers\AttachmentsController');
$router->add('bulkedit', 'App\Controllers\BulkEditController');
$router->add('calendaredit', 'App\Controllers\CalendarEditController');
$router->add('calendaroptions', 'App\Controllers\CalendarOptionsController');
$router->add('calendarview', 'App\Controllers\CalendarViewController');
$router->add('config', 'App\Controllers\ConfigController');
$router->add('database', 'App\Controllers\DatabaseController');
$router->add('dataprivacy', 'App\Controllers\DataprivacyController');
$router->add('daynote', 'App\Controllers\DaynoteController');
$router->add('declination', 'App\Controllers\DeclinationController');
$router->add('groupcalendaredit', 'App\Controllers\GroupCalendarEditController');
$router->add('groupedit', 'App\Controllers\GroupEditController');
$router->add('groups', 'App\Controllers\GroupsController');
$router->add('holidayedit', 'App\Controllers\HolidayEditController');
$router->add('holidays', 'App\Controllers\HolidaysController');
$router->add('home', 'App\Controllers\HomeController');
$router->add('imprint', 'App\Controllers\ImprintController');
$router->add('log', 'App\Controllers\LogController');
$router->add('login', 'App\Controllers\LoginController');
$router->add('login2fa', 'App\Controllers\Login2faController');
$router->add('logout', 'App\Controllers\LogoutController');
$router->add('maintenance', 'App\Controllers\MaintenanceController');
$router->add('messageedit', 'App\Controllers\MessageEditController');
$router->add('messages', 'App\Controllers\MessagesController');
$router->add('monthedit', 'App\Controllers\MontheditController');
$router->add('passwordrequest', 'App\Controllers\PasswordRequestController');
$router->add('passwordreset', 'App\Controllers\PasswordResetController');
$router->add('patternadd', 'App\Controllers\PatternAddController');
$router->add('patternedit', 'App\Controllers\PatternEditController');
$router->add('patterns', 'App\Controllers\PatternsController');
$router->add('permissions', 'App\Controllers\PermissionsController');
$router->add('phpinfo', 'App\Controllers\PhpInfoController');
$router->add('regionedit', 'App\Controllers\RegionEditController');
$router->add('regions', 'App\Controllers\RegionsController');
$router->add('register', 'App\Controllers\RegisterController');
$router->add('remainder', 'App\Controllers\RemainderController');
$router->add('roleedit', 'App\Controllers\RoleEditController');
$router->add('roles', 'App\Controllers\RolesController');
$router->add('setup2fa', 'App\Controllers\Setup2faController');
$router->add('statsabsence', 'App\Controllers\StatsAbsenceController');
$router->add('statsabstype', 'App\Controllers\StatsAbstypeController');
$router->add('statspresence', 'App\Controllers\StatsPresenceController');
$router->add('statspresencetype', 'App\Controllers\StatsPresencetypeController');
$router->add('statsremainder', 'App\Controllers\StatsRemainderController');
$router->add('statistics', 'App\Controllers\StatisticsController');
$router->add('useradd', 'App\Controllers\UserAddController');
$router->add('useredit', 'App\Controllers\UserEditController');
$router->add('userimport', 'App\Controllers\UserImportController');
$router->add('userlist', 'App\Controllers\UserListController');
$router->add('verify', 'App\Controllers\VerifyController');
$router->add('viewprofile', 'App\Controllers\ViewProfileController');
$router->add('year', 'App\Controllers\YearController');

// Dispatch Request
try {
  $router->dispatch($controller);
} catch (Exception $e) {
  $alertData = [
    'type'    => 'danger',
    'title'   => isset($LANG['alert_danger_title']) ? $LANG['alert_danger_title'] : 'Error',
    'subject' => 'Application Error',
    'text'    => $e->getMessage(),
    'help'    => ''
  ];

  // Update alertData in container
  $container->set('alertData', function ($c) use ($alertData) {
    return $alertData;
  });

  try {
    $router->dispatch('alert');
  } catch (Exception $e2) {
    // If the alert controller itself fails, fallback to simple output
    echo "<h1>Application Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<hr>";
    echo "<p>Additionally, an error occurred while trying to display the error page:</p>";
    echo "<p>" . $e2->getMessage() . "</p>";
  }
}
