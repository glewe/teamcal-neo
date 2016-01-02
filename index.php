<?php
/**
 * index.php
 * 
 * @category TeamCal Neo 
 * @version 0.4.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license
 */

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

//=============================================================================
//
// DEFINES
//
define('VALID_ROOT', 1);
define('WEBSITE_ROOT', __DIR__);
$fullURL = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$pos = strrpos($fullURL,'/');
define('WEBSITE_URL', substr($fullURL,0,$pos)); //Remove trailing slash

//=============================================================================
//
// CLASS AUTOLOADER
// The autoloader function makes sure that whenever a class is instantiated that 
// the appropriate class is included if not already.
//
function my_autoloader($class) 
{
   include 'classes/' . $class . '.class.php';
}
spl_autoload_register('my_autoloader');

//=============================================================================
//
// LOAD CONFIG
//
require_once (WEBSITE_ROOT . '/config/config.db.php');
require_once (WEBSITE_ROOT . '/config/config.controller.php');
require_once (WEBSITE_ROOT . '/config/config.app.php');

//=============================================================================
//
// CLASS INSTANCES
//

// Instantiate primary classes (used by other classes)
//
$DB  = new DB($CONF['db_server'], $CONF['db_name'], $CONF['db_user'], $CONF['db_pass']);
$C   = new Config();

//
// Instantiate secondary classes
//
$AV   = new Avatar();
$G    = new Groups();
$L    = new Login();
$LOG  = new Log();
$MSG  = new Messages();
$P    = new Permissions();
$R    = new Regions();
$RO   = new Roles();
$U    = new Users();
$UG   = new UserGroup();
$UMSG = new UserMessage();
$UO   = new UserOption();

//
// Custom classes
//
$A    = new Absences();
$AG   = new AbsenceGroup();
$AL   = new Allowances();
$D    = new Daynotes();
$H    = new Holidays();
$M    = new Months();
$T    = new Templates();
$UL   = new Users(); // For the currently logged in user

//=============================================================================
//
// HELPERS
//

//
// LeAF Helpers
//
require_once (WEBSITE_ROOT . '/helpers/global.helper.php');
require_once (WEBSITE_ROOT . '/helpers/model.helper.php');
require_once (WEBSITE_ROOT . '/helpers/notification.helper.php');
require_once (WEBSITE_ROOT . '/helpers/view.helper.php');

//
// Custom helpers
//
require_once (WEBSITE_ROOT . '/helpers/app.helper.php');

//=============================================================================
//
// VARIABLE DEFAULTS
//
require_once (WEBSITE_ROOT . '/config/config.vars.php');
$showAlert = false;
$appTitle = $C->read('appTitle');
$language = $C->read("defaultLanguage");
$appStatus['maintenance'] = false;
$controller = 'home';
$userData['isLoggedIn'] = false;
$userData['username'] = 'Public';
$userData['roleid'] = '3'; // 3 = Public
$userData['color'] = 'default';
$userData['avatar'] = 'noavatar_male.png';

//
// If someone is logged in, overwrite defaults
//
if ($luser = $L->checkLogin() AND (isset($_GET['action']) AND $_GET['action'] != 'logout'))
{
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
   
   if (!$userData['avatar']=$UO->read($UL->username, 'avatar'))
   {
      if ($UO->read($UL->username, 'gender') == "female") $userData['avatar'] = 'noavatar_female.png';
   }
   
   $userlang = $UO->read($UL->username, 'language');
   if ($userlang != "default") $language = $userlang;
   
   $usertheme = $UO->read($UL->username, 'theme');
   if ($usertheme != "default") $theme = $usertheme;
    
   //
   // Switch language via menu (only allowed when logged in) 
   //
   if (isset($_GET['applang']))
   {
      $appLang = sanitize($_GET['applang']);
      if (in_array($appLang, $appLanguages))
      {
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
   foreach ($messages as $msg)
   {
      if ($msg['popup']) 
      {
         $controller = 'messages';
         break;
      }
   }
}

//=============================================================================
//
// LOAD LANGUAGE
//
require_once (WEBSITE_ROOT . '/languages/' . $language . '.php');     // Framework
require_once (WEBSITE_ROOT . '/languages/' . $language . '.app.php'); // Application

//=============================================================================
//
// DETERMINE CONTROLLER
//
if (isset($_GET['action']))
{
   $action = sanitize($_GET['action']);
   if ($action == 'logout')
   {
      $L->logout();
      $LOG->log("logLogin", $L->checkLogin(), "log_logout");
      header("Location: " . $_SERVER['PHP_SELF']);
      die();
   }
   else
   {
      $controller = $_GET['action'];
   }
}

if ($C->read('underMaintenance'))
{
   $appStatus['maintenance'] = true;
   if ($luser != 'admin' AND $controller != 'login') $controller = 'maintenance';
}

//=============================================================================
//
// PREPARE VIEW
//
$htmlData['title'] = $C->read("appTitle");
$htmlData['description'] = $C->read("appDescription");
$htmlData['version'] = $CONF['app_version'];
$htmlData['author'] = $CONF['app_author'];
$htmlData['copyright'] = $CONF['app_copyright'];
$htmlData['license'] = $CONF['app_license'];
$htmlData['theme'] = getTheme();
$htmlData['jQueryTheme'] = $C->read("jqtheme");
if ($C->read("faCDN")) $htmlData['faCDN'] = true; else $htmlData['faCDN'] = false; 
if ($C->read("jQueryCDN")) $htmlData['jQueryCDN'] = true; else $htmlData['jQueryCDN'] = false; 

$userData['loginInfo'] = loginInfo();

//=============================================================================
//
// LOAD CONTROLLER
//
if (file_exists(WEBSITE_ROOT . '/controller/' . $controller . '.php'))
{
   include (WEBSITE_ROOT . '/controller/' . $controller . '.php');
}
else
{
   //
   // Controller not found
   //
   $alertData['type'] = 'danger';
   $alertData['title'] = $LANG['alert_danger_title'];
   $alertData['subject'] = $LANG['alert_controller_not_found_subject'];
   $alertData['text'] = str_replace('%1%', $controller, $LANG['alert_controller_not_found_text']);
   $alertData['help'] = $LANG['alert_controller_not_found_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
}
?>
