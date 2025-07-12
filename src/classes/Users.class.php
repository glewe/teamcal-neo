<?php

/**
 * Users
 *
 * This class provides methods and properties for users.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Users {
  public string $username = '';
  public string $password = '';
  public string $firstname = '';
  public string $lastname = '';
  public string $email = '';
  public string $order_key = '';
  public int $role = 2;
  public int $locked = 0;
  public int $hidden = 0;
  public int $onhold = 0;
  public int $verify = 0;
  public int $bad_logins = 0;
  public string $grace_start = DEFAULT_TIMESTAMP;
  public string $last_pw_change = DEFAULT_TIMESTAMP;
  public string $last_login = DEFAULT_TIMESTAMP;
  public string $created = DEFAULT_TIMESTAMP;
  public string $bad_logins_start = '';

  private $db = null;
  private string $table = '';
  private string $archive_table = '';
  private string $config_table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);         // Needed for PDO::errorInfo()
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Needed for PDO:errorCode()
    $this->table = $CONF['db_table_users'];
    $this->archive_table = $CONF['db_table_archive_users'];
    $this->config_table = $CONF['db_table_config'];
  }

  //---------------------------------------------------------------------------
  /**
   * Archives a user record
   *
   * @param string $username Username to archive
   * @return boolean Query result
   */
  public function archive(string $username): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT u.* FROM ' . $this->table . ' u WHERE username = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Counts the records
   *
   * @return integer
   */
  public function count(): int {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      return $row[0];
    }
    return 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all user records
   *
   * @param boolean $countAdmin Flag to include/exclude admin account
   * @param boolean $countHidden Flag to include/exclude hidden accounts
   * @return integer Count
   */
  public function countUsers(bool $countAdmin = false, bool $countHidden = false): int {
    if ($countHidden) {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table);
    } else {
      $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE hidden = 0');
    }
    $result = $query->execute();
    if ($result) {
      if ($countAdmin) {
        return $query->fetchColumn();
      } else {
        return $query->fetchColumn() - 1;
      }
    } else {
      return 0;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a new user record from local variables
   *
   * @return boolean Query result
   */
  public function create(): bool {
    $stmt = 'INSERT INTO ' . $this->table . ' (username, password, firstname, lastname, email, order_key, role, locked, hidden, onhold, verify, bad_logins, grace_start, last_pw_change, last_login, created) ';
    $stmt .= 'VALUES (:val1, :val2, :val3, :val4, :val5, :val6, :val7, :val8, :val9, :val10, :val11, :val12, :val13, :val14, :val15, :val16)';
    $query = $this->db->prepare($stmt);
    $query->bindParam('val1', $this->username);
    $query->bindParam('val2', $this->password);
    $query->bindParam('val3', $this->firstname);
    $query->bindParam('val4', $this->lastname);
    $query->bindParam('val5', $this->email);
    $query->bindParam('val6', $this->order_key);
    $query->bindParam('val7', $this->role);
    $query->bindParam('val8', $this->locked);
    $query->bindParam('val9', $this->hidden);
    $query->bindParam('val10', $this->onhold);
    $query->bindParam('val11', $this->verify);
    $query->bindParam('val12', $this->bad_logins);
    $query->bindParam('val13', $this->grace_start);
    $query->bindParam('val14', $this->last_pw_change);
    $query->bindParam('val15', $this->last_login);
    $query->bindParam('val16', $this->created);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * @param boolean $archive Whether to search in archive table
   * @return boolean Query result
   */
  public function deleteAll(bool $archive = false): bool {
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username <> :val1');
    $val1 = 'admin';
    $query->bindParam('val1', $val1);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a user record by username
   *
   * @param string $username Username to find
   * @param boolean $archive Whether to search in archive table
   * @return boolean Query result
   */
  public function deleteByName(string $username = '', bool $archive = false): bool {
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a user record exists
   *
   * @param string $username Username to find
   * @param boolean $archive Whether to search in archive table
   * @return boolean True if found, false if not
   */
  public function exists(string $username = '', bool $archive = false): bool {
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    $result = $query->execute();
    return $result && $query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Finds a user record by username and fills values into local variables
   *
   * @param string $username Username to find
   * @param boolean $archive Whether to search in archive table
   * @return boolean Query result
   */
  public function findByName(string $username = '', bool $archive = false): bool {
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }
    $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      $this->username = $row['username'];
      $this->password = $row['password'];
      $this->firstname = $row['firstname'];
      $this->lastname = $row['lastname'];
      $this->email = $row['email'];
      $this->order_key = $row['order_key'];
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

  //---------------------------------------------------------------------------
  /**
   * Finds a user record by token and fills values into local variables
   *
   * @param string $token Token to find
   * @return boolean Query result
   */
  public function findByToken(string $token): bool {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE md5(CONCAT("PasswordResetRequestFor",username)) = :val1');
    $query->bindParam('val1', $token);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      $this->username = $row['username'];
      $this->password = $row['password'];
      $this->firstname = $row['firstname'];
      $this->lastname = $row['lastname'];
      $this->email = $row['email'];
      $this->order_key = $row['order_key'];
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

  //---------------------------------------------------------------------------
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
  public function getAll(string $order1 = 'lastname', string $order2 = 'firstname', string $sort = 'ASC', bool $archive = false, bool $includeAdmin = false): array {
    if ($this->useOrderKey()) {
      $order1 = 'order_key';
      $order2 = 'lastname';
    }

    $records = array();

    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }

    if ($includeAdmin) {
      $query = $this->db->prepare('SELECT * FROM ' . $table . ' ORDER BY ' . $order1 . ' ' . $sort . ', ' . $order2 . ' ' . $sort);
    } else {
      $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE username != :val1 ORDER BY ' . $order1 . ' ' . $sort . ', ' . $order2 . ' ' . $sort);
      $val1 = 'admin';
      $query->bindParam('val1', $val1);
    }

    $result = $query->execute();

    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = $row;
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all records for a given email address
   *
   * @param string $email Email to find
   * @return array | boolean Array with records
   */
  public function getAllForEmail(string $email): array|false {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE email = :val1 ORDER BY lastname ASC, firstname ASC');
    $query->bindParam('val1', $email);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = $row;
      }
      return $records;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Get all users for a given role
   *
   * @param string $role
   * @return array
   */
  public function getAllForRole(string $role): array|false {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE role = :role ORDER BY lastname ASC, firstname ASC');
    $query->bindParam('role', $role);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = $row;
      }
      return $records;
    }
    return false;
  }

  //---------------------------------------------------------------------------
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
  public function getAllButHidden(string $order1 = 'lastname', string $order2 = 'firstname', string $sort = 'ASC', bool $archive = false, bool $includeAdmin = false): array {
    if ($this->useOrderKey()) {
      $order1 = 'order_key';
      $order2 = 'lastname';
    }

    $records = array();
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }

    if ($includeAdmin) {
      $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE hidden != 1 ORDER BY ' . $order1 . ' ' . $sort . ', ' . $order2 . ' ' . $sort);
    } else {
      $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE username != :val1 AND hidden != 1 ORDER BY ' . $order1 . ' ' . $sort . ', ' . $order2 . ' ' . $sort);
    }
    $val1 = 'admin';
    $query->bindParam('val1', $val1);

    $result = $query->execute();

    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = $row;
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all records with likeness in username, lastname or firstname
   *
   * @param string $like Likeness to search for
   * @return array Array with records
   */
  public function getAllLike(string $like, bool $archive = false): array {
    $records = array();
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }
    $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE firstname LIKE :val1 OR lastname LIKE :val2 OR username LIKE :val3 ORDER BY lastname ASC, firstname ASC');
    $val = '%' . $like . '%';
    $query->bindParam('val1', $val);
    $query->bindParam('val2', $val);
    $query->bindParam('val3', $val);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        if ($row['username'] != 'admin') {
          $records[] = $row;
        }
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a record for a given username
   *
   * @param string $uname Username to search for
   * @param boolean $archive Whether to use archive table
   * @return array Array with records
   */
  public function getByUsername(string $uname, bool $archive = false): array {
    $records = array();
    if ($archive) {
      $table = $this->archive_table;
    } else {
      $table = $this->table;
    }
    $query = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE username = :val1');
    $query->bindParam('val1', $uname);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        if ($row['username'] != 'admin') {
          $records[] = $row;
        }
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the E-mail address of a given username
   *
   * @param string $username Username to find
   * @return string Email address or empty
   */
  public function getEmail(string $username): string {
    $query = $this->db->prepare('SELECT email FROM ' . $this->table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      return $row['email'];
    }
    return '';
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the fullname of a given username
   *
   * @param string $username Username to find
   * @return string Fullname or empty
   */
  public function getFullname(string $username): string {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      if (strlen($row['firstname'])) {
        $fullname = $row['firstname'];
        if (strlen($row['lastname'])) {
          $fullname .= ' ' . $row['lastname'];
        }
      } else {
        if (strlen($row['lastname'])) {
          $fullname = $row['lastname'];
        } else {
          $fullname = 'no name';
        }
      }
      return $fullname;
    }
    return '?';
  }

  //---------------------------------------------------------------------------
  /**
   * Gets Lastname, Firstname of a given username
   *
   * @param string $username Username to find
   * @return string Lastname, Firtsname or empty
   */
  public function getLastFirst(string $username): string {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      if (strlen($row['lastname'])) {
        $lastfirst = $row['lastname'];
        if (strlen($row['firstname'])) {
          $lastfirst .= ', ' . $row['firstname'];
        }
      } else {
        if (strlen($row['firstname'])) {
          $lastfirst = $row['firstname'];
        } else {
          $lastfirst = 'no name';
        }
      }
      return $lastfirst;
    }
    return '?';
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the role of a given username
   *
   * @param string $username Username to find
   * @return string Role or empty
   */
  public function getRole(string $username): string {
    $query = $this->db->prepare('SELECT role FROM ' . $this->table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      return $row['role'];
    }
    return '';
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all usernames
   *
   * @return array Array with usernames
   */
  public function getUsernames(): array {
    $records = array();
    $query = $this->db->prepare('SELECT username FROM ' . $this->table . ' ORDER BY username ASC');
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = $row['username'];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a user has a certain role
   *
   * @param string $username Username to find
   * @param string $role Role to check
   * @return boolean True or False
   */
  public function hasRole(string $username, string $role): bool {
    $query = $this->db->prepare('SELECT role FROM ' . $this->table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      return $row['role'] == $role;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a user is hidden
   *
   * @param string $username Username to find
   * @return boolean True or False
   */
  public function isHidden(string $username): bool|int {
    $query = $this->db->prepare('SELECT hidden FROM ' . $this->table . ' WHERE username = :val1');
    $query->bindParam('val1', $username);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      return $row['hidden'];
    }
    return true;
  }

  //---------------------------------------------------------------------------
  /**
   * Restore archived user records
   *
   * @param string $username Username to restore
   * @return boolean Query result
   */
  public function restore(string $username): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = :val1');
    $query->bindParam('val1', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Sets the role ID for a given user
   *
   * @param string $username Username to find
   * @param string $role Role to set
   * @return boolean True or False
   */
  public function setRole(string $username, string $role): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET role = :val1 WHERE username = :val2');
    $query->bindParam('val1', $role);
    $query->bindParam('val2', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Unhides a user record
   *
   * @param string $username Username of record to update
   * @return boolean Query result
   */
  public function unhide(string $username): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET `hidden` = :val1 WHERE `username` = :val2');
    $val1 = '0';
    $query->bindParam('val1', $val1);
    $query->bindParam('val2', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Unholds a user record
   *
   * @param string $username Username of record to update
   * @return boolean Query result
   */
  public function unhold(string $username): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET `onhold` = :val2 WHERE `username` = :val1');
    $query->bindParam('val1', $username);
    $val2 = '0';
    $query->bindParam('val2', $val2);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Unlocks a user record
   *
   * @param string $username Username of record to update
   * @return boolean Query result
   */
  public function unlock(string $username): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET `locked` = :val2 WHERE `username` = :val1');
    $query->bindParam('val1', $username);
    $val2 = '0';
    $query->bindParam('val2', $val2);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Unverifies a user record (Sets verify to 0)
   *
   * @param string $username Username of record to update
   * @return boolean Query result
   */
  public function unverify(string $username): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET `verify` = :val2 WHERE `username` = :val1');
    $query->bindParam('val1', $username);
    $val2 = '0';
    $query->bindParam('val2', $val2);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates an existing user record from local class variables
   *
   * @param string $username Username of record to update
   * @return boolean Query result
   */
  public function update(string $username): bool {
    $stmt = "UPDATE {$this->table} SET
      username = :username,
      password = :password,
      firstname = :firstname,
      lastname = :lastname,
      email = :email,
      order_key = :order_key,
      role = :role,
      locked = :locked,
      hidden = :hidden,
      onhold = :onhold,
      verify = :verify,
      bad_logins = :bad_logins,
      grace_start = :grace_start,
      last_pw_change = :last_pw_change,
      last_login = :last_login,
      created = :created
      WHERE username = :where_username";
    $query = $this->db->prepare($stmt);
    $query->bindParam(':username', $this->username);
    $query->bindParam(':password', $this->password);
    $query->bindParam(':firstname', $this->firstname);
    $query->bindParam(':lastname', $this->lastname);
    $query->bindParam(':email', $this->email);
    $query->bindParam(':order_key', $this->order_key);
    $query->bindParam(':role', $this->role);
    $query->bindParam(':locked', $this->locked);
    $query->bindParam(':hidden', $this->hidden);
    $query->bindParam(':onhold', $this->onhold);
    $query->bindParam(':verify', $this->verify);
    $query->bindParam(':bad_logins', $this->bad_logins);
    $query->bindParam(':grace_start', $this->grace_start);
    $query->bindParam(':last_pw_change', $this->last_pw_change);
    $query->bindParam(':last_login', $this->last_login);
    $query->bindParam(':created', $this->created);
    $query->bindParam(':where_username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * User order key
   *
   * @return boolean Query result
   */
  private function useOrderKey(): bool|int|string {
    $query = $this->db->prepare("SELECT value FROM {$this->config_table} WHERE name = 'sortByOrderKey'");
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      return $row['value'];
    }
    return false;
  }
}
