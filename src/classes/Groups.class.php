<?php

/**
 * Groups
 *
 * This class provides methods and properties for user groups.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Groups {
  public $id = '';
  public $name = '';
  public $description = '';
  public $minpresent = '';
  public $maxabsent = '';
  public $minpresentwe = '';
  public $maxabsentwe = '';

  private $db = null;
  private $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_groups'];
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a new group record from local class variables
   *
   * @return boolean $result Query result
   */
  public function create(): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (name, description, minpresent, maxabsent, minpresentwe, maxabsentwe) VALUES (:name, :description, :minpresent, :maxabsent, :minpresentwe, :maxabsentwe)');
    $query->bindParam(':name', $this->name);
    $query->bindParam(':description', $this->description);
    $query->bindParam(':minpresent', $this->minpresent);
    $query->bindParam(':maxabsent', $this->maxabsent);
    $query->bindParam(':minpresentwe', $this->minpresentwe);
    $query->bindParam(':maxabsentwe', $this->maxabsentwe);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a record by ID
   *
   * @param string $id Record ID to delete
   * @return boolean $result Query result
   */
  public function delete(string $id): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @return boolean Query result or false
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table);
    $query->execute();
    if ($query->fetchColumn()) {
      $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
      return $query->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all group records into an array
   *
   * @param string $sort Sort direction (ASC or DESC)
   * @return array Array with all group records
   */
  public function getAll(string $sort = 'ASC'): array {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY name ' . $sort);
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all records into an array where likeness is found in group name
   * or description
   *
   * @param string $like Likeness to search for
   * @return array Array with all records
   */
  public function getAllLike(string $like): array {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE name LIKE :like OR description LIKE :like ORDER BY name ASC');
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
   * Gets all group names into an array
   *
   * @return array Array with all group names
   */
  public function getAllNames(): array {
    $records = array();
    $query = $this->db->prepare('SELECT name FROM ' . $this->table . ' ORDER BY name ASC');
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row['name'];
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a group record for a given ID
   *
   * @param string $id Group ID to find
   * @return boolean True or false
   */
  public function getById(string $id): bool {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    if ($row = $query->fetch()) {
      $this->id = $row['id'];
      $this->name = $row['name'];
      $this->description = $row['description'];
      $this->minpresent = $row['minpresent'];
      $this->maxabsent = $row['maxabsent'];
      $this->minpresentwe = $row['minpresentwe'];
      $this->maxabsentwe = $row['maxabsentwe'];
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a group record for a given group name
   *
   * @param string $name Group name to find
   * @return boolean True or false
   */
  public function getByName(string $name): bool {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE name = :name');
    $query->bindParam(':name', $name);
    $query->execute();
    if ($row = $query->fetch()) {
      $this->id = $row['id'];
      $this->name = $row['name'];
      $this->description = $row['description'];
      $this->minpresent = $row['minpresent'];
      $this->maxabsent = $row['maxabsent'];
      $this->minpresentwe = $row['minpresentwe'];
      $this->maxabsentwe = $row['maxabsentwe'];
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the maximum absent value for group
   *
   * @param string $groupname Group name
   * @return string Group ID
   */
  public function getId(string $name): string|int {
    $query = $this->db->prepare('SELECT id FROM ' . $this->table . ' WHERE name = :name');
    $query->bindParam(':name', $name);
    $query->execute();
    $id = $query->fetchColumn();
    return $id !== false ? $id : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the maximum absent value for group
   *
   * @param string $id ID to find
   * @return    string    Maximum absent value
   */
  public function getMaxAbsent(string $id): string|int {
    $query = $this->db->prepare('SELECT maxabsent FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    $val = $query->fetchColumn();
    return $val !== false ? $val : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the maximum absent value for group for weekends
   *
   * @param string $id ID to find
   * @return    string    Maximum absent value weekends
   */
  public function getMaxAbsentWe(string $id): string|int {
    $query = $this->db->prepare('SELECT maxabsentwe FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    $val = $query->fetchColumn();
    return $val !== false ? $val : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the maximum absent value for group
   *
   * @param string $id ID to find
   * @return    string    Minimum present value
   */
  public function getMinPresent(string $id): string|int {
    $query = $this->db->prepare('SELECT minpresent FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    $val = $query->fetchColumn();
    return $val !== false ? $val : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the maximum absent value for group for weekends
   *
   * @param string $id ID to find
   * @return    string    Minimum present value weekends
   */
  public function getMinPresentWe(string $id): string|int {
    $query = $this->db->prepare('SELECT minpresentwe FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    $val = $query->fetchColumn();
    return $val !== false ? $val : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a group name for a given ID
   *
   * @param string $id ID to find
   * @return string Group name (or false if not found)
   */
  public function getNameById(string $id): string|bool {
    $query = $this->db->prepare('SELECT name FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    $val = $query->fetchColumn();
    return $val !== false ? $val : false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a group record for a given ID
   *
   * @param string $id Group ID to find
   * @return array|boolean True or false
   */
  public function getRowById(string $id): array|bool {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    $row = $query->fetch();
    if ($row) {
      return [$row];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a record for a given ID
   *
   * @param string $name Record ID
   * @return boolean Query result
   */
  public function update(string $id): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET name = :name, description = :description, minpresent = :minpresent, maxabsent = :maxabsent, minpresentwe = :minpresentwe, maxabsentwe = :maxabsentwe WHERE id = :id');
    $query->bindParam(':name', $this->name);
    $query->bindParam(':description', $this->description);
    $query->bindParam(':minpresent', $this->minpresent);
    $query->bindParam(':maxabsent', $this->maxabsent);
    $query->bindParam(':minpresentwe', $this->minpresentwe);
    $query->bindParam(':maxabsentwe', $this->maxabsentwe);
    $query->bindParam(':id', $id);
    return $query->execute();
  }
}
