<?php
/**
 * Permissions.class.php
 *
 * @category TeamCal Neo 
* @version 1.0.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed.
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the permissions database table
 */
class Permissions
{
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
      $this->table = $CONF['db_table_permissions'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Read all unique scheme names
    *
    * @return array Array of scheme names
    */
   public function getSchemes()
   {
      $records = array ();
      $query = $this->db->prepare('SELECT DISTINCT scheme FROM ' . $this->table);
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $records[] = $row['scheme'];
         }
      }
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a scheme exists
    *
    * @param string $scheme Scheme name to look for
    * @return boolean True or false
    */
   public function schemeExists($scheme)
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE scheme = :val1');
      $query->bindParam('val1', $scheme);
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
    * Checks whether a given role has a given permission
    *
    * @param string $scheme Name of the permission scheme
    * @param string $permission Name of the permission
    * @param string $role Role of the permission
    * @return boolean True or False
    */
   public function isAllowed($scheme, $permission, $role)
   {
      $query = $this->db->prepare('SELECT allowed FROM ' . $this->table . ' WHERE scheme = :val1 AND permission = :val2 AND role = :val3' );
      $query->bindParam('val1', $scheme);
      $query->bindParam('val2', $permission);
      $query->bindParam('val3', $role);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         if ($row['allowed']) return true; else return false;
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Update/create a permission
    *
    * @param string $scheme Name of the permission scheme
    * @param string $permission Name of the permission
    * @param string $role Role of the permission
    * @param bool $allowed True or False
    * @return boolean Query result
    */
   public function setPermission($scheme, $permission, $role, $allowed)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE scheme = :val1 AND permission = :val2 AND role = :val3');
      $query->bindParam('val1', $scheme);
      $query->bindParam('val2', $permission);
      $query->bindParam('val3', $role);
      $result = $query->execute();
      
      if ($result)
      {
         if (!$row = $query->fetch())
         {
            $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (scheme, permission, role, allowed) VALUES (:val1, :val2, :val3, :val4)');
         }
         else
         {
            $query2 = $this->db->prepare('UPDATE ' . $this->table . ' SET allowed = :val4 WHERE scheme = :val1 AND permission = :val2 AND role = :val3');
         }
         $query2->bindParam('val1', $scheme);
         $query2->bindParam('val2', $permission);
         $query2->bindParam('val3', $role);
         $query2->bindParam('val4', $allowed);
         $result2 = $query2->execute();
         return $result2;
      }
      else
      {
         return $result;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Delete a role from all permission schemes
    *
    * @param integer $role Role ID
    * @return boolean Query result
    */
   public function deleteRole($role)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE role = :val1');
      $query->bindParam('val1', $role);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Delete a permission scheme
    *
    * @param string $scheme Name of the permission scheme
    * @return boolean Query result
    */
   public function deleteScheme($scheme)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE scheme = :val1');
      $query->bindParam('val1', $scheme);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Delete all records (except default)
    *
    * @return boolean Query result
    */
   public function deleteAll()
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE scheme <> "Default"');
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
