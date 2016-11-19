<?php
/**
 * config.app.php
 * 
 * Application based parameters. Don't change anything in this file.
 *
 * @category TeamCal Neo 
 * @version 1.2.000
 * @author George Lewe
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

//=============================================================================
/**
 * APP INSTALLED
 *
 * A flag indicating whether the installation script has been executed. 
 * 0 = Not run yet
 * 1 = Was run
 * Set this to 0 if you want to run the installation.php script again.
 * If not, you need to delete or rename the installation.php file.
 */
$CONF['app_installed'] = "0";

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
 * FILE UPLOAD
 *
 * Defines the allowed file types for upload
 * Defines the allowed max file sizes for upload
 */
$CONF['avatarExtensions'] = array ( 'gif', 'jpg', 'png' );
$CONF['avatarMaxsize'] = 1024 * 100; // 100 KB

$CONF['imgExtensions'] = array ( 'gif', 'jpg', 'png' );
$CONF['uplExtensions'] = array ( 'gif', 'jpg', 'png', 'doc', 'docx', 'pdf', 'ppt', 'pptx', 'xls', 'xlsx', 'zip' );
$CONF['uplMaxsize'] = 2048 * 1024; // 2 MB

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
$CONF['app_avatar_dir'] = 'upload/avatars/';
$CONF['app_upl_dir'] = 'upload/files/';
?>