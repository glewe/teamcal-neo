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

  private $db = null;
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
  public function create(): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (name, description, abs1, abs2, abs3, abs4, abs5, abs6, abs7) VALUES (:name, :description, :abs1, :abs2, :abs3, :abs4, :abs5, :abs6, :abs7)');
    $query->bindParam(':name', $this->name);
    $query->bindParam(':description', $this->description);
    for ($i = 1; $i <= 7; $i++) {
      $prop = 'abs' . $i;
      $query->bindParam(':abs' . $i, $this->$prop);
    }
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @return boolean Query result
   */
  public function deleteAll(): bool {
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
  public function delete(string $id): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a pattern record and saves values in class variables
   *
   * @param string $id Record ID
   * @return boolean Query result
   */
  public function get(string $id): bool {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    $row = $query->fetch();
    if ($row) {
      $this->id = $row['id'];
      $this->name = $row['name'];
      $this->description = $row['description'];
      for ($i = 1; $i <= 7; $i++) {
        $prop = 'abs' . $i;
        $this->$prop = $row[$prop];
      }
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all pattern records into an array
   *
   * @return array Array with records
   */
  public function getAll(): array {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY name');
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
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
  public function getAllLike(string $like): array {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE name LIKE :like OR description LIKE :like ORDER BY name');
    $val = '%' . $like . '%';
    $query->bindParam(':like', $val);
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
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
  public function patternExists(array $absPattern): string|false {
    $stmt = 'SELECT name FROM ' . $this->table . ' WHERE abs1 = :abs1 AND abs2 = :abs2 AND abs3 = :abs3 AND abs4 = :abs4 AND abs5 = :abs5 AND abs6 = :abs6 AND abs7 = :abs7';
    $query = $this->db->prepare($stmt);
    for ($i = 1; $i <= 7; $i++) {
      $query->bindParam(':abs' . $i, $absPattern[$i]);
    }
    $query->execute();
    $row = $query->fetch();
    if ($row) {
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
  public function update(string $id): bool {
    $stmt = 'UPDATE ' . $this->table . ' SET name = :name, description = :description, abs1 = :abs1, abs2 = :abs2, abs3 = :abs3, abs4 = :abs4, abs5 = :abs5, abs6 = :abs6, abs7 = :abs7 WHERE id = :id';
    $query = $this->db->prepare($stmt);
    $query->bindParam(':name', $this->name);
    $query->bindParam(':description', $this->description);
    for ($i = 1; $i <= 7; $i++) {
      $prop = 'abs' . $i;
      $query->bindParam(':abs' . $i, $this->$prop);
    }
    $query->bindParam(':id', $id);
    return $query->execute();
  }
}
