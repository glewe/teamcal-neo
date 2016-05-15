<?php
/**
 * config.app.php
 * 
 * Application based parameters. Don't change anything in this file.
 *
 * @category TeamCal Neo 
 * @version 0.5.006
 * @author George Lewe
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
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
 * If not, it is recommended that you delete the installation.php file.
 */
$CONF['app_installed'] = "0";

//=============================================================================
/**
 * ADDONS
 * 
 * Switch add-ons on or off. These settings are read in view/header.php
 */
$CONF['addon_bootstrap_editable'] = false;
$CONF['addon_chartjs'] = true;
$CONF['addon_ckeditor'] = true;
$CONF['addon_select2'] = false;
$CONF['addon_syntaxhighlighter'] = false;

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

$CONF['docExtensions'] = array ( 'doc', 'docx', 'pdf', 'ppt', 'pptx', 'xls', 'xlsx', 'zip' );
$CONF['docMaxsize'] = 2048 * 1024; // 2 MB

$CONF['imgExtensions'] = array ( 'gif', 'jpg', 'png' );
$CONF['imgMaxsize'] = 1024 * 1024; // 1 MB

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
$CONF['app_image_dir'] = 'upload/images/';
$CONF['app_doc_dir'] = 'upload/documents/';
$CONF['app_jqueryui_dir'] = 'js/jquery/ui/1.11.4/';

//=============================================================================
/**
 * PRODUCT, AUTHOR, COPYRIGHT, LICENSE INFORMATION
 * 
 * !Do not change this information. It is protected by the license agreement!
 */
$CONF['app_name'] = "TeamCal Neo";
$CONF['app_version'] = "0.5.006";
$CONF['app_version_date'] = "2016-05-15";
$CONF['app_year_start'] = "2014";
$CONF['app_year_current'] = date('Y');
$CONF['app_author'] = "George Lewe";
$CONF['app_url'] = "http://www.lewe.com";
$CONF['app_email'] = "george@lewe.com";
$CONF['app_copyright'] = "(c) " . $CONF['app_year_start'] . "-" . $CONF['app_year_current'] . " by " . $CONF['app_author'] . " (" . $CONF['app_url'] . ")";
$CONF['app_powered'] = "Powered by " . $CONF['app_name'] . " " . $CONF['app_version'] . " &copy; " . $CONF['app_year_start'] . "-" . $CONF['app_year_current'] . " by <a href=\"http://www.lewe.com\" class=\"copyright\" target=\"_blank\">" . $CONF['app_author'] . "</a>";
$CONF['app_license'] = "This program is cannot be licensed yet. Redistribution is not allowed.";
?>