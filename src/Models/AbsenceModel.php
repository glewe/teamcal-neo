<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * AbsenceModel
 *
 * This class provides methods and properties for absence types.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class AbsenceModel
{
  public int       $id                = 0;
  public string    $name              = '';
  public string    $symbol            = '';
  public string    $icon              = 'No';
  public string    $color             = '000000';
  public string    $bgcolor           = 'ffffff';
  public int       $bgtrans           = 0;
  public float|int $factor            = 1;
  public int       $allowance         = 0;
  public int       $allowmonth        = 0;
  public int       $allowweek         = 0;
  public int       $counts_as         = 0;
  public int       $show_in_remainder = 1;
  public int       $show_totals       = 1;
  public int       $approval_required = 0;
  public int       $counts_as_present = 0;
  public int       $manager_only      = 0;
  public int       $hide_in_profile   = 0;
  public int       $confidential      = 0;
  public int       $takeover          = 0;

  private ?PDO   $db    = null;
  private string $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null $db Database object
   * @param array|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db && $conf) {
      $this->db    = $db;
      $this->table = $conf['db_table_absences'];
    }
    else {
      global $CONF, $DB;
      $this->db    = $DB->db;
      $this->table = $CONF['db_table_absences'];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Counts the records.
   *
   * @return int Number of records
   */
  public function count(): int {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table);
    $query->execute();
    return (int) $query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Creates an absence type record.
   *
   * @return bool Query result
   */
  public function create(): bool {
    $query = $this->db->prepare(
      'INSERT INTO ' . $this->table . ' (
        name,
        symbol,
        icon,
        color,
        bgcolor,
        bgtrans,
        factor,
        allowance,
        allowmonth,
        allowweek,
        counts_as,
        show_in_remainder,
        show_totals,
        approval_required,
        counts_as_present,
        manager_only,
        hide_in_profile,
        confidential,
        takeover
      )' .
      ' VALUES (
          :val1,
          :val2,
          :val3,
          :val4,
          :val5,
          :val6,
          :val7,
          :val8,
          :val9,
          :val10,
          :val11,
          :val12,
          :val13,
          :val14,
          :val15,
          :val16,
          :val17,
          :val18,
          :val19
      )'
    );

    $query->bindParam('val1', $this->name);
    $query->bindParam('val2', $this->symbol);
    $query->bindParam('val3', $this->icon);
    $query->bindParam('val4', $this->color);
    $query->bindParam('val5', $this->bgcolor);
    $query->bindParam('val6', $this->bgtrans);
    $query->bindParam('val7', $this->factor);
    $query->bindParam('val8', $this->allowance);
    $query->bindParam('val9', $this->allowmonth);
    $query->bindParam('val10', $this->allowweek);
    $query->bindParam('val11', $this->counts_as);
    $query->bindParam('val12', $this->show_in_remainder);
    $query->bindParam('val13', $this->show_totals);
    $query->bindParam('val14', $this->approval_required);
    $query->bindParam('val15', $this->counts_as_present);
    $query->bindParam('val16', $this->manager_only);
    $query->bindParam('val17', $this->hide_in_profile);
    $query->bindParam('val18', $this->confidential);
    $query->bindParam('val19', $this->takeover);

    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes an absence type record.
   *
   * @param string|int $id Record ID
   *
   * @return bool Query result
   */
  public function delete(string|int $id = ''): bool {
    if ($id !== '') {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      return $query->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all absence type records.
   *
   * @return bool Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets an absence type record.
   *
   * @param string|int $id Record ID
   *
   * @return bool Query result
   */
  public function get(string|int $id = ''): bool {
    $result = false;
    if ($id !== '') {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $this->id                = (int) $row['id'];
        $this->name              = (string) $row['name'];
        $this->symbol            = (string) $row['symbol'];
        $this->icon              = (string) $row['icon'];
        $this->color             = (string) $row['color'];
        $this->bgcolor           = (string) $row['bgcolor'];
        $this->bgtrans           = (int) $row['bgtrans'];
        $this->factor            = (float) $row['factor'];
        $this->allowance         = (int) $row['allowance'];
        $this->allowmonth        = (int) $row['allowmonth'];
        $this->allowweek         = (int) $row['allowweek'];
        $this->counts_as         = (int) $row['counts_as'];
        $this->show_in_remainder = (int) $row['show_in_remainder'];
        $this->show_totals       = (int) $row['show_totals'];
        $this->approval_required = (int) $row['approval_required'];
        $this->counts_as_present = (int) $row['counts_as_present'];
        $this->manager_only      = (int) $row['manager_only'];
        $this->hide_in_profile   = (int) $row['hide_in_profile'];
        $this->confidential      = (int) $row['confidential'];
        $this->takeover          = (int) $row['takeover'];
      }
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all records into an array.
   *
   * @return array Array with records
   */
  public function getAll(): array {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY name');
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all absence types counting as the given ID.
   *
   * @param string|int $id ID to search for
   *
   * @return array|bool Array with records
   */
  public function getAllSub(string|int $id): array|bool {
    $records = array();
    $query   = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE counts_as = :val1 ORDER BY name');
    $query->bindParam('val1', $id);
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
   * Gets all primary absences (not counts_as) types but the one with the given ID.
   *
   * @param string|int $id ID to skip
   *
   * @return array|bool Array with records
   */
  public function getAllPrimaryBut(string|int $id): array|bool {
    $records = array();
    $query   = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE id != :val1 AND counts_as = '0' ORDER BY name");
    $query->bindParam('val1', $id);
    if ($query->execute()) {
      while ($row = $query->fetch()) {
        $records[] = $row;
      }
      return $records;
    }
    else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the allowance of an absence type.
   *
   * @param string|int $id Record ID
   *
   * @return string Absence type allowance
   */
  public function getAllowance(string|int $id = ''): string {
    $rc = '0';
    if ($id !== '') {
      $query = $this->db->prepare('SELECT allowance FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = (string) $row['allowance'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the allowance per month.
   *
   * @param string|int $id Record ID
   *
   * @return string Absence type allowance
   */
  public function getAllowMonth(string|int $id = ''): string {
    $rc = '0';
    if ($id !== '') {
      $query = $this->db->prepare('SELECT allowmonth FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = (string) $row['allowmonth'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the allowance per week.
   *
   * @param string|int $id Record ID
   *
   * @return string Absence type allowance
   */
  public function getAllowWeek(string|int $id = ''): string {
    $rc = '0';
    if ($id !== '') {
      $query = $this->db->prepare('SELECT allowweek FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = (string) $row['allowweek'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the approval required value of an absence type.
   *
   * @param string|int $id Record ID
   *
   * @return bool|string|int Approval required
   */
  public function getApprovalRequired(string|int $id = ''): bool|string|int {
    $rc = false;
    if ($id !== '') {
      $query = $this->db->prepare('SELECT approval_required FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = $row['approval_required'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the background color of an absence type.
   *
   * @param string|int $id Record ID
   *
   * @return string Absence type bgcolor
   */
  public function getBgColor(string|int $id = ''): string {
    $rc = '';
    if ($id !== '') {
      $query = $this->db->prepare('SELECT bgcolor FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = (string) $row['bgcolor'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the background transparency flag of an absence type.
   *
   * @param string|int $id Record ID
   *
   * @return string Absence type bgtrans
   */
  public function getBgTrans(string|int $id = ''): string {
    $rc = '';
    if ($id !== '') {
      $query = $this->db->prepare('SELECT bgtrans FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = (string) $row['bgtrans'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets an absence type record.
   *
   * @param string $name Absence type name
   *
   * @return bool|int Query result
   */
  public function getByName(string $name = ''): bool|int {
    $result = 0;
    if ($name !== '') {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE name = :val1');
      $query->bindParam('val1', $name);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $this->id                = (int) $row['id'];
        $this->name              = (string) $row['name'];
        $this->symbol            = (string) $row['symbol'];
        $this->icon              = (string) $row['icon'];
        $this->color             = (string) $row['color'];
        $this->bgcolor           = (string) $row['bgcolor'];
        $this->bgtrans           = (int) $row['bgtrans'];
        $this->factor            = (float) $row['factor'];
        $this->allowance         = (int) $row['allowance'];
        $this->allowmonth        = (int) $row['allowmonth'];
        $this->allowweek         = (int) $row['allowweek'];
        $this->counts_as         = (int) $row['counts_as'];
        $this->show_in_remainder = (int) $row['show_in_remainder'];
        $this->show_totals       = (int) $row['show_totals'];
        $this->approval_required = (int) $row['approval_required'];
        $this->counts_as_present = (int) $row['counts_as_present'];
        $this->manager_only      = (int) $row['manager_only'];
        $this->hide_in_profile   = (int) $row['hide_in_profile'];
        $this->confidential      = (int) $row['confidential'];
      }
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the color of an absence type.
   *
   * @param string|int $id Record ID
   *
   * @return string Absence type color
   */
  public function getColor(string|int $id = ''): string {
    $rc = '';
    if ($id !== '') {
      $query = $this->db->prepare('SELECT color FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = (string) $row['color'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the counts_as of the absence.
   *
   * @param string|int $id Record ID
   *
   * @return bool|string|int Absence counts as
   */
  public function getCountsAs(string|int $id = ''): bool|string|int {
    $rc = false;
    if ($id !== '') {
      $query = $this->db->prepare('SELECT counts_as FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = $row['counts_as'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the counts_as_present of the absence.
   *
   * @param string|int $id Record ID
   *
   * @return bool|string|int Absence counts as
   */
  public function getCountsAsPresent(string|int $id = ''): bool|string|int {
    $rc = false;
    if ($id !== '') {
      $query = $this->db->prepare('SELECT counts_as_present FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = $row['counts_as_present'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the factor value of an absence type.
   *
   * @param string|int $id Record ID
   *
   * @return int|string|float Absence type factor
   */
  public function getFactor(string|int $id = ''): int|string|float {
    $rc = 1; // Default factor is 1
    if ($id !== '') {
      $query = $this->db->prepare('SELECT factor FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = $row['factor'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the icon of an absence type.
   *
   * @param string|int $id Record ID
   *
   * @return string Absence type icon
   */
  public function getIcon(string|int $id = ''): string {
    $rc = '.';
    if ($id !== '') {
      $query = $this->db->prepare('SELECT icon FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = (string) $row['icon'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the last auto-increment ID.
   *
   * @return int|bool Last auto-increment ID
   */
  public function getLastId(): int|bool {
    $query  = $this->db->prepare('SHOW TABLE STATUS LIKE ' . $this->table);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      return intval($row['Auto_increment']) - 1;
    }
    else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the name of an absence type.
   *
   * @param string|int $id Record ID
   *
   * @return string Absence type name
   */
  public function getName(string|int $id = ''): string {
    $rc = '';
    if ($id !== '') {
      $query = $this->db->prepare('SELECT name FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = (string) $row['name'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the next auto-increment ID.
   *
   * @return string|null Next auto-increment ID
   */
  public function getNextId(): string|null {
    $query  = $this->db->prepare('SHOW TABLE STATUS LIKE ' . $this->table);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      return (string) $row['Auto_increment'];
    }
    return null;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the symbol of an absence type.
   *
   * @param string|int $id Record ID
   *
   * @return string Absence type symbol
   */
  public function getSymbol(string|int $id = ''): string {
    $rc = '.';
    if ($id !== '') {
      $query = $this->db->prepare('SELECT symbol FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = (string) $row['symbol'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether an absence is confidential.
   *
   * @param string|int $id Record ID
   *
   * @return bool|string|int True or false
   */
  public function isConfidential(string|int $id = ''): bool|string|int {
    if ($id !== '') {
      $query = $this->db->prepare('SELECT confidential FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        return $row['confidential'];
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether an absence is manager only.
   *
   * @param string|int $id Record ID
   *
   * @return bool|string|int True or false
   */
  public function isManagerOnly(string|int $id = ''): bool|string|int {
    if ($id !== '') {
      $query = $this->db->prepare('SELECT manager_only FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        return $row['manager_only'];
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether an absence is takeover enabled.
   *
   * @param string|int $id Record ID
   *
   * @return bool|string|int True or false
   */
  public function isTakeover(string|int $id = ''): bool|string|int {
    if ($id !== '') {
      $query = $this->db->prepare('SELECT takeover FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        return $row['takeover'];
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Makes all sub-abs of a given abs primary. Sets counts_as = 0.
   *
   * @param string|int $id Absence ID of the primary
   *
   * @return bool True or False
   */
  public function setAllSubsPrimary(string|int $id): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET counts_as = 0 WHERE counts_as = :val1');
    $query->bindParam('val1', $id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates an absence type by it's symbol from the current array data.
   *
   * @param string|int $id Record ID
   *
   * @return bool Query result
   */
  public function update(string|int $id = ''): bool {
    $result = false;
    if ($id !== '') {
      $query = $this->db->prepare('UPDATE ' . $this->table . '
        SET
          name = :val1,
          symbol = :val2,
          icon = :val3,
          color = :val4,
          bgcolor = :val5,
          bgtrans = :val6,
          factor = :val7,
          allowance = :val8,
          allowmonth = :val9,
          allowweek = :val10,
          counts_as = :val11,
          show_in_remainder = :val12,
          show_totals = :val13,
          approval_required = :val14,
          counts_as_present = :val15,
          manager_only = :val16,
          hide_in_profile = :val17,
          confidential = :val18,
          takeover = :val19
       WHERE
          id = :val20');

      $query->bindParam('val1', $this->name);
      $query->bindParam('val2', $this->symbol);
      $query->bindParam('val3', $this->icon);
      $query->bindParam('val4', $this->color);
      $query->bindParam('val5', $this->bgcolor);
      $query->bindParam('val6', $this->bgtrans);
      $query->bindParam('val7', $this->factor);
      $query->bindParam('val8', $this->allowance);
      $query->bindParam('val9', $this->allowmonth);
      $query->bindParam('val10', $this->allowweek);
      $query->bindParam('val11', $this->counts_as);
      $query->bindParam('val12', $this->show_in_remainder);
      $query->bindParam('val13', $this->show_totals);
      $query->bindParam('val14', $this->approval_required);
      $query->bindParam('val15', $this->counts_as_present);
      $query->bindParam('val16', $this->manager_only);
      $query->bindParam('val17', $this->hide_in_profile);
      $query->bindParam('val18', $this->confidential);
      $query->bindParam('val19', $this->takeover);
      $query->bindParam('val20', $id);

      $result = $query->execute();
    }
    return $result;
  }
}
