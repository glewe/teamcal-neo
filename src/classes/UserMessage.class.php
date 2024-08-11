<?php

/**
 * UserMessage
 *
 * This class provides methods and properties for user group assignments.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class UserMessage {
  private $db = '';
  private $table = '';
  private $archive_table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_user_message'];
    $this->archive_table = $CONF['db_table_archive_user_message'];
  }

  //---------------------------------------------------------------------------
  /**
   * Archives a user record
   *
   * @param string $username Username to archive
   * @return boolean Query result
   */
  public function archive($username) {
    $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE username = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Adds a message link for a user
   *
   * @param string $username Username to assign to
   * @param string $msgid Message ID
   * @param string $popup Popup type
   * @return boolean Query result
   */
  public function add($username, $msgid, $popup) {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, msgid, popup) VALUES (:val1, :val2, :val3)');
    $query->bindParam('val1', $username);
    $query->bindParam('val2', $msgid);
    $query->bindParam('val3', $popup);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a user has message links
   *
   * @param string $username Username to find
   * @param boolean $archive Whether to search in archive table
   * @return boolean True if found, false if not
   */
  public function exists($username = '', $archive = false) {
    if ($archive) {
      $table = $this->archive_table;
    }
    else {
      $table = $this->table;
    }
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    $result = $query->execute();
    return $result && $query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a message link
   *
   * @param string $id ID of the records to delete
   * @param boolean $archive Whether to search in archive table
   * @return boolean Query result
   */
  public function delete($id, $archive = false) {
    if ($archive) {
      $table = $this->archive_table;
    }
    else {
      $table = $this->table;
    }
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE id = :val1');
    $query->bindParam('val1', $id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @param boolean $archive Whether to search in archive table
   * @return boolean Query result
   */
  public function deleteAll($archive = false) {
    if ($archive) {
      $table = $this->archive_table;
    }
    else {
      $table = $this->table;
    }
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table);
    $result = $query->execute();
    if ($result && $query->fetchColumn()) {
      $query = $this->db->prepare('TRUNCATE TABLE ' . $table);
      return $query->execute();
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all message links for a given user
   *
   * @param string $username Username of the records to delete
   * @param boolean $archive Whether to search in archive table
   * @return boolean Query result
   */
  public function deleteByUser($username, $archive = false) {
    if ($archive) {
      $table = $this->archive_table;
    }
    else {
      $table = $this->table;
    }
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all message link IDs for a given user
   *
   * @param string $username Username
   * @return array Array with records
   */
  public function getAllByUser($username) {
    $records = array();
    $query = $this->db->prepare('SELECT msgid FROM ' . $this->table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
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
   * Gets all message links for a given message ID
   *
   * @param string $msgid Message ID
   * @return array Array with records
   */
  public function getAllByMsgId($msgid) {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE msgid = :val1');
    $query->bindParam('val1', $msgid);
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
   * Gets all popup message link IDs for a given user
   *
   * @param string $username Username
   * @return array Array with records
   */
  public function getAllPopupByUser($username) {
    $records = array();
    $query = $this->db->prepare('SELECT msgid FROM ' . $this->table . ' WHERE username = :val1 AND popup = :val2');
    $query->bindParam('val1', $username);
    $val2 = '1';
    $query->bindParam('val2', $val2);
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
   * Restore arcived user records
   *
   * @param string $username Username to restore
   * @return boolean Query result
   */
  public function restore($username) {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Sets a message link to silent
   *
   * @param string $id Record ID
   * @return boolean Query result
   */
  public function setSilent($id) {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET popup = :val2 WHERE id = :val1');
    $query->bindParam('val1', $id);
    $val2 = '0';
    $query->bindParam('val2', $val2);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Sets all message links for a given username to silent
   *
   * @param string $username Username
   * @return boolean Query result
   */
  public function setSilentByUser($username) {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET popup = :val2 WHERE username = :val1');
    $query->bindParam('val1', $username);
    $val2 = '0';
    $query->bindParam('val2', $val2);
    return $query->execute();
  }
}
