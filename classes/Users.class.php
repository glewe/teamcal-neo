<?php
/**
 * Users.class.php
 *
 * @category TeamCal Neo
 * @version 2.2.2
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed.
*/
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to manage the users database table
 */
class Users
{
   public $username = '';
   public $password = '';
   public $firstname = '';
   public $lastname = '';
   public $email = '';
   public $role = 2;
   public $locked = 0;
   public $hidden = 0;
   public $onhold = 0;
   public $verify = 0;
   public $bad_logins = 0;
   public $grace_start = DEFAULT_TIMESTAMP;
   public $last_pw_change = DEFAULT_TIMESTAMP;
   public $last_login = DEFAULT_TIMESTAMP;
   public $created = DEFAULT_TIMESTAMP;
    
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
      $this->table = $CONF['db_table_users'];
      $this->archive_table = $CONF['db_table_archive_users'];
   }
    
   // ---------------------------------------------------------------------
   /**
    * Archives a user record
    *
    * @param string $username Username to archive
    * @return boolean Query result
    */
   public function archive($username)
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
    * Checks whether a user record exists
    *
    * @param string $username Username to find
    * @param boolean $archive Whether to search in archive table
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
    * Reads all user records
    *
    * @param boolean $countAdmin Flag to include/exclude admin account
    * @param boolean $countHidden Flag to include/exclude hidden accounts
    * @return integer Count
    */
   public function countUsers($countAdmin=false, $countHidden=false)
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
    * @return boolean Query result
    */
   public function create()
   {
//       $q = 'INSERT INTO ' . $this->table . ' (
//             username,
//             password,
//             firstname,
//             lastname,
//             email,
//             role,
//             locked,
//             hidden,
//             onhold,
//             verify,
//             bad_logins,
//             grace_start,
//             last_pw_change,
//             last_login,
//             created
//          ) VALUES (
//             "'.$this->username.'",
//             "'.$this->password.'",
//             "'.$this->firstname.'",
//             "'.$this->lastname.'",
//             "'.$this->email.'",
//             "'.$this->role.'",
//             "'.$this->locked.'",
//             "'.$this->hidden.'",
//             "'.$this->onhold.'",
//             "'.$this->verify.'",
//             "'.$this->bad_logins.'",
//             "'.$this->grace_start.'",
//             "'.$this->last_pw_change.'",
//             "'.$this->last_login.'",
//             "'.$this->created.'"
//          )';
//       print $q;

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
    * @return boolean Query result
    */
   public function deleteAll($archive = FALSE)
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
    * @return boolean Query result
    */
   public function deleteByName($username = '', $archive = FALSE)
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
    * @return boolean Query result
    */
   public function findByName($username = '', $archive = FALSE)
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
      return false;
   }
    
   // ---------------------------------------------------------------------
   /**
    * Finds a user record by token and fills values into local variables
    *
    * @param string $token Token to find
    * @return boolean Query result
    */
   public function findByToken($token)
   {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE md5(CONCAT("PasswordResetRequestFor",username)) = :val1');
      $query->bindParam('val1', $token);
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
      return false;
   }
   
    // ---------------------------------------------------------------------
   /**
    * Gets all records into an array
    *
    * @param string $order1 First order criteria
    * @param string $order2 First order criteria
    * @param string $sort Sort order (ASC or DESC)
    * @param boolean $archive Whether to use archive table
    * @param boolean $includeAdmin Whether to include admin account or not
    * @return array Array with records
    */
   public function getAll($order1 = 'lastname', $order2 = 'firstname', $sort = 'ASC', $archive = false, $includeAdmin = false)
   {
      $records = array ();
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;

      if ($includeAdmin)
         $query = $this->db->prepare('SELECT * FROM ' . $table . ' ORDER BY ' . $order1 . ' ' . $sort . ', ' . $order2 . ' ' . $sort);
         else
            $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE username != "admin" ORDER BY ' . $order1 . ' ' . $sort . ', ' . $order2 . ' ' . $sort);

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
    * Gets all records for a given email address
    *
    * @param string $email Email to find
    * @return array Array with records
    */
   public function getAllForEmail($email)
   {
      $records = array ();
   
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE email = :val1 ORDER BY lastname ASC, firstname ASC');
      $query->bindParam('val1', $email);
      $result = $query->execute();

      if ($result)
      {
         while ( $row = $query->fetch() )
         {
            $records[] = $row;
         }
         return $records;
      }
      return false;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets all records into an array except hidden users
    *
    * @param string $order1 First order criteria
    * @param string $order2 First order criteria
    * @param string $sort Sort order (ASC or DESC)
    * @param boolean $archive Whether to use archive table
    * @param boolean $includeAdmin Whether to include admin account or not
    * @return array Array with records
    */
   public function getAllButHidden($order1 = 'lastname', $order2 = 'firstname', $sort = 'ASC', $archive = false, $includeAdmin = false)
   {
      $records = array ();
      if ($archive) $table = $this->archive_table;
      else $table = $this->table;
       
      if ($includeAdmin)
         $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE hidden != 1 ORDER BY ' . $order1 . ' ' . $sort . ', ' . $order2 . ' ' . $sort);
         else
            $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE username != "admin" AND hidden != 1 ORDER BY ' . $order1 . ' ' . $sort . ', ' . $order2 . ' ' . $sort);
             
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
    * Gets all records with likeness in username, lastname or firstname
    *
    * @param string $like Likeness to search for
    * @return array Array with records
    */
   public function getAllLike($like, $archive = FALSE)
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
    * Gets a record for a given username
    *
    * @param string $uname Username to search for
    * @param boolean $archive Whether to use archive table
    * @return array Array with records
    */
   public function getByUsername($uname, $archive = FALSE)
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
    * Gets the E-mail address of a given username
    *
    * @param string $username Username to find
    * @return string Email address or empty
    */
   public function getEmail($username)
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
    * Gets the fullname of a given username
    *
    * @param string $username Username to find
    * @return string Fullname or empty
    */
   public function getFullname($username)
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
    * Gets Lastname, Firstname of a given username
    *
    * @param string $username Username to find
    * @return string Lastname, Firtsname or empty
    */
   public function getLastFirst($username)
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
    * Gets the role of a given username
    *
    * @param string $username Username to find
    * @return string Role or empty
    */
   public function getRole($username)
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
    * Gets all usernames
    *
    * @return array Array with usernames
    */
   public function getUsernames()
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
   public function hasRole($username, $role)
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
    * @return boolean True or False
    */
   public function isHidden($username)
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
    * Sets the role ID for a given user
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
    * Unhides a user record
    *
    * @param string $username Username of record to update
    * @return boolean Query result
    */
   public function unhide($username)
   {
      $stmt = 'UPDATE ' . $this->table . ' SET `hidden` = "0" WHERE `username` = "' . $username . '"';
      $result = $this->db->exec($stmt);
      return $result;
   }
    
   // ---------------------------------------------------------------------
   /**
    * Unholds a user record
    *
    * @param string $username Username of record to update
    * @return boolean Query result
    */
    public function unhold($username)
    {
       $stmt = 'UPDATE ' . $this->table . ' SET `onhold` = "0" WHERE `username` = "' . $username . '"';
       $result = $this->db->exec($stmt);
       return $result;
    }
     
    // ---------------------------------------------------------------------
   /**
    * Unlocks a user record
    *
    * @param string $username Username of record to update
    * @return boolean Query result
    */
   public function unlock($username)
   {
      $stmt = 'UPDATE ' . $this->table . ' SET `locked` = "0" WHERE `username` = "' . $username . '"';
      $result = $this->db->exec($stmt);
      return $result;
   }
    
   // ---------------------------------------------------------------------
   /**
    * Unverifies a user record (Sets verify to 0)
    *
    * @param string $username Username of record to update
    * @return boolean Query result
    */
   public function unverify($username)
   {
      $stmt = 'UPDATE ' . $this->table . ' SET `verify` = "0" WHERE `username` = "' . $username . '"';
      $result = $this->db->exec($stmt);
      return $result;
   }
    
   // ---------------------------------------------------------------------
   /**
    * Updates an existing user record from local class variables
    *
    * @param string $username Username of record to update
    * @return boolean Query result
    */
   public function update($username)
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
