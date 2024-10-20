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
  private $db = '';
  private $table = '';
  private $C = '';

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
  public function delete($from = '', $to = '') {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE (timestamp >= :val1 AND timestamp <= :val2)');
    $query->bindParam('val1', $from);
    $query->bindParam('val2', $to);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @return boolean Query result
   */
  public function deleteAll() {
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
  public function read($sort = 'DESC', $from = '', $to = '', $logtype = '%', $logsearchuser = '%', $logsearchevent = '%') {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE (timestamp >= :val1 AND timestamp <= :val2 AND type LIKE :val3 AND user LIKE :val4 AND event LIKE :val5) ORDER BY timestamp ' . $sort);
    $query->bindParam('val1', $from);
    $query->bindParam('val2', $to);
    $query->bindParam('val3', $logtype);
    $query->bindParam('val4', $logsearchuser);
    $query->bindParam('val5', $logsearchevent);
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
   * Creates a log record
   *
   * @param string $type Log type
   * @param string $user Username
   * @param string $event Event
   * @return boolean Query result
   */
  public function logEvent($type, $user, $event, $object = '') {
    global $LANG;
    if (!strlen($this->C->read("logLanguage"))) {
      $loglang = 'english';
    } else {
      $loglang = $this->C->read("logLanguage");
    }
    require_once WEBSITE_ROOT . "/languages/" . $loglang . ".log.php";
    $myEvent = $LANG[$event] . $object;
    if ($this->C->read($type)) {
      $ts = date("YmdHis");
      $ip = getClientIp();
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (type, timestamp, ip, user, event) VALUES (:val1, :val2, :val3, :val4, :val5)');
      $query->bindParam('val1', $type);
      $query->bindParam('val2', $ts);
      $query->bindParam('val3', $ip);
      $query->bindParam('val4', $user);
      $query->bindParam('val5', $myEvent);
      return $query->execute();
    }
    return false;
  }
}
