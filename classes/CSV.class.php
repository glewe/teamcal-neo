<?php
/**
 * CSV.class.php
 * 
 * @category TeamCal Neo 
 * @version 0.5.004
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to deal with CSV parsing
 */
class CSV
{
   var $headrow;
   var $body;
   
   // ---------------------------------------------------------------------
   /**
    * Class constructor
    */
   function __construct()
   {
      $this->headrow = "";
      $this->body = "";
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates the CSV header line with the field names
    *
    * @param integer $rows MySQL query result (contains the number of fields)
    */
   function addHeadrow($rows)
   {
      $out = '';
      for($i = 0; $i < mysql_num_fields($rows); $i++)
      {
         $meta = mysql_fetch_field($rows, $i);
         $out .= "\"" . $meta->name . "\";";
      }
      $out = substr_replace($out, ";", strlen($out) - 1, 1);
      $out .= "\n";
      $this->body = $this->body . $out;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Writes each field value of a row
    *
    * @param integer $rows MySQL query result
    * @param array $row Array of field values
    */
   function addElement($row, $rows)
   {
      $out = '';
      for($i = 0; $i < mysql_num_fields($rows); $i++)
      {
         $meta = mysql_fetch_field($rows, $i);
         if ($meta->name == "password")
         {
            $out .= "\"********\";";
         }
         else
         {
            if (strlen($row[$i])) $out .= "\"" . $row[$i] . "\";";
            else $out .= ";";
         }
      }
      $out = substr_replace($out, ";", strlen($out) - 1, 1);
      $out .= "\n";
      $this->body = $this->body . $out;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Returns the CSV text
    *
    * @return string CSV text (header row and body)
    */
   function getCSVDocument()
   {
      return $this->headrow . $this->body;
   }
}
?>
