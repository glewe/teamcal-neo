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
   * retcode = 97 : LDAP anonymous bind check failed
   *
   * LDAP_TLS values:
   *   0 : No encryption (plain LDAP)
   *   1 : STARTTLS on the plain LDAP connection (default, backward compatible)
   *   2 : LDAPS (implicit TLS from the start of the connection)
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
    }

    $ldaprdn            = defined('LDAP_DIT') ? LDAP_DIT : '';
    $ldappass           = defined('LDAP_PASS') ? LDAP_PASS : '';
    $ldaptls            = self::intConfigConstant('LDAP_TLS');
    $host               = defined('LDAP_HOST') ? LDAP_HOST : '';
    $port               = defined('LDAP_PORT') ? LDAP_PORT : 389;
    $searchbase         = defined('LDAP_SBASE') ? LDAP_SBASE : '';
    $checkAnonymousBind = self::boolConfigConstant('LDAP_CHECK_ANONYMOUS_BIND');
    $searchBind         = self::boolConfigConstant('LDAP_SEARCH_BIND');

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
    // Construct LDAP URI. Use implicit TLS (ldaps://) when configured, otherwise fall
    // back to plain ldap:// which can still be upgraded via STARTTLS below.
    $useLdaps = $ldaptls === 2;
    $scheme   = $useLdaps ? "ldaps" : "ldap";
    $ldapUri  = $scheme . "://" . $host . ":" . $port;
    $ds       = ldap_connect($ldapUri);

    if (!$ds) {
      return 93;
    }

    //
    // Use LDAP v3 if possible
    //
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

    //
    // Test anonymous bind. If that fails, the server rejected it (a config/permissions
    // issue), which is distinct from a connection failure.
    //
    if ($checkAnonymousBind && !@ldap_bind($ds)) {
      return 97;
    }

    //
    // Start TLS (only applies to plain ldap:// connections; ldaps:// is already encrypted)
    //
    if ($ldaptls && !$useLdaps && !ldap_start_tls($ds)) {
      return 94;
    }

    //
    // LDAP Search bind
    //
    if ($searchBind && !@ldap_bind($ds, $ldaprdn, $ldappass)) {
      return 96;
    }

    //
    // Search for user UID
    //
    $info         = null;
    $safeUsername = ldap_escape($username, "", LDAP_ESCAPE_FILTER);

    if (self::boolConfigConstant('LDAP_ADS')) {
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

  //---------------------------------------------------------------------------
  /**
   * Reads a config constant as an int, defaulting to 0 if undefined.
   *
   * Routed through a method with a declared `int` return type (rather than reading the
   * constant directly at the call site) so PHPStan trusts that declared type instead of
   * narrowing to the literal value of whichever config.app.php branch it happens to see
   * during analysis — the real value varies by environment (.env / OIDC vs. LDAP config).
   *
   * @param string $name Name of the constant to read
   *
   * @return int The constant's value cast to int, or 0 if undefined
   */
  private static function intConfigConstant(string $name): int {
    return defined($name) ? (int) constant($name) : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Reads a config constant as a bool, defaulting to false if undefined.
   *
   * See intConfigConstant() for why this goes through a declared-return-type method.
   *
   * @param string $name Name of the constant to read
   *
   * @return bool The constant's value cast to bool, or false if undefined
   */
  private static function boolConfigConstant(string $name): bool {
    return defined($name) ? (bool) constant($name) : false;
  }
}
