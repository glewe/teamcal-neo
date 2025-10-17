<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: Permission
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['perm_absenceedit_desc'] = 'Allows to list and edit absence types.';
$LANG['perm_absenceedit_title'] = 'Absence Types (Edit)';
$LANG['perm_absum_desc'] = 'Allows to view the absence summary page for users.';
$LANG['perm_absum_title'] = 'Absence Summary (View)';
$LANG['perm_activate_confirm'] = 'Are you sure you want to activate this permission scheme?';
$LANG['perm_activate_scheme'] = 'Activate scheme';
$LANG['perm_active'] = '(Active)';
$LANG['perm_admin_desc'] = 'Allows to access the system administration pages.';
$LANG['perm_admin_title'] = 'Administration (System)';
$LANG['perm_calendaredit_desc'] = 'Allows to open the calendar editor. This permission is required to edit any user calendars.';
$LANG['perm_calendaredit_title'] = 'Calendar (Edit)';
$LANG['perm_calendareditall_desc'] = 'Allows to edit the calendars of all users. User with this permission can set any absence for anybody without validation.';
$LANG['perm_calendareditall_title'] = 'Calendar (Edit All)';
$LANG['perm_calendareditgroup_desc'] = 'Allows to edit all user calendars of those groups that the role holder is member or manager of.';
$LANG['perm_calendareditgroup_title'] = 'Calendar (Edit Group as Member or Manager)';
$LANG['perm_calendareditgroupmanaged_desc'] = 'Allows to edit all user calendars of those groups that the role holder is manager of.';
$LANG['perm_calendareditgroupmanaged_title'] = 'Calendar (Edit Group as Manager)';
$LANG['perm_calendareditown_desc'] = 'Allows to edit the own calendar. If you run a central absence management you might want to switch this off for regular users.';
$LANG['perm_calendareditown_title'] = 'Calendar (Edit Own)';
$LANG['perm_calendaroptions_desc'] = 'Allows to configure the calendar options.';
$LANG['perm_calendaroptions_title'] = 'Calendar (Options)';
$LANG['perm_calendarview_desc'] = 'Allows to view the calendar (month and year). If this is not permitted, no calendars can be displayed. Can be used to allow the public to view the calendar.';
$LANG['perm_calendarview_title'] = 'Calendar (View)';
$LANG['perm_calendarviewall_desc'] = 'Allows to view all calendars.';
$LANG['perm_calendarviewall_title'] = 'Calendar (View All)';
$LANG['perm_calendarviewgroup_desc'] = 'Allows to view all calendars of users that are in the same group as the logged in user.';
$LANG['perm_calendarviewgroup_title'] = 'Calendar (View Group)';
$LANG['perm_create_scheme'] = 'Create scheme';
$LANG['perm_create_scheme_desc'] = 'Type in a name for the new scheme. It will be created and loaded with default settings right away. All changes to the current scheme that have not been applied will be lost.';
$LANG['perm_daynote_desc'] = 'Allows to edit personal daynotes.';
$LANG['perm_daynote_title'] = 'Daynotes (Edit)';
$LANG['perm_daynoteglobal_desc'] = 'Allows to edit global daynotes for region calendars.';
$LANG['perm_daynoteglobal_title'] = 'Daynotes (Edit Global)';
$LANG['perm_declination_desc'] = 'Allows to access the declination management page.';
$LANG['perm_declination_title'] = 'Declination Management';
$LANG['perm_delete_confirm'] = 'Are you sure you want to delete this permission scheme? The Default scheme will be loaded and activated.';
$LANG['perm_delete_scheme'] = 'Delete scheme';
$LANG['perm_groupcalendaredit_desc'] = 'Allows to edit group calendars if also the permission feature "Calendar (Edit Group as Member or Manager)" or "Calendar (Edit All)" is granted to the role.<br><i>Note: Group managers can always edit the group calendar of the groups they manage without this permission.</i>';
$LANG['perm_groupcalendaredit_title'] = 'Group Calendar (Edit)';
$LANG['perm_groupmemberships_desc'] = 'Allows to assign users to groups as members or managers. The Groups Tab needs to be enabled.';
$LANG['perm_groupmemberships_title'] = 'Users (Edit Memberships)';
$LANG['perm_groups_desc'] = 'Allows to list and edit user groups.';
$LANG['perm_groups_title'] = 'Groups (Edit)';
$LANG['perm_holidays_desc'] = 'Allows to list and edit holidays.';
$LANG['perm_holidays_title'] = 'Holidays (Edit)';
$LANG['perm_inactive'] = '(Inactive)';
$LANG['perm_manageronlyabsences_desc'] = 'Allows to edit Group Manager Only absence types.';
$LANG['perm_manageronlyabsences_title'] = 'Group Manager Absence Types (Edit)';
$LANG['perm_messageedit_desc'] = 'Allows to create and send messages.';
$LANG['perm_messageedit_title'] = 'Messages (Create)';
$LANG['perm_messageview_desc'] = 'Allows to access the messages page. Note, that the messages page only shows messages for the logged in user.';
$LANG['perm_messageview_title'] = 'Messages (View)';
$LANG['perm_patternedit_desc'] = 'Allows to manage absence patterns.';
$LANG['perm_patternedit_title'] = 'Absence Patterns (Edit)';
$LANG['perm_regions_desc'] = 'Allows to list and edit regions and their holidays.';
$LANG['perm_regions_title'] = 'Regions (Edit)';
$LANG['perm_remainder_desc'] = 'Allows to view the Remainder page.';
$LANG['perm_remainder_title'] = 'Remainder';
$LANG['perm_reset_confirm'] = 'Are you sure you want to reset the current permission scheme to the "Default" scheme?';
$LANG['perm_reset_scheme'] = 'Reset scheme';
$LANG['perm_roles_desc'] = 'Allows to list and edit roles.';
$LANG['perm_roles_title'] = 'Roles (Edit)';
$LANG['perm_save_scheme'] = 'Save scheme';
$LANG['perm_select_confirm'] = 'When you confirm a new scheme selection, all changes to the current scheme that have not been applied will be lost.';
$LANG['perm_select_scheme'] = 'Select scheme';
$LANG['perm_statistics_desc'] = 'Allows to view the statistics page.';
$LANG['perm_statistics_title'] = 'Statistics (View)';
$LANG['perm_tab_features'] = 'Features';
$LANG['perm_tab_general'] = 'General';
$LANG['perm_title'] = 'Permission Scheme Settings';
$LANG['perm_upload_desc'] = 'Allows the access and upload of file attachments.';
$LANG['perm_upload_title'] = 'Attachments';
$LANG['perm_userabsences_desc'] = 'Enables the Absences tab when editing a user profile.';
$LANG['perm_userabsences_title'] = 'User (Absences Tab)';
$LANG['perm_useraccount_desc'] = 'Enables the Account tab when editing a user profile.';
$LANG['perm_useraccount_title'] = 'Users (Account Tab)';
$LANG['perm_useradmin_desc'] = 'Allows to list and add user accounts.';
$LANG['perm_useradmin_title'] = 'Users (List and Add)';
$LANG['perm_userallowance_desc'] = 'Allows to edit individual allowances for absence types in the user profile Absences tab. The tab has to be enabled.';
$LANG['perm_userallowance_title'] = 'User (Absences Allowances)';
$LANG['perm_useravatar_desc'] = 'Enables the Avatar tab when editing a user profile.';
$LANG['perm_useravatar_title'] = 'Users (Avatar Tab)';
$LANG['perm_usercustom_desc'] = 'Enables the Custom tab when editing a user profile.';
$LANG['perm_usercustom_title'] = 'Users (Custom Tab)';
$LANG['perm_useredit_desc'] = 'Allows editing the user profiles.';
$LANG['perm_useredit_title'] = 'Users (Edit)';
$LANG['perm_usergroups_desc'] = 'Enables the Groups tab when editing a user profile.';
$LANG['perm_usergroups_title'] = 'Users (Groups Tab)';
$LANG['perm_userimport_desc'] = 'Allows the import of user accounts via CSV.';
$LANG['perm_userimport_title'] = 'User Import';
$LANG['perm_usernotifications_desc'] = 'Enables the Notifications tab when editing a user profile.';
$LANG['perm_usernotifications_title'] = 'Users (Notifications Tab)';
$LANG['perm_useroptions_desc'] = 'Enables the Options tab when editing a user profile.';
$LANG['perm_useroptions_title'] = 'Users (Options Tab)';
$LANG['perm_view_by_perm'] = 'View by permission';
$LANG['perm_view_by_role'] = 'View by role';
$LANG['perm_viewprofile_desc'] = 'Allows to access the view profile page showing basic info like name, phone number etc. Viewing user popups is also dependent on this permission.';
$LANG['perm_viewprofile_title'] = 'Users (View)';
