<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * UserMessageModel
 *
 * This class provides methods and properties for user message assignments.
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
      $this->table         = $conf['db_table_user_message'];
      $this->archive_table = $conf['db_table_archive_user_message'];
    }
    else {
      global $CONF, $DB;
      $this->db            = $DB->db;
      $this->table         = $CONF['db_table_user_message'];
      $this->archive_table = $CONF['db_table_archive_user_message'];
    }
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
    $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE username = :username');
    $query->bindParam(':username', $username);
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
    // Prevent duplicate entry
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :username AND msgid = :msgid');
    $query->bindParam(':username', $username);
    $query->bindParam(':msgid', $msgid);
    $query->execute();
    if ($query->fetchColumn() > 0) {
      return false;
    }
    $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, msgid, popup) VALUES (:username, :msgid, :popup)');
    $query2->bindParam(':username', $username);
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
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE username = :username');
    $query->bindParam(':username', $username);
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
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all message link IDs for a given user.
   *
   * @param string $username Username
   *
   * @return array Array with records
   */
  public function getAllByUser(string $username): array {
    $records = [];
    $query   = $this->db->prepare('SELECT msgid FROM ' . $this->table . ' WHERE username = :username');
    $query->bindParam(':username', $username);
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
   * Gets all message links for a given message ID.
   *
   * @param string $msgid Message ID
   *
   * @return array Array with records
   */
  public function getAllByMsgId(string $msgid): array {
    $records = [];
    $query   = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE msgid = :msgid');
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
   * @return array Array with records
   */
  public function getAllPopupByUser(string $username): array {
    $records = [];
    $popup   = '1';
    $query   = $this->db->prepare('SELECT msgid FROM ' . $this->table . ' WHERE username = :username AND popup = :popup');
    $query->bindParam(':username', $username);
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
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = :username');
    $query->bindParam(':username', $username);
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
    $popup = '0';
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET popup = :popup WHERE username = :username');
    $query->bindParam(':username', $username);
    $query->bindParam(':popup', $popup);
    return $query->execute();
  }
}
