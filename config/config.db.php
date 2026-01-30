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
 * DATABASE
 * ----------------------------------------------------------------------------
 *
 * Enter your database parameter here
 */
$CONF['db_server'] = "localhost";
$CONF['db_name'] = "tcneo_5";
$CONF['db_user'] = "root";
$CONF['db_pass'] = "";
$CONF['db_table_prefix'] = "tcneo_";

/**
 * The ID array is used to create the table names below.
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

/**
 * Table names, example:
 * $CONF['db_table_config'] = "myprefix_config";
 */
foreach ($tableIDs as $tid) {
  $confIndex = 'db_table_' . $tid;
  $confArchiveIndex = 'db_table_archive_' . $tid;
  $CONF[$confIndex] = $CONF['db_table_prefix'] . $tid;
  $CONF[$confArchiveIndex] = $CONF['db_table_prefix'] . 'archive_' . $tid;
}

define('DEFAULT_TIMESTAMP', '1900-01-01 00:00:00');
