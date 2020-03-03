<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Controller Configuration
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2020 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Application Configuration
 * @since 3.0.0
 */

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
 * - dark (dark gray)
 * - default (text color)
 * - primary (blue)
 * - info (cyan)
 * - secondary (light gray)
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
$CONF['menuIconColor'] = "secondary";
$CONF['controllers'] = array (
   //
   // LeAF Controllers (Lewe Application Framework)
   //
   'about' => new Controller('about', 'fas fa-info-circle', $CONF['menuIconColor'], 'default', '', 'About', 'https://support.lewe.com/docs/teamcal-neo-manual/'),
   'attachments' => new Controller('attachments', 'fas fa-paperclip', $CONF['menuIconColor'], 'primary', 'upload', 'Attachments', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/attachments/'),
   'config' => new Controller('config', 'fas fa-cog', $CONF['menuIconColor'], 'primary', 'admin', 'Configuration', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/framework-configuration/'),
   'database' => new Controller('database', 'fas fa-database', $CONF['menuIconColor'], 'danger', 'admin', 'Database', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/database-management/'),
   'dataprivacy' => new Controller('dataprivacy', 'fas fa-shield-alt', $CONF['menuIconColor'], 'default', '', 'Data Privacy Policy', 'https://support.lewe.com/docs/teamcal-neo-manual/'),
   'groups' => new Controller('groups', 'fas fa-users', $CONF['menuIconColor'], 'primary', 'groups', 'Groups', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/groups/'),
   'groupedit' => new Controller('groupedit', 'fas fa-users', $CONF['menuIconColor'], 'danger', 'groups', 'Group Edit', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/groups/group-edit/'),
   'home' => new Controller('home', 'fas fa-home', $CONF['menuIconColor'], 'default', '', 'Home', 'https://support.lewe.com/docs/teamcal-neo-manual/'),
   'imprint' => new Controller('imprint', 'fas fa-file-alt', $CONF['menuIconColor'], 'default', '', 'Imprint', 'https://support.lewe.com/docs/teamcal-neo-manual/'),
   'log' => new Controller('log', 'fas fa-list', $CONF['menuIconColor'], 'info', 'admin', 'Log', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/system-log/'),
   'login' => new Controller('login', 'fas fa-sign-in-alt', $CONF['menuIconColor'], 'default', '', 'Login', 'https://support.lewe.com/docs/teamcal-neo-manual/'),
   'logout' => new Controller('logout', 'fas fa-sign-out-alt', $CONF['menuIconColor'], 'default', '', 'Logout', 'https://support.lewe.com/docs/teamcal-neo-manual/'),
   'maintenance' => new Controller('maintenance', 'fas fa-wrench', $CONF['menuIconColor'], 'danger', '', 'Maintenance', 'https://support.lewe.com/docs/teamcal-neo-manual/'),
   'messages' => new Controller('messages', 'fas fa-comments', $CONF['menuIconColor'], 'info', 'messageview', 'Messages', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/messages/'),
   'messageedit' => new Controller('messageedit', 'fas fa-comment', $CONF['menuIconColor'], 'danger', 'messageedit', 'Message Edit', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/messages/'),
   'passwordrequest' => new Controller('passwordrequest', 'fas fa-user-lock', $CONF['menuIconColor'], 'primary', '', 'Password Request', 'https://support.lewe.com/docs/teamcal-neo-manual/'),
   'passwordreset' => new Controller('passwordreset', 'fas fa-user-lock', $CONF['menuIconColor'], 'primary', '', 'Reset Password', 'https://support.lewe.com/docs/teamcal-neo-manual/'),
   'permissions' => new Controller('permissions', 'fas fa-lock', $CONF['menuIconColor'], 'primary', 'admin', 'Permissions', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/permissions/'),
   'phpinfo' => new Controller('phpinfo', 'fas fa-stethoscope', $CONF['menuIconColor'], 'primary', 'admin', 'PHP Info', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/php-info/'),
   'register' => new Controller('register', 'fas fa-user-plus', $CONF['menuIconColor'], 'success', '', 'Register', 'https://support.lewe.com/docs/teamcal-neo-manual/'),
   'roles' => new Controller('roles', 'fas fa-user-circle', $CONF['menuIconColor'], 'primary', 'roles', 'Roles', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/44138587/Roles'),
   'roleedit' => new Controller('roleedit', 'fas fa-user-circle', $CONF['menuIconColor'], 'danger', 'roles', 'Role Edit', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/roles/'),
   'users' => new Controller('users', 'fas fa-user', $CONF['menuIconColor'], 'primary', 'admin', 'Users', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/users/'),
   'useradd' => new Controller('useradd', 'fas fa-user-plus', $CONF['menuIconColor'], 'warning', 'admin', 'User Add', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/users/'),
   'useredit' => new Controller('useredit', 'fas fa-user-edit', $CONF['menuIconColor'], 'danger', 'useredit', 'User Edit', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/users/user-edit/'),
   'userimport' => new Controller('userimport', 'fas fa-upload', $CONF['menuIconColor'], 'primary', 'admin', 'User Import', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/users/user-import/'),
   'verify' => new Controller('verify', 'fas fa-user', $CONF['menuIconColor'], 'default', '', 'Verify', 'https://support.lewe.com/docs/teamcal-neo-manual/'),
   'viewprofile' => new Controller('viewprofile', 'fas fa-user', $CONF['menuIconColor'], 'default', 'viewprofile', 'View Profile', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/view-profile/'),
   //
   // Application Controllers
   // Enter your application controllers below
   //
   'absences' => new Controller('absences', 'fas fa-check-square', $CONF['menuIconColor'], 'primary', 'absenceedit', 'Absence Types', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/absence-types/'),
   'absenceedit' => new Controller('absenceedit', 'fas fa-check-square', $CONF['menuIconColor'], 'danger', 'absenceedit', 'Absence Type Edit', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/absence-types/absence-type-edit/'),
   'absenceicon' => new Controller('absenceicon', 'fab fa-font-awesome', $CONF['menuIconColor'], 'primary', 'absenceedit', 'Absence Type Icon', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/absence-types/absence-type-icon/'),
   'absum' => new Controller('absum', 'fas fa-list', $CONF['menuIconColor'], 'default', 'absum', 'Absence Summary', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/statistics/user-absence-summary/'),
   'calendarview' => new Controller('calendarview', 'fas fa-calendar-alt', $CONF['menuIconColor'], 'primary', 'calendarview', 'Calendar View', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/calendar-month/view-calendar-month/'),
   'calendaredit' => new Controller('calendaredit', 'fas fa-calendar-check', $CONF['menuIconColor'], 'danger', 'calendaredit', 'Calendar Edit', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/calendar-month/edit-calendar-month/'),
   'calendaroptions' => new Controller('calendaroptions', 'fas fa-wrench', $CONF['menuIconColor'], 'primary', 'calendaroptions', 'Calendar Options', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/calendar-options/'),
   'daynote' => new Controller('daynote', 'fas fa-sticky-note', $CONF['menuIconColor'], 'info', 'daynote', 'Daynote', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/calendar-month/edit-daynotes/'),
   'declination' => new Controller('declination', 'fas fa-minus-circle', $CONF['menuIconColor'], 'danger', 'declination', 'Declination', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/declination-management/'),
   'groupcalendaredit' => new Controller('groupcalendaredit', 'fas fa-calendar-o', $CONF['menuIconColor'], 'danger', 'groupcalendaredit', 'Group Calendar Edit', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/calendar-month/edit-group-calendar-month/'),
   'holidays' => new Controller('holidays', 'fas fa-calendar-day', $CONF['menuIconColor'], 'primary', 'holidays', 'Holidays', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/holidays/'),
   'holidayedit' => new Controller('holidayedit', 'fas fa-calendar-day', $CONF['menuIconColor'], 'danger', 'holidays', 'Holiday Edit', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/holidays/holiday-edit/'),
   'monthedit' => new Controller('monthedit', 'fas fa-calendar-alt', $CONF['menuIconColor'], 'danger', 'regions', 'Month Edit', 'https://georgelewe.atlassian.net/wiki/spaces/TCNEO/pages/151552032/Month+Edit'),
   'regions' => new Controller('regions', 'fas fa-globe', $CONF['menuIconColor'], 'primary', 'regions', 'Regions', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/regions/'),
   'regionedit' => new Controller('regionedit', 'fas fa-globe', $CONF['menuIconColor'], 'primary', 'regions', 'Region Edit', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/regions/'),
   'remainder' => new Controller('remainder', 'fas fa-calculator', $CONF['menuIconColor'], 'primary', 'remainder', 'Remainder View', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/remainder-page/'),
   'statsabsence' => new Controller('statsabsence', 'fas fa-chart-bar', $CONF['menuIconColor'], 'default', 'statistics', 'Absence Statistics', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/statistics/absence-statistics/'),
   'statsabstype' => new Controller('statsabstype', 'fas fa-chart-bar', $CONF['menuIconColor'], 'default', 'statistics', 'Absence Type Statistics', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/statistics/absence-type-statistics/'),
   'statspresence' => new Controller('statspresence', 'fas fa-chart-bar', $CONF['menuIconColor'], 'default', 'statistics', 'Presence Statistics', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/statistics/presence-statistics/'),
   'statsremainder' => new Controller('statsremainder', 'fas fa-chart-bar', $CONF['menuIconColor'], 'default', 'statistics', 'Remainder Statistics', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/statistics/remainder-statistics/'),
   'tcpimport' => new Controller('tcpimport', 'fas fa-upload', $CONF['menuIconColor'], 'warning', 'admin', 'TeamCal Pro Import', 'https://support.lewe.com/docs/teamcal-neo-manual/administration/database-management/teamcal-pro-import/'),
   'year' => new Controller('year', 'fas fa-calendar', $CONF['menuIconColor'], 'primary','calendarview', 'Year Calendar', 'https://support.lewe.com/docs/teamcal-neo-manual/user-guide/calendar-year/'),
);
?>