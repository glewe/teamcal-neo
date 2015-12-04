<?php
/**
 * config.app.php
 * 
 * Application based parameters. Don't change anything in this file.
 *
 * @category TeamCal Neo 
 * @version 0.3.004
 * @author George Lewe
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

//=============================================================================
/**
 * COOKIE NAME
 * 
 * The cookie prefix to be used on the browser client's device
 */
$CONF['cookie_name'] = 'tcneo';

//=============================================================================
/**
 * ENCRYPTION
 * 
 * Salt is a string that is used encrypt the passwords. You can change salt
 * to any other 9 char string.
 */
$CONF['salt'] = 's7*9fgJ#R';

//=============================================================================
/**
 * LDAP
 * 
 * You can enable LDAP user authentication in this section. Please read the
 * requirements below.
 *
 * PHP Requirements
 * You will need to get and compile LDAP client libraries from either
 * OpenLDAP or Bind9.net in order to compile PHP with LDAP support.
 *
 * PHP Installation
 * LDAP support in PHP is not enabled by default. You will need to use the
 * --with-ldap[=DIR] configuration option when compiling PHP to enable LDAP
 * support. DIR is the LDAP base install directory. To enable SASL support,
 * be sure --with-ldap-sasl[=DIR] is used, and that sasl.h exists on the system.
 */
$CONF['LDAP_YES'] = 0; // Use LDAP authentication
$CONF['LDAP_ADS'] = 0; // Set to 1 when authenticating against an Active Directory
$CONF['LDAP_HOST'] = "ldap.mydomain.com"; // LDAP host name
$CONF['LDAP_PORT'] = "389"; // LDAP port
$CONF['LDAP_PASS'] = 'XXXXXXXX'; // SA associated password
$CONF['LDAP_DIT'] = "cn=<service account>,ou=fantastic_four,ou=superheroes,dc=marvel,dc=comics"; // Directory Information Tree (Relative Distinguished Name)
$CONF['LDAP_SBASE'] = "ou=superheroes,ou=characters,dc=marvel,dc=comics"; // Search base, location in the LDAP dirctory to search
$CONF['LDAP_TLS'] = 0; // To avoid "Undefined index: LDAP_TLS" error message for LDAP bind to Active Directory

//=============================================================================
/**
 * PATHS
 */
$CONF['app_avatar_dir'] = 'upload/avatar';
$CONF['app_homepage_dir'] = 'upload/homepage';
$CONF['app_jqueryui_dir'] = 'js/jquery/ui/1.10.4/';

//=============================================================================
/**
 * PRODUCT, AUTHOR, COPYRIGHT, LICENSE INFORMATION
 * 
 * !Do not change this information. It is protected by the license agreement!
 */
$CONF['app_name'] = "TeamCal Neo";
$CONF['app_version'] = "0.3.004";
$CONF['app_help_root'] = "https://georgelewe.atlassian.net/wiki/display/CP10/";
$CONF['app_version_date'] = "2015-02-07";
$CONF['app_year'] = "2014";
$CONF['app_curr_year'] = date('Y');
$CONF['app_author'] = "George Lewe";
$CONF['app_author_url'] = "http://www.lewe.com";
$CONF['app_author_email'] = "george@lewe.com";
$CONF['app_copyright'] = "&copy; " . $CONF['app_year'] . "-" . $CONF['app_curr_year'] . " by <a href=\"mailto:" . $CONF['app_author_email'] . "?subject=" . $CONF['app_name'] . "&nbsp;" . $CONF['app_version'] . "\" class=\"copyright\">" . $CONF['app_author'] . "</a>.";
$CONF['app_copyright_html'] = "(c) " . $CONF['app_year'] . "-" . $CONF['app_curr_year'] . " by " . $CONF['app_author'] . " (" . $CONF['app_author_url'] . ")";
$CONF['app_powered_by'] = "Powered by " . $CONF['app_name'] . " " . $CONF['app_version'] . " &copy; " . $CONF['app_year'] . "-" . $CONF['app_curr_year'] . " by <a href=\"http://www.lewe.com\" class=\"copyright\" target=\"_blank\">" . $CONF['app_author'] . "</a>";
$CONF['app_license_html'] = "This program is under development and cannot be licensed yet. Redistribution is not allowed.

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTIBILITY or FITNESS FOR A PARTICULAR PURPOSE.\n";
?>