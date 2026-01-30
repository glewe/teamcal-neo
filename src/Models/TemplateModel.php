<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * TemplateModel
 *
 * This class provides methods and properties for absences templates.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class TemplateModel
{
  public string $username = '';
  public string $year     = '';
  public string $month    = '';
  public int    $abs1     = 0;
  public int    $abs2     = 0;
  public int    $abs3     = 0;
  public int    $abs4     = 0;
  public int    $abs5     = 0;
  public int    $abs6     = 0;
  public int    $abs7     = 0;
  public int    $abs8     = 0;
  public int    $abs9     = 0;
  public int    $abs10    = 0;
  public int    $abs11    = 0;
  public int    $abs12    = 0;
  public int    $abs13    = 0;
  public int    $abs14    = 0;
  public int    $abs15    = 0;
  public int    $abs16    = 0;
  public int    $abs17    = 0;
  public int    $abs18    = 0;
  public int    $abs19    = 0;
  public int    $abs20    = 0;
  public int    $abs21    = 0;
  public int    $abs22    = 0;
  public int    $abs23    = 0;
  public int    $abs24    = 0;
  public int    $abs25    = 0;
  public int    $abs26    = 0;
  public int    $abs27    = 0;
  public int    $abs28    = 0;
  public int    $abs29    = 0;
  public int    $abs30    = 0;
  public int    $abs31    = 0;

  private ?PDO   $db            = null;
  private string $table         = '';
  private string $abs_table     = '';
  private string $archive_table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null   $db   Database object
   * @param array|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db !== null && $conf !== null) {
      $this->db            = $db;
      $this->table         = $conf['db_table_templates'];
      $this->abs_table     = $conf['db_table_absences'];
      $this->archive_table = $conf['db_table_archive_templates'];
      $this->archive_table = $conf['db_table_archive_templates'];
    }
    else {
      global $CONF, $DB;
      $this->db            = $DB->db;
      $this->table         = $CONF['db_table_templates'];
      $this->abs_table     = $CONF['db_table_absences'];
      $this->archive_table = $CONF['db_table_archive_templates'];
      $this->archive_table = $CONF['db_table_archive_templates'];
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
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Counts a specific absences of a given username, year, month and day.
   *
   * @param string $username Username to find
   * @param string $year     Year to find (YYYY)
   * @param string $month    Month to find (MM)
   * @param int    $absid    Absence ID
   * @param int    $start    Start day
   * @param int    $end      End day
   *
   * @return int 0 or absence ID count
   */
  public function countAbsence(string $username = '%', string $year = '', string $month = '', string|int $absid = 0, int $start = 1, int $end = 0): int {
    $count  = 0;
    $mytime = $year . "-" . $month . "-" . $start;
    $myts   = strtotime($mytime);
    if (!$end || $end > 31) {
      $end = (int) date("t", $myts);
    }

    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE username LIKE :username AND year = :year AND month = :month");
    $month = sprintf("%02d", (int) $month);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':year', $year, PDO::PARAM_STR);
    $query->bindParam(':month', $month, PDO::PARAM_STR);
    $result = $query->execute();

    if ($result) {
      if ($username != "%") {
        if ($row = $query->fetch()) {
          for ($i = $start; $i <= $end; $i++) {
            if ((int) $row['abs' . $i] === (int) $absid) {
              $count++;
            }
          }
        }
      }
      else {
        while ($row = $query->fetch()) {
          for ($i = $start; $i <= $end; $i++) {
            if ((int) $row['abs' . $i] === (int) $absid) {
              $count++;
            }
          }
        }
      }
    }
    return $count;
  }

  //---------------------------------------------------------------------------
  /**
   * Counts any weekday absence of a given username, year, month and day.
   *
   * @param string $username Username to find
   * @param string $year     Year to find (YYYY)
   * @param string $month    Month to find (MM)
   * @param int    $start    Start day
   * @param int    $end      End day
   *
   * @return int 0 or absence ID count
   */
  public function countAllAbsences(string $username = '%', string $year = '', string $month = '', int $start = 1, int $end = 0): int {
    $count  = 0;
    $mytime = $year . "-" . $month . "-" . $start;
    $myts   = strtotime($mytime);

    if (!$end || $end > 31) {
      $end = (int) date("t", $myts);
    }

    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE username LIKE :username AND year = :year AND month = :month");
    $month = sprintf("%02d", (int) $month);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':year', $year, PDO::PARAM_STR);
    $query->bindParam(':month', $month, PDO::PARAM_STR);
    $result = $query->execute();

    if ($result) {
      if ($username != "%") {
        if ($row = $query->fetch()) {
          for ($i = $start; $i <= $end; $i++) {
            if ((int) $row['abs' . $i] !== 0) {
              $thistime = $year . "-" . $month . "-" . $i;
              $thists   = strtotime($thistime);
              $weekday  = (int) date("w", $thists);
              // Only check weekdays (0 = Sunday, 6 = Saturday)
              if ($weekday > 0 && $weekday < 6) {
                $countsAsPresent = 0;
                $query2          = $this->db->prepare('SELECT counts_as_present FROM ' . $this->abs_table . ' WHERE id = :absid');
                $query2->bindParam(':absid', $row['abs' . $i], PDO::PARAM_INT);
                $result2 = $query2->execute();
                if ($result2 && $row2 = $query2->fetch()) {
                  $countsAsPresent = (int) $row2['counts_as_present'];
                }
                if (!$countsAsPresent) {
                  $count++;
                }
              }
            }
          }
        }
      }
      else {
        while ($row = $query->fetch()) {
          for ($i = $start; $i <= $end; $i++) {
            if ((int) $row['abs' . $i] !== 0) {
              $thistime = $year . "-" . $month . "-" . $i;
              $thists   = strtotime($thistime);
              $weekday  = (int) date("w", $thists);
              // Only check weekdays (0 = Sunday, 6 = Saturday)
              if ($weekday > 0 && $weekday < 6) {
                $countsAsPresent = 0;
                $query2          = $this->db->prepare('SELECT counts_as_present FROM ' . $this->abs_table . ' WHERE id = :absid');
                $query2->bindParam(':absid', $row['abs' . $i], PDO::PARAM_INT);
                $result2 = $query2->execute();
                if ($result2 && $row2 = $query2->fetch()) {
                  $countsAsPresent = (int) $row2['counts_as_present'];
                }
                if (!$countsAsPresent) {
                  $count++;
                }
              }
            }
          }
        }
      }
    }
    return $count;
  }

  //---------------------------------------------------------------------------
  /**
   * Counts any weekend absence of a given username, year, month and day.
   *
   * @param string $username Username to find
   * @param string $year     Year to find (YYYY)
   * @param string $month    Month to find (MM)
   * @param int    $start    Start day
   * @param int    $end      End day
   *
   * @return int 0 or absence ID count
   */
  public function countAllAbsencesWe(string $username = '%', string $year = '', string $month = '', int $start = 1, int $end = 0): int {
    $count  = 0;
    $mytime = $year . "-" . $month . "-" . $start;
    $myts   = strtotime($mytime);

    if (!$end || $end > 31) {
      $end = (int) date("t", $myts);
    }

    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE username LIKE :username AND year = :year AND month = :month");
    $month = sprintf("%02d", (int) $month);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':year', $year, PDO::PARAM_STR);
    $query->bindParam(':month', $month, PDO::PARAM_STR);
    $result = $query->execute();

    if ($result) {
      if ($username != "%") {
        if ($row = $query->fetch()) {
          for ($i = $start; $i <= $end; $i++) {
            if ((int) $row['abs' . $i] !== 0) {
              $thistime = $year . "-" . $month . "-" . $i;
              $thists   = strtotime($thistime);
              $weekday  = (int) date("w", $thists);
              // Only check weekends (0 = Sunday, 6 = Saturday)
              if ($weekday === 0 || $weekday === 6) {
                $countsAsPresent = 0;
                $query2          = $this->db->prepare('SELECT counts_as_present FROM ' . $this->abs_table . ' WHERE id = :absid');
                $query2->bindParam(':absid', $row['abs' . $i], PDO::PARAM_INT);
                $result2 = $query2->execute();
                if ($result2 && $row2 = $query2->fetch()) {
                  $countsAsPresent = (int) $row2['counts_as_present'];
                }
                if (!$countsAsPresent) {
                  $count++;
                }
              }
            }
          }
        }
      }
      else {
        while ($row = $query->fetch()) {
          for ($i = $start; $i <= $end; $i++) {
            if ((int) $row['abs' . $i] !== 0) {
              $thistime = $year . "-" . $month . "-" . $i;
              $thists   = strtotime($thistime);
              $weekday  = (int) date("w", $thists);
              // Only check weekends (0 = Sunday, 6 = Saturday)
              if ($weekday === 0 || $weekday === 6) {
                $countsAsPresent = 0;
                $query2          = $this->db->prepare('SELECT counts_as_present FROM ' . $this->abs_table . ' WHERE id = :absid');
                $query2->bindParam(':absid', $row['abs' . $i], PDO::PARAM_INT);
                $result2 = $query2->execute();
                if ($result2 && $row2 = $query2->fetch()) {
                  $countsAsPresent = (int) $row2['counts_as_present'];
                }
                if (!$countsAsPresent) {
                  $count++;
                }
              }
            }
          }
        }
      }
    }
    return $count;
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a template from local variables.
   *
   * @return bool Query result
   */
  public function create(): bool {
    $stmt  = "INSERT INTO {$this->table} (username, year, month, " .
      implode(", ", array_map(fn($i) => "abs{$i}", range(1, 31))) .
      ") VALUES (:username, :year, :month, " .
      implode(", ", array_map(fn($i) => ":abs{$i}", range(1, 31))) . ")";
    $query = $this->db->prepare($stmt);
    $query->bindParam(':username', $this->username, PDO::PARAM_STR);
    $query->bindParam(':year', $this->year, PDO::PARAM_STR);
    $query->bindParam(':month', $this->month, PDO::PARAM_STR);
    for ($i = 1; $i <= 31; $i++) {
      $prop = 'abs' . $i;
      $query->bindParam(':abs' . $i, $this->$prop, PDO::PARAM_INT);
    }
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records.
   *
   * @param bool $archive Whether to use archive table
   *
   * @return bool Query result
   */
  public function deleteAll(bool $archive = false): bool {
    $table  = $archive ? $this->archive_table : $this->table;
    $query  = $this->db->prepare("SELECT COUNT(*) FROM {$table}");
    $result = $query->execute();
    if ($result && $query->fetchColumn()) {
      $query = $this->db->prepare("TRUNCATE TABLE {$table}");
      return $query->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a template by username, year and month.
   *
   * @param string $username Username this template is for
   * @param string $year     Year of the template (YYYY)
   * @param string $month    Month of the template (MM)
   *
   * @return bool Query result
   */
  public function deleteTemplate(string $username = '', string $year = '', string $month = ''): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE username = :username AND year = :year AND month = :month");
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':year', $year, PDO::PARAM_STR);
    $month = sprintf("%02d", (int) $month);
    $query->bindParam(':month', $month, PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all templates before and including a certain year/month.
   *
   * @param string $year  Year (YYYY)
   * @param string $month Month (MM)
   *
   * @return bool Query result
   */
  public function deleteBefore(string $year, string $month): bool {
    $sql  = "DELETE FROM {$this->table} WHERE `year` = :year AND `month` = :month";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':year', $year, PDO::PARAM_STR);
    $stmt->bindParam(':month', $month, PDO::PARAM_STR);
    return $stmt->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a template by ID.
   *
   * @param string $id ID of record to delete
   *
   * @return bool Query result
   */
  public function deleteById(string $id = ''): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all templates for a username.
   *
   * @param string $username Username to delete all records of
   * @param bool   $archive  Whether to use archive table
   *
   * @return bool Query result
   */
  public function deleteByUser(string $username = '', bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare("DELETE FROM {$table} WHERE username = :username");
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a record exists.
   *
   * @param string $username Username to find
   * @param bool   $archive  Whether to use archive table
   *
   * @return bool True if found, false if not
   */
  public function exists(string $username = '', bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$table} WHERE username = :username");
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $result = $query->execute();
    return $result && (int) $query->fetchColumn() > 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the absence ID of a given username, year, month and day.
   *
   * @param string $username Username to find
   * @param string $year     Year to find (YYYY)
   * @param string $month    Month to find (MM)
   * @param string $day      Day of month to find (D)
   *
   * @return int|bool 0 or absence ID
   */
  public function getAbsence(string $username = '', string $year = '', string $month = '', string $day = '1'): int|bool {
    $query = $this->db->prepare("SELECT abs{$day} FROM {$this->table} WHERE username = :username AND year = :year AND month = :month");
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':year', $year, PDO::PARAM_STR);
    $month = sprintf("%02d", (int) $month);
    $query->bindParam(':month', $month, PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      return (int) $row['abs' . $day];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a template by username, year and month.
   *
   * @param string $username Username to find
   * @param string $year     Year to find (YYYY)
   * @param string $month    Month to find (MM)
   *
   * @return bool Query result
   */
  public function getTemplate(string $username = '', string $year = '', string $month = ''): bool {
    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE username LIKE :username AND year = :year AND month = :month");
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':year', $year, PDO::PARAM_STR);
    $month = sprintf("%02d", (int) $month);
    $query->bindParam(':month', $month, PDO::PARAM_STR);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      $this->username = (string) $row['username'];
      $this->year     = (string) $row['year'];
      $this->month    = (string) $row['month'];
      for ($i = 1; $i <= 31; $i++) {
        $prop        = 'abs' . $i;
        $this->$prop = (int) $row[$prop];
      }
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a template by ID.
   *
   * @param string $id Record ID to find
   *
   * @return bool Query result
   */
  public function getTemplateById(string $id = ''): bool {
    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      $this->username = (string) $row['username'];
      $this->year     = (string) $row['year'];
      $this->month    = (string) $row['month'];
      for ($i = 1; $i <= 31; $i++) {
        $prop        = 'abs' . $i;
        $this->$prop = (int) $row[$prop];
      }
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given user has a given absence in a given month.
   *
   * @param string $username Username to find
   * @param string $year     Year to find (YYYY)
   * @param string $month    Month to find (MM)
   * @param int    $absid    Absence ID to find
   *
   * @return bool 0 or 1
   */
  public function hasAbsence(string $username = '', string $year = '', string $month = '', string|int $absid = 0): bool {
    $month = sprintf("%02d", (int) $month);
    for ($i = 1; $i <= 31; $i++) {
      $query = $this->db->prepare("SELECT username FROM {$this->table} WHERE username = :username AND year = :year AND month = :month AND abs{$i} = :absid");
      $query->bindParam(':username', $username, PDO::PARAM_STR);
      $query->bindParam(':year', $year, PDO::PARAM_STR);
      $query->bindParam(':month', $month, PDO::PARAM_STR);
      $query->bindParam(':absid', $absid);
      $result = $query->execute();
      if ($result && $query->fetch()) {
        return true;
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Replaces an absence ID in all templates.
   *
   * @param int $absidold Symbol to be replaced
   * @param int $absidnew Symbol to replace with
   *
   * @return bool
   */
  public function replaceAbsId(string|int $absidold, string|int $absidnew): bool {
    $query  = $this->db->prepare("SELECT id, " . implode(", ", array_map(fn($i) => "abs{$i}", range(1, 31))) . " FROM {$this->table}");
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        for ($i = 1; $i <= 31; $i++) {
          if ((int) $row['abs' . $i] === $absidold) {
            $stmt   = "UPDATE {$this->table} SET abs{$i} = :absidnew WHERE id = :id";
            $query2 = $this->db->prepare($stmt);
            $query2->bindParam(':absidnew', $absidnew);
            $query2->bindParam(':id', $row['id'], PDO::PARAM_INT);
            $query2->execute();
          }
        }
      }
      return true;
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
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Set an absence for a given username, year, month and day.
   *
   * @param string $username Username for update
   * @param string $year     Year for update (YYYY)
   * @param string $month    Month for update (MM)
   * @param string $day      Day for update
   * @param int    $abs      Absence to set
   *
   * @return bool Query result
   */
  public function setAbsence(string $username, string $year, string $month, string $day, string|int $abs): bool {
    $query = $this->db->prepare("UPDATE {$this->table} SET abs{$day} = :abs WHERE username = :username AND year = :year AND month = :month");
    $query->bindParam(':abs', $abs);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':year', $year, PDO::PARAM_STR);
    $month = sprintf("%02d", (int) $month);
    $query->bindParam(':month', $month, PDO::PARAM_STR);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a template for a given username, year and month.
   *
   * @param string $uname Username for update
   * @param string $year  Year for update (YYYY)
   * @param string $month Month for update (MM)
   *
   * @return bool Query result
   */
  public function update(string $uname, string $year, string $month): bool {
    $stmt     = "UPDATE {$this->table} SET username = :username, year = :year, month = :month, ";
    $setParts = [];
    for ($i = 1; $i <= 31; $i++) {
      $setParts[] = "abs{$i} = :abs{$i}";
    }
    $stmt .= implode(", ", $setParts);
    $stmt .= " WHERE username = :uname AND year = :yearOld AND month = :monthOld";

    $query = $this->db->prepare($stmt);
    $query->bindParam(':username', $this->username, PDO::PARAM_STR);
    $query->bindParam(':year', $this->year, PDO::PARAM_STR);
    $query->bindParam(':month', $this->month, PDO::PARAM_STR);
    $query->bindParam(':uname', $uname, PDO::PARAM_STR);
    $query->bindParam(':yearOld', $year, PDO::PARAM_STR);
    $month = sprintf("%02d", (int) $month);
    $query->bindParam(':monthOld', $month, PDO::PARAM_STR);
    for ($i = 1; $i <= 31; $i++) {
      $prop = 'abs' . $i;
      $query->bindParam(':abs' . $i, $this->$prop, PDO::PARAM_INT);
    }
    return $query->execute();
  }
}
