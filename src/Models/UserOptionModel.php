<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * UserOptionModel
 *
 * This class provides methods and properties for user options.
 *
 * The public API takes usernames (matching every caller's existing data -
 * this is the most-called model in the codebase), resolving internally to
 * the tcneo_users.id foreign key that tcneo_user_option.user_id actually
 * stores - same approach as AbsenceDayModel/AllowanceModel. Since this
 * model is normally instantiated once per request via the container, the
 * per-instance resolve cache means each distinct username costs one extra
 * lookup query for the whole request, not per call.
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
  private string $users_table   = '';
  private string $archive_users_table = '';

  /** @var array<string, int|null> */
  private array $userIdCache = [];

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null             $db   Database connection object
   * @param array<string, string>|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db !== null && $conf !== null) {
      $this->db            = $db;
      $this->table         = $conf['db_table_user_option'];
      $this->archive_table = $conf['db_table_archive_user_option'];
      $this->users_table   = $conf['db_table_users'];
      $this->archive_users_table = $conf['db_table_archive_users'];
    }
    else {
      global $CONF, $dbModel;
      $this->db            = $dbModel->db;
      $this->table         = $CONF['db_table_user_option'];
      $this->archive_table = $CONF['db_table_archive_user_option'];
      $this->users_table   = $CONF['db_table_users'];
      $this->archive_users_table = $CONF['db_table_archive_users'];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Resolves a username to its tcneo_users.id, caching the result.
   *
   * @param string $username Username to resolve
   *
   * @return int|null Resolved id, or null if the username is empty/unknown
   */
  private function resolveUserId(string $username): ?int {
    if ($username === '') {
      return null;
    }
    if (array_key_exists($username, $this->userIdCache)) {
      return $this->userIdCache[$username];
    }
    $query = $this->db->prepare('SELECT id FROM ' . $this->users_table . ' WHERE username = :username');
    $query->bindParam(':username', $username);
    $query->execute();
    $id = $query->fetchColumn();
    if ($id === false) {
      return null; // do not cache misses - the user may be created later in this request
    }
    return $this->userIdCache[$username] = (int) $id;
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
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE `user_id` = :user_id');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
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
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE `user_id` = :user_id');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
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
    $table  = $archive ? $this->archive_table : $this->table;
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE `user_id` = :user_id');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
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
    $userId = $this->resolveUserId($username);
    if ($userId === null) {
      return false; // not a stored user (e.g. an anonymous visitor): there is nothing to attach the option to
    }
    // Prevent duplicate entry
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE user_id = :user_id AND `option` = :option');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query->bindParam(':option', $option);
    $query->execute();
    if ($query->fetchColumn() > 0) {
      return false;
    }
    $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (user_id, `option`, value) VALUES (:user_id, :option, :value)');
    $query2->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query2->bindParam(':option', $option);
    $query2->bindParam(':value', $value);
    return $query2->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records belonging to non-system users.
   *
   * @param bool $archive Whether to search in archive table
   *
   * @return bool Query result
   */
  public function deleteAll(bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE user_id NOT IN (SELECT id FROM ' . $this->users_table . ' WHERE is_system = 1)');
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
    $table  = $archive ? $this->archive_table : $this->table;
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('DELETE FROM ' . $table . ' WHERE `user_id` = :user_id');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
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
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE `user_id` = :user_id AND `option` = :option');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
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
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE `user_id` = :user_id AND `option` = :option');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query->bindParam(':option', $option);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn() > 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Reads the given options for many users with a single query.
   *
   * Archived users only exist in the archive users table, so the matching
   * users table is joined when $archive is set.
   *
   * @param array<int, string> $usernames Usernames to read for
   * @param array<int, string> $options   Option names to read
   * @param bool               $archive   Whether to read from the archive tables
   *
   * @return array<string, array<string, string>> [username => [option => value]]
   */
  public function readForUsers(array $usernames, array $options, bool $archive = false): array {
    $result = [];
    if (empty($usernames) || empty($options)) {
      return $result;
    }
    $table      = $archive ? $this->archive_table : $this->table;
    $usersTable = $archive ? $this->archive_users_table : $this->users_table;
    $userMarks  = implode(',', array_fill(0, count($usernames), '?'));
    $optMarks   = implode(',', array_fill(0, count($options), '?'));
    $query      = $this->db->prepare(
      'SELECT u.username, uo.`option`, uo.value FROM ' . $table . ' uo'
      . ' JOIN ' . $usersTable . ' u ON u.id = uo.user_id'
      . ' WHERE uo.`option` IN (' . $optMarks . ') AND u.username IN (' . $userMarks . ')'
    );
    $query->execute(array_merge(array_values($options), array_values($usernames)));
    while ($row = $query->fetch()) {
      $result[(string) $row['username']][(string) $row['option']] = (string) $row['value'];
    }
    return $result;
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
    $table  = $archive ? $this->archive_table : $this->table;
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE `user_id` = :user_id AND `option` = :option');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
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
    $userId = $this->resolveUserId($username);
    if ($userId === null) {
      return false; // not a stored user (e.g. an anonymous visitor): there is nothing to attach the option to
    }
    $query  = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE `user_id` = :user_id AND `option` = :option');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query->bindParam(':option', $option);
    $result = $query->execute();
    if ($result && $query->fetchColumn() > 0) {
      $query2 = $this->db->prepare('UPDATE ' . $this->table . ' SET `value` = :value WHERE `user_id` = :user_id AND `option` = :option');
      $query2->bindParam(':value', $value);
      $query2->bindValue(':user_id', $userId, PDO::PARAM_INT);
      $query2->bindParam(':option', $option);
      return $query2->execute();
    }
    else {
      $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (`user_id`, `option`, `value`) VALUES (:user_id, :option, :value)');
      $query2->bindValue(':user_id', $userId, PDO::PARAM_INT);
      $query2->bindParam(':option', $option);
      $query2->bindParam(':value', $value);
      return $query2->execute();
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Save multiple user options in a batch.
   *
   * @param string               $username Username to find
   * @param array<string, string> $options  Associative array of option=>value pairs
   *
   * @return bool Query result
   */
  public function saveBatch(string $username, array $options): bool {
    if (empty($options)) {
      return true;
    }
    $userId = $this->resolveUserId($username);
    if ($userId === null) {
      return false; // not a stored user (e.g. an anonymous visitor): there is nothing to attach the option to
    }

    $placeholders = [];
    $values       = [];
    foreach ($options as $option => $value) {
      $placeholders[] = "(?, ?, ?)";
      $values[]       = $userId;
      $values[]       = $option;
      $values[]       = $value;
    }

    $sql   = "INSERT INTO " . $this->table . " (`user_id`, `option`, `value`) VALUES " . implode(', ', $placeholders) . " ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)";
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
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('SELECT value FROM ' . $this->table . ' WHERE `user_id` = :user_id AND `option` = :option');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
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
