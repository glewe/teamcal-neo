<?php
/**
 * DB.class.php
 * 
 * @category TeamCal Neo 
 * @version 2.2.3
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed.
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to deal with the database
 */
class DB
{
   public $db;
   
   // ---------------------------------------------------------------------
   /**
    * Class constructor
    * 
    * @param string $server Server address
    * @param string $database Database name
    * @param string $user Database username
    * @param string $password Password
    */
   public function __construct($server, $database, $user, $password)
   {
      /**
       * Connect to database
       */
      try
      {
         $this->db = new PDO('mysql:host=' . $server . ';dbname=' . $database . ';charset=utf8', $user, $password);
      } catch ( PDOException $e )
      {
         /**
          * Database connection error
          */
         $errorData['title'] = 'Application Error';
         $errorData['subject'] = 'Database connection error.';
         $errorData['text'] = $e->getMessage();
         require (WEBSITE_ROOT . '/views/error.php');
         die();
      }
      
      /**
       * Database settings
       */
      $query = $this->db->prepare('SET SQL_BIG_SELECTS=1');
      $result = $query->execute();
   }
   
   // ---------------------------------------------------------------------
   /**
    * Optimize tables
    */
   public function optimizeTables()
   {
      $tables = array();
      
      $query = $this->db->prepare('SHOW TABLES');
      $result = $query->execute();
      
      while ($result and $row = $query->fetch())
      {
         $tables[] = $row[0];
      }

      foreach ($tables as $table)
      {
         $query = $this->db->prepare('OPTIMIZE TABLE ' . $table);
         $result = $query->execute();
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Run query
    * 
    * @param string $myQyery MySQL query
    */
   public function runQuery($myQuery)
   {
      $query = $this->db->prepare($myQuery);
      $result = $query->execute();
      
      if ($result)
      {
         return true;
      }
      else 
      {
         return false;
      }
   }
}
?>
