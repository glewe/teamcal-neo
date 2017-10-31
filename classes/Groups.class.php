<?php
/**
 * Groups.class.php
 *
 * @category TeamCal Neo 
 * @version 1.8.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed.
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the groups database table
 */
class Groups
{
   public $id = '';
   public $name = '';
   public $description = '';
   public $minpresent = '';
   public $maxabsent = '';
   
   private $db = '';
   private $table = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   public function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_groups'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a new group record from local class variables
    *
    * @return boolean $result Query result
    */
   public function create()
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (name, description, minpresent, maxabsent) VALUES (:val1, :val2, :val3, :val4)');
      $query->bindParam('val1', $this->name);
      $query->bindParam('val2', $this->description);
      $query->bindParam('val3', $this->minpresent);
      $query->bindParam('val4', $this->maxabsent);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a record by ID
    *
    * @param string $id Record ID to delete
    * @return boolean $result Query result
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
    * Deletes all records
    *
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
    * Gets all group records into an array
    *
    * @param string $sort Sort direction (ASC or DESC)
    * @return array Array with all group records
    */
   public function getAll($sort='ASC')
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY name '. $sort);
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
    * Gets all records into an array where likeness is found in group name
    * or description
    *
    * @param string $like Likeness to search for
    * @return array Array with all records
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
    * Gets all group names into an array
    *
    * @return array Array with all group names
    */
   public function getAllNames()
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY name ASC');
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
    * Gets a group record for a given ID
    *
    * @param string $id Group ID to find
    * @return boolean True or false
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
          $this->minpresent = $row['minpresent'];
          $this->maxabsent = $row['maxabsent'];
          return true;
       }
       else 
       {
          return false;
       }
    }
    
   // ---------------------------------------------------------------------
   /**
    * Gets a group record for a given group name
    *
    * @param string $name Group name to find
    * @return boolean True or false
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
          $this->minpresent = $row['minpresent'];
          $this->maxabsent = $row['maxabsent'];
          return true;
       }
       else 
       {
          return false;
       }
    }
    
   // ---------------------------------------------------------------------
   /**
    * Gets the maximum absent value for group
    *
    * @param string $id ID to find
    * @return string Minimum present value
    */
    public function getMaxAbsent($id)
    {
       $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
       $query->bindParam('val1', $id);
       $result = $query->execute();
       
       if ($result and $row = $query->fetch())
       {
          return $row['maxabsent'];
       }
       else 
       {
          return 0;
       }
    }
    
   // ---------------------------------------------------------------------
   /**
    * Gets the maximum absent value for group
    *
    * @param string $id ID to find
    * @return string Maximum absent value
    */
    public function getMinPresent($id)
    {
       $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
       $query->bindParam('val1', $id);
       $result = $query->execute();
       
       if ($result and $row = $query->fetch())
       {
          return $row['minpresent'];
       }
       else 
       {
          return 0;
       }
    }
    
   // ---------------------------------------------------------------------
   /**
    * Gets a group name for a given ID
    *
    * @param string $id ID to find
    * @return string Group name (or false if not found)
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
    * Gets a group record for a given ID
    *
    * @param string $id Group ID to find
    * @return boolean True or false
    */
    public function getRowById($id)
    {
       $records = array ();
       $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
       $query->bindParam('val1', $id);
       $result = $query->execute();
       
       if ($result and $row = $query->fetch())
       {
          $records[] = $row;
          return $records;
       }
       else
       {
          return false;
       }
    }
    
   // ---------------------------------------------------------------------
   /**
    * Updates a record for a given ID
    *
    * @param string $name Record ID
    * @return boolean Query result
    */
   public function update($id)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET name = :val1, description = :val2, minpresent = :val3, maxabsent = :val4 WHERE id = :val5');
      $query->bindParam('val1', $this->name);
      $query->bindParam('val2', $this->description);
      $query->bindParam('val3', $this->minpresent);
      $query->bindParam('val4', $this->maxabsent);
      $query->bindParam('val5', $id);
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
