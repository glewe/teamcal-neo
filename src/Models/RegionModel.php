<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * RegionModel
 *
 * This class provides methods and properties for regions.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class RegionModel
{
  public string $id          = '';
  public string $name        = '';
  public string $description = '';

  public string $roleid   = '';
  public string $regionid = '';
  public string $access   = '';

  private PDO    $db;
  private string $accessTable = '';
  private string $table       = '';
  /** @var array<string|int, string> */
  private array  $nameCache   = [];

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null             $db   Database object
   * @param array<string, string>|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db !== null && $conf !== null) {
      $this->db          = $db;
      $this->table       = $conf['db_table_regions'];
      $this->accessTable = $conf['db_table_region_role'];
    }
    else {
      global $CONF, $DB;
      $this->db          = $DB->db;
      $this->table       = $CONF['db_table_regions'];
      $this->accessTable = $CONF['db_table_region_role'];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a region record.
   *
   * @return bool Query result
   */
  public function create(): bool {
    $query = $this->db->prepare("INSERT INTO {$this->table} (name, description) VALUES (:name, :description)");
    $query->bindParam(':name', $this->name, PDO::PARAM_STR);
    $query->bindParam(':description', $this->description, PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a region record.
   *
   * @param string|int $id Region ID
   *
   * @return bool Query result
   */
  public function delete(string|int $id): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a region from access table.
   *
   * @param string|int $id Region ID
   *
   * @return bool Query result
   */
  public function deleteAccess(string|int $id): bool {
    $query = $this->db->prepare("DELETE FROM {$this->accessTable} WHERE regionid = :regionid");
    $query->bindParam(':regionid', $id, PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records.
   *
   * @return bool Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare("TRUNCATE TABLE {$this->table}");
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the region_role access.
   *
   * @param string|int $id     Region ID
   * @param string|int $roleid Role ID
   *
   * @return string|bool Access type or false
   */
  public function getAccess(string|int $id, string|int $roleid): string|bool {
    $query = $this->db->prepare("SELECT access FROM {$this->accessTable} WHERE regionid = :regionid AND roleid = :roleid");
    $query->bindParam(':regionid', $id, PDO::PARAM_STR);
    $query->bindParam(':roleid', $roleid, PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      return (string) $row['access'];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all region records into an array.
   *
   * @return array<int, array<string, mixed>> Array with records
   */
  public function getAll(): array {
    $records = [];
    $query   = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY name");
    $result  = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = $row;
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all records with likeness in name or description.
   *
   * @param string $like Likeness to search for
   *
   * @return array<int, array<string, mixed>> Array with records
   */
  public function getAllLike(string $like): array {
    $records   = [];
    $query     = $this->db->prepare("SELECT * FROM {$this->table} WHERE name LIKE :like1 OR description LIKE :like2 ORDER BY name");
    $likeParam = "%{$like}%";
    $query->bindParam(':like1', $likeParam, PDO::PARAM_STR);
    $query->bindParam(':like2', $likeParam, PDO::PARAM_STR);
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
   * Reads all region names into an array.
   *
   * @return string[] Array with all region names
   */
  public function getAllNames(): array {
    $records = [];
    $query   = $this->db->prepare("SELECT name FROM {$this->table} ORDER BY name");
    $result  = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = (string) $row['name'];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a region record for a given ID.
   *
   * @param string|int $id Region ID to find
   *
   * @return bool Query result
   */
  public function getById(string|int $id): bool {
    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      $this->id          = (string) $row['id'];
      $this->name        = (string) $row['name'];
      $this->description = (string) $row['description'];
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a record by region name.
   *
   * @param string $name Region name to find
   *
   * @return bool Query result
   */
  public function getByName(string $name): bool {
    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE name = :name");
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      $this->id          = (string) $row['id'];
      $this->name        = (string) $row['name'];
      $this->description = (string) $row['description'];
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the region ID for a given name.
   *
   * @param string $name Region name to find
   *
   * @return string|bool Record ID or false
   */
  public function getId(string $name): string|bool {
    $query = $this->db->prepare("SELECT id FROM {$this->table} WHERE name = :name");
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      return (string) $row['id'];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a region name for a given ID.
   *
   * @param string|int $id Region ID to find
   *
   * @return string|bool Region name or false
   */
  public function getNameById(string|int $id): string|bool {
    if (isset($this->nameCache[$id])) {
      return $this->nameCache[$id];
    }
    $query = $this->db->prepare("SELECT name FROM {$this->table} WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      $this->nameCache[$id] = (string) $row['name'];
      return (string) $row['name'];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a region_role record.
   *
   * @param string|int $id     Region ID
   * @param string|int $roleid Role ID
   * @param string $access Access type
   *
   * @return bool Query result
   */
  public function setAccess(string|int $id, string|int $roleid, string $access): bool {
    $query = $this->db->prepare("INSERT INTO {$this->accessTable} (regionid, roleid, access) VALUES (:regionid, :roleid, :access)");
    $query->bindParam(':regionid', $id, PDO::PARAM_STR);
    $query->bindParam(':roleid', $roleid, PDO::PARAM_STR);
    $query->bindParam(':access', $access, PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a region record for a given region ID.
   *
   * @param string|int $id Region ID to update
   *
   * @return bool Query result
   */
  public function update(string|int $id): bool {
    $query = $this->db->prepare("UPDATE {$this->table} SET name = :name, description = :description WHERE id = :id");
    $query->bindParam(':name', $this->name, PDO::PARAM_STR);
    $query->bindParam(':description', $this->description, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    return $query->execute();
  }
}
