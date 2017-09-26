<?php
/**
 * config.controller.php
 * 
 * Application based parameters. Don't change anything in this file.
 *
 * @category TeamCal Neo 
 * @version 1.6.000
 * @author George Lewe
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

//=============================================================================
/**
 * CONTROLLER ARRAY
 * 
 * The controller class expects the following paramter upon instantiation:
 * 
 * <key> => new Controller (<name>, <faIcon>, <iconColor>, <panelColor>, <permission>, <title>)
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
 * <title>:
 * String to be displayed as the browser tab title
 *
 */
$CONF['controllers'] = array (
   //
   // LeAF Controllers (Lewe Application Framework)
   //
   'about' => new Controller('about', 'info-circle', 'info', 'default', '', 'About'),
   'attachments' => new Controller('attachments', 'upload', 'warning', 'primary', 'upload', 'Attachments'),
   'config' => new Controller('config', 'cog', 'default', 'primary', 'admin', 'Configuration'),
   'database' => new Controller('database', 'database', 'danger', 'danger', 'admin', 'Database'),
   'groups' => new Controller('groups', 'group', 'success', 'primary', 'groups', 'Groups'),
   'groupedit' => new Controller('groupedit', 'group', 'warning', 'danger', 'groups', 'Group Edit'),
   'home' => new Controller('home', 'home', 'primary', 'default', '', 'Home'),
   'imprint' => new Controller('imprint', 'file-text-o', 'default', 'default', '', 'Imprint'),
   'log' => new Controller('log', 'list-ol', 'info', 'info', 'admin', 'Log'),
   'login' => new Controller('login', 'sign-in', 'success', 'default', '', 'Login'),
   'logout' => new Controller('logout', 'sign-out', 'success', 'default', '', 'Logout'),
   'maintenance' => new Controller('maintenance', 'wrench', 'danger', 'danger', '', 'Maintenance'),
   'messages' => new Controller('messages', 'comments-o', 'info', 'info', 'messageview', 'Messages'),
   'messageedit' => new Controller('messageedit', 'comment-o', 'info', 'danger', 'messageedit', 'Message Edit'),
   'passwordrequest' => new Controller('passwordrequest', 'sign-in', 'success', 'primary', '', 'Password Request'),
   'passwordreset' => new Controller('passwordreset', 'lock', 'success', 'primary', '', 'Reset Password'),
   'permissions' => new Controller('permissions', 'lock', 'danger', 'primary', 'admin', 'Permissions'),
   'phpinfo' => new Controller('phpinfo', 'cogs', 'default', 'primary', 'admin', 'PHP Info'),
   'register' => new Controller('register', 'pencil', 'warning', 'success', '', 'Register'),
   'roles' => new Controller('roles', 'group', 'warning', 'primary', 'roles', 'Roles'),
   'roleedit' => new Controller('roleedit', 'edit', 'warning', 'danger', 'roles', 'Role Edit'),
   'users' => new Controller('users', 'user', 'primary', 'primary', 'admin', 'Users'),
   'useradd' => new Controller('useradd', 'edit', 'warning', 'warning', 'admin', 'User Add'),
   'useredit' => new Controller('useredit', 'edit', 'warning', 'danger', 'admin', 'User Edit'),
   'userimport' => new Controller('userimport', 'upload', 'warning', 'primary', 'admin', 'User Import'),
   'verify' => new Controller('verify', 'user', 'info', 'default', '', 'Verify'),
   'viewprofile' => new Controller('viewprofile', 'user', 'default', 'default', 'viewprofile', 'View Profile'),
   //
   // Application Controllers
   // Enter your application controllers below
   //
   'absences' => new Controller('absences', 'check-square-o', 'primary', 'primary', 'absenceedit', 'Absence Types'),
   'absenceedit' => new Controller('absenceedit', 'check-square-o', 'warning', 'danger', 'absenceedit', 'Absence Type Edit'),
   'absenceicon' => new Controller('absenceicon', 'file-image-o', 'primary', 'primary', 'absenceedit', 'Absence Type Icon'),
   'absum' => new Controller('absum', 'list-ol', 'primary', 'default', 'absum', 'Absence Summary'),
   'calendarview' => new Controller('calendarview', 'calendar', 'danger', 'primary', 'calendarview', 'Calendar View'),
   'calendaredit' => new Controller('calendaredit', 'calendar-o', 'danger', 'danger', 'calendaredit', 'Calendar Edit'),
   'calendaroptions' => new Controller('calendaroptions', 'wrench', 'primary', 'primary', 'calendaroptions', 'Calendar Options'),
   'daynote' => new Controller('daynote', 'sticky-note-o', 'info', 'info', 'daynote', 'Daynote'),
   'declination' => new Controller('declination', 'minus-circle', 'danger', 'danger', 'declination', 'Declination'),
   'groupcalendaredit' => new Controller('groupcalendaredit', 'calendar-o', 'info', 'danger', 'groupcalendaredit', 'Group Calendar Edit'),
   'holidays' => new Controller('holidays', 'calendar-o', 'danger', 'primary', 'holidays', 'Holidays'),
   'holidayedit' => new Controller('holidayedit', 'calendar-o', 'danger', 'danger', 'holidays', 'Holiday Edit'),
   'monthedit' => new Controller('monthedit', 'calendar-o', 'success', 'danger', 'regions', 'Month Edit'),
   'regions' => new Controller('regions', 'globe', 'success', 'primary', 'regions', 'Regions'),
   'regionedit' => new Controller('regionedit', 'globe', 'success', 'primary', 'regions', 'Region Edit'),
   'statsabsence' => new Controller('statsabsence', 'bar-chart', 'danger', 'default', 'statistics', 'Absence Statistics'),
   'statsabstype' => new Controller('statsabstype', 'bar-chart', 'info', 'default', 'statistics', 'Absence Type Statistics'),
   'statspresence' => new Controller('statspresence', 'bar-chart', 'success', 'default', 'statistics', 'Presence Statistics'),
   'statsremainder' => new Controller('statsremainder', 'bar-chart', 'warning', 'default', 'statistics', 'Remainder Statistics'),
   'tcpimport' => new Controller('tcpimport', 'upload', 'warning', 'warning', 'admin', 'TeamCal Pro Import'),
   'year' => new Controller('year', 'calendar', 'info', 'primary', 'calendarview', 'Year Calendar'),
);
?>