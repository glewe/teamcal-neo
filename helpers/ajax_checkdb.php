<?php
/**
 * ajax_checkdb.php
 * 
 * @category TeamCal Neo 
 * @version 0.5.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
header("Cache-Control: no-cache");
header("Pragma: no-cache");
if ( strlen($_REQUEST['server']) AND strlen($_REQUEST['db']) AND strlen($_REQUEST['db']) AND strlen($_REQUEST['user']) )
{
   try
   {
      $pdo = new PDO('mysql:host=' . $_REQUEST['server'] . ';dbname=' . $_REQUEST['db'] . ';charset=utf8', $_REQUEST['user'], $_REQUEST['pass']);
      $query = $pdo->prepare('SELECT * FROM ' . $_REQUEST['prefix'] . 'users;');
      $result = $query->execute();
      if ($result AND $query->rowCount())
      {
         $msg  = "<div class='alert alert-success'><h4><strong>Database Connection Test</strong></h4><p>Connect to the mySQL server and database was successful.</p></div>";
         $msg .= "<div class='alert alert-success'><h4><strong>Table Test</strong></h4><p>Tables with the given prefix exist.</p></div>";
      }
      else 
      {
         $msg  = "<div class='alert alert-success'><h4><strong>Database Connection Test</strong></h4><p>Connect to the mySQL server and database was successful.</p></div>";
         $msg .= "<div class='alert alert-warning'><h4><strong>Table Test</strong></h4><p>Tables with the given prefix do not exist.</p></div>";
      }
   } catch ( PDOException $e )
   {
      $msg = "<div class='alert alert-danger'><h4><strong>Database Connection Test</strong></h4><p>Connect to mySQL server and/or database failed.</p></div>";
   }
}
else
{
   $msg = "<div class='alert alert-danger'><h4><strong>Database Connection Test</strong></h4><p>Connect to mySQL server and/or database failed.</p></div>";
}
echo $msg;
?>
