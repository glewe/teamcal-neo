<?php
/**
 * config.controller.php
 * 
 * Application based parameters. Don't change anything in this file.
 *
 * @category TeamCal Neo 
 * @version 0.9.003
 * @author George Lewe
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
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
   // LeAF Controllers (Lewe Application Framework)
   //
   'about' => new Controller('about', 'info-circle', 'info', 'default', ''),
   'attachments' => new Controller('attachments', 'upload', 'warning', 'primary', 'upload'),
   'config' => new Controller('config', 'cog', 'default', 'primary', 'admin'),
   'configapp' => new Controller('configapp', 'cog', 'success', 'success', 'admin'),
   'database' => new Controller('database', 'database', 'danger', 'danger', 'admin'),
   'groups' => new Controller('groups', 'group', 'success', 'primary', 'groups'),
   'groupedit' => new Controller('groupedit', 'group', 'warning', 'danger', 'groups'),
   'home' => new Controller('home', 'home', 'primary', 'default', ''),
   'imprint' => new Controller('imprint', 'file-text-o', 'default', 'default', ''),
   'log' => new Controller('log', 'list-ol', 'info', 'info', 'admin'),
   'login' => new Controller('login', 'sign-in', 'success', 'default', ''),
   'logout' => new Controller('logout', 'sign-out', 'success', 'default', ''),
   'maintenance' => new Controller('maintenance', 'wrench', 'danger', 'danger', ''),
   'messages' => new Controller('messages', 'comments-o', 'info', 'info', 'messageview'),
   'messageedit' => new Controller('messageedit', 'comment-o', 'info', 'danger', 'messageedit'),
   'permissions' => new Controller('permissions', 'lock', 'danger', 'primary', 'admin'),
   'phpinfo' => new Controller('phpinfo', 'cogs', 'default', 'primary', 'admin'),
   'register' => new Controller('register', 'pencil', 'warning', 'success', ''),
   'roles' => new Controller('roles', 'group', 'warning', 'primary', 'roles'),
   'roleedit' => new Controller('roleedit', 'edit', 'warning', 'danger', 'roles'),
   'users' => new Controller('users', 'user', 'primary', 'primary', 'admin'),
   'useredit' => new Controller('useredit', 'edit', 'warning', 'danger', 'admin'),
   'useradd' => new Controller('useradd', 'edit', 'warning', 'warning', 'admin'),
   'verify' => new Controller('verify', 'user', 'info', 'default', ''),
   'viewprofile' => new Controller('viewprofile', 'user', 'default', 'default', 'viewprofile'),
   //
   // Application Controllers
   // Enter your application controllers below
   //
   'absences' => new Controller('absences', 'check-square-o', 'primary', 'primary', 'absenceedit'),
   'absenceedit' => new Controller('absenceedit', 'check-square-o', 'warning', 'danger', 'absenceedit'),
   'absenceicon' => new Controller('absenceicon', 'file-image-o', 'primary', 'primary', 'absenceedit'),
   'absum' => new Controller('absum', 'list-ol', 'primary', 'default', 'absum'),
   'calendarview' => new Controller('calendarview', 'calendar', 'danger', 'primary', 'calendarview'),
   'calendaredit' => new Controller('calendaredit', 'calendar-o', 'danger', 'danger', 'calendaredit'),
   'calendaroptions' => new Controller('calendaroptions', 'wrench', 'primary', 'primary', 'calendaroptions'),
   'daynote' => new Controller('daynote', 'sticky-note-o', 'info', 'info', 'daynote'),
   'declination' => new Controller('declination', 'minus-circle', 'danger', 'danger', 'declination'),
   'holidays' => new Controller('holidays', 'calendar-o', 'danger', 'primary', 'holidays'),
   'holidayedit' => new Controller('holidayedit', 'calendar-o', 'danger', 'danger', 'holidays'),
   'monthedit' => new Controller('monthedit', 'calendar-o', 'success', 'danger', 'regions'),
   'regions' => new Controller('regions', 'globe', 'success', 'primary', 'regions'),
   'regionedit' => new Controller('regionedit', 'globe', 'success', 'primary', 'regions'),
   'statsabsence' => new Controller('statsabsence', 'bar-chart', 'danger', 'default', 'statistics'),
   'statsabstype' => new Controller('statsabstype', 'bar-chart', 'info', 'default', 'statistics'),
   'statspresence' => new Controller('statspresence', 'bar-chart', 'success', 'default', 'statistics'),
   'statsremainder' => new Controller('statsremainder', 'bar-chart', 'warning', 'default', 'statistics'),
   'tcpimport' => new Controller('tcpimport', 'upload', 'warning', 'warning', 'admin'),
   'year' => new Controller('year', 'calendar', 'info', 'primary', 'calendarview'),
);
?>