<?php

/**
 * PDODb
 *
 * This class provides methods and properties for database access via PDO.
 * The code is based on Mehemt Selcuk Batal's SunDB class from 2020:
 * https://github.com/msbatal/PHP-PDO-Database-Class
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.10.0
 */

class PDODb {
  /**
   * Database credentials
   * @var array
   */
  private $connectionParams = [
    'driver' => 'mysql',
    'url' => null,
    'host' => 'localhost',
    'port' => '3306',
    'dbname' => null,
    'username' => null,
    'password' => null,
    'charset' => 'utf8'
  ];

  /**
   * Action for query string
   * @var string
   */
  private $action;

  /**
   * Dynamic column control (on/off)
   * @var boolean
   */
  private $checkColumn = true;

  /**
   * Dynamic table control (on/off)
   * @var boolean
   */
  private $checkTable = true;

  /**
   * Dynamic type list for Group By condition value
   * @var string
   */
  private $groupBy;

  /**
   * Dynamic type list for Having condition value
   * @var string
   */
  private $having;

  /**
   * Static instance of self
   * @var object
   */
  private static $instance;

  /**
   * Value of the auto increment column
   * @var integer
   */
  private $lastInsertId = 0;

  /**
   * Limit condition value for SQL query
   * @var string
   */
  private $limit;

  /**
   * Dynamic type list for Order By condition value
   * @var array
   */
  private $orderBy = [];

  /**
   * PDO instance
   * @var object
   */
  private $pdo;

  /**
   * SQL query
   * @var string
   */
  private $query;

  /**
   * Array that holds query result
   * @var array
   */
  private $queryResult;

  /**
   * Number of affected rows
   * @var integer
   */
  private $rowCount = 0;

  /**
   * Table name
   * @var string
   */
  private $table;

  /**
   * Array that holds Insert/Update values
   * @var array
   */
  private $values = [];

  /**
   * Array that holds Where conditions (And)
   * @var array
   */
  private $where = [];

  /**
   * Array that holds Where values
   * @var array
   */
  private $whereValues = [];

  /**
   * Record order to be selected
   * @var string
   */
  private $which;

  //---------------------------------------------------------------------------
  /**
   * Constructor for the PDODb class.
   *
   * This constructor initializes the database connection parameters and sets up the PDO instance.
   * It can accept an array of connection parameters, a PDO object, or individual connection parameters.
   *
   * @param string|array|object $type The database type or an array of connection parameters or a PDO object.
   * @param string $host The database host.
   * @param string $username The database username.
   * @param string $password The database password.
   * @param string $dbname The database name.
   * @param integer $port The database port.
   * @param string $charset The database charset.
   */
  public function __construct($type = null, $host = null, $username = null, $password = null, $dbname = null, $port = null, $charset = null) {
    set_exception_handler(function ($exception) {
      $errorData = [
        'title' => 'DATABASE ERROR',
        'subject' => 'PDODb Exception',
        'text' => $exception->getMessage()
      ];
      include_once WEBSITE_ROOT . '/views/error.php';
    });
    if (is_array($type)) {
      $this->connectionParams = $type;
    } else if (is_object($type)) {
      $this->pdo = $type;
    } else {
      foreach ($this->connectionParams as $key => $value) {
        if (isset($$key) && !is_null($$key)) {
          $this->connectionParams[$key] = $$key;
        }
      }
    }
    self::$instance = $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Return all records.
   *
   * This method sets the internal state to indicate that all records should be returned.
   *
   * @return $this Returns the current instance for method chaining.
   */
  public function all() {
    $this->which = 'all';
    return $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Backup the database.
   *
   * This method creates a backup of the database by generating SQL statements to recreate the database schema and data.
   * It supports excluding specific tables from the backup and can either save the backup to a file or display it.
   *
   * @param string|null $fileName The name of the backup file. Defaults to a timestamped file name if not provided.
   * @param string|null $action The action to perform. Can be 'save' to download the backup file or any other value to display it. Defaults to 'save'.
   * @param array $excludeTables An array of table names to exclude from the backup. Defaults to an empty array.
   * @throws \Exception If the database driver is SQLite or if there is an error during the backup process.
   */
  public function backup($fileName = null, $action = null, $excludeTables = []) {
    if ($this->connectionParams['driver'] == 'sqlite') {
      throw new \Exception('SQLite database backup is not allowed. Download "' . $this->connectionParams['url'] . '" file directly.');
    }
    if (empty($fileName)) {
      $fileName = 'PDODb-Backup-' . date("YmdHis") . '.sql';
    } else {
      $fileName .= '.sql';
    }
    if (empty($action)) {
      $action = 'save';
    }
    if ($action == 'save') {
      header('Content-disposition: attachment; filename=' . $fileName);
      header('Content-type: application/force-download');
    }
    //
    // List all tables
    //
    $show = $this->pdo()->query('show tables')->fetchAll();
    $tables = [];
    foreach ($show as $rows) {
      $content = [];
      $table = reset($rows);
      if (!in_array($table, $excludeTables)) {
        //
        // List table structure
        //
        $create = $this->pdo()->query("show create table `$table`")->fetchAll();
        //
        // Select create table structure
        //
        $content[] = $create[0]['Create Table'] . ";\n";
        //
        // List all values in table
        //
        $query = $this->pdo()->prepare("select * from `$table`");
        $query->execute(array());
        $select = $query->fetchAll();
        if ($query->rowCount() > 0) {
          foreach ($select as $row) {
            if (count($row) < 1) {
              continue;
            }
            $header = "INSERT INTO `$table` VALUES ('";
            $body = implode("', '", array_values($row));
            $footer = "');";
            $content[] = $header . $body . $footer;
          }
          if (count($content) < 1) {
            continue;
          }
          $tables[$table] = implode("\n", $content);
        }
      }
    }
    if ($action == 'save') {
      //
      // Save file message
      //
      echo "# PDODb Database Backup File\n# Backup Date: " . date("Y-m-d H:i:s") . "\n# Backup File: " . $fileName . "\n\n\n";
      echo implode("\n\n", array_values($tables));
    } else {
      //
      // Show file content on screen
      //
      echo nl2br(implode('<br><br>', array_values($tables)));
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Check if a column exists in the specified table.
   *
   * This method queries the database to check if a column with the specified name exists in the given table.
   * If the column does not exist, it throws an exception.
   *
   * @param string $column The name of the column to check.
   * @return void
   * @throws \Exception If the column does not exist.
   */
  private function checkColumn($column = null) {
    $result = $this->pdo()->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $this->connectionParams['dbname'] . "' AND TABLE_NAME = '" . $this->table . "' AND COLUMN_NAME = '" . $column . "'");
    if ($result->rowCount() != 1) {
      throw new \Exception('Column "' . $column . '" does not exist.');
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Check if a table exists in the database.
   *
   * This method queries the database to check if a table with the specified name exists.
   * If the table does not exist, it throws an exception.
   *
   * @param string $table The name of the table to check.
   * @return void
   * @throws \Exception If the table does not exist.
   */
  private function checkTable($table = null) {
    $result = $this->pdo()->query("SHOW TABLES LIKE '" . $table . "'");
    if ($result->rowCount() != 1) {
      throw new \Exception('Table "' . $table . '" does not exist.');
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Create PDO connection
   *
   * This method establishes a connection to the database using the provided connection parameters.
   * It supports different database drivers such as MySQL, MSSQL, and SQLite.
   * The method sets various PDO attributes to ensure proper error handling and performance.
   *
   * @return void
   * @throws \Exception If the database driver is not set or the PDO instance is not created.
   */
  private function connect() {
    if (empty($this->connectionParams['driver'])) {
      throw new \Exception('Database Driver is not set.');
    }
    if ($this->connectionParams['driver'] == 'sqlite') {
      $connectionString = 'sqlite:' . $this->connectionParams['url'];
      $this->pdo = new \PDO($connectionString);
    } else if ($this->connectionParams['driver'] == 'mssql') {
      $connectionString = 'sqlsrv:Server=' . $this->connectionParams['host'] . ';Database=' . $this->connectionParams['dbname'];
      $this->pdo = new \PDO($connectionString, $this->connectionParams['username'], $this->connectionParams['password']);
    } else {
      $connectionString = $this->connectionParams['driver'] . ':';
      $connectionParams = [ 'host', 'dbname', 'port', 'charset' ];
      foreach ($connectionParams as $connectionParam) {
        if (!empty($this->connectionParams[$connectionParam])) {
          $connectionString .= $connectionParam . '=' . $this->connectionParams[$connectionParam] . ';';
        }
      }
      $connectionString = rtrim($connectionString, ';');
      $this->pdo = new \PDO($connectionString, $this->connectionParams['username'], $this->connectionParams['password']);
    }
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    $this->pdo->setAttribute(\PDO::ATTR_CURSOR, \PDO::CURSOR_SCROLL);
    $this->pdo->setAttribute(\PDO::ATTR_ORACLE_NULLS, \PDO::NULL_EMPTY_STRING);
    $this->pdo->setAttribute(\PDO::ATTR_PERSISTENT, true);
    if ($this->connectionParams['driver'] == 'mysql') {
      $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
      $this->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
      $this->pdo->setAttribute(\PDO::MYSQL_ATTR_FOUND_ROWS, true);
      $this->pdo->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, 'SET CHARACTER SET utf8, NAMES utf8');
    }
    if (!($this->pdo instanceof \PDO)) {
      throw new \Exception('This object is not an instance of PDO.');
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Delete data from a specified table.
   *
   * This method constructs and executes an SQL DELETE query to remove data from a specified table.
   * It resets the internal state, checks if the table exists (if applicable), and builds the DELETE query.
   *
   * @param string $table The name of the table to delete data from.
   * @return $this Returns the current instance for method chaining.
   * @throws \Exception If the table does not exist.
   */
  public function delete($table = null) {
    $this->reset();
    if ($this->connectionParams['driver'] != 'sqlite' && $this->checkTable) {
      $this->checkTable($table);
    }
    $this->table = $table;
    $this->action = 'delete';
    $this->query = 'DELETE from `' . $table . '`';
    return $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Return the first record.
   *
   * This method sets the internal state to indicate that only the first record should be returned.
   *
   * @return $this Returns the current instance for method chaining.
   */
  public function first() {
    $this->which = 'first';
    return $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Execute a user-defined function with a parameter.
   *
   * This method takes a user-defined function and a parameter, then executes the function with the given parameter.
   * If either the function or the parameter is not provided, it throws an exception.
   *
   * @param callable|null $func The user-defined function to execute. Defaults to null.
   * @param mixed|null $param The parameter to pass to the function. Defaults to null.
   * @return mixed Returns the result of the function execution.
   * @throws \Exception If the function or parameter is not provided.
   */
  public function func($func = null, $param = null) {
    if (empty($func) || empty($param)) {
      throw new \Exception('Missing parameters for "' . $func . '" function.');
    }
    return $func($param);
  }

  //---------------------------------------------------------------------------
  /**
   * Return a static instance of the Database class.
   *
   * This method returns the static instance of the Database class, allowing for a singleton pattern.
   * It ensures that only one instance of the Database class is used throughout the application.
   *
   * @return object Returns the static instance of the Database class.
   */
  public static function getInstance() {
    return self::$instance;
  }

  //---------------------------------------------------------------------------
  /**
   * Add a GROUP BY clause to the SQL query.
   *
   * This method constructs and adds a GROUP BY clause to the SQL query.
   * It can optionally apply a SQL function to the column.
   *
   * @param string $column The name of the column to group by.
   * @param string|null $function An optional SQL function to apply to the column (e.g., COUNT, MAX). Defaults to null.
   * @return $this Returns the current instance for method chaining.
   * @throws \Exception If the column name is not provided.
   */
  public function groupBy($column = null, $function = null) {
    if (empty($column)) {
      throw new \Exception('Group By clause must contain a column name.');
    }
    if ($this->connectionParams['driver'] != 'sqlite' && $this->checkColumn) {
      $this->checkColumn($column);
    }
    if (!empty($function)) {
      $this->groupBy = $function . '(`' . $column . '`)';
    } else {
      $this->groupBy = '`' . $column . '`';
    }
    return $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Add a HAVING clause to the SQL query.
   *
   * This method constructs and adds a HAVING clause to the SQL query.
   * It checks if the value is provided and if the database driver is not SQLite.
   *
   * @param mixed $value The value for the HAVING clause.
   * @return $this Returns the current instance for method chaining.
   * @throws \Exception If the value is not provided.
   */
  public function having($value = null) {
    if (empty($value)) {
      throw new \Exception('Having clause must contain a value.');
    }
    if ($this->connectionParams['driver'] != 'sqlite') {
      $this->having = $value;
    }
    return $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Insert data into a specified table.
   *
   * This method constructs and executes an SQL INSERT query to add data to a specified table.
   * It resets the internal state, checks if the table exists (if applicable), and builds the INSERT query.
   *
   * @param string $table The name of the table to insert data into.
   * @param array $data An associative array of column names and values to insert.
   * @return $this Returns the current instance for method chaining.
   * @throws \Exception If the data is not an array or is empty.
   */
  public function insert($table = null, $data = []) {
    $this->reset();
    if ($this->connectionParams['driver'] != 'sqlite' && $this->checkTable) {
      $this->checkTable($table);
    }
    if (!is_array($data) || count($data) <= 0) {
      throw new \Exception('Insert clause must contain an array with data.');
    }
    foreach ($data as $key => $value) {
      $keys[] = '`' . $key . '`';
      $alias[] = '?';
      if ($value == '' && $value <> '0') {
        $value = NULL;
      }
      $this->values[] = $value;
    }
    $strKeys = implode(',', $keys);
    $strAlias = implode(',', $alias);
    $this->table = $table;
    $this->action = 'insert';
    if (is_int($keys[0])) {
      $this->query = 'insert into `' . $table . '` (' . $strKeys . ') values (' . $strAlias . ')';
    } else {
      $this->query = 'insert into `' . $table . '` values (' . $strAlias . ')';
    }
    return $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Return the last record.
   *
   * This method sets the internal state to indicate that only the last record should be returned.
   *
   * @return $this Returns the current instance for method chaining.
   */
  public function last() {
    $this->which = 'last';
    return $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Get the last inserted ID.
   *
   * This method returns the ID of the last inserted row.
   *
   * @return int Returns the ID of the last inserted row.
   */
  public function lastInsertId() {
    return (int)$this->lastInsertId;
  }

  //---------------------------------------------------------------------------
  /**
   * Add a LIMIT clause to the SQL query.
   *
   * This method constructs and adds a LIMIT clause to the SQL query.
   * It sets the starting point and the number of records to retrieve.
   *
   * @param int $start The starting point for the LIMIT clause. Defaults to 0.
   * @param int|null $page The number of records to retrieve. If not provided, it defaults to the value of $start.
   * @return $this Returns the current instance for method chaining.
   * @throws \Exception If the $start or $page is not an integer.
   */
  public function limit($start = 0, $page = null) {
    if (!is_int($start)) {
      throw new \Exception('Limit clause must be 0 or above.');
    }
    if (empty($page) || !is_int($page)) {
      $page = $start;
      $start = 0;
    }
    $this->limit = $start . ',' . $page;
    return $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Perform maintenance on the database tables.
   *
   * This method performs several maintenance operations on the database tables, including analyzing, checking, optimizing, and repairing the tables.
   * It first retrieves the list of tables in the database, then performs the maintenance operations on each table.
   * If all operations are successful, it returns true; otherwise, it returns false.
   *
   * @return bool Returns true if all maintenance operations are successful, false otherwise.
   * @throws \Exception If there is an error during any of the maintenance operations.
   */
  public function maintenance() {
    $tables = [];
    $show = $this->pdo()->query('show tables')->fetchAll(); // list tables
    foreach ($show as $rows) {
      if (!is_array($rows)) continue;
      if (count($rows) < 1) continue;
      $tables[] = $this->connectionParams['dbname'] . '.' . reset($rows);
    }
    if (count($tables) > 0) {
      $tables = implode(', ', $tables);
      try {
        $analyze = $this->pdo()->query("analyze table $tables");
        $check = $this->pdo()->query("check table $tables");
        $optimize = $this->pdo()->query("optimize table $tables");
        $repair = $this->pdo()->query("repair table $tables");
      } catch (Exception $e) {
        throw new \Exception($e->getMessage());
      }
      if ($analyze && $check && $optimize && $repair) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Add an ORDER BY clause to the SQL query.
   *
   * This method constructs and adds an ORDER BY clause to the SQL query.
   * It supports ordering by a column name in ascending or descending order.
   * If the column name contains 'RAND', it will be used for random ordering.
   *
   * @param string $column The name of the column to order by.
   * @param string|null $order The order direction (ASC or DESC). Defaults to null.
   * @return $this Returns the current instance for method chaining.
   * @throws \Exception If the column name or order value is not provided.
   */
  public function orderBy($column = null, $order = null) {
    if (strpos(strtoupper($column), 'RAND') !== false && empty($order)) {
      $this->orderBy[] = $column;
    } else {
      if (empty($column) || !in_array(strtoupper($order), [ 'ASC', 'DESC' ], true)) {
        throw new \Exception('Order By clause must contain a column name and order value.');
      }
      if ($this->connectionParams['driver'] != 'sqlite' && $this->checkColumn) {
        $this->checkColumn($column);
      }
      $this->orderBy[] = '`' . $column . '` ' . $order;
    }
    return $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Add an OR WHERE clause to the SQL query.
   *
   * This method constructs and adds an OR WHERE clause to the SQL query.
   * It is a shorthand for adding a WHERE clause with the 'or' condition.
   *
   * @param string $column The name of the column to apply the condition on.
   * @param mixed $value The value to compare the column against. Can be a single value or an array of values.
   * @param string $operator The operator to use for comparison (e.g., '=', 'like', 'between', 'in').
   * @return $this Returns the current instance for method chaining.
   */
  public function orWhere($column = null, $value = null, $operator = null) {
    return $this->where($column, $value, $operator, 'or');
  }

  //---------------------------------------------------------------------------
  /**
   * Check/Call PDO connection
   *
   * This method checks if the PDO instance is set. If not, it calls the connect method to establish a connection.
   * It also verifies that the PDO instance is valid.
   *
   * @return \PDO Returns the PDO instance.
   * @throws \Exception If the PDO instance is not valid.
   */
  private function pdo() {
    if (!$this->pdo) {
      $this->connect();
    }
    if (!($this->pdo instanceof \PDO)) {
      throw new \Exception('This object is not an instance of PDO.');
    }
    return $this->pdo;
  }

  //---------------------------------------------------------------------------
  /**
   * Return a random record.
   *
   * This method sets the internal state to indicate that a random record should be returned.
   *
   * @return $this Returns the current instance for method chaining.
   */
  public function random() {
    $this->which = 'random';
    return $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Execute a raw SQL query.
   *
   * This method constructs and executes a raw SQL query with optional bound values.
   * It resets the internal state, sets the action to 'query', and assigns the provided query string.
   * If values are provided, they are bound to the query.
   *
   * @param string|null $query The raw SQL query to execute. Defaults to null.
   * @param array $values An optional array of values to bind to the query. Defaults to an empty array.
   * @return $this Returns the current instance for method chaining.
   */
  public function rawQuery($query = null, $values = []) {
    $this->reset();
    if (is_array($values) && count($values) > 0) {
      foreach ($values as $value) {
        if ($value === NULL) {
          $value = '';
        }
        $this->values[] = $value;
      }
    }
    $this->action = 'query';
    $this->query = $query;
    return $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Resets all properties of the PDODb class.
   *
   * This method clears all the internal variables used for building and executing SQL queries.
   * It is typically called before starting a new query to ensure no residual data from previous queries affects the current one.
   */
  private function reset() {
    $this->action = '';
    $this->groupBy = '';
    $this->having = '';
    $this->lastInsertId = 0;
    $this->limit = '';
    $this->orderBy = [];
    $this->orWhere = [];
    $this->query = '';
    $this->rowCount = 0;
    $this->table = '';
    $this->values = [];
    $this->where = [];
    $this->whereValues = [];
    $this->which = '';
  }

  //---------------------------------------------------------------------------
  /**
   * Get the number of affected rows.
   *
   * This method returns the number of rows affected by the last SQL statement executed.
   *
   * @return int Returns the number of affected rows.
   */
  public function rowCount() {
    return (int)$this->rowCount;
  }

  //---------------------------------------------------------------------------
  /**
   * Execute the constructed SQL query.
   *
   * This method builds and executes the SQL query based on the internal state of the Database class.
   * It handles various SQL clauses such as WHERE, GROUP BY, HAVING, ORDER BY, and LIMIT.
   * The method supports different actions like SELECT, INSERT, UPDATE, DELETE, and raw queries.
   *
   * @return mixed The result of the executed query. The return type depends on the action:
   *               - SELECT: Returns an array of records or a single record based on the internal state.
   *               - INSERT, UPDATE, DELETE: Returns a boolean indicating the success of the operation.
   *               - Raw query: Returns the result of the raw query execution.
   * @throws \Exception If the action is not allowed or if there is an error during query execution.
   */
  public function run($debug = false) {
    if (is_array($this->where) && count($this->where) > 0) { // add Where condition
      $count = 0;
      $clnWhere = array();
      foreach ($this->where as $key => $value) { // remove first And/OR part
        $count++;
        if ($count == 1) {
          $clnWhere[] = ltrim(ltrim($value, 'or'), 'and');
        } else {
          $clnWhere[] = $value;
        }
      }
      $this->query .= ' where ' . implode('', $clnWhere) . '';
    }
    if (!empty($this->groupBy)) {
      //
      // Add Group By condition
      //
      $this->query .= ' group by ' . $this->groupBy;
    }
    if (!empty($this->groupBy) && !empty($this->having)) {
      //
      // Add Having condition
      //
      $this->query .= ' having ' . $this->having;
    }
    if (is_array($this->orderBy) && count($this->orderBy) > 0) {
      //
      // Add Order By condition
      //
      $this->query .= ' order by ' . implode(',', $this->orderBy);
    }
    if (!empty($this->limit)) {
      //
      // Add Limit condition
      //
      $this->query .= ' limit ' . $this->limit;
    }
    switch ($this->action) {
      case 'select':
        //
        // Run Select query and return the result (array|object)
        //
        $query = $this->pdo()->prepare($this->query);
        if ($debug) {
          dnd($query); // Display query and die
        }
        $result = $query->execute($this->whereValues);
        $this->queryResult = $query->fetchAll();
        $this->rowCount = $query->rowCount(); // selected row count
        $query->closeCursor();
        unset($query);
        if ($this->which == 'first') {
          return $this->queryResult[0]; // return only first record
        } else if ($this->which == 'last') {
          return end($this->queryResult); // return only last record
        } else if ($this->which == 'random') {
          $index = rand(0, $this->rowCount - 1);
          return $this->queryResult[$index]; // return a random record
        } else {
          return $this->queryResult; // return all records
        }
        break;
      case 'insert':
        //
        // Run Insert query and return the result (bool)
        //
        $query = $this->pdo()->prepare($this->query);
        if ($debug) {
          dnd($query); // Display query and die
        }
        $result = $query->execute($this->values);
        $this->rowCount = $query->rowCount();               // inserted row count
        $this->lastInsertId = $this->pdo()->lastInsertId(); // auto increment value
        $query->closeCursor();
        unset($query);
        return $result;
        break;
      case 'update':
        //
        // Run Update query and return the result (bool)
        //
        $query = $this->pdo()->prepare($this->query);
        if ($debug) {
          dnd($query); // Display query and die
        }
        $result = $query->execute(array_merge($this->values, $this->whereValues));
        $this->rowCount = $query->rowCount(); // updated row count
        $query->closeCursor();
        unset($query);
        return $result;
        break;
      case 'delete':
        //
        // Run Delete query and return the result (bool)
        //
        $query = $this->pdo()->prepare($this->query);
        if ($debug) {
          dnd($query); // Display query and die
        }
        $result = $query->execute($this->whereValues);
        $this->rowCount = $query->rowCount(); // deleted row count
        $query->closeCursor();
        unset($query);
        return $result;
        break;
      case 'query':
        //
        // Run Raw query and return the result (bool)
        //
        $query = $this->pdo()->prepare($this->query);
        if ($debug) {
          dnd($query); // Display query and die
        }
        $result = $query->execute($this->values);
        $this->rowCount = $query->rowCount(); // affected row count
        $exp = explode(' ', $this->query);    // for determine the action
        if ($exp[0] == 'select') {
          $this->queryResult = $query->fetchAll();
          $query->closeCursor();
          unset($query);
          return $this->queryResult;
        } else {
          $query->closeCursor();
          unset($query);
          return $result;
        }
        break;
      default:
        throw new \Exception('Command "' . $this->action . '" is not allowed.');
        break;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Build a SELECT part of the query.
   *
   * This method initializes the query for selecting data from a specified table.
   * It resets the internal state, checks if the table exists (if applicable), and constructs the SELECT query.
   *
   * @param string $table The name of the table to select from.
   * @param string|array $columns The columns to select. Can be a string of column names or an array of column names. Defaults to '*'.
   * @return $this Returns the current instance for method chaining.
   */
  public function select($table = null, $columns = '*', $debug = false) {
    $this->reset();
    if ($this->connectionParams['driver'] != 'sqlite' && $this->checkTable) {
      $this->checkTable($table);
    }
    if (is_array($columns) && count($columns) > 0) {
      $columns = implode(',', $columns);
    } else {
      $columns = '*';
    }
    $this->table = $table;
    $this->action = 'select';
    $this->query = 'select ' . $columns . ' from `' . $table . '`';
    return $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Display the constructed SQL query.
   *
   * This method outputs the constructed SQL query for debugging purposes.
   * If the query is not found, it displays an error message.
   * If the action is 'query', it directly displays the raw query.
   * Otherwise, it replaces placeholders in the query with actual values and displays the final query.
   */
  public function showQuery() {
    if (empty($this->query)) {
      echo '<b>[PDODb] Error:</b> SQL query not found.';
    } else {
      if ($this->action == 'query') {
        echo '<p><b>[PDODb] Query:</b> ' . $this->query . '</p>';
      } else {
        $queryArray = explode('?', $this->query);
        for ($i = 0; $i < count($queryArray) - 1; $i++) {
          $result .= $queryArray[$i] . "'" . $this->whereValues[$i] . "'";
        }
        $result .= $queryArray[count($queryArray) - 1];
        echo '<p><b>[PDODb] Query:</b> ' . $result . '</p>';
      }
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Get the total number of rows in a table.
   *
   * This method queries the database to count the total number of rows in the specified table.
   * It first checks if the table exists (if applicable) and then executes a COUNT query.
   *
   * @param string|null $table The name of the table to count rows in. Defaults to null.
   * @return int Returns the total number of rows in the table.
   * @throws \Exception If the table does not exist.
   */
  public function tableCount($table = null) {
    if ($this->connectionParams['driver'] != 'sqlite' && $this->checkTable) {
      $this->checkTable($table);
    }
    $query = $this->pdo()->query('select count(*) as total from ' . $table)->fetchAll();
    return (int)$query[0]['total'];
  }

  //---------------------------------------------------------------------------
  /**
   * Update data in a specified table.
   *
   * This method constructs and executes an SQL UPDATE query to modify data in a specified table.
   * It resets the internal state, checks if the table exists (if applicable), and builds the UPDATE query.
   *
   * @param string $table The name of the table to update data in.
   * @param array $data An associative array of column names and values to update.
   * @return $this Returns the current instance for method chaining.
   * @throws \Exception If the data is not an array or is empty.
   */
  public function update($table = null, $data = []) {
    $this->reset();
    if ($this->connectionParams['driver'] != 'sqlite' && $this->checkTable) {
      $this->checkTable($table);
    }
    if (!is_array($data) || count($data) <= 0) {
      throw new \Exception('Update clause must contain an array with data.');
    }
    foreach ($data as $key => $value) {
      $keys[] = '`' . $key . '`=?';
      if ($value === NULL) {
        $value = '';
      }
      $this->values[] = $value;
    }
    $keys = implode(',', $keys);
    $this->table = $table;
    $this->action = 'update';
    $this->query = 'update `' . $table . '` set ' . $keys;
    return $this;
  }

  //---------------------------------------------------------------------------
  /**
   * Add a WHERE clause to the SQL query.
   *
   * This method constructs and adds a WHERE clause to the SQL query.
   * It supports various operators such as '=', 'like', 'between', 'in', etc.
   * The method also handles different conditions (AND/OR) and checks if the column exists (if applicable).
   *
   * @param string $column The name of the column to apply the condition on.
   * @param mixed $value The value to compare the column against. Can be a single value or an array of values.
   * @param string $operator The operator to use for comparison (e.g., '=', 'like', 'between', 'in').
   * @param string $condition The condition to use (AND/OR). Defaults to 'and'.
   * @return $this Returns the current instance for method chaining.
   * @throws \Exception If the column or operator is not provided.
   */
  public function where($column = null, $operator = null, $value = null, $condition = 'and') {
    if (empty($value) && empty($operator)) {
      $this->where[] = $condition . ' (' . $column . ') ';
    } else {
      if (empty($column) || empty($operator)) {
        throw new \Exception('Where clause must contain a value and operator.');
      }
      if ($this->connectionParams['driver'] != 'sqlite' && $this->checkColumn) {
        $this->checkColumn($column);
      }
      if ($operator == 'like' || $operator == 'not like') {
        $this->where[] = $condition . ' (`' . $column . '` ' . $operator . '?) ';
        if ($value === NULL) {
          $value = '';
        }
        $this->whereValues[] = $value;
      } else if ($operator == 'between' || $operator == 'not between') {
        if (!empty($value[0]) && !empty($value[1])) {
          $this->whereValues[] = $value[0];
          $this->whereValues[] = $value[1];
          $this->where[] = $condition . ' (`' . $column . '` ' . $operator . ' ? and ?) ';
        }
      } else if ($operator == 'in' || $operator == 'not in') {
        if (is_array($value) && count($value) > 0) {
          foreach ($value as $val) {
            $values[] = '?';
            $this->whereValues[] = $val;
          }
          $this->where[] = $condition . ' (`' . $column . '` ' . $operator . ' (' . implode(',', $values) . ')) ';
        }
      } else {
        $this->where[] = $condition . ' (`' . $column . '` ' . $operator . '?) ';
        if ($value === NULL) {
          $value = '';
        }
        $this->whereValues[] = $value;
      }
    }
    return $this;
  }
}
