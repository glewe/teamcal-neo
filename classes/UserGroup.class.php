<?php
/**
 * UserGroup.class.php
 *
 * @category TeamCal Neo 
 * @version 1.1.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed.
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to manage the user-group database table
 */
class UserGroup
{
   public $id = NULL;
   public $username = NULL;
   public $groupid = NULL;
   public $type = NULL;
   
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
      $this->table = $CONF['db_table_user_group'];
      $this->archive_table = $CONF['db_table_archive_user_group'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Archives all user_group records for a given user
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
    * Counts all members of a given group
    *
    * @param string $groupid Group ID to search by
    * @return integer Record count
    */
   public function countMembers($groupid)
   {
      $count= 0;
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE groupid = :val1 AND (type = "manager" OR type ="member")');
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
    * @return boolean Query result or false
    */
   public function createUserGroupEntry($username, $groupid, $type)
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
    * @return boolean Query result
    */
   public function deleteAll($archive = FALSE)
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
    * @return boolean Query result
    */
   public function deleteByGroup($groupid, $archive = FALSE)
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
    * @return boolean Query result
    */
   public function deleteById()
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
    * @param bool $archive Whether to use the archive table
    * @return boolean Query result
    */
   public function deleteByUser($username = '', $archive = FALSE)
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
    * Deletes all guests of a group
    *
    * @param string $username Username
    * @param string $groupid Group ID
    * @param bool $archive Whether to use the archive table
    * @return boolean Query result
    */
   public function deleteAllGuests($groupid = '', $archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
   
      $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE groupid = :val1 AND type = "guest"');
      $query->bindParam('val1', $groupid);
      $result = $query->execute();
      return $result;
   }
    
   // ---------------------------------------------------------------------
   /**
    * Deletes all managers of a group
    *
    * @param string $username Username
    * @param string $groupid Group ID
    * @param bool $archive Whether to use the archive table
    * @return boolean Query result
    */
   public function deleteAllManagers($groupid = '', $archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE groupid = :val1 AND type = "manager"');
      $query->bindParam('val1', $groupid);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes all members of a group
    *
    * @param string $username Username
    * @param string $groupid Group ID
    * @param bool $archive Whether to use the archive table
    * @return boolean Query result
    */
   public function deleteAllMembers($groupid = '', $archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE groupid = :val1 AND type = "member"');
      $query->bindParam('val1', $groupid);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a record exists
    *
    * @param string $username Username to find
    * @param bool $archive Whether to use the archive table
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
    * Gets all usernames of a given group (managers and members)
    *
    * @param string $groupid Group ID to search by
    * @return array Array with all group records
    */
   public function getAllforGroup($groupid, $sort='ASC')
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE groupid = :val1 AND (type = "manager" OR type ="member") ORDER BY username '. $sort);
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
    * Gets all usernames of a given group (managers and members and guests)
    *
    * @param string $groupid Group ID to search by
    * @return array Array with all group records
    */
   public function getAllforGroupPlusGuests($groupid, $sort='ASC')
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE groupid = :val1 ORDER BY username '. $sort);
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
    * Gets all records for a given user into an array
    *
    * @param string $username Username to find
    * @return array Array with all records
    */
   public function getAllforUser($username)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :val1 AND (type = "manager" OR type ="member")');
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
    * Gets all groups for a given user into an array where the index is the
    * groupid and the value is the membership type.
    *
    * @param string $username Username to find
    * @return array Array with all records
    */
   public function getAllforUser2($username)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :val1 AND (type = "manager" OR type ="member")');
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
    * Gets a record by ID
    *
    * @param string $id Record ID to find
    * @return boolean Query result
    */
   public function getById($id)
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
    * Gets the first group a user has an entry for
    *
    * @param string $username Username to find
    * @return string Group ID of first group found or 'unknown'
    */
   public function getGroupName($username)
   {
      $query = $this->db->prepare('SELECT groupid FROM ' . $this->table . ' WHERE username = :val1 AND (type = "manager" OR type ="member")');
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
    * @return array Array with usernames of group managers
    */
   public function getGroupManagers($groupid)
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
    * Gets all groups where the given user is guest
    *
    * @param string $username Username to check
    * @return array Array with usernames of group managers
    */
   public function getGuestships($username)
   {
      $records = array ();
      $query = $this->db->prepare('SELECT groupid FROM ' . $this->table . ' WHERE `username` = "' . $username . '" AND `type` = "guest"');
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
    * Gets all managed groups of a given user
    *
    * @param string $username Username to check
    * @return array Array with usernames of group managers
    */
   public function getManagerships($username)
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
    * @return array Array with usernames of group managers
    */
   public function getMemberships($username)
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
    * Checks whether a given user is manager of any group
    *
    * @param string $username Username to check
    * @return boolean True if he is, false if not
    */
   public function isGroupManager($username)
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
   public function isGroupManagerOfGroup($username, $groupid)
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
   public function isGroupManagerOfUser($user1, $user2)
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
   public function isGroupMember($username)
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
    * Checks whether a given user is guest of any group
    *
    * @param string $username Username to check
    * @return boolean True if he is, false if not
    */
   public function isGuest($username)
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :val1 AND type = "guest"');
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
    * Checks whether a given user is guest of a given group
    *
    * @param string $username Username to check
    * @param string $groupid Group ID to check
    * @return boolean True if he is, false if not
    */
   public function isGuestOfGroup($username, $groupid)
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :val1 AND groupid = :val2 AND type = "guest"');
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
    * Checks whether a given user is member od a given group
    *
    * @param string $username Username
    * @param string $groupid Group ID
    * @return boolean True if member, false if not
    */
   public function isMemberOfGroup($username, $groupid)
   {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :val1 AND groupid = :val2 AND (type = "manager" OR type ="member")');
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
    * Checks whether a given user is member or guest of a given group
    *
    * @param string $username Username
    * @param string $groupid Group ID
    * @return boolean True if member, false if not
    */
   public function isMemberOrGuestOfGroup($username, $groupid)
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
    * Saves/updates a record
    *
    * @param string $username Username
    * @param string $groupid Group ID
    * @param string $type Type of relationship
    * @return boolean Query result
    */
   public function save($username, $groupid, $type)
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
   public function shareGroups($user1, $user2)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :val1 AND (type = "manager" OR type ="member")');
      $query->bindParam('val1', $user1);
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $query2 = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :val1 AND groupid = :val2 AND (type = "manager" OR type ="member")');
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
    * 
    * @return boolean Query result
    */
   public function update()
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
    * @return boolean Query result
    */
   public function updateGroupname($groupold, $groupnew)
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
    * @return boolean Query result
    */
   public function updateUserGroupType($username, $groupid, $type)
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
