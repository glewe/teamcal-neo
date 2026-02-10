<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * PatternModel
 *
 * This class provides methods and properties for attendance patterns.
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
  public ?int   $abs1        = 0;
  public ?int   $abs2        = 0;
  public ?int   $abs3        = 0;
  public ?int   $abs4        = 0;
  public ?int   $abs5        = 0;
  public ?int   $abs6        = 0;
  public ?int   $abs7        = 0;

  private PDO    $db;
  private string $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null             $db   Database object
   * @param array<string, string>|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db !== null && $conf !== null) {
      $this->db    = $db;
      $this->table = $conf['db_table_patterns'];
    }
    else {
      global $CONF, $DB;
      $this->db    = $DB->db;
      $this->table = $CONF['db_table_patterns'];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a pattern record from class variables.
   *
   * @return bool Query result
   */
  public function create(): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (name, description, abs1, abs2, abs3, abs4, abs5, abs6, abs7) VALUES (:name, :description, :abs1, :abs2, :abs3, :abs4, :abs5, :abs6, :abs7)');
    $query->bindParam(':name', $this->name, PDO::PARAM_STR);
    $query->bindParam(':description', $this->description, PDO::PARAM_STR);
    for ($i = 1; $i <= 7; $i++) {
      $prop = 'abs' . $i;
      $query->bindParam(':abs' . $i, $this->$prop, PDO::PARAM_INT);
    }
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records.
   *
   * @return bool Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a pattern record.
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
      for ($i = 1; $i <= 7; $i++) {
        $prop        = 'abs' . $i;
        $this->$prop = isset($row[$prop]) ? (int) $row[$prop] : 0;
      }
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
   * Checks whether a given absence pattern exists.
   *
   * @param array<int, int> $absPattern Array of absences
   *
   * @return string|bool Pattern name or false
   */
  public function patternExists(array $absPattern): string|bool {
    $stmt  = 'SELECT name FROM ' . $this->table . ' WHERE abs1 = :abs1 AND abs2 = :abs2 AND abs3 = :abs3 AND abs4 = :abs4 AND abs5 = :abs5 AND abs6 = :abs6 AND abs7 = :abs7';
    $query = $this->db->prepare($stmt);
    for ($i = 1; $i <= 7; $i++) {
      $query->bindParam(':abs' . $i, $absPattern[$i], PDO::PARAM_INT);
    }
    $query->execute();
    $row = $query->fetch();
    if ($row) {
      return (string) $row['name'];
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a pattern record.
   *
   * @param string $id Record ID to update
   *
   * @return bool Query result
   */
  public function update(string $id): bool {
    $stmt  = 'UPDATE ' . $this->table . ' SET name = :name, description = :description, abs1 = :abs1, abs2 = :abs2, abs3 = :abs3, abs4 = :abs4, abs5 = :abs5, abs6 = :abs6, abs7 = :abs7 WHERE id = :id';
    $query = $this->db->prepare($stmt);
    $query->bindParam(':name', $this->name, PDO::PARAM_STR);
    $query->bindParam(':description', $this->description, PDO::PARAM_STR);
    for ($i = 1; $i <= 7; $i++) {
      $prop = 'abs' . $i;
      $query->bindParam(':abs' . $i, $this->$prop, PDO::PARAM_INT);
    }
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    return $query->execute();
  }
}
