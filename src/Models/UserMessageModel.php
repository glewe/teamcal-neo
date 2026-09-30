<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * UserMessageModel
 *
 * This class provides methods and properties for user message assignments.
 *
 * The public API takes usernames (matching every caller's existing data),
 * resolving internally to the tcneo_users.id foreign key that user_id
 * actually stores - same approach as AbsenceDayModel/AllowanceModel. The
 * `msgid` param name is kept (it already held a real tcneo_messages.id)
 * even though the column is now `message_id`.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class UserMessageModel
{
  private PDO    $db;
  private string $table         = '';
  private string $archive_table = '';
  private string $users_table   = '';

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
      $this->table         = $conf['db_table_user_message'];
      $this->archive_table = $conf['db_table_archive_user_message'];
      $this->users_table   = $conf['db_table_users'];
    }
    else {
      global $CONF, $dbModel;
      $this->db            = $dbModel->db;
      $this->table         = $CONF['db_table_user_message'];
      $this->archive_table = $CONF['db_table_archive_user_message'];
      $this->users_table   = $CONF['db_table_users'];
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
   * Archives a user record.
   *
   * @param string $username Username to archive
   *
   * @return bool Query result
   */
  public function archive(string $username): bool {
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE user_id = :user_id');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Adds a message link for a user.
   *
   * @param string $username Username to assign to
   * @param string $msgid    Message ID
   * @param string $popup    Popup type
   *
   * @return bool Query result
   */
  public function add(string $username, string $msgid, string $popup): bool {
    $userId = $this->resolveUserId($username);
    // Prevent duplicate entry
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE user_id = :user_id AND message_id = :msgid');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query->bindParam(':msgid', $msgid);
    $query->execute();
    if ($query->fetchColumn() > 0) {
      return false;
    }
    $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (user_id, message_id, popup) VALUES (:user_id, :msgid, :popup)');
    $query2->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query2->bindParam(':msgid', $msgid);
    $query2->bindParam(':popup', $popup);
    return $query2->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a user has message links.
   *
   * @param string $username Username to find
   * @param bool   $archive  Whether to search in archive table
   *
   * @return bool True if found, false if not
   */
  public function exists(string $username = '', bool $archive = false): bool {
    $table  = $archive ? $this->archive_table : $this->table;
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE user_id = :user_id');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn() > 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a message link.
   *
   * @param int  $id      ID of the records to delete
   * @param bool $archive Whether to search in archive table
   *
   * @return bool Query result
   */
  public function delete(int $id, bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    return $query->execute();
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
    $table  = $archive ? $this->archive_table : $this->table;
    $query  = $this->db->prepare('SELECT COUNT(*) FROM ' . $table);
    $result = $query->execute();
    if ($result && $query->fetchColumn()) {
      $query = $this->db->prepare('TRUNCATE TABLE ' . $table);
      return $query->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all message links for a given user.
   *
   * @param string $username Username of the records to delete
   * @param bool   $archive  Whether to search in archive table
   *
   * @return bool Query result
   */
  public function deleteByUser(string $username, bool $archive = false): bool {
    $table  = $archive ? $this->archive_table : $this->table;
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('DELETE FROM ' . $table . ' WHERE user_id = :user_id');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all message links for a given message ID.
   *
   * @param string $msgid Message ID
   *
   * @return array<int, array<string, mixed>> Array with records
   */
  public function getAllByMsgId(string $msgid): array {
    $records = [];
    $query   = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE message_id = :msgid');
    $query->bindParam(':msgid', $msgid);
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
   * Gets all popup message link IDs for a given user.
   *
   * @param string $username Username
   *
   * @return array<int, array<string, mixed>> Array with records
   */
  public function getAllPopupByUser(string $username): array {
    $records = [];
    $userId  = $this->resolveUserId($username);
    $popup   = '1';
    $query   = $this->db->prepare('SELECT message_id FROM ' . $this->table . ' WHERE user_id = :user_id AND popup = :popup');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query->bindParam(':popup', $popup);
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
   * Restore arcived user records.
   *
   * @param string $username Username to restore
   *
   * @return bool Query result
   */
  public function restore(string $username): bool {
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE user_id = :user_id');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Sets a message link to silent.
   *
   * @param int $id Record ID
   *
   * @return bool Query result
   */
  public function setSilent(int $id): bool {
    $popup = '0';
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET popup = :popup WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->bindParam(':popup', $popup);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Sets all message links for a given username to silent.
   *
   * @param string $username Username
   *
   * @return bool Query result
   */
  public function setSilentByUser(string $username): bool {
    $userId = $this->resolveUserId($username);
    $popup  = '0';
    $query  = $this->db->prepare('UPDATE ' . $this->table . ' SET popup = :popup WHERE user_id = :user_id');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query->bindParam(':popup', $popup);
    return $query->execute();
  }
}
