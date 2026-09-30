<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use Throwable;

/**
 * PatternModel
 *
 * This class provides methods and properties for attendance patterns.
 *
 * A pattern's weekday assignments live in `tcneo_pattern_days`, one row per
 * weekday that actually has an absence assigned (replacing the old
 * `abs1..abs7` columns on `tcneo_patterns`). A weekday with no assigned
 * absence simply has no row - see getWeekdayMap()/setWeekdayMap().
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class PatternModel
{
  public int    $id          = 0;
  public string $name        = '';
  public string $description = '';

  private PDO    $db;
  private string $table     = '';
  private string $daysTable = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null                    $db   Database object
   * @param array<string, string>|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db !== null && $conf !== null) {
      $this->db        = $db;
      $this->table     = $conf['db_table_patterns'];
      $this->daysTable = $conf['db_table_pattern_days'];
    }
    else {
      global $CONF, $dbModel;
      $this->db        = $dbModel->db;
      $this->table     = $CONF['db_table_patterns'];
      $this->daysTable = $CONF['db_table_pattern_days'];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a pattern record from class variables (name/description only -
   * assign weekdays afterwards via setWeekdayMap($this->id, ...)).
   *
   * @return bool Query result
   */
  public function create(): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (name, description) VALUES (:name, :description)');
    $query->bindParam(':name', $this->name, PDO::PARAM_STR);
    $query->bindParam(':description', $this->description, PDO::PARAM_STR);
    $result     = $query->execute();
    $this->id   = (int) $this->db->lastInsertId();
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a pattern record. `fk_pd_pattern` is ON DELETE CASCADE, so its
   * weekday assignments in tcneo_pattern_days are removed automatically.
   *
   * @param string $id Record ID
   *
   * @return bool Query result
   */
  public function delete(string $id): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a pattern record and saves values in class variables.
   *
   * @param string $id Record ID
   *
   * @return bool Query result
   */
  public function get(string $id): bool {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $row = $query->fetch();
    if ($row) {
      $this->id          = (int) $row['id'];
      $this->name        = (string) $row['name'];
      $this->description = (string) $row['description'];
      return true;
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all pattern records into an array.
   *
   * @return array<int, array<string, mixed>> Array with records
   */
  public function getAll(): array {
    $records = [];
    $query   = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY name');
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all records with likeness in name or description.
   *
   * @param string $like Likeness to search for
   *
   * @return array<int, array<string, mixed>> Array with records
   */
  public function getAllLike(string $like): array {
    $records = [];
    $query   = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE name LIKE :like OR description LIKE :like ORDER BY name');
    $val     = '%' . $like . '%';
    $query->bindParam(':like', $val, PDO::PARAM_STR);
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the weekday-to-absence map for a pattern.
   *
   * @param string $patternId Pattern ID
   *
   * @return array<int, int> [weekday(1=Mon..7=Sun) => absenceId], defaulting missing weekdays to 0 (none)
   */
  public function getWeekdayMap(string $patternId): array {
    $map   = array_fill(1, 7, 0);
    $query = $this->db->prepare('SELECT weekday, absence_id FROM ' . $this->daysTable . ' WHERE pattern_id = :id');
    $query->bindParam(':id', $patternId, PDO::PARAM_INT);
    $query->execute();
    while ($row = $query->fetch()) {
      $map[(int) $row['weekday']] = (int) $row['absence_id'];
    }
    return $map;
  }

  //---------------------------------------------------------------------------
  /**
   * Sets the weekday-to-absence map for a pattern. An absence ID of 0 (none)
   * removes that weekday's row instead of storing it.
   *
   * @param string           $patternId       Pattern ID
   * @param array<int, int|string> $weekdayToAbsence [weekday(1..7) => absenceId]
   *
   * @return bool True if all weekdays were written successfully
   */
  public function setWeekdayMap(string $patternId, array $weekdayToAbsence): bool {
    $this->db->beginTransaction();
    try {
      foreach ($weekdayToAbsence as $weekday => $absenceId) {
        $absenceId = (int) $absenceId;
        if ($absenceId === 0) {
          $query = $this->db->prepare('DELETE FROM ' . $this->daysTable . ' WHERE pattern_id = :pid AND weekday = :wd');
          $query->bindValue(':pid', (int) $patternId, PDO::PARAM_INT);
          $query->bindValue(':wd', (int) $weekday, PDO::PARAM_INT);
          $query->execute();
        }
        else {
          $query = $this->db->prepare('
            INSERT INTO ' . $this->daysTable . ' (pattern_id, weekday, absence_id)
            VALUES (:pid, :wd, :aid)
            ON DUPLICATE KEY UPDATE absence_id = :aid2
          ');
          $query->bindValue(':pid', (int) $patternId, PDO::PARAM_INT);
          $query->bindValue(':wd', (int) $weekday, PDO::PARAM_INT);
          $query->bindValue(':aid', $absenceId, PDO::PARAM_INT);
          $query->bindValue(':aid2', $absenceId, PDO::PARAM_INT);
          $query->execute();
        }
      }
      $this->db->commit();
      return true;
    }
    catch (Throwable $e) {
      $this->db->rollBack();
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a pattern with the exact same weekday-to-absence map already exists.
   *
   * The pattern catalog is small and admin-managed, so comparing each
   * existing pattern's map in PHP is simpler (and just as fast in practice)
   * than reconstructing an exact-match query against the sparse child table.
   *
   * @param array<int, int|string> $weekdayToAbsence [weekday(1..7) => absenceId]
   *
   * @return string|bool Pattern name or false
   */
  public function patternExists(array $weekdayToAbsence): string|bool {
    $normalized = [];
    foreach ($weekdayToAbsence as $weekday => $absenceId) {
      $normalized[(int) $weekday] = (int) $absenceId;
    }
    ksort($normalized);

    foreach ($this->getAll() as $pattern) {
      $existing = $this->getWeekdayMap((string) $pattern['id']);
      ksort($existing);
      if ($existing === $normalized) {
        return (string) $pattern['name'];
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a pattern record's name/description.
   *
   * @param string $id Record ID to update
   *
   * @return bool Query result
   */
  public function update(string $id): bool {
    $stmt  = 'UPDATE ' . $this->table . ' SET name = :name, description = :description WHERE id = :id';
    $query = $this->db->prepare($stmt);
    $query->bindParam(':name', $this->name, PDO::PARAM_STR);
    $query->bindParam(':description', $this->description, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    return $query->execute();
  }
}
