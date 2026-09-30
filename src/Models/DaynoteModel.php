<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * DaynoteModel
 *
 * This class provides methods and properties for daynotes.
 *
 * The public API takes/returns usernames and region ID strings (matching
 * every caller's existing data), resolving internally to the nullable
 * tcneo_daynotes.user_id/region_id FK columns the table actually stores -
 * same approach as AbsenceDayModel/AllowanceModel. Two sentinels collapse
 * onto NULL: username '' or 'all' (global note, was `username = 'all'`) and
 * region '', '0' or 'default' (regionless, was several inconsistent string
 * sentinels - see getAllRegionless()). Because both FK columns are
 * nullable, every comparison against them uses MySQL's NULL-safe `<=>`
 * operator instead of `=`, so one query form handles both the "real value"
 * and "NULL" cases.
 *
 * The `yyyymmdd` column was also renamed to `day` and changed from an
 * 8-digit string to a real DATE type; toDate()/toYyyymmdd() convert between
 * the DATE column and the 8-digit YYYYMMDD strings every caller uses.
 *
 * `getForMonthUser()`/`getforMonth()` and the `$daynotes` property they
 * filled were dropped - grep confirmed zero callers anywhere in the
 * codebase (the per-day `get()` calls elsewhere cover this need instead).
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class DaynoteModel
{
  public ?int   $id           = null;
  public string $yyyymmdd     = '';
  public string $daynote      = '';
  public string $username     = '';
  public string $region       = '';
  public string $color        = '';
  public string $confidential = '';

  private ?PDO   $db            = null;
  private string $table         = '';
  private string $archive_table = '';
  private string $users_table   = '';

  /** @var array<string, int|null> */
  private array $userIdCache = [];
  /** @var array<int, string> */
  private array $usernameCache = [];

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null             $db   Database connection object
   * @param array<string, string>|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db && $conf) {
      $this->db            = $db;
      $this->table         = $conf['db_table_daynotes'];
      $this->archive_table = $conf['db_table_archive_daynotes'];
      $this->users_table   = $conf['db_table_users'];
    }
    else {
      global $CONF, $dbModel;
      $this->db            = $dbModel->db;
      $this->table         = $CONF['db_table_daynotes'];
      $this->archive_table = $CONF['db_table_archive_daynotes'];
      $this->users_table   = $CONF['db_table_users'];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Resolves a username to its tcneo_users.id, caching the result.
   *
   * 'all' (the global-note sentinel) resolves to null, which the queries compare with the
   * null-safe <=> operator. Any other username that is not a known user resolves to 0, an
   * id that matches no row: resolving it to null as well would make exists(), deleteByUser()
   * and friends act on the global notes whenever the user does not exist (for example
   * exists() on an archived user, which is how UserService::restoreUser() checks that
   * nothing is in the way).
   *
   * @param string $username Username to resolve
   *
   * @return int|null Resolved id, null for the global sentinel, 0 for an unknown user
   */
  private function resolveUserId(string $username): ?int {
    if ($username === 'all') {
      return null;
    }
    if (array_key_exists($username, $this->userIdCache)) {
      return $this->userIdCache[$username];
    }
    $query = $this->db->prepare('SELECT id FROM ' . $this->users_table . ' WHERE username = :username');
    $query->bindParam(':username', $username);
    $query->execute();
    $id = $query->fetchColumn();
    if ($id === false) {
      return 0; // do not cache misses - the user may be created later in this request
    }
    return $this->userIdCache[$username] = (int) $id;
  }

  //---------------------------------------------------------------------------
  /**
   * Resolves a tcneo_users.id back to its username, caching the result.
   * null resolves back to 'all' (the global-note sentinel).
   *
   * @param int|null $userId User ID to resolve
   *
   * @return string Username, or 'all' for a global note
   */
  private function resolveUsername(?int $userId): string {
    if ($userId === null) {
      return 'all';
    }
    if (isset($this->usernameCache[$userId])) {
      return $this->usernameCache[$userId];
    }
    $query = $this->db->prepare('SELECT username FROM ' . $this->users_table . ' WHERE id = :id');
    $query->bindValue(':id', $userId, PDO::PARAM_INT);
    $query->execute();
    $username = $query->fetchColumn();
    return $this->usernameCache[$userId] = ($username !== false ? (string) $username : '');
  }

  //---------------------------------------------------------------------------
  /**
   * Normalizes a region ID string to its nullable int form.
   * '', '0' and 'default' (the old "unset" sentinels) all resolve to null.
   *
   * @param string $region Region ID string
   *
   * @return int|null
   */
  private static function normalizeRegionId(string $region): ?int {
    if ($region === '' || $region === '0' || $region === 'default') {
      return null;
    }
    return (int) $region;
  }

  //---------------------------------------------------------------------------
  /**
   * Converts a nullable region_id back to the string form callers expect.
   *
   * @param int|string|null $regionId
   *
   * @return string
   */
  private static function regionToString(int|string|null $regionId): string {
    return $regionId !== null ? (string) $regionId : '';
  }

  //---------------------------------------------------------------------------
  /**
   * Converts an 8-digit YYYYMMDD string (the format every caller uses) to
   * the Y-m-d format the `day` DATE column stores.
   *
   * @param string $yyyymmdd 8-digit date, e.g. '20260601'
   *
   * @return string Y-m-d, e.g. '2026-06-01'
   */
  private static function toDate(string $yyyymmdd): string {
    return substr($yyyymmdd, 0, 4) . '-' . substr($yyyymmdd, 4, 2) . '-' . substr($yyyymmdd, 6, 2);
  }

  //---------------------------------------------------------------------------
  /**
   * Converts a `day` column value (Y-m-d) back to the 8-digit YYYYMMDD
   * format callers expect.
   *
   * @param string $date Y-m-d, e.g. '2026-06-01'
   *
   * @return string 8-digit date, e.g. '20260601'
   */
  private static function toYyyymmdd(string $date): string {
    return str_replace('-', '', $date);
  }

  //---------------------------------------------------------------------------
  /**
   * Archives all records for a given user.
   *
   * @param string $username Username to archive
   *
   * @return bool Query result
   */
  public function archive(string $username): bool {
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare("INSERT INTO {$this->archive_table} SELECT t.* FROM {$this->table} t WHERE user_id <=> :user_id");
    $query->bindValue(':user_id', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a daynote record from class variables.
   *
   * @return bool Query result or false
   */
  public function create(): bool {
    //
    // Make sure no daynote exists for this day
    //
    $this->delete($this->yyyymmdd, $this->username, $this->region);
    $userId   = $this->resolveUserId($this->username);
    $regionId = self::normalizeRegionId($this->region);
    $day      = self::toDate($this->yyyymmdd);
    $query    = $this->db->prepare("INSERT INTO {$this->table} (day, user_id, region_id, daynote, color, confidential) VALUES (:day, :user_id, :region_id, :daynote, :color, :confidential)");
    $query->bindParam(':day', $day);
    $query->bindValue(':user_id', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $query->bindValue(':region_id', $regionId, $regionId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $query->bindParam(':daynote', $this->daynote);
    $query->bindParam(':color', $this->color);
    $query->bindParam(':confidential', $this->confidential);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a daynote record for a given date/username/region.
   *
   * @param string $yyyymmdd 8 character date (YYYYMMDD) to find for deletion
   * @param string $username Username to find for deletion
   * @param string $region   Region to find for deletion
   *
   * @return bool Query result
   */
  public function delete(string $yyyymmdd = '', string $username = '', string $region = 'default'): bool {
    $userId   = $this->resolveUserId($username);
    $regionId = self::normalizeRegionId($region);
    $day      = self::toDate($yyyymmdd);
    $query    = $this->db->prepare("DELETE FROM {$this->table} WHERE day = :day AND user_id <=> :user_id AND region_id <=> :region_id");
    $query->bindParam(':day', $day);
    $query->bindValue(':user_id', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $query->bindValue(':region_id', $regionId, $regionId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records.
   *
   * @param bool $archive Whether to use the archive table
   *
   * @return bool Query result or false
   */
  public function deleteAll(bool $archive = false): bool {
    $table = $archive ? $this->archive_table : $this->table;
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$table}");
    $query->execute();
    if ($query->fetchColumn()) {
      $query = $this->db->prepare("TRUNCATE TABLE {$table}");
      return $query->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all daynotes before (and including) a given day.
   *
   * @param string $yyyymmdd 8 character date (YYYYMMDD) to find for deletion
   *
   * @return bool Query result
   */
  public function deleteAllBefore(string $yyyymmdd = ''): bool {
    $day   = self::toDate($yyyymmdd);
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE day <= :day");
    $query->bindParam(':day', $day);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all daynotes for a region.
   *
   * @param string $region Region to find for deletion
   *
   * @return bool Query result
   */
  public function deleteAllForRegion(string $region = 'default'): bool {
    $regionId = self::normalizeRegionId($region);
    $query    = $this->db->prepare("DELETE FROM {$this->table} WHERE region_id <=> :region_id");
    $query->bindValue(':region_id', $regionId, $regionId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all daynotes for a user.
   *
   * @param string $username Username to find for deletion
   * @param bool   $archive  Whether to use the archive table
   *
   * @return bool Query result
   */
  public function deleteByUser(string $username = '', bool $archive = false): bool {
    $table  = $archive ? $this->archive_table : $this->table;
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare("DELETE FROM {$table} WHERE user_id <=> :user_id");
    $query->bindValue(':user_id', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Delete all global (all-users) daynotes.
   *
   * @return bool Query result or false
   */
  public function deleteAllGlobal(): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE user_id IS NULL");
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all daynotes for a date and user.
   *
   * @param string $date     Date to find for deletion
   * @param string $username Username to find for deletion
   *
   * @return bool Query result
   */
  public function deleteByDateAndUser(string $date, string $username): bool {
    $userId = $this->resolveUserId($username);
    $day    = self::toDate($date);
    $query  = $this->db->prepare("DELETE FROM {$this->table} WHERE day = :day AND user_id <=> :user_id");
    $query->bindParam(':day', $day);
    $query->bindValue(':user_id', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a daynote record by id.
   *
   * @param string $id ID to find for deletion
   *
   * @return bool Query result
   */
  public function deleteById(string $id): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
    $query->bindParam(':id', $id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether records for a given user exist.
   *
   * @param string $username Username to find
   * @param bool   $archive  Whether to use the archive table
   *
   * @return bool True if found, false if not
   */
  public function exists(string $username, bool $archive = false): bool {
    $table  = $archive ? $this->archive_table : $this->table;
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare("SELECT COUNT(*) FROM {$table} WHERE user_id <=> :user_id");
    $query->bindValue(':user_id', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $query->execute();
    return (bool) $query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a daynote record for a given date/username/region.
   *
   * @param string $yyyymmdd    Date (YYYYMMDD) to find
   * @param string $username    Username to find
   * @param string $region      Region to find
   * @param bool   $replaceCRLF Flag to replace CRLF with <br>
   *
   * @return bool Query result
   */
  public function get(string $yyyymmdd = '', string $username = '', string $region = 'default', bool $replaceCRLF = false): bool {
    $userId   = $this->resolveUserId($username);
    $regionId = self::normalizeRegionId($region);
    $day      = self::toDate($yyyymmdd);
    $query    = $this->db->prepare("SELECT * FROM {$this->table} WHERE day = :val1 AND user_id <=> :val2 AND region_id <=> :val3");
    $query->bindParam('val1', $day);
    $query->bindValue('val2', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $query->bindValue('val3', $regionId, $regionId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      $this->id           = (int) $row['id'];
      $this->yyyymmdd     = self::toYyyymmdd((string) $row['day']);
      $this->username     = $this->resolveUsername($row['user_id'] !== null ? (int) $row['user_id'] : null);
      $this->region        = self::regionToString($row['region_id']);
      $this->daynote      = $replaceCRLF ? str_replace("\r\n", "<br>", (string) $row['daynote']) : (string) $row['daynote'];
      $this->color        = (string) $row['color'];
      $this->confidential = (string) $row['confidential'];
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all daynotes with no region set.
   *
   * @return array<int, array<string, mixed>>|bool Query result or false on failure
   */
  public function getAllRegionless(): array|bool {
    $records = [];
    $query   = $this->db->prepare("SELECT * FROM {$this->table} WHERE region_id IS NULL");
    $result  = $query->execute();
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
   * Finds a daynote record by id and loads values in local class variables.
   *
   * @param string $id          ID to find
   * @param bool   $replaceCRLF Flag to replace CRLF with <br>
   *
   * @return bool Query result
   */
  public function getById(string $id, bool $replaceCRLF = false): bool {
    $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :val1");
    $query->bindParam('val1', $id);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      $this->id           = (int) $row['id'];
      $this->yyyymmdd     = self::toYyyymmdd((string) $row['day']);
      $this->username     = $this->resolveUsername($row['user_id'] !== null ? (int) $row['user_id'] : null);
      $this->region        = self::regionToString($row['region_id']);
      $this->daynote      = $replaceCRLF ? str_replace("\r\n", "<br>", (string) $row['daynote']) : (string) $row['daynote'];
      $this->color        = (string) $row['color'];
      $this->confidential = (string) $row['confidential'];
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a daynote is confidential.
   *
   * @param string $id Record ID
   *
   * @return bool|string Confidential status or false if not found
   */
  public function isConfidential(string $id = ''): bool|string {
    if ($id !== '') {
      $query = $this->db->prepare("SELECT confidential FROM {$this->table} WHERE id = :id");
      $query->bindParam(':id', $id);
      $query->execute();
      $result = $query->fetchColumn();
      if ($result !== false) {
        return (string) $result;
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Restores all records for a given user.
   *
   * @param string $username Username to restore
   *
   * @return bool Query result
   */
  public function restore(string $username): bool {
    $userId = $this->resolveUserId($username);
    $query  = $this->db->prepare("INSERT INTO {$this->table} SELECT a.* FROM {$this->archive_table} a WHERE user_id <=> :user_id");
    $query->bindValue(':user_id', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Sets the region for a daynote record.
   *
   * @param string $daynoteId Record ID
   * @param string $regionId  Region ID
   *
   * @return bool Query result
   */
  public function setRegion(string $daynoteId, string $regionId): bool {
    $normalizedRegionId = self::normalizeRegionId($regionId);
    $query               = $this->db->prepare("UPDATE {$this->table} SET region_id = :region_id WHERE id = :id;");
    $query->bindValue(':region_id', $normalizedRegionId, $normalizedRegionId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $query->bindParam(':id', $daynoteId);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a daynote record from local class variables.
   *
   * @return bool Query result
   */
  public function update(): bool {
    $userId   = $this->resolveUserId($this->username);
    $regionId = self::normalizeRegionId($this->region);
    $day      = self::toDate($this->yyyymmdd);
    $query    = $this->db->prepare("UPDATE {$this->table} SET day = :day, daynote = :daynote, user_id = :user_id, region_id = :region_id, color = :color, confidential = :confidential WHERE id = :id");
    $query->bindParam(':day', $day);
    $query->bindParam(':daynote', $this->daynote);
    $query->bindValue(':user_id', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $query->bindValue(':region_id', $regionId, $regionId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $query->bindParam(':color', $this->color);
    $query->bindParam(':confidential', $this->confidential);
    $query->bindParam(':id', $this->id);
    return $query->execute();
  }
}
