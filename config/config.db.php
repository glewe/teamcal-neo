<?php
/**
 * config.db.php
 * 
 * Database parameters
 *
 * @category TeamCal Neo 
 * @version 1.9.004
 * @author George Lewe
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

//=============================================================================
/**
 * DATABASE
 * 
 * Enter your database parameter here
 */
$CONF['db_server'] = "localhost";
$CONF['db_name'] = "tcneo";
$CONF['db_user'] = "root";
$CONF['db_pass'] = "";
$CONF['db_table_prefix'] = "tcneo_";

/**
 * The ID array is used to create the table names below.
 */
$tableIDs = array (
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
foreach ($tableIDs as $tid)
{
   $confIndex = 'db_table_' . $tid;
   $confArchiveIndex = 'db_table_archive_' . $tid;
   $CONF[$confIndex] = $CONF['db_table_prefix'] . $tid;
   $CONF[$confArchiveIndex] = $CONF['db_table_prefix'] . 'archive_' . $tid;
}

define('DEFAULT_TIMESTAMP', '1000-01-01 00:00:00');
?>
