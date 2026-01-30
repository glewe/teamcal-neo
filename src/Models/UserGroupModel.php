<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * UserGroupModel
 *
 * This class provides methods and properties for user group assignments.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class UserGroupModel
{
  public ?int    $id       = null;
  public ?string $username = null;
  public ?string $groupid  = null;
  public ?string $type     = null;

  private PDO    $db;
  private string $table         = '';
  private string $archive_table = '';
  private string $groups_table  = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null   $db   Database connection object
   * @param array|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db !== null && $conf !== null) {
      $this->db            = $db;
      $this->table         = $conf['db_table_user_group'];
      $this->archive_table = $conf['db_table_archive_user_group'];
      $this->groups_table  = (isset($conf['db_table_groups'])) ? $conf['db_table_groups'] : 'groups';
    }
    else {
      global $CONF, $DB;
      $this->db            = $DB->db;
      $this->table         = $CONF['db_table_user_group'];
      $this->archive_table = $CONF['db_table_archive_user_group'];
      $this->groups_table  = (isset($CONF['db_table_groups'])) ? $CONF['db_table_groups'] : 'groups';
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Archives all user_group records for a given user.
   *
   * @param string $username Username to archive
   *
   * @return bool Query result
   */
  public function archive(string $username): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->archive_table . ' SELECT t.* FROM ' . $this->table . ' t WHERE username = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Counts all members of a given group.
   *
   * @param string $groupid Group ID to search by
   *
   * @return int Record count
   */
  public function countMembers(string $groupid): int {
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE groupid = :groupid AND (type = :type1 OR type = :type2)");
    $type1 = 'manager';
    $type2 = 'member';
    $query->bindParam(':groupid', $groupid);
    $query->bindParam(':type1', $type1);
    $query->bindParam(':type2', $type2);
    $result = $query->execute();
    return $result ? (int) $query->fetchColumn() : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a new user-group record.
   *
   * @param string $username Username
   * @param string $groupid  Group ID
   * @param string $type     Type of membership (member, manager)
   *
   * @return bool Query result or false
   */
  public function createUserGroupEntry(string $username, string $groupid, string $type): bool {
    // Prevent duplicate entry
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :username AND groupid = :groupid');
    $query->bindParam(':username', $username);
    $query->bindParam(':groupid', $groupid);
    $query->execute();
    if ($query->fetchColumn() > 0) {
      return false;
    }
    $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, groupid, type) VALUES (:username, :groupid, :type)');
    $query2->bindParam(':username', $username);
    $query2->bindParam(':groupid', $groupid);
    $query2->bindParam(':type', $type);
    return $query2->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records.
   *
   * @param bool $archive Whether to search in archive table
   *
   * @return bool Query result
   */
  public function deleteAll(bool $archive = false): bool {
    $table  = $archive ? $this->archive_table : $this->table;
    $query  = $this->db->prepare('SELECT COUNT(*) FROM ' . $table);
    $result = $query->execute();
    if ($result && $query->fetchColumn()) {
      $query = $this->db->prepare('TRUNCATE TABLE ' . $table);
      return $query->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records for a given group.
   *
   * @param string $groupid Group ID to delete
   * @param bool   $archive Whether to use archive table
   *
   * @return bool Query result
   */
  public function deleteByGroup(string $groupid, bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE groupid = :groupid');
    $query->bindParam(':groupid', $groupid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a user-group record by ID from local class variable.
   *
   * @return bool Query result
   */
  public function deleteById(): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $this->id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records for a given user.
   *
   * @param string $username Username to delete
   * @param bool   $archive  Whether to use the archive table
   *
   * @return bool Query result
   */
  public function deleteByUser(string $username = '', bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE username = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all guests of a group.
   *
   * @param string $groupid Group ID
   * @param bool   $archive Whether to use the archive table
   *
   * @return bool Query result
   */
  public function deleteAllGuests(string $groupid = '', bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare("DELETE FROM " . $table . " WHERE groupid = :groupid AND type = 'guest'");
    $query->bindParam(':groupid', $groupid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all managers of a group.
   *
   * @param string $groupid Group ID
   * @param bool   $archive Whether to use the archive table
   *
   * @return bool Query result
   */
  public function deleteAllManagers(string $groupid = '', bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare("DELETE FROM " . $table . " WHERE groupid = :groupid AND type = 'manager'");
    $query->bindParam(':groupid', $groupid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all members of a group.
   *
   * @param string $groupid Group ID
   * @param bool   $archive Whether to use the archive table
   *
   * @return bool Query result
   */
  public function deleteAllMembers(string $groupid = '', bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare("DELETE FROM " . $table . " WHERE groupid = :groupid AND type = 'member'");
    $query->bindParam(':groupid', $groupid);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a record exists.
   *
   * @param string $username Username to find
   * @param bool   $archive  Whether to use the archive table
   *
   * @return bool True if found, false if not
   */
  public function exists(string $username = '', bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE username = :username');
    $query->bindParam(':username', $username);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn() > 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all records of a given group (managers and members).
   *
   * @param string $groupid Group ID to search by
   * @param string $sort    Sort order (ASC or DESC)
   *
   * @return array Array with all group records
   */
  public function getAllforGroup(string $groupid, string $sort = 'ASC'): array {
    $records = [];
    $sort    = strtoupper($sort) === 'DESC' ? 'DESC' : 'ASC';
    $query   = $this->db->prepare("SELECT * FROM {$this->table} WHERE groupid = :groupid AND (type = 'manager' OR type ='member') ORDER BY username $sort");
    $query->bindParam(':groupid', $groupid);
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
   * Gets all manager usernames of a given group ID.
   *
   * @param string $groupid Group ID to search by
   *
   * @return array Array with all group records
   */
  public function getAllManagerUsernames(string $groupid): array {
    $records = [];
    $query   = $this->db->prepare("SELECT username FROM {$this->table} WHERE groupid = :groupid AND type ='manager' ORDER BY username ASC");
    $query->bindParam(':groupid', $groupid);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = (string) $row['username'];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all member usernames of a given group ID.
   *
   * @param string $groupid Group ID to search by
   *
   * @return array Array with all group records
   */
  public function getAllMemberUsernames(string $groupid): array {
    $records = [];
    $query   = $this->db->prepare("SELECT username FROM {$this->table} WHERE groupid = :groupid AND type ='member' ORDER BY username ASC");
    $query->bindParam(':groupid', $groupid);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = (string) $row['username'];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all usernames of a given group (managers and members).
   *
   * @param string $username Username
   *
   * @return array Array with all group records
   */
  public function getAllManagedGroupsForUser(string $username): array {
    $records = [];
    $query   = $this->db->prepare(
      'SELECT g.* FROM ' . $this->groups_table . ' g JOIN ' . $this->table . ' ug ON g.id = ug.groupid WHERE ug.username = :username'
    );
    $query->bindParam(':username', $username);
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
   * Gets all usernames of a given group (managers and members and guests).
   *
   * @param string $groupid Group ID to search by
   * @param string $sort    Sort order
   *
   * @return array Array with all group records
   */
  public function getAllforGroupPlusGuests(string $groupid, string $sort = 'ASC'): array {
    $records = [];
    $sort    = strtoupper($sort) === 'DESC' ? 'DESC' : 'ASC';
    $query   = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE groupid = :groupid ORDER BY username ' . $sort);
    $query->bindParam(':groupid', $groupid);
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
   * Gets all records for a given user into an array.
   *
   * @param string $username Username to find
   *
   * @return array Array with all records
   */
  public function getAllforUser(string $username): array {
    $records = [];
    $query   = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = :username AND (type = 'manager' OR type = 'member')");
    $query->bindParam(':username', $username);
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
   * Gets all groups for a given user into an array where the index is the
   * groupid and the value is the membership type.
   *
   * @param string $username Username to find
   *
   * @return array Array with all records
   */
  public function getAllforUser2(string $username): array {
    $records = [];
    $query   = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = :username AND (type = 'manager' OR type = 'member')");
    $query->bindParam(':username', $username);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[$row['groupid']] = (string) $row['type'];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a record by ID.
   *
   * @param int $id Record ID to find
   *
   * @return bool Query result
   */
  public function getById(int $id): bool {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      $this->id       = (int) $row['id'];
      $this->username = (string) $row['username'];
      $this->groupid  = (string) $row['groupid'];
      $this->type     = (string) $row['type'];
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the first group a user has an entry for.
   *
   * @param string $username Username to find
   *
   * @return string Group ID of first group found or 'unknown'
   */
  public function getGroupName(string $username): string {
    $query = $this->db->prepare("SELECT groupid FROM {$this->table} WHERE username = :username AND (type = 'manager' OR type = 'member')");
    $query->bindParam(':username', $username);
    $result = $query->execute();
    if ($result && ($row = $query->fetch())) {
      return (string) $row['groupid'];
    }
    return 'unknown';
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all group managers of a given group.
   *
   * @param string $groupid Group ID to check
   *
   * @return array Array with usernames of group managers
   */
  public function getGroupManagers(string $groupid): array {
    $records = [];
    $type    = 'manager';
    $query   = $this->db->prepare('SELECT username FROM ' . $this->table . ' WHERE groupid = :groupid AND type = :type');
    $query->bindParam(':groupid', $groupid);
    $query->bindParam(':type', $type);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = (string) $row['username'];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all groups where the given user is guest.
   *
   * @param string $username Username to check
   *
   * @return array Array with usernames of group managers
   */
  public function getGuestships(string $username): array {
    $records = [];
    $type    = 'guest';
    $query   = $this->db->prepare("SELECT groupid FROM {$this->table} WHERE username = :username AND type = :type");
    $query->bindParam(':username', $username);
    $query->bindParam(':type', $type);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = (string) $row['groupid'];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all managed groups of a given user.
   *
   * @param string $username Username to check
   *
   * @return array Array with usernames of group managers
   */
  public function getManagerships(string $username): array {
    $records = [];
    $type    = 'manager';
    $query   = $this->db->prepare("SELECT groupid FROM {$this->table} WHERE username = :username AND type = :type");
    $query->bindParam(':username', $username);
    $query->bindParam(':type', $type);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = (string) $row['groupid'];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all groups that a given user is member of.
   *
   * @param string $username Username to check
   *
   * @return array Array with usernames of group managers
   */
  public function getMemberships(string $username): array {
    $records = [];
    $type    = 'member';
    $query   = $this->db->prepare("SELECT groupid FROM {$this->table} WHERE username = :username AND type = :type");
    $query->bindParam(':username', $username);
    $query->bindParam(':type', $type);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $records[] = (string) $row['groupid'];
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given user is manager of any group.
   *
   * @param string $username Username to check
   *
   * @return bool True if he is, false if not
   */
  public function isGroupManager(string $username): bool {
    $type  = 'manager';
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE username = :username AND type = :type");
    $query->bindParam(':username', $username);
    $query->bindParam(':type', $type);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn() > 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given user is manager of a given group.
   *
   * @param string $username Username to check
   * @param string $groupid  Group ID to check
   *
   * @return bool True if he is, false if not
   */
  public function isGroupManagerOfGroup(string $username, string $groupid): bool {
    $type  = 'manager';
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE username = :username AND groupid = :groupid AND type = :type");
    $query->bindParam(':username', $username);
    $query->bindParam(':groupid', $groupid);
    $query->bindParam(':type', $type);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn() > 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given user is manager of another given user.
   *
   * @param string $user1 Username to check whether he is manager of user 2
   * @param string $user2 Username to check whether he is managed by user 1
   *
   * @return bool True if he is, false if not
   */
  public function isGroupManagerOfUser(string $user1, string $user2): bool {
    $query = $this->db->prepare('SELECT groupid FROM ' . $this->table . ' WHERE username = :username');
    $query->bindParam(':username', $user2);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $type   = 'manager';
        $query2 = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE username = :username AND groupid = :groupid AND type = :type");
        $query2->bindParam(':username', $user1);
        $query2->bindParam(':groupid', $row['groupid']);
        $query2->bindParam(':type', $type);
        $result2 = $query2->execute();
        if ($result2 && $query2->fetchColumn() > 0) {
          return true;
        }
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given user is member of any group.
   *
   * @param string $username Username to check
   *
   * @return bool True if he is, false if not
   */
  public function isGroupMember(string $username): bool {
    $type  = 'member';
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE username = :username AND type = :type");
    $query->bindParam(':username', $username);
    $query->bindParam(':type', $type);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn() > 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given user is guest of any group.
   *
   * @param string $username Username to check
   *
   * @return bool True if he is, false if not
   */
  public function isGuest(string $username): bool {
    $type  = 'guest';
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE username = :username AND type = :type");
    $query->bindParam(':username', $username);
    $query->bindParam(':type', $type);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn() > 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given user is guest of a given group.
   *
   * @param string $username Username to check
   * @param string $groupid  Group ID to check
   *
   * @return bool True if he is, false if not
   */
  public function isGuestOfGroup(string $username, string $groupid): bool {
    $type  = 'guest';
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE username = :username AND groupid = :groupid AND type = :type");
    $query->bindParam(':username', $username);
    $query->bindParam(':groupid', $groupid);
    $query->bindParam(':type', $type);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn() > 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given user is member od a given group.
   *
   * @param string $username Username
   * @param string $groupid  Group ID
   *
   * @return bool True if member, false if not
   */
  public function isMemberOfGroup(string $username, string $groupid): bool {
    $type  = 'member';
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :username AND groupid = :groupid AND type = :type');
    $query->bindParam(':username', $username);
    $query->bindParam(':groupid', $groupid);
    $query->bindParam(':type', $type);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn() > 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given user is member od a given group.
   *
   * @param string $username Username
   * @param string $groupid  Group ID
   *
   * @return bool True if member, false if not
   */
  public function isMemberOrManagerOfGroup(string $username, string $groupid): bool {
    $type1 = 'manager';
    $type2 = 'member';
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :username AND groupid = :groupid AND (type = :type1 OR type = :type2)');
    $query->bindParam(':username', $username);
    $query->bindParam(':groupid', $groupid);
    $query->bindParam(':type1', $type1);
    $query->bindParam(':type2', $type2);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn() > 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given user is member or guest of a given group.
   *
   * @param string $username Username
   * @param string $groupid  Group ID
   *
   * @return bool True if member, false if not
   */
  public function isMemberOrGuestOfGroup(string $username, string $groupid): bool {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :username AND groupid = :groupid');
    $query->bindParam(':username', $username);
    $query->bindParam(':groupid', $groupid);
    $result = $query->execute();
    return (bool) ($result && $query->fetchColumn() > 0);
  }

  //---------------------------------------------------------------------------
  /**
   * Restores all user_group records for a given user.
   *
   * @param string $username Username to restore
   *
   * @return bool Query result
   */
  public function restore(string $username): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archive_table . ' a WHERE username = :username');
    $query->bindParam(':username', $username);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Saves/updates a record.
   *
   * @param string $username Username
   * @param string $groupid  Group ID
   * @param string $type     Type of relationship
   *
   * @return bool Query result
   */
  public function save(string $username, string $groupid, string $type): bool {
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE username = :username AND groupid = :groupid");
    $query->bindParam(':username', $username);
    $query->bindParam(':groupid', $groupid);
    $result = $query->execute();
    if ($result && $query->fetchColumn() > 0) {
      $query2 = $this->db->prepare('UPDATE ' . $this->table . ' SET type = :type WHERE username = :username AND groupid = :groupid');
      $query2->bindParam(':type', $type);
      $query2->bindParam(':username', $username);
      $query2->bindParam(':groupid', $groupid);
    }
    else {
      $query2 = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, groupid, type) VALUES (:username, :groupid, :type)');
      $query2->bindParam(':username', $username);
      $query2->bindParam(':groupid', $groupid);
      $query2->bindParam(':type', $type);
    }
    return $query2->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether two given users share membership or guestship of at least one group.
   *
   * @param string $user1 First username
   * @param string $user2 Second username
   *
   * @return bool True if they do, false if not
   */
  public function shareGroups(string $user1, string $user2): bool {
    $query = $this->db->prepare('SELECT groupid FROM ' . $this->table . ' WHERE username = :username AND (type = :type1 OR type = :type2 OR type = :type3)');
    $type1 = 'manager';
    $type2 = 'member';
    $type3 = 'guest';
    $query->bindParam(':username', $user1);
    $query->bindParam(':type1', $type1);
    $query->bindParam(':type2', $type2);
    $query->bindParam(':type3', $type3);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $query2 = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :username AND groupid = :groupid AND (type = :type1 OR type = :type2 OR type = :type3)');
        $query2->bindParam(':username', $user2);
        $query2->bindParam(':groupid', $row['groupid']);
        $query2->bindParam(':type1', $type1);
        $query2->bindParam(':type2', $type2);
        $query2->bindParam(':type3', $type3);
        $result2 = $query2->execute();
        if ($result2 && $query2->fetchColumn() > 0) {
          return true;
        }
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether two given users share membership or managership of at least one group.
   *
   * @param string $user1 First username
   * @param string $user2 Second username
   *
   * @return bool True if they do, false if not
   */
  public function shareGroupMemberships(string $user1, string $user2): bool {
    $type1 = 'manager';
    $type2 = 'member';
    $query = $this->db->prepare('SELECT groupid FROM ' . $this->table . ' WHERE username = :username AND (type = :type1 OR type = :type2)');
    $query->bindParam(':username', $user1);
    $query->bindParam(':type1', $type1);
    $query->bindParam(':type2', $type2);
    $result = $query->execute();
    if ($result) {
      while ($row = $query->fetch()) {
        $query2 = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE username = :username AND groupid = :groupid AND (type = :type1 OR type = :type2)');
        $query2->bindParam(':username', $user2);
        $query2->bindParam(':groupid', $row['groupid']);
        $query2->bindParam(':type1', $type1);
        $query2->bindParam(':type2', $type2);
        $result2 = $query2->execute();
        if ($result2 && $query2->fetchColumn() > 0) {
          return true;
        }
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a user-group record from local class variables.
   *
   * @return bool Query result
   */
  public function update(): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET username = :username, groupid = :groupid, type = :type WHERE id = :id');
    $query->bindParam(':username', $this->username);
    $query->bindParam(':groupid', $this->groupid);
    $query->bindParam(':type', $this->type);
    $query->bindParam(':id', $this->id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a groupname in all records.
   *
   * @param string $groupold Old group ID
   * @param string $groupnew New group ID
   *
   * @return bool Query result
   */
  public function updateGroupname(string $groupold, string $groupnew): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET groupid = :groupnew WHERE groupid = :groupold');
    $query->bindParam(':groupnew', $groupnew);
    $query->bindParam(':groupold', $groupold);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates the type of membership.
   *
   * @param string $username Username to update
   * @param string $groupid  Group ID to update
   * @param string $type     New membership type
   *
   * @return bool Query result
   */
  public function updateUserGroupType(string $username, string $groupid, string $type): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET type = :type WHERE username = :username AND groupid = :groupid');
    $query->bindParam(':type', $type);
    $query->bindParam(':username', $username);
    $query->bindParam(':groupid', $groupid);
    return $query->execute();
  }
}
