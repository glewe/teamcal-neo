<?php
/**
 * Templates.class.php
 *
 * @category TeamCal Neo 
 * @version 2.1.1
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides objects and methods to manage the template table
 */
class Templates
{
   public $username = '';
   public $year = '';
   public $month = '';
   public $abs1 = 0;
   public $abs2 = 0;
   public $abs3 = 0;
   public $abs4 = 0;
   public $abs5 = 0;
   public $abs6 = 0;
   public $abs7 = 0;
   public $abs8 = 0;
   public $abs9 = 0;
   public $abs10 = 0;
   public $abs11 = 0;
   public $abs12 = 0;
   public $abs13 = 0;
   public $abs14 = 0;
   public $abs15 = 0;
   public $abs16 = 0;
   public $abs17 = 0;
   public $abs18 = 0;
   public $abs19 = 0;
   public $abs20 = 0;
   public $abs21 = 0;
   public $abs22 = 0;
   public $abs23 = 0;
   public $abs24 = 0;
   public $abs25 = 0;
   public $abs26 = 0;
   public $abs27 = 0;
   public $abs28 = 0;
   public $abs29 = 0;
   public $abs30 = 0;
   public $abs31 = 0;
   
   private $db = '';
   private $table = '';
   private $archive_table = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   public function __construct()
   {
      global $CONF, $DB;
      global $myDb;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_templates'];
      $this->abs_table = $CONF['db_table_absences'];
      $this->archive_table = $CONF['db_table_archive_templates'];
      $this->utable = $CONF['db_table_users'];
      $this->archive_utable = $CONF['db_table_archive_users'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Archives all records for a given user
    *
    * @param string $username Username to archive
    * @return boolean Query result
    */
   public function archive($username)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Counts a sepcific absences of a given username, year, month and day
    *
    * @param string $username Username to find
    * @param string $year Year to find (YYYY)
    * @param string $month Month to find (MM)
    * @param string $start Start day
    * @param string $end End day
    * @return integer 0 or absence ID count
    */
   public function countAbsence($username = '%', $year = '', $month = '', $absid, $start = 1, $end = 0)
   {
      $count = 0;
      $mytime = $month . " ".$start."," . $year;
      $myts = strtotime($mytime);
      if (!$end or $end > 31) $end = date("t", $myts);
      
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username LIKE :val1 AND year = :val2 AND month = :val3');
      $month = sprintf("%02d", $month);
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $year);
      $month = sprintf("%02d", $month);
      $query->bindParam('val3', $month);
      $result = $query->execute();
      
      if ($result)
      {
         if ($username != "%")
         {
            if ($row = $query->fetch())
            {
               for($i = $start; $i <= $end; $i++)
               {
                  if ($row['abs' . $i] == $absid) $count++;
               }
            }
         }
         else
         {
            while ( $row = $query->fetch() )
            {
               for($i = $start; $i <= $end; $i++)
               {
                  if ($row['abs' . $i] == $absid) $count++;
               }
            }
         }
      }
      return $count;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Counts any absence of a given username, year, month and day
    *
    * @param string $username Username to find
    * @param string $year Year to find (YYYY)
    * @param string $month Month to find (MM)
    * @param string $start Start day
    * @param string $end End day
    * @return integer 0 or absence ID count
    */
   public function countAllAbsences($username = '%', $year = '', $month = '', $start = 1, $end = 0)
   {
      $count = 0;
      $mytime = $month . " ".$start."," . $year;
      $myts = strtotime($mytime);
      if (!$end or $end > 31) $end = date("t", $myts);
      
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username LIKE :val1 AND year = :val2 AND month = :val3');
      $month = sprintf("%02d", $month);
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $year);
      $month = sprintf("%02d", $month);
      $query->bindParam('val3', $month);
      $result = $query->execute();
      
      if ($result)
      {
         if ($username != "%")
         {
            if ($row = $query->fetch())
            {
               for($i = $start; $i <= $end; $i++)
               {
                  if ($row['abs'.$i] != 0) {
                     $countsAsPresent = false;
                     $query2 = $this->db->prepare('SELECT counts_as_present FROM ' . $this->abs_table . ' WHERE id = :val1');
                     $query2->bindParam('val1', $row['abs'.$i]);
                     $result2 = $query2->execute();
                     if ($result2 and $row2 = $query2->fetch()) $countsAsPresent = $row2['counts_as_present'];
                     if (!$countsAsPresent) $count++;
                  }
               }
            }
         }
         else
         {
            while ( $row = $query->fetch() )
            {
               for($i = $start; $i <= $end; $i++)
               {
                  if ($row['abs'.$i] != 0) {
                     $countsAsPresent = false;
                     $query2 = $this->db->prepare('SELECT counts_as_present FROM ' . $this->abs_table . ' WHERE id = :val1');
                     $query2->bindParam('val1', $row['abs'.$i]);
                     $result2 = $query2->execute();
                     if ($result2 and $row2 = $query2->fetch()) $countsAsPresent = $row2['counts_as_present'];
                     if (!$countsAsPresent) $count++;
                  }
               }
            }
         }
      }
      return $count;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a template from local variables
    *
    * @return boolean Query result
    */
   public function create()
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, year, month, abs1, abs2, abs3, abs4, abs5, abs6, abs7, abs8, abs9, abs10, abs11, abs12, abs13, abs14, abs15, abs16, abs17, abs18, abs19, abs20, abs21, abs22, abs23, abs24, abs25, abs26, abs27, abs28, abs29, abs30, abs31) VALUES (:val1, :val2, :val3, :val4, :val5, :val6, :val7, :val8, :val9, :val10, :val11, :val12, :val13, :val14, :val15, :val16, :val17, :val18, :val19, :val20, :val21, :val22, :val23, :val24, :val25, :val26, :val27, :val28, :val29, :val30, :val31, :val32, :val33, :val34)');
      $query->bindParam('val1', $this->username);
      $query->bindParam('val2', $this->year);
      $query->bindParam('val3', $this->month);
      for($i = 1; $i <= 31; $i++)
      {
         $prop = 'abs' . $i;
         $query->bindParam('val' . ($i + 3), $this->$prop);
      }
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all records
    *
    * @param boolean $archive Whether to use archive table
    * @return boolean Query result
    */
   public function deleteAll($archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table);
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
    * Deletes a template by username, year and month
    *
    * @param string $username Username this template is for
    * @param string $year Year of the template (YYYY)
    * @param string $month Month of the template (MM)
    * @return boolean Query result
    */
   public function deleteTemplate($username = '', $year = '', $month = '')
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE username = :val1 AND year = :val2 AND month = :val3');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $year);
      $month = sprintf("%02d", $month);
      $query->bindParam('val3', $month);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all templates before and including a certain year/month
    *
    * @param string $year Year (YYYY)
    * @param string $month Month (MM)
    * @return boolean Query result
    */
   public function deleteBefore($year = '', $month = '')
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE year < :val1 OR (year = :val1 AND month <= :val2)');
      $query->bindParam('val1', $year);
      $month = sprintf("%02d", $month);
      $query->bindParam('val2', $month);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a template by ID
    *
    * @param integer $id ID of record to delete
    * @return boolean Query result
    */
   public function deleteById($id = '')
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all templates for a username
    *
    * @param string $uname Username to delete all records of
    * @param boolean $archive Whether to use archive table
    * @return boolean Query result
    */
   public function deleteByUser($username = '', $archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a record exists
    *
    * @param string $username Username to find
    * @param boolean $archive Whether to use archive table
    * @return boolean True if found, false if not
    */
   public function exists($username = '', $archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      
      if ($result and $query->fetchColumn())
      {
         return true;
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets the absence ID of a given username, year, month and day
    *
    * @param string $username Username to find
    * @param string $year Year to find (YYYY)
    * @param string $month Month to find (MM)
    * @param string $day Day of month to find (D)
    * @return boolean 0 or absence ID
    */
   public function getAbsence($username = '', $year = '', $month = '', $day = '1')
   {
      $query = $this->db->prepare('SELECT abs' . $day . ' FROM ' . $this->table . ' WHERE username = :val1 AND year = :val2 AND month = :val3');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $year);
      $month = sprintf("%02d", $month);
      $query->bindParam('val3', $month);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['abs' . $day];
      }
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gest a template by username, year and month
    *
    * @param string $username Username to find
    * @param string $year Year to find (YYYY)
    * @param string $month Month to find (MM)
    * @return boolean Query result
    */
   public function getTemplate($username = '', $year = '', $month = '')
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username LIKE :val1 AND year = :val2 AND month = :val3');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $year);
      $month = sprintf("%02d", $month);
      $query->bindParam('val3', $month);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         $this->username = $row['username'];
         $this->year = $row['year'];
         $this->month = $row['month'];
         for($i = 1; $i <= 31; $i++)
         {
            $prop = 'abs' . $i;
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
    * Gets a template by ID
    *
    * @param string $id Record ID to find
    * @return boolean Query result
    */
   public function getTemplateById($id = '')
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         $this->username = $row['username'];
         $this->year = $row['year'];
         $this->month = $row['month'];
         for($i = 1; $i <= 31; $i++)
         {
            $prop = 'abs' . $i;
            $this->$prop = $row[$prop];
         }
      }
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a given user has a given absence in a given month
    *
    * @param string $username Username to find
    * @param string $year Year to find (YYYY)
    * @param string $month Month to find (MM)
    * @param string $absid Absence ID to find
    * @return boolean 0 or 1
    */
   public function hasAbsence($username='', $year = '', $month = '', $absid)
   {
      $month = sprintf("%02d", $month);
      
      for ($i=1; $i<=31; $i++)
      {
         $myQuery = 'SELECT username FROM '.$this->table.' WHERE username = "'.$username.'" AND year = "'.$year.'" AND month = "'.$month.'" AND abs'.$i.' = "'.$absid.'";';
         $query = $this->db->prepare($myQuery);
         $result = $query->execute();
         if ($result and $row = $query->fetch())
         {
            return true;
         }
      }
      return false;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Replaces an absence ID in all templates.
    *
    * @param string $symopld Symbol to be replaced
    * @param string $symnew Symbol to replace with
    */
   public function replaceAbsId($absidold, $absidnew)
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
               if ($row['abs' . $i] == $absidold)
               {
                  $prop = 'abs' . $i;
                  $row[$prop] = $absidnew;
                  $stmt .= $prop . ' = "' . $absidnew . '", ';
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
    * Restores all records for a given user
    *
    * @param string $name Username to restore
    * @return boolean Query result
    */
   public function restore($username)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Set an absence for a given username, year, month and day
    *
    * @param string $username Username for update
    * @param string $year Year for update (YYYY)
    * @param string $month Month for update (MM)
    * @param string $day Day for update
    * @param string $abs Absence to set
    * @return boolean Query result
    */
   public function setAbsence($username, $year, $month, $day, $abs)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET abs' . $day . ' = :val1 WHERE username = :val2 AND year = :val3 AND month = :val4');
      $query->bindParam('val1', $abs);
      $query->bindParam('val2', $username);
      $query->bindParam('val3', $year);
      $month = sprintf("%02d", $month);
      $query->bindParam('val4', $month);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates a template for a given username, year and month
    *
    * @param string $uname Username for update
    * @param string $year Year for update (YYYY)
    * @param string $month Month for update (MM)
    * @return boolean Query result
    */
   public function update($uname, $year, $month)
   {
      $stmt = 'UPDATE ' . $this->table . ' SET username = :val1, year = :val2, month = :val3, ';
      for($i = 1; $i <= 31; $i++)
      {
         $prop = 'abs' . $i;
         $stmt .= $prop . ' = ' . $this->$prop . ', ';
      }
      $stmt = substr($stmt, 0, -2);
      $stmt .= ' WHERE username = :val4 AND year = :val5 AND month = :val6';
      
      $query = $this->db->prepare($stmt);
      $query->bindParam('val1', $this->username);
      $query->bindParam('val2', $this->year);
      $query->bindParam('val3', $this->month);
      $query->bindParam('val4', $uname);
      $query->bindParam('val5', $year);
      $month = sprintf("%02d", $month);
      $query->bindParam('val6', $month);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Optimize table
    *
    * @return boolean Query result
    */
   public function optimize()
   {
      $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->table);
      $result = $query->execute();
      $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->archive_table);
      $result = $query->execute();
      return $result;
   }
}
?>
