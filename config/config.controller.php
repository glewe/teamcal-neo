<?php
/**
 * config.controller.php
 * 
 * Application based parameters. Don't change anything in this file.
 *
 * @category TeamCal Neo 
 * @version 0.4.001
 * @author George Lewe
 * @copyright Copyright (c) 2014-2016 by George Lewe
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
 * $LANG['perm_admin_desc'] = 'Allows access to the administration pages.'; 
 *   
 */
$CONF['controllers'] = array (
   //
   // LeAF Controllers
   //
   'about' => new Controller('about', 'info-circle', 'info', 'default', ''),
   'config' => new Controller('config', 'cog', 'default', 'primary', 'admin'),
   'configapp' => new Controller('configapp', 'cog', 'success', 'success', 'admin'),
   'database' => new Controller('database', 'database', 'danger', 'danger', 'admin'),
   'groups' => new Controller('groups', 'group', 'success', 'primary', 'groups'),
   'groupedit' => new Controller('groupedit', 'group', 'warning', 'danger', 'groups'),
   'home' => new Controller('home', 'home', 'primary', 'default', ''),
   'imprint' => new Controller('imprint', 'file-text-o', 'default', 'default', ''),
   'log' => new Controller('log', 'list-ol', 'info', 'info', 'admin'),
   'login' => new Controller('login', 'sign-in', 'success', 'default', ''),
   'maintenance' => new Controller('maintenance', 'wrench', 'danger', 'danger', ''),
   'messages' => new Controller('messages', 'comments-o', 'info', 'default', 'messageview'),
   'messageedit' => new Controller('messageedit', 'comment-o', 'danger', 'danger', 'messageedit'),
   'permissions' => new Controller('permissions', 'lock', 'danger', 'danger', 'admin'),
   'phpinfo' => new Controller('phpinfo', 'cogs', 'default', 'default', 'admin'),
   'register' => new Controller('register', 'pencil', 'warning', 'success', ''),
   'roles' => new Controller('roles', 'group', 'warning', 'primary', 'roles'),
   'roleedit' => new Controller('roleedit', 'edit', 'warning', 'danger', 'roles'),
   'upload' => new Controller('upload', 'upload', 'warning', 'warning', 'upload'),
   'users' => new Controller('users', 'user', 'primary', 'primary', 'admin'),
   'useredit' => new Controller('useredit', 'edit', 'warning', 'warning', 'admin'),
   'useradd' => new Controller('useradd', 'edit', 'warning', 'warning', 'admin'),
   'verify' => new Controller('verify', 'user', 'info', 'default', ''),
   'viewprofile' => new Controller('viewprofile', 'user', 'default', 'default', 'viewprofile'),
   //
   // Application Controllers
   // Enter your application controllers below
   //
   'absences' => new Controller('absences', 'check-square-o', 'primary', 'primary', 'absenceedit'),
   'absenceedit' => new Controller('absenceedit', 'check-square-o', 'warning', 'warning', 'absenceedit'),
   'absenceicon' => new Controller('absenceicon', 'file-image-o', 'primary', 'primary', 'absenceedit'),
   'absum' => new Controller('absum', 'list-ol', 'primary', 'primary', 'absum'),
   'calendarview' => new Controller('calendarview', 'calendar', 'danger', 'primary', 'calendarview'),
   'calendaredit' => new Controller('calendaredit', 'calendar-o', 'danger', 'primary', 'calendaredit'),
   'calendaroptions' => new Controller('calendaroptions', 'wrench', 'primary', 'primary', 'calendaroptions'),
   'declination' => new Controller('declination', 'minus-circle', 'danger', 'danger', 'declination'),
   'holidays' => new Controller('holidays', 'calendar-o', 'danger', 'primary', 'holidays'),
   'holidayedit' => new Controller('holidayedit', 'calendar-o', 'danger', 'primary', 'holidays'),
   'monthedit' => new Controller('monthedit', 'calendar-o', 'success', 'primary', 'regions'),
   'regions' => new Controller('regions', 'globe', 'success', 'primary', 'regions'),
   'regionedit' => new Controller('regionedit', 'globe', 'success', 'primary', 'regions'),
   'statsabsence' => new Controller('statsabsence', 'bar-chart', 'danger', 'default', 'statistics'),
   'statsabstype' => new Controller('statsabstype', 'bar-chart', 'info', 'default', 'statistics'),
   'statspresence' => new Controller('statspresence', 'bar-chart', 'success', 'default', 'statistics'),
   'statsremainder' => new Controller('statsremainder', 'bar-chart', 'warning', 'default', 'statistics'),
   'year' => new Controller('year', 'calendar', 'info', 'primary', 'calendarview'),
);
?>