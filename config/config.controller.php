<?php
/**
 * config.controller.php
 * 
 * Application based parameters. Don't change anything in this file.
 *
 * @category TeamCal Neo 
 * @version 1.9.011
 * @author George Lewe
 * @copyright Copyright (c) 2014-2019 by George Lewe
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
 * <key> => new Controller (<name>, <faIcon>, <iconColor>, <panelColor>, <permission>, <title>, <help URL>)
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
$CONF['menuIconColor'] = "primary";
$CONF['controllers'] = array (
   //
   // LeAF Controllers (Lewe Application Framework)
   //
   'about' => new Controller('about', 'fas fa-info-circle', $CONF['menuIconColor'], 'default', '', 'About', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'attachments' => new Controller('attachments', 'fas fa-paperclip', $CONF['menuIconColor'], 'primary', 'upload', 'Attachments', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138579/Attachments'),
   'config' => new Controller('config', 'fas fa-cog', $CONF['menuIconColor'], 'primary', 'admin', 'Configuration', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138537/Framework+Configuration'),
   'database' => new Controller('database', 'fas fa-database', $CONF['menuIconColor'], 'danger', 'admin', 'Database', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990477/Database+Management'),
   'groups' => new Controller('groups', 'fas fa-users', $CONF['menuIconColor'], 'primary', 'groups', 'Groups', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138585/Groups'),
   'groupedit' => new Controller('groupedit', 'fas fa-users', $CONF['menuIconColor'], 'danger', 'groups', 'Group Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138585/Groups'),
   'home' => new Controller('home', 'fas fa-home', $CONF['menuIconColor'], 'default', '', 'Home', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'imprint' => new Controller('imprint', 'fas fa-file-alt', $CONF['menuIconColor'], 'default', '', 'Imprint', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'log' => new Controller('log', 'fas fa-list', $CONF['menuIconColor'], 'info', 'admin', 'Log', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990489/System+Log'),
   'login' => new Controller('login', 'fas fa-sign-in-alt', $CONF['menuIconColor'], 'default', '', 'Login', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'logout' => new Controller('logout', 'fas fa-sign-out', $CONF['menuIconColor'], 'default', '', 'Logout', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'maintenance' => new Controller('maintenance', 'fas fa-wrench', $CONF['menuIconColor'], 'danger', '', 'Maintenance', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'messages' => new Controller('messages', 'fas fa-comments', $CONF['menuIconColor'], 'info', 'messageview', 'Messages', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990535/Messages+View'),
   'messageedit' => new Controller('messageedit', 'fas fa-comment', $CONF['menuIconColor'], 'danger', 'messageedit', 'Message Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990546/Messages+Create'),
   'passwordrequest' => new Controller('passwordrequest', 'fas fa-sign-in', $CONF['menuIconColor'], 'primary', '', 'Password Request', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'passwordreset' => new Controller('passwordreset', 'fas fa-lock', $CONF['menuIconColor'], 'primary', '', 'Reset Password', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'permissions' => new Controller('permissions', 'fas fa-lock', $CONF['menuIconColor'], 'primary', 'admin', 'Permissions', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138573/Permissions'),
   'phpinfo' => new Controller('phpinfo', 'fas fa-stethoscope', $CONF['menuIconColor'], 'primary', 'admin', 'PHP Info', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990491/PHP+Info'),
   'register' => new Controller('register', 'fas fa-user-plus', $CONF['menuIconColor'], 'success', '', 'Register', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'roles' => new Controller('roles', 'fas fa-user-circle', $CONF['menuIconColor'], 'primary', 'roles', 'Roles', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138587/Roles'),
   'roleedit' => new Controller('roleedit', 'fas fa-user-circle', $CONF['menuIconColor'], 'danger', 'roles', 'Role Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138587/Roles'),
   'users' => new Controller('users', 'fas fa-user', $CONF['menuIconColor'], 'primary', 'admin', 'Users', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138582/Users'),
   'useradd' => new Controller('useradd', 'fas fa-user-plus', $CONF['menuIconColor'], 'warning', 'admin', 'User Add', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138582/Users'),
   'useredit' => new Controller('useredit', 'fas fa-user-edit', $CONF['menuIconColor'], 'danger', 'useredit', 'User Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/142180353/User+Edit'),
   'userimport' => new Controller('userimport', 'fas fa-upload', $CONF['menuIconColor'], 'primary', 'admin', 'User Import', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/80892338/User+Import'),
   'verify' => new Controller('verify', 'fas fa-user', $CONF['menuIconColor'], 'default', '', 'Verify', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/overview'),
   'viewprofile' => new Controller('viewprofile', 'fas fa-user', $CONF['menuIconColor'], 'default', 'viewprofile', 'View Profile', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/151519291/View+Profile'),
   //
   // Application Controllers
   // Enter your application controllers below
   //
   'absences' => new Controller('absences', 'fas fa-check-square', $CONF['menuIconColor'], 'primary', 'absenceedit', 'Absence Types', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990493/Absence+Types'),
   'absenceedit' => new Controller('absenceedit', 'fas fa-check-square', $CONF['menuIconColor'], 'danger', 'absenceedit', 'Absence Type Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990495/Absence+Type+Edit'),
   'absenceicon' => new Controller('absenceicon', 'fab fa-font-awesome', $CONF['menuIconColor'], 'primary', 'absenceedit', 'Absence Type Icon', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990495/Absence+Type+Edit'),
   'absum' => new Controller('absum', 'fas fa-list', $CONF['menuIconColor'], 'default', 'absum', 'Absence Summary', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990567/User+Absence+Summary'),
   'calendarview' => new Controller('calendarview', 'fas fa-calendar-alt', $CONF['menuIconColor'], 'primary', 'calendarview', 'Calendar View', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990571/View+Calendar+Month'),
   'calendaredit' => new Controller('calendaredit', 'fas fa-calendar-check', $CONF['menuIconColor'], 'danger', 'calendaredit', 'Calendar Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990577/Edit+User+Calendar+Month'),
   'calendaroptions' => new Controller('calendaroptions', 'fas fa-wrench', $CONF['menuIconColor'], 'primary', 'calendaroptions', 'Calendar Options', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138565/Calendar+Options'),
   'daynote' => new Controller('daynote', 'fas fa-sticky-note-o', $CONF['menuIconColor'], 'info', 'daynote', 'Daynote', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/57442318/Edit+Daynotes'),
   'declination' => new Controller('declination', 'fas fa-minus-circle', $CONF['menuIconColor'], 'danger', 'declination', 'Declination', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/72089642/Declination+Management'),
   'groupcalendaredit' => new Controller('groupcalendaredit', 'fas fa-calendar-o', $CONF['menuIconColor'], 'danger', 'groupcalendaredit', 'Group Calendar Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/82122981/Edit+Group+Calendar+Month'),
   'holidays' => new Controller('holidays', 'fas fa-calendar-day', $CONF['menuIconColor'], 'primary', 'holidays', 'Holidays', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990497/Holidays'),
   'holidayedit' => new Controller('holidayedit', 'fas fa-calendar-day', $CONF['menuIconColor'], 'danger', 'holidays', 'Holiday Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990503/Holiday+Edit'),
   'monthedit' => new Controller('monthedit', 'fas fa-calendar-alt', $CONF['menuIconColor'], 'danger', 'regions', 'Month Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/151552032/Month+Edit'),
   'regions' => new Controller('regions', 'fas fa-globe', $CONF['menuIconColor'], 'primary', 'regions', 'Regions', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990505/Regions'),
   'regionedit' => new Controller('regionedit', 'fas fa-globe', $CONF['menuIconColor'], 'primary', 'regions', 'Region Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990505/Regions'),
   'remainder' => new Controller('remainder', 'fas fa-calculator', $CONF['menuIconColor'], 'primary', 'remainder', 'Remainder View', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/142049329/Remainder+Page'),
   'statsabsence' => new Controller('statsabsence', 'fas fa-chart-bar', $CONF['menuIconColor'], 'default', 'statistics', 'Absence Statistics', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990556/Absences+Statistics'),
   'statsabstype' => new Controller('statsabstype', 'fas fa-chart-bar', $CONF['menuIconColor'], 'default', 'statistics', 'Absence Type Statistics', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990560/Absence+Type+Statistics'),
   'statspresence' => new Controller('statspresence', 'fas fa-chart-bar', $CONF['menuIconColor'], 'default', 'statistics', 'Presence Statistics', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990558/Presence+Statistics'),
   'statsremainder' => new Controller('statsremainder', 'fas fa-chart-bar', $CONF['menuIconColor'], 'default', 'statistics', 'Remainder Statistics', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990562/Remainder+Statistics'),
   'tcpimport' => new Controller('tcpimport', 'fas fa-upload', $CONF['menuIconColor'], 'warning', 'admin', 'TeamCal Pro Import', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990479/TeamCal+Pro+Import'),
   'year' => new Controller('year', 'fas fa-calendar', $CONF['menuIconColor'], 'primary','calendarview', 'Year Calendar', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44990529/Calendar+Year'),
);
?>