<?php
/**
 * notification.helper.php
 *
 * Collection of notification functions
 *
 * @category TeamCal Neo 
 * @version 1.4.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo '<script type="text/javascript">alert("Debug: ");</script>';

// ---------------------------------------------------------------------------
/**
 * If a user was activatd by the admin we send him a mail about it
 *
 * @param string $email Recipient's email address
 * @param string $username The username created
 * @param string $lastname The user's lastname
 * @param string $firstname The user's firstname
 */
function sendAccountActivatedMail($email, $username, $lastname, $firstname)
{
   global $C, $LANG;
   
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $to = $email;
   
   $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_user_account_activated']);
   $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
   $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
   $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_user_account_activated.html');
   $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');
   
   $message = str_replace('%intro%', $intro, $message);
   $message = str_replace('%body%', $body, $message);
   $message = str_replace('%outro%', $outro, $message);
    
   $message = str_replace('%app_name%', $appTitle, $message);
   $message = str_replace('%app_url%', WEBSITE_URL, $message);

   sendEmail($to, $subject, $message);
}

// ---------------------------------------------------------------------------
/**
 * If a user was added or updated we send him an info to let him know.
 * Esepcially when he was added he needs to know what URL to navigate to and
 * how to login.
 *
 * @param string $email Recipient's email address
 * @param string $username The username created
 * @param string $password The password created
 */
function sendAccountCreatedMail($email, $username, $password)
{
   global $C, $LANG;
   
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $to = $email;
   
   $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_user_account_created']);
   $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
   $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
   $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_user_account_created.html');
   $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');
   
   $message = str_replace('%intro%', $intro, $message);
   $message = str_replace('%body%', $body, $message);
   $message = str_replace('%outro%', $outro, $message);
   
   $message = str_replace('%app_name%', $appTitle, $message);
   $message = str_replace('%app_url%', WEBSITE_URL, $message);
   $message = str_replace('%username%', $username, $message);
   $message = str_replace('%password%', $password, $message);

   sendEmail($to, $subject, $message);
}

// ---------------------------------------------------------------------------
/**
 * If a user has registered and admin approval is needed, we send a mail to admin.
 *
 * @param string $email Recipient's email address
 * @param string $username The username created
 * @param string $lastname The user's lastname
 * @param string $firstname The user's firstname
 */
function sendAccountNeedsApprovalMail($email, $username, $lastname, $firstname)
{
   global $C, $LANG;
   
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $to = $email;
   
   $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_user_account_needs_approval']);
   $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
   $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
   $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_user_account_needs_approval.html');
   $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');
   
   $message = str_replace('%intro%', $intro, $message);
   $message = str_replace('%body%', $body, $message);
   $message = str_replace('%outro%', $outro, $message);
    
   $message = str_replace('%app_name%', $appTitle, $message);
   $message = str_replace('%app_url%', WEBSITE_URL, $message);
   $message = str_replace('%username%', $username, $message);
   $message = str_replace('%lastname%', $lastname, $message);
   $message = str_replace('%firstname%', $firstname, $message);

   sendEmail($to, $subject, $message);
}

// ---------------------------------------------------------------------------
/**
 * If a user has registered we send him a mail with the verification link.
 *
 * @param string $email Recipient's email address
 * @param string $username The username created
 * @param string $lastname The user's lastname
 * @param string $firstname The user's firstname
 * @param string $verifycode The verification code for the user
 */
function sendAccountRegisteredMail($email, $username, $lastname, $firstname, $verifycode)
{
   global $C, $LANG;
   
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $to = $email;
   
   $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_user_account_registered']);
   $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
   $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
   $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_user_account_registered.html');
   $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');
   
   $message = str_replace('%intro%', $intro, $message);
   $message = str_replace('%body%', $body, $message);
   $message = str_replace('%outro%', $outro, $message);
    
   $message = str_replace('%app_name%', $appTitle, $message);
   $message = str_replace('%app_url%', WEBSITE_URL, $message);
   $message = str_replace('%verifycode%', $verifycode, $message);
   $message = str_replace('%username%', $username, $message);
   $message = str_replace('%lastname%', $lastname, $message);
   $message = str_replace('%firstname%', $firstname, $message);

   sendEmail($to, $subject, $message);
}

// ---------------------------------------------------------------------------
/**
 * If a user has tried to verify his account with an incorrect verify code
 * we send a mail to admin about it.
 *
 * @param string $email Recipient's email address
 * @param string $username The username created
 * @param string $lastname The user's lastname
 * @param string $firstname The user's firstname
 * @param string $vcode The verification code for the user
 * @param string $vcodeSubmitted The verification submitted by the user
 */
function sendAccountVerificationMismatchMail($email, $username, $vcode, $vcodeSubmitted)
{
   global $C, $LANG;
   
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $to = $email;
   
   $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_user_account_mismatch']);
   $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
   $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
   $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_user_account_verify_mismatch.html');
   $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');
   
   $message = str_replace('%intro%', $intro, $message);
   $message = str_replace('%body%', $body, $message);
   $message = str_replace('%outro%', $outro, $message);
    
   $message = str_replace('%app_name%', $appTitle, $message);
   $message = str_replace('%app_url%', WEBSITE_URL, $message);
   $message = str_replace('%username%', $username, $message);
   $message = str_replace('%vcode%', $vcode, $message);
   $message = str_replace('%vcode_submitted%', $vcodeSubmitted, $message);
    
   sendEmail($to, $subject, $message);
}

// ---------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a group change event
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $groupname The groupname
 * @param string $groupdesc The group description
 */
function sendGroupEventNotifications($event, $groupname, $groupdesc = '')
{
   global $C, $LANG, $U, $UO;
   
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $events = array (
      'changed',
      'created',
      'deleted' 
   );
    
   if (in_array($event, $events))
   {
      $subject = $LANG['email_subject_group_' . $event];
      $subject = str_replace('%app_name%', $appTitle, $subject);
      
      $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
      $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
      $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_group_' . $event . '.html');
      $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');
      
      $message = str_replace('%intro%', $intro, $message);
      $message = str_replace('%body%', $body, $message);
      $message = str_replace('%outro%', $outro, $message);
      $message = str_replace('%app_name%', $appTitle, $message);
      $message = str_replace('%app_url%', WEBSITE_URL, $message);
      
      $message = str_replace('%groupname%', $groupname, $message);
      $message = str_replace('%groupdesc%', $groupdesc, $message);
      
      $users = $U->getAll('lastname', 'firstname', 'ASC', false, true);
      foreach ( $users as $profile )
      {
         if ($UO->read($profile['username'], 'notifyGroupEvents')) sendEmail($profile['email'], $subject, $message);
      }
   }
}

// ---------------------------------------------------------------------------
/**
 * Sends a password reset token mail
 *
 * @param string $email Recipient's email address
 * @param string $username The username created
 * @param string $lastname The user's lastname
 * @param string $firstname The user's firstname
 * @param string $token The password reset token
 */
function sendPasswordResetMail($email, $username, $lastname, $firstname, $token)
{
   global $C, $LANG;
    
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $to = $email;
    
   $subject = str_replace('%app_name%', $appTitle, $LANG['email_subject_password_reset']);
   $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
   $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
   $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_user_pwdreq.html');
   $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');
    
   $message = str_replace('%intro%', $intro, $message);
   $message = str_replace('%body%', $body, $message);
   $message = str_replace('%outro%', $outro, $message);

   $message = str_replace('%app_name%', $appTitle, $message);
   $message = str_replace('%app_url%', WEBSITE_URL, $message);
   $message = str_replace('%token%', $token, $message);
   $message = str_replace('%username%', $username, $message);
   $message = str_replace('%lastname%', $lastname, $message);
   $message = str_replace('%firstname%', $firstname, $message);

   sendEmail($to, $subject, $message);
}

// ---------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a role change event
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $rolename The role name
 * @param string $roledesc The role description
 */
function sendRoleEventNotifications($event, $rolename, $roledesc = '')
{
   global $C, $LANG, $U, $UO;
   
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $events = array (
      'changed',
      'created',
      'deleted' 
   );
   
   if (in_array($event, $events))
   {
      $subject = $LANG['email_subject_group_' . $event];
      $subject = str_replace('%app_name%', $appTitle, $subject);
      
      $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
      $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
      $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_role_' . $event . '.html');
      $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');
      
      $message = str_replace('%intro%', $intro, $message);
      $message = str_replace('%body%', $body, $message);
      $message = str_replace('%outro%', $outro, $message);
      $message = str_replace('%app_name%', $appTitle, $message);
      $message = str_replace('%app_url%', WEBSITE_URL, $message);
      
      $message = str_replace('%rolename%', $rolename, $message);
      $message = str_replace('%roledesc%', $roledesc, $message);
      
      $users = $U->getAll('lastname', 'firstname', 'ASC', false, true);
      foreach ( $users as $profile )
      {
         if ($UO->read($profile['username'], 'notifyRoleEvents')) sendEmail($profile['email'], $subject, $message);
      }
   }
}

// ---------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a user change event
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $username The username
 * @param string $firstname The firstname
 * @param string $lastname The lastname
 */
function sendUserEventNotifications($event, $username, $firstname, $lastname)
{
   global $C, $LANG, $U, $UO;
   
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $events = array (
      'created',
      'changed',
      'deleted' 
   );
   
   if (in_array($event, $events))
   {
      $subject = $LANG['email_subject_user_account_' . $event];
      $subject = str_replace('%app_name%', $appTitle, $subject);
      
      $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
      $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
      $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_user_' . $event . '.html');
      $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');
      
      $message = str_replace('%intro%', $intro, $message);
      $message = str_replace('%body%', $body, $message);
      $message = str_replace('%outro%', $outro, $message);
      $message = str_replace('%app_name%', $appTitle, $message);
      $message = str_replace('%app_url%', WEBSITE_URL, $message);
      
      $message = str_replace('%username%', $username, $message);
      $message = str_replace('%firstname%', $firstname, $message);
      $message = str_replace('%lastname%', $lastname, $message);
      
      $users = $U->getAll('lastname', 'firstname', 'ASC', false, true);
      foreach ( $users as $profile )
      {
         if ($UO->read($profile['username'], 'notifyUserEvents')) sendEmail($profile['email'], $subject, $message);
      }
   }
}

// ---------------------------------------------------------------------------
/**
 * Sends an HTML eMail, either via SMTP or regular PHP mail
 * Requires the PEAR Mail package installed on the server that TcNeo is running on
 *
 * @param string $to eMail to address
 * @param string $subject eMail subject
 * @param string $body eMail body
 * 
 * @return bool Email success
 */
function sendEmail($to, $subject, $body, $from = '')
{
   global $C, $CONF;
   
   error_reporting(E_ALL ^ E_STRICT);
   
   $from_regexp = preg_match('/<(.*?)>/', $from, $fetch);

   /**
    * Set From and ReplyTo
    */
   if ((!strlen($from)) or ($from_regexp and ($fetch[1] == $C->read("mailReply"))))
   {
      $from = $replyto = mb_encode_mimeheader($C->read("mailFrom")) . " <" . $C->read("mailReply") . ">";
      $from_mailonly = $C->read("mailReply");
   }
   else if ($from_regexp)
   {
      $from_mailonly = $fetch[1];
      $replyto = mb_encode_mimeheader($from);
   }
   
   /**
    * "To" has to be a valid email or comma separated list of valid emails.
    *
    * It might be empty if a user to be notified has not setup his email address
    */
   $to = rtrim($to,', '); // remove the last ", " if exists
   $to = rtrim($to,','); // remove the last "," if exists
   $toArray = explode(",", $to);
   $toValid = "";
   foreach ( $toArray as $toPiece )
   {
      if (!validEmail($toPiece)) $toValid .= $C->read("mailReply") . ",";
      else $toValid .= $toPiece . ",";
   }
   $toValid = rtrim($to,','); // remove the last "," if exists (just in case)
   
   if ($C->read("mailSMTP"))
   {
      /**
       * SMTP Mail
       */
      include_once ('Mail.php');
      
      $host = $C->read("mailSMTPHost");
      $port = $C->read("mailSMTPPort");
      $username = $C->read("mailSMTPusername");
      $password = $C->read("mailSMTPPassword");
      if ($C->read("mailSMTPSSL")) $ssl = "ssl://";
      else $ssl = "";
      
      /*
       * SMTP requires a valid email address in the From field
       */
      if (!validEmail($from_mailonly))
      {
         $from = $replyto = mb_encode_mimeheader($C->read("mailFrom")) . " <" . $C->read("mailReply") . ">";
      }
      
      $headers = array (
         'From' => $from,
         'Reply-To' => $replyto,
         'To' => $toValid,
         'Subject' => $subject,
         'Content-type' => "text/html; charset=iso-8859-1" 
      );
      
      /*
       * Put an HTML envelope around the body
       */
      $body = '<html><body>' . $body . '</body></html>';
      
      $smtp = @Mail::factory('smtp', array (
         'host' => $ssl . $host,
         'port' => $port,
         'auth' => true,
         'username' => $username,
         'password' => $password 
      ));
      
      $mail = @$smtp->send($toValid, $headers, $body);
      
      if ($error = @PEAR::isError($mail))
      {
         /*
          * Display SMTP error in a Javascript popup
          */
         $err = $mail->getMessage();
         $err .= "<table style=\"border-collapse: collapse;\">
                  <tr><td style=\"border: 1px solid #BBBBBB;\">From:</td><td style=\"border: 1px solid #BBBBBB;\">" . $headers['From'] . "</td></tr>
                  <tr><td style=\"border: 1px solid #BBBBBB;\">To:</td><td style=\"border: 1px solid #BBBBBB;\">" . $headers['To'] . "</td></tr>
                  <tr><td style=\"border: 1px solid #BBBBBB;\">Subject:</td><td style=\"border: 1px solid #BBBBBB;\">" . $headers['Subject'] . "</td></tr>
                  <tr><td style=\"border: 1px solid #BBBBBB;\">Body:</td><td style=\"border: 1px solid #BBBBBB;\"><pre>" . $body . "</pre></td></tr>
               </table>";
         showError("smtp", $err);
         return FALSE;
      }
      else
      {
         return TRUE;
      }
   }
   else
   {
      /**
       * Regular PHP Mail
       */
      $headers = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $headers .= "From: " . $from . "\r\n";
      $headers .= "Reply-To: " . $replyto;
      $body = '<html><body>' . $body . '</body></html>';
      $result = mail($toValid, $subject, $body, $headers);
      
      //
      // Enable to debug mail content
      //
      if (false)
      {
         print "To: " .$toValid . "<br>" . "From: " . $from . "\r\n" . $body;
      }
      
      return $result;
   }
}

// ---------------------------------------------------------------------------
/**
 * Validate an email address.
 *
 * @param string $email The email address to validate
 * 
 * @return boolean True or False indicating validity
 */
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex + 1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen - 1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local)))
      {
         // character not valid in local part unless
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local)))
         {
            $isValid = false;
         }
      }
   }
   return $isValid;
}
?>
