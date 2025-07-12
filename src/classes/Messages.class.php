<?php

/**
 * Messages
 *
 * This class provides methods and properties for user roles.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Messages {
  public $id = null;
  public $timestamp = '';
  public $username = '';
  public $text = '';
  public $popup = '0';
  public $type = 'info';

  private $table = '';
  private $db = null;
  private $umtable = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_messages'];
    $this->umtable = $CONF['db_table_user_message'];
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a message
   *
   * @param string $timestamp Timestamp
   * @param string $username Username
   * @param string $text Text of the message
   * @param string $type Type of the message
   * @return integer Last inserted record ID
   */
  public function create(string $timestamp, string $text, string $type = 'info'): int|false {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (timestamp, text, type) VALUES (:timestamp, :text, :type)');
    $query->bindParam(':timestamp', $timestamp);
    $query->bindParam(':text', $text);
    $query->bindParam(':type', $type);
    $result = $query->execute();
    if ($result) {
      return (int)$this->db->lastInsertId();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a message
   *
   * @param string $id Record ID to delete
   * @return boolean Query result
   */
  public function delete(string $id): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @return boolean Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all records into an array
   *
   * @return array $records Array with all records
   */
  public function getAll(): array {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table);
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all records for a given username linked from the user-message
   * table.
   *
   * @param string $username Username to search for
   * @return array Array with all records
   */
  public function getAllByUser(string $username): array {
    $records = array();
    $query = $this->db->prepare(
      'SELECT * FROM ' . $this->table . ' as a JOIN ' . $this->umtable . ' as um ON um.msgid = a.id WHERE um.username = :username ORDER BY timestamp DESC'
    );
    $query->bindParam(':username', $username);
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
    }
    return $records;
  }
}
