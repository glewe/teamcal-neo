<?php
/**
 * UserGroup.class.php
 *
 * @category TeamCal Neo 
 * @version 0.3.00
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to manage the user-group database table
 */
class UserGroup
{
   var $db = '';
   var $table = '';
   var $archive_table = '';
   var $id = NULL;
   var $username = NULL;
   var $groupid = NULL;
   var $type = NULL;
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_user_group'];
      $this->archive_table = $CONF['db_table_archive_user_group'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Archives all user_group records for a given user
    *
    * @param string $username Username to archive
    * @return bool Query result or false
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
    * Reads all usernames of a given group
    *
    * @param string $groupid Group ID to search by
    * @return array $records Array with all group records
    */
   function countMembers($groupid)
   {
      $count= 0;
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE groupid = :val1');
      $query->bindParam('val1', $groupid);
      $result = $query->execute();
      return $query->fetchColumn();
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a new user-group record
    *
    * @param string $username Username
    * @param string $groupid Group ID
    * @param string $type Type of membership (member, manager)
    * @return bool Query result or false
    */
   function createUserGroupEntry($username, $groupid, $type)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, groupid, type) VALUES (:val1, :val2, :val3)');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $groupid);
      $query->bindParam('val3', $type);
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
    * Deletes all records for a given group
    *
    * @param string $groupid Group ID to delete
    * @return bool $result Query result
    */
   function deleteByGroup($groupid, $archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE groupid = :val1');
      $query->bindParam('val1', $groupid);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a user-group record by ID from local class variable
    *
    * @return bool $result Query result
    */
   function deleteById()
   {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $this->id);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all records for a given user
    *
    * @param string $username Username to delete
    * @param boolean $archive Whether to search in archive table
    * @return bool $result Query result
    */
   function deleteByUser($username = '', $archive = FALSE)
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
    * Deletes all records for a given user and group (Deletes membership)
    *
    * @param string $username Username
    * @param string $groupid Group ID
    * @return bool $result Query result
    */
   function deleteMembership($username = '', $groupid = '', $archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE groupid = :val1 AND username = :val2');
      $query->bindParam('val1', $groupid);
      $query->bindParam('val2', $username);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a record exists
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
    * Finds a user-group record by ID
    *
    * @param string $id Record ID to find
    * @return bool $result Query result
    */
   function findById($id)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         $this->id = $row['id'];
         $this->username = $row['username'];
         $this->groupid = $row['groupid'];
         $this->type = $row['type'];
      }
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Reads all usernames of a given group
    *
    * @param string $groupid Group ID to search by
    * @return array $records Array with all group records
    */
   function getAllforGroup($groupid)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE groupid = :val1 ORDER BY username ASC');
      $query->bindParam('val1', $groupid);
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
    * Reads all records for a given user into an array
    *
    * @param string $username Username to find
    * @return array $records Array with all records
    */
   function getAllforUser($username)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :val1');
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
    * Reads all groups for a given user into an array where the index is the
    * groupid and the value is the membership type.
    *
    * @param string $username Username to find
    * @return array $records Array with all records
    */
   function getAllforUser2($username)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $records[$row['groupid']] = $row['type'];
         }
      }
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets the first group a user has an entry for
    *
    * @param string $username Username to find
    * @return string Group ID of first group found or 'unknown'
    */
   function getGroupName($username)
   {
      $query = $this->db->prepare('SELECT groupid FROM ' . $this->table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['groupid'];
      }
      else
      {
         return 'unknown';
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets all group managers of a given group
    *
    * @param string $groupid Group ID to check
    * @return array $records Array with usernames of group managers
    */
   function getGroupManagers($groupid)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT username FROM ' . $this->table . ' WHERE groupid = :val1 AND type = :val2');
      $query->bindParam('val1', $groupid);
      $query->bindParam('val2', 'manager');
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $records[$row['groupid']] = $row['username'];
         }
      }
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a given user is manager of any group
    *
    * @param string $username Username to check
    * @return boolean True if he is, false if not
    */
   function isGroupManager($username)
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :val1 AND type = "manager"');
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
    * Checks whether a given user is manager of a given group
    *
    * @param string $username Username to check
    * @param string $groupid Group ID to check
    * @return boolean True if he is, false if not
    */
   function isGroupManagerOfGroup($username, $groupid)
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :val1 AND groupid = :val2 AND type = "manager"');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $groupid);
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
    * Checks whether a given user is manager of another given user
    *
    * @param string $user1 Username to check whether he is manager of user 2
    * @param string $user2 Username to check whether he is managed by user 1
    * @return boolean True if he is, false if not
    */
   function isGroupManagerOfUser($user1, $user2)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :val1');
      $query->bindParam('val1', $user2);
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $query2 = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :val1 AND groupid = :val2 AND type = "manager"');
            $query2->bindParam('val1', $user1);
            $query2->bindParam('val2', $row['groupid']);
            $result2 = $query2->execute();
            if ($result2 and $query2->fetchColumn())
            {
               return true;
            }
         }
      }
      return false;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a given user is member of any group
    *
    * @param string $username Username to check
    * @return boolean True if he is, false if not
    */
   function isGroupMember($username)
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :val1 AND type = "member"');
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
    * Gets all managed groups of a given user
    *
    * @param string $username Username to check
    * @return array $records Array with usernames of group managers
    */
   function getManagerships($username)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT groupid FROM ' . $this->table . ' WHERE `username` = "' . $username . '" AND `type` = "manager"');
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $records[] = $row['groupid'];
         }
      }
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets all groups that a given user is member of
    *
    * @param string $username Username to check
    * @return array $records Array with usernames of group managers
    */
   function getMemberships($username)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT groupid FROM ' . $this->table . ' WHERE `username` = "' . $username . '" AND `type` = "member"');
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $records[] = $row['groupid'];
         }
      }
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a given user is member od a given group
    *
    * @param string $username Username
    * @param string $groupid Group ID
    * @return boolean True if member, false if not
    */
   function isMemberOfGroup($username, $groupid)
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :val1 AND groupid = :val2');
      $query->bindParam('val1', $username);
      $query->bindParam('val2', $groupid);
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
    * Restores all user_group records for a given user
    *
    * @param string $name Username to restore
    * @return bool Query result or false
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
    * Saves/updates a record
    *
    * @param string $username Username
    * @param string $groupid Group ID
    * @param string $type Type of relationship
    * @return bool $result Query result or false
    */
   function save($username, $groupid, $type)
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE `username` = "' . $username . '" AND `groupid` = "' . $groupid . '"');
      $result = $query->execute();
      
      if ($result and $query->fetchColumn())
      {
         $query2 = $this->db->prepare('UPDATE ' . $this->table . ' SET type = :val1 WHERE username = :val2 AND groupid = :val3');
         $query2->bindParam('val1', $type);
         $query2->bindParam('val2', $username);
         $query2->bindParam('val3', $groupid);
      }
      else
      {
         $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (`username`, `groupid`, `type`) VALUES (:val1, :val2, :val3)');
         $query2->bindParam('val1', $username);
         $query2->bindParam('val2', $groupid);
         $query2->bindParam('val3', $type);
      }
      $result2 = $query2->execute();
      return $result2;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether two given users share membership of at least one group
    *
    * @param string $user1 First username
    * @param string $user2 Second username
    * @return boolean True if they do, false if not
    */
   function shareGroups($user1, $user2)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :val1');
      $query->bindParam('val1', $user1);
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $query2 = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :val1 AND groupid = :val2');
            $query2->bindParam('val1', $user2);
            $query2->bindParam('val2', $row['groupid']);
            $result2 = $query2->execute();
            if ($result2 and $query2->fetchColumn())
            {
               return true;
            }
         }
      }
      return false;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates a user-group record from local class variables
    */
   function update()
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET username = :val1, groupid = :val2, type = :val3 WHERE id = :val4');
      $query->bindParam('val1', $this->username);
      $query->bindParam('val2', $this->groupid);
      $query->bindParam('val3', $this->type);
      $query->bindParam('val4', $this->id);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates a groupname in all records
    *
    * @param string $groupold Old group ID
    * @param string $groupnew New group ID
    */
   function updateGroupname($groupold, $groupnew)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET groupid = :val1 WHERE groupid = :val2');
      $query->bindParam('val1', $groupnew);
      $query->bindParam('val2', $groupold);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates the type of membership
    *
    * @param string $username Username to update
    * @param string $groupid Group ID to update
    * @param string $type New membership type
    * @return bool $result Query result
    */
   function updateUserGroupType($username, $groupid, $type)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET type = :val1 WHERE groupid = :val2 AND username = :val3');
      $query->bindParam('val1', $type);
      $query->bindParam('val2', $groupid);
      $query->bindParam('val3', $username);
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
