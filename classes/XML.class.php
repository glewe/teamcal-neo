<?php
/**
 * XML.class.php
 *
 * @category TeamCal Neo 
 * @version 0.3.00
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit ('No direct access allowed!');

/** 
 * Provides properties and methods to deal with XML files
 */
class XML 
{
   var $header;
   var $startTag;
   var $endTag;
   var $body;

   // ---------------------------------------------------------------------
   /** 
    * Constructor. Creates the start and end tag.
    * 
    * @param string $tablename Name of MySQL table to parse
    */
   function __construct($tablename) 
   {
      /* $this->header="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; */
      $this->startTag = "<Table Name=\"".$tablename."\">";
      $this->endTag = "</Table>\n";
   }

   // ---------------------------------------------------------------------
   /** 
    * Adds an element to the XML ouput based on a given MySQL query result handle
    * 
    * @param array $row Single MySQL query result row
    * @param integer MySQL query result handle
    */
   function addElement($row, $rows) 
   {
      $out = "\t<DataRow>\n";
      for ($i = 0; $i < mysql_num_fields($rows); $i++) 
      {
         $meta = mysql_fetch_field($rows, $i);
         if ($meta->name == "password") 
         {
            $out = $out."\t\t<DataField Name=\"".$meta->name."\" Type=\"".$meta->type."\">********</DataField>\n";
         }
         else 
         {
            $out = $out."\t\t<DataField Name=\"".$meta->name."\" Type=\"".$meta->type."\">".htmlspecialchars($row[$i])."</DataField>\n";
         }
      }
      $out = $out."\t</DataRow>\n";
      $this->body = $this->body.$out;
   }

   // ---------------------------------------------------------------------
   /** 
    * Returns the XML text
    * 
    * @return string XML text
    */
   function getXMLDocument() 
   {
      return $this->header.$this->startTag."\n".$this->body.$this->endTag;
   }
}
?>
