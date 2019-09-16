<?php
/**
 * Roles.class.php
 *
 * @category TeamCal Neo 
 * @version 2.2.0
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the role table
 */
class Absences
{
   public $id = 0;
   public $name = '';
   public $symbol = '';
   public $icon = 'No';
   public $color = '000000';
   public $bgcolor = 'ffffff';
   public $bgtrans = 0;
   public $factor = 1;
   public $allowance = 0;
   public $allowmonth = 0;
   public $allowweek = 0;
   public $counts_as = 0;
   public $show_in_remainder = 1;
   public $show_totals = 1;
   public $approval_required = 0;
   public $counts_as_present = 0;
   public $manager_only = 0;
   public $hide_in_profile = 0;
   public $confidential = 0;
   public $takeover = 0;
   
   private $db = NULL;
   private $table = '';
   
   // ----------------------------------------------------------------------
   /**
    * Constructor
    */
   public function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_absences'];
   }
   
   // ----------------------------------------------------------------------
   /**
    * Creates an absence type record
    *
    * @return boolean Query result
    */
   public function create()
   {
      $query = $this->db->prepare(
            'INSERT INTO ' . $this->table . ' (
               name, 
               symbol, 
               icon, 
               color,
               bgcolor,
               bgtrans,
               factor,
               allowance,
               allowmonth,
               allowweek,
               counts_as,
               show_in_remainder,
               show_totals,
               approval_required,
               counts_as_present,
               manager_only,
               hide_in_profile,
               confidential,
               takeover
            )'.
            ' VALUES (
               :val1,
               :val2,
               :val3,
               :val4,
               :val5,
               :val6,
               :val7,
               :val8,
               :val9,
               :val10,
               :val11,
               :val12,
               :val13,
               :val14,
               :val15,
               :val16,
               :val17,
               :val18,
               :val19
            )');

      $query->bindParam('val1', $this->name);
      $query->bindParam('val2', $this->symbol);
      $query->bindParam('val3', $this->icon);
      $query->bindParam('val4', $this->color);
      $query->bindParam('val5', $this->bgcolor);
      $query->bindParam('val6', $this->bgtrans);
      $query->bindParam('val7', $this->factor);
      $query->bindParam('val8', $this->allowance);
      $query->bindParam('val9', $this->allowmonth);
      $query->bindParam('val10', $this->allowweek);
      $query->bindParam('val11', $this->counts_as);
      $query->bindParam('val12', $this->show_in_remainder);
      $query->bindParam('val13', $this->show_totals);
      $query->bindParam('val14', $this->approval_required);
      $query->bindParam('val15', $this->counts_as_present);
      $query->bindParam('val16', $this->manager_only);
      $query->bindParam('val17', $this->hide_in_profile);
      $query->bindParam('val18', $this->confidential);
      $query->bindParam('val19', $this->takeover);
      $result = $query->execute();
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Deletes an absence type record
    *
    * @param string $id Record ID
    * @return boolean Query result
    */
   public function delete($id = '')
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
    * @return boolean Query result
    */
   public function deleteAll()
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
    * @return boolean Query result
    */
   public function get($id = '')
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
            $this->allowmonth = $row['allowmonth'];
            $this->allowweek = $row['allowweek'];
            $this->counts_as = $row['counts_as'];
            $this->show_in_remainder = $row['show_in_remainder'];
            $this->show_totals = $row['show_totals'];
            $this->approval_required = $row['approval_required'];
            $this->counts_as_present = $row['counts_as_present'];
            $this->manager_only = $row['manager_only'];
            $this->hide_in_profile = $row['hide_in_profile'];
            $this->confidential = $row['confidential'];
            $this->takeover = $row['takeover'];
         }
      }
      return $result;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Reads all records into an array
    *
    * @return array $records Array with records
    */
   public function getAll()
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
    * @return array $records Array with records
    */
   public function getAllSub($id)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE counts_as = :val1 ORDER BY name ASC');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result)
      {
         while ( $row = $query->fetch() ) $records[] = $row;
         return $records;
      }
      return false;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets all primary absences (not counts_as) types but the one with the given ID
    *
    * @param string $id ID to skip
    * @return array $records Array with records
    */
   public function getAllPrimaryBut($id)
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
   public function getAllowance($id = '')
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
    * Gets the allowance per month
    *
    * @param string $id Record ID
    * @return string Absence type allowance
    */
   public function getAllowMonth($id = '')
   {
      $rc = '0';
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT allowmonth FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['allowmonth'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the allowance per week
    *
    * @param string $id Record ID
    * @return string Absence type allowance
    */
   public function getAllowWeek($id = '')
   {
      $rc = '0';
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT allowweek FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            $rc = $row['allowweek'];
         }
      }
      return $rc;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Gets the approval required value of an absence type
    *
    * @param string $id Record ID
    * @return boolean Approval required
    */
   public function getApprovalRequired($id = '')
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
    * @return string Absence type bgcolor
    */
   public function getBgColor($id = '')
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
    * @return string Absence type bgtrans
    */
   public function getBgTrans($id = '')
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
    * @return boolean Query result
    */
   public function getByName($name = '')
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
            $this->allowmonth = $row['allowmonth'];
            $this->allowweek = $row['allowweek'];
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
   public function getColor($id = '')
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
    * Gets the counts_as of the absence
    *
    * @param string $id Record ID
    * @return string Absence counts as
    */
   public function getCountsAs($id = '')
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
    * Gets the counts_as_present of the absence
    *
    * @param string $id Record ID
    * @return string Absence counts as
    */
   public function getCountsAsPresent($id = '')
   {
      $rc = false;
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT counts_as_present FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
          
         if ($result and $row = $query->fetch())
         {
            $rc = $row['counts_as_present'];
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
   public function getFactor($id = '')
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
   public function getIcon($id = '')
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
   public function getLastId()
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
    * Gets the name of an absence type
    *
    * @param string $id Record ID
    * @return string Absence type name
    */
   public function getName($id = '')
   {
      $rc = '';
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
   public function getNextId()
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
   public function getSymbol($id = '')
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
    * Checks whether an absence is confidential
    *
    * @param string $id Record ID
    * @return boolean
    */
   public function isConfidential($id = '')
   {
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT confidential FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            if($row['confidential']) return true;
            else return false;
         }
      }
      return false;
   }
   
   // ----------------------------------------------------------------------
   /**
    * Checks whether an absence is manager only
    *
    * @param string $id Record ID
    * @return boolean
    */
    public function isManagerOnly($id = '')
    {
       if (isset($id))
       {
          $query = $this->db->prepare('SELECT manager_only FROM ' . $this->table . ' WHERE id = :val1');
          $query->bindParam('val1', $id);
          $result = $query->execute();
          
          if ($result and $row = $query->fetch())
          {
             if($row['manager_only']) return true;
             else return false;
          }
       }
       return false;
    }
    
    // ----------------------------------------------------------------------
   /**
    * Checks whether an absence is takeover enabled
    *
    * @param string $id Record ID
    * @return boolean
    */
   public function isTakeover($id = '')
   {
      if (isset($id))
      {
         $query = $this->db->prepare('SELECT takeover FROM ' . $this->table . ' WHERE id = :val1');
         $query->bindParam('val1', $id);
         $result = $query->execute();
         
         if ($result and $row = $query->fetch())
         {
            if($row['takeover']) return true;
            else return false;
         }
      }
      return false;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Makes all sub-abs of a given abs primary. Sets counts_as = 0.
    *
    * @param string $id Absence ID of the primary
    * @return boolean True or False
    */
   function setAllSubsPrimary($id)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET counts_as = 0 WHERE counts_as = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();

      if ($result) return true;
      else         return false;
   }
      
   // ----------------------------------------------------------------------
   /**
    * Updates an absence type by it's symbol from the current array data
    *
    * @param string $id Record ID
    * @return boolean Query result
    */
   public function update($id = '')
   {
      $result = 0;
      if (isset($id))
      {
         $query = $this->db->prepare('UPDATE ' . $this->table . ' 
               SET 
                  name = :val1, 
                  symbol = :val2, 
                  icon = :val3, 
                  color = :val4, 
                  bgcolor = :val5, 
                  bgtrans = :val6, 
                  factor = :val7, 
                  allowance = :val8, 
                  allowmonth = :val9, 
                  allowweek = :val10, 
                  counts_as = :val11, 
                  show_in_remainder = :val12, 
                  show_totals = :val13, 
                  approval_required = :val14, 
                  counts_as_present = :val15, 
                  manager_only = :val16, 
                  hide_in_profile = :val17, 
                  confidential = :val18, 
                  takeover = :val19 
               WHERE 
                  id = :val20');
         
         $query->bindParam('val1', $this->name);
         $query->bindParam('val2', $this->symbol);
         $query->bindParam('val3', $this->icon);
         $query->bindParam('val4', $this->color);
         $query->bindParam('val5', $this->bgcolor);
         $query->bindParam('val6', $this->bgtrans);
         $query->bindParam('val7', $this->factor);
         $query->bindParam('val8', $this->allowance);
         $query->bindParam('val9', $this->allowmonth);
         $query->bindParam('val10', $this->allowweek);
         $query->bindParam('val11', $this->counts_as);
         $query->bindParam('val12', $this->show_in_remainder);
         $query->bindParam('val13', $this->show_totals);
         $query->bindParam('val14', $this->approval_required);
         $query->bindParam('val15', $this->counts_as_present);
         $query->bindParam('val16', $this->manager_only);
         $query->bindParam('val17', $this->hide_in_profile);
         $query->bindParam('val18', $this->confidential);
         $query->bindParam('val19', $this->takeover);
         $query->bindParam('val20', $id);
         $result = $query->execute();
      }
      return $result;
   }
   
   // ----------------------------------------------------------------------
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
