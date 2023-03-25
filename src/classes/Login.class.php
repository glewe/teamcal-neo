<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Login
 *
 * This class provides methods and properties for user logins.
 * 
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage User Management
 * @since 3.0.0
 */
class Login
{
    private $user = '';
    private $bad_logins = 0;
    private $grace_period = 0;
    private $min_pw_length = 0;
    private $pw_strength = 0;
    private $php_self = '';
    private $log = '';
    private $logtype = '';

    // ---------------------------------------------------------------------
    /**
     * Constructor
     */
    public function __construct()
    {
        global $CONF, $C, $_POST, $_SERVER;

        $this->cookie_name = COOKIE_NAME;
        $this->bad_logins = intval($C->read("badLogins"));
        $this->grace_period = intval($C->read("gracePeriod"));
        $this->min_pw_length = intval($C->read("pwdLength"));
        $this->pw_strength = intval($C->read("pwdStrength"));
        $this->php_self = $_SERVER['PHP_SELF'];
        $this->log = $CONF['db_table_log'];
        $this->hostName = $this->getHost();
    }

    // ---------------------------------------------------------------------
    /**
     * Checks the login cookie and if it exists and is valid and if the user
     * is logged in we get the user info from the database.
     *
     * @return string Username of the user logged in, or false
     */
    public function checkLogin()
    {
        global $U;

        //
        // If the cookie is set, look up the username in the database
        //
        // Cookie array[0]=username
        // Cookie array[1]=password
        //
        if (isset($_COOKIE[$this->cookie_name])) {
            // echo ("<script type=\"text/javascript\">alert(\"[checkLogin]\\nCookie '".$this->cookie_name."' is set\")</script>");
            $array = explode(":", $_COOKIE[$this->cookie_name]);
            // echo ("<script type=\"text/javascript\">alert(\"[checkLogin]\\nCookie array[0]=".$array[0]."\\nCookie array[1]=".$array[1]."\")</script>");
            if (!isset($array[1])) $array[1] = '';
            if (password_verify($array[0], $array[1])) {
                $U->findByName($array[0]);
                return $U->username;
            } else {
                return false;
            }
        } else {
            // echo ("<script type=\"text/javascript\">alert(\"[checkLogin]\\nCookie '".$this->cookie_name."' is NOT set\")</script>");
            return false;
        }
    }

    // ---------------------------------------------------------------------
    /**
     * Determines the current host name
     *
     * @return string
     */
    function getHost()
    {
        if ($host = getenv('HTTP_X_FORWARDED_HOST')) {
            $elements = explode(',', $host);
            $host = trim(end($elements));
        } else {
            if (!$host = $_SERVER['HTTP_HOST']) {
                if (!$host = $_SERVER['SERVER_NAME']) {
                    $host = !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
                }
            }
        }

        //
        // Remove port number from host
        //
        $host = preg_replace('/:\d+$/', '', $host);

        return trim($host);
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
     * @return integer
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
    public function isPasswordValid($uname = '', $pw = '', $pwnew1 = '', $pwnew2 = '')
    {
        if (!isset($this->pw_strength)) $this->pw_strength = 0;
        $result = 0;

        if (empty($uname)) return 10;
        if (empty($pwnew1) || empty($pwnew2)) return 11;
        if ($pwnew1 != $pwnew2) return 12;

        //
        // MINIMUM LENGTH
        //
        if (strlen($pwnew1) < $this->min_pw_length) return 20;

        if ($this->pw_strength > 0) {
            //
            // LOW STRENGTH
            // = anything allowed if min_pw_length and new<>old
            //
            // convert the password to lower case and strip out the
            // common number for letter substitutions
            // then lowercase the username as well.
            //
            $pw_lower = strtolower($pw);
            $pwnew1_lower = strtolower($pwnew1);
            $pwnew1_denum = strtr($pwnew1_lower, '5301!', 'seoll');
            $uname_lower = strtolower($uname);

            if (preg_match($uname_lower, $pwnew1_denum)) return 30;
            if (preg_match(strrev($uname_lower), $pwnew1_denum)) return 31;
            if ($pwnew1_lower == $pw_lower) return 32;

            if ($this->pw_strength > 1) {
                //
                // MEDIUM STRENGTH
                //
                if (!preg_match('[0-9]', $pwnew1)) return 40;

                if ($this->pw_strength > 2) {
                    //
                    // HIGH STRENGTH
                    //
                    if (!preg_match('[a-z]', $pwnew1)) return 50;
                    if (!preg_match('[A-Z]', $pwnew1)) return 51;
                    if (!preg_match('[^a-zA-Z0-9]', $pwnew1)) return 52;
                }
            }
            return $result;
        }
    }

    // ---------------------------------------------------------------------
    /**
     * LDAP authentication
     * (Thanks to Aleksandr Babenko for the original code.)
     *
     * !!! Beta !!! Use at own risk.
     *
     * retcode = 0  : successful LDAP authentication
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
    private function ldapVerify($uidpass)
    {
        global $U;

        $ldaprdn = LDAP_DIT;
        $ldappass = LDAP_PASS;
        $ldaptls = LDAP_TLS;
        $host = LDAP_HOST;
        $port = LDAP_PORT;
        $searchbase = LDAP_SBASE;

        // 
        // Attributes to return
        //
        $attr = array(
            "dn",
            "uid"
        );

        //
        // Check missing password
        //
        if (!$uidpass) return 91;

        //
        // Connect to LDAP host
        //
        $ds = ldap_connect($host, $port);

        //
        // Use LDAP v3 if possible
        //
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

        //
        // Test anonymous bind. If that fails: Unable to connect to LDAP server
        // (patch by Franz Gregor)
        //
        if (!@ldap_bind($ds)) return 93;

        //
        // Start TLS
        //
        if ($ldaptls && !ldap_start_tls($ds)) return 94;

        //
        // LDAP Search bind
        // (patch by Franz Gregor)
        //
        if (!@ldap_bind($ds, $ldaprdn, $ldappass)) return 96;

        //
        // Search for user UID
        //
        if (LDAP_ADS) {
            if (!$info = ldap_first_entry($ds, ldap_search($ds, $searchbase, "sAMAccountName=" . $U->username, $attr))) return 95;
        } else {
            if (!$info = ldap_first_entry($ds, ldap_search($ds, $searchbase, "uid=" . $U->username, $attr))) return 95;
        }

        //
        // Now authenticate the user using the user dn
        //
        $uiddn = ldap_get_dn($ds, $info);
        $ldapbind = ldap_bind($ds, $uiddn, $uidpass);

        //
        // Close LDAP connection
        //
        ldap_close($ds);

        //
        // Return result
        //
        if ($ldapbind) return 0;
        else return 92;
    }

    // ---------------------------------------------------------------------
    /**
     * Local Authentication
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
    private function localVerify($password)
    {
        global $CONF, $U;

        // $echotxt = "<script type=\"text/javascript\">alert(\"Login: ".$password."|".password_hash($password, PASSWORD_DEFAULT)."|".$U->password."\");</script>";
        // die($echotxt);

        if ($verifyResult = password_verify($password, $U->password)) {
            //
            // Password correct
            //
            return 0;
        }

        if ($this->bad_logins == 0) {
            //
            // Password not correct.
            // Bad logins are not counted so just return "bad password"
            //
            return 7;
        }

        if (!$U->bad_logins) {
            //
            // 1st bad login attempt, set the counter = 1
            // Set the timestamp to seconds since UNIX epoch (makes checking grace period easy)
            //
            $U->bad_logins = 1;
            $U->bad_logins_start = date("U");
            $U->update($U->username);
            return 4;
        } elseif (++$U->bad_logins >= $this->bad_logins) {
            //
            // That's too much! I've had it now with your bad logins.
            // Login locked for grace period of time.
            //
            $U->bad_logins_start = date("U");
            $U->locked = '1';
            $U->update($U->username);
            return 6;
        } else {
            //
            // 2nd or higher bad login attempt
            //
            return 5;
        }
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
    public function login($loginname = '', $loginpwd = '')
    {
        global $C, $U, $UO;

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

        //
        // At this point we know that the user is not ONHOLD or the grace period is over.
        // We can safely unset it.
        //
        $U->onhold = 0;

        //
        // Now check the password
        //
        if (LDAP_YES && $loginname != "admin") {
            //
            // You need to have PHP LDAP libraries installed.
            //
            // The admin user is always logged in against the local database.
            // In case the LDAP does not work an admin login must still be possible.
            //
            $retcode = $this->ldapVerify($loginpwd);
        } else {
            //
            // Otherwise use TcNeo authentication
            //
            $retcode = $this->localVerify($loginpwd);
        }
        if ($retcode != 0) return $retcode;

        //
        // Successful login!
        // Set up the tc cookie and save the uname so TeamCal can get it.
        //
        $secret = password_hash($loginname, PASSWORD_DEFAULT);
        $value = $loginname . ":" . $secret;
        setcookie($this->cookie_name, ''); // Clear current cookie
        setcookie($this->cookie_name, $value, time() + intval($C->read("cookieLifetime")), '', $this->hostName, false, true); // Set new cookie
        $U->bad_logins = 0;
        $U->grace_start = DEFAULT_TIMESTAMP;
        $U->last_login = date("YmdHis");
        $U->update($U->username);

        return 0;
    }

    // ---------------------------------------------------------------------
    /**
     * Clears the login cookie
     */
    public function logout()
    {
        setcookie($this->cookie_name, '', time() - 3600, '', $this->hostName, false, true);
    }
}
