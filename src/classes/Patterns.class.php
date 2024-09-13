<?php

/**
 * Patterns
 *
 * This class provides methods and properties for absence patterns.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.0.0
 */
class Patterns {
  public $id = 0;
  public $name = '';
  public $description = '';
  public $abs1 = 0;
  public $abs2 = 0;
  public $abs3 = 0;
  public $abs4 = 0;
  public $abs5 = 0;
  public $abs6 = 0;
  public $abs7 = 0;

  private $db = '';
  private $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_patterns'];
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a pattern record from class variables
   *
   * @return boolean Query result
   */
  public function create() {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (name, description, abs1, abs2, abs3, abs4, abs5, abs6, abs7) VALUES (:val1, :val2, :val3, :val4, :val5, :val6, :val7, :val8, :val9)');
    $query->bindParam('val1', $this->name);
    $query->bindParam('val2', $this->description);
    for ($i = 1; $i <= 7; $i++) {
      $prop = 'abs' . $i;
      $query->bindParam('val' . ($i + 2), $this->$prop);
    }
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @return boolean Query result
   */
  public function deleteAll() {
    $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a pattern record
   *
   * @param string $id Record ID
   * @return boolean Query result
   */
  public function delete($id) {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
    $query->bindParam('val1', $id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a pattern record and saves values in class variables
   *
   * @param string $id Record ID
   * @return boolean Query result
   */
  public function get($id) {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
    $query->bindParam('val1', $id);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      $this->id = $row['id'];
      $this->name = $row['name'];
      $this->description = $row['description'];
      for ($i = 1; $i <= 7; $i++) {
        $prop = 'abs' . $i;
        $this->$prop = $row[$prop];
      }
      return true;
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all pattern records into an array
   *
   * @return array Array with records
   */
  public function getAll() {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY name');
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = $row;
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all records with likeness in name or description
   *
   * @param string $like Likeness to search for
   * @return array Array with records
   */
  public function getAllLike($like) {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE name LIKE :val1 OR description LIKE :val1 ORDER BY name');
    $val1 = '%' . $like . '%';
    $query->bindParam('val1', $val1);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = $row;
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given absence pattern exists
   *
   * @param array $absPattern Array of absences
   * @return boolean
   */
  public function patternExists($absPattern) {
    $stmt = 'SELECT name FROM ' . $this->table . ' WHERE abs1 = :val1 AND abs2 = :val2 AND abs3 = :val3 AND abs4 = :val4 AND abs5 = :val5 AND abs6 = :val6 AND abs7 = :val7';
    $query = $this->db->prepare($stmt);
    $query->bindParam('val1', $absPattern[1]);
    $query->bindParam('val2', $absPattern[2]);
    $query->bindParam('val3', $absPattern[3]);
    $query->bindParam('val4', $absPattern[4]);
    $query->bindParam('val5', $absPattern[5]);
    $query->bindParam('val6', $absPattern[6]);
    $query->bindParam('val7', $absPattern[7]);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      return $row['name'];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a pattern record
   *
   * @param string $id Record ID to update
   * @return boolean Query result
   */
  public function update($id) {
    $stmt = 'UPDATE ' . $this->table . ' SET name = :val1, description = :val2, abs1 = :val3, abs2 = :val4, abs3 = :val5, abs4 = :val6, abs5 = :val7, abs6 = :val8, abs7 = :val9 WHERE id = :val10';
    $query = $this->db->prepare($stmt);
    $query->bindParam('val1', $this->name);
    $query->bindParam('val2', $this->description);
    $query->bindParam('val3', $this->abs1);
    $query->bindParam('val4', $this->abs2);
    $query->bindParam('val5', $this->abs3);
    $query->bindParam('val6', $this->abs4);
    $query->bindParam('val7', $this->abs5);
    $query->bindParam('val8', $this->abs6);
    $query->bindParam('val9', $this->abs7);
    $query->bindParam('val10', $id);
    return $query->execute();
  }
}
