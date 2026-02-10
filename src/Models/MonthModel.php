<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * MonthModel
 *
 * This class provides methods and properties for months.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class MonthModel
{
  public string $year   = '';
  public string $month  = '';
  public string $region = '1';

  public int $wday1  = 0;
  public int $wday2  = 0;
  public int $wday3  = 0;
  public int $wday4  = 0;
  public int $wday5  = 0;
  public int $wday6  = 0;
  public int $wday7  = 0;
  public int $wday8  = 0;
  public int $wday9  = 0;
  public int $wday10 = 0;
  public int $wday11 = 0;
  public int $wday12 = 0;
  public int $wday13 = 0;
  public int $wday14 = 0;
  public int $wday15 = 0;
  public int $wday16 = 0;
  public int $wday17 = 0;
  public int $wday18 = 0;
  public int $wday19 = 0;
  public int $wday20 = 0;
  public int $wday21 = 0;
  public int $wday22 = 0;
  public int $wday23 = 0;
  public int $wday24 = 0;
  public int $wday25 = 0;
  public int $wday26 = 0;
  public int $wday27 = 0;
  public int $wday28 = 0;
  public int $wday29 = 0;
  public int $wday30 = 0;
  public int $wday31 = 0;

  public int $week1  = 0;
  public int $week2  = 0;
  public int $week3  = 0;
  public int $week4  = 0;
  public int $week5  = 0;
  public int $week6  = 0;
  public int $week7  = 0;
  public int $week8  = 0;
  public int $week9  = 0;
  public int $week10 = 0;
  public int $week11 = 0;
  public int $week12 = 0;
  public int $week13 = 0;
  public int $week14 = 0;
  public int $week15 = 0;
  public int $week16 = 0;
  public int $week17 = 0;
  public int $week18 = 0;
  public int $week19 = 0;
  public int $week20 = 0;
  public int $week21 = 0;
  public int $week22 = 0;
  public int $week23 = 0;
  public int $week24 = 0;
  public int $week25 = 0;
  public int $week26 = 0;
  public int $week27 = 0;
  public int $week28 = 0;
  public int $week29 = 0;
  public int $week30 = 0;
  public int $week31 = 0;

  public int $hol1  = 0;
  public int $hol2  = 0;
  public int $hol3  = 0;
  public int $hol4  = 0;
  public int $hol5  = 0;
  public int $hol6  = 0;
  public int $hol7  = 0;
  public int $hol8  = 0;
  public int $hol9  = 0;
  public int $hol10 = 0;
  public int $hol11 = 0;
  public int $hol12 = 0;
  public int $hol13 = 0;
  public int $hol14 = 0;
  public int $hol15 = 0;
  public int $hol16 = 0;
  public int $hol17 = 0;
  public int $hol18 = 0;
  public int $hol19 = 0;
  public int $hol20 = 0;
  public int $hol21 = 0;
  public int $hol22 = 0;
  public int $hol23 = 0;
  public int $hol24 = 0;
  public int $hol25 = 0;
  public int $hol26 = 0;
  public int $hol27 = 0;
  public int $hol28 = 0;
  public int $hol29 = 0;
  public int $hol30 = 0;
  public int $hol31 = 0;

  private ?PDO   $db    = null;
  private string $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null             $db   Database connection object
   * @param array<string, string>|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db && $conf) {
      $this->db    = $db;
      $this->table = $conf['db_table_months'];
    }
    else {
      global $CONF, $DB;
      $this->db    = $DB->db;
      $this->table = $CONF['db_table_months'];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all templates before (and including) a given year/month.
   *
   * @param string $year   Year of the template (YYYY)
   * @param string $month  Month of the template (MM)
   * @param string $region Region of the template
   *
   * @return bool Query result
   */
  public function clearHolidays(string $year, string $month, string $region): bool {
    $stmt = "UPDATE {$this->table} SET ";
    for ($i = 1; $i <= 31; $i++) {
      $prop  = 'hol' . $i;
      $stmt .= $prop . ' = 0, ';
    }
    $stmt   = substr($stmt, 0, -2);
    $stmt  .= ' WHERE year = :year AND month = :month AND region = :region;';
    $query  = $this->db->prepare($stmt);
    $query->bindParam(':year', $year);
    $month = sprintf("%02d", (int) $month);
    $query->bindParam(':month', $month);
    $query->bindParam(':region', $region);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a template from local variables.
   *
   * @return bool Query result
   */
  public function create(): bool {
    $stmt = "
       INSERT INTO {$this->table}
       (
          region, year, month,
          hol1,  hol2,  hol3,  hol4,  hol5,  hol6,  hol7,  hol8,  hol9,  hol10,  hol11,  hol12,  hol13,  hol14,  hol15,  hol16,  hol17,  hol18,  hol19,  hol20,  hol21,  hol22,  hol23,  hol24,  hol25,  hol26,  hol27,  hol28,  hol29,  hol30,  hol31,
          wday1, wday2, wday3, wday4, wday5, wday6, wday7, wday8, wday9, wday10, wday11, wday12, wday13, wday14, wday15, wday16, wday17, wday18, wday19, wday20, wday21, wday22, wday23, wday24, wday25, wday26, wday27, wday28, wday29, wday30, wday31,
          week1, week2, week3, week4, week5, week6, week7, week8, week9, week10, week11, week12, week13, week14, week15, week16, week17, week18, week19, week20, week21, week22, week23, week24, week25, week26, week27, week28, week29, week30, week31
       )
       VALUES
       (
          :val1,  :val2,  :val3,
          :val4,  :val5,  :val6,  :val7,  :val8,  :val9,  :val10, :val11, :val12, :val13, :val14, :val15, :val16, :val17, :val18, :val19, :val20, :val21, :val22, :val23, :val24, :val25, :val26, :val27, :val28, :val29, :val30, :val31, :val32, :val33, :val34,
          :val35, :val36, :val37, :val38, :val39, :val40, :val41, :val42, :val43, :val44, :val45, :val46, :val47, :val48, :val49, :val50, :val51, :val52, :val53, :val54, :val55, :val56, :val57, :val58, :val59, :val60, :val61, :val62, :val63, :val64, :val65,
          :val66, :val67, :val68, :val69, :val70, :val71, :val72, :val73, :val74, :val75, :val76, :val77, :val78, :val79, :val80, :val81, :val82, :val83, :val84, :val85, :val86, :val87, :val88, :val89, :val90, :val91, :val92, :val93, :val94, :val95, :val96
       )";

    $query = $this->db->prepare($stmt);
    $query->bindParam('val1', $this->region);
    $query->bindParam('val2', $this->year);
    $month = sprintf("%02d", (int) $this->month);
    $query->bindParam('val3', $month);
    for ($i = 1; $i <= 31; $i++) {
      $prop = 'hol' . $i;
      $query->bindParam('val' . ($i + 3), $this->$prop);
      $prop = 'wday' . $i;
      $query->bindParam('val' . ($i + 34), $this->$prop);
      $prop = 'week' . $i;
      $query->bindParam('val' . ($i + 65), $this->$prop);
    }

    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records.
   *
   * @return bool Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$this->table}");
    $query->execute();
    if ($query->fetchColumn()) {
      $query = $this->db->prepare("TRUNCATE TABLE {$this->table}");
      return $query->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all templates before (and including) a given year/month.
   *
   * @param string $year  Year of the template (YYYY)
   * @param string $month Month of the template (MM)
   *
   * @return bool Query result
   */
  public function deleteBefore(string $year, string $month): bool {
    $formattedMonth = sprintf("%02d", (int) $month);
    $query          = $this->db->prepare("DELETE FROM {$this->table} WHERE year < ? OR (year = ? AND month <= ?)");
    return $query->execute([$year, $year, $formattedMonth]);
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all templates for a given year/month combo.
   *
   * @param string $year  Year of the template (YYYY)
   * @param string $month Month of the template (MM)
   *
   * @return bool Query result
   */
  public function deleteMonth(string $year, string $month): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE year = :year AND month = :month");
    $query->bindParam(':year', $year);
    $formattedMonth = sprintf("%02d", (int) $month);
    $query->bindParam(':month', $formattedMonth);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all templates for a region.
   *
   * @param string $region Region ID
   *
   * @return bool Query result
   */
  public function deleteRegion(string $region): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE region = :region");
    $query->bindParam(':region', $region);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a template by region, year and month.
   *
   * @param string $year   Year of the template (YYYY)
   * @param string $month  Month of the template (MM)
   * @param string $region Region ID
   *
   * @return bool Query result
   */
  public function deleteRegionMonth(string $year, string $month, string $region): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE region = :region AND year = :year AND month = :month");
    $query->bindParam(':region', $region);
    $query->bindParam(':year', $year);
    $formattedMonth = sprintf("%02d", (int) $month);
    $query->bindParam(':month', $formattedMonth);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the holiday ID of a given region, year, month and day.
   *
   * @param string $year   Year to find (YYYY)
   * @param string $month  Month to find (MM)
   * @param string $day    Day of month to find (D)
   * @param string $region Region ID
   *
   * @return int|bool 0 or Holiday ID, or false on failure
   */
  public function getHoliday(string $year, string $month, string $day, string $region): int|bool {
    $query = $this->db->prepare("SELECT hol{$day} FROM {$this->table} WHERE region = :region AND year = :year AND month = :month");
    $query->bindParam(':region', $region);
    $query->bindParam(':year', $year);
    $formattedMonth = sprintf("%02d", (int) $month);
    $query->bindParam(':month', $formattedMonth);
    $query->execute();
    $row = $query->fetch();
    if ($row) {
      return (int) $row['hol' . $day];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a template by region, year and month.
   *
   * @param string $year   Year to find (YYYY)
   * @param string $month  Month to find (MM)
   * @param string $region Region ID
   *
   * @return bool Query result
   */
  public function getMonth(string $year, string $month, string $region): bool {
    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE region = :val1 AND year = :val2 AND month = :val3");
    $query->bindParam('val1', $region);
    $query->bindParam('val2', $year);
    $formattedMonth = sprintf("%02d", (int) $month);
    $query->bindParam('val3', $formattedMonth);
    $result = $query->execute();

    if ($result && $row = $query->fetch()) {
      $this->region = (string) $row['region'];
      $this->year   = (string) $row['year'];
      $this->month  = (string) $row['month'];
      for ($i = 1; $i <= 31; $i++) {
        $prop        = 'hol' . $i;
        $this->$prop = (int) $row[$prop];
        $prop        = 'wday' . $i;
        $this->$prop = (int) $row[$prop];
        $prop        = 'week' . $i;
        $this->$prop = (int) $row[$prop];
      }
      return true;
    }
    else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all templates for a given region ID.
   *
   * @param string $region Region ID
   *
   * @return array<int, array<string, mixed>> Month templates of the given region
   */
  public function getRegion(string $region): array {
    $records = [];
    $query   = $this->db->prepare("SELECT * FROM {$this->table} WHERE region = :region");
    $query->bindParam(':region', $region);
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the weeknumber of a given region, year, month and day.
   *
   * @param string $year   Year to find (YYYY)
   * @param string $month  Month to find (MM)
   * @param string $day    Day of month to find (D)
   * @param string $region Region ID
   *
   * @return int|bool Week number or false on failure
   */
  public function getWeek(string $year, string $month, string $day, string $region): int|bool {
    $query = $this->db->prepare("SELECT week{$day} FROM {$this->table} WHERE region = :region AND year = :year AND month = :month");
    $query->bindParam(':region', $region);
    $query->bindParam(':year', $year);
    $formattedMonth = sprintf("%02d", (int) $month);
    $query->bindParam(':month', $formattedMonth);
    $query->execute();
    $row = $query->fetch();
    if ($row) {
      return (int) $row['week' . $day];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the weekday of a given region, year, month and day.
   *
   * @param string $year   Year to find (YYYY)
   * @param string $month  Month to find (MM)
   * @param string $day    Day of month to find (D)
   * @param string $region Region ID
   *
   * @return int|bool Weekday ID or false on failure
   */
  public function getWeekday(string $year, string $month, string $day, string $region): int|bool {
    $query = $this->db->prepare("SELECT wday{$day} FROM {$this->table} WHERE region = :region AND year = :year AND month = :month");
    $query->bindParam(':region', $region);
    $query->bindParam(':year', $year);
    $formattedMonth = sprintf("%02d", (int) $month);
    $query->bindParam(':month', $formattedMonth);
    $query->execute();
    $row = $query->fetch();
    if ($row) {
      return (int) $row['wday' . $day];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Sets a holiday for a given year, month, day and region.
   *
   * @param string $year   Year to update (YYYY)
   * @param string $month  Month to update (MM)
   * @param string $day    Day to update
   * @param string $region Region ID
   * @param string $hol    Absence to set
   *
   * @return bool Query result
   */
  public function setHoliday(string $year, string $month, string $day, string $region, string $hol): bool {
    $query = $this->db->prepare("UPDATE {$this->table} SET hol{$day} = :hol WHERE region = :region AND year = :year AND month = :month");
    $query->bindParam(':hol', $hol);
    $query->bindParam(':region', $region);
    $query->bindParam(':year', $year);
    $formattedMonth = sprintf("%02d", (int) $month);
    $query->bindParam(':month', $formattedMonth);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Sets a weekday for a given year, month, day and region.
   *
   * @param string $year   Year to update (YYYY)
   * @param string $month  Month to update (MM)
   * @param string $day    Day to update
   * @param string $region Region ID
   * @param string $wday   Weekday number
   *
   * @return bool Query result
   */
  public function setWeekday(string $year, string $month, string $day, string $region, string $wday): bool {
    $query = $this->db->prepare("UPDATE {$this->table} SET wday{$day} = :wday WHERE region = :region AND year = :year AND month = :month");
    $query->bindParam(':wday', $wday);
    $query->bindParam(':region', $region);
    $query->bindParam(':year', $year);
    $formattedMonth = sprintf("%02d", (int) $month);
    $query->bindParam(':month', $formattedMonth);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Sets a week number for a given year, month, day and region.
   *
   * @param string $year   Year to update (YYYY)
   * @param string $month  Month to update (MM)
   * @param string $day    Day to update
   * @param string $region Region ID
   * @param string $week   Week number
   *
   * @return bool Query result
   */
  public function setWeek(string $year, string $month, string $day, string $region, string $week): bool {
    $query = $this->db->prepare("UPDATE {$this->table} SET week{$day} = :week WHERE region = :region AND year = :year AND month = :month");
    $query->bindParam(':week', $week);
    $query->bindParam(':region', $region);
    $query->bindParam(':year', $year);
    $formattedMonth = sprintf("%02d", (int) $month);
    $query->bindParam(':month', $formattedMonth);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a template for a given region, year and month.
   *
   * @param string $year   Year to update (YYYY)
   * @param string $month  Month to update (MM)
   * @param string $region Region ID
   *
   * @return bool Query result
   */
  public function update(string $year, string $month, string $region): bool {
    $stmt = "UPDATE {$this->table} SET region = :val1, year = :val2, month = :val3, ";
    for ($i = 1; $i <= 31; $i++) {
      $prop  = 'hol' . $i;
      $stmt .= $prop . ' = ' . $this->$prop . ', ';
      $prop  = 'wday' . $i;
      $stmt .= $prop . ' = ' . $this->$prop . ', ';
      $prop  = 'week' . $i;
      $stmt .= $prop . ' = ' . $this->$prop . ', ';
    }
    $stmt  = substr($stmt, 0, -2);
    $stmt .= ' WHERE region = :val4 AND year = :val5 AND month = :val6';

    $query = $this->db->prepare($stmt);
    $query->bindParam('val1', $this->region);
    $query->bindParam('val2', $this->year);
    $query->bindParam('val3', $this->month);
    $query->bindParam('val4', $region);
    $query->bindParam('val5', $year);
    $formattedMonth = sprintf("%02d", (int) $month);
    $query->bindParam('val6', $formattedMonth);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Replaces a holiday ID in all templates.
   *
   * @param string $idold ID to be replaced
   * @param string $idnew ID to replace with
   *
   * @return bool Query result
   */
  public function replaceHoliday(string $idold, string $idnew): bool {
    $query  = $this->db->prepare("SELECT * FROM {$this->table}");
    $result = $query->execute();

    if ($result) {
      while ($row = $query->fetch()) {
        $stmt = "UPDATE {$this->table} SET ";
        $any  = false;
        for ($i = 1; $i <= 31; $i++) {
          if ($row['hol' . $i] == $idold) {
            $prop  = 'hol' . $i;
            $stmt .= $prop . " = :val{$i}, ";
            $any   = true;
          }
        }

        if ($any) {
          $stmt    = substr($stmt, 0, -2);
          $stmt   .= " WHERE id = :recordId";
          $query2  = $this->db->prepare($stmt);
          for ($i = 1; $i <= 31; $i++) {
            if ($row['hol' . $i] == $idold) {
              $query2->bindValue(":val{$i}", $idnew);
            }
          }
          $query2->bindValue(':recordId', $row['id']);
          $query2->execute();
        }
      }
    }
    return $result;
  }
}
