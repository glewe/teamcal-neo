<?php
require_once 'PDODb.php';

/**
 * Daynotes
 *
 * This class provides methods and properties for daynotes.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Daynotes {
  public $id = null;
  public $yyyymmdd = '';
  public $daynote = '';
  public $daynotes = array();
  public $username = '';
  public $region = '';
  public $color = '';
  public $confidential = '';

  private $db = '';
  private $table = '';
  private $archive_table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF;
    $this->db = new PDODb([
      'driver' => $CONF['db_driver'],
      'host' => $CONF['db_server'],
      'port' => $CONF['db_port'],
      'dbname' => $CONF['db_name'],
      'username' => $CONF['db_user'],
      'password' => $CONF['db_password'],
      'charset' => $CONF['db_charset'],
    ]);
    $this->table = $CONF['db_table_daynotes'];
    $this->archive_table = $CONF['db_table_archive_daynotes'];
  }

  //---------------------------------------------------------------------------
  /**
   * Archives all records for a given user
   *
   * @param string $username Username to archive
   * @return boolean Query result
   */
  public function archive($username) {
    $query = 'INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE username = ' . $username;
    return $this->db->rawQuery($query)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Restores all records for a given user
   *
   * @param string $name Username to restore
   * @return boolean Query result
   */
  public function restore($username) {
    $query = 'INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = ' . $username;
    return $this->db->rawQuery($query)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether records for a given user exist
   *
   * @param string $username Username to find
   * @param boolean $archive Whether to use the archive table
   * @return boolean True if found, false if not
   */
  public function exists($username, $archive = false) {
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }
    $this->db->select($table)->where('username', '=', $username)->run();
    return $this->db->rowCount();
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a daynote record from class variables
   *
   * @return boolean Query result or false
   */
  public function create() {
    //
    // Make sure no daynote exists for this day
    //
    $this->delete($this->yyyymmdd, $this->username, $this->region);
    //
    // Create the new one
    //
    $data = [
      'id' => null,
      'yyyymmdd' => $this->yyyymmdd,
      'username' => $this->username,
      'region' => $this->region,
      'daynote' => $this->daynote,
      'color' => $this->color,
      'confidential' => $this->confidential
    ];
    return $this->db->insert($this->table, $data)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a daynote record for a given date/username/region
   *
   * @param string $yyyymmdd 8 character date (YYYYMMDD) to find for deletion
   * @param string $username Userame to find for deletion
   * @param string $region Region to find for deletion
   * @return boolean Query result
   */
  public function delete($yyyymmdd = '', $username = '', $region = 'default') {
    return $this->db->delete($this->table)
      ->where('yyyymmdd', '=', $yyyymmdd)
      ->where('username', '=', $username)
      ->where('region', '=', $region)
      ->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @param boolean $archive Whether to use the archive table
   * @return boolean Query result or false
   */
  public function deleteAll($archive = false) {
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }
    return $this->db->delete($table)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all daynotes before (and including) a given day
   *
   * @param string $yyyymmdd 8 character date (YYYYMMDD) to find for deletion
   * @return boolean Query result
   */
  public function deleteAllBefore($yyyymmdd = '') {
    return $this->db->delete($this->table)
      ->where('yyyymmdd', '<=', $yyyymmdd)
      ->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all daynotes for a region
   *
   * @param string $region Region to find for deletion
   * @return boolean Query result
   */
  public function deleteAllForRegion($region = 'default') {
    return $this->db->delete($this->table)
      ->where('region', '=', $region)
      ->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all daynotes for a user
   *
   * @param string $uname Username to find for deletion
   * @return boolean Query result
   */
  public function deleteByUser($username = '', $archive = false) {
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }
    return $this->db->delete($table)
      ->where('username', '=', $username)
      ->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Delete an announcement by timestamp
   *
   * @return boolean Query result or false
   */
  public function deleteAllGlobal() {
    return $this->db->delete($this->table)
      ->where('username', '=', 'all')
      ->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all daynotes for a date and user
   *
   * @param string $date Date to find for deletion
   * @param string $username Username to find for deletion
   * @return boolean Query result
   */
  public function deleteByDateAndUser($date, $username) {
    return $this->db->delete($this->table)
      ->where('yyyymmdd', '=', $date)
      ->where('username', '=', $username)
      ->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a daynote record by id
   *
   * @param string $id ID to find for deletion
   * @return boolean Query result
   */
  public function deleteById($id) {
    return $this->db->delete($this->table)->where('id', '=', $id)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a daynote record for a given date/username/region
   *
   * @param string $yyyymmdd Date (YYYYMMDD) to find
   * @param string $username Userame to find
   * @param string $region Userame to find
   * @return boolean Query result
   */
  public function get($yyyymmdd = '', $username = '', $region = 'default', $replaceCRLF = false) {
    $row = $this->db->select($this->table)
      ->where('yyyymmdd', '=', $yyyymmdd)
      ->where('username', '=', $username)
      ->where('region', '=', $region)
      ->first()
      ->run();
    if ($row) {
      $this->id = $row['id'];
      $this->yyyymmdd = $row['yyyymmdd'];
      $this->username = $row['username'];
      $this->region = $row['region'];
      if ($replaceCRLF) {
        $this->daynote = str_replace("\r\n", "<br>", $row['daynote']);
      } else {
        $this->daynote = $row['daynote'];
      }
      $this->color = $row['color'];
      $this->confidential = $row['confidential'];
      return true;
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all daynotes for a given user and month and load them in daynotes array
   *
   * @param string $yyyy Year to find
   * @param string $mm Month to find
   * @param string $username Username to find
   * @param string $region Region to find
   * @param boolean $replaceCLRF Flag to replace CRLF with <br>
   * @return boolean Query result
   */
  public function getForMonthUser($yyyy, $mm, $username, $region = 'default', $replaceCRLF = false) {
    $number = cal_days_in_month(CAL_GREGORIAN, intval($mm), intval($yyyy));
    $days = sprintf('%02d', $number);
    $startdate = $yyyy . $mm . '01';
    $enddate = $yyyy . $mm . $days;

    $query = 'SELECT * FROM ' . $this->table . ' WHERE (yyyymmdd BETWEEN ' . $startdate . ' AND ' . $enddate . ') AND username = ' . $username . ' AND region = ' . $region;
    $row = $this->db->rawQuery($query)->run();
    while ($row) {
      if ($replaceCRLF) {
        $this->daynotes[$row['username']][$row['yyyymmdd']] = str_replace("\r\n", "<br>", $row['daynote']);
      } else {
        $this->daynotes[$row['username']][$row['yyyymmdd']] = $row['daynote'];
      }
    }
    return $row;
  }

  //---------------------------------------------------------------------------
  /**
   * Find all daynotes for all users for a given month/region
   *
   * @param string $yyyy Year to find
   * @param string $mm Month to find
   * @param string $usernames Array of usernames to find
   * @param string $region Region to find
   * @param boolean $replaceCLRF Flag to replace CRLF with <br>
   * @return boolean Query result
   */
  public function getforMonth($yyyy, $mm, $usernames, $region = 'default', $replaceCRLF = false) {
    $number = cal_days_in_month(CAL_GREGORIAN, intval($mm), intval($yyyy));
    $days = sprintf(' % 02d', $number);
    $startdate = $yyyy . $mm . '01';
    $enddate = $yyyy . $mm . $days;
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE yyyymmdd BETWEEN :val1 and :val2 and username IN(:val3) and region = :val4');
    $query->bindParam('val1', $startdate);
    $query->bindParam('val2', $enddate);
    $query->bindParam('val3', $usernames);
    $query->bindParam('val4', $region);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        if ($replaceCRLF) {
          $this->daynotes[$row['username']][$row['yyyymmdd']] = str_replace("\r\n", "<br>", $row['daynote']);
        }
        else {
          $this->daynotes[$row['username']][$row['yyyymmdd']] = $row['daynote'];
        }
      }
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all daynotes with no region set
   *
   * @return array | boolean Query result
   */
  public function getAllRegionless() {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE `region` = NULL or `region` = 0;');
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = $row;
      }
      return $records;
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Finds a daynote record by id and loads values in local class variables
   *
   * @param string $id ID to find
   * @param boolean $replaceCLRF Flag to replace CRLF with <br>
   * @return boolean Query result
   */
  public function getById($id, $replaceCRLF = false) {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
    $query->bindParam('val1', $id);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      $this->id = $row['id'];
      $this->yyyymmdd = $row['yyyymmdd'];
      $this->username = $row['username'];
      $this->region = $row['region'];
      if ($replaceCRLF) {
        $this->daynote = str_replace("\r\n", "<br>", $row['daynote']);
      }
      else {
        $this->daynote = $row['daynote'];
      }
      $this->color = $row['color'];
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a daynote is confidential
   *
   * @param string $id Record ID
   * @return boolean
   */
  public function isConfidential($id = '') {
    if (isset($id)) {
      $query = $this->db->prepare('SELECT confidential FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        return $row['confidential'];
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Sets the region for a daynote record
   *
   * @return boolean Query result
   */
  public function setRegion($daynoteId, $regionId) {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET `region` = :val1 WHERE id = :val2;');
    $query->bindParam('val1', $regionId);
    $query->bindParam('val2', $daynoteId);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a daynote record from local class variables
   *
   * @return boolean Query result
   */
  public function update() {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET yyyymmdd = :val1, daynote = :val2, username = :val3, region = :val4, color = :val5, confidential = :val6 WHERE id = :val7');
    $query->bindParam('val1', $this->yyyymmdd);
    $query->bindParam('val2', $this->daynote);
    $query->bindParam('val3', $this->username);
    $query->bindParam('val4', $this->region);
    $query->bindParam('val5', $this->color);
    $query->bindParam('val6', $this->confidential);
    $query->bindParam('val7', $this->id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Optimize table
   *
   * @return boolean Query result
   */
  public function optimize() {
    $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->table);
    $query->execute();
    $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->archive_table);
    return $query->execute();
  }
}
