<?php
/**
 * Roles.class.php
 *
 * @category TeamCal Neo 
 * @version 1.3.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed.
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the role table
 */
class Roles
{
   public $id = '';
   public $name = '';
   public $description = '';
   public $color = '';
   
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
      $this->table = $CONF['db_table_roles'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a new role record from local class variables
    *
    * @return boolean Query result
    */
   public function create()
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (name, description, color) VALUES (:val1, :val2, :val3)');
      $query->bindParam('val1', $this->name);
      $query->bindParam('val2', $this->description);
      $query->bindParam('val3', $this->color);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a record by ID
    *
    * @param string $id ID to delete
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
    * Deletes all records
    *
    * @return boolean Query result
    */
   public function deleteAll()
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
    * Gets all records
    *
    * @return array Array with all role records
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
    * Gets all records with a likeness i name or description
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
    * Gets all role names
    *
    * @return array Array with all role names
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
    * Gets a record by ID
    *
    * @param string $id Role ID to find
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
         $this->color = $row['color'];
         return true;
      }
      else 
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets a role record for a given role name
    *
    * @param string $name Role name to find
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
         $this->color = $row['color'];
         return true;
      }
      else 
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets the role color by ID
    *
    * @param string $id Role ID to find
    * @return string Color
    */
   public function getColorById($id)
   {
      $query = $this->db->prepare('SELECT color FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['color'];
      }
      else 
      {
         return "default";
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets the role color by name
    *
    * @param string $name Role name to find
    * @return string Role color
    */
   public function getColorByName($name)
   {
      $query = $this->db->prepare('SELECT color FROM ' . $this->table . ' WHERE name = :val1');
      $query->bindParam('val1', $name);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['color'];
      }
      else 
      {
         return "default";
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets a role name for a given ID
    *
    * @param string $id Role ID to find
    * @return string Role name
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
    * Updates a record for a given ID
    *
    * @param string $name Role ID to update
    * @return boolean Query result
    */
   public function update($id)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET name = :val1, description = :val2, color = :val3 WHERE id = :val4');
      $query->bindParam('val1', $this->name);
      $query->bindParam('val2', $this->description);
      $query->bindParam('val3', $this->color);
      $query->bindParam('val4', $id);
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
