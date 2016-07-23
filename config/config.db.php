<?php
/**
 * config.db.php
 * 
 * Database parameters
 *
 * @category TeamCal Neo 
 * @version 0.9.001
 * @author George Lewe
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

//=============================================================================
/**
 * DATABASE
 * 
 * Enter your database parameter here
 */
$CONF['db_server'] = "localhost";
$CONF['db_name'] = "test";
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
?>