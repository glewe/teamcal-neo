<?php
/**
 * config.db.php
 * 
 * Database parameters.
 *
 * @category TeamCal Neo 
 * @version 0.3.005
 * @author George Lewe
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

//=============================================================================
/**
 * DATABASE
 * 
 * Enter your database parameter here
 */
$CONF['db_server'] = "db527920860.db.1and1.com";
$CONF['db_name'] = "db527920860";
$CONF['db_user'] = "dbo527920860";
$CONF['db_pass'] = "jdHhCYWU";
$CONF['db_table_prefix'] = "";

$CONF['db_server'] = "localhost";
$CONF['db_name'] = "tcneo";
$CONF['db_user'] = "root";
$CONF['db_pass'] = "";
$CONF['db_table_prefix'] = "";

/**
 * The ID array is used to create the table names below.
 */
$tableIDs = array (
   'absences',
   'absence_group',
   'allowances',
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
   'user_message',
   'user_group',
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