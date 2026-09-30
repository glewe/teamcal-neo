<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * CalendarDayModel
 *
 * This class provides methods to manage per-day holiday overrides against
 * `tcneo_calendar_days`, replacing MonthModel's per-region/month row of
 * `hol1..31`/`wday1..31`/`week1..31` columns.
 *
 * The table is sparse: a row only exists for a day whose holiday assignment
 * differs from the default (holiday_id = 1, "Business Day" = no override).
 * Weekday and ISO week number are not stored - both are 100% derivable from
 * the date itself, so they never require a database round trip; see
 * buildWeekdayGrid().
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     6.0.0
 */
class CalendarDayModel
{
  private ?PDO   $db    = null;
  private string $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null                    $db   Database connection object
   * @param array<string, string>|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db !== null && $conf !== null) {
      $this->table = $conf['db_table_calendar_days'];
      $this->db    = $db;
    }
    else {
      global $CONF, $dbModel;
      $this->table = $CONF['db_table_calendar_days'];
      $this->db    = $dbModel->db;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Builds a DATE string from year/month/day parts.
   *
   * @param string     $year  Four-digit year (YYYY)
   * @param string|int $month Month (1-12)
   * @param string|int $day   Day of month (1-31)
   *
   * @return string YYYY-MM-DD
   */
  private static function toDate(string $year, string|int $month, string|int $day): string {
    return sprintf('%04d-%02d-%02d', (int) $year, (int) $month, (int) $day);
  }

  //---------------------------------------------------------------------------
  /**
   * Builds a flat weekday/week-number grid for a whole month, keyed the way
   * the legacy MonthModel's public properties were ('wday1'..'wday31',
   * 'week1'..'week31'), so views walking days 1..31 by dynamic key name
   * (Twig's `attribute(M, 'wday' ~ i)`) keep working unchanged.
   *
   * Pure date math - no database access.
   *
   * @param string     $year  Four-digit year (YYYY)
   * @param string|int $month Month (1-12)
   *
   * @return array<string, int>
   */
  public static function buildWeekdayGrid(string $year, string|int $month): array {
    $daysInMonth = (int) date('t', mktime(0, 0, 0, (int) $month, 1, (int) $year));
    $grid        = [];
    for ($d = 1; $d <= $daysInMonth; $d++) {
      $ts               = mktime(0, 0, 0, (int) $month, $d, (int) $year);
      $grid['wday' . $d] = (int) date('N', $ts);
      $grid['week' . $d] = (int) date('W', $ts);
    }
    return $grid;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the holiday ID override for a single region/day.
   *
   * @param string     $year     Year (YYYY)
   * @param string|int $month    Month (1-12)
   * @param string|int $day      Day of month (1-31)
   * @param string|int $regionId Region ID
   *
   * @return int Holiday ID, defaulting to 1 (Business Day / no override) when no row exists
   */
  public function getHoliday(string $year, string|int $month, string|int $day, string|int $regionId): int {
    $date  = self::toDate($year, $month, $day);
    $query = $this->db->prepare("SELECT holiday_id FROM {$this->table} WHERE region_id = :region AND day = :day");
    $query->bindValue(':region', (int) $regionId, PDO::PARAM_INT);
    $query->bindValue(':day', $date);
    $query->execute();
    $row = $query->fetch();
    return $row ? (int) $row['holiday_id'] : 1;
  }

  //---------------------------------------------------------------------------
  /**
   * Sets (or clears) the holiday override for a single region/day.
   *
   * Setting holidayId to 1 (Business Day, the default) deletes any existing
   * override row instead of storing a redundant "no override" row.
   *
   * @param string     $year      Year (YYYY)
   * @param string|int $month     Month (1-12)
   * @param string|int $day       Day of month (1-31)
   * @param string|int $regionId  Region ID
   * @param string|int $holidayId Holiday ID to assign
   *
   * @return bool Query result
   */
  public function setHoliday(string $year, string|int $month, string|int $day, string|int $regionId, string|int $holidayId): bool {
    $date = self::toDate($year, $month, $day);

    if ((int) $holidayId === 1) {
      $query = $this->db->prepare("DELETE FROM {$this->table} WHERE region_id = :region AND day = :day");
      $query->bindValue(':region', (int) $regionId, PDO::PARAM_INT);
      $query->bindValue(':day', $date);
      return $query->execute();
    }

    $query = $this->db->prepare("
      INSERT INTO {$this->table} (region_id, day, holiday_id)
      VALUES (:region, :day, :holiday)
      ON DUPLICATE KEY UPDATE holiday_id = :holiday2
    ");
    $query->bindValue(':region', (int) $regionId, PDO::PARAM_INT);
    $query->bindValue(':day', $date);
    $query->bindValue(':holiday', (int) $holidayId, PDO::PARAM_INT);
    $query->bindValue(':holiday2', (int) $holidayId, PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the holiday-ID map for a whole region/month.
   *
   * @param string     $year     Year (YYYY)
   * @param string|int $month    Month (1-12)
   * @param string|int $regionId Region ID
   *
   * @return array<int, int> [day => holidayId], defaulting missing days to 1 (Business Day)
   */
  public function getMonthMap(string $year, string|int $month, string|int $regionId): array {
    $daysInMonth = (int) date('t', mktime(0, 0, 0, (int) $month, 1, (int) $year));
    $map         = array_fill(1, $daysInMonth, 1);

    $from  = self::toDate($year, $month, 1);
    $to    = self::toDate($year, $month, $daysInMonth);
    $query = $this->db->prepare("SELECT day, holiday_id FROM {$this->table} WHERE region_id = :region AND day BETWEEN :from AND :to");
    $query->bindValue(':region', (int) $regionId, PDO::PARAM_INT);
    $query->bindValue(':from', $from);
    $query->bindValue(':to', $to);
    $query->execute();
    while ($row = $query->fetch()) {
      $day        = (int) substr((string) $row['day'], 8, 2);
      $map[$day]  = (int) $row['holiday_id'];
    }
    return $map;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all holiday overrides for a region/month (resets the month to defaults).
   *
   * @param string     $year     Year (YYYY)
   * @param string|int $month    Month (1-12)
   * @param string|int $regionId Region ID
   *
   * @return bool Query result
   */
  public function clearHolidays(string $year, string|int $month, string|int $regionId): bool {
    $daysInMonth = (int) date('t', mktime(0, 0, 0, (int) $month, 1, (int) $year));
    $from        = self::toDate($year, $month, 1);
    $to          = self::toDate($year, $month, $daysInMonth);
    $query       = $this->db->prepare("DELETE FROM {$this->table} WHERE region_id = :region AND day BETWEEN :from AND :to");
    $query->bindValue(':region', (int) $regionId, PDO::PARAM_INT);
    $query->bindValue(':from', $from);
    $query->bindValue(':to', $to);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets every existing holiday-override row for a region, across all months.
   *
   * Used for region-to-region merges/transfers: since the table is sparse,
   * every row returned here already represents a real override (there is
   * nothing to fetch for default days).
   *
   * @param string|int $regionId Region ID
   *
   * @return array<string, int> [YYYY-MM-DD => holidayId]
   */
  public function getRegionOverrides(string|int $regionId): array {
    $overrides = [];
    $query     = $this->db->prepare("SELECT day, holiday_id FROM {$this->table} WHERE region_id = :region");
    $query->bindValue(':region', (int) $regionId, PDO::PARAM_INT);
    $query->execute();
    while ($row = $query->fetch()) {
      $overrides[(string) $row['day']] = (int) $row['holiday_id'];
    }
    return $overrides;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all holiday overrides for a region.
   *
   * Note: `fk_cd_region` is `ON DELETE CASCADE`, so this happens automatically
   * when the region itself is deleted. This method remains for callers that
   * need to clear a region's overrides without deleting the region.
   *
   * @param string|int $regionId Region ID
   *
   * @return bool Query result
   */
  public function deleteRegion(string|int $regionId): bool {
    $query = $this->db->prepare("DELETE FROM {$this->table} WHERE region_id = :region");
    $query->bindValue(':region', (int) $regionId, PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all holiday overrides before (and including) a given year/month, across all regions.
   *
   * @param string     $year  Year (YYYY)
   * @param string|int $month Month (1-12)
   *
   * @return bool Query result
   */
  public function deleteBefore(string $year, string|int $month): bool {
    $nextMonth = (int) $month + 1;
    $nextYear  = (int) $year;
    if ($nextMonth > 12) {
      $nextMonth = 1;
      $nextYear++;
    }
    $cutoff = self::toDate((string) $nextYear, $nextMonth, 1);
    $query  = $this->db->prepare("DELETE FROM {$this->table} WHERE day < :cutoff");
    $query->bindValue(':cutoff', $cutoff);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records.
   *
   * @return bool Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare("SELECT COUNT(*) FROM {$this->table}");
    $query->execute();
    if ($query->fetchColumn()) {
      $query = $this->db->prepare("TRUNCATE TABLE {$this->table}");
      return $query->execute();
    }
    return false;
  }
}
