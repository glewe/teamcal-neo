<?php
/**
 * Attachment.class.php
 *
 * @category TeamCal Neo 
 * @version 0.9.004
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides objects and methods to manage the user-option table
 */
class Attachment
{
   var $db = '';
   var $filename = '';
   var $uploader = '';
    
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_attachments'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a record
    *
    * @param string $filetype File type
    * @param string $filename File name
    * @return bool Query result
    */
   function create($filename, $uploader)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (`filename`, `uploader`) VALUES (:val1, :val2)');
      $query->bindParam('val1', $filename);
      $query->bindParam('val2', $uploader);
      $result = $query->execute();
      if ($result)
      {
         return $this->db->lastInsertId();
      }
      else 
      {
         return $result;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all records
    *
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
    * Deletes a record by filetype/filename
    *
    * @param string $filetype File type to delete
    * @param string $filename File name to delete
    * @return bool Query result or false
    */
   function delete($filename)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE filename = :val1');
      $query->bindParam('val1', $filename);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a record by ID
    *
    * @return bool Query result or false
    */
   function deleteById($id)
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Reads all records into an array
    *
    * @return array $records Array with all region records
    */
   function getAll()
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY filename ASC');
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
    * Gets the record ID of a given file
    *
    * @param string $filetype File type to find
    * @param string $filename File name to find
    * @return bool $result Query result
    */
   function getId($filename)
   {
      $query = $this->db->prepare('SELECT id FROM ' . $this->table . ' WHERE filename = :val1');
      $query->bindParam('val1', $filename);
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
    * Gets the uploader of a given file
    *
    * @param string $filetype File type to find
    * @param string $filename File name to find
    * @return bool $result Query result
    */
   function getUploader($filename)
   {
      $query = $this->db->prepare('SELECT uploader FROM ' . $this->table . ' WHERE filename = :val1');
      $query->bindParam('val1', $filename);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['uploader'];
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
