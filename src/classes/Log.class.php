<?php

/**
 * Log
 *
 * This class provides methods and properties for application log messages.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Log {
  public $id = null;
  public $type = null;
  public $timestamp = '';
  public $user = '';
  public $event = '';
  private $db = null;
  private $table = '';
  private $C = null;

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $C, $CONF, $DB;
    $this->C = $C;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_log'];
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes log records by date range
   *
   * @param string $from ISO formatted start date
   * @param string $to ISO formatted end date
   * @return boolean Query result
   */
  public function delete(string $from = '', string $to = ''): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE (timestamp >= :from AND timestamp <= :to)');
    $query->bindParam(':from', $from);
    $query->bindParam(':to', $to);
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
   * Reads records by date range
   *
   * @param string $sort Sort order (ASC or DESC)
   * @param string $from ISO formatted start date
   * @param string $to ISO formatted end date
   * @param string $logtype Type to search for
   * @param string $logsearchuser User to search for
   * @param string $logsearchevent Event to search for
   * @return array Array of records
   */
  public function read(string $sort = 'DESC', string $from = '', string $to = '', string $logtype = '%', string $logsearchuser = '%', string $logsearchevent = '%'): array {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE (timestamp >= :from AND timestamp <= :to AND type LIKE :type AND user LIKE :user AND event LIKE :event) ORDER BY timestamp ' . $sort);
    $query->bindParam(':from', $from);
    $query->bindParam(':to', $to);
    $query->bindParam(':type', $logtype);
    $query->bindParam(':user', $logsearchuser);
    $query->bindParam(':event', $logsearchevent);
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a log record
   *
   * @param string $type Log type
   * @param string $user Username
   * @param string $event Event
   * @return boolean Query result
   */
  public function logEvent(string $type, string $user, string $event, string $object = ''): bool {
    global $LANG;
    $loglang = $this->C->read("logLanguage");
    if (!strlen($loglang)) {
      $loglang = 'english';
    }
    require_once WEBSITE_ROOT . "/languages/" . $loglang . ".log.php";
    $myEvent = $LANG[$event] . $object;
    if ($this->C->read($type)) {
      $ts = date("YmdHis");
      $ip = getClientIp();
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (type, timestamp, ip, user, event) VALUES (:type, :timestamp, :ip, :user, :event)');
      $query->bindParam(':type', $type);
      $query->bindParam(':timestamp', $ts);
      $query->bindParam(':ip', $ip);
      $query->bindParam(':user', $user);
      $query->bindParam(':event', $myEvent);
      return $query->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Generates test log records with random data
   *
   * @param int $count Number of test records to create
   * @return boolean Query result
   */
  public function generateTestLogs(int $count): bool {
    $logTypes = ['logLogin', 'logUser', 'logCalendar', 'logConfig', 'logDatabase', 'logGroup', 'logImport', 'logLog', 'logMessage', 'logMonth', 'logMessage', 'logPermission', 'logRegion', 'logRegistration', 'logRole', 'logUpload'];
    $users = ['admin', 'ccarl', 'dduck', 'einstein', 'sgonzales', 'phead', 'blightyear', 'mmouse', 'sman', 'mimouse'];
    $messages = [
      'User logged in',
      'User logged out',
      'Calendar updated',
      'Configuration changed',
      'Database operation',
      'Group modified',
      'Import completed',
      'Log accessed',
      'Message sent',
      'Month view accessed',
      'News posted',
      'Permission changed',
      'Region updated',
      'User registered',
      'Role assigned',
      'File uploaded'
    ];

    for ($i = 0; $i < $count; $i++) {
      $type = $logTypes[array_rand($logTypes)];
      $timestamp = date("YmdHis", strtotime('-' . rand(0, 365) . ' days'));
      $ip = rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
      $user = $users[array_rand($users)];
      $event = $messages[array_rand($messages)];

      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (type, timestamp, ip, user, event) VALUES (:type, :timestamp, :ip, :user, :event)');
      $query->bindParam(':type', $type);
      $query->bindParam(':timestamp', $timestamp);
      $query->bindParam(':ip', $ip);
      $query->bindParam(':user', $user);
      $query->bindParam(':event', $event);
      if (!$query->execute()) {
        return false;
      }
    }
    return true;
  }
}
