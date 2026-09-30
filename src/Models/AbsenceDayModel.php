<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * AbsenceDayModel
 *
 * Replaces TemplateModel. Manages tcneo_absence_days: one row per assigned
 * (user, day) instead of one row per (user, month) with 31 abs1..abs31
 * columns. A day with no row simply has no absence assigned - there is no
 * "empty template" to pre-create, unlike the old per-month row.
 *
 * The public API takes usernames (matching every caller's existing data),
 * resolving internally to the tcneo_users.id foreign key the table actually
 * stores; resolved ids are cached per request to avoid repeat lookups in
 * day-by-day loops.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     6.0.0
 */
class AbsenceDayModel
{
  private ?PDO   $db            = null;
  private string $table         = '';
  private string $archiveTable  = '';
  private string $usersTable    = '';
  private string $absencesTable = '';

  /** @var array<string, int|null> */
  private array $userIdCache = [];

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null                    $db   Database object
   * @param array<string, string>|null  $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db !== null && $conf !== null) {
      $this->db            = $db;
      $this->table         = $conf['db_table_absence_days'];
      $this->archiveTable  = $conf['db_table_archive_absence_days'];
      $this->usersTable    = $conf['db_table_users'];
      $this->absencesTable = $conf['db_table_absences'];
    }
    else {
      global $CONF, $dbModel;
      $this->db            = $dbModel->db;
      $this->table         = $CONF['db_table_absence_days'];
      $this->archiveTable  = $CONF['db_table_archive_absence_days'];
      $this->usersTable    = $CONF['db_table_users'];
      $this->absencesTable = $CONF['db_table_absences'];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Resolves a username to its tcneo_users.id, caching the result. Returns
   * null for an empty username, the '%' all-users wildcard, or an unknown
   * username - callers treat null as "no matching row(s) possible."
   *
   * @param string $username Username to resolve
   *
   * @return int|null Resolved id, or null
   */
  private function resolveUserId(string $username): ?int {
    if ($username === '' || $username === '%') {
      return null;
    }
    if (array_key_exists($username, $this->userIdCache)) {
      return $this->userIdCache[$username];
    }
    $query = $this->db->prepare('SELECT id FROM ' . $this->usersTable . ' WHERE username = :username');
    $query->bindParam(':username', $username);
    $query->execute();
    $id = $query->fetchColumn();
    if ($id === false) {
      return null; // do not cache misses - the user may be created later in this request
    }
    return $this->userIdCache[$username] = (int) $id;
  }

  //---------------------------------------------------------------------------
  /**
   * Builds a Y-m-d date string from separate year/month/day parts.
   *
   * @param string     $year  Year (YYYY)
   * @param string|int $month Month (M or MM)
   * @param string|int $day   Day (D or DD)
   *
   * @return string
   */
  private static function toDate(string $year, string|int $month, string|int $day): string {
    return $year . '-' . sprintf('%02d', (int) $month) . '-' . sprintf('%02d', (int) $day);
  }

  //---------------------------------------------------------------------------
  /**
   * Converts a YYYYMMDD (or already-dashed) date string to Y-m-d.
   *
   * @param string $ymd Date as YYYYMMDD or YYYY-MM-DD
   *
   * @return string
   */
  private static function ymdToDate(string $ymd): string {
    $digits = str_replace('-', '', $ymd);
    return substr($digits, 0, 4) . '-' . substr($digits, 4, 2) . '-' . substr($digits, 6, 2);
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the absence ID assigned to a given user on a given day.
   *
   * @param string     $username Username to find
   * @param string     $year     Year (YYYY)
   * @param string|int $month    Month
   * @param string|int $day      Day of month
   *
   * @return int Absence ID, or 0 if none assigned
   */
  public function getAbsence(string $username, string $year, string|int $month, string|int $day): int {
    $userId = $this->resolveUserId($username);
    if ($userId === null) {
      return 0;
    }
    $query = $this->db->prepare('SELECT absence_id FROM ' . $this->table . ' WHERE user_id = :user_id AND day = :day');
    $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $date = self::toDate($year, $month, $day);
    $query->bindParam(':day', $date);
    $query->execute();
    $result = $query->fetchColumn();
    return $result !== false ? (int) $result : 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Sets (or clears, when $absenceId is 0) the absence for a given user on a
   * given day.
   *
   * @param string          $username  Username for update
   * @param string          $year      Year (YYYY)
   * @param string|int      $month     Month
   * @param string|int      $day       Day of month
   * @param string|int      $absenceId Absence ID to set, 0 to clear
   *
   * @return bool Query result
   */
  public function setAbsence(string $username, string $year, string|int $month, string|int $day, string|int $absenceId): bool {
    $userId = $this->resolveUserId($username);
    if ($userId === null) {
      return false;
    }
    return $this->setAbsenceForUserId($userId, self::toDate($year, $month, $day), (int) $absenceId);
  }

  //---------------------------------------------------------------------------
  /**
   * Sets or clears one user's absence for one already-resolved day. Shared
   * by setAbsence() and setMonthMap().
   *
   * @param int    $userId    Resolved tcneo_users.id
   * @param string $date      Y-m-d date
   * @param int    $absenceId Absence ID to set, 0 to clear
   *
   * @return bool Query result
   */
  private function setAbsenceForUserId(int $userId, string $date, int $absenceId): bool {
    if ($absenceId === 0) {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE user_id = :user_id AND day = :day');
      $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
      $query->bindParam(':day', $date);
      return $query->execute();
    }
    $query = $this->db->prepare(
      'INSERT INTO ' . $this->table . ' (user_id, day, absence_id) VALUES (:user_id, :day, :absence_id)
       ON DUPLICATE KEY UPDATE absence_id = VALUES(absence_id)'
    );
    $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $query->bindParam(':day', $date);
    $query->bindParam(':absence_id', $absenceId, PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a whole month's absence assignments for a user as [day => absenceId]
   * for every day of the month (0 where nothing is assigned). Replaces
   * TemplateModel::getTemplate() + reading $T->abs1..abs31.
   *
   * @param string     $username Username to find
   * @param string     $year     Year (YYYY)
   * @param string|int $month    Month
   *
   * @return array<int, int> [day => absenceId]
   */
  public function getMonthMap(string $username, string $year, string|int $month): array {
    $daysInMonth = (int) date('t', strtotime(self::toDate($year, $month, 1)));
    $map         = array_fill(1, $daysInMonth, 0);

    $userId = $this->resolveUserId($username);
    if ($userId === null) {
      return $map;
    }

    $start = self::toDate($year, $month, 1);
    $end   = self::toDate($year, $month, $daysInMonth);
    $query = $this->db->prepare('SELECT day, absence_id FROM ' . $this->table . ' WHERE user_id = :user_id AND day BETWEEN :start AND :end');
    $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $query->bindParam(':start', $start);
    $query->bindParam(':end', $end);
    $query->execute();
    while ($row = $query->fetch()) {
      $day        = (int) substr((string) $row['day'], 8, 2);
      $map[$day]  = (int) $row['absence_id'];
    }
    return $map;
  }

  //---------------------------------------------------------------------------
  /**
   * Bulk-sets a whole month's absences for a user from a [day => absenceId]
   * map (0 clears that day). Wrapped in a transaction so a partial failure
   * can't leave the month half-saved. Replaces setting $T->abs1..abs31 then
   * calling TemplateModel::update().
   *
   * @param string             $username      Username for update
   * @param string             $year          Year (YYYY)
   * @param string|int         $month         Month
   * @param array<int, mixed>  $dayToAbsence  [day => absenceId]
   *
   * @return bool True if every day saved successfully
   */
  public function setMonthMap(string $username, string $year, string|int $month, array $dayToAbsence): bool {
    $userId = $this->resolveUserId($username);
    if ($userId === null) {
      return false;
    }
    $this->db->beginTransaction();
    try {
      $ok = true;
      foreach ($dayToAbsence as $day => $absenceId) {
        $date = self::toDate($year, $month, (int) $day);
        $ok   = $this->setAbsenceForUserId($userId, $date, (int) $absenceId) && $ok;
      }
      if ($ok) {
        $this->db->commit();
      }
      else {
        $this->db->rollBack();
      }
      return $ok;
    } catch (\Throwable $e) {
      $this->db->rollBack();
      throw $e;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given user has a given absence anywhere in a given
   * month.
   *
   * @param string     $username  Username to find
   * @param string     $year      Year (YYYY)
   * @param string|int $month     Month
   * @param string|int $absenceId Absence ID to find
   *
   * @return bool
   */
  public function hasAbsence(string $username, string $year, string|int $month, string|int $absenceId): bool {
    $userId = $this->resolveUserId($username);
    if ($userId === null) {
      return false;
    }
    $daysInMonth = (int) date('t', strtotime(self::toDate($year, $month, 1)));
    $start       = self::toDate($year, $month, 1);
    $end         = self::toDate($year, $month, $daysInMonth);
    $query       = $this->db->prepare(
      'SELECT 1 FROM ' . $this->table . ' WHERE user_id = :user_id AND absence_id = :absence_id AND day BETWEEN :start AND :end LIMIT 1'
    );
    $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $absenceIdInt = (int) $absenceId;
    $query->bindParam(':absence_id', $absenceIdInt, PDO::PARAM_INT);
    $query->bindParam(':start', $start);
    $query->bindParam(':end', $end);
    $query->execute();
    return (bool) $query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Counts a specific absence for a user (or, with username '%', every
   * user) across an arbitrary date range. Unlike TemplateModel::countAbsence
   * (limited to a single month's row), this queries the range directly -
   * callers no longer need to split a range into per-month calls.
   *
   * @param string     $username  Username to find, or '%' for all users
   * @param string|int $absenceId Absence ID
   * @param string     $startYmd  Range start, YYYYMMDD (or YYYY-MM-DD)
   * @param string     $endYmd    Range end, YYYYMMDD (or YYYY-MM-DD)
   *
   * @return int
   */
  public function countAbsence(string $username, string|int $absenceId, string $startYmd, string $endYmd): int {
    $params = [
      ':absence_id' => (int) $absenceId,
      ':start'      => self::ymdToDate($startYmd),
      ':end'        => self::ymdToDate($endYmd),
    ];
    $sql = 'SELECT COUNT(*) FROM ' . $this->table . ' WHERE absence_id = :absence_id AND day BETWEEN :start AND :end';
    if ($username !== '%' && $username !== '') {
      $userId = $this->resolveUserId($username);
      if ($userId === null) {
        return 0;
      }
      $sql              .= ' AND user_id = :user_id';
      $params[':user_id'] = $userId;
    }
    $query = $this->db->prepare($sql);
    $query->execute($params);
    return (int) $query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Counts weekday absences (excluding absence types that count as present)
   * for a user (or, with username '%', every user) across a date range.
   *
   * @param string $username Username to find, or '%' for all users
   * @param string $startYmd Range start, YYYYMMDD (or YYYY-MM-DD)
   * @param string $endYmd   Range end, YYYYMMDD (or YYYY-MM-DD)
   *
   * @return int
   */
  public function countAllAbsences(string $username, string $startYmd, string $endYmd): int {
    return $this->countAllAbsencesFiltered($username, $startYmd, $endYmd, false);
  }

  //---------------------------------------------------------------------------
  /**
   * Counts weekend absences (excluding absence types that count as present)
   * for a user (or, with username '%', every user) across a date range.
   *
   * @param string $username Username to find, or '%' for all users
   * @param string $startYmd Range start, YYYYMMDD (or YYYY-MM-DD)
   * @param string $endYmd   Range end, YYYYMMDD (or YYYY-MM-DD)
   *
   * @return int
   */
  public function countAllAbsencesWe(string $username, string $startYmd, string $endYmd): int {
    return $this->countAllAbsencesFiltered($username, $startYmd, $endYmd, true);
  }

  //---------------------------------------------------------------------------
  /**
   * Shared implementation for countAllAbsences()/countAllAbsencesWe().
   * MySQL/MariaDB DAYOFWEEK(): 1=Sunday..7=Saturday, so weekend is (1, 7).
   *
   * @param string $username    Username to find, or '%' for all users
   * @param string $startYmd    Range start, YYYYMMDD (or YYYY-MM-DD)
   * @param string $endYmd      Range end, YYYYMMDD (or YYYY-MM-DD)
   * @param bool   $weekendOnly True for weekend-only, false for weekday-only
   *
   * @return int
   */
  private function countAllAbsencesFiltered(string $username, string $startYmd, string $endYmd, bool $weekendOnly): int {
    $params = [
      ':start' => self::ymdToDate($startYmd),
      ':end'   => self::ymdToDate($endYmd),
    ];
    $dowCondition = $weekendOnly ? 'DAYOFWEEK(t.day) IN (1, 7)' : 'DAYOFWEEK(t.day) NOT IN (1, 7)';
    $sql          = 'SELECT COUNT(*) FROM ' . $this->table . ' t
                      INNER JOIN ' . $this->absencesTable . ' a ON a.id = t.absence_id
                      WHERE t.day BETWEEN :start AND :end AND a.counts_as_present = 0 AND ' . $dowCondition;
    if ($username !== '%' && $username !== '') {
      $userId = $this->resolveUserId($username);
      if ($userId === null) {
        return 0;
      }
      $sql                .= ' AND t.user_id = :user_id';
      $params[':user_id']  = $userId;
    }
    $query = $this->db->prepare($sql);
    $query->execute($params);
    return (int) $query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Replaces every occurrence of one absence ID with another across all
   * users. Passing 0 as the new ID deletes the affected rows instead (a
   * "none" assignment has no row in this table's sparse shape). Used when an
   * absence type is deleted, so no dangling references remain.
   *
   * @param string|int $oldAbsenceId Absence ID to replace
   * @param string|int $newAbsenceId Absence ID to replace it with, or 0 to clear
   *
   * @return bool
   */
  public function replaceAbsenceId(string|int $oldAbsenceId, string|int $newAbsenceId): bool {
    $old = (int) $oldAbsenceId;
    $new = (int) $newAbsenceId;
    if ($new === 0) {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE absence_id = :old');
      $query->bindParam(':old', $old, PDO::PARAM_INT);
      return $query->execute();
    }
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET absence_id = :new WHERE absence_id = :old');
    $query->bindParam(':new', $new, PDO::PARAM_INT);
    $query->bindParam(':old', $old, PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether any row exists for a given user.
   *
   * @param string $username Username to find
   * @param bool   $archive  Whether to check the archive table
   *
   * @return bool
   */
  public function exists(string $username, bool $archive = false): bool {
    $userId = $this->resolveUserId($username);
    if ($userId === null) {
      return false;
    }
    $table = $archive ? $this->archiveTable : $this->table;
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE user_id = :user_id');
    $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $query->execute();
    return (bool) $query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Archives all rows for a given user (copies live rows to the archive
   * table; the live rows are removed separately via deleteByUser()).
   *
   * @param string $username Username to archive
   *
   * @return bool Query result
   */
  public function archive(string $username): bool {
    $userId = $this->resolveUserId($username);
    if ($userId === null) {
      return false;
    }
    $query = $this->db->prepare('INSERT INTO ' . $this->archiveTable . ' SELECT t.* FROM ' . $this->table . ' t WHERE t.user_id = :user_id');
    $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Restores all archived rows for a given user back into the live table.
   *
   * @param string $username Username to restore
   *
   * @return bool Query result
   */
  public function restore(string $username): bool {
    $userId = $this->resolveUserId($username);
    if ($userId === null) {
      return false;
    }
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' SELECT a.* FROM ' . $this->archiveTable . ' a WHERE a.user_id = :user_id');
    $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all rows for a given user.
   *
   * @param string $username Username to delete all records of
   * @param bool   $archive  Whether to delete from the archive table
   *
   * @return bool Query result
   */
  public function deleteByUser(string $username, bool $archive = false): bool {
    $userId = $this->resolveUserId($username);
    if ($userId === null) {
      return false;
    }
    $table = $archive ? $this->archiveTable : $this->table;
    $query = $this->db->prepare('DELETE FROM ' . $table . ' WHERE user_id = :user_id');
    $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all rows across all users.
   *
   * @param bool $archive Whether to use the archive table
   *
   * @return bool Query result
   */
  public function deleteAll(bool $archive = false): bool {
    $table  = $archive ? $this->archiveTable : $this->table;
    $query  = $this->db->prepare('SELECT COUNT(*) FROM ' . $table);
    $result = $query->execute();
    if ($result && $query->fetchColumn()) {
      $stmt = $this->db->prepare('TRUNCATE TABLE ' . $table);
      return $stmt->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all rows dated strictly before the first day of a given
   * year/month, across all users.
   *
   * Note: the equivalent TemplateModel::deleteBefore() actually matched an
   * exact year/month instead of "before" it, despite the name and its use
   * in an admin "delete data older than..." cleanup form - fixed here to
   * match the documented/intended behavior.
   *
   * @param string     $year  Year (YYYY)
   * @param string|int $month Month
   *
   * @return bool Query result
   */
  public function deleteBefore(string $year, string|int $month): bool {
    $cutoff = self::toDate($year, $month, 1);
    $query  = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE day < :cutoff');
    $query->bindParam(':cutoff', $cutoff);
    return $query->execute();
  }
}
