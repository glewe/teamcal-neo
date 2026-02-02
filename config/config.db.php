<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Database Configuration
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

/**
 * ----------------------------------------------------------------------------
 * DATABASE CREDENTIALS
 * ----------------------------------------------------------------------------
 *
 * It is recommended to set the database credentials in the .env file.
 * If you don't have a .env file, copy the .env.example file to .env and adjust the values.
 * Otherwise, use the fallback / manual configuration below.
 */
if (isset($_ENV['DB_HOST'])) {
  // Use .env configuration
  $CONF['db_server']       = $_ENV['DB_HOST'];
  $CONF['db_name']         = $_ENV['DB_NAME'];
  $CONF['db_user']         = $_ENV['DB_USER'];
  $CONF['db_pass']         = $_ENV['DB_PASS'];
  $CONF['db_table_prefix'] = $_ENV['DB_PREFIX'];
}
else {
  // Fallback / Manual configuration
  $CONF['db_server']       = "localhost";
  $CONF['db_name']         = "teamcal_neo";
  $CONF['db_user']         = "root";
  $CONF['db_pass']         = "";
  $CONF['db_table_prefix'] = "tcneo_";
}

/**
 * ----------------------------------------------------------------------------
 * DATABASE TABLES
 * ----------------------------------------------------------------------------
 *
 * The table name array is used by the models.
 * Table names, example:
 * $CONF['db_table_config'] = "myprefix_config";
 */
$tableIDs = array(
  'absences',
  'absence_group',
  'allowances',
  'attachments',
  'config',
  'daynotes',
  'holidays',
  'groups',
  'log',
  'messages',
  'months',
  'patterns',
  'permissions',
  'region_role',
  'regions',
  'roles',
  'templates',
  'users',
  'user_attachment',
  'user_group',
  'user_message',
  'user_option',
);
foreach ($tableIDs as $tid) {
  $confIndex = 'db_table_' . $tid;
  $confArchiveIndex = 'db_table_archive_' . $tid;
  $CONF[$confIndex] = $CONF['db_table_prefix'] . $tid;
  $CONF[$confArchiveIndex] = $CONF['db_table_prefix'] . 'archive_' . $tid;
}

/**
 * ----------------------------------------------------------------------------
 * OTHER DATABASE SETTINGS
 * ----------------------------------------------------------------------------
 */
define('DEFAULT_TIMESTAMP', '1900-01-01 00:00:00');
