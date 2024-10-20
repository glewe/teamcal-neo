<?php

/**
 * Permissions
 *
 * This class provides methods and properties for user permissions.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Permissions {
  private $db = '';
  private $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_permissions'];
  }

  //---------------------------------------------------------------------------
  /**
   * Delete all records (except default)
   *
   * @return boolean Query result
   */
  public function deleteAll() {
    $query = $this->db->prepare("DELETE FROM " . $this->table . " WHERE scheme <> 'Default'");
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Delete a role from all permission schemes
   *
   * @param integer $role Role ID
   * @return boolean Query result
   */
  public function deleteRole($role) {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE role = :val1');
    $query->bindParam('val1', $role);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Delete a permission scheme
   *
   * @param string $scheme Name of the permission scheme
   * @return boolean Query result
   */
  public function deleteScheme($scheme) {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE scheme = :val1');
    $query->bindParam('val1', $scheme);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves all permissions for a given scheme.
   *
   * @param string $scheme The name of the permission scheme.
   * @return array An array of permissions associated with the given scheme.
   */
  public function getPermissions($scheme) {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE scheme = :val1 AND allowed = :val2');
    $one = 1;
    $query->bindParam('val1', $scheme);
    $query->bindParam('val2', $one);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = [
          'permission' => $row['permission'],
          'role' => $row['role']
        ];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Read all unique scheme names
   *
   * @return array Array of scheme names
   */
  public function getSchemes() {
    $records = array();
    $query = $this->db->prepare('SELECT DISTINCT scheme FROM ' . $this->table);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = $row['scheme'];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given role has a given permission
   *
   * @param string $scheme Name of the permission scheme
   * @param string $permission Name of the permission
   * @param string $role Role of the permission
   * @return boolean True or False
   */
  public function isAllowed($scheme, $permission, $role) {
    $query = $this->db->prepare('SELECT allowed FROM ' . $this->table . ' WHERE scheme = :val1 AND permission = :val2 AND role = :val3');
    $query->bindParam('val1', $scheme);
    $query->bindParam('val2', $permission);
    $query->bindParam('val3', $role);
    $result = $query->execute();

    if ($result && $row = $query->fetch()) {
      return $row['allowed'];
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a scheme exists
   *
   * @param string $scheme Scheme name to look for
   * @return boolean True or false
   */
  public function schemeExists($scheme) {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE scheme = :val1');
    $query->bindParam('val1', $scheme);
    $result = $query->execute();

    return $result && $query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Update/create a permission
   *
   * @param string $scheme Name of the permission scheme
   * @param string $permission Name of the permission
   * @param string $role Role of the permission
   * @param bool $allowed True or False
   * @return boolean Query result
   */
  public function setPermission($scheme, $permission, $role, $allowed) {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE scheme = :val1 AND permission = :val2 AND role = :val3');
    $query->bindParam('val1', $scheme);
    $query->bindParam('val2', $permission);
    $query->bindParam('val3', $role);
    $result = $query->execute();

    if ($result) {
      if (!$query->fetch()) {
        $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (scheme, permission, role, allowed) VALUES (:val1, :val2, :val3, :val4)');
      } else {
        $query2 = $this->db->prepare('UPDATE ' . $this->table . ' SET allowed = :val4 WHERE scheme = :val1 AND permission = :val2 AND role = :val3');
      }
      $query2->bindParam('val1', $scheme);
      $query2->bindParam('val2', $permission);
      $query2->bindParam('val3', $role);
      $query2->bindParam('val4', $allowed);
      return $query2->execute();
    } else {
      return $result;
    }
  }
}
