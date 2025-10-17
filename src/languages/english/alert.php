<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: Alerts
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['alert_alert_title'] = 'ALERT';
$LANG['alert_danger_title'] = 'ERROR';
$LANG['alert_info_title'] = 'INFORMATION';
$LANG['alert_success_title'] = 'SUCCESS';
$LANG['alert_warning_title'] = 'WARNING';
$LANG['alert_captcha_wrong'] = 'Captcha code wrong';
$LANG['alert_captcha_wrong_text'] = 'The Captcha code was incorrect. Please try again.';
$LANG['alert_captcha_wrong_help'] = 'The Captcha code must be entered as displayed. Capitalization is not relevant.';
$LANG['alert_controller_not_found_subject'] = 'Controller not found';
$LANG['alert_controller_not_found_text'] = 'The controller "%1%" could not be found.';
$LANG['alert_controller_not_found_help'] = 'Please check your installation. The file does not exist or you may not have permission to read it.';
$LANG['alert_csrf_invalid_subject'] = 'Security Token Invalid';
$LANG['alert_csrf_invalid_text'] = 'The request submitted to the application had a missing or invalid security token.';
$LANG['alert_csrf_invalid_help'] = 'Please reload the page and try again. If the problem persists, please contact your administrator.';
$LANG['alert_decl_allowmonth_reached'] = 'The maximum amount of %1% per month for this absence type is exceeded.';
$LANG['alert_decl_allowweek_reached'] = 'The maximum amount of %1% per week for this absence type is exceeded.';
$LANG['alert_decl_allowyear_reached'] = 'The maximum amount of %1% per year for this absence type is exceeded.';
$LANG['alert_decl_approval_required'] = 'This absence type requires approval. It has been entered in your calendar but a daynote was added to indicate that it is not approved yet. Your manager was informed by mail.';
$LANG['alert_decl_approval_required_daynote'] = 'This absence was requested but is not approved yet.';
$LANG['alert_decl_before_date'] = 'Absence changes before the following date are not allowed: ';
$LANG['alert_decl_group_minpresent'] = 'Group minimum presence threshold reached for group(s): ';
$LANG['alert_decl_group_maxabsent'] = 'Group maximum absence threshold reached for group(s): ';
$LANG['alert_decl_group_threshold'] = 'Group absence threshold reached for your group(s): ';
$LANG['alert_decl_holiday_noabsence'] = 'This day is a holiday that does not allow absences.';
$LANG['alert_decl_period'] = 'Absence changes in the following period are not allowed: ';
$LANG['alert_decl_takeover'] = 'Absence type \'%s\' not enabled for take-over.';
$LANG['alert_decl_total_threshold'] = 'Total absence threshold reached.';
$LANG['alert_imp_subject'] = 'CSV import errors encountered';
$LANG['alert_imp_admin'] = 'Line %s: The username "admin" is not allowed to be imported.';
$LANG['alert_imp_columns'] = 'Line %s: There are less or more than %s columns.';
$LANG['alert_imp_email'] = 'Line %s: "%s" is not a valid email address.';
$LANG['alert_imp_exists'] = 'Line %s: Username "%s" already exists.';
$LANG['alert_imp_firstname'] = 'Line %s: The firstname "%s" does not comply to the allowed format (alphanumeric characters, blank, dot, dash and underscore).';
$LANG['alert_imp_gender'] = 'Line %s: Incorrect gender "%s" (male or female).';
$LANG['alert_imp_lastname'] = 'Line %s: The lastname "%s" does not comply to the allowed format (alphanumeric characters, blank, dot, dash and underscore).';
$LANG['alert_imp_username'] = 'Line %s: The username "%s" does not comply to the allowed format (alphanumeric characters, dot and @).';
$LANG['alert_input'] = 'Input validation failed';
$LANG['alert_input_alpha'] = 'This field allows alphabetical characters only.';
$LANG['alert_input_alpha_numeric'] = 'This field allows alphanumerical characters only.';
$LANG['alert_input_alpha_numeric_dash'] = 'This field allows alphanumerical characters only plus dash and underscore.';
$LANG['alert_input_alpha_numeric_dash_blank'] = 'This field allows alphanumerical characters only plus blank, dash and underscore.';
$LANG['alert_input_alpha_numeric_dash_blank_dot'] = 'This field allows alphanumerical characters only plus blank, dot, dash and underscore.';
$LANG['alert_input_alpha_numeric_dash_blank_special'] = 'This field allows alphanumerical characters only plus blank, dash, underscore and the special characters \'!@#$%^&*().';
$LANG['alert_input_ctype_graph'] = 'This field allows printable characters only.';
$LANG['alert_input_date'] = 'The date must be in ISO 8601 format, e.g. 2014-01-01.';
$LANG['alert_input_email'] = 'The E-mail address is invalid.';
$LANG['alert_input_equal'] = 'The value of this field must be the same as in the field "%s".';
$LANG['alert_input_equal_string'] = 'The string in this field must be "%s".';
$LANG['alert_input_exact_length'] = 'The input of this field must be exactly "%s" characters.';
$LANG['alert_input_greater_than'] = 'The value of this field must be greater than the field "%s".';
$LANG['alert_input_hex_color'] = 'This field allows only a six character long hexadecimal color code, e.g. FF5733.';
$LANG['alert_input_hexadecimal'] = 'This field allows hexadecimal characters only.';
$LANG['alert_input_ip_address'] = 'The input of this field is not a valid IP address.';
$LANG['alert_input_less_than'] = 'The value of this field must be less than the field "%s".';
$LANG['alert_input_match'] = 'The field "%s" must match field "%s".';
$LANG['alert_input_max_length'] = 'The input of this field can have a maximum of "%s" characters.';
$LANG['alert_input_min_length'] = 'The input of this field must have a minimum of "%s" characters.';
$LANG['alert_input_numeric'] = 'The input of this field must be numeric.';
$LANG['alert_input_phone_number'] = 'The input in this field must be a valid phone number, e.g. (555) 123 4567 oder +49 172 123 4567.';
$LANG['alert_input_pwdlow'] = 'The password must be at least 4 characters long and can contain small and capital letters, numbers and the following special characters: !@#$%^&*().';
$LANG['alert_input_pwdmedium'] = 'The password must be at least 6 characters long, must contain at least one small letter, at least one capital letter and at least one number. Allowed are small and capital letters, numbers and the following special characters: !@#$%^&*().';
$LANG['alert_input_pwdhigh'] = 'The password must be at least 8 characters long, must contain at least one small letter, at least one capital letter, at least one number and at least one special character. Allowed are small and capital letters, numbers and the following special characters: !@#$%^&*().';
$LANG['alert_input_regex_match'] = 'The input of this field did not match the regular expression "%s".';
$LANG['alert_input_required'] = 'This field is mandatory.';
$LANG['alert_input_username'] = 'This field allows alphanumerical characters, dash, underscore, dot and the @ sign.';
$LANG['alert_input_validation_subject'] = 'Input validation';
$LANG['alert_license_subject'] = 'License Management';
$LANG['alert_maintenance_subject'] = 'Site Under Maintenance';
$LANG['alert_maintenance_text'] = 'The site is currently set to "Under Maintenance". Regular users will not be able to use any feature.';
$LANG['alert_maintenance_help'] = 'As an administrator you can set the site back to active under Administration -> Framework Configuration -> System.';
$LANG['alert_no_data_subject'] = 'Invalid Data';
$LANG['alert_no_data_text'] = 'This operation was requested with invalid or insufficient data.';
$LANG['alert_no_data_help'] = 'The operation failed due to missing or invalid data.';
$LANG['alert_not_allowed_subject'] = 'Access not allowed';
$LANG['alert_not_allowed_text'] = 'You do not have permission to access this page or feature.';
$LANG['alert_not_allowed_help'] = 'If you are not logged in, then public access to this page is not allowed. If you are logged in, your account role is not permitted to view this page.';
$LANG['alert_not_enabled_subject'] = 'Feature not enabled';
$LANG['alert_not_enabled_text'] = 'This feature is currently not enabled.';
$LANG['alert_perm_invalid'] = 'The new permission scheme name "%1%" is invalid. Choose upper or lower case characters or numbers from 0 to 9. Don\'t use blanks.';
$LANG['alert_perm_exists'] = 'The permission scheme "%1%" already exists. Use a different name or delete the old one first.';
$LANG['alert_perm_default'] = 'The "Default" permission scheme cannot be reset to itself.';
$LANG['alert_pwdTokenExpired_subject'] = 'Token Expired';
$LANG['alert_pwdTokenExpired_text'] = 'The token for resetting your password was valid for 24 hours and has expired.';
$LANG['alert_pwdTokenExpired_help'] = 'Go to the Login Screen and request a new one.';
$LANG['alert_reg_subject'] = 'User Registration';
$LANG['alert_reg_approval_needed'] = 'Your verification was successful. However, your account needs to be finally activated by an administrator. A mail has been sent to him/her.';
$LANG['alert_reg_success'] = 'Your verification was successful. You can now log in and use the application.';
$LANG['alert_reg_mismatch'] = 'The submitted verification code does not match the one we have on record. A mail has been sent to the admin to review your case.';
$LANG['alert_reg_no_user'] = 'The username cannot be found. Are you sure it was registered?';
$LANG['alert_reg_no_vcode'] = 'A verification code could not be found. Was it verified already? Please contact the administrator to check your account settings.';
$LANG['alert_secret_exists_subject'] = 'Two Factor Authentication Already Set Up';
$LANG['alert_secret_exists_text'] = 'A two factor authentication was already set up for your account.';
$LANG['alert_secret_exists_help'] = 'For security reasons, you cannot remove or reset it yourself. Please contact the administrator to do that for you so you can start a new onboarding process.';
$LANG['alert_upl_csv_subject'] = 'Upload CSV File';
$LANG['alert_upl_doc_subject'] = 'Upload Documents';
$LANG['alert_upl_img_subject'] = 'Upload Images';
