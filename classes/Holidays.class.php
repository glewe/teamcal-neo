<?php
/**
 * Holildays.class.php
 *
 * @category TeamCal Neo 
 * @version 0.3.003
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides objects and methods to interface with the holiday table
 */
class Holidays
{
   var $db = NULL;
   var $table = '';
   var $id = 0;
   var $name = '';
   var $description = '';
   var $color = '000000';
   var $bgcolor = 'ffffff';
   var $businessday = 0;
   
   // ----------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_holidays'];
   }
   
   // ----------------------------------------------------------------------
   /**
    * Creates a holiday record
    *
    * @return bool $result Query result
    */
   function create()
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (name, description, color, bgcolor, businessday) VALUES (:val1, :val2, :val3, :val4, :val5)');
      $query->bindParam('val1', $this->name);
      $query->bindParam('val2', $this->description);
      $query->bindParam('val3', $this->color);
      $query->bindParam('val4', $this->bgcolor);
      $query->bindParam('val5', $this->businessday);
      $result = $query->execute();
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Deletes a holiday record
    *
    * @param string $id Record ID
    * @return bool $result Query result
    */
   function delete($id = '')
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
    * @return bool $result Query result
    */
   function deleteAll()
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
    * @return bool $result Query result
    */
   function get($id = '')
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
         }
      }
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Reads all records into an array. Only true holidays are selected (id > 3)
    *
    * @param string $sort What to sort by
    * @return array $records Array with all records
    */
   function getAll($sort='name')
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
    * Reads all records into an array. Only true holidays are selected (id > 3)
    *
    * @param string $sort What to sort by
    * @return array $records Array with all records
    */
   function getAllCustom($sort='name')
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
    * @return bool 0 or 1
    */
   function isBusinessDay($id = '')
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
    * Gets the name of a Holiday
    *
    * @param string $id Record ID
    * @return string Holiday name
    */
   function getName($id = '')
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
   function getDescription($id = '')
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
   function getColor($id = '')
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
   function getBgColor($id = '')
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
   function getLastId()
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
   function getNextId()
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
    * @return bool $result Query result
    */
   function update($id = '')
   {
      $result = 0;
      if (isset($id))
      {
         $query = $this->db->prepare('UPDATE ' . $this->table . ' SET name = :val1, description = :val2, color = :val3, bgcolor = :val4, businessday = :val5 WHERE id = :val6');
         $query->bindParam('val1', $this->name);
         $query->bindParam('val2', $this->description);
         $query->bindParam('val3', $this->color);
         $query->bindParam('val4', $this->bgcolor);
         $query->bindParam('val5', $this->businessday);
         $query->bindParam('val6', $id);
         $result = $query->execute();
      }
      return $result;
   }
   
   // ----------------------------------------------------------------------
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
