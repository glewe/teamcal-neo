<?php
// Define constants required by the application
if (!defined('WEBSITE_ROOT')) {
    define('WEBSITE_ROOT', dirname(__DIR__));
}
if (!defined('VALID_ROOT')) {
    define('VALID_ROOT', 1);
}
if (!defined('APP_INSTALLED')) {
    define('APP_INSTALLED', true);
}
if (!defined('WEBSITE_URL')) {
    define('WEBSITE_URL', 'http://localhost');
}
if (!defined('L_USER')) {
    define('L_USER', 0);
}
if (!defined('DEFAULT_TIMESTAMP')) {
    define('DEFAULT_TIMESTAMP', '1970-01-01 00:00:00');
}

// LDAP Mock Constants
if (!defined('LDAP_YES')) define('LDAP_YES', true);
if (!defined('LDAP_ADS')) define('LDAP_ADS', true);
if (!defined('LDAP_TLS')) define('LDAP_TLS', true);
if (!defined('LDAP_CHECK_ANONYMOUS_BIND')) define('LDAP_CHECK_ANONYMOUS_BIND', true);
if (!defined('LDAP_HOST')) define('LDAP_HOST', 'localhost');
if (!defined('LDAP_PORT')) define('LDAP_PORT', 389);
if (!defined('LDAP_DIT')) define('LDAP_DIT', '');
if (!defined('LDAP_PASS')) define('LDAP_PASS', '');
if (!defined('LDAP_SBASE')) define('LDAP_SBASE', '');

// Mock Global Variables often used
$LANG = []; // Mock language array
$CONF = []; // Mock config array

require_once __DIR__ . '/../vendor/autoload.php';
