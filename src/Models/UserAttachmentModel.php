<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * UserAttachmentModel
 *
 * This class provides methods and properties for user attachments.
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
  public ?int   $id       = null;
  public string $username = '';
  public ?int   $fileid   = null;

  private PDO    $db;
  private string $table         = '';
  private string $archive_table = '';

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
    }
    else {
      global $CONF, $DB;
      $this->db            = $DB->db;
      $this->table         = $CONF['db_table_user_attachment'];
      $this->archive_table = $CONF['db_table_archive_user_attachment'];
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
    $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE username = :username');
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
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = :username');
    $query->bindParam(':username', $username);
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
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE username = :username');
    $query->bindParam(':username', $username);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn());
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a new record.
   *
   * @param string $username Username
   * @param string $fileid   File ID
   *
   * @return bool Query result
   */
  public function create(string $username, string $fileid): bool {
    // Prevent duplicate user-file entries
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :username AND fileid = :fileid');
    $query->bindParam(':username', $username);
    $query->bindParam(':fileid', $fileid);
    $query->execute();
    if ($query->fetchColumn() > 0) {
      return true;
    }
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, fileid) VALUES (:username, :fileid)');
    $query->bindParam(':username', $username);
    $query->bindParam(':fileid', $fileid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records.
   *
   * @param bool $archive Whether to use archive table
   *
   * @return bool Query result
   */
  public function deleteAll(bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare("DELETE FROM " . $table . " WHERE username <> 'admin'");
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
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Delete all records for a given file ID.
   *
   * @param string $fileid File ID
   *
   * @return bool Query result
   */
  public function deleteFile(string $fileid): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE fileid = :fileid');
    $query->bindParam(':fileid', $fileid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a user has access to a file.
   *
   * @param string $username Username to find
   * @param string $fileid   File ID to find
   *
   * @return bool True if exists, false if not
   */
  public function hasAccess(string $username, string $fileid): bool {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :username AND fileid = :fileid');
    $query->bindParam(':username', $username);
    $query->bindParam(':fileid', $fileid);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn());
  }
}
