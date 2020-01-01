<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * AbsenceGroup
 *
 * This class provides methods and properties for attachments.
 * 
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2020 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Attachments
 * @since 3.0.0
 */
class Attachment
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
      $this->table = $CONF['db_table_attachments'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a record
    *
    * @param string $filename File name
    * @param string $uploader Uploader username
    * @return boolean Query result
    */
   public function create($filename, $uploader)
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
    * @return boolean Query result
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
    * Deletes a record by filetype/filename
    *
    * @param string $filename File name to delete
    * @return boolean Query result
    */
   public function delete($filename)
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
    * @return boolean Query result
    */
   public function deleteById($id)
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
    * @return array Array with records
    */
   public function getAll()
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
    * @param string $filename File name to find
    * @return string Record ID
    */
   public function getId($filename)
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
    * @param string $filename File name to find
    * @return string Uploader
    */
   public function getUploader($filename)
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
    * Gets the uploader of a given file
    *
    * @param string $filename File name to find
    * @return string Uploader
    */
   public function getUploaderById($fileid)
   {
      $query = $this->db->prepare('SELECT uploader FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $fileid);
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
    * @return boolean Query result
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
