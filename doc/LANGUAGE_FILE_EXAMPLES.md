# TeamCal Neo Language File Examples

This document provides practical examples of controller-specific language files, showing typical key patterns, organization, and usage scenarios.

## Core Language File Example

### `src/languages/english/core.php`
```php
<?php
/**
 * TeamCal Neo Language File: Core
 * 
 * @file        core.php
 * @author      George Lewe
 * @copyright   (c) 2014-2025 George Lewe
 * @package     TeamCal Neo
 * @subpackage  Languages
 * 
 * Contains essential strings used across multiple controllers.
 * These keys are loaded for every page request.
 */

//
// Alert Messages
//
$LANG['alert_alert_title'] = 'Alert';
$LANG['alert_success_title'] = 'Success';
$LANG['alert_info_title'] = 'Information';
$LANG['alert_warning_title'] = 'Warning';
$LANG['alert_danger_title'] = 'Error';

//
// Button Labels
//
$LANG['btn_save'] = 'Save';
$LANG['btn_cancel'] = 'Cancel';
$LANG['btn_delete'] = 'Delete';
$LANG['btn_edit'] = 'Edit';
$LANG['btn_add'] = 'Add';
$LANG['btn_close'] = 'Close';
$LANG['btn_back'] = 'Back';
$LANG['btn_next'] = 'Next';

//
// Common Labels
//
$LANG['name'] = 'Name';
$LANG['description'] = 'Description';
$LANG['all'] = 'All';
$LANG['none'] = 'None';
$LANG['yes'] = 'Yes';
$LANG['no'] = 'No';
$LANG['active'] = 'Active';
$LANG['inactive'] = 'Inactive';

//
// Time and Date
//
$LANG['today'] = 'Today';
$LANG['year'] = 'Year';
?>
```

## Feature-Specific Examples

### User Management Example

#### `src/languages/english/user.php`
```php
<?php
/**
 * TeamCal Neo Language File: User Management
 * 
 * Contains language strings for user-related functionality including
 * user listing, creation, editing, and management.
 * Used by controllers: users, useradd, useredit, userimport
 */

//
// User List Page
//
$LANG['user_list_title'] = 'User Management';
$LANG['user_add_title'] = 'Add User';
$LANG['user_edit_title'] = 'Edit User';
$LANG['user_delete_title'] = 'Delete User';

//
// User Properties
//
$LANG['user_username'] = 'Username';
$LANG['user_firstname'] = 'First Name';
$LANG['user_lastname'] = 'Last Name';
$LANG['user_email'] = 'Email Address';
$LANG['user_role'] = 'Role';
$LANG['user_groups'] = 'Groups';
$LANG['user_region'] = 'Region';
$LANG['user_active'] = 'Active';

//
// User Actions
//
$LANG['user_create_success'] = 'User created successfully';
$LANG['user_update_success'] = 'User updated successfully';
$LANG['user_delete_success'] = 'User deleted successfully';
$LANG['user_delete_confirm'] = 'Are you sure you want to delete user "%s"?';

//
// User Validation
//
$LANG['user_username_required'] = 'Username is required';
$LANG['user_username_exists'] = 'Username already exists';
$LANG['user_email_invalid'] = 'Please enter a valid email address';
$LANG['user_email_exists'] = 'Email address already exists';

//
// User Status
//
$LANG['user_status_active'] = 'Active';
$LANG['user_status_inactive'] = 'Inactive';
$LANG['user_status_locked'] = 'Locked';
$LANG['user_last_login'] = 'Last Login';
$LANG['user_never_logged_in'] = 'Never logged in';

//
// Buttons specific to user management
//
$LANG['btn_user_list'] = 'User List';
$LANG['btn_user_add'] = 'Add User';
$LANG['btn_user_edit'] = 'Edit User';
$LANG['btn_user_calendar'] = 'User Calendar';
?>
```

### Calendar Management Example

#### `src/languages/english/calendar.php`
```php
<?php
/**
 * TeamCal Neo Language File: Calendar
 * 
 * Contains language strings for calendar functionality including
 * calendar display, editing, and navigation.
 * Used by controllers: calendarview, calendaredit, groupcalendaredit
 */

//
// Calendar Display
//
$LANG['cal_title'] = '%s %s - %s';  // Year Month - Region
$LANG['cal_summary'] = 'Summary';
$LANG['cal_businessDays'] = 'Business Days';

//
// Calendar Navigation
//
$LANG['cal_tt_backward'] = 'Previous Month';
$LANG['cal_tt_forward'] = 'Next Month';
$LANG['cal_tt_onemore'] = 'Add one more month';
$LANG['cal_tt_oneless'] = 'Remove one month';

//
// Calendar Editing
//
$LANG['caledit_title'] = 'Edit Calendar';
$LANG['caledit_selUser'] = 'Select User';
$LANG['caledit_selMonth'] = 'Select Month';
$LANG['caledit_selRegion'] = 'Select Region';
$LANG['caledit_save_success'] = 'Calendar updated successfully';

//
// Calendar Selection Modals
//
$LANG['cal_selMonth'] = 'Select Month';
$LANG['cal_selRegion'] = 'Select Region';
$LANG['cal_selGroup'] = 'Select Group';
$LANG['cal_selAbsence'] = 'Select Absence Type';
$LANG['cal_selAbsence_comment'] = 'Select an absence type to filter the calendar display.';
$LANG['cal_selWidth'] = 'Select Width';
$LANG['cal_selWidth_comment'] = 'Select the display width for the calendar.';

//
// Calendar Search
//
$LANG['cal_search'] = 'Search Users';
$LANG['cal_search_placeholder'] = 'Enter username or name...';

//
// Calendar Tooltips
//
$LANG['cal_tt_absent'] = 'User is absent on this day';
$LANG['cal_tt_anotherabsence'] = 'User has another absence on this day';
$LANG['cal_tt_clicktoedit'] = 'Click to edit this day';

//
// Calendar Buttons
//
$LANG['btn_cal_edit'] = 'Edit Calendar';
$LANG['btn_cal_view'] = 'View Calendar';
$LANG['btn_cal_print'] = 'Print Calendar';
?>
```

### Authentication Example

#### `src/languages/english/login.php`
```php
<?php
/**
 * TeamCal Neo Language File: Authentication
 * 
 * Contains language strings for user authentication including
 * login, logout, 2FA, and verification processes.
 * Used by controllers: login, logout, login2fa, setup2fa, verify
 */

//
// Login Page
//
$LANG['login_title'] = 'Login';
$LANG['login_username'] = 'Username';
$LANG['login_password'] = 'Password';
$LANG['login_remember'] = 'Remember me';
$LANG['login_submit'] = 'Sign In';
$LANG['login_forgot_password'] = 'Forgot your password?';

//
// Login Messages
//
$LANG['login_success'] = 'Welcome! You have been logged in successfully.';
$LANG['login_failed'] = 'Invalid username or password.';
$LANG['login_account_locked'] = 'Your account has been locked due to too many failed login attempts.';
$LANG['login_account_inactive'] = 'Your account is inactive. Please contact an administrator.';

//
// Logout
//
$LANG['logout_title'] = 'Logout';
$LANG['logout_success'] = 'You have been logged out successfully.';
$LANG['logout_confirm'] = 'Are you sure you want to log out?';

//
// Two-Factor Authentication
//
$LANG['login_2fa_title'] = 'Two-Factor Authentication';
$LANG['login_2fa_code'] = 'Authentication Code';
$LANG['login_2fa_help'] = 'Enter the 6-digit code from your authenticator app.';
$LANG['login_2fa_invalid'] = 'Invalid authentication code. Please try again.';
$LANG['login_2fa_setup'] = 'Set up two-factor authentication';

//
// Account Verification
//
$LANG['verify_title'] = 'Verify Account';
$LANG['verify_success'] = 'Your account has been verified successfully.';
$LANG['verify_failed'] = 'Verification failed. The link may be expired or invalid.';
$LANG['verify_email_sent'] = 'A verification email has been sent to your email address.';

//
// Session Management
//
$LANG['session_expired'] = 'Your session has expired. Please log in again.';
$LANG['session_invalid'] = 'Invalid session. Please log in again.';

//
// Menu Items
//
$LANG['mnu_user_login'] = 'Login';
$LANG['mnu_user_logout'] = 'Logout';
$LANG['mnu_user_profile'] = 'My Profile';
?>
```

### Absence Management Example

#### `src/languages/english/absence.php`
```php
<?php
/**
 * TeamCal Neo Language File: Absence Management
 * 
 * Contains language strings for absence-related functionality including
 * absence types, requests, approvals, and reporting.
 * Used by controllers: absences, absenceedit, absenceicon, absum
 */

//
// Absence Types List
//
$LANG['abs_list_title'] = 'Absence Types';
$LANG['abs_add_title'] = 'Add Absence Type';
$LANG['abs_edit_title'] = 'Edit Absence Type';
$LANG['abs_delete_title'] = 'Delete Absence Type';

//
// Absence Properties
//
$LANG['abs_name'] = 'Name';
$LANG['abs_symbol'] = 'Symbol';
$LANG['abs_color'] = 'Color';
$LANG['abs_icon'] = 'Icon';
$LANG['abs_allowance'] = 'Allowance';
$LANG['abs_factor'] = 'Factor';
$LANG['abs_approval_required'] = 'Approval Required';
$LANG['abs_manager_only'] = 'Manager Only';
$LANG['abs_hide_in_profile'] = 'Hide in Profile';
$LANG['abs_confidential'] = 'Confidential';

//
// Absence Actions
//
$LANG['abs_create_success'] = 'Absence type created successfully';
$LANG['abs_update_success'] = 'Absence type updated successfully';
$LANG['abs_delete_success'] = 'Absence type deleted successfully';
$LANG['abs_delete_confirm'] = 'Are you sure you want to delete absence type "%s"?';

//
// Absence Validation
//
$LANG['abs_name_required'] = 'Absence name is required';
$LANG['abs_name_exists'] = 'An absence type with this name already exists';
$LANG['abs_symbol_required'] = 'Symbol is required';
$LANG['abs_color_invalid'] = 'Please select a valid color';

//
// Absence Summary
//
$LANG['absum_title'] = 'Absence Summary';
$LANG['absum_user'] = 'User';
$LANG['absum_period'] = 'Period';
$LANG['absum_total'] = 'Total Days';
$LANG['absum_bytype'] = 'By Type';
$LANG['absum_export'] = 'Export Summary';

//
// Absence Summary Modals
//
$LANG['absum_modalYearTitle'] = 'Select the Year for the Summary';
$LANG['absum_modalUserTitle'] = 'Select User for Summary';
$LANG['absum_modalTypeTitle'] = 'Select Absence Types';

//
// Absence Allowances
//
$LANG['abs_allowance_annual'] = 'Annual Allowance';
$LANG['abs_allowance_taken'] = 'Days Taken';
$LANG['abs_allowance_remaining'] = 'Days Remaining';
$LANG['abs_allowance_carryover'] = 'Carryover from Previous Year';

//
// Absence Status
//
$LANG['abs_status_pending'] = 'Pending Approval';
$LANG['abs_status_approved'] = 'Approved';
$LANG['abs_status_declined'] = 'Declined';
$LANG['abs_status_cancelled'] = 'Cancelled';

//
// Absence Options
//
$LANG['abs_allow_active'] = 'Allow during active periods';
$LANG['abs_count_business_days'] = 'Count business days only';
$LANG['abs_exclude_weekends'] = 'Exclude weekends';
$LANG['abs_exclude_holidays'] = 'Exclude holidays';
?>
```

## Complex Controller Examples

### Multi-File Controller Example

#### Controllers that use multiple language files:
```php
// Controller: absenceedit
// Loads: core.php + absence.php + calendar.php

// From core.php
$LANG['btn_save']           // Save button
$LANG['alert_success_title'] // Success alerts

// From absence.php  
$LANG['abs_name']           // Absence properties
$LANG['abs_edit_title']     // Page title

// From calendar.php
$LANG['cal_selMonth']       // Calendar selection
$LANG['caledit_selUser']    // User selection
```

### Statistics Controller Example

#### `src/languages/english/statistics.php`
```php
<?php
/**
 * TeamCal Neo Language File: Statistics
 * 
 * Contains language strings for reporting and analytics functionality.
 * Used by controllers: statistics, statsabsence, statsabstype, 
 *                     statspresence, statsremainder
 */

//
// Statistics Main
//
$LANG['stats_title'] = 'Statistics & Reports';
$LANG['stats_period'] = 'Period';
$LANG['stats_from'] = 'From';
$LANG['stats_to'] = 'To';
$LANG['stats_generate'] = 'Generate Report';

//
// Statistics Types
//
$LANG['stats_absence'] = 'Absence Statistics';
$LANG['stats_presence'] = 'Presence Statistics';
$LANG['stats_remainder'] = 'Remainder Statistics';
$LANG['stats_abstype'] = 'Absence Type Statistics';

//
// Statistics Display
//
$LANG['stats_byusers'] = '(Per User)';
$LANG['stats_bygroups'] = '(Per Group)';
$LANG['stats_bytypes'] = '(Per Type)';
$LANG['stats_total'] = 'Total';
$LANG['stats_average'] = 'Average';
$LANG['stats_minimum'] = 'Minimum';
$LANG['stats_maximum'] = 'Maximum';

//
// Statistics Export
//
$LANG['stats_export_excel'] = 'Export to Excel';
$LANG['stats_export_pdf'] = 'Export to PDF';
$LANG['stats_export_csv'] = 'Export to CSV';

//
// Chart Labels
//
$LANG['stats_chart_days'] = 'Days';
$LANG['stats_chart_users'] = 'Users';
$LANG['stats_chart_percentage'] = 'Percentage';
?>
```

## Configuration Example

### System Configuration Language File

#### `src/languages/english/config.php`
```php
<?php
/**
 * TeamCal Neo Language File: Configuration
 * 
 * Contains language strings for system configuration and settings.
 * Used by controllers: config
 */

//
// Configuration Sections
//
$LANG['config_general'] = 'General Settings';
$LANG['config_database'] = 'Database Settings';
$LANG['config_email'] = 'Email Settings';
$LANG['config_ldap'] = 'LDAP Settings';
$LANG['config_security'] = 'Security Settings';

//
// General Configuration
//
$LANG['config_app_name'] = 'Application Name';
$LANG['config_app_url'] = 'Application URL';
$LANG['config_timezone'] = 'Default Timezone';
$LANG['config_language'] = 'Default Language';
$LANG['config_theme'] = 'Default Theme';

//
// Security Configuration
//
$LANG['config_session_timeout'] = 'Session Timeout (minutes)';
$LANG['config_password_min_length'] = 'Minimum Password Length';
$LANG['config_password_require_uppercase'] = 'Require Uppercase Letters';
$LANG['config_password_require_numbers'] = 'Require Numbers';
$LANG['config_password_require_symbols'] = 'Require Symbols';
$LANG['config_enable_2fa'] = 'Enable Two-Factor Authentication';

//
// Email Configuration
//
$LANG['config_smtp_host'] = 'SMTP Host';
$LANG['config_smtp_port'] = 'SMTP Port';
$LANG['config_smtp_username'] = 'SMTP Username';
$LANG['config_smtp_password'] = 'SMTP Password';
$LANG['config_smtp_encryption'] = 'SMTP Encryption';
$LANG['config_from_email'] = 'From Email Address';
$LANG['config_from_name'] = 'From Name';

//
// Configuration Actions
//
$LANG['config_save_success'] = 'Configuration saved successfully';
$LANG['config_save_failed'] = 'Failed to save configuration';
$LANG['config_test_email'] = 'Send Test Email';
$LANG['config_test_ldap'] = 'Test LDAP Connection';
$LANG['config_backup_db'] = 'Backup Database';
$LANG['config_restore_db'] = 'Restore Database';
?>
```

## Best Practices from Examples

### 1. Logical Grouping
```php
//
// Section Comment
//
$LANG['prefix_key1'] = 'Value 1';
$LANG['prefix_key2'] = 'Value 2';

//
// Another Section
//
$LANG['other_key1'] = 'Other Value 1';
```

### 2. Consistent Naming
```php
// Actions follow pattern: [entity]_[action]_[result]
$LANG['user_create_success'] = 'User created successfully';
$LANG['user_update_success'] = 'User updated successfully';
$LANG['user_delete_success'] = 'User deleted successfully';

// Properties follow pattern: [entity]_[property]
$LANG['user_username'] = 'Username';
$LANG['user_email'] = 'Email Address';
$LANG['user_active'] = 'Active';
```

### 3. Placeholder Support
```php
// Use %s for string placeholders
$LANG['user_delete_confirm'] = 'Are you sure you want to delete user "%s"?';
$LANG['cal_title'] = '%s %s - %s';  // Year Month - Region

// Use %d for number placeholders
$LANG['stats_days_total'] = 'Total: %d days';
```

### 4. Help Text and Comments
```php
// Provide helpful context
$LANG['cal_selAbsence_comment'] = 'Select an absence type to filter the calendar display.';
$LANG['login_2fa_help'] = 'Enter the 6-digit code from your authenticator app.';
```

### 5. Status and State Management
```php
// Cover all possible states
$LANG['user_status_active'] = 'Active';
$LANG['user_status_inactive'] = 'Inactive';
$LANG['user_status_locked'] = 'Locked';
$LANG['user_status_pending'] = 'Pending Approval';
```

These examples demonstrate how to organize language files effectively while maintaining consistency, clarity, and ease of maintenance across the entire application.