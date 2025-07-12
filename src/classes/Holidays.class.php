<?php

/**
 * Holidays
 *
 * This class provides methods and properties for holidays.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Holidays {
  public $id = 0;
  public $name = '';
  public $description = '';
  public $color = '000000';
  public $bgcolor = 'ffffff';
  public $businessday = 0;
  public $noabsence = 0;
  public $keepweekendcolor = 0;

  private $db = null;
  private $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_holidays'];
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a holiday record
   *
   * @return boolean Query result
   */
  public function create(): bool {
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (name, description, color, bgcolor, businessday, noabsence, keepweekendcolor) VALUES (:name, :description, :color, :bgcolor, :businessday, :noabsence, :keepweekendcolor)');
    $query->bindParam(':name', $this->name);
    $query->bindParam(':description', $this->description);
    $query->bindParam(':color', $this->color);
    $query->bindParam(':bgcolor', $this->bgcolor);
    $query->bindParam(':businessday', $this->businessday);
    $query->bindParam(':noabsence', $this->noabsence);
    $query->bindParam(':keepweekendcolor', $this->keepweekendcolor);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a holiday record
   *
   * @param string $id Record ID
   * @return boolean Query result
   */
  public function delete(string $id = ''): bool {
    if ($id !== '') {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
      $query->bindParam(':id', $id);
      return $query->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records (except Business day, Saturday, Sunday)
   *
   * @return boolean Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id > 3;");
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a holiday record
   *
   * @param string $id Record ID
   * @return boolean Query result
   */
  public function get(string $id = ''): bool {
    if ($id !== '') {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
      $query->bindParam(':id', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->color = $row['color'];
        $this->bgcolor = $row['bgcolor'];
        $this->businessday = $row['businessday'];
        $this->noabsence = $row['noabsence'];
        $this->keepweekendcolor = $row['keepweekendcolor'];
        return true;
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets a holiday record
   *
   * @param string $name Holiday name
   * @return boolean Query result
   */
  public function getByName(string $name = ''): bool {
    if ($name !== '') {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE name = :name');
      $query->bindParam(':name', $name);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->color = $row['color'];
        $this->bgcolor = $row['bgcolor'];
        $this->businessday = $row['businessday'];
        $this->noabsence = $row['noabsence'];
        return true;
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all records into an array. Only true holidays are selected (id > 3)
   *
   * @param string $sort What to sort by
   * @return array Array with records
   */
  public function getAll(string $sort = 'name'): array {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY ' . $sort . ' ASC');
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all custom records. Only true holidays are selected (id > 3)
   *
   * @param string $sort What to sort by
   * @return array Array with records
   */
  public function getAllCustom(string $sort = 'name'): array {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id > 3 ORDER BY ' . $sort . ' ASC');
    $query->execute();
    while ($row = $query->fetch()) {
      $records[] = $row;
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether the given holiday counts as business day
   *
   * @param string $id Record ID
   * @return boolean True or false
   */
  public function isBusinessDay(string $id = ''): int {
    if ($id !== '') {
      $query = $this->db->prepare('SELECT businessday FROM ' . $this->table . ' WHERE id = :id');
      $query->bindParam(':id', $id);
      $query->execute();
      $val = $query->fetchColumn();
      return $val !== false ? (int)$val : 0;
    }
    return 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether the given holiday shall not overwrite weekend coloring
   *
   * @param string $id Record ID
   * @return boolean True or false
   */
  public function keepWeekendColor(string $id = ''): int {
    if ($id !== '') {
      $query = $this->db->prepare('SELECT keepweekendcolor FROM ' . $this->table . ' WHERE id = :id');
      $query->bindParam(':id', $id);
      $query->execute();
      $val = $query->fetchColumn();
      return $val !== false ? (int)$val : 0;
    }
    return 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether the given holiday allows no absence
   *
   * @param string $id Record ID
   * @return boolean True or false
   */
  public function noAbsenceAllowed(string $id = ''): int {
    if ($id !== '') {
      $query = $this->db->prepare('SELECT noabsence FROM ' . $this->table . ' WHERE id = :id');
      $query->bindParam(':id', $id);
      $query->execute();
      $val = $query->fetchColumn();
      return $val !== false ? (int)$val : 0;
    }
    return 0;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the name of a Holiday
   *
   * @param string $id Record ID
   * @return string Holiday name
   */
  public function getName(string $id = ''): string {
    if ($id !== '') {
      $query = $this->db->prepare('SELECT name FROM ' . $this->table . ' WHERE id = :id');
      $query->bindParam(':id', $id);
      $query->execute();
      $val = $query->fetchColumn();
      return $val !== false ? $val : 'unknown';
    }
    return 'unknown';
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the description of a holiday
   *
   * @param string $id Record ID
   * @return string Holiday description
   */
  public function getDescription(string $id = ''): string {
    if ($id !== '') {
      $query = $this->db->prepare('SELECT description FROM ' . $this->table . ' WHERE id = :id');
      $query->bindParam(':id', $id);
      $query->execute();
      $val = $query->fetchColumn();
      return $val !== false ? $val : '.';
    }
    return '.';
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the color of a holiday
   *
   * @param string $id Record ID
   * @return string Holiday color
   */
  public function getColor(string $id = ''): string {
    if ($id !== '') {
      $query = $this->db->prepare('SELECT color FROM ' . $this->table . ' WHERE id = :id');
      $query->bindParam(':id', $id);
      $query->execute();
      $val = $query->fetchColumn();
      return $val !== false ? $val : '000000';
    }
    return '000000';
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the background color of a holiday
   *
   * @param string $id Record ID
   * @return string Holiday background color
   */
  public function getBgColor(string $id = ''): string {
    if ($id !== '') {
      $query = $this->db->prepare('SELECT bgcolor FROM ' . $this->table . ' WHERE id = :id');
      $query->bindParam(':id', $id);
      $query->execute();
      $val = $query->fetchColumn();
      return $val !== false ? $val : '000000';
    }
    return '000000';
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the last auto-increment ID
   *
   * @return int|void Last auto-increment ID
   */
  public function getLastId(): int|null {
    $query = $this->db->prepare('SHOW TABLE STATUS LIKE :table');
    $query->bindParam(':table', $this->table);
    $query->execute();
    if ($row = $query->fetch()) {
      return intval($row['Auto_increment']) - 1;
    }
    return null;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the next auto-increment ID
   *
   * @return string|void Next auto-increment ID
   */
  public function getNextId(): string|null {
    $query = $this->db->prepare('SHOW TABLE STATUS LIKE :table');
    $query->bindParam(':table', $this->table);
    $query->execute();
    if ($row = $query->fetch()) {
      return $row['Auto_increment'];
    }
    return null;
  }

  //---------------------------------------------------------------------------
  /**
   * Updates a holiday
   *
   * @param string $id Record ID
   * @return boolean Query result
   */
  public function update(string $id = ''): bool {
    if ($id !== '') {
      $query = $this->db->prepare('UPDATE ' . $this->table . ' SET name = :name, description = :description, color = :color, bgcolor = :bgcolor, businessday = :businessday, noabsence = :noabsence, keepweekendcolor = :keepweekendcolor WHERE id = :id');
      $query->bindParam(':name', $this->name);
      $query->bindParam(':description', $this->description);
      $query->bindParam(':color', $this->color);
      $query->bindParam(':bgcolor', $this->bgcolor);
      $query->bindParam(':businessday', $this->businessday);
      $query->bindParam(':noabsence', $this->noabsence);
      $query->bindParam(':keepweekendcolor', $this->keepweekendcolor);
      $query->bindParam(':id', $id);
      return $query->execute();
    }
    return false;
  }
}
