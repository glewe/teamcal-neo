<?php

/**
 * Database Check
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Helpers
 * @since 3.0.0
 */
header("Cache-Control: no-cache");
header("Pragma: no-cache");
if (strlen($_REQUEST['server']) && strlen($_REQUEST['db']) && strlen($_REQUEST['user'])) {
  try {
    $pdo = new PDO('mysql:host=' . $_REQUEST['server'] . ';dbname=' . $_REQUEST['db'] . ';charset=utf8', $_REQUEST['user'], $_REQUEST['pass']);
    $query = $pdo->prepare('SELECT * FROM ' . $_REQUEST['prefix'] . 'users;');
    $result = $query->execute();
    if ($result && $query->rowCount()) {
      $msg = "<div class='alert alert-success'><h5>Database Connection Test</h5><p>Connect to the mySQL server and database was successful.</p></div>";
      $msg .= "<div class='alert alert-success'><h5>Table Test</h5><p>Tables with the given prefix exist.</p></div>";
    } else {
      $msg = "<div class='alert alert-success'><h5>Database Connection Test</h5><p>Connect to the mySQL server and database was successful.</p></div>";
      $msg .= "<div class='alert alert-warning'><h5>Table Test</h5><p>Tables with the given prefix do not exist.</p></div>";
    }
  } catch (PDOException $e) {
    $msg = "<div class='alert alert-danger'><h5>Database Connection Test</h5><p>Connect to mySQL server and/or database failed.</p></div>";
  }
} else {
  $msg = "<div class='alert alert-danger'><h5>Database Connection Test</h5><p>Connect to mySQL server and/or database failed.</p></div>";
}
echo $msg;
