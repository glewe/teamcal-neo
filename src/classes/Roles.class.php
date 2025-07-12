<?php

/**
 * Roles
 *
 * This class provides methods and properties for user roles.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Roles {
  public string $id = '';
  public string $name = '';
  public string $description = '';
  public string $color = '';
  private $db = null;
  private string $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_roles'];
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a new role record from local class variables
   *
   * @return boolean Query result
   */
  public function create(): bool {
    $query = $this->db->prepare("INSERT INTO {$this->table} (name, description, color) VALUES (:name, :description, :color)");
    $query->bindParam('name', $this->name, \PDO::PARAM_STR);
    $query->bindParam('description', $this->description, \PDO::PARAM_STR);
    $query->bindParam('color', $this->color, \PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a record by ID
   *
   * @param string $id ID to delete
   * @return boolean Query result
   */
  public function delete(string $id): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
    $query->bindParam('id', $id, \PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @return boolean Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$this->table}");
    $result = $query->execute();
    if ($result && $query->fetchColumn()) {
      $query = $this->db->prepare("TRUNCATE TABLE {$this->table}");
      return $query->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all records
   *
   * @return array Array with all role records
   */
  public function getAll(): array {
    $records = [];
    $query = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY name ASC");
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
   * Gets all records with a likeness i name or description
   *
   * @param string $like Likeness to search for
   * @return array Array with all records
   */
  public function getAllLike(string $like): array {
    $records = [];
    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE name LIKE :like OR description LIKE :like ORDER BY name ASC");
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
   * Gets all role names
   *
   * @return array Array with all role names
   */
  public function getAllNames(): array {
    $records = [];
    $query = $this->db->prepare("SELECT name FROM {$this->table} ORDER BY name ASC");
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
   * Gets a record by ID
   *
   * @param string $id Role ID to find
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
      $this->color = $row['color'];
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a role record for a given role name
   *
   * @param string $name Role name to find
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
      $this->color = $row['color'];
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the role color by ID
   *
   * @param string $id Role ID to find
   * @return string Color
   */
  public function getColorById(string $id): string {
    $query = $this->db->prepare("SELECT color FROM {$this->table} WHERE id = :id");
    $query->bindParam('id', $id, \PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      return $row['color'];
    }
    return "default";
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the role color by name
   *
   * @param string $name Role name to find
   * @return string Role color
   */
  public function getColorByName(string $name): string {
    $query = $this->db->prepare("SELECT color FROM {$this->table} WHERE name = :name");
    $query->bindParam('name', $name, \PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      return $row['color'];
    }
    return "default";
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a role name for a given ID
   *
   * @param string $id Role ID to find
   * @return string Role name
   */
  public function getNameById(string $id): string|false {
    $query = $this->db->prepare("SELECT name FROM {$this->table} WHERE id = :id");
    $query->bindParam('id', $id, \PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      return $row['name'];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a record for a given ID
   *
   * @param string $name Role ID to update
   * @return boolean Query result
   */
  public function update(string $id): bool {
    $query = $this->db->prepare("UPDATE {$this->table} SET name = :name, description = :description, color = :color WHERE id = :id");
    $query->bindParam('name', $this->name, \PDO::PARAM_STR);
    $query->bindParam('description', $this->description, \PDO::PARAM_STR);
    $query->bindParam('color', $this->color, \PDO::PARAM_STR);
    $query->bindParam('id', $id, \PDO::PARAM_STR);
    return $query->execute();
  }
}
