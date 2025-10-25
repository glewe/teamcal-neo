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

  private static $cache = [];

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
    $result = $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (name, description, minpresent, maxabsent, minpresentwe, maxabsentwe) VALUES (:name, :description, :minpresent, :maxabsent, :minpresentwe, :maxabsentwe)');
    $query->bindParam(':name', $this->name);
    $query->bindParam(':description', $this->description);
    $query->bindParam(':minpresent', $this->minpresent);
    $query->bindParam(':maxabsent', $this->maxabsent);
    $query->bindParam(':minpresentwe', $this->minpresentwe);
    $query->bindParam(':maxabsentwe', $this->maxabsentwe);
    $success = $query->execute();
    if ($success) {
      $this->invalidateCache();
    }
    return $success;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a record by ID
   *
   * @param string $id Record ID to delete
   * @return boolean $result Query result
   */
  public function delete(string $id): bool {
    $success = $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $success = $query->execute();
    if ($success) {
      $this->invalidateCache();
    }
    return $success;
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
      $success = $query->execute();
      if ($success) {
        $this->invalidateCache();
      }
      return $success;
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
    $query = $this->db->prepare('SELECT id, name, description, minpresent, maxabsent, minpresentwe, maxabsentwe FROM ' . $this->table . ' ORDER BY name ' . $sort);
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all group records into an array, using cache if available.
   *
   * @param string $sort Sort direction (ASC or DESC)
   * @return array Array with all group records
   */
  public function getAllCached(string $sort = 'ASC'): array {
    $this->loadCache();
    // Re-sort if needed, but assume ASC for cache
    if ($sort === 'DESC') {
      usort(self::$cache, function($a, $b) {
        return strnatcasecmp($b['name'], $a['name']);
      });
    }
    return self::$cache;
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
    $this->loadCache();
    foreach (self::$cache as $row) {
      if ($row['id'] === $id) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->minpresent = $row['minpresent'];
        $this->maxabsent = $row['maxabsent'];
        $this->minpresentwe = $row['minpresentwe'];
        $this->maxabsentwe = $row['maxabsentwe'];
        return true;
      }
    }
    // Fallback to DB if not in cache (e.g., after invalidation race)
    $query = $this->db->prepare('SELECT id, name, description, minpresent, maxabsent, minpresentwe, maxabsentwe FROM ' . $this->table . ' WHERE id = :id');
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
      // Add to cache
      self::$cache[] = $row;
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
   * Gets the group ID for a given name, using cache.
   *
   * @param string       $name Group name
   * @return string|int        Group ID or 0
   */
  public function getId(string $name): string|int {
    $this->loadCache();
    foreach (self::$cache as $row) {
      if ($row['name'] === $name) {
        return $row['id'];
      }
    }
    // Fallback
    $query = $this->db->prepare('SELECT id FROM ' . $this->table . ' WHERE name = :name');
    $query->bindParam(':name', $name);
    $query->execute();
    $id = $query->fetchColumn();
    return $id !== false ? $id : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the maximum absent value for group, using cache.
   *
   * @param string      $id ID to find
   * @return string|int     Maximum absent value
   */
  public function getMaxAbsent(string $id): string|int {
    $this->loadCache();
    foreach (self::$cache as $row) {
      if ($row['id'] === $id) {
        return (int)$row['maxabsent'];
      }
    }
    // Fallback
    $query = $this->db->prepare('SELECT maxabsent FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    $val = $query->fetchColumn();
    return $val !== false ? $val : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the maximum absent value for group for weekends, using cache.
   *
   * @param string      $id ID to find
   * @return string|int     Maximum absent value weekends
   */
  public function getMaxAbsentWe(string $id): string|int {
    $this->loadCache();
    foreach (self::$cache as $row) {
      if ($row['id'] === $id) {
        return (int)$row['maxabsentwe'];
      }
    }
    // Fallback
    $query = $this->db->prepare('SELECT maxabsentwe FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    $val = $query->fetchColumn();
    return $val !== false ? $val : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the minimum present value for group, using cache.
   *
   * @param string      $id ID to find
   * @return string|int     Minimum present value
   */
  public function getMinPresent(string $id): string|int {
    $this->loadCache();
    foreach (self::$cache as $row) {
      if ($row['id'] === $id) {
        return (int)$row['minpresent'];
      }
    }
    // Fallback
    $query = $this->db->prepare('SELECT minpresent FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    $val = $query->fetchColumn();
    return $val !== false ? $val : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the minimum present value for group for weekends, using cache.
   *
   * @param string      $id ID to find
   * @return string|int     Minimum present value weekends
   */
  public function getMinPresentWe(string $id): string|int {
    $this->loadCache();
    foreach (self::$cache as $row) {
      if ($row['id'] === $id) {
        return (int)$row['minpresentwe'];
      }
    }
    // Fallback
    $query = $this->db->prepare('SELECT minpresentwe FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    $val = $query->fetchColumn();
    return $val !== false ? $val : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a group name for a given ID, using cache.
   *
   * @param string       $id ID to find
   * @return string|bool     Group name or false
   */
  public function getNameById(string $id): string|bool {
    $this->loadCache();
    foreach (self::$cache as $row) {
      if ($row['id'] === $id) {
        return $row['name'];
      }
    }
    // Fallback
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
   * Invalidates the cache.
   */
  public function invalidateCache(): void {
    self::$cache = [];
  }

  //---------------------------------------------------------------------------
  /**
   * Loads the cache if not already loaded.
   *
   * @return array The cached groups data.
   */
  private function loadCache(): array {
    if (empty(self::$cache)) {
      self::$cache = $this->getAll('ASC');
    }
    return self::$cache;
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a record for a given ID
   *
   * @param string $name Record ID
   * @return boolean Query result
   */
  public function update(string $id): bool {
    $success = $query = $this->db->prepare('UPDATE ' . $this->table . ' SET name = :name, description = :description, minpresent = :minpresent, maxabsent = :maxabsent, minpresentwe = :minpresentwe, maxabsentwe = :maxabsentwe WHERE id = :id');
    $query->bindParam(':name', $this->name);
    $query->bindParam(':description', $this->description);
    $query->bindParam(':minpresent', $this->minpresent);
    $query->bindParam(':maxabsent', $this->maxabsent);
    $query->bindParam(':minpresentwe', $this->minpresentwe);
    $query->bindParam(':maxabsentwe', $this->maxabsentwe);
    $query->bindParam(':id', $id);
    $success = $query->execute();
    if ($success) {
      $this->invalidateCache();
    }
    return $success;
  }
}
