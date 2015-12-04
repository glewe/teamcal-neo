<?php
/**
 * config.controller.php
 * 
 * Application based parameters. Don't change anything in this file.
 *
 * @category TeamCal Neo 
 * @version 0.3.003
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

?>