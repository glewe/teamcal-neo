<?php
/**
 * Users.class.php
 *
 * @category TeamCal Neo 
 * @version 0.5.006
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to manage the users database table
 */
class Users
{
   var $db = '';
   var $table = '';
   var $archive_table = '';
   var $username = '';
   var $password = '';
   var $firstname = '';
   var $lastname = '';
   var $email = '';
   var $role = '';
   var $locked = 0;
   var $hidden = 0;
   var $onhold = 0;
   var $verify = 0;
   var $bad_logins = 0;
   var $grace_start = NULL;
   var $last_pw_change = NULL;
   var $last_login = NULL;
   var $created = NULL;
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $DB;
      $this->db = $DB->db;
      $this->table = $CONF['db_table_users'];
      $this->archive_table = $CONF['db_table_archive_users'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Archives a user record
    *
    * @param string $username Username to archive
    * @return bool $result Query result
    */
   function archive($username)
   {
      $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT u.* FROM ' . $this->table . ' u WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Restore arcived user records
    *
    * @param string $username Username to restore
    * @return bool $result Query result
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
    * Checks whether a user record exists
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
    * Reads all usernames of a given group
    *
    * @param boolean $skipAdmin Flag to include/exclude admin in the count
    * @return integer Count
    */
   function countUsers($countAdmin=false, $countHidden=false)
   {
      if ($countHidden)
         $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table);
      else 
         $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE hidden = 0');
      
      $result = $query->execute();
      
      if ($countAdmin) 
         return $result->fetchColumn();
      else 
         return $result->fetchColumn()-1;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a new user record from local variables
    *
    * @return bool $result Query result
    */
   function create()
   {
      $stmt = 'INSERT INTO ' . $this->table . ' (username, password, firstname, lastname, email, role, locked, hidden, onhold, verify, bad_logins, grace_start, last_pw_change, last_login, created) ';
      $stmt .= 'VALUES (:val1, :val2, :val3, :val4, :val5, :val6, :val7, :val8, :val9, :val10, :val11, :val12, :val13, :val14, :val15)';
      $query = $this->db->prepare($stmt);
      $query->bindParam('val1', $this->username);
      $query->bindParam('val2', $this->password);
      $query->bindParam('val3', $this->firstname);
      $query->bindParam('val4', $this->lastname);
      $query->bindParam('val5', $this->email);
      $query->bindParam('val6', $this->role);
      $query->bindParam('val7', $this->locked);
      $query->bindParam('val8', $this->hidden);
      $query->bindParam('val9', $this->onhold);
      $query->bindParam('val10', $this->verify);
      $query->bindParam('val11', $this->bad_logins);
      $query->bindParam('val12', $this->grace_start);
      $query->bindParam('val13', $this->last_pw_change);
      $query->bindParam('val14', $this->last_login);
      $query->bindParam('val15', $this->created);
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
      
      $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username <> "admin"');
      $result = $query->execute();
      return $result;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a user record by username
    *
    * @param string $username Username to find
    * @param boolean $archive Whether to search in archive table
    * @return bool $result Query result
    */
   function deleteByName($username = '', $archive = FALSE)
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
    * Finds a user record by username and fills values into local variables
    *
    * @param string $username Username to find
    * @param boolean $archive Whether to search in archive table
    * @return integer Result of MySQL query
    */
   function findByName($username = '', $archive = FALSE)
   {
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         $this->username = $row['username'];
         $this->password = $row['password'];
         $this->firstname = $row['firstname'];
         $this->lastname = $row['lastname'];
         $this->email = $row['email'];
         $this->role = $row['role'];
         $this->locked = $row['locked'];
         $this->hidden = $row['hidden'];
         $this->onhold = $row['onhold'];
         $this->verify = $row['verify'];
         $this->bad_logins = $row['bad_logins'];
         $this->grace_start = $row['grace_start'];
         $this->last_pw_change = $row['last_pw_change'];
         $this->last_login = $row['last_login'];
         $this->created = $row['created'];
         return true;
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Reads all records into an array
    *
    * @return array $records Array with all records
    */
   function getAll($order1 = 'lastname', $order2 = 'firstname', $sort = 'ASC', $archive = false, $includeAdmin = false)
   {
      $records = array ();
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      if ($includeAdmin)
      {
         $query = $this->db->prepare('SELECT * FROM ' . $table . ' ORDER BY ' . $order1 . ' ' . $sort . ', ' . $order2 . ' ' . $sort);
      }
      else
      {
         $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE username != "admin" ORDER BY ' . $order1 . ' ' . $sort . ', ' . $order2 . ' ' . $sort);
      }
          
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
    * Reads all records into an array where username, lastname or firstname
    * is like specified
    *
    * @param string $like Likeness to search for
    * @return array $records Array with all records
    */
   function getAllLike($like, $archive = FALSE)
   {
      $records = array ();
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE firstname LIKE :val1 OR lastname LIKE :val1 OR username LIKE :val1 ORDER BY lastname ASC, firstname ASC');
      $val1 = '%' . $like . '%';
      $query->bindParam('val1', $val1);
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            if ($row['username'] != 'admin') $records[] = $row;
         }
      }
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Reads a record for a given username
    *
    * @param string $uname Username to search for
    * @return array $records Array with all records
    */
   function getByUsername($uname, $archive = FALSE)
   {
      $records = array ();
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
      
      $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE username = :val1');
      $query->bindParam('val1', $uname);
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            if ($row['username'] != 'admin') $records[] = $row;
         }
      }
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Reads the E-mail address of a given username
    *
    * @param string $username Username to find
    * @return string $email Email address or empty
    */
   function getEmail($username)
   {
      $query = $this->db->prepare('SELECT email FROM ' . $this->table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         return $row['email'];
      }
      return '';
   }
   
   // ---------------------------------------------------------------------
   /**
    * Delivers the fullname of a given username
    *
    * @param string $username Username to find
    * @return string $email Email address or empty
    */
   function getFullname($username)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         if (strlen($row['firstname']))
         {
            $fullname = $row['firstname'];
            if (strlen($row['lastname'])) $fullname .= ' ' . $row['lastname'];
         }
         else 
         {
            if (strlen($row['lastname'])) $fullname = $row['lastname'];
            else  $fullname = 'no name';
         }
         return $fullname;
      }
      return '?';
   }
   
   // ---------------------------------------------------------------------
   /**
    * Delivers Lastname, Firstname of a given username
    *
    * @param string $username Username to find
    * @return string $email Email address or empty
    */
   function getLastFirst($username)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         if (strlen($row['lastname']))
         {
            $lastfirst = $row['lastname'];
            if (strlen($row['firstname'])) $lastfirst .= ', ' . $row['firstname'];
         }
         else 
         {
            if (strlen($row['firstname'])) $lastfirst = $row['firstname'];
            else  $lastfirst = 'no name';
         }
         return $lastfirst;
      }
      return '?';
   }
   
   // ---------------------------------------------------------------------
   /**
    * Reads the role of a given username
    *
    * @param string $username Username to find
    * @return string $role Role or empty
    */
   function getRole($username)
   {
      $query = $this->db->prepare('SELECT role FROM ' . $this->table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      
      if ($result AND $row = $query->fetch())
      {
         return $row['role'];
      }
      return '';
   }
   
   // ---------------------------------------------------------------------
   /**
    * Reads all usernames into an array
    *
    * @return array $records Array with all usernames
    */
   function getUsernames()
   {
      $records = array ();
      $query = $this->db->prepare('SELECT username FROM ' . $this->table . ' ORDER BY username ASC');
      $result = $query->execute();
      
      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $records[] = $row['username'];
         }
      }
      return $records;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a user has a certain role
    *
    * @param string $username Username to find
    * @param string $role Role to check
    * @return boolean True or False
    */
   function hasRole($username, $role)
   {
      $query = $this->db->prepare('SELECT role FROM ' . $this->table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      
      if ($result AND $row = $query->fetch())
      {
         if ($row['role'] == $role) return true;
         else return false;
      }
      return false;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a user is hidden
    *
    * @param string $username Username to find
    * @return bool True or False
    */
   function isHidden($username)
   {
      $query = $this->db->prepare('SELECT hidden FROM ' . $this->table . ' WHERE username = :val1');
      $query->bindParam('val1', $username);
      $result = $query->execute();
      
      if ($result and $row = $query->fetch())
      {
         if ($row['hidden'])
         {
            return true;
         }
         else 
         {
            return false;
         }
      }
      return true;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a user has a certain role
    *
    * @param string $username Username to find
    * @param string $role Role to set
    * @return boolean True or False
    */
   function setRole($username, $role)
   {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET role = :val1 WHERE username = :val2');
      $query->bindParam('val1', $role);
      $query->bindParam('val2', $username);
      $result = $query->execute();
      
      if ($result) return true;
      else         return false;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Updates an existing user record from local class variables
    *
    * @param string $username Username of record to update
    * @return bool $result Query result
    */
   function update($username)
   {
      $stmt = 'UPDATE ' . $this->table . ' SET ';
      $stmt .= '`username` = "' . $this->username . '", ';
      $stmt .= '`password` = "' . $this->password . '", ';
      $stmt .= '`firstname` = "' . $this->firstname . '", ';
      $stmt .= '`lastname` = "' . $this->lastname . '", ';
      $stmt .= '`email` = "' . $this->email . '", ';
      $stmt .= '`role` = "' . $this->role . '", ';
      $stmt .= '`locked` = "' . $this->locked . '", ';
      $stmt .= '`hidden` = "' . $this->hidden . '", ';
      $stmt .= '`onhold` = "' . $this->onhold . '", ';
      $stmt .= '`verify` = "' . $this->verify . '", ';
      $stmt .= '`bad_logins` = "' . $this->bad_logins . '", ';
      $stmt .= '`grace_start` = "' . $this->grace_start . '", ';
      $stmt .= '`last_pw_change` = "' . $this->last_pw_change . '", ';
      $stmt .= '`last_login` = "' . $this->last_login . '", ';
      $stmt .= '`created` = "' . $this->created . '" ';
      $stmt .= 'WHERE `username` = "' . $username . '"';
      $result = $this->db->exec($stmt);
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
