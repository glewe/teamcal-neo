<?php
/**
 * Login.class.php
 *
 * @category TeamCal Neo 
 * @version 0.9.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to manage login activities
 */
class Login
{
   var $user = '';
   var $salt = '';
   var $bad_logins = 0;
   var $grace_period = 0;
   var $min_pw_length = 0;
   var $pw_strength = 0;
   var $php_self = '';
   var $log = '';
   var $logtype = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $C, $_POST, $_SERVER; 
      
      $this->salt = $CONF['salt'];
      $this->cookie_name = $CONF['cookie_name'];
      $this->bad_logins = intval($C->read("badLogins"));
      $this->grace_period = intval($C->read("gracePeriod"));
      $this->min_pw_length = intval($C->read("pwdLength"));
      $this->pw_strength = intval($C->read("pwdStrength"));
      $this->php_self = $_SERVER['PHP_SELF'];
      $this->log = $CONF['db_table_log'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks the TeamCal cookie and if it exists and is valid and if the user
    * is logged in we get the user info from the database.
    *
    * @return string Username of the user logged in, or emtpy
    */
   function checkLogin()
   {
      global $U;
      
      /**
       * If the cookie is set, look up the username in the database
       */
      if (isset($_COOKIE[$this->cookie_name]))
      {
         // echo ("<script type=\"text/javascript\">alert(\"[checkLogin]\\nCookie '".$this->cookie_name."' is
         // set\")</script>");
         $array = explode(":", $_COOKIE[$this->cookie_name]);
         // echo ("<script type=\"text/javascript\">alert(\"[checkLogin]\\nCookie array[0]=".$array[0]."\\nCookie
         // array[1]=".$array[1]."\")</script>");
         if (!isset($array[1])) $array[1] = '';
         if (crypt($array[0], $this->salt) === $array[1])
         {
            $U->findByName($array[0]);
            return $U->username;
         }
         else
         {
            return false;
         }
      }
      else
      {
         // echo ("<script type=\"text/javascript\">alert(\"[checkLogin]\\nCookie '".$this->cookie_name."' is NOT
         // set\")</script>");
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Based on the global config parameter 'pw_strength' Passwords must be:
    * -min_pw_length long
    * -can't match username forward or backward
    * -mixed case
    * -have 1 number
    * -have 1 punctuation char
    *
    * @param string $uname Username trying to log in
    * @param string $pw Current password
    * @param string $pwnew1 New password
    * @param string $pwnew2 Repeated new password
    * @return integer $result
    *         10 - Username missing
    *         11 - Password missing
    *         12 - Password mismatch
    *         20 - Password too short
    *         30 - Password contains username
    *         31 - Password contains username backwards
    *         32 - New password is same as old
    *         40 - Password contains no number
    *         50 - Password contains no lower case character
    *         51 - Password contains no upper case character
    *         52 - Password contains no special characters
    */
   function isPasswordValid($uname = '', $pw = '', $pwnew1 = '', $pwnew2 = '')
   {
      if (!isset($this->pw_strength)) $this->pw_strength = 0;
      $result = 0;
      
      if (empty($uname)) return 10;
      if (empty($pwnew1) || empty($pwnew2)) return 11;
      if ($pwnew1 != $pwnew2) return 12;
      
      /**
       * MINIMUM LENGTH
       */
      if (strlen($pwnew1) < $this->min_pw_length) return 20;
      
      if ($this->pw_strength > 0)
      {
         /**
          * LOW STRENGTH
          * = anything allowed if min_pw_length and new<>old
          *
          * convert the password to lower case and strip out the
          * common number for letter substitutions
          * then lowercase the username as well.
          */
         $pw_lower = strtolower($pw);
         $pwnew1_lower = strtolower($pwnew1);
         $pwnew1_denum = strtr($pwnew1_lower, '5301!', 'seoll');
         $uname_lower = strtolower($uname);
         
         if (ereg($uname_lower, $pwnew1_denum)) return 30;
         if (ereg(strrev($uname_lower), $pwnew1_denum)) return 31;
         if ($pwnew1_lower == $pw_lower) return 32;
         
         if ($this->pw_strength > 1)
         {
            /**
             * MEDIUM STRENGTH
             */
            if (!ereg('[0-9]', $pwnew1)) return 40;
            
            if ($this->pw_strength > 2)
            {
               /**
                * HIGH STRENGTH
                */
               if (!ereg('[a-z]', $pwnew1)) return 50;
               if (!ereg('[A-Z]', $pwnew1)) return 51;
               if (!ereg('[^a-zA-Z0-9]', $pwnew1)) return 52;
            }
         }
         return $rstr;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * LDAP authentication
    * (Thanks to Aleksandr Babenko for the original code.)
    *
    * !!! Beta 2 mode !!! Use at own risk.
    *
    * retcode = 0 : successful LDAP authentication
    * retcode = 91 : password missing
    * retcode = 92 : LDAP user bind failed
    * retcode = 93 : Unable to connect to LDAP server
    * retcode = 94 : STARTTLS failed
    * retcode = 95 : No uid found
    * retcode = 96 : LDAP search bind failed
    *
    * @param string $uidpass LDAP password
    * @return integer Authentication return code
    */
   function ldapVerify($uidpass)
   {
      global $CONF, $U;
      
      $ldaprdn = $CONF['LDAP_DIT'];
      $ldappass = $CONF['LDAP_PASS'];
      $ldaptls = $CONF['LDAP_TLS'];
      $host = $CONF['LDAP_HOST'];
      $port = $CONF['LDAP_PORT'];
      // attributes to return
      $attr = array (
         "dn",
         "uid" 
      );
      $searchbase = $CONF['LDAP_SBASE'];
      
      /**
       * Force Fail on NULL password
       */
      if (!$uidpass) return 91;
      
      $ds = ldap_connect($host, $port); // Is always a ressource even if no connection possible (patch by Franz Gregor)
      ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); // Use v3 when possible
      if (!@ldap_bind($ds)) return 93; // Test anonymous bind => Unable to connect to LDAP server (patch by Franz Gregor)
      if ($ldaptls && !ldap_start_tls($ds)) return 94;
      if (!@ldap_bind($ds, $ldaprdn, $ldappass)) return 96; // (patch by Franz Gregor)
      
      /**
       * Search for user UID
       */
      if ($CONF['LDAP_ADS'])
      {
         if (!$info = ldap_first_entry($ds, ldap_search($ds, $searchbase, "sAMAccountName=" . $U->username, $attr))) return 95;
      }
      else
      {
         if (!$info = ldap_first_entry($ds, ldap_search($ds, $searchbase, "uid=" . $U->username, $attr))) return 95;
      }
      
      /**
       * Now authenticate the user using the user dn
       */
      $uiddn = ldap_get_dn($ds, $info);
      $ldapbind = ldap_bind($ds, $uiddn, $uidpass);
      
      /**
       * Close LDAP connection
       */
      ldap_close($ds);
      
      if ($ldapbind) return 0;
      else return 92;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Login.
    * Checks the login credentials and sets cookie if accepted
    *
    * Return Codes
    * retcode = 0 : Success
    * retcode = 1 : Username and/or password missing
    * retcode = 2 : User not found
    * retcode = 3 : Account locked
    * retcode = 4 : Password incorrect 1st time
    * retcode = 5 : Password incorrect 2nd time or more
    * retcode = 6 : Login disabled and still in grace period
    * retcode = 7 : Password incorrect (no bad login count)
    * retcode = 8 : Account not verified
    * retcode = 91 : LDAP error: password missing
    * retcode = 92 : LDAP error: bind failed
    * retcode = 93 : LDAP error: unable to connect
    * retcode = 94 : LDAP error: Start of TLS encryption failed
    * retcode = 95 : LDAP error: Username not found
    * retcode = 96 : LDAP error: Search bind failed
    *
    * @param string $loginname Username
    * @param string $loginpwd Password
    * @return integer Login return code
    */
   function login($loginname = '', $loginpwd = '')
   {
      global $C, $CONF, $U, $UO;
      
      $logged_in = 0;
      $showForm = 0;
      $retcode = 0;
      $bad_logins_now = 0;
      
      if (empty($loginname) or empty($loginpwd)) return 1;
      
      $now = date("U");
      
      if (!$U->findByName($loginname)) return 2; // User not found. If found U->username is now set.
      if ($U->locked) return 3; // Account is locked or not approved
      if ($UO->read($loginname, "verifycode")) return 8; // Account not verified.
      if ($U->onhold and ($now - $U->grace_start <= $this->grace_period)) return 6; // Login is locked for this account and grace period is not over yet.
      
      /**
       * At this point we know that the user is not ONHOLD or the grace period is over.
       * We can safely unset it.
       */
      $U->onhold = 0;
      
      /**
       * Now check the password
       */
      if ($CONF['LDAP_YES'] && $loginname != "admin")
      {
         /**
          * You need to have PHP LDAP libraries installed.
          *
          * The admin user is always logged in against the local database.
          * In case the LDAP does not work an admin login must still be possible.
          */
         $retcode = $this->ldapVerify($loginpwd);
      }
      else
      {
         /**
          * Otherwise use TcNeo authentication
          */
         $retcode = $this->tcneoVerify($loginpwd);
      }
      if ($retcode != 0) return $retcode;
      
      /**
       * Successful login!
       * Set up the tc cookie and save the uname so TeamCal can get it.
       */
      $secret = crypt($loginname, $this->salt);
      $value = $loginname . ":" . $secret;
      setcookie($this->cookie_name, ''); // Clear current cookie
      //setcookie($this->cookie_name, $value, time() + intval($C->read("cookieLifetime")), '', $_SERVER['HTTP_HOST'], false, true); // Set new cookie
      setcookie($this->cookie_name, $value, time() + intval($C->read("cookieLifetime")), '/'); // Set new cookie
      $U->bad_logins = 0;
      $U->grace_start = '';
      $U->last_login = date("YmdHis");
      $U->update($U->username);
      
      return 0;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Logs the current user out and clears the cookie
    */
   function logout()
   {
      setcookie($this->cookie_name, '', time() - 3600, '', $_SERVER['HTTP_HOST'], false, true);
      setcookie($this->cookie_name, false, time() - 60*100000, '/');
      setcookie($this->cookie_name, ''); // Clear current cookie
   }

   // ---------------------------------------------------------------------
   /**
    * Returns the current password rules
    *
    * @return string The current password rules
    */
   function pwRules()
   {
      switch ($this->pw_strength)
      {
         case 0 :
            $pws = "minimum";
            break;
         case 1 :
            $pws = "low";
            break;
         case 2 :
            $pws = "medium";
            break;
         case 3 :
            $pws = "maximum";
            break;
      }
       
      $errors = "<b>The Password \"level\" of TeamCal Neo is set to " . $pws . "</b>.<br>Passwords must be at least " . $this->min_pw_length . " characters long and a new password cannot be the same as the old one.";
       
      if ($this->pw_strength > 0) $errors .= "<br>The password cannot contain the username forward or backward. Also you can't use the numbers '53011' for the letters 'seoll'";
      if ($this->pw_strength > 1) $errors .= "The password must also contain at least one number";
      if ($this->pw_strength > 2) $errors .= "and it must contain one UPPER and one lower case letter and one punctuation character";
      if ($this->pw_strength > 0) $errors .= ".<br>";
       
      return $errors;
   }
 
   // ---------------------------------------------------------------------
   /**
    * TcNeo Authentication
    * Refactored local-database authentication method
    *
    * Return Codes
    * retcode = 0 : successful login
    * retcode = 4 : first bad login
    * retcode = 5 : second/higher bad login
    * retcode = 6 : too many bad logins
    * retcode = 7 : bad password
    *
    * @param string password
    * @return integer authentication return code
    */
   function tcneoVerify($password)
   {
      global $CONF, $U;
       
      //echo "<script type=\"text/javascript\">alert(\"Login: ".$password."|".crypt($password, $this->salt)."|".$U->password."\");</script>";
      if (crypt($password, $this->salt) == $U->password) return 0; // Password correct
      if ($this->bad_logins == 0) return 7; // if we don't need to enumerate/manage bad logins, just return "bad password"
       
      if (!$U->bad_logins)
      {
         /**
          * 1st bad login attempt, set the counter = 1
          * Set the timestamp to seconds since UNIX epoch (makes checking grace period easy)
          */
         $U->bad_logins = 1;
         $U->bad_logins_start = date("U");
         $retcode = 4;
      }
      elseif (++$U->bad_logins >= $this->bad_logins)
      {
         /**
          * That's too much! I've had it now with your bad logins.
          * Login locked for grace period of time.
          */
         $U->bad_logins_start = date("U");
         $U->setStatus($CONF['USLOGLOC']);
         $retcode = 6;
      }
      else
      {
         /**
          * 2nd or higher bad login attempt
          */
         $retcode = 5;
      }
      $U->update($U->username);
      return $retcode;
   }
}
?>
