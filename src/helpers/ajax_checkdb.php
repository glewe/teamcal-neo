<?php
/**
 * Database Check
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
header("Cache-Control: no-cache");
header("Pragma: no-cache");
if (strlen($_REQUEST['server']) && strlen($_REQUEST['db']) && strlen($_REQUEST['user'])) {

  // Validate server hostname
  $server = $_REQUEST['server'];
  $pattern = '/^([a-zA-Z0-9.-]+)$/';
  if (!preg_match($pattern, $server)) {
    die("Invalid server hostname.");
  }

  // Validate database name
  $database = $_REQUEST['db'];
  $pattern = '/^[a-zA-Z0-9_]+$/';
  if (!preg_match($pattern, $database)) {
    die("Invalid database name.");
  }

  // Validate prefix
  if (isset($_REQUEST['prefix']) && strlen($_REQUEST['prefix'])) {
    $prefix = $_REQUEST['prefix'];
    $pattern = '/^[a-zA-Z_]\w{0,63}$/';
    if (!preg_match($pattern, $prefix)) {
      die("Invalid table name prefix.");
    }
  } else {
    $prefix = '';
  }

  try {
    // Connect to the database
    $pdo = new PDO('mysql:host=' . $server . ';dbname=' . $database . ';charset=utf8', $_REQUEST['user'], $_REQUEST['pass']);

    // Query to check for tables in the database
    $query = $pdo->prepare("SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = :db");
    $query->execute([ 'db' => $database]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result['table_count'] === 0) {
      $msg = "<div class='alert alert-success'><h5>Database Check</h5><p>The database exists and is empty.</p></div>";
    } else {
      $msg = "<div class='alert alert-warning'><h5>Database Check</h5><p>The database exists but is not empty.</p></div>";
      $query2 = $pdo->prepare("SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = '" . $database . "' AND table_name LIKE '" . $prefix . "%'");
      $query2->execute();
      $result2 = $query2->fetch(PDO::FETCH_ASSOC);
      if ($result2['table_count'] > 0) {
        $msg .= "<div class='alert alert-warning'><h5>Table Check</h5><p>Tables with the given prefix exist (" . $result2['table_count'] . ").</p></div>";
      } else {
        $msg .= "<div class='alert alert-success'><h5>Table Check</h5><p>Tables with the given prefix do not exist (" . $result2['table_count'] . ").</p></div>";
      }
    }
  } catch (PDOException $e) {
    $msg = "<div class='alert alert-danger'><h5>Database Check</h5><p>Failed to connect to the database: " . $e->getMessage() . "</p></div>";
  }
} else {
  $msg = "<div class='alert alert-danger'><h5>Database Check</h5><p>You need to provide at least the database server, name and user.</p></div>";
}
echo $msg;
