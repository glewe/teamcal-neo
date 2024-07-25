<?php
if (!defined('VALID_ROOT')) { exit(''); }

/**
 * DB.class.php
 *
 * @category TeamCal Neo Basic
 * @version 3.0.0
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @license This program cannot be licensed. Redistribution is not allowed.
 */

/**
 * Provides properties and methods to deal with the database
 */
class DB {
  public $db;

  // ---------------------------------------------------------------------
  /**
   * Class constructor
   *
   * @param string $server Server address
   * @param string $database Database name
   * @param string $user Database username
   * @param string $password Password
   */
  public function __construct($server, $database, $user, $password) {
    /**
     * Connect to database
     */
    try {
      $this->db = new PDO('mysql:host=' . $server . ';dbname=' . $database . ';charset=utf8', $user, $password);
    } catch (PDOException $e) {
      /**
       * Database connection error
       */
      $errorData['title'] = 'Application Error';
      $errorData['subject'] = 'Database connection error.';
      $errorData['text'] = $e->getMessage();
      require_once WEBSITE_ROOT . "/views/error.php";
      die();
    }

    /**
     * Database settings
     */
    $query = $this->db->prepare('SET SQL_BIG_SELECTS=1');
    $query->execute();
  }

  // ---------------------------------------------------------------------
  /**
   * Get database info
   *
   * @return string    $dbInfo    PDO database information
   */
  public function getDatabaseInfo() {
    $dbInfo = "\n";

    $attributes = array(
      "AUTOCOMMIT",
      "ERRMODE",
      "CASE",
      "CLIENT_VERSION",
      "CONNECTION_STATUS",
      "ORACLE_NULLS",
      "PERSISTENT",
      "SERVER_INFO",
      "SERVER_VERSION",
    );

    foreach ($attributes as $val) {
      $dbInfo .= "PDO::ATTR_$val: ";
      try {
        $dbInfo .= $this->db->getAttribute(constant("PDO::ATTR_$val")) . "\n";
      } catch (PDOException $e) {
        $dbInfo .= $e->getMessage() . "\n";
      }
    }
    return $dbInfo;
  }

  // ---------------------------------------------------------------------
  /**
   * Optimize tables
   */
  public function optimizeTables() {
    $tables = array();
    $query = $this->db->prepare('SHOW TABLES');
    $result = $query->execute();
    while ($result && $row = $query->fetch()) {
      $tables[] = $row[0];
    }
    foreach ($tables as $table) {
      $query = $this->db->prepare('OPTIMIZE TABLE ' . $table);
      $query->execute();
    }
  }

  // ---------------------------------------------------------------------
  /**
   * Run query
   *
   * @param string $myQyery MySQL query
   */
  public function runQuery($myQuery) {
    $query = $this->db->prepare($myQuery);
    return $query->execute();
  }
}
