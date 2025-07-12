<?php

/**
 * Database
 *
 * This class provides methods and properties for the database.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

/**
 * Provides properties and methods to deal with the database
 */
class DB {
  public $db;

  //---------------------------------------------------------------------------
  /**
   * Class constructor
   *
   * @param string $server Server address
   * @param string $database Database name
   * @param string $user Database username
   * @param string $password Password
   */
  public function __construct(string $server, string $database, string $user, string $password) {
    /**
     * Connect to database
     */
    try {
      $this->db = new PDO('mysql:host=' . $server . ';dbname=' . $database . ';charset=utf8', $user, $password);
      $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);         // Needed for PDO::errorInfo()
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Needed for PDO:errorCode()
    } catch (PDOException $e) {
      $errorData = [
        'title' => 'Application Error',
        'subject' => 'Database connection error.',
        'text' => $e->getMessage()
      ];
      require_once WEBSITE_ROOT . "/views/error.php";
      exit(1);
    }

    /**
     * Database settings
     */
    $query = $this->db->prepare('SET SQL_BIG_SELECTS=1');
    $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Get database info
   *
   * @return string PDO database information
   */
  public function getDatabaseInfo(): string {
    global $LANG;

    $dbInfo = "<table class='table'>
      <thead>
        <tr>
          <th>" . $LANG['attribute'] . "</th>
          <th>" . $LANG['value'] . "</th>
        </tr>
      </thead>
      <tbody>
      \n";

    $attributes = [
      "AUTOCOMMIT",
      "CASE",
      "CLIENT_VERSION",
      "CONNECTION_STATUS",
      "DRIVER_NAME",
      "ERRMODE",
      "ORACLE_NULLS",
      "PERSISTENT",
      "SERVER_INFO",
      "SERVER_VERSION",
    ];

    foreach ($attributes as $val) {
      $dbInfo .= "<tr><td>$val</td>";
      try {
        $dbInfo .= "<td>" . $this->db->getAttribute(constant("PDO::ATTR_$val")) . "</td></tr>\n";
      } catch (PDOException $e) {
        $dbInfo .= $e->getMessage() . "\n";
      }
    }
    $dbInfo .= "</tbody>\n</table>\n";
    return $dbInfo;
  }

  //---------------------------------------------------------------------------
  /**
   * Optimize tables
   */
  public function optimizeTables(): void {
    $query = $this->db->prepare('SHOW TABLES');
    $query->execute();
    $tableList = '';
    while ($row = $query->fetch()) {
      $tableList .= '`' . $row[0] . '`, ';
    }
    $tableList = rtrim($tableList, ', ');
    if ($tableList) {
      $stmt = 'OPTIMIZE TABLE ' . $tableList . ';';
      $query = $this->db->prepare($stmt);
      $query->execute();
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Run a MySQL query.
   *
   * This method prepares and executes a given MySQL query.
   *
   * @param string $myQuery The MySQL query to be executed.
   * @return boolean Query result indicating success or failure of the execution.
   */
  public function runQuery(string $myQuery): bool {
    $query = $this->db->prepare($myQuery);
    return $query->execute();
  }
}
