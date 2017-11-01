<?php
/**
 * UserAttachment.class.php
 *
 * @category TeamCal Neo 
 * @version 1.8.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides objects and methods to manage the user-option table
 */
class UserAttachment
{
   public $id = NULL;
   public $username = '';
   public $fileid = NULL;
   
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
      $this->db = $DB->db;
      $this->table = $CONF['db_table_user_attachment'];
      $this->archive_table = $CONF['db_table_archive_user_attachment'];
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
    * Creates a new record
    *
    * @param string $username Username
    * @param string $fileid File ID
    * @return boolean Query result
    */
   public function create($username, $fileid)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, fileid) VALUES (:val1, :val2)');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $fileid);
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
      
      $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username <> "admin"');
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all records for a given username
    *
    * @param string $username Username to delete
    * @param boolean $archive Whether to use archive table
    * @return boolean Query result
    */
   public function deleteUser($username = '', $archive = FALSE)
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
    * Delete all records for a given file ID
    *
    * @param string $fileid File ID
    * @return boolean Query result
    */
   public function deleteFile($fileid)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE fileid = :val1');
      $query->bindParam('val1', $fileid);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a user has access to a file
    *
    * @param string $username Username to find
    * @param string $fileid File ID to find
    * @return string TRUE if exists, FALSE if not
    */
   public function hasAccess($username, $fileid)
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :val1 AND fileid = :val2');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $fileid);
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
    * Optimize table
    *
    * @return boolean $result Query result
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
