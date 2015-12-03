<?php
/**
 * config.app.php
 * 
 * Application based parameters. Don't change anything in this file.
 *
 * @category TeamCal Neo 
 * @version 0.3.00
 * @author George Lewe
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

//=============================================================================
/**
 * CONTROLLER ARRAY
 * 
 * The controller class expects the following paramter upon instantiation:
 * 
 * <key> => new Controller (<name>, <faIcon>, <iconColor>, <panelColor>, <permission>)
 * 
 * <key>:
 * Used in views/menu.php to access the controller instance for icon and 
 * color information. I recommend to use the same name as the controller.
 * 
 * <name>:
 * Controller name. Must be the same as the file name in the controllers folder.
 * 
 * <faIcon>:
 * Name of the Fontawesome icon. Available Fontawesome icons can be checked here 
 * http://fortawesome.github.io/Font-Awesome/icons/. Remove prefix "fa_" from the
 * icon name and enter the rest here.
 *
 * <iconColor>:
 * Bootstrap text color to be used for the icon in the menu. See color names below.
 * Bootstrap color names:
 * - default (gray)
 * - primary (blue)
 * - info (cyan)
 * - success (green)
 * - warning (orange)
 * - danger (red)
 *
 * <panelColor>:
 * Bootstrap panel color for the controller page. See color names above.
 *
 * <permission>:
 * The name of the permission that a user role must have to access this controller.
 * You can assign the same name for several controllers to group them.
 * If you set a permission it is shown on the permission page where you can assign
 * it to roles.
 * For each permission you use here you need two language entries that will be used
 * on the permission page. Example: For the permission "admin" you need:
 * $LANG['perm_admin_title'] = 'Administration'; 
 * $LANG['perm_admin_desc'] = 'Allows access to the dministration pages.'; 
 *   
 */
$CONF['controllers'] = array (
   'about' => new Controller('about', 'info-circle', 'info', 'default', ''),
   'absences' => new Controller('absences', 'check-square-o', 'primary', 'primary', 'absencetypes'),
   'absenceedit' => new Controller('absenceedit', 'check-square-o', 'warning', 'warning', 'absencetypes'),
   'absenceicon' => new Controller('absenceicon', 'file-image-o', 'primary', 'primary', 'absencetypes'),
   'calendarview' => new Controller('calendarview', 'calendar', 'danger', 'primary', 'calendarview'),
   'calendaredit' => new Controller('calendaredit', 'calendar-o', 'danger', 'primary', 'calendaredit'),
   'calendaroptions' => new Controller('calendaroptions', 'wrench', 'primary', 'primary', 'calendaroptions'),
   'config' => new Controller('config', 'cog', 'default', 'primary', 'admin'),
   'database' => new Controller('database', 'database', 'danger', 'danger', 'admin'),
   'declination' => new Controller('declination', 'minus-circle', 'danger', 'danger', 'declination'),
   'groups' => new Controller('groups', 'group', 'primary', 'primary', 'groups'),
   'groupedit' => new Controller('groupedit', 'group', 'warning', 'warning', 'groups'),
   'holidays' => new Controller('holidays', 'calendar-o', 'danger', 'primary', 'holidays'),
   'holidayedit' => new Controller('holidayedit', 'calendar-o', 'danger', 'primary', 'holidays'),
   'home' => new Controller('home', 'home', 'primary', 'default', ''),
   'imprint' => new Controller('imprint', 'file-text-o', 'default', 'default', ''),
   'log' => new Controller('log', 'list-ol', 'info', 'default', 'admin'),
   'login' => new Controller('login', 'sign-in', 'success', 'default', ''),
   'maintenance' => new Controller('maintenance', 'wrench', 'danger', 'danger', ''),
   'messages' => new Controller('messages', 'comments-o', 'info', 'default', 'messageview'),
   'messageedit' => new Controller('messageedit', 'comment-o', 'warning', 'warning', 'messageedit'),
   'monthedit' => new Controller('monthedit', 'calendar-o', 'success', 'primary', 'regions'),
   'permissions' => new Controller('permissions', 'lock', 'warning', 'danger', 'admin'),
   'phpinfo' => new Controller('phpinfo', 'cogs', 'danger', 'danger', 'admin'),
   'regions' => new Controller('regions', 'globe', 'success', 'primary', 'regions'),
   'regionedit' => new Controller('regionedit', 'globe', 'success', 'primary', 'regions'),
   'register' => new Controller('register', 'pencil', 'warning', 'success', ''),
   'roles' => new Controller('roles', 'group', 'warning', 'primary', 'roles'),
   'roleedit' => new Controller('roleedit', 'edit', 'warning', 'warning', 'roles'),
   'statistics' => new Controller('statistics', 'bar-chart', 'warning', 'default', 'statistics'),
   'users' => new Controller('users', 'user', 'primary', 'primary', 'useradmin'),
   'useredit' => new Controller('useredit', 'edit', 'warning', 'warning', 'useredit'),
   'useradd' => new Controller('useradd', 'edit', 'warning', 'warning', 'useradmin'),
   'viewprofile' => new Controller('viewprofile', 'user', 'default', 'default', 'viewprofile'),
   'year' => new Controller('year', 'calendar', 'info', 'primary', 'calendarview'),
);

//=============================================================================
/**
 * COOKIE NAME
 * 
 * The cookie prefix to be used on the browser client's device
 */
$CONF['cookie_name'] = 'tcneo2';

//=============================================================================
/**
 * DATABASE
 * 
 * Enter your database parameter here
 */
$CONF['db_server'] = "db527920860.db.1and1.com";
$CONF['db_name'] = "db527920860";
$CONF['db_user'] = "dbo527920860";
$CONF['db_pass'] = "jdHhCYWU";
$CONF['db_table_prefix'] = "";

$CONF['db_server'] = "localhost";
$CONF['db_name'] = "tcneo";
$CONF['db_user'] = "root";
$CONF['db_pass'] = "";
$CONF['db_table_prefix'] = "";

/**
 * The ID array is used to create the table names below.
 */
$tableIDs = array (
   'absences',
   'absence_group',
   'allowances',
   'config',
   'daynotes',
   'holidays',
   'groups',
   'log',
   'messages',
   'months',
   'permissions',
   'regions',
   'roles',
   'templates',
   'users',
   'user_message',
   'user_group',
   'user_option',
);

/**
 * Table names, example:
 * $CONF['db_table_config'] = "myprefix_config";
 */
foreach ($tableIDs as $tid)
{
   $confIndex = 'db_table_' . $tid;
   $confArchiveIndex = 'db_table_archive_' . $tid;
   $CONF[$confIndex] = $CONF['db_table_prefix'] . $tid;
   $CONF[$confArchiveIndex] = $CONF['db_table_prefix'] . 'archive_' . $tid;
}

//=============================================================================
/**
 * ENCRYPTION
 * 
 * Salt is a string that is used encrypt the passwords. You can change salt
 * to any other 9 char string.
 */
$CONF['salt'] = 's7*9fgJ#R';

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
$CONF['LDAP_YES'] = 0; // Use LDAP authentication
$CONF['LDAP_ADS'] = 0; // Set to 1 when authenticating against an Active Directory
$CONF['LDAP_HOST'] = "ldap.mydomain.com"; // LDAP host name
$CONF['LDAP_PORT'] = "389"; // LDAP port
$CONF['LDAP_PASS'] = 'XXXXXXXX'; // SA associated password
$CONF['LDAP_DIT'] = "cn=<service account>,ou=fantastic_four,ou=superheroes,dc=marvel,dc=comics"; // Directory Information Tree (Relative Distinguished Name)
$CONF['LDAP_SBASE'] = "ou=superheroes,ou=characters,dc=marvel,dc=comics"; // Search base, location in the LDAP dirctory to search
$CONF['LDAP_TLS'] = 0; // To avoid "Undefined index: LDAP_TLS" error message for LDAP bind to Active Directory

//=============================================================================
/**
 * PATHS
 */
$CONF['app_avatar_dir'] = 'upload/avatar';
$CONF['app_homepage_dir'] = 'upload/homepage';
$CONF['app_jqueryui_dir'] = 'js/jquery/ui/1.10.4/';

//=============================================================================
/**
 * PRODUCT, AUTHOR, COPYRIGHT, LICENSE INFORMATION
 * 
 * !Do not change this information. It is protected by the license agreement!
 */
$CONF['app_name'] = "TeamCal Neo";
$CONF['app_version'] = "0.3.00";
$CONF['app_help_root'] = "https://georgelewe.atlassian.net/wiki/display/CP10/";
$CONF['app_version_date'] = "2015-02-07";
$CONF['app_year'] = "2014";
$CONF['app_curr_year'] = date('Y');
$CONF['app_author'] = "George Lewe";
$CONF['app_author_url'] = "http://www.lewe.com";
$CONF['app_author_email'] = "george@lewe.com";
$CONF['app_copyright'] = "&copy; " . $CONF['app_year'] . "-" . $CONF['app_curr_year'] . " by <a href=\"mailto:" . $CONF['app_author_email'] . "?subject=" . $CONF['app_name'] . "&nbsp;" . $CONF['app_version'] . "\" class=\"copyright\">" . $CONF['app_author'] . "</a>.";
$CONF['app_copyright_html'] = "(c) " . $CONF['app_year'] . "-" . $CONF['app_curr_year'] . " by " . $CONF['app_author'] . " (" . $CONF['app_author_url'] . ")";
$CONF['app_powered_by'] = "Powered by " . $CONF['app_name'] . " " . $CONF['app_version'] . " &copy; " . $CONF['app_year'] . "-" . $CONF['app_curr_year'] . " by <a href=\"http://www.lewe.com\" class=\"copyright\" target=\"_blank\">" . $CONF['app_author'] . "</a>";
$CONF['app_license_html'] = "This program is under development and cannot be licensed yet. Redistribution is not allowed.

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTIBILITY or FITNESS FOR A PARTICULAR PURPOSE.\n";
?>