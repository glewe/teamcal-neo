<?php
/**
 * Permissions.class.php
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
 * Provides properties and methods to interface with the permissions database table
 */
class Permissions
{
   var $db = '';
   var $table = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_permissions'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Read all unique scheme names
    *
    * @return array $records Array containing the scheme names
    */
   function getSchemes()
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
    * @return bool True if yes, false if no
    */
   function schemeExists($scheme)
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
    * Read the value of a permission
    *
    * @param string $scheme Name of the permission scheme
    * @param string $permission Name of the permission
    * @param string $role Role of the permission
    * @return boolean True or False
    */
   function isAllowed($scheme, $permission, $role)
   {
      $query = $this->db->prepare('SELECT allowed FROM ' . $this->table . ' WHERE scheme = :val1 AND permission = :val2 AND role = :val3' );
      $query->bindParam('val1', $scheme);
      $query->bindParam('val2', $permission);
      $query->bindParam('val3', $role);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         if ($row['allowed']) return true;
         else return false;
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
    * @return bool $result Query result
    */
   function setPermission($scheme, $permission, $role, $allowed)
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
            $query2->bindParam('val1', $scheme);
            $query2->bindParam('val2', $permission);
            $query2->bindParam('val3', $role);
            $query2->bindParam('val4', $allowed);
         }
         else
         {
            $query2 = $this->db->prepare('UPDATE ' . $this->table . ' SET allowed = :val1 WHERE scheme = :val2 AND permission = :val3 AND role = :val4');
            $query2->bindParam('val1', $allowed);
            $query2->bindParam('val2', $scheme);
            $query2->bindParam('val3', $permission);
            $query2->bindParam('val4', $role);
         }
         
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
    * @return integer Query result, or 0 if query not successful
    */
   function deleteRole($role)
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
    * @return integer Query result, or 0 if query not successful
    */
   function deleteScheme($scheme)
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
    * @return integer Query result, or 0 if query not successful
    */
   function deleteAll()
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE scheme <> "Default"');
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
