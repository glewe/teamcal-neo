<?php
declare(strict_types=1);

namespace App\Services;

/**
 * LdapService
 *
 * Handles LDAP authentication.
 */
class LdapService
{
  //---------------------------------------------------------------------------
  /**
   * LDAP authentication.
   *
   * retcode = 0  : successful LDAP authentication
   * retcode = 90 : extension missing
   * retcode = 91 : password missing
   * retcode = 92 : LDAP user bind failed
   * retcode = 93 : Unable to connect to LDAP server
   * retcode = 94 : STARTTLS failed
   * retcode = 95 : No uid found
   * retcode = 96 : LDAP search bind failed
   *
   * @param string $username
   * @param string $password
   * @return int Authentication return code
   */
  public function verify(string $username, string $password): int {
    //
    // Check availability of LDAP extension
    //
    if (!function_exists('ldap_connect')) {
      return 90;
      // The original code assumes the extension is there if LDAP_YES is true.
      // But for safety and PHPStan, we can check.
    }

    $ldaprdn            = defined('LDAP_DIT') ? LDAP_DIT : '';
    $ldappass           = defined('LDAP_PASS') ? LDAP_PASS : '';
    $ldaptls            = defined('LDAP_TLS') ? LDAP_TLS : false;
    $host               = defined('LDAP_HOST') ? LDAP_HOST : '';
    $port               = defined('LDAP_PORT') ? LDAP_PORT : 389;
    $searchbase         = defined('LDAP_SBASE') ? LDAP_SBASE : '';
    $checkAnonymousBind = defined('LDAP_CHECK_ANONYMOUS_BIND') ? LDAP_CHECK_ANONYMOUS_BIND : false;
    $searchBind         = defined('LDAP_SEARCH_BIND') ? LDAP_SEARCH_BIND : false;

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
    if (!$password) {
      return 91;
    }

    //
    // Connect to LDAP host
    //
    // Construct LDAP URI to avoid deprecation warning for 2-argument usage
    $ldapUri = "ldap://" . $host . ":" . $port;
    $ds      = ldap_connect($ldapUri);

    if (!$ds) {
      return 93;
    }

    //
    // Use LDAP v3 if possible
    //
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

    //
    // Test anonymous bind. If that fails: Unable to connect to LDAP server
    //
    // @phpstan-ignore-next-line
    if ($checkAnonymousBind && !@ldap_bind($ds)) {
      return 93;
    }

    //
    // Start TLS
    //
    // @phpstan-ignore-next-line
    if ($ldaptls && !ldap_start_tls($ds)) {
      return 94;
    }

    //
    // LDAP Search bind
    //
    // @phpstan-ignore-next-line
    if ($searchBind && !@ldap_bind($ds, $ldaprdn, $ldappass)) {
      return 96;
    }

    //
    // Search for user UID
    //
    $info         = null;
    $safeUsername = ldap_escape($username, "", LDAP_ESCAPE_FILTER);

    // @phpstan-ignore-next-line
    if (defined('LDAP_ADS') && LDAP_ADS) {
      $search = ldap_search($ds, $searchbase, "sAMAccountName=" . $safeUsername, $attr);
      if ($search) {
        $info = ldap_first_entry($ds, $search);
      }
    }
    else {
      $search = ldap_search($ds, $searchbase, "uid=" . $safeUsername, $attr);
      if ($search) {
        $info = ldap_first_entry($ds, $search);
      }
    }

    if (!$info) {
      return 95;
    }

    //
    // Now authenticate the user using the user dn
    //
    $uiddn    = ldap_get_dn($ds, $info);
    $ldapbind = false;
    if ($uiddn) {
      $ldapbind = @ldap_bind($ds, $uiddn, $password);
    }

    //
    // Close LDAP connection
    //
    ldap_close($ds);

    //
    // Return result
    //
    if ($ldapbind) {
      return 0;
    }
    else {
      return 92;
    }
  }
}
