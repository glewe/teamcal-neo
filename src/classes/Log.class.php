<?php
require_once 'PDODb.php';

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
  private $db = '';
  private $table = '';
  private $C = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $C, $CONF;
    $this->C = $C;
    $this->db = new PDODb([
      'driver' => $CONF['db_driver'],
      'host' => $CONF['db_server'],
      'port' => $CONF['db_port'],
      'dbname'=> $CONF['db_name'],
      'username' => $CONF['db_user'],
      'password' => $CONF['db_password'],
      'charset' => $CONF['db_charset'],
    ]);
    $this->table = $CONF['db_table_log'];
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes log records by date range
   *
   * @param string $from ISO formatted start date
   * @param string $to ISO formatted end date
   * @return void
   */
  public function delete($from = '', $to = '') {
    $this->db->delete($this->table)
      ->where('timestamp', '>=', $from)
      ->where('timestamp', '<=', $to )
      ->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @return void
   */
  public function deleteAll() {
    $this->db->delete($this->table)->run();
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
    return $this->db->select($this->table)
      ->where('timestamp', '>=', $from)
      ->where('timestamp', '<=', $to)
      ->where('type', 'LIKE', $logtype)
      ->where('user', 'LIKE', $logsearchuser)
      ->where('event', 'LIKE', $logsearchevent)
      ->orderBy('timestamp', $sort)
      ->run();
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
      $data = [
        'id' => '',
        'type' => $type,
        'timestamp' => $ts,
        'user' => $user,
        'event' => $myEvent,
      ];
      return $this->db->insert($this->table, $data)->run();
    }
    return false;
  }
}
