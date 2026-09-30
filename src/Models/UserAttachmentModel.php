<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * UserAttachmentModel
 *
 * This class provides methods for per-user attachment access grants
 * (tcneo_user_attachment).
 *
 * The public API takes usernames (matching every caller's existing data),
 * resolving internally to the tcneo_users.id foreign key that user_id
 * actually stores - same approach as AbsenceDayModel/AllowanceModel. The
 * `fileid` param name is kept (it already held a real tcneo_attachments.id)
 * even though the column is now `attachment_id`.
 *
 * Note: `$id`/`$username`/`$fileid` public properties from the old version
 * of this class were never actually read or written by any method - every
 * method takes its own parameters - so they were dropped rather than ported.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class UserAttachmentModel
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
      $this->table         = $conf['db_table_user_attachment'];
      $this->archive_table = $conf['db_table_archive_user_attachment'];
      $this->users_table   = $conf['db_table_users'];
    }
    else {
      global $CONF, $dbModel;
      $this->db            = $dbModel->db;
      $this->table         = $CONF['db_table_user_attachment'];
      $this->archive_table = $CONF['db_table_archive_user_attachment'];
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
   * Archives all records for a given user.
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
   * Restores all records for a given user.
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
   * Checks whether a record exists.
   *
   * @param string $username Username to find
   * @param bool   $archive  Whether to use archive table
   *
   * @return bool True if found, false if not
   */
  public function exists(string $username = '', bool $archive = false): bool {
    $table  = $archive ? $this->archive_table : $this->table;
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE user_id = :user_id');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn());
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a new record.
   *
   * @param string $username Username
   * @param string $fileid   File (attachment) ID
   *
   * @return bool Query result
   */
  public function create(string $username, string $fileid): bool {
    $userId = $this->resolveUserId($username);
    // Prevent duplicate user-file entries
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE user_id = :user_id AND attachment_id = :fileid');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query->bindParam(':fileid', $fileid);
    $query->execute();
    if ($query->fetchColumn() > 0) {
      return true;
    }
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (user_id, attachment_id) VALUES (:user_id, :fileid)');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query->bindParam(':fileid', $fileid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records except grants belonging to system users.
   *
   * @param bool $archive Whether to use archive table
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
   * Deletes all records for a given username.
   *
   * @param string $username Username to delete
   * @param bool   $archive  Whether to use archive table
   *
   * @return bool Query result
   */
  public function deleteUser(string $username = '', bool $archive = false): bool {
    $table  = $archive ? $this->archive_table : $this->table;
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('DELETE FROM ' . $table . ' WHERE user_id = :user_id');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Delete all records for a given file ID.
   *
   * @param string $fileid File (attachment) ID
   *
   * @return bool Query result
   */
  public function deleteFile(string $fileid): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE attachment_id = :fileid');
    $query->bindParam(':fileid', $fileid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a user has access to a file.
   *
   * @param string $username Username to find
   * @param string $fileid   File (attachment) ID to find
   *
   * @return bool True if exists, false if not
   */
  public function hasAccess(string $username, string $fileid): bool {
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE user_id = :user_id AND attachment_id = :fileid');
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query->bindParam(':fileid', $fileid);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn());
  }
}
