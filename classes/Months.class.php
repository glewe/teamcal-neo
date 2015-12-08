<?php
/**
 * Months.class.php
 *
 * @category TeamCal Neo 
 * @version 0.3.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides objects and methods to manage the months table
 */
class Months
{
   var $db = '';
   var $table = '';
   var $year = '';
   var $month = '';
   var $region = 1;
   
   var $wday1 = 0;
   var $wday2 = 0;
   var $wday3 = 0;
   var $wday4 = 0;
   var $wday5 = 0;
   var $wday6 = 0;
   var $wday7 = 0;
   var $wday8 = 0;
   var $wday9 = 0;
   var $wday10 = 0;
   var $wday11 = 0;
   var $wday12 = 0;
   var $wday13 = 0;
   var $wday14 = 0;
   var $wday15 = 0;
   var $wday16 = 0;
   var $wday17 = 0;
   var $wday18 = 0;
   var $wday19 = 0;
   var $wday20 = 0;
   var $wday21 = 0;
   var $wday22 = 0;
   var $wday23 = 0;
   var $wday24 = 0;
   var $wday25 = 0;
   var $wday26 = 0;
   var $wday27 = 0;
   var $wday28 = 0;
   var $wday29 = 0;
   var $wday30 = 0;
   var $wday31 = 0;
   
   var $week1 = 0;
   var $week2 = 0;
   var $week3 = 0;
   var $week4 = 0;
   var $week5 = 0;
   var $week6 = 0;
   var $week7 = 0;
   var $week8 = 0;
   var $week9 = 0;
   var $week10 = 0;
   var $week11 = 0;
   var $week12 = 0;
   var $week13 = 0;
   var $week14 = 0;
   var $week15 = 0;
   var $week16 = 0;
   var $week17 = 0;
   var $week18 = 0;
   var $week19 = 0;
   var $week20 = 0;
   var $week21 = 0;
   var $week22 = 0;
   var $week23 = 0;
   var $week24 = 0;
   var $week25 = 0;
   var $week26 = 0;
   var $week27 = 0;
   var $week28 = 0;
   var $week29 = 0;
   var $week30 = 0;
   var $week31 = 0;
   
   var $hol1 = 0;
   var $hol2 = 0;
   var $hol3 = 0;
   var $hol4 = 0;
   var $hol5 = 0;
   var $hol6 = 0;
   var $hol7 = 0;
   var $hol8 = 0;
   var $hol9 = 0;
   var $hol10 = 0;
   var $hol11 = 0;
   var $hol12 = 0;
   var $hol13 = 0;
   var $hol14 = 0;
   var $hol15 = 0;
   var $hol16 = 0;
   var $hol17 = 0;
   var $hol18 = 0;
   var $hol19 = 0;
   var $hol20 = 0;
   var $hol21 = 0;
   var $hol22 = 0;
   var $hol23 = 0;
   var $hol24 = 0;
   var $hol25 = 0;
   var $hol26 = 0;
   var $hol27 = 0;
   var $hol28 = 0;
   var $hol29 = 0;
   var $hol30 = 0;
   var $hol31 = 0;
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_months'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all templates before (and including) a given year/month
    *
    * @param string $year Year of the template (YYYY)
    * @param string $month Month of the template (MM)
    * @return bool $result Query result
    */
   function clearHolidays($year, $month, $region)
   {
      $stmt = 'UPDATE ' . $this->table . ' SET ';
      for($i = 1; $i <= 31; $i++)
      {
         $prop = 'hol' . $i;
         $stmt .= $prop . ' = 0, ';
      }
      $stmt = substr($stmt, 0, -2);
      $stmt .= ' WHERE year = :val1 AND month = :val2 AND region = :val3;';

      $query = $this->db->prepare($stmt);
      $query->bindParam('val1', $year);
      $query->bindParam('val2', sprintf("%02d", $month));
      $query->bindParam('val3', $region);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a template from local variables
    *
    * @return bool $result Query result
    */
   function create()
   {
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
      $query->bindParam('val3', sprintf("%02d", $this->month));
      for($i = 1; $i <= 31; $i++)
      {
         $prop = 'hol' . $i;
         $query->bindParam('val' . ($i + 3), $this->$prop);
         $prop = 'wday' . $i;
         $query->bindParam('val' . ($i + 34), $this->$prop);
         $prop = 'week' . $i;
         $query->bindParam('val' . ($i + 65), $this->$prop);
      }
      
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all records
    *
    * @return bool $result Query result
    */
   function deleteAll()
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table);
      $result = $query->execute();
      
      if ($result and $query->fetchColumn())
      {
         $query = $this->db->prepare('TRUNCATE TABLE ' . $table);
         $result = $query->execute();
         return $result;
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all templates before (and including) a given year/month
    *
    * @param string $year Year of the template (YYYY)
    * @param string $month Month of the template (MM)
    * @return bool $result Query result
    */
   function deleteBefore($year, $month)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE year < :val1 OR (year = :val1 AND month <= :val2)');
      $query->bindParam('val1', $year);
      $query->bindParam('val2', $month);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all templates for a given year/month combo
    *
    * @param string $year Year of the template (YYYY)
    * @param string $month Month of the template (MM)
    * @return bool $result Query result
    */
   function deleteMonth($year, $month)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE year = :val1 AND month = :val2');
      $query->bindParam('val1', $year);
      $query->bindParam('val2', $month);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all templates for a username
    *
    * @param string $region Region ID to delete all records of
    * @return bool $result Query result
    */
   function deleteRegion($region)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE region = :val1');
      $query->bindParam('val1', $region);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a template by region, year and month
    *
    * @param string $region Region ID this template is for
    * @param string $year Year of the template (YYYY)
    * @param string $month Month of the template (MM)
    * @return bool $result Query result
    */
   function deleteRegionMonth($year, $month, $region)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE region = :val1 AND year = :val2 AND month = :val3');
      $query->bindParam('val1', $region);
      $query->bindParam('val2', $year);
      $query->bindParam('val3', $month);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets the holiday ID of a given region, year, month and day
    *
    * @param string $region Region ID to find
    * @param string $year Year to find (YYYY)
    * @param string $month Month to find (MM)
    * @param string $day Day of month to find (D)
    * @return bool 0 or absence ID
    */
   function getHoliday($year, $month, $day, $region)
   {
      $query = $this->db->prepare('SELECT hol' . $day . ' FROM ' . $this->table . ' WHERE region = :val1 AND year = :val2 AND month = :val3');
      $query->bindParam('val1', $region);
      $query->bindParam('val2', $year);
      $query->bindParam('val3', sprintf("%02d", $month));
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['hol' . $day];
      }
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gest a template by region, year and month
    *
    * @param string $region Region ID to find
    * @param string $year Year to find (YYYY)
    * @param string $month Month to find (MM)
    * @return bool $result Query result
    */
   function getMonth($year, $month, $region)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE region = :val1 AND year = :val2 AND month = :val3');
      $query->bindParam('val1', $region);
      $query->bindParam('val2', $year);
      $query->bindParam('val3', sprintf("%02d", $month));
      $result = $query->execute();
      
      if ($result AND $row = $query->fetch())
      {
         $this->region = $row['region'];
         $this->year = $row['year'];
         $this->month = $row['month'];
         for($i = 1; $i <= 31; $i++)
         {
            $prop = 'hol' . $i;
            $this->$prop = $row[$prop];
            $prop = 'wday' . $i;
            $this->$prop = $row[$prop];
            $prop = 'week' . $i;
            $this->$prop = $row[$prop];
         }
         return true;
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gest all templates for a given region ID
    *
    * @param string $region Region ID to find
    * @return bool $result Query result
    */
   function getRegion($region)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE region = :val1');
      $query->bindParam('val1', $region);
      $result = $query->execute();
      
      if ($result AND $row = $query->fetch())
      {
         $this->region = $row['region'];
         $this->year = $row['year'];
         $this->month = $row['month'];
         for($i = 1; $i <= 31; $i++)
         {
            $prop = 'hol' . $i;
            $this->$prop = $row[$prop];
            $prop = 'wday' . $i;
            $this->$prop = $row[$prop];
            $prop = 'week' . $i;
            $this->$prop = $row[$prop];
         }
         return true;
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets the weeknumber of a given region, year, month and day
    *
    * @param string $region Region ID to find
    * @param string $year Year to find (YYYY)
    * @param string $month Month to find (MM)
    * @param string $day Day of month to find (D)
    * @return bool 0 or absence ID
    */
   function getWeek($year, $month, $day, $region)
   {
      $query = $this->db->prepare('SELECT week' . $day . ' FROM ' . $this->table . ' WHERE region = :val1 AND year = :val2 AND month = :val3');
      $query->bindParam('val1', $region);
      $query->bindParam('val2', $year);
      $query->bindParam('val3', sprintf("%02d", $month));
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['week' . $day];
      }
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets the weekday of a given region, year, month and day
    *
    * @param string $region Region ID to find
    * @param string $year Year to find (YYYY)
    * @param string $month Month to find (MM)
    * @param string $day Day of month to find (D)
    * @return bool 0 or absence ID
    */
   function getWeekday($year, $month, $day, $region)
   {
      $query = $this->db->prepare('SELECT wday' . $day . ' FROM ' . $this->table . ' WHERE region = :val1 AND year = :val2 AND month = :val3');
      $query->bindParam('val1', $region);
      $query->bindParam('val2', $year);
      $query->bindParam('val3', sprintf("%02d", $month));
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['wday' . $day];
      }
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Sets a holiday for a given year, month, day and region
    *
    * @param string $region Region ID to update
    * @param string $year Year to update (YYYY)
    * @param string $month Month to update (MM)
    * @param string $day Day to update
    * @param string $hol Absence to set
    * @return bool $result Query result
    */
   function setHoliday($year, $month, $day, $region, $hol)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET hol' . $day . ' = :val1 WHERE region = :val2 AND year = :val3 AND month = :val4');
      $query->bindParam('val1', $hol);
      $query->bindParam('val2', $region);
      $query->bindParam('val3', $year);
      $query->bindParam('val4', sprintf("%02d", $month));
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Sets a weekday for a given year, month, day and region
    *
    * @param string $region Region ID to update
    * @param string $year Year to update (YYYY)
    * @param string $month Month to update (MM)
    * @param string $day Day to update
    * @param string $wday Weekday number
    * @return bool $result Query result
    */
   function setWeekday($year, $month, $day, $region, $wday)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET wday' . $day . ' = :val1 WHERE region = :val2 AND year = :val3 AND month = :val4');
      $query->bindParam('val1', $wday);
      $query->bindParam('val2', $region);
      $query->bindParam('val3', $year);
      $query->bindParam('val4', sprintf("%02d", $month));
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Sets a week number for a given year, month, day and region
    *
    * @param string $region Region ID to update
    * @param string $year Year to update (YYYY)
    * @param string $month Month to update (MM)
    * @param string $day Day to update
    * @param string $week Week number
    * @return bool $result Query result
    */
   function setWeek($year, $month, $day, $region, $week)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET week' . $day . ' = :val1 WHERE region = :val2 AND year = :val3 AND month = :val4');
      $query->bindParam('val1', $week);
      $query->bindParam('val2', $region);
      $query->bindParam('val3', $year);
      $query->bindParam('val4', sprintf("%02d", $month));
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates a template for a given region, year and month
    *
    * @param string $region Region ID to update
    * @param string $year Year to update (YYYY)
    * @param string $month Month to update (MM)
    * @return bool $result Query result
    */
   function update($year, $month, $region)
   {
      $stmt = 'UPDATE ' . $this->table . ' SET region = :val1, year = :val2, month = :val3, ';
      for($i = 1; $i <= 31; $i++)
      {
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
      $query->bindParam('val6', sprintf("%02d", $month));
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Replaces a holiday ID in all templates.
    *
    * @param string $idold ID to be replaced
    * @param string $idnew ID to replace with
    */
   function replaceHoliday($idold, $idnew)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table);
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $stmt = 'UPDATE ' . $this->table . ' SET ';
            for($i = 1; $i <= 31; $i++)
            {
               if ($row['hol' . $i] == $idold)
               {
                  $prop = 'hol' . $i;
                  $row[$prop] = $idnew;
                  $stmt .= $prop . ' = "' . $idnew . '", ';
               }
            }
            $stmt = substr($stmt, 0, -2);
            $stmt .= ' WHERE id = "' . $row['id'] . '";';
            $query2 = $this->db->prepare($stmt);
            $result2 = $query2->execute();
         }
      }
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Optimize table
    *
    * @return bool $result Query result
    */
   function optimize()
   {
      $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->table);
      $result = $query->execute();
      return $result;
   }
}

?>
