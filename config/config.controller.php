<?php
/**
 * config.controller.php
 * 
 * Application based parameters. Don't change anything in this file.
 *
 * @category TeamCal Neo 
 * @version 1.9.000
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
   'about' => new Controller('about', 'info-circle', 'info', 'default', '', 'About', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'attachments' => new Controller('attachments', 'upload', 'warning', 'primary', 'upload', 'Attachments', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138579/Attachments'),
   'config' => new Controller('config', 'cog', 'default', 'primary', 'admin', 'Configuration', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138537/Framework+Configuration'),
   'database' => new Controller('database', 'database', 'danger', 'danger', 'admin', 'Database', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990477/Database+Management'),
   'groups' => new Controller('groups', 'group', 'success', 'primary', 'groups', 'Groups', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138585/Groups'),
   'groupedit' => new Controller('groupedit', 'group', 'warning', 'danger', 'groups', 'Group Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138585/Groups'),
   'home' => new Controller('home', 'home', 'primary', 'default', '', 'Home', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'imprint' => new Controller('imprint', 'file-text-o', 'default', 'default', '', 'Imprint', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'log' => new Controller('log', 'list-ol', 'info', 'info', 'admin', 'Log', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990489/System+Log'),
   'login' => new Controller('login', 'sign-in', 'success', 'default', '', 'Login', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'logout' => new Controller('logout', 'sign-out', 'success', 'default', '', 'Logout', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'maintenance' => new Controller('maintenance', 'wrench', 'danger', 'danger', '', 'Maintenance', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'messages' => new Controller('messages', 'comments-o', 'info', 'info', 'messageview', 'Messages', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990535/Messages+View'),
   'messageedit' => new Controller('messageedit', 'comment-o', 'info', 'danger', 'messageedit', 'Message Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990546/Messages+Create'),
   'passwordrequest' => new Controller('passwordrequest', 'sign-in', 'success', 'primary', '', 'Password Request', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'passwordreset' => new Controller('passwordreset', 'lock', 'success', 'primary', '', 'Reset Password', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'permissions' => new Controller('permissions', 'lock', 'danger', 'primary', 'admin', 'Permissions', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138573/Permissions'),
   'phpinfo' => new Controller('phpinfo', 'cogs', 'default', 'primary', 'admin', 'PHP Info', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990491/PHP+Info'),
   'register' => new Controller('register', 'pencil', 'warning', 'success', '', 'Register', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'roles' => new Controller('roles', 'group', 'warning', 'primary', 'roles', 'Roles', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138587/Roles'),
   'roleedit' => new Controller('roleedit', 'edit', 'warning', 'danger', 'roles', 'Role Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138587/Roles'),
   'users' => new Controller('users', 'user', 'primary', 'primary', 'admin', 'Users', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138582/Users'),
   'useradd' => new Controller('useradd', 'edit', 'warning', 'warning', 'admin', 'User Add', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138582/Users'),
   'useredit' => new Controller('useredit', 'edit', 'warning', 'danger', 'useredit', 'User Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/142180353/User+Edit'),
   'userimport' => new Controller('userimport', 'upload', 'warning', 'primary', 'admin', 'User Import', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/80892338/User+Import'),
   'verify' => new Controller('verify', 'user', 'info', 'default', '', 'Verify', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'viewprofile' => new Controller('viewprofile', 'user', 'default', 'default', 'viewprofile', 'View Profile', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/151519291/View+Profile'),
   //
   // Application Controllers
   // Enter your application controllers below
   //
   'absences' => new Controller('absences', 'check-square-o', 'primary', 'primary', 'absenceedit', 'Absence Types', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990493/Absence+Types'),
   'absenceedit' => new Controller('absenceedit', 'check-square-o', 'warning', 'danger', 'absenceedit', 'Absence Type Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990495/Absence+Type+Edit'),
   'absenceicon' => new Controller('absenceicon', 'file-image-o', 'primary', 'primary', 'absenceedit', 'Absence Type Icon', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990495/Absence+Type+Edit'),
   'absum' => new Controller('absum', 'list-ol', 'primary', 'default', 'absum', 'Absence Summary', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990567/User+Absence+Summary'),
   'calendarview' => new Controller('calendarview', 'calendar', 'danger', 'primary', 'calendarview', 'Calendar View', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990571/View+Calendar+Month'),
   'calendaredit' => new Controller('calendaredit', 'calendar-o', 'danger', 'danger', 'calendaredit', 'Calendar Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990577/Edit+User+Calendar+Month'),
   'calendaroptions' => new Controller('calendaroptions', 'wrench', 'primary', 'primary', 'calendaroptions', 'Calendar Options', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138565/Calendar+Options'),
   'daynote' => new Controller('daynote', 'sticky-note-o', 'info', 'info', 'daynote', 'Daynote', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/57442318/Edit+Daynotes'),
   'declination' => new Controller('declination', 'minus-circle', 'danger', 'danger', 'declination', 'Declination', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/72089642/Declination+Management'),
   'groupcalendaredit' => new Controller('groupcalendaredit', 'calendar-o', 'info', 'danger', 'groupcalendaredit', 'Group Calendar Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/82122981/Edit+Group+Calendar+Month'),
   'holidays' => new Controller('holidays', 'calendar-o', 'danger', 'primary', 'holidays', 'Holidays', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990497/Holidays'),
   'holidayedit' => new Controller('holidayedit', 'calendar-o', 'danger', 'danger', 'holidays', 'Holiday Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990503/Holiday+Edit'),
   'monthedit' => new Controller('monthedit', 'calendar-o', 'success', 'danger', 'regions', 'Month Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/151552032/Month+Edit'),
   'regions' => new Controller('regions', 'globe', 'success', 'primary', 'regions', 'Regions', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990505/Regions'),
   'regionedit' => new Controller('regionedit', 'globe', 'success', 'primary', 'regions', 'Region Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990505/Regions'),
   'remainder' => new Controller('remainder', 'calendar-check-o', 'success', 'primary', 'remainder', 'Remainder View', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/142049329/Remainder+Page'),
   'statsabsence' => new Controller('statsabsence', 'bar-chart', 'danger', 'default', 'statistics', 'Absence Statistics', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990556/Absences+Statistics'),
   'statsabstype' => new Controller('statsabstype', 'bar-chart', 'info', 'default', 'statistics', 'Absence Type Statistics', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990560/Absence+Type+Statistics'),
   'statspresence' => new Controller('statspresence', 'bar-chart', 'success', 'default', 'statistics', 'Presence Statistics', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990558/Presence+Statistics'),
   'statsremainder' => new Controller('statsremainder', 'bar-chart', 'warning', 'default', 'statistics', 'Remainder Statistics', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990562/Remainder+Statistics'),
   'tcpimport' => new Controller('tcpimport', 'upload', 'warning', 'warning', 'admin', 'TeamCal Pro Import', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990479/TeamCal+Pro+Import'),
   'year' => new Controller('year', 'calendar', 'info', 'primary', 'calendarview', 'Year Calendar', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990529/Calendar+Year'),
);
?>