<?php

/**
 * UserAttachment
 *
 * This class provides methods and properties for user attachments.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class UserAttachment {
  public ?int $id = null;
  public string $username = '';
  public ?int $fileid = null;

  private $db = null;
  private string $table = '';
  private string $archive_table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_user_attachment'];
    $this->archive_table = $CONF['db_table_archive_user_attachment'];
  }

  //---------------------------------------------------------------------------
  /**
   * Archives all records for a given user
   *
   * @param string $username Username to archive
   * @return boolean Query result
   */
  public function archive(string $username): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE username = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Restores all records for a given user
   *
   * @param string $name Username to restore
   * @return boolean Query result
   */
  public function restore(string $username): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a record exists
   *
   * @param string $username Username to find
   * @param boolean $archive Whether to use archive table
   * @return boolean True if found, false if not
   */
  public function exists(string $username = '', bool $archive = false): bool {
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    $result = $query->execute();
    return $result && $query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a new record
   *
   * @param string $username Username
   * @param string $fileid File ID
   * @return boolean Query result
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
   * Deletes all records
   *
   * @param boolean $archive Whether to use archive table
   * @return boolean Query result
   */
  public function deleteAll(bool $archive = false): bool {
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }
    $query = $this->db->prepare("DELETE FROM " . $table . " WHERE username <> 'admin'");
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records for a given username
   *
   * @param string $username Username to delete
   * @param boolean $archive Whether to use archive table
   * @return boolean Query result
   */
  public function deleteUser(string $username = '', bool $archive = false): bool {
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Delete all records for a given file ID
   *
   * @param string $fileid File ID
   * @return boolean Query result
   */
  public function deleteFile(string $fileid): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE fileid = :val1');
    $query->bindParam('val1', $fileid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a user has access to a file
   *
   * @param string $username Username to find
   * @param string $fileid File ID to find
   * @return string true if exists, false if not
   */
  public function hasAccess(string $username, string $fileid): bool|int {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :username AND fileid = :fileid');
    $query->bindParam(':username', $username);
    $query->bindParam(':fileid', $fileid);
    $result = $query->execute();
    return $result && $query->fetchColumn();
  }
}
