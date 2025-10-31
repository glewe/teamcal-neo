<?php

/**
 * Regions
 *
 * This class provides methods and properties for regions.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Regions {
  public string $id = '';
  public string $name = '';
  public string $description = '';

  public string $roleid = '';
  public string $regionid = '';
  public string $access = '';

  private $db = null;
  private string $accessTable = '';
  private string $table = '';
  private array $nameCache = [];

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_regions'];
    $this->accessTable = $CONF['db_table_region_role'];
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a region record
   *
   * @return boolean Query result
   */
  public function create(): bool {
    $query = $this->db->prepare("INSERT INTO {$this->table} (name, description) VALUES (:name, :description)");
    $query->bindParam('name', $this->name, \PDO::PARAM_STR);
    $query->bindParam('description', $this->description, \PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a region record
   *
   * @param string $id Region ID
   * @return boolean Query result
   */
  public function delete(string $id): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
    $query->bindParam('id', $id, \PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a region from access table
   *
   * @param string $id Region ID
   * @return boolean Query result
   */
  public function deleteAccess(string $id): bool {
    $query = $this->db->prepare("DELETE FROM {$this->accessTable} WHERE regionid = :regionid");
    $query->bindParam('regionid', $id, \PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @param boolean $archive Whether to use the archive table
   * @return boolean Query result or false
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare("TRUNCATE TABLE {$this->table}");
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the region_role access
   *
   * @param integer $id Region ID
   * @param integer $roleid Role ID
   * @return string Access type
   */
  public function getAccess(string $id, string $roleid): string|false {
    $query = $this->db->prepare("SELECT access FROM {$this->accessTable} WHERE regionid = :regionid AND roleid = :roleid");
    $query->bindParam('regionid', $id, \PDO::PARAM_STR);
    $query->bindParam('roleid', $roleid, \PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      return $row['access'];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all region records into an array
   *
   * @param boolean $excludeHidden If true, exclude hidden regions
   * @return array Array with records
   */
  public function getAll(): array {
    $records = [];
    $query = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY name");
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
  public function getAllLike(string $like): array {
    $records = [];
    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE name LIKE :like OR description LIKE :like ORDER BY name");
    $likeParam = "%{$like}%";
    $query->bindParam('like', $likeParam, \PDO::PARAM_STR);
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
   * Reads all region names into an array
   *
   * @return array Array with all region names
   */
  public function getAllNames(): array {
    $records = [];
    $query = $this->db->prepare("SELECT name FROM {$this->table} ORDER BY name");
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = $row['name'];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a region record for a given ID
   *
   * @param string $id Region ID to find
   * @return boolean Query result
   */
  public function getById(string $id): bool {
    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
    $query->bindParam('id', $id, \PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      $this->id = $row['id'];
      $this->name = $row['name'];
      $this->description = $row['description'];
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a record by region name
   *
   * @param string $name Region name to find
   * @return boolean Query result
   */
  public function getByName(string $name): bool {
    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE name = :name");
    $query->bindParam('name', $name, \PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      $this->id = $row['id'];
      $this->name = $row['name'];
      $this->description = $row['description'];
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the region ID for a given name
   *
   * @param string $name Region name to find
   * @return string Record ID
   */
  public function getId(string $name): string|false {
    $query = $this->db->prepare("SELECT id FROM {$this->table} WHERE name = :name");
    $query->bindParam('name', $name, \PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      return $row['id'];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a region name for a given ID
   *
   * @param string $id Region ID to find
   * @return boolean Query result
   */
  public function getNameById(string $id): string|false {
    //
    // Check cache first
    //
    if (isset($this->nameCache[$id])) {
      return $this->nameCache[$id];
    }
    $query = $this->db->prepare("SELECT name FROM {$this->table} WHERE id = :id");
    $query->bindParam('id', $id, \PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      //
      // Cache the result
      //
      $this->nameCache[$id] = $row['name'];
      return $row['name'];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a region_role record
   *
   * @return boolean Query result
   */
  public function setAccess(string $id, string $roleid, string $access): bool {
    $query = $this->db->prepare("INSERT INTO {$this->accessTable} (regionid, roleid, access) VALUES (:regionid, :roleid, :access)");
    $query->bindParam('regionid', $id, \PDO::PARAM_STR);
    $query->bindParam('roleid', $roleid, \PDO::PARAM_STR);
    $query->bindParam('access', $access, \PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a region record for a given region ID
   *
   * @param string $name Region ID to update
   * @return boolean Query result
   */
  public function update(string $id): bool {
    $query = $this->db->prepare("UPDATE {$this->table} SET name = :name, description = :description WHERE id = :id");
    $query->bindParam('name', $this->name, \PDO::PARAM_STR);
    $query->bindParam('description', $this->description, \PDO::PARAM_STR);
    $query->bindParam('id', $id, \PDO::PARAM_STR);
    return $query->execute();
  }
}
