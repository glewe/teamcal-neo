<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Models\ConfigModel;

/**
 * LogModel
 *
 * This class provides methods and properties for application log messages.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class LogModel
{
  public ?int    $id        = null;
  public ?string $type      = null;
  public string  $timestamp = '';
  public string  $user      = '';
  public string  $event     = '';

  private ?PDO         $db    = null;
  private string       $table = '';
  private ?ConfigModel $C     = null;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null         $db
   * @param array|null       $conf
   * @param ConfigModel|null $configObj
   */
  public function __construct(?PDO $db = null, ?array $conf = null, ?ConfigModel $configObj = null) {
    global $C, $CONF, $DB;

    $this->C     = $configObj ?? $C;
    $this->db    = $db ?? $DB->db;
    $this->table = $conf['db_table_log'] ?? $CONF['db_table_log'];
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes log records by date range.
   *
   * @param string $from ISO formatted start date
   * @param string $to   ISO formatted end date
   *
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
   * Deletes all records.
   *
   * @return boolean Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Reads records by date range.
   *
   * @param string $sort           Sort order (ASC or DESC)
   * @param string $from           ISO formatted start date
   * @param string $to             ISO formatted end date
   * @param string $logtype        Type to search for
   * @param string $logsearchuser  User to search for
   * @param string $logsearchevent Event to search for
   *
   * @return array Array of records
   */
  public function read(string $sort = 'DESC', string $from = '', string $to = '', string $logtype = '%', string $logsearchuser = '%', string $logsearchevent = '%'): array {
    $records = array();
    $query   = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE (timestamp >= :from AND timestamp <= :to AND type LIKE :type AND user LIKE :user AND event LIKE :event) ORDER BY timestamp ' . $sort);
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
   * Creates a log record.
   *
   * @param string $type   Log type
   * @param string $user   Username
   * @param string $event  Event
   * @param string $object Additional object info
   *
   * @return boolean Query result
   */
  public function logEvent(string $type, string $user, string $event, string $object = ''): bool {
    global $LANG;
    $loglang = $this->C->read("logLanguage");
    if (empty($loglang)) {
      $loglang = 'english';
    }
    require_once WEBSITE_ROOT . '/src/Helpers/language.helper.php';
    \LanguageLoader::loadForController('log');
    $myEvent = (isset($LANG[$event]) ? $LANG[$event] : $event) . $object;
    if ($this->C->read($type)) {
      $ts    = date("YmdHis");
      $ip    = $this->getClientIp();
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
   * Generates test log records with random data.
   *
   * @param int $count Number of test records to create
   *
   * @return boolean Query result
   */
  public function generateTestLogs(int $count): bool {
    $logTypes = ['logLogin', 'logUser', 'logCalendar', 'logConfig', 'logDatabase', 'logGroup', 'logImport', 'logLog', 'logMessage', 'logMonth', 'logMessage', 'logPermission', 'logRegion', 'logRegistration', 'logRole', 'logUpload'];
    $users    = ['admin', 'ccarl', 'dduck', 'einstein', 'sgonzales', 'phead', 'blightyear', 'mmouse', 'sman', 'mimouse'];
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
      $type      = $logTypes[array_rand($logTypes)];
      // NOSONAR - rand() is safe here: only used for generating non-sensitive test data
      $timestamp = date("YmdHis", strtotime('-' . rand(0, 365) . ' days'));
      // NOSONAR - rand() is safe here: only used for generating non-sensitive test data
      $ip        = rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
      $user      = $users[array_rand($users)];
      $event     = $messages[array_rand($messages)];

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

  //---------------------------------------------------------------------------
  /**
   * Determines the current client IP address.
   *
   * @return string
   */
  private function getClientIp(): string {
    if (getenv('HTTP_CLIENT_IP')) {
      $ipaddress = getenv('HTTP_CLIENT_IP');
    }
    elseif (getenv('HTTP_X_FORWARDED_FOR')) {
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_X_FORWARDED')) {
      $ipaddress = getenv('HTTP_X_FORWARDED');
    }
    elseif (getenv('HTTP_FORWARDED_FOR')) {
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_FORWARDED')) {
      $ipaddress = getenv('HTTP_FORWARDED');
    }
    elseif (getenv('REMOTE_ADDR')) {
      $ipaddress = getenv('REMOTE_ADDR');
    }
    else {
      $ipaddress = 'UNKNOWN';
    }
    return $ipaddress;
  }
}
