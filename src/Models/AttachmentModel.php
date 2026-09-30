<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * AttachmentModel
 *
 * This class provides methods and properties for attachments.
 *
 * The public API takes/returns uploader usernames (matching every caller's
 * existing data), resolving internally to the tcneo_users.id foreign key
 * that tcneo_attachments.uploader_id actually stores - same approach as
 * AbsenceDayModel/AllowanceModel.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class AttachmentModel
{
  private \PDO   $db;
  private string $table       = '';
  private string $users_table = '';

  /** @var array<string, int|null> */
  private array $userIdCache = [];
  /** @var array<int, string> */
  private array $usernameCache = [];

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null             $db   Database connection object
   * @param array<string, string>|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db !== null && $conf !== null) {
      $this->db          = $db;
      $this->table       = $conf['db_table_attachments'];
      $this->users_table = $conf['db_table_users'];
    }
    else {
      global $CONF, $dbModel;
      $this->db          = $dbModel->db;
      $this->table       = $CONF['db_table_attachments'];
      $this->users_table = $CONF['db_table_users'];
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
   * Resolves a tcneo_users.id back to its username, caching the result.
   *
   * @param int|null $userId User ID to resolve
   *
   * @return string Username, or '' if not found/null
   */
  private function resolveUsername(?int $userId): string {
    if ($userId === null) {
      return '';
    }
    if (isset($this->usernameCache[$userId])) {
      return $this->usernameCache[$userId];
    }
    $query = $this->db->prepare('SELECT username FROM ' . $this->users_table . ' WHERE id = :id');
    $query->bindValue(':id', $userId, PDO::PARAM_INT);
    $query->execute();
    $username = $query->fetchColumn();
    return $this->usernameCache[$userId] = ($username !== false ? (string) $username : '');
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a record.
   *
   * @param string $filename File name
   * @param string $uploader Uploader username
   *
   * @return string|bool Last insert ID or boolean result
   */
  public function create(string $filename, string $uploader): string|bool {
    // Check if the file already exists to avoid duplicate entry error
    $query = $this->db->prepare('SELECT id FROM ' . $this->table . ' WHERE filename = :filename');
    $query->bindParam(':filename', $filename);
    $query->execute();
    if ($row = $query->fetch()) {
      // File already exists, return its ID
      return (string) $row['id'];
    }
    // Insert new record
    $uploaderId = $this->resolveUserId($uploader);
    $query      = $this->db->prepare('INSERT INTO ' . $this->table . ' (`filename`, `uploader_id`) VALUES (:filename, :uploader_id)');
    $query->bindParam(':filename', $filename);
    $query->bindValue(':uploader_id', $uploaderId, $uploaderId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $result = $query->execute();
    if ($result) {
      return $this->db->lastInsertId();
    }
    else {
      return $result;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a record by filename.
   *
   * @param string $filename File name to delete
   *
   * @return bool Query result
   */
  public function delete(string $filename): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE filename = :filename');
    $query->bindParam(':filename', $filename);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a record by ID.
   *
   * @param string $id ID to delete
   *
   * @return bool Query result
   */
  public function deleteById(string $id): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all records into an array.
   *
   * @return array<int, array<string, mixed>> Array with records
   */
  public function getAll(): array {
    $records = [];
    $query   = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY filename ASC');
    if ($query->execute()) {
      while ($row = $query->fetch()) {
        $records[] = $row;
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the record ID of a given file.
   *
   * @param string $filename File name to find
   *
   * @return string|bool Record ID or false
   */
  public function getId(string $filename): string|bool {
    $query = $this->db->prepare('SELECT id FROM ' . $this->table . ' WHERE filename = :filename');
    $query->bindParam(':filename', $filename);
    if ($query->execute() && $row = $query->fetch()) {
      return (string) $row['id'];
    }
    else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the uploader username of a given file.
   *
   * @param string $filename File name to find
   *
   * @return string|bool Uploader username or false
   */
  public function getUploader(string $filename): string|bool {
    $query = $this->db->prepare('SELECT uploader_id FROM ' . $this->table . ' WHERE filename = :filename');
    $query->bindParam(':filename', $filename);
    if ($query->execute() && $row = $query->fetch()) {
      return $this->resolveUsername($row['uploader_id'] !== null ? (int) $row['uploader_id'] : null);
    }
    else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the uploader username of a given file ID.
   *
   * @param string $fileid File ID to find
   *
   * @return string|bool Uploader username or false
   */
  public function getUploaderById(string $fileid): string|bool {
    $query = $this->db->prepare('SELECT uploader_id FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $fileid);
    if ($query->execute() && $row = $query->fetch()) {
      return $this->resolveUsername($row['uploader_id'] !== null ? (int) $row['uploader_id'] : null);
    }
    else {
      return false;
    }
  }
}
