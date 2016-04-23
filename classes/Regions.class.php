<?php
/**
 * Regions.class.php
 *
 * @category TeamCal Neo 
 * @version 0.5.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the region table
 */
class Regions
{
   var $db = '';
   var $table = '';
   var $id = '';
   var $name = '';
   var $description = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_regions'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a new region record from local class variables
    *
    * @return bool $result Query result
    */
   function create()
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (name, description) VALUES (:val1, :val2)');
      $query->bindParam('val1', $this->name);
      $query->bindParam('val2', $this->description);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a region record for a given region name
    *
    * @param string $id Role ID to delete
    * @return bool $result Query result
    */
   function delete($id)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      return $result;
   }

   // ---------------------------------------------------------------------
   /**
    * Deletes all records
    *
    * @param boolean $archive Whether to use the archive table
    * @return bool Query result or false
    */
   function deleteAll()
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table);
      $result = $query->execute();
       
      if ($result and $query->fetchColumn())
      {
         $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
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
    * Reads all region records into an array
    *
    * @param boolean $excludeHidden If TRUE, exclude hidden regions
    * @return array $records Array with all region records
    */
   function getAll()
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY name ASC');
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $records[] = $row;
         }
      }
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Reads all records into an array where regionname is like specified
    *
    * @param string $like Likeness to search for
    * @return array $records Array with all records
    */
   function getAllLike($like)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE name LIKE :val1 OR description LIKE :val1 ORDER BY name ASC');
      $val1 = '%' . $like . '%';
      $query->bindParam('val1', $val1);
      $result = $query->execute();
       
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $records[] = $row;
         }
      }
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Reads all region names into an array
    *
    * @return array $records Array with all region names
    */
   function getAllNames()
   {
      $records = array ();
      $query = $this->db->prepare('SELECT name FROM ' . $this->table . ' ORDER BY name ASC');
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $records[] = $row['name'];
         }
      }
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets a region record for a given ID
    *
    * @param string $id Role ID to find
    * @return bool $result Query result
    */
   function getById($id)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         $this->id = $row['id'];
         $this->name = $row['name'];
         $this->description = $row['description'];
         return true;
      }
      else 
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Finds a region record for a given region name and loads values in local class variables
    *
    * @param string $name Role name to find
    * @return bool $result Query result
    */
   function getByName($name)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE name = :val1');
      $query->bindParam('val1', $name);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         $this->id = $row['id'];
         $this->name = $row['name'];
         $this->description = $row['description'];
         return true;
      }
      else 
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets a region name for a given ID
    *
    * @param string $id Role ID to find
    * @return bool $result Query result
    */
   function getNameById($id)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['name'];
      }
      else 
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates a region record for a given region ID
    *
    * @param string $name Role ID to update
    * @return bool $result Query result
    */
   function update($id)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET name = :val1, description = :val2 WHERE id = :val3');
      $query->bindParam('val1', $this->name);
      $query->bindParam('val2', $this->description);
      $query->bindParam('val3', $id);
      $result = $query->execute();
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
