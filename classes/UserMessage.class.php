<?php
/**
 * UserMessage.class.php
 *
 * @category TeamCal Neo 
 * @version 0.3.004
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the user-message database table
 */
class UserMessage
{
   var $db = '';
   var $table = '';
   var $archive_table = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_user_message'];
      $this->archive_table = $CONF['db_table_archive_user_message'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Archives a user record
    *
    * @param string $username Username to archive
    * @return bool $result Query result
    */
   function archive($username)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Adds a message link for a user
    *
    * @param string $username Username to assign to
    * @param string $msgid Message ID
    * @return bool $result Query result
    */
   function add($username, $msgid, $popup)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, msgid, popup) VALUES (:val1, :val2, :val3)');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $msgid);
      $query->bindParam('val3', $popup);
      $result = $query->execute();
      return $result;
   }

   // ---------------------------------------------------------------------
   /**
    * Checks whether a user has message links
    *
    * @param string $username Username to find
    * @param boolean $archive Whether to search in archive table
    * @return bool True if found, false if not
    */
   function exists($username = '', $archive = FALSE)
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
    * Deletes a message link
    *
    * @param string $id ID of the records to delete
    * @param boolean $archive Whether to search in archive table
    * @return bool $result Query result
    */
   function delete($id, $archive = false)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all records
    *
    * @param boolean $archive Whether to search in archive table
    * @return bool Query result or false
    */
   function deleteAll($archive = FALSE)
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
    * Deletes all message links for a given user
    *
    * @param string $username Username of the records to delete
    * @param boolean $archive Whether to search in archive table
    * @return bool $result Query result
    */
   function deleteByUser($username, $archive = false)
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
    * Gets all message link IDs for a given user
    *
    * @param string $username Username
    * @return array $records Array with all records
    */
   function getAllByUser($username)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT msgid FROM ' . $this->table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
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
    * Gets all message links for a given message ID
    *
    * @param string $msgid Message ID
    * @return array $records Array with all records
    */
   function getAllByMsgId($msgid)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE msgid = :val1');
      $query->bindParam('val1', $msgid);
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
    * Gets all popup message link IDs for a given user
    *
    * @param string $username Username
    * @return array $records Array with all records
    */
   function getAllPopupByUser($username)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT msgid FROM ' . $this->table . ' WHERE username = :val1 AND popup = "1"');
      $query->bindParam('val1', $username);
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
    * Restore arcived user records
    *
    * @param string $username Username to restore
    * @return bool $result Query result
    */
   function restore($username)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Sets a message link to silent
    *
    * @param string $id Record ID
    * @return array $records Array with all records
    */
   function setSilent($id)
   {
      $records = array ();
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET popup = "0" WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Sets all message links for a given username to silent
    *
    * @param string $username Username
    * @return array $records Array with all records
    */
   function setSilentByUser($username)
   {
      $records = array ();
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET popup = "0" WHERE username = :val1');
      $query->bindParam('val1', $username);
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
      $query = $this->db->prepare('OPTIMIZE TABLE ' . $this->archive_table);
      $result = $query->execute();
      return $result;
   }
}
?>
