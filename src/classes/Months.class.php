<?php
if (!defined('VALID_ROOT')) {
  exit('');
}

/**
 * Months
 *
 * This class provides methods and properties for months.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Calendar Management
 * @since 3.0.0
 */
class Months {
  public $year = '';
  public $month = '';
  public $region = 1;

  public $wday1 = 0;
  public $wday2 = 0;
  public $wday3 = 0;
  public $wday4 = 0;
  public $wday5 = 0;
  public $wday6 = 0;
  public $wday7 = 0;
  public $wday8 = 0;
  public $wday9 = 0;
  public $wday10 = 0;
  public $wday11 = 0;
  public $wday12 = 0;
  public $wday13 = 0;
  public $wday14 = 0;
  public $wday15 = 0;
  public $wday16 = 0;
  public $wday17 = 0;
  public $wday18 = 0;
  public $wday19 = 0;
  public $wday20 = 0;
  public $wday21 = 0;
  public $wday22 = 0;
  public $wday23 = 0;
  public $wday24 = 0;
  public $wday25 = 0;
  public $wday26 = 0;
  public $wday27 = 0;
  public $wday28 = 0;
  public $wday29 = 0;
  public $wday30 = 0;
  public $wday31 = 0;

  public $week1 = 0;
  public $week2 = 0;
  public $week3 = 0;
  public $week4 = 0;
  public $week5 = 0;
  public $week6 = 0;
  public $week7 = 0;
  public $week8 = 0;
  public $week9 = 0;
  public $week10 = 0;
  public $week11 = 0;
  public $week12 = 0;
  public $week13 = 0;
  public $week14 = 0;
  public $week15 = 0;
  public $week16 = 0;
  public $week17 = 0;
  public $week18 = 0;
  public $week19 = 0;
  public $week20 = 0;
  public $week21 = 0;
  public $week22 = 0;
  public $week23 = 0;
  public $week24 = 0;
  public $week25 = 0;
  public $week26 = 0;
  public $week27 = 0;
  public $week28 = 0;
  public $week29 = 0;
  public $week30 = 0;
  public $week31 = 0;

  public $hol1 = 0;
  public $hol2 = 0;
  public $hol3 = 0;
  public $hol4 = 0;
  public $hol5 = 0;
  public $hol6 = 0;
  public $hol7 = 0;
  public $hol8 = 0;
  public $hol9 = 0;
  public $hol10 = 0;
  public $hol11 = 0;
  public $hol12 = 0;
  public $hol13 = 0;
  public $hol14 = 0;
  public $hol15 = 0;
  public $hol16 = 0;
  public $hol17 = 0;
  public $hol18 = 0;
  public $hol19 = 0;
  public $hol20 = 0;
  public $hol21 = 0;
  public $hol22 = 0;
  public $hol23 = 0;
  public $hol24 = 0;
  public $hol25 = 0;
  public $hol26 = 0;
  public $hol27 = 0;
  public $hol28 = 0;
  public $hol29 = 0;
  public $hol30 = 0;
  public $hol31 = 0;

  private $db = '';
  private $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_months'];
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all templates before (and including) a given year/month
   *
   * @param string $year Year of the template (YYYY)
   * @param string $month Month of the template (MM)
   * @param string $region Region of the template
   * @return boolean Query result
   */
  public function clearHolidays($year, $month, $region) {
    $stmt = 'UPDATE ' . $this->table . ' SET ';
    for ($i = 1; $i <= 31; $i++) {
      $prop = 'hol' . $i;
      $stmt .= $prop . ' = 0, ';
    }
    $stmt = substr($stmt, 0, -2);
    $stmt .= ' WHERE year = :val1 AND month = :val2 AND region = :val3;';

    $query = $this->db->prepare($stmt);
    $query->bindParam('val1', $year);
    $month = sprintf("%02d", $month);
    $query->bindParam('val2', $month);
    $query->bindParam('val3', $region);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a template from local variables
   *
   * @return boolean Query result
   */
  public function create() {
    $stmt = '
       INSERT INTO ' . $this->table . '
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
       )';

    $query = $this->db->prepare($stmt);
    $query->bindParam('val1', $this->region);
    $query->bindParam('val2', $this->year);
    $month = sprintf("%02d", $this->month);
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
   * Deletes all records
   *
   * @return boolean Query result
   */
  public function deleteAll() {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table);
    $result = $query->execute();

    if ($result && $query->fetchColumn()) {
      $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
      return $query->execute();
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all templates before (and including) a given year/month
   *
   * @param string $year Year of the template (YYYY)
   * @param string $month Month of the template (MM)
   * @return boolean Query result
   */
  public function deleteBefore($year, $month) {
    $month = sprintf("%02d", $month);
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE year < ' . $year . ' OR (year = ' . $year . ' AND month <= ' . $month . ')');
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all templates for a given year/month combo
   *
   * @param string $year Year of the template (YYYY)
   * @param string $month Month of the template (MM)
   * @return boolean Query result
   */
  public function deleteMonth($year, $month) {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE year = :val1 AND month = :val2');
    $query->bindParam('val1', $year);
    $month = sprintf("%02d", $month);
    $query->bindParam('val2', $month);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all templates for a region
   *
   * @param string $region Region ID
   * @return boolean Query result
   */
  public function deleteRegion($region) {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE region = :val1');
    $query->bindParam('val1', $region);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a template by region, year and month
   *
   * @param string $year Year of the template (YYYY)
   * @param string $month Month of the template (MM)
   * @param string $region Region ID
   * @return boolean Query result
   */
  public function deleteRegionMonth($year, $month, $region) {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE region = :val1 AND year = :val2 AND month = :val3');
    $query->bindParam('val1', $region);
    $query->bindParam('val2', $year);
    $month = sprintf("%02d", $month);
    $query->bindParam('val3', $month);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the holiday ID of a given region, year, month and day
   *
   * @param string $year Year to find (YYYY)
   * @param string $month Month to find (MM)
   * @param string $day Day of month to find (D)
   * @param string $region Region ID
   * @return boolean 0 or Holiday ID
   */
  public function getHoliday($year, $month, $day, $region) {
    $query = $this->db->prepare('SELECT hol' . $day . ' FROM ' . $this->table . ' WHERE region = :val1 AND year = :val2 AND month = :val3');
    $query->bindParam('val1', $region);
    $query->bindParam('val2', $year);
    $month = sprintf("%02d", $month);
    $query->bindParam('val3', $month);
    $result = $query->execute();

    if ($result && $row = $query->fetch()) {
      return $row['hol' . $day];
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gest a template by region, year and month
   *
   * @param string $year Year to find (YYYY)
   * @param string $month Month to find (MM)
   * @param string $region Region ID
   * @return boolean Query result
   */
  public function getMonth($year, $month, $region) {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE region = :val1 AND year = :val2 AND month = :val3');
    $query->bindParam('val1', $region);
    $query->bindParam('val2', $year);
    $month = sprintf("%02d", $month);
    $query->bindParam('val3', $month);
    $result = $query->execute();

    if ($result && $row = $query->fetch()) {
      $this->region = $row['region'];
      $this->year = $row['year'];
      $this->month = $row['month'];
      for ($i = 1; $i <= 31; $i++) {
        $prop = 'hol' . $i;
        $this->$prop = $row[$prop];
        $prop = 'wday' . $i;
        $this->$prop = $row[$prop];
        $prop = 'week' . $i;
        $this->$prop = $row[$prop];
      }
      return true;
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gest all templates for a given region ID
   *
   * @param string $region Region ID
   * @return array Month templates of the given region
   */
  public function getRegion($region) {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE region = :val1');
    $query->bindParam('val1', $region);
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
   * Gets the weeknumber of a given region, year, month and day
   *
   * @param string $year Year to find (YYYY)
   * @param string $month Month to find (MM)
   * @param string $day Day of month to find (D)
   * @param string $region Region ID
   * @return boolean 0 or absence ID
   */
  public function getWeek($year, $month, $day, $region) {
    $query = $this->db->prepare('SELECT week' . $day . ' FROM ' . $this->table . ' WHERE region = :val1 AND year = :val2 AND month = :val3');
    $query->bindParam('val1', $region);
    $query->bindParam('val2', $year);
    $month = sprintf("%02d", $month);
    $query->bindParam('val3', $month);
    $result = $query->execute();

    if ($result && $row = $query->fetch()) {
      return $row['week' . $day];
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the weekday of a given region, year, month and day
   *
   * @param string $year Year to find (YYYY)
   * @param string $month Month to find (MM)
   * @param string $day Day of month to find (D)
   * @param string $region Region ID
   * @return boolean 0 or absence ID
   */
  public function getWeekday($year, $month, $day, $region) {
    $query = $this->db->prepare('SELECT wday' . $day . ' FROM ' . $this->table . ' WHERE region = :val1 AND year = :val2 AND month = :val3');
    $query->bindParam('val1', $region);
    $query->bindParam('val2', $year);
    $month = sprintf("%02d", $month);
    $query->bindParam('val3', $month);
    $result = $query->execute();

    if ($result && $row = $query->fetch()) {
      return $row['wday' . $day];
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Sets a holiday for a given year, month, day and region
   *
   * @param string $year Year to update (YYYY)
   * @param string $month Month to update (MM)
   * @param string $day Day to update
   * @param string $region Region ID
   * @param string $hol Absence to set
   * @return boolean Query result
   */
  public function setHoliday($year, $month, $day, $region, $hol) {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET hol' . $day . ' = :val1 WHERE region = :val2 AND year = :val3 AND month = :val4');
    $query->bindParam('val1', $hol);
    $query->bindParam('val2', $region);
    $query->bindParam('val3', $year);
    $month = sprintf("%02d", $month);
    $query->bindParam('val4', $month);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Sets a weekday for a given year, month, day and region
   *
   * @param string $year Year to update (YYYY)
   * @param string $month Month to update (MM)
   * @param string $day Day to update
   * @param string $region Region ID
   * @param string $wday Weekday number
   * @return boolean Query result
   */
  public function setWeekday($year, $month, $day, $region, $wday) {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET wday' . $day . ' = :val1 WHERE region = :val2 AND year = :val3 AND month = :val4');
    $query->bindParam('val1', $wday);
    $query->bindParam('val2', $region);
    $query->bindParam('val3', $year);
    $month = sprintf("%02d", $month);
    $query->bindParam('val4', $month);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Sets a week number for a given year, month, day and region
   *
   * @param string $year Year to update (YYYY)
   * @param string $month Month to update (MM)
   * @param string $day Day to update
   * @param string $region Region ID
   * @param string $week Week number
   * @return boolean Query result
   */
  public function setWeek($year, $month, $day, $region, $week) {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET week' . $day . ' = :val1 WHERE region = :val2 AND year = :val3 AND month = :val4');
    $query->bindParam('val1', $week);
    $query->bindParam('val2', $region);
    $query->bindParam('val3', $year);
    $month = sprintf("%02d", $month);
    $query->bindParam('val4', $month);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a template for a given region, year and month
   *
   * @param string $year Year to update (YYYY)
   * @param string $month Month to update (MM)
   * @param string $region Region ID
   * @return boolean Query result
   */
  public function update($year, $month, $region) {
    $stmt = 'UPDATE ' . $this->table . ' SET region = :val1, year = :val2, month = :val3, ';
    for ($i = 1; $i <= 31; $i++) {
      $prop = 'hol' . $i;
      $stmt .= $prop . ' = ' . $this->$prop . ', ';
      $prop = 'wday' . $i;
      $stmt .= $prop . ' = ' . $this->$prop . ', ';
      $prop = 'week' . $i;
      $stmt .= $prop . ' = ' . $this->$prop . ', ';
    }
    $stmt = substr($stmt, 0, -2);
    $stmt .= ' WHERE region = :val4 AND year = :val5 AND month = :val6';

    $query = $this->db->prepare($stmt);
    $query->bindParam('val1', $this->region);
    $query->bindParam('val2', $this->year);
    $query->bindParam('val3', $this->month);
    $query->bindParam('val4', $region);
    $query->bindParam('val5', $year);
    $month = sprintf("%02d", $month);
    $query->bindParam('val6', $month);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Replaces a holiday ID in all templates.
   *
   * @param string $idold ID to be replaced
   * @param string $idnew ID to replace with
   */
  public function replaceHoliday($idold, $idnew) {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table);
    $result = $query->execute();

    if ($result) {
      while ($row = $query->fetch()) {
        $stmt = "UPDATE " . $this->table . " SET ";
        for ($i = 1; $i <= 31; $i++) {
          if ($row['hol' . $i] == $idold) {
            $prop = 'hol' . $i;
            $row[$prop] = $idnew;
            $stmt .= $prop . " = '" . $idnew . "', ";
          }
        }
        $stmt = substr($stmt, 0, -2);
        $stmt .= " WHERE id = '" . $row['id'] . "';";
        $query2 = $this->db->prepare($stmt);
        $query2->execute();
      }
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Optimize table
   *
   * @return boolean Query result
   */
  public function optimize() {
    $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->table);
    return $query->execute();
  }
}
