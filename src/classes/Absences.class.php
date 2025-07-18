<?php

/**
 * Absences
 *
 * This class provides methods and properties for user roles.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Absences {
  public $id = 0;
  public $name = '';
  public $symbol = '';
  public $icon = 'No';
  public $color = '000000';
  public $bgcolor = 'ffffff';
  public $bgtrans = 0;
  public $factor = 1;
  public $allowance = 0;
  public $allowmonth = 0;
  public $allowweek = 0;
  public $counts_as = 0;
  public $show_in_remainder = 1;
  public $show_totals = 1;
  public $approval_required = 0;
  public $counts_as_present = 0;
  public $manager_only = 0;
  public $hide_in_profile = 0;
  public $confidential = 0;
  public $takeover = 0;

  private $db = null;
  private $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_absences'];
  }

  //---------------------------------------------------------------------------
  /**
   * Counts the records
   *
   * @return integer
   */
  public function count(): int {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table);
    $query->execute();
    return (int)$query->fetchColumn();
  }

  //---------------------------------------------------------------------------
  /**
   * Creates an absence type record
   *
   * @return boolean Query result
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
   * Deletes an absence type record
   *
   * @param string $id Record ID
   * @return boolean Query result
   */
  public function delete(string $id = ''): bool {
    if ($id !== '') {
      $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      return $query->execute();
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all absence type records
   *
   * @return boolean Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets an absence type record
   *
   * @param string $id Record ID
   * @return boolean Query result
   */
  public function get(string $id = ''): bool {
    $result = false;
    if ($id !== '') {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->symbol = $row['symbol'];
        $this->icon = $row['icon'];
        $this->color = $row['color'];
        $this->bgcolor = $row['bgcolor'];
        $this->bgtrans = $row['bgtrans'];
        $this->factor = $row['factor'];
        $this->allowance = $row['allowance'];
        $this->allowmonth = $row['allowmonth'];
        $this->allowweek = $row['allowweek'];
        $this->counts_as = $row['counts_as'];
        $this->show_in_remainder = $row['show_in_remainder'];
        $this->show_totals = $row['show_totals'];
        $this->approval_required = $row['approval_required'];
        $this->counts_as_present = $row['counts_as_present'];
        $this->manager_only = $row['manager_only'];
        $this->hide_in_profile = $row['hide_in_profile'];
        $this->confidential = $row['confidential'];
        $this->takeover = $row['takeover'];
      }
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all records into an array
   *
   * @return array $records Array with records
   */
  public function getAll(): array {
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY name');
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all absence types counting as the given ID
   *
   * @param string $id ID to search for
   * @return array|boolean $records Array with records
   */
  public function getAllSub(string $id): array|bool {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE counts_as = :val1 ORDER BY name');
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
   * Gets all primary absences (not counts_as) types but the one with the given ID
   *
   * @param string $id ID to skip
   * @return array|boolean $records Array with records
   */
  public function getAllPrimaryBut(string $id): array|bool {
    $records = array();
    $query = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE id != :val1 AND counts_as = '0' ORDER BY name");
    $query->bindParam('val1', $id);
    if ($query->execute()) {
      while ($row = $query->fetch()) {
        $records[] = $row;
      }
      return $records;
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the allowance of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type allowance
   */
  public function getAllowance(string $id = ''): string {
    $rc = '0';
    if (isset($id)) {
      $query = $this->db->prepare('SELECT allowance FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = $row['allowance'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the allowance per month
   *
   * @param string $id Record ID
   * @return string Absence type allowance
   */
  public function getAllowMonth(string $id = ''): string {
    $rc = '0';
    if (isset($id)) {
      $query = $this->db->prepare('SELECT allowmonth FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = $row['allowmonth'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the allowance per week
   *
   * @param string $id Record ID
   * @return string Absence type allowance
   */
  public function getAllowWeek(string $id = ''): string {
    $rc = '0';
    if (isset($id)) {
      $query = $this->db->prepare('SELECT allowweek FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = $row['allowweek'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the approval required value of an absence type
   *
   * @param string $id Record ID
   * @return boolean Approval required
   */
  public function getApprovalRequired(string $id = ''): bool|string {
    $rc = false;
    if (isset($id)) {
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
   * Gets the background color of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type bgcolor
   */
  public function getBgColor(string $id = ''): string {
    $rc = '';
    if (isset($id)) {
      $query = $this->db->prepare('SELECT bgcolor FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = $row['bgcolor'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the background transparency flag of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type bgtrans
   */
  public function getBgTrans(string $id = ''): string {
    $rc = '';
    if (isset($id)) {
      $query = $this->db->prepare('SELECT bgtrans FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = $row['bgtrans'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets an absence type record
   *
   * @param string $name Absence type name
   * @return boolean Query result
   */
  public function getByName(string $name = ''): bool|int {
    $result = 0;
    if (isset($name)) {
      $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE name = :val1');
      $query->bindParam('val1', $name);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->symbol = $row['symbol'];
        $this->icon = $row['icon'];
        $this->color = $row['color'];
        $this->bgcolor = $row['bgcolor'];
        $this->bgtrans = $row['bgtrans'];
        $this->factor = $row['factor'];
        $this->allowance = $row['allowance'];
        $this->allowmonth = $row['allowmonth'];
        $this->allowweek = $row['allowweek'];
        $this->counts_as = $row['counts_as'];
        $this->show_in_remainder = $row['show_in_remainder'];
        $this->show_totals = $row['show_totals'];
        $this->approval_required = $row['approval_required'];
        $this->counts_as_present = $row['counts_as_present'];
        $this->manager_only = $row['manager_only'];
        $this->hide_in_profile = $row['hide_in_profile'];
        $this->confidential = $row['confidential'];
      }
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the color of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type color
   */
  public function getColor(string $id = ''): string {
    $rc = '';
    if (isset($id)) {
      $query = $this->db->prepare('SELECT color FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = $row['color'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the counts_as of the absence
   *
   * @param string $id Record ID
   * @return string Absence counts as
   */
  public function getCountsAs(string $id = ''): bool|string {
    $rc = false;
    if (isset($id)) {
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
   * Gets the counts_as_present of the absence
   *
   * @param string $id Record ID
   * @return string Absence counts as
   */
  public function getCountsAsPresent(string $id = ''): bool|string {
    $rc = false;
    if (isset($id)) {
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
   * Gets the factor value of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type factor
   */
  public function getFactor(string $id = ''): int|string {
    $rc = 1; // Default factor is 1
    if (isset($id)) {
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
   * Gets the icon of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type icon
   */
  public function getIcon(string $id = ''): string {
    $rc = '.';
    if (isset($id)) {
      $query = $this->db->prepare('SELECT icon FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = $row['icon'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the last auto-increment ID
   *
   * @return string|boolean Last auto-increment ID
   */
  public function getLastId(): int|bool {
    $query = $this->db->prepare('SHOW TABLE STATUS LIKE ' . $this->table);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      return intval($row['Auto_increment']) - 1;
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the name of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type name
   */
  public function getName(string $id = ''): string {
    $rc = '';
    if (isset($id)) {
      $query = $this->db->prepare('SELECT name FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = $row['name'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the next auto-increment ID
   *
   * @return string Next auto-increment ID
   */
  public function getNextId(): string|null {
    $query = $this->db->prepare('SHOW TABLE STATUS LIKE ' . $this->table);
    $result = $query->execute();
    if ($result && $row = $query->fetch()) {
      return $row['Auto_increment'];
    }
    return null;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the symbol of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type symbol
   */
  public function getSymbol(string $id = ''): string {
    $rc = '.';
    if (isset($id)) {
      $query = $this->db->prepare('SELECT symbol FROM ' . $this->table . ' WHERE id = :val1');
      $query->bindParam('val1', $id);
      $result = $query->execute();
      if ($result && $row = $query->fetch()) {
        $rc = $row['symbol'];
      }
    }
    return $rc;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether an absence is confidential
   *
   * @param string $id Record ID
   * @return boolean
   */
  public function isConfidential(string $id = ''): bool|string {
    if (isset($id)) {
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
   * Checks whether an absence is manager only
   *
   * @param string $id Record ID
   * @return boolean
   */
  public function isManagerOnly(string $id = ''): bool|string {
    if (isset($id)) {
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
   * Checks whether an absence is takeover enabled
   *
   * @param string $id Record ID
   * @return boolean
   */
  public function isTakeover(string $id = ''): bool|string {
    if (isset($id)) {
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
   * @param string $id Absence ID of the primary
   * @return boolean True or False
   */
  public function setAllSubsPrimary(string $id): bool {
    $query = $this->db->prepare('UPDATE ' . $this->table . ' SET counts_as = 0 WHERE counts_as = :val1');
    $query->bindParam('val1', $id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates an absence type by it's symbol from the current array data
   *
   * @param string $id Record ID
   * @return boolean Query result
   */
  public function update(string $id = ''): bool {
    $result = false;
    if (isset($id)) {
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
