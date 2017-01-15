<?php
/**
 * Regions.class.php
 *
 * @category TeamCal Neo 
 * @version 1.3.004
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the region table
 */
class Regions
{
   public $id = '';
   public $name = '';
   public $description = '';
   
   public $roleid = '';
   public $regionid = '';
   public $access = '';
   
   private $db = '';
   private $accessTable = '';
   private $table = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   public function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_regions'];
      $this->accessTable = $CONF['db_table_region_role'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a region record
    *
    * @return boolean Query result
    */
   public function create()
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (name, description) VALUES (:val1, :val2)');
      $query->bindParam('val1', $this->name);
      $query->bindParam('val2', $this->description);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a region record
    *
    * @param string $id Region ID
    * @return boolean Query result
    */
   public function delete($id)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      return $result;
   }

   // ---------------------------------------------------------------------
   /**
    * Deletes a region from access table
    *
    * @param string $id Region ID
    * @return boolean Query result
    */
   public function deleteAccess($id)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->accessTable . ' WHERE regionid = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      return $result;
   }

   // ---------------------------------------------------------------------
   /**
    * Deletes all records
    *
    * @param boolean $archive Whether to use the archive table
    * @return boolean Query result or false
    */
   public function deleteAll()
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
    * Gets the region_role access
    *
    * @param integer $id Region ID
    * @param integer $roleid Role ID
    * @return string Access type
    */
   public function getAccess($id, $roleid)
   {
      $query = $this->db->prepare('SELECT access FROM ' . $this->accessTable . ' WHERE regionid = :val1 AND roleid = :val2');
      $query->bindParam('val1', $id);
      $query->bindParam('val2', $roleid);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['access'];
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
    * @return array Array with records
    */
   public function getAll()
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
    * Gets all records with likeness in name or description
    *
    * @param string $like Likeness to search for
    * @return array Array with records
    */
   public function getAllLike($like)
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
    * @return array Array with all region names
    */
   public function getAllNames()
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
    * @param string $id Region ID to find
    * @return boolean Query result
    */
   public function getById($id)
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
    * Gets a record by region name
    *
    * @param string $name Region name to find
    * @return boolean Query result
    */
   public function getByName($name)
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
    * Gets the region ID for a given name
    *
    * @param string $name Region name to find
    * @return string Record ID
    */
   public function getId($name)
   {
      $query = $this->db->prepare('SELECT id FROM ' . $this->table . ' WHERE name = :val1');
      $query->bindParam('val1', $name);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['id'];
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
    * @param string $id Region ID to find
    * @return boolean Query result
    */
   public function getNameById($id)
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
    * Creates a region_role record
    *
    * @return boolean Query result
    */
   public function setAccess($id, $roleid, $access)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->accessTable . ' (regionid, roleid, access) VALUES (:val1, :val2, :val3)');
      $query->bindParam('val1', $id);
      $query->bindParam('val2', $roleid);
      $query->bindParam('val3', $access);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates a region record for a given region ID
    *
    * @param string $name Region ID to update
    * @return boolean Query result
    */
   public function update($id)
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
