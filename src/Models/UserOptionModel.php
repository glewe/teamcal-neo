<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * UserOptionModel
 *
 * This class provides methods and properties for user options.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class UserOptionModel
{
  public ?int    $id       = null;
  public ?string $username = null;
  public ?string $option   = null;
  public mixed   $value    = null;

  private PDO    $db;
  private string $table         = '';
  private string $archive_table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null   $db   Database connection object
   * @param array|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db !== null && $conf !== null) {
      $this->db            = $db;
      $this->table         = $conf['db_table_user_option'];
      $this->archive_table = $conf['db_table_archive_user_option'];
    }
    else {
      global $CONF, $DB;
      $this->db            = $DB->db;
      $this->table         = $CONF['db_table_user_option'];
      $this->archive_table = $CONF['db_table_archive_user_option'];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Archives all records for a given user.
   *
   * @param string $username Username to archive
   *
   * @return bool Query result
   */
  public function archive(string $username): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE `username` = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Restores all records for a given user.
   *
   * @param string $username Username to restore
   *
   * @return bool Query result
   */
  public function restore(string $username): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE `username` = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a record exists.
   *
   * @param string $username Username to find
   * @param bool   $archive  Whether to search in archive table
   *
   * @return bool True if found, false if not
   */
  public function exists(string $username = '', bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE `username` = :username');
    $query->bindParam(':username', $username);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn() > 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a new user-option record.
   *
   * @param string $username Username
   * @param string $option   Option name
   * @param string $value    Option value
   *
   * @return bool Query result
   */
  public function create(string $username, string $option, string $value): bool {
    // Prevent duplicate entry
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :username AND `option` = :option');
    $query->bindParam(':username', $username);
    $query->bindParam(':option', $option);
    $query->execute();
    if ($query->fetchColumn() > 0) {
      return false;
    }
    $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, `option`, value) VALUES (:username, :option, :value)');
    $query2->bindParam(':username', $username);
    $query2->bindParam(':option', $option);
    $query2->bindParam(':value', $value);
    return $query2->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records.
   *
   * @param bool $archive Whether to search in archive table
   *
   * @return bool Query result
   */
  public function deleteAll(bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $admin = 'admin';
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username <> :admin');
    $query->bindParam(':admin', $admin);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a user-option record by ID from local class variable.
   *
   * @return bool Query result
   */
  public function deleteById(): bool {
    if ($this->id === null) {
      return false;
    }
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE `id` = :id');
    $query->bindParam(':id', $this->id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records for a given user.
   *
   * @param string $username Username to delete
   * @param bool   $archive  Whether to use archive table
   *
   * @return bool Query result
   */
  public function deleteByUser(string $username = '', bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE `username` = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Delete all records for a given option.
   *
   * @param string $option Option to delete
   *
   * @return bool Query result
   */
  public function deleteOption(string $option): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE `option` = :option');
    $query->bindParam(':option', $option);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Delete all option records for a given value.
   *
   * @param string $option Option to delete
   * @param string $value  Value to delete
   *
   * @return bool Query result
   */
  public function deleteOptionByValue(string $option, string $value): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE `option` = :option AND `value` = :value');
    $query->bindParam(':option', $option);
    $query->bindParam(':value', $value);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Delete an option records for a given user.
   *
   * @param string $username Username to find
   * @param string $option   Option to delete
   *
   * @return bool Query result
   */
  public function deleteUserOption(string $username, string $option): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE `username` = :username AND `option` = :option');
    $query->bindParam(':username', $username);
    $query->bindParam(':option', $option);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether an option record for a given user exists.
   *
   * @param string $username Username to find
   * @param string $option   Option to earch for
   *
   * @return bool True if found, false if not
   */
  public function hasOption(string $username, string $option): bool {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE `username` = :username AND `option` = :option');
    $query->bindParam(':username', $username);
    $query->bindParam(':option', $option);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn() > 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Finds the value of an option for a given user.
   *
   * @param string $username Username to find
   * @param string $option   Option to find
   * @param bool   $archive  Whether to search in archive table
   *
   * @return string|false Value of the option (or false if not found)
   */
  public function read(string $username, string $option, bool $archive = false): string|false {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE `username` = :username AND `option` = :option');
    $query->bindParam(':username', $username);
    $query->bindParam(':option', $option);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      return (string) $row['value'];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Save a user option or creates it if not exists.
   *
   * @param string $username Username to find
   * @param string $option   Option to find
   * @param string $value    New value
   *
   * @return bool Query result
   */
  public function save(string $username, string $option, string $value): bool {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE `username` = :username AND `option` = :option');
    $query->bindParam(':username', $username);
    $query->bindParam(':option', $option);
    $result = $query->execute();
    if ($result && $query->fetchColumn() > 0) {
      $query2 = $this->db->prepare('UPDATE ' . $this->table . ' SET `value` = :value WHERE `username` = :username AND `option` = :option');
      $query2->bindParam(':value', $value);
      $query2->bindParam(':username', $username);
      $query2->bindParam(':option', $option);
      return $query2->execute();
    }
    else {
      $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (`username`, `option`, `value`) VALUES (:username, :option, :value)');
      $query2->bindParam(':username', $username);
      $query2->bindParam(':option', $option);
      $query2->bindParam(':value', $value);
      return $query2->execute();
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Save multiple user options in a batch.
   *
   * @param string $username Username to find
   * @param array  $options  Associative array of option=>value pairs
   *
   * @return bool Query result
   */
  public function saveBatch(string $username, array $options): bool {
    if (empty($options)) {
      return true;
    }

    $placeholders = [];
    $values       = [];
    foreach ($options as $option => $value) {
      $placeholders[] = "(?, ?, ?)";
      $values[]       = $username;
      $values[]       = $option;
      $values[]       = $value;
    }

    $sql   = "INSERT INTO " . $this->table . " (`username`, `option`, `value`) VALUES " . implode(', ', $placeholders) . " ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)";
    $query = $this->db->prepare($sql);
    return $query->execute($values);
  }

  //---------------------------------------------------------------------------
  /**
   * Finds the boolean or yes/no value of an option for a given user.
   *
   * @param string $username Username to find
   * @param string $option   Option to find
   *
   * @return bool True or false
   */
  public function true(string $username, string $option): bool {
    $query = $this->db->prepare('SELECT value FROM ' . $this->table . ' WHERE `username` = :username AND `option` = :option');
    $query->bindParam(':username', $username);
    $query->bindParam(':option', $option);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      $val = trim((string) $row['value']);
      return $val !== '' && strtolower($val) !== 'no' && strtolower($val) !== 'false' && $val !== '0';
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Updates defregion name in all records.
   *
   * @param string $regionold Old regionname
   * @param string $regionnew New regionname
   *
   * @return bool Query result
   */
  public function updateRegion(string $regionold, string $regionnew = 'default'): bool {
    $option = 'defregion';
    $query  = $this->db->prepare('UPDATE ' . $this->table . ' SET `value` = :regionnew WHERE `option` = :option AND `value` = :regionold');
    $query->bindParam(':regionnew', $regionnew);
    $query->bindParam(':option', $option);
    $query->bindParam(':regionold', $regionold);
    return $query->execute();
  }
}
