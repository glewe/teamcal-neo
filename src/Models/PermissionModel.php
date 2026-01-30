<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * PermissionModel
 *
 * This class provides methods and properties for permissions.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class PermissionModel
{
  private ?PDO   $db    = null;
  private string $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null   $db   Database object
   * @param array|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db && $conf) {
      $this->db    = $db;
      $this->table = $conf['db_table_permissions'];
    }
    else {
      global $CONF, $DB;
      $this->db    = $DB->db;
      $this->table = $CONF['db_table_permissions'];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Delete all records (except default).
   *
   * @return bool Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE scheme <> 'Default'");
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Delete a role from all permission schemes.
   *
   * @param int $role Role ID
   *
   * @return bool Query result
   */
  public function deleteRole(string|int $role): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE role = :role");
    $query->bindParam('role', $role, \PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Delete a permission scheme.
   *
   * @param string $scheme Name of the permission scheme
   *
   * @return bool Query result
   */
  public function deleteScheme(string $scheme): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE scheme = :scheme");
    $query->bindParam('scheme', $scheme, \PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Set multiple permissions in a single batch operation.
   *
   * @param array $batchData Array of permission records to set
   *
   * @return bool Query result
   */
  public function setPermissionsBatch(array $batchData): bool {
    if (empty($batchData)) {
      return false;
    }

    $placeholders = [];
    $values       = [];

    foreach ($batchData as $record) {
      $placeholders[] = '(?, ?, ?, ?)';
      $values[]       = $record['scheme'];
      $values[]       = $record['permission'];
      $values[]       = $record['role'];
      $values[]       = $record['allowed'];
    }

    $sql = "INSERT INTO {$this->table}
             (scheme, permission, role, allowed)
             VALUES " . implode(', ', $placeholders) . "
             ON DUPLICATE KEY UPDATE allowed = VALUES(allowed)";

    $query = $this->db->prepare($sql);
    return $query->execute($values);
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves all permissions for a given scheme.
   *
   * @param string $scheme The name of the permission scheme.
   *
   * @return array An array of permissions associated with the given scheme.
   */
  public function getPermissions(string $scheme): array {
    $records = [];
    $query   = $this->db->prepare("SELECT permission, role FROM {$this->table} WHERE scheme = :scheme AND allowed = :allowed");
    $one     = 1;
    $query->bindParam('scheme', $scheme, \PDO::PARAM_STR);
    $query->bindParam('allowed', $one, \PDO::PARAM_INT);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = [
          'permission' => $row['permission'],
          'role'       => $row['role'],
        ];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Read all unique scheme names.
   *
   * @return array Array of scheme names
   */
  public function getSchemes(): array {
    $records = [];
    $query   = $this->db->prepare("SELECT DISTINCT scheme FROM {$this->table}");
    $result  = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = $row['scheme'];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given role has a given permission.
   *
   * @param string $scheme Name of the permission scheme
   * @param string $permission Name of the permission
   * @param string $role Role of the permission
   *
   * @return bool True or False
   */
  public function isAllowed(string $scheme, string $permission, string|int $role): bool {
    $query = $this->db->prepare("SELECT allowed FROM {$this->table} WHERE scheme = :scheme AND permission = :permission AND role = :role");
    $query->bindParam('scheme', $scheme, \PDO::PARAM_STR);
    $query->bindParam('permission', $permission, \PDO::PARAM_STR);
    $query->bindParam('role', $role, \PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      return (bool) $row['allowed'];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a scheme exists.
   *
   * @param string $scheme Scheme name to look for
   *
   * @return bool True or false
   */
  public function schemeExists(string $scheme): bool {
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE scheme = :scheme");
    $query->bindParam('scheme', $scheme, \PDO::PARAM_STR);
    $result = $query->execute();
    return $result && $query->fetchColumn() > 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Update/create a permission.
   *
   * @param string $scheme Name of the permission scheme
   * @param string $permission Name of the permission
   * @param string $role Role of the permission
   * @param bool $allowed True or False
   *
   * @return bool Query result
   */
  public function setPermission(string $scheme, string $permission, string|int $role, bool $allowed): bool {
    $query = $this->db->prepare("SELECT 1 FROM {$this->table} WHERE scheme = :scheme AND permission = :permission AND role = :role");
    $query->bindParam('scheme', $scheme, \PDO::PARAM_STR);
    $query->bindParam('permission', $permission, \PDO::PARAM_STR);
    $query->bindParam('role', $role, \PDO::PARAM_STR);
    $result = $query->execute();

    if ($result) {
      if (!$query->fetch()) {
        $query2 = $this->db->prepare("INSERT INTO {$this->table} (scheme, permission, role, allowed) VALUES (:scheme, :permission, :role, :allowed)");
      }
      else {
        $query2 = $this->db->prepare("UPDATE {$this->table} SET allowed = :allowed WHERE scheme = :scheme AND permission = :permission AND role = :role");
      }
      $query2->bindParam('scheme', $scheme, \PDO::PARAM_STR);
      $query2->bindParam('permission', $permission, \PDO::PARAM_STR);
      $query2->bindParam('role', $role, \PDO::PARAM_STR);
      $allowedInt = $allowed ? 1 : 0;
      $query2->bindParam('allowed', $allowedInt, \PDO::PARAM_INT);
      return $query2->execute();
    }
    return false;
  }
}
