<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Notification Helper Functions
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

//-----------------------------------------------------------------------------
/**
 * If a user was activatd by the admin we send him a mail about it
 *
 * @param string $email Recipient's email address
 *
 * @return bool True if email was sent successfully, false otherwise
 */
function sendAccountActivatedMail(string $email): bool {
  global $C, $LANG, $LOG;
  
  //
  // Input validation
  //
  if (!validEmail($email)) {
    return false;
  }
  
  try {
    $language = $C->read('defaultLanguage');
    $appTitle = $C->read('appTitle');
    
    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/templates/email_html.html',
      'intro' => WEBSITE_ROOT . '/templates/' . $language . '/intro.html',
      'body' => WEBSITE_ROOT . '/templates/' . $language . '/body_user_account_activated.html',
      'outro' => WEBSITE_ROOT . '/templates/' . $language . '/outro.html'
    ];
    
    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        throw new Exception("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }
    
    $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_user_account_activated']);
    
    //
    // Build message with all replacements
    //
    $message = $templates['message'];
    $replacements = [
      '%intro%' => $templates['intro'],
      '%body%' => $templates['body'],
      '%outro%' => $templates['outro'],
      '%app_name%' => $appTitle,
      '%app_url%' => WEBSITE_URL
    ];
    
    $message = str_replace(array_keys($replacements), array_values($replacements), $message);
    
    return sendEmail($email, $subject, $message);
  } catch (Exception $e) {
    //
    // Log error using the Log class
    //
    if (isset($LOG)) {
      $LOG->logEvent("logRegistration", "System", "Failed to send account activation email: ", $e->getMessage());
    }
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * If a user was added or updated we send him an info to let him know.
 * Esepcially when he was added he needs to know what URL to navigate to and
 * how to login.
 *
 * @param string $email Recipient's email address
 * @param string $username The username created
 * @param string $password The password created
 *
 * @return bool True if email was sent successfully, false otherwise
 */
function sendAccountCreatedMail(string $email, string $username, string $password): bool {
  global $C, $LANG, $LOG;
  
  //
  // Input validation
  //
  if (!validEmail($email)) {
    return false;
  }
  
  try {
    $language = $C->read('defaultLanguage');
    $appTitle = $C->read('appTitle');
    
    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/templates/email_html.html',
      'intro' => WEBSITE_ROOT . '/templates/' . $language . '/intro.html',
      'body' => WEBSITE_ROOT . '/templates/' . $language . '/body_user_account_created.html',
      'outro' => WEBSITE_ROOT . '/templates/' . $language . '/outro.html'
    ];
    
    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        throw new Exception("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }
    
    $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_user_account_created']);
    
    //
    // Build message with all replacements
    //
    $message = $templates['message'];
    $replacements = [
      '%intro%' => $templates['intro'],
      '%body%' => $templates['body'],
      '%outro%' => $templates['outro'],
      '%app_name%' => $appTitle,
      '%app_url%' => WEBSITE_URL,
      '%username%' => $username,
      '%password%' => $password
    ];
    
    $message = str_replace(array_keys($replacements), array_values($replacements), $message);
    
    return sendEmail($email, $subject, $message);
  } catch (Exception $e) {
    //
    // Log error using the Log class
    //
    if (isset($LOG)) {
      $LOG->logEvent("logRegistration", "System", "Failed to send account creation email: ", $e->getMessage());
    }
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * If a user has registered and admin approval is needed, we send a mail to admin.
 *
 * @param string $email Recipient's email address
 * @param string $username The username created
 * @param string $lastname The user's lastname
 * @param string $firstname The user's firstname
 *
 * @return bool True if email was sent successfully, false otherwise
 */
function sendAccountNeedsApprovalMail(string $email, string $username, string $lastname, string $firstname): bool {
  global $C, $LANG, $LOG;
  
  //
  // Input validation
  //
  if (!validEmail($email)) {
    return false;
  }
  
  try {
    $language = $C->read('defaultLanguage');
    $appTitle = $C->read('appTitle');
    
    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/templates/email_html.html',
      'intro' => WEBSITE_ROOT . '/templates/' . $language . '/intro.html',
      'body' => WEBSITE_ROOT . '/templates/' . $language . '/body_user_account_needs_approval.html',
      'outro' => WEBSITE_ROOT . '/templates/' . $language . '/outro.html'
    ];
    
    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        throw new Exception("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }
    
    $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_user_account_needs_approval']);
    
    //
    // Build message with all replacements
    //
    $message = $templates['message'];
    $replacements = [
      '%intro%' => $templates['intro'],
      '%body%' => $templates['body'],
      '%outro%' => $templates['outro'],
      '%app_name%' => $appTitle,
      '%app_url%' => WEBSITE_URL,
      '%username%' => $username,
      '%lastname%' => $lastname,
      '%firstname%' => $firstname
    ];
    
    $message = str_replace(array_keys($replacements), array_values($replacements), $message);
    
    return sendEmail($email, $subject, $message);
  } catch (Exception $e) {
    //
    // Log error using the Log class
    //
    if (isset($LOG)) {
      $LOG->logEvent("logRegistration", "System", "Failed to send account approval request email: ", $e->getMessage());
    }
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * If a user has registered we send him a mail with the verification link.
 *
 * @param string $email Recipient's email address
 * @param string $username The username created
 * @param string $lastname The user's lastname
 * @param string $firstname The user's firstname
 * @param string $verifycode The verification code for the user
 *
 * @return bool True if email was sent successfully, false otherwise
 */
function sendAccountRegisteredMail(string $email, string $username, string $lastname, string $firstname, string $verifycode): bool {
  global $C, $LANG, $LOG;
  
  //
  // Input validation
  //
  if (!validEmail($email)) {
    return false;
  }
  
  try {
    $language = $C->read('defaultLanguage');
    $appTitle = $C->read('appTitle');
    
    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/templates/email_html.html',
      'intro' => WEBSITE_ROOT . '/templates/' . $language . '/intro.html',
      'body' => WEBSITE_ROOT . '/templates/' . $language . '/body_user_account_registered.html',
      'outro' => WEBSITE_ROOT . '/templates/' . $language . '/outro.html'
    ];
    
    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        throw new Exception("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }
    
    $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_user_account_registered']);
    
    //
    // Build message with all replacements
    //
    $message = $templates['message'];
    $replacements = [
      '%intro%' => $templates['intro'],
      '%body%' => $templates['body'],
      '%outro%' => $templates['outro'],
      '%app_name%' => $appTitle,
      '%app_url%' => WEBSITE_URL,
      '%verifycode%' => $verifycode,
      '%username%' => $username,
      '%lastname%' => $lastname,
      '%firstname%' => $firstname
    ];
    
    $message = str_replace(array_keys($replacements), array_values($replacements), $message);
    
    return sendEmail($email, $subject, $message);
  } catch (Exception $e) {
    //
    // Log error using the Log class
    //
    if (isset($LOG)) {
      $LOG->logEvent("logRegistration", "System", "Failed to send account registration email: ", $e->getMessage());
    }
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * If a user has tried to verify his account with an incorrect verify code
 * we send a mail to admin about it.
 *
 * @param string $email Recipient's email address
 * @param string $username The username created
 * @param string $vcode The verification code for the user
 * @param string $vcodeSubmitted The verification submitted by the user
 *
 * @return bool True if email was sent successfully, false otherwise
 */
function sendAccountVerificationMismatchMail(string $email, string $username, string $vcode, string $vcodeSubmitted): bool {
  global $C, $LANG, $LOG;
  
  //
  // Input validation
  //
  if (!validEmail($email)) {
    return false;
  }
  
  try {
    $language = $C->read('defaultLanguage');
    $appTitle = $C->read('appTitle');
    
    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/templates/email_html.html',
      'intro' => WEBSITE_ROOT . '/templates/' . $language . '/intro.html',
      'body' => WEBSITE_ROOT . '/templates/' . $language . '/body_user_account_verify_mismatch.html',
      'outro' => WEBSITE_ROOT . '/templates/' . $language . '/outro.html'
    ];
    
    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        throw new Exception("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }
    
    $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_user_account_mismatch']);
    
    //
    // Build message with all replacements
    //
    $message = $templates['message'];
    $replacements = [
      '%intro%' => $templates['intro'],
      '%body%' => $templates['body'],
      '%outro%' => $templates['outro'],
      '%app_name%' => $appTitle,
      '%app_url%' => WEBSITE_URL,
      '%username%' => $username,
      '%vcode%' => $vcode,
      '%vcode_submitted%' => $vcodeSubmitted
    ];
    
    $message = str_replace(array_keys($replacements), array_values($replacements), $message);
    
    return sendEmail($email, $subject, $message);
  } catch (Exception $e) {
    //
    // Log error using the Log class
    //
    if (isset($LOG)) {
      $LOG->logEvent("logRegistration", "System", "Failed to send verification mismatch email: ", $e->getMessage());
    }
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a group change event
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $groupname The groupname
 * @param string $groupdesc The group description
 *
 * @return bool True if all emails were sent successfully, false if any failed
 */
function sendGroupEventNotifications(string $event, string $groupname, string $groupdesc = ''): bool {
  global $C, $LANG, $U, $UO, $LOG;
  
  $events = ['changed', 'created', 'deleted'];
  
  if (!in_array($event, $events)) {
    return false;
  }
  
  try {
    $language = $C->read('defaultLanguage');
    $appTitle = $C->read('appTitle');
    
    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/templates/email_html.html',
      'intro' => WEBSITE_ROOT . '/templates/' . $language . '/intro.html',
      'body' => WEBSITE_ROOT . '/templates/' . $language . '/body_group_' . $event . '.html',
      'outro' => WEBSITE_ROOT . '/templates/' . $language . '/outro.html'
    ];
    
    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        throw new Exception("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }
    
    $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_group_' . $event]);
    
    //
    // Build message with all replacements
    //
    $message = $templates['message'];
    $replacements = [
      '%intro%' => $templates['intro'],
      '%body%' => $templates['body'],
      '%outro%' => $templates['outro'],
      '%app_name%' => $appTitle,
      '%app_url%' => WEBSITE_URL,
      '%groupname%' => $groupname,
      '%groupdesc%' => $groupdesc
    ];
    
    $message = str_replace(array_keys($replacements), array_values($replacements), $message);
    
    //
    // Get all users and send notifications
    //
    $users = $U->getAll('lastname', 'firstname', 'ASC', false, true);
    $allSuccessful = true;
    
    foreach ($users as $profile) {
      if ($UO->read($profile['username'], 'notifyGroupEvents')) {
        if (!sendEmail($profile['email'], $subject, $message)) {
          $allSuccessful = false;
          if (isset($LOG)) {
            $LOG->logEvent("logGroup", "System", "Failed to send group event notification to {$profile['email']}: ", "Email send failed");
          }
        }
      }
    }
    
    return $allSuccessful;
  } catch (Exception $e) {
    //
    // Log error using the Log class
    //
    if (isset($LOG)) {
      $LOG->logEvent("logGroup", "System", "Failed to send group event notifications: ", $e->getMessage());
    }
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends a password reset token mail
 *
 * @param string $email Recipient's email address
 * @param string $username The username created
 * @param string $lastname The user's lastname
 * @param string $firstname The user's firstname
 * @param string $token The password reset token
 *
 * @return bool True if email was sent successfully, false otherwise
 */
function sendPasswordResetMail(string $email, string $username, string $lastname, string $firstname, string $token): bool {
  global $C, $LANG, $LOG;
  
  //
  // Input validation
  //
  if (!validEmail($email)) {
    return false;
  }
  
  try {
    $language = $C->read('defaultLanguage');
    $appTitle = $C->read('appTitle');
    
    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/templates/email_html.html',
      'intro' => WEBSITE_ROOT . '/templates/' . $language . '/intro.html',
      'body' => WEBSITE_ROOT . '/templates/' . $language . '/body_user_pwdreq.html',
      'outro' => WEBSITE_ROOT . '/templates/' . $language . '/outro.html'
    ];
    
    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        throw new Exception("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }
    
    $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_password_reset']);
    
    //
    // Build message with all replacements
    //
    $message = $templates['message'];
    $replacements = [
      '%intro%' => $templates['intro'],
      '%body%' => $templates['body'],
      '%outro%' => $templates['outro'],
      '%app_name%' => $appTitle,
      '%app_url%' => WEBSITE_URL,
      '%token%' => $token,
      '%username%' => $username,
      '%lastname%' => $lastname,
      '%firstname%' => $firstname
    ];
    
    $message = str_replace(array_keys($replacements), array_values($replacements), $message);
    
    return sendEmail($email, $subject, $message);
  } catch (Exception $e) {
    //
    // Log error using the Log class
    //
    if (isset($LOG)) {
      $LOG->logEvent("logUser", "System", "Failed to send password reset email: ", $e->getMessage());
    }
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a role change event
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $rolename The role name
 * @param string $roledesc The role description
 *
 * @return bool True if all emails were sent successfully, false if any failed
 */
function sendRoleEventNotifications(string $event, string $rolename, string $roledesc = ''): bool {
  global $C, $LANG, $U, $UO, $LOG;
  
  $events = ['changed', 'created', 'deleted'];
  
  if (!in_array($event, $events)) {
    return false;
  }
  
  try {
    $language = $C->read('defaultLanguage');
    $appTitle = $C->read('appTitle');
    
    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/templates/email_html.html',
      'intro' => WEBSITE_ROOT . '/templates/' . $language . '/intro.html',
      'body' => WEBSITE_ROOT . '/templates/' . $language . '/body_role_' . $event . '.html',
      'outro' => WEBSITE_ROOT . '/templates/' . $language . '/outro.html'
    ];
    
    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        throw new Exception("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }
    
    $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_role_' . $event]);
    
    //
    // Build message with all replacements
    //
    $message = $templates['message'];
    $replacements = [
      '%intro%' => $templates['intro'],
      '%body%' => $templates['body'],
      '%outro%' => $templates['outro'],
      '%app_name%' => $appTitle,
      '%app_url%' => WEBSITE_URL,
      '%rolename%' => $rolename,
      '%roledesc%' => $roledesc
    ];
    
    $message = str_replace(array_keys($replacements), array_values($replacements), $message);
    
    //
    // Get all users and send notifications
    //
    $users = $U->getAll('lastname', 'firstname', 'ASC', false, true);
    $allSuccessful = true;
    
    foreach ($users as $profile) {
      if ($UO->read($profile['username'], 'notifyRoleEvents')) {
        if (!sendEmail($profile['email'], $subject, $message)) {
          $allSuccessful = false;
          if (isset($LOG)) {
            $LOG->logEvent("logRole", "System", "Failed to send role event notification to {$profile['email']}: ", "Email send failed");
          }
        }
      }
    }
    
    return $allSuccessful;
  } catch (Exception $e) {
    //
    // Log error using the Log class
    //
    if (isset($LOG)) {
      $LOG->logEvent("logRole", "System", "Failed to send role event notifications: ", $e->getMessage());
    }
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a user change event
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $username The username
 * @param string $firstname The firstname
 * @param string $lastname The lastname
 *
 * @return bool True if all emails were sent successfully, false if any failed
 */
function sendUserEventNotifications(string $event, string $username, string $firstname, string $lastname): bool {
  global $C, $LANG, $U, $UO, $LOG;
  
  $events = ['created', 'changed', 'deleted'];
  
  if (!in_array($event, $events)) {
    return false;
  }
  
  try {
    $language = $C->read('defaultLanguage');
    $appTitle = $C->read('appTitle');
    
    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/templates/email_html.html',
      'intro' => WEBSITE_ROOT . '/templates/' . $language . '/intro.html',
      'body' => WEBSITE_ROOT . '/templates/' . $language . '/body_user_' . $event . '.html',
      'outro' => WEBSITE_ROOT . '/templates/' . $language . '/outro.html'
    ];
    
    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        throw new Exception("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }
    
    $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_user_account_' . $event]);
    
    //
    // Build message with all replacements
    //
    $message = $templates['message'];
    $replacements = [
      '%intro%' => $templates['intro'],
      '%body%' => $templates['body'],
      '%outro%' => $templates['outro'],
      '%app_name%' => $appTitle,
      '%app_url%' => WEBSITE_URL,
      '%username%' => $username,
      '%firstname%' => $firstname,
      '%lastname%' => $lastname
    ];
    
    $message = str_replace(array_keys($replacements), array_values($replacements), $message);
    
    //
    // Get all users and send notifications
    //
    $users = $U->getAll('lastname', 'firstname', 'ASC', false, true);
    $allSuccessful = true;
    
    foreach ($users as $profile) {
      if ($UO->read($profile['username'], 'notifyUserEvents')) {
        if (!sendEmail($profile['email'], $subject, $message)) {
          $allSuccessful = false;
          if (isset($LOG)) {
            $LOG->logEvent("logUser", "System", "Failed to send user event notification to {$profile['email']}: ", "Email send failed");
          }
        }
      }
    }
    
    return $allSuccessful;
  } catch (Exception $e) {
    //
    // Log error using the Log class
    //
    if (isset($LOG)) {
      $LOG->logEvent("logUser", "System", "Failed to send user event notifications: ", $e->getMessage());
    }
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends an HTML eMail, either via SMTP or regular PHP mail
 * Requires the PEAR Mail package installed on the server that TcNeo is running on
 *
 * @param string $to eMail to address
 * @param string $subject eMail subject
 * @param string $body eMail body
 * @param string $from eMail from address (optional)
 *
 * @return bool Email success
 */
function sendEmail(string $to, string $subject, string $body, string $from = ''): bool {
  global $C;
  $debug = false;

  error_reporting(E_ALL);

  $from_regexp = preg_match('/<(.*?)>/', $from, $fetch);

  //
  // Set From and ReplyTo
  //
  if ((!strlen($from)) || ($from_regexp && ($fetch[1] == $C->read("mailReply")))) {
    $from = $replyto = mb_encode_mimeheader($C->read("mailFrom")) . " <" . $C->read("mailReply") . ">";
    $from_mailonly = $C->read("mailReply");
  } elseif ($from_regexp) {
    $from_mailonly = $fetch[1];
    $replyto = mb_encode_mimeheader($from);
  }

  //
  // "To" has to be a valid email or comma separated list of valid emails.
  // It might be empty if a user to be notified has not setup his email address
  //
  $to = rtrim($to, ', '); // remove the last ", " if exists
  $to = rtrim($to, ',');  // remove the last "," if exists
  $toArray = explode(",", $to);
  $toValid = "";
  foreach ($toArray as $toPiece) {
    if (!validEmail($toPiece)) {
      $toValid .= $C->read("mailReply") . ",";
    } else {
      $toValid .= $toPiece . ",";
    }
  }
  $toValid = rtrim($toValid, ','); // remove the last "," if exists (just in case)

  if ($C->read("mailSMTP")) {
    //
    // SMTP Mail - Check if PEAR Mail is available
    //
    if (!@include_once 'Mail.php' || !class_exists('Mail') || !class_exists('PEAR')) {
      error_log("PEAR Mail package not found - falling back to PHP mail()");
      // Fall back to regular PHP mail
      goto regular_mail;
    }
    $host = $C->read("mailSMTPHost");
    $port = $C->read("mailSMTPPort");
    $username = $C->read("mailSMTPusername");
    $password = $C->read("mailSMTPPassword");
    if ($C->read("mailSMTPSSL")) {
      $ssl = "ssl://";
    } else {
      $ssl = "";
    }

    //
    // SMTP requires a valid email address in the From field
    //
    if (!validEmail($from_mailonly)) {
      $from = $replyto = mb_encode_mimeheader($C->read("mailFrom")) . " <" . $C->read("mailReply") . ">";
    }

    $headers = array(
      'From' => $from,
      'Reply-To' => $replyto,
      'To' => $toValid,
      'Subject' => $subject,
      'Content-type' => "text/html; charset=iso-8859-1"
    );

    //
    // Put an HTML envelope around the body
    //
    $body = '<html><body>' . $body . '</body></html>';

    // Use variable function to avoid lint errors for external PEAR classes
    $mailClass = 'Mail';
    
    if ($C->read("mailSMTPAnonymous")) {
      $smtp = @call_user_func(array($mailClass, 'factory'), 'smtp', array(
        'host' => $ssl . $host,
        'port' => $port,
        'auth' => false
      ));
    } else {
      $smtp = @call_user_func(array($mailClass, 'factory'), 'smtp', array(
        'host' => $ssl . $host,
        'port' => $port,
        'auth' => true,
        'username' => $username,
        'password' => $password
      ));
    }

    $mail = @$smtp->send($toValid, $headers, $body);

    // Use variable function to avoid lint errors for external PEAR classes
    $pearClass = 'PEAR';
    if (class_exists($pearClass) && @call_user_func(array($pearClass, 'isError'), $mail)) {
      //
      // Log SMTP error instead of displaying it directly
      //
      error_log("SMTP Error: " . $mail->getMessage());
      return false;
    } else {
      return true;
    }
  } else {
    regular_mail:
    //
    // Regular PHP Mail
    //
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $replyto;
    $body = '<html><body>' . $body . '</body></html>';
    $result = mail($toValid, $subject, $body, $headers);

    //
    // Enable to debug mail content
    //
    if ($debug) {
      print "To: " . $toValid . "<br>" . "From: " . $from . "\r\n" . $body;
      die();
    }
    return $result;
  }
}

//-----------------------------------------------------------------------------
/**
 * Validate an email address.
 *
 * @param string $email The email address to validate
 *
 * @return boolean True or False indicating validity
 */
function validEmail(string $email): bool {
  $isValid = true;
  $atIndex = strrpos($email, "@");
  if (is_bool($atIndex) && !$atIndex) {
    $isValid = false;
  } else {
    $domain = substr($email, $atIndex + 1);
    $local = substr($email, 0, $atIndex);
    $localLen = strlen($local);
    $domainLen = strlen($domain);
    if ($localLen < 1 || $localLen > 64) {
      //
      // local part length exceeded
      //
      $isValid = false;
    } elseif ($domainLen < 1 || $domainLen > 255) {
      //
      // domain part length exceeded
      //
      $isValid = false;
    } elseif ($local[0] == '.' || $local[$localLen - 1] == '.') {
      //
      // local part starts or ends with '.'
      //
      $isValid = false;
    } elseif (preg_match('/\\.\\./', $local)) {
      //
      // local part has two consecutive dots
      //
      $isValid = false;
    } elseif (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
      //
      // character not valid in domain part
      //
      $isValid = false;
    } elseif (preg_match('/\\.\\./', $domain)) {
      //
      // domain part has two consecutive dots
      //
      $isValid = false;
    } elseif (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local))) {
      //
      // character not valid in local part unless
      // local part is quoted
      //
      if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local))) {
        $isValid = false;
      }
    }
  }
  return $isValid;
}
