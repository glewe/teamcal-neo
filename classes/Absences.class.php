<?php
/**
 * Roles.class.php
 *
 * @category TeamCal Neo 
 * @version 0.6.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the role table
 */
class Absences
{
   var $db = NULL;
   var $table = '';
   var $id = 0;
   var $name = '';
   var $symbol = '';
   var $icon = 'No';
   var $color = '000000';
   var $bgcolor = 'ffffff';
   var $bgtrans = 0;
   var $factor = 1;
   var $allowance = '0';
   var $counts_as = 0;
   var $show_in_remainder = 1;
   var $show_totals = 1;
   var $approval_required = 0;
   var $counts_as_present = 0;
   var $manager_only = 0;
   var $hide_in_profile = 0;
   var $confidential = 0;
   
   // ----------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_absences'];
   }
   
   // ----------------------------------------------------------------------
   /**
    * Creates an absence type record
    *
    * @return bool $result Query result
    */
   function create()
   {
      $query = $this->db->prepare(
            'INSERT INTO ' . $this->table . 
            ' (name, symbol, icon, color, bgcolor, bgtrans, factor, allowance, counts_as, show_in_remainder, show_totals, approval_required, counts_as_present, manager_only, hide_in_profile, confidential)'.
            ' VALUES (:val1, :val2, :val3, :val4, :val5, :val6, :val7, :val8, :val9, :val10, :val11, :val12, :val13, :val14, :val15, :val16)');
      $query->bindParam('val1', $this->name);
      $query->bindParam('val2', $this->symbol);
      $query->bindParam('val3', $this->icon);
      $query->bindParam('val4', $this->color);
      $query->bindParam('val5', $this->bgcolor);
      $query->bindParam('val6', $this->bgtrans);
      $query->bindParam('val7', $this->factor);
      $query->bindParam('val8', $this->allowance);
      $query->bindParam('val9', $this->counts_as);
      $query->bindParam('val10', $this->show_in_remainder);
      $query->bindParam('val11', $this->show_totals);
      $query->bindParam('val12', $this->approval_required);
      $query->bindParam('val13', $this->counts_as_present);
      $query->bindParam('val14', $this->manager_only);
      $query->bindParam('val15', $this->hide_in_profile);
      $query->bindParam('val16', $this->confidential);
      $result = $query->execute();
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Deletes an absence type record
    *
    * @param string $id Record ID
    * @return bool $result Query result
    */
   function delete($id = '')
   {
      $result = 0;
      if (isset($id))
      {
         $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
      }
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Deletes all absence type records
    *
    * @return bool $result Query result
    */
   function deleteAll()
   {
      $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
      $result = $query->execute();
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets an absence type record
    *
    * @param string $id Record ID
    * @return bool $result Query result
    */
   function get($id = '')
   {
      $result = 0;
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->symbol = $row['symbol'];
            $this->icon = $row['icon'];
            $this->color = $row['color'];
            $this->bgcolor = $row['bgcolor'];
            $this->bgtrans = $row['bgtrans'];
            $this->factor = $row['factor'];
            $this->allowance = $row['allowance'];
            $this->counts_as = $row['counts_as'];
            $this->show_in_remainder = $row['show_in_remainder'];
            $this->show_totals = $row['show_totals'];
            $this->approval_required = $row['approval_required'];
            $this->counts_as_present = $row['counts_as_present'];
            $this->manager_only = $row['manager_only'];
            $this->hide_in_profile = $row['hide_in_profile'];
            $this->confidential = $row['confidential'];
         }
      }
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Reads all records into an array
    *
    * @return array $records Array with all records
    */
   function getAll()
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY name ASC');
      $result = $query->execute();
      if ($result)
      {
         while ( $row = $query->fetch() ) $records[] = $row;
      }
      return $records;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Reads all absence types counting as the given ID
    *
    * @param string $id ID to search for
    * @return array $records Array with all records
    */
   function getAllSub($id)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE counts_as = :val1 ORDER BY name ASC');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      while ( $row = $query->fetch() )
         $records[] = $row;
      return $records;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets all primary absences (not counts_as) types but the one with the given ID
    *
    * @param string $id ID to skip
    * @return array $records Array with all records
    */
   function getAllPrimaryBut($id)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id != :val1 AND counts_as = "0" ORDER BY name ASC');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      while ( $row = $query->fetch() )
         $records[] = $row;
      return $records;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the allowance of an absence type
    *
    * @param string $id Record ID
    * @return string Absence type allowance
    */
   function getAllowance($id = '')
   {
      $rc = '0';
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT allowance FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['allowance'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the approval required value of an absence type
    *
    * @param string $id Record ID
    * @return bool Approval required
    */
   function getApprovalRequired($id = '')
   {
      $rc = false;
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT approval_required FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['approval_required'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the background color of an absence type
    *
    * @param string $id Record ID
    * @return string Absence type color
    */
   function getBgColor($id = '')
   {
      $rc = '';
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT bgcolor FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['bgcolor'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the background transparency flag of an absence type
    *
    * @param string $id Record ID
    * @return string Absence type color
    */
   function getBgTrans($id = '')
   {
      $rc = '';
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT bgtrans FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['bgtrans'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets an absence type record
    *
    * @param string $name Absence type name
    * @return bool $result Query result
    */
   function getByName($name = '')
   {
      $result = 0;
      if (isset($name))
      {
         $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE name = :val1');
         $query->bindParam('val1', $name);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->symbol = $row['symbol'];
            $this->icon = $row['icon'];
            $this->color = $row['color'];
            $this->bgcolor = $row['bgcolor'];
            $this->bgtrans = $row['bgtrans'];
            $this->factor = $row['factor'];
            $this->allowance = $row['allowance'];
            $this->counts_as = $row['counts_as'];
            $this->show_in_remainder = $row['show_in_remainder'];
            $this->show_totals = $row['show_totals'];
            $this->approval_required = $row['approval_required'];
            $this->counts_as_present = $row['counts_as_present'];
            $this->manager_only = $row['manager_only'];
            $this->hide_in_profile = $row['hide_in_profile'];
            $this->confidential = $row['confidential'];
         }
      }
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the color of an absence type
    *
    * @param string $id Record ID
    * @return string Absence type color
    */
   function getColor($id = '')
   {
      $rc = '';
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT color FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['color'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the counts_as ID of the absence
    *
    * @param string $id Record ID
    * @return string Absence counts as
    */
   function getCountsAs($id = '')
   {
      $rc = false;
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT counts_as FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['counts_as'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the factor value of an absence type
    *
    * @param string $id Record ID
    * @return string Absence type factor
    */
   function getFactor($id = '')
   {
      $rc = 1; // Default factor is 1
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT factor FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['factor'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the icon of an absence type
    *
    * @param string $id Record ID
    * @return string Absence type icon
    */
   function getIcon($id = '')
   {
      $rc = '.';
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT icon FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['icon'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the last auto-increment ID
    *
    * @return string Last auto-increment ID
    */
   function getLastId()
   {
      $query = $this->db->prepare('SHOW TABLE STATUS LIKE ' . $this->table);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return intval($row['Auto_increment']) - 1;
      }
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the manager only flag of an absence type
    *
    * @param string $id Record ID
    * @return bool Manager only flag
    */
   function getManagerOnly($id = '')
   {
      $rc = false;
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT manager_only FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['manager_only'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the name of an absence type
    *
    * @param string $id Record ID
    * @return string Absence type name
    */
   function getName($id = '')
   {
      $rc = 'unknown';
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT name FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['name'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the next auto-increment ID
    *
    * @return string Next auto-increment ID
    */
   function getNextId()
   {
      $query = $this->db->prepare('SHOW TABLE STATUS LIKE ' . $this->table);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['Auto_increment'];
      }
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the symbol of an absence type
    *
    * @param string $id Record ID
    * @return string Absence type symbol
    */
   function getSymbol($id = '')
   {
      $rc = '.';
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT symbol FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['symbol'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Updates an absence type by it's symbol from the current array data
    *
    * @param string $id Record ID
    * @return bool $result Query result
    */
   function update($id = '')
   {
      $result = 0;
      if (isset($id))
      {
         $query = $this->db->prepare('UPDATE ' . $this->table . ' SET 
               name = :val1, 
               symbol = :val2, 
               icon = :val3, 
               color = :val4, 
               bgcolor = :val5, 
               bgtrans = :val6, 
               factor = :val7, 
               allowance = :val8, 
               counts_as = :val9, 
               show_in_remainder = :val10, 
               show_totals = :val11, 
               approval_required = :val12, 
               counts_as_present = :val13, 
               manager_only = :val14, 
               hide_in_profile = :val15, 
               confidential = :val16 
               WHERE id = :val17');
         
         $query->bindParam('val1', $this->name);
         $query->bindParam('val2', $this->symbol);
         $query->bindParam('val3', $this->icon);
         $query->bindParam('val4', $this->color);
         $query->bindParam('val5', $this->bgcolor);
         $query->bindParam('val6', $this->bgtrans);
         $query->bindParam('val7', $this->factor);
         $query->bindParam('val8', $this->allowance);
         $query->bindParam('val9', $this->counts_as);
         $query->bindParam('val10', $this->show_in_remainder);
         $query->bindParam('val11', $this->show_totals);
         $query->bindParam('val12', $this->approval_required);
         $query->bindParam('val13', $this->counts_as_present);
         $query->bindParam('val14', $this->manager_only);
         $query->bindParam('val15', $this->hide_in_profile);
         $query->bindParam('val16', $this->confidential);
         $query->bindParam('val17', $id);
         $result = $query->execute();
      }
      return $result;
   }
   
   // ----------------------------------------------------------------------
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
