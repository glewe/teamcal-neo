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
  public PDO $db;
  /**
   * When true the constructor will require the error view and exit on connection failure.
   * Set to false in tests to allow exception handling by the caller.
   * @var bool
   */
  public bool $exitOnError = true;

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
      $dsn = 'mysql:host=' . $server . ';dbname=' . $database . ';charset=utf8mb4';
      $options = [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ];
      $this->db = new PDO($dsn, $user, $password, $options);
    } catch (PDOException $e) {
      if ($this->exitOnError) {
        $errorData = [
          'title' => 'Application Error',
          'subject' => 'Database connection error.',
          'text' => $e->getMessage()
        ];
        require_once WEBSITE_ROOT . "/views/error.php";
        exit(1);
      }
      // Re-throw so callers (tests or callers that set exitOnError=false) can handle it.
      throw $e;
    }

    /**
     * Database settings
     */
    $query = $this->db->prepare('SET SQL_BIG_SELECTS=1');
    $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Return database attribute map suitable for views.
   *
   * @return array<string,string>
   */
  public function getAttributes(): array {
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
    $result = [];
    foreach ($attributes as $val) {
      try {
        $result[$val] = (string)$this->db->getAttribute(constant("PDO::ATTR_$val"));
      } catch (PDOException $e) {
        $result[$val] = $e->getMessage();
      }
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Return an extended set of database attributes (portable + MySQL-specific).
   * Values are stringified and driver/constants are checked defensively.
   *
   * @return array<string,string>
   */
  public function getAttributesExtended(): array {
    $map = [
      // portable PDO attributes (constant name => label used in output)
      'AUTOCOMMIT' => 'PDO::ATTR_AUTOCOMMIT',
      'CASE' => 'PDO::ATTR_CASE',
      'CLIENT_VERSION' => 'PDO::ATTR_CLIENT_VERSION',
      'CONNECTION_STATUS' => 'PDO::ATTR_CONNECTION_STATUS',
      'DRIVER_NAME' => 'PDO::ATTR_DRIVER_NAME',
      'ERRMODE' => 'PDO::ATTR_ERRMODE',
      'ORACLE_NULLS' => 'PDO::ATTR_ORACLE_NULLS',
      'PERSISTENT' => 'PDO::ATTR_PERSISTENT',
      'SERVER_INFO' => 'PDO::ATTR_SERVER_INFO',
      'SERVER_VERSION' => 'PDO::ATTR_SERVER_VERSION',
      'DEFAULT_FETCH_MODE' => 'PDO::ATTR_DEFAULT_FETCH_MODE',
      'EMULATE_PREPARES' => 'PDO::ATTR_EMULATE_PREPARES',
      'STRINGIFY_FETCHES' => 'PDO::ATTR_STRINGIFY_FETCHES',
      'STATEMENT_CLASS' => 'PDO::ATTR_STATEMENT_CLASS',
      'TIMEOUT' => 'PDO::ATTR_TIMEOUT',
      // MySQL-specific attributes (may not be defined on other drivers)
      'MYSQL_INIT_COMMAND' => 'PDO::MYSQL_ATTR_INIT_COMMAND',
      'MYSQL_BUFFERED_QUERY' => 'PDO::MYSQL_ATTR_USE_BUFFERED_QUERY',
      'MYSQL_LOCAL_INFILE' => 'PDO::MYSQL_ATTR_LOCAL_INFILE',
      'MYSQL_READ_DEFAULT_FILE' => 'PDO::MYSQL_ATTR_READ_DEFAULT_FILE',
      'MYSQL_READ_DEFAULT_GROUP' => 'PDO::MYSQL_ATTR_READ_DEFAULT_GROUP',
      'MYSQL_MAX_BUFFER_SIZE' => 'PDO::MYSQL_ATTR_MAX_BUFFER_SIZE',
      'MYSQL_FOUND_ROWS' => 'PDO::MYSQL_ATTR_FOUND_ROWS',
    ];

    $result = [];
    foreach ($map as $label => $constName) {
      if (!defined($constName)) {
        $result[$label] = 'n/a';
        continue;
      }
      try {
        $val = $this->db->getAttribute(constant($constName));
        if (is_array($val) || is_object($val)) {
          $val = json_encode($val, JSON_UNESCAPED_UNICODE);
        } elseif ($val === false) {
          $val = 'false';
        } elseif ($val === null) {
          $val = 'null';
        } else {
          $val = (string)$val;
        }
        $result[$label] = $val;
      } catch (PDOException $e) {
        $result[$label] = 'error: ' . $e->getMessage();
      }
    }

    return $result;
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

  //---------------------------------------------------------------------------
  /**
   * Execute a parameterized statement (safe wrapper)
   *
   * @param string $sql
   * @param array $params
   * @return bool
   */
  public function execute(string $sql, array $params = []): bool {
    $stmt = $this->db->prepare($sql);
    return $stmt->execute($params);
  }

  //---------------------------------------------------------------------------
  /**
   * Fetch all rows for a parameterized query.
   *
   * @param string $sql
   * @param array $params
   * @return array
   */
  public function fetchAll(string $sql, array $params = []): array {
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
  }

  //---------------------------------------------------------------------------
  /**
   * Fetch a single row for a parameterized query.
   *
   * @param string $sql
   * @param array $params
   * @return array|null
   */
  public function fetch(string $sql, array $params = []): ?array {
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row === false ? null : $row;
  }

  //---------------------------------------------------------------------------
  /**
   * Return last insert id.
   *
   * @return string
   */
  public function lastInsertId(): string {
    return $this->db->lastInsertId();
  }
}
