<?php
/**
 * Holildays.class.php
 *
 * @category TeamCal Neo 
 * @version 2.1.0
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides objects and methods to interface with the holiday table
 */
class Holidays
{
   public $id = 0;
   public $name = '';
   public $description = '';
   public $color = '000000';
   public $bgcolor = 'ffffff';
   public $businessday = 0;
   public $noabsence = 0;
   public $keepweekendcolor = 0;
   
   private $db = NULL;
   private $table = '';
   
   // ----------------------------------------------------------------------
   /**
    * Constructor
    */
   public function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_holidays'];
   }
   
   // ----------------------------------------------------------------------
   /**
    * Creates a holiday record
    *
    * @return boolean Query result
    */
   public function create()
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (name, description, color, bgcolor, businessday, noabsence, keepweekendcolor) VALUES (:val1, :val2, :val3, :val4, :val5, :val6, :val7)');
      $query->bindParam('val1', $this->name);
      $query->bindParam('val2', $this->description);
      $query->bindParam('val3', $this->color);
      $query->bindParam('val4', $this->bgcolor);
      $query->bindParam('val5', $this->businessday);
      $query->bindParam('val6', $this->noabsence);
      $query->bindParam('val7', $this->keepweekendcolor);
      $result = $query->execute();
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Deletes a holiday record
    *
    * @param string $id Record ID
    * @return boolean Query result
    */
   public function delete($id = '')
   {
      $result = 0;
      if (isset($id))
      {
         $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
      }
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Deletes all records (except Business day, Saturday, Sunday)
    *
    * @return boolean Query result
    */
   public function deleteAll()
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id > "3"');
      $result = $query->execute();
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets a holiday record
    *
    * @param string $id Record ID
    * @return boolean Query result
    */
   public function get($id = '')
   {
      $result = 0;
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->color = $row['color'];
            $this->bgcolor = $row['bgcolor'];
            $this->businessday = $row['businessday'];
            $this->noabsence = $row['noabsence'];
            $this->keepweekendcolor = $row['keepweekendcolor'];
         }
      }
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets a holiday record
    *
    * @param string $name Holiday name
    * @return boolean Query result
    */
   public function getByName($name = '')
   {
      $result = 0;
      if (isset($name))
      {
         $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE name = :val1');
         $query->bindParam('val1', $name);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->color = $row['color'];
            $this->bgcolor = $row['bgcolor'];
            $this->businessday = $row['businessday'];
            $this->noabsence = $row['noabsence'];
         }
         else 
         {
            return false;
         }
      }
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Reads all records into an array. Only true holidays are selected (id > 3)
    *
    * @param string $sort What to sort by
    * @return array Array with records
    */
   public function getAll($sort='name')
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY ' . $sort . ' ASC');
      $result = $query->execute();
      while ( $row = $query->fetch() )
         $records[] = $row;
      return $records;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets all custom records. Only true holidays are selected (id > 3)
    *
    * @param string $sort What to sort by
    * @return array Array with records
    */
   public function getAllCustom($sort='name')
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id > 3 ORDER BY ' . $sort . ' ASC');
      $result = $query->execute();
      while ( $row = $query->fetch() )
         $records[] = $row;
      return $records;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Checks whether the given holiday counts as business day
    *
    * @param string $id Record ID
    * @return boolean True or false
    */
   public function isBusinessDay($id = '')
   {
      $rc = 0;
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT businessday FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['businessday'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Checks whether the given holiday shall not overwrite weekend coloring
    *
    * @param string $id Record ID
    * @return boolean True or false
    */
   public function keepWeekendColor($id = '')
   {
      $rc = 0;
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT keepweekendcolor FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['keepweekendcolor'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Checks whether the given holiday allows no absence
    *
    * @param string $id Record ID
    * @return boolean True or false
    */
   public function noAbsenceAllowed($id = '')
   {
      $rc = 0;
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT noabsence FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['noabsence'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the name of a Holiday
    *
    * @param string $id Record ID
    * @return string Holiday name
    */
   public function getName($id = '')
   {
      $rc = 'unknown';
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT name FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['name'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the description of a holiday
    *
    * @param string $id Record ID
    * @return string Holiday description
    */
   public function getDescription($id = '')
   {
      $rc = '.';
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT description FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['description'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the color of a holiday
    *
    * @param string $id Record ID
    * @return string Holiday color
    */
   public function getColor($id = '')
   {
      $rc = '000000';
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT color FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['color'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the background color of a holiday
    *
    * @param string $id Record ID
    * @return string Holiday background color
    */
   public function getBgColor($id = '')
   {
      $rc = '000000';
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT bgcolor FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['bgcolor'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the last auto-increment ID
    *
    * @return string Last auto-increment ID
    */
   public function getLastId()
   {
      $query = $this->db->prepare('SHOW TABLE STATUS LIKE ' . $this->table);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return intval($row['Auto_increment']) - 1;
      }
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the next auto-increment ID
    *
    * @return string Next auto-increment ID
    */
   public function getNextId()
   {
      $query = $this->db->prepare('SHOW TABLE STATUS LIKE ' . $this->table);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['Auto_increment'];
      }
   }
   
   // ----------------------------------------------------------------------
   /**
    * Updates a holiday
    *
    * @param string $id Record ID
    * @return boolean Query result
    */
   public function update($id = '')
   {
      $result = 0;
      if (isset($id))
      {
         $query = $this->db->prepare('UPDATE ' . $this->table . ' SET name = :val1, description = :val2, color = :val3, bgcolor = :val4, businessday = :val5, noabsence = :val6, keepweekendcolor = :val7 WHERE id = :val8');
         $query->bindParam('val1', $this->name);
         $query->bindParam('val2', $this->description);
         $query->bindParam('val3', $this->color);
         $query->bindParam('val4', $this->bgcolor);
         $query->bindParam('val5', $this->businessday);
         $query->bindParam('val6', $this->noabsence);
         $query->bindParam('val7', $this->keepweekendcolor);
         $query->bindParam('val8', $id);
         $result = $query->execute();
      }
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Optimize table
    *
    * @return boolean Query result
    */
   public function optimize()
   {
      $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->table);
      $result = $query->execute();
      return $result;
   }
}
?>
