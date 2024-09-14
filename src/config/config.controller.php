<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Controller Configuration
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
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
$CONF['controllers'] = array(
  //
  // LeAF Controllers (Lewe Application Framework)
  //
  'about' => new Controller('about', 'bi-calendar-week logo-gradient', $CONF['menuIconColor'], 'default', '', 'About', 'https://lewe.gitbook.io/teamcal-neo/'),
  'attachments' => new Controller('attachments', 'bi-paperclip', $CONF['menuIconColor'], 'primary', 'upload', 'Attachments', 'https://lewe.gitbook.io/teamcal-neo/user-guide/attachments/'),
  'config' => new Controller('config', 'bi-gear', $CONF['menuIconColor'], 'primary', 'admin', 'Configuration', 'https://lewe.gitbook.io/teamcal-neo/administration/framework-configuration/'),
  'database' => new Controller('database', 'bi-database-fill-gear', $CONF['menuIconColor'], 'danger', 'admin', 'Database', 'https://lewe.gitbook.io/teamcal-neo/administration/database-management/'),
  'dataprivacy' => new Controller('dataprivacy', 'bi-shield-check', $CONF['menuIconColor'], 'default', '', 'Data Privacy Policy', 'https://lewe.gitbook.io/teamcal-neo/'),
  'groups' => new Controller('groups', 'bi-people', $CONF['menuIconColor'], 'primary', 'groups', 'Groups', 'https://lewe.gitbook.io/teamcal-neo/administration/groups/'),
  'groupedit' => new Controller('groupedit', 'bi-people', $CONF['menuIconColor'], 'danger', 'groups', 'Group Edit', 'https://lewe.gitbook.io/teamcal-neo/administration/groups/group-edit/'),
  'home' => new Controller('home', 'bi-house', $CONF['menuIconColor'], 'default', '', 'Home', 'https://lewe.gitbook.io/teamcal-neo/'),
  'imprint' => new Controller('imprint', 'bi-vector-pen', $CONF['menuIconColor'], 'default', '', 'Imprint', 'https://lewe.gitbook.io/teamcal-neo/'),
  'log' => new Controller('log', 'bi-card-list', $CONF['menuIconColor'], 'info', 'admin', 'Log', 'https://lewe.gitbook.io/teamcal-neo/administration/system-log/'),
  'login' => new Controller('login', 'bi-box-arrow-in-right', $CONF['menuIconColor'], 'default', '', 'Login', 'https://lewe.gitbook.io/teamcal-neo/'),
  'logout' => new Controller('logout', 'bi-box-arrow-left', $CONF['menuIconColor'], 'default', '', 'Logout', 'https://lewe.gitbook.io/teamcal-neo/'),
  'maintenance' => new Controller('maintenance', 'bi-wrench', $CONF['menuIconColor'], 'danger', '', 'Maintenance', 'https://lewe.gitbook.io/teamcal-neo/'),
  'messages' => new Controller('messages', 'bi-chat-text', $CONF['menuIconColor'], 'info', 'messageview', 'Messages', 'https://lewe.gitbook.io/teamcal-neo/user-guide/messages/'),
  'messageedit' => new Controller('messageedit', 'bi-chat-text', $CONF['menuIconColor'], 'danger', 'messageedit', 'Message Edit', 'https://lewe.gitbook.io/teamcal-neo/user-guide/messages/'),
  'passwordrequest' => new Controller('passwordrequest', 'bi-person-lock', $CONF['menuIconColor'], 'primary', '', 'Password Request', 'https://lewe.gitbook.io/teamcal-neo/'),
  'passwordreset' => new Controller('passwordreset', 'bi-person-lock', $CONF['menuIconColor'], 'primary', '', 'Reset Password', 'https://lewe.gitbook.io/teamcal-neo/'),
  'permissions' => new Controller('permissions', 'bi-key', $CONF['menuIconColor'], 'primary', 'admin', 'Permissions', 'https://lewe.gitbook.io/teamcal-neo/administration/permissions/'),
  'phpinfo' => new Controller('phpinfo', 'bi-filetype-php', $CONF['menuIconColor'], 'primary', 'admin', 'PHP Info', 'https://lewe.gitbook.io/teamcal-neo/administration/php-info/'),
  'register' => new Controller('register', 'bi-person-plus', $CONF['menuIconColor'], 'success', '', 'Register', 'https://lewe.gitbook.io/teamcal-neo/'),
  'roles' => new Controller('roles', 'bi-person-circle', $CONF['menuIconColor'], 'primary', 'roles', 'Roles', 'https://lewe.gitbook.io/teamcal-neo/administration/roles/'),
  'roleedit' => new Controller('roleedit', 'bi-person-circle', $CONF['menuIconColor'], 'danger', 'roles', 'Role Edit', 'https://lewe.gitbook.io/teamcal-neo/administration/roles/'),
  'users' => new Controller('users', 'bi-person', $CONF['menuIconColor'], 'primary', 'admin', 'Users', 'https://lewe.gitbook.io/teamcal-neo/administration/users/'),
  'useradd' => new Controller('useradd', 'bi-person-add', $CONF['menuIconColor'], 'warning', 'admin', 'User Add', 'https://lewe.gitbook.io/teamcal-neo/administration/users/'),
  'useredit' => new Controller('useredit', 'bi-person-gear', $CONF['menuIconColor'], 'danger', 'useredit', 'User Edit', 'https://lewe.gitbook.io/teamcal-neo/administration/users/user-edit/'),
  'userimport' => new Controller('userimport', 'bi-upload', $CONF['menuIconColor'], 'primary', 'admin', 'User Import', 'https://lewe.gitbook.io/teamcal-neo/administration/users/user-import/'),
  'verify' => new Controller('verify', 'bi-person-check', $CONF['menuIconColor'], 'default', '', 'Verify', 'https://lewe.gitbook.io/teamcal-neo/'),
  'viewprofile' => new Controller('viewprofile', 'bi-person-vcard', $CONF['menuIconColor'], 'default', 'viewprofile', 'View Profile', 'https://lewe.gitbook.io/teamcal-neo/user-guide/view-profile/'),
  //
  // Application Controllers
  // Enter your application controllers below
  //
  'absences' => new Controller('absences', 'bi-calendar-check', $CONF['menuIconColor'], 'primary', 'absenceedit', 'Absence Types', 'https://lewe.gitbook.io/teamcal-neo/administration/absence-types/'),
  'absenceedit' => new Controller('absenceedit', 'bi-calendar-check', $CONF['menuIconColor'], 'danger', 'absenceedit', 'Absence Type Edit', 'https://lewe.gitbook.io/teamcal-neo/administration/absence-types/absence-type-edit/'),
  'absenceicon' => new Controller('absenceicon', 'bi-emoji-smile', $CONF['menuIconColor'], 'primary', 'absenceedit', 'Absence Type Icon', 'https://lewe.gitbook.io/teamcal-neo/administration/absence-types/absence-type-icon/'),
  'absum' => new Controller('absum', 'bi-list-ul', $CONF['menuIconColor'], 'default', 'absum', 'Absence Summary', 'https://lewe.gitbook.io/teamcal-neo/user-guide/statistics/user-absence-summary/'),
  'bulkedit' => new Controller('bulkedit', 'bi-plus-slash-minus', $CONF['menuIconColor'], 'danger', 'admin', 'Allowances', 'https://lewe.gitbook.io/teamcal-neo/administration/allowance-bulk-edit/'),
  'calendarview' => new Controller('calendarview', 'bi-calendar-week', $CONF['menuIconColor'], 'primary', 'calendarview', 'Calendar View', 'https://lewe.gitbook.io/teamcal-neo/user-guide/calendar-month/view-calendar-month/'),
  'calendaredit' => new Controller('calendaredit', 'bi-calendar2-check', $CONF['menuIconColor'], 'danger', 'calendaredit', 'Calendar Edit', 'https://lewe.gitbook.io/teamcal-neo/user-guide/calendar-month/edit-calendar-month/'),
  'calendaroptions' => new Controller('calendaroptions', 'bi-sliders', $CONF['menuIconColor'], 'primary', 'calendaroptions', 'Calendar Options', 'https://lewe.gitbook.io/teamcal-neo/administration/calendar-options/'),
  'daynote' => new Controller('daynote', 'bi-sticky', $CONF['menuIconColor'], 'info', 'daynote', 'Daynote', 'https://lewe.gitbook.io/teamcal-neo/user-guide/calendar-month/edit-daynotes/'),
  'declination' => new Controller('declination', 'bi-slash-circle', $CONF['menuIconColor'], 'danger', 'declination', 'Declination', 'https://lewe.gitbook.io/teamcal-neo/administration/declination-management/'),
  'groupcalendaredit' => new Controller('groupcalendaredit', 'bi-calendar-month', $CONF['menuIconColor'], 'danger', 'groupcalendaredit', 'Group Calendar Edit', 'https://lewe.gitbook.io/teamcal-neo/user-guide/calendar-month/edit-group-calendar-month/'),
  'holidays' => new Controller('holidays', 'bi-calendar-heart', $CONF['menuIconColor'], 'primary', 'holidays', 'Holidays', 'https://lewe.gitbook.io/teamcal-neo/administration/holidays/'),
  'holidayedit' => new Controller('holidayedit', 'bi-calendar-heart', $CONF['menuIconColor'], 'danger', 'holidays', 'Holiday Edit', 'https://lewe.gitbook.io/teamcal-neo/administration/holidays/holiday-edit/'),
  'monthedit' => new Controller('monthedit', 'bi-calendar-month', $CONF['menuIconColor'], 'danger', 'regions', 'Month Edit', 'https://lewe.gitbook.io/teamcal-neo/administration/regions/month-edit'),
  'patterns' => new Controller('patterns', 'bi-calendar2-week', $CONF['menuIconColor'], 'primary', 'patternedit', 'Absence Patterns', 'https://lewe.gitbook.io/teamcal-neo/administration/absence-patterns/'),
  'patternadd' => new Controller('patternadd', 'bi-calendar2-week', $CONF['menuIconColor'], 'primary', 'patternedit', 'Add Absence Pattern', 'https://lewe.gitbook.io/teamcal-neo/administration/absence-patterns/'),
  'patternedit' => new Controller('patternedit', 'bi-calendar2-week', $CONF['menuIconColor'], 'primary', 'patternedit', 'Edit Absence Pattern', 'https://lewe.gitbook.io/teamcal-neo/administration/absence-patterns/'),
  'regions' => new Controller('regions', 'bi-globe', $CONF['menuIconColor'], 'primary', 'regions', 'Regions', 'https://lewe.gitbook.io/teamcal-neo/administration/regions/'),
  'regionedit' => new Controller('regionedit', 'bi-globe', $CONF['menuIconColor'], 'primary', 'regions', 'Region Edit', 'https://lewe.gitbook.io/teamcal-neo/administration/regions/'),
  'remainder' => new Controller('remainder', 'bi-calculator', $CONF['menuIconColor'], 'primary', 'remainder', 'Remainder View', 'https://lewe.gitbook.io/teamcal-neo/user-guide/remainder-page/'),
  'statsabsence' => new Controller('statsabsence', 'bi-bar-chart', $CONF['menuIconColor'], 'default', 'statistics', 'Absence Statistics', 'https://lewe.gitbook.io/teamcal-neo/user-guide/statistics/absence-statistics/'),
  'statsabstype' => new Controller('statsabstype', 'bi-bar-chart', $CONF['menuIconColor'], 'default', 'statistics', 'Absence Type Statistics', 'https://lewe.gitbook.io/teamcal-neo/user-guide/statistics/absence-type-statistics/'),
  'statspresence' => new Controller('statspresence', 'bi-bar-chart', $CONF['menuIconColor'], 'default', 'statistics', 'Presence Statistics', 'https://lewe.gitbook.io/teamcal-neo/user-guide/statistics/presence-statistics/'),
  'statsremainder' => new Controller('statsremainder', 'bi-bar-chart', $CONF['menuIconColor'], 'default', 'statistics', 'Remainder Statistics', 'https://lewe.gitbook.io/teamcal-neo/user-guide/statistics/remainder-statistics/'),
  'year' => new Controller('year', 'bi-calendar', $CONF['menuIconColor'], 'primary', 'calendarview', 'Year Calendar', 'https://lewe.gitbook.io/teamcal-neo/user-guide/calendar-year/'),
);
