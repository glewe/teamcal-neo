<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * DaynoteModel
 *
 * This class provides methods and properties for daynotes.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class DaynoteModel
{
  public ?int   $id           = null;
  public string $yyyymmdd     = '';
  public string $daynote      = '';
  /** @var array<string, array<string, string>> */
  public array  $daynotes     = [];
  public string $username     = '';
  public string $region       = '';
  public string $color        = '';
  public string $confidential = '';

  private ?PDO   $db            = null;
  private string $table         = '';
  private string $archive_table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null             $db   Database connection object
   * @param array<string, string>|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db && $conf) {
      $this->db            = $db;
      $this->table         = $conf['db_table_daynotes'];
      $this->archive_table = $conf['db_table_archive_daynotes'];
    }
    else {
      global $CONF, $DB;
      $this->db            = $DB->db;
      $this->table         = $CONF['db_table_daynotes'];
      $this->archive_table = $CONF['db_table_archive_daynotes'];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Archives all records for a given user.
   *
   * @param string $username Username to archive
   *
   * @return bool Query result
   */
  public function archive(string $username): bool {
    $query = $this->db->prepare("INSERT INTO {$this->archive_table} SELECT t.* FROM {$this->table} t WHERE username = :username");
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a daynote record from class variables.
   *
   * @return bool Query result or false
   */
  public function create(): bool {
    //
    // Make sure no daynote exists for this day
    //
    $this->delete($this->yyyymmdd, $this->username, $this->region);
    $query = $this->db->prepare("INSERT INTO {$this->table} (yyyymmdd, username, region, daynote, color, confidential) VALUES (:yyyymmdd, :username, :region, :daynote, :color, :confidential)");
    $query->bindParam(':yyyymmdd', $this->yyyymmdd);
    $query->bindParam(':username', $this->username);
    $query->bindParam(':region', $this->region);
    $query->bindParam(':daynote', $this->daynote);
    $query->bindParam(':color', $this->color);
    $query->bindParam(':confidential', $this->confidential);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a daynote record for a given date/username/region.
   *
   * @param string $yyyymmdd 8 character date (YYYYMMDD) to find for deletion
   * @param string $username Username to find for deletion
   * @param string $region   Region to find for deletion
   *
   * @return bool Query result
   */
  public function delete(string $yyyymmdd = '', string $username = '', string $region = 'default'): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE yyyymmdd = :yyyymmdd AND username = :username AND region = :region");
    $query->bindParam(':yyyymmdd', $yyyymmdd);
    $query->bindParam(':username', $username);
    $query->bindParam(':region', $region);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records.
   *
   * @param bool $archive Whether to use the archive table
   *
   * @return bool Query result or false
   */
  public function deleteAll(bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$table}");
    $query->execute();
    if ($query->fetchColumn()) {
      $query = $this->db->prepare("TRUNCATE TABLE {$table}");
      return $query->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all daynotes before (and including) a given day.
   *
   * @param string $yyyymmdd 8 character date (YYYYMMDD) to find for deletion
   *
   * @return bool Query result
   */
  public function deleteAllBefore(string $yyyymmdd = ''): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE yyyymmdd <= :yyyymmdd");
    $query->bindParam(':yyyymmdd', $yyyymmdd);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all daynotes for a region.
   *
   * @param string $region Region to find for deletion
   *
   * @return bool Query result
   */
  public function deleteAllForRegion(string $region = 'default'): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE region = :region");
    $query->bindParam(':region', $region);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all daynotes for a user.
   *
   * @param string $username Username to find for deletion
   * @param bool   $archive  Whether to use the archive table
   *
   * @return bool Query result
   */
  public function deleteByUser(string $username = '', bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare("DELETE FROM {$table} WHERE username = :username");
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Delete an announcement by timestamp.
   *
   * @return bool Query result or false
   */
  public function deleteAllGlobal(): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE username = 'all'");
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all daynotes for a date and user.
   *
   * @param string $date     Date to find for deletion
   * @param string $username Username to find for deletion
   *
   * @return bool Query result
   */
  public function deleteByDateAndUser(string $date, string $username): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE yyyymmdd = :yyyymmdd AND username = :username");
    $query->bindParam(':yyyymmdd', $date);
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a daynote record by id.
   *
   * @param string $id ID to find for deletion
   *
   * @return bool Query result
   */
  public function deleteById(string $id): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
    $query->bindParam(':id', $id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether records for a given user exist.
   *
   * @param string $username Username to find
   * @param bool   $archive  Whether to use the archive table
   *
   * @return bool True if found, false if not
   */
  public function exists(string $username, bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$table} WHERE username = :username");
    $query->bindParam(':username', $username);
    $query->execute();
    return (bool) $query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a daynote record for a given date/username/region.
   *
   * @param string $yyyymmdd    Date (YYYYMMDD) to find
   * @param string $username    Username to find
   * @param string $region      Region to find
   * @param bool   $replaceCRLF Flag to replace CRLF with <br>
   *
   * @return bool Query result
   */
  public function get(string $yyyymmdd = '', string $username = '', string $region = 'default', bool $replaceCRLF = false): bool {
    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE yyyymmdd = :val1 AND username = :val2 AND region = :val3");
    $query->bindParam('val1', $yyyymmdd);
    $query->bindParam('val2', $username);
    $query->bindParam('val3', $region);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      $this->id           = (int) $row['id'];
      $this->yyyymmdd     = (string) $row['yyyymmdd'];
      $this->username     = (string) $row['username'];
      $this->region       = (string) $row['region'];
      $this->daynote      = $replaceCRLF ? str_replace("\r\n", "<br>", (string) $row['daynote']) : (string) $row['daynote'];
      $this->color        = (string) $row['color'];
      $this->confidential = (string) $row['confidential'];
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all daynotes for a given user and month and load them in daynotes array.
   *
   * @param string $yyyy        Year to find
   * @param string $mm          Month to find
   * @param string $username    Username to find
   * @param string $region      Region to find
   * @param bool   $replaceCRLF Flag to replace CRLF with <br>
   *
   * @return bool Query result
   */
  public function getForMonthUser(string $yyyy, string $mm, string $username, string $region = 'default', bool $replaceCRLF = false): bool {
    $number    = cal_days_in_month(CAL_GREGORIAN, intval($mm), intval($yyyy));
    $days      = sprintf('%02d', $number);
    $startdate = $yyyy . $mm . '01';
    $enddate   = $yyyy . $mm . $days;
    $query     = $this->db->prepare("SELECT * FROM {$this->table} WHERE yyyymmdd BETWEEN :val1 AND :val2 AND username = :val3 AND region = :val4");
    $query->bindParam('val1', $startdate);
    $query->bindParam('val2', $enddate);
    $query->bindParam('val3', $username);
    $query->bindParam('val4', $region);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $this->daynotes[$row['username']][$row['yyyymmdd']] = $replaceCRLF ? str_replace("\r\n", "<br>", (string) $row['daynote']) : (string) $row['daynote'];
      }
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Find all daynotes for all users for a given month/region.
   *
   * @param string $yyyy        Year to find
   * @param string $mm          Month to find
   * @param string $usernames   Comma-separated or similar string of usernames to find (depending on usage)
   * @param string $region      Region to find
   * @param bool   $replaceCRLF Flag to replace CRLF with <br>
   *
   * @return bool Query result
   */
  public function getforMonth(string $yyyy, string $mm, string $usernames, string $region = 'default', bool $replaceCRLF = false): bool {
    $number    = cal_days_in_month(CAL_GREGORIAN, intval($mm), intval($yyyy));
    $days      = sprintf('%02d', $number);
    $startdate = $yyyy . $mm . '01';
    $enddate   = $yyyy . $mm . $days;
    // NOTE: username IN(:val3) with a string $usernames assumes the caller has prepared the string correctly for SQL or it's a single value.
    // Legacy behavior retained.
    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE yyyymmdd BETWEEN :val1 AND :val2 AND username IN(:val3) AND region = :val4");
    $query->bindParam('val1', $startdate);
    $query->bindParam('val2', $enddate);
    $query->bindParam('val3', $usernames);
    $query->bindParam('val4', $region);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $this->daynotes[$row['username']][$row['yyyymmdd']] = $replaceCRLF ? str_replace("\r\n", "<br>", (string) $row['daynote']) : (string) $row['daynote'];
      }
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all daynotes with no region set.
   *
   * @return array<int, array<string, mixed>>|bool Query result or false on failure
   */
  public function getAllRegionless(): array|bool {
    $records = [];
    $query   = $this->db->prepare("SELECT * FROM {$this->table} WHERE `region` IS NULL OR `region` = '0' OR `region` = '';");
    $result  = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = $row;
      }
      return $records;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Finds a daynote record by id and loads values in local class variables.
   *
   * @param string $id          ID to find
   * @param bool   $replaceCRLF Flag to replace CRLF with <br>
   *
   * @return bool Query result
   */
  public function getById(string $id, bool $replaceCRLF = false): bool {
    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :val1");
    $query->bindParam('val1', $id);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      $this->id           = (int) $row['id'];
      $this->yyyymmdd     = (string) $row['yyyymmdd'];
      $this->username     = (string) $row['username'];
      $this->region       = (string) $row['region'];
      $this->daynote      = $replaceCRLF ? str_replace("\r\n", "<br>", (string) $row['daynote']) : (string) $row['daynote'];
      $this->color        = (string) $row['color'];
      $this->confidential = (string) $row['confidential'];
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a daynote is confidential.
   *
   * @param string $id Record ID
   *
   * @return bool|string Confidential status or false if not found
   */
  public function isConfidential(string $id = ''): bool|string {
    if ($id !== '') {
      $query = $this->db->prepare("SELECT confidential FROM {$this->table} WHERE id = :id");
      $query->bindParam(':id', $id);
      $query->execute();
      $result = $query->fetchColumn();
      if ($result !== false) {
        return (string) $result;
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Restores all records for a given user.
   *
   * @param string $username Username to restore
   *
   * @return bool Query result
   */
  public function restore(string $username): bool {
    $query = $this->db->prepare("INSERT INTO {$this->table} SELECT a.* FROM {$this->archive_table} a WHERE username = :username");
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Sets the region for a daynote record.
   *
   * @param string $daynoteId Record ID
   * @param string $regionId  Region ID
   *
   * @return bool Query result
   */
  public function setRegion(string $daynoteId, string $regionId): bool {
    $query = $this->db->prepare("UPDATE {$this->table} SET `region` = :region WHERE id = :id;");
    $query->bindParam(':region', $regionId);
    $query->bindParam(':id', $daynoteId);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a daynote record from local class variables.
   *
   * @return bool Query result
   */
  public function update(): bool {
    $query = $this->db->prepare("UPDATE {$this->table} SET yyyymmdd = :yyyymmdd, daynote = :daynote, username = :username, region = :region, color = :color, confidential = :confidential WHERE id = :id");
    $query->bindParam(':yyyymmdd', $this->yyyymmdd);
    $query->bindParam(':daynote', $this->daynote);
    $query->bindParam(':username', $this->username);
    $query->bindParam(':region', $this->region);
    $query->bindParam(':color', $this->color);
    $query->bindParam(':confidential', $this->confidential);
    $query->bindParam(':id', $this->id);
    return $query->execute();
  }
}
