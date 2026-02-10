<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * MessageModel
 *
 * This class provides methods and properties for system messages.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class MessageModel
{
  public ?int   $id        = null;
  public string $timestamp = '';
  public string $username  = '';
  public string $text      = '';
  public string $popup     = '0';
  public string $type      = 'info';

  private ?PDO   $db      = null;
  private string $table   = '';
  private string $umtable = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null             $db   Database connection object
   * @param array<string, string>|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db && $conf) {
      $this->db      = $db;
      $this->table   = $conf['db_table_messages'];
      $this->umtable = $conf['db_table_user_message'];
    }
    else {
      global $CONF, $DB;
      $this->db      = $DB->db;
      $this->table   = $CONF['db_table_messages'];
      $this->umtable = $CONF['db_table_user_message'];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a message.
   *
   * @param string $timestamp Timestamp
   * @param string $text      Text of the message
   * @param string $type      Type of the message
   *
   * @return int|bool Last inserted record ID or false on failure
   */
  public function create(string $timestamp, string $text, string $type = 'info'): int|bool {
    $query = $this->db->prepare("INSERT INTO {$this->table} (timestamp, text, type) VALUES (:timestamp, :text, :type)");
    $query->bindParam(':timestamp', $timestamp);
    $query->bindParam(':text', $text);
    $query->bindParam(':type', $type);
    $result = $query->execute();
    if ($result) {
      return (int) $this->db->lastInsertId();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a message.
   *
   * @param string $id Record ID to delete
   *
   * @return bool Query result
   */
  public function delete(string $id): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
    $query->bindParam(':id', $id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records.
   *
   * @return bool Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare("TRUNCATE TABLE {$this->table}");
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all records into an array.
   *
   * @return array<int, array<string, mixed>> Array with all records
   */
  public function getAll(): array {
    $records = [];
    $query   = $this->db->prepare("SELECT * FROM {$this->table}");
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all records for a given username linked from the user-message table.
   *
   * @param string $username Username to search for
   *
   * @return array<int, array<string, mixed>> Array with all records
   */
  public function getAllByUser(string $username): array {
    $records = [];
    $query   = $this->db->prepare(
      "SELECT a.*, um.id as umid, um.popup as um_popup FROM {$this->table} as a JOIN {$this->umtable} as um ON um.msgid = a.id WHERE um.username = :username ORDER BY timestamp DESC"
    );
    $query->bindParam(':username', $username);
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
    }
    return $records;
  }
}
