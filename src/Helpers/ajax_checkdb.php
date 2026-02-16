<?php
/**
 * Database Check
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
header("Cache-Control: no-cache");
header("Pragma: no-cache");
if (strlen($_REQUEST['server']) && strlen($_REQUEST['db']) && strlen($_REQUEST['user'])) {

  //
  // Validate and sanitize server hostname
  //
  $server  = trim($_REQUEST['server']);
  $pattern = '/^([a-zA-Z0-9.-]+)$/';
  if (!preg_match($pattern, $server) || strlen($server) > 255) {
    die("Invalid server hostname.");
  }

  //
  // Validate and sanitize database name
  //
  $database = trim($_REQUEST['db']);
  $pattern  = '/^\w+$/';
  if (!preg_match($pattern, $database) || strlen($database) > 64) {
    die("Invalid database name.");
  }

  //
  // Validate and sanitize prefix
  //
  if (isset($_REQUEST['prefix']) && strlen($_REQUEST['prefix'])) {
    $prefix  = trim($_REQUEST['prefix']);
    $pattern = '/^[a-zA-Z_]\w{0,63}$/';
    if (!preg_match($pattern, $prefix) || strlen($prefix) > 64) {
      die("Invalid table name prefix.");
    }
  }
  else {
    $prefix = '';
  }

  try {
    //
    // Sanitize user credentials
    //
    $user   = trim($_REQUEST['user']);
    $pass   = $_REQUEST['pass'] ?? '';
    $port   = $_REQUEST['port'] ?? null;
    $socket = $_REQUEST['socket'] ?? null;

    // Validate username
    if (strlen($user) > 80) {
      die("Username too long.");
    }

    //
    // Connect to the database with timeout and security options
    //
    if ($socket) {
      $dsn = 'mysql:unix_socket=' . $socket . ';dbname=' . $database . ';charset=utf8mb4';
    }
    else {
      $dsn = 'mysql:host=' . $server . ($port ? ';port=' . $port : '') . ';dbname=' . $database . ';charset=utf8mb4';
    }

    $pdo = new PDO(
      $dsn,
      $user,
      $pass,
      [
        PDO::ATTR_TIMEOUT          => 5,
        PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
      ]
    );

    //
    // Query to check for tables in the database
    //
    $query = $pdo->prepare("SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = :db");
    $query->execute(['db' => $database]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result['table_count'] === 0) {
      $msg = "<div class='alert alert-success'><h5>Database Check</h5><p>The database exists and is empty.</p></div>";
    }
    else {
      $msg    = "<div class='alert alert-warning'><h5>Database Check</h5><p>The database exists but is not empty.</p></div>";
      $query2 = $pdo->prepare("SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = :db AND table_name LIKE :prefix");
      $query2->execute(['db' => $database, 'prefix' => $prefix . '%']);
      $result2 = $query2->fetch(PDO::FETCH_ASSOC);
      if ($result2['table_count'] > 0) {
        $msg .= "<div class='alert alert-warning'><h5>Table Check</h5><p>Tables with the given prefix exist (" . $result2['table_count'] . ").</p></div>";
      }
      else {
        $msg .= "<div class='alert alert-success'><h5>Table Check</h5><p>Tables with the given prefix do not exist (" . $result2['table_count'] . ").</p></div>";
      }
    }
  } catch (PDOException $e) {
    // Log the actual error for debugging but don't expose it to the user
    error_log("Database connection error in ajax_checkdb.php: " . $e->getMessage());
    $msg = "<div class='alert alert-danger'><h5>Database Check</h5><p>Failed to connect to the database. Please check your connection parameters.</p></div>";
  }
}
else {
  $msg = "<div class='alert alert-danger'><h5>Database Check</h5><p>You need to provide at least the database server, name and user.</p></div>";
}
echo $msg;
