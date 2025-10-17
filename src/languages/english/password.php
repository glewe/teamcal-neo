<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Language strings English: Password
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2025 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.3.0
 */
$LANG['pwdreq_title'] = 'Password Reset';
$LANG['pwdreq_alert_failed'] = 'Please provide a valid E-mail address.';
$LANG['pwdreq_alert_notfound'] = 'User Not Found';
$LANG['pwdreq_alert_notfound_text'] = 'No user account with this E-mail address could be found.';
$LANG['pwdreq_alert_success'] = 'An E-mail with instructions to reset the password was sent.';
$LANG['pwdreq_email'] = 'E-mail';
$LANG['pwdreq_email_comment'] = 'Please enter the E-mail address of your user account. A mail with further instructions to reset your password will be sent to it.';
$LANG['pwdreq_selectUser'] = 'Select User';
$LANG['pwdreq_selectUser_comment'] = 'Several users were found with this E-Mail address. Please select the user for which the password shall be reset.';

$LANG['password_rules_low'] = '<br>The password strength is currently set to "low", resulting in the following rules:<br>
      - At least 4 characters<br>';
$LANG['password_rules_medium'] = '<br>The password strength is currently set to "medium", resulting in the following rules:<br>
      - At least 6 characters<br>
      - At least 1 capital letter<br>
      - At least 1 small letter<br>
      - At least 1 number<br>';
$LANG['password_rules_high'] = '<br>The password strength is currently set to "high", resulting in the following rules:<br>
      - At least 8 characters<br>
      - At least 1 capital letter<br>
      - At least 1 small letter<br>
      - At least 1 number<br>
      - At least 1 special character<br>';
