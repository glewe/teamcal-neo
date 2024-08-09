<?php
require_once 'PDODb.php';

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
    global $CONF;
    $this->db = new PDODb([
      'driver' => $CONF['db_driver'],
      'host' => $CONF['db_server'],
      'port' => $CONF['db_port'],
      'dbname' => $CONF['db_name'],
      'username' => $CONF['db_user'],
      'password' => $CONF['db_password'],
      'charset' => $CONF['db_charset'],
    ]);
    $this->table = $CONF['db_table_absences'];
  }

  //---------------------------------------------------------------------------
  /**
   * Counts the records
   *
   * @return integer
   */
  public function count() {
    $this->db->select($this->table)->run();
    return $this->db->rowCount();
  }

  //---------------------------------------------------------------------------
  /**
   * Creates an absence type record
   *
   * @return boolean Query result
   */
  public function create() {
    $data = [
      'id' => null,
      'name' => $this->name,
      'symbol' => $this->symbol,
      'icon' => $this->icon,
      'color' => $this->color,
      'bgcolor' => $this->bgcolor,
      'bgtrans' => $this->bgtrans,
      'factor' => $this->factor,
      'allowance' => $this->allowance,
      'allowmonth' => $this->allowmonth,
      'allowweek' => $this->allowweek,
      'counts_as' => $this->counts_as,
      'show_in_remainder' => $this->show_in_remainder,
      'show_totals' => $this->show_totals,
      'approval_required' => $this->approval_required,
      'counts_as_present' => $this->counts_as_present,
      'manager_only' => $this->manager_only,
      'hide_in_profile' => $this->hide_in_profile,
      'confidential' => $this->confidential,
      'takeover' => $this->takeover
    ];
    return $this->db->insert($this->table, $data)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes an absence type record
   *
   * @param string $id Record ID
   * @return boolean Query result
   */
  public function delete($id = '') {
    if (isset($id)) {
      return $this->db->delete($this->table)
        ->where('id', '=', $id)
        ->run();
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all absence type records
   *
   * @return boolean Query result
   */
  public function deleteAll() {
    return $this->db->delete($this->table)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets an absence type record
   *
   * @param string $id Record ID
   * @return boolean Query result
   */
  public function get($id = '') {
    $row = $this->db->select($this->table)->where('id', '=', $id)->first()->run();
    if ($row) {
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
      return true;
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all records into an array
   *
   * @return array $records Array with records
   */
  public function getAll() {
    return $this->db->select($this->table)->orderBy('name', 'asc')->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all absence types counting as the given ID
   *
   * @param string $id ID to search for
   * @return array|boolean $records Array with records
   */
  public function getAllSub($id) {
    return $this->db->select($this->table)
      ->where('counts_as', '=', $id)
      ->orderBy('name', 'asc')
      ->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets all primary absences (not counts_as) types but the one with the given ID
   *
   * @param string $id ID to skip
   * @return array|boolean $records Array with records
   */
  public function getAllPrimaryBut($id) {
    return $this->db->select($this->table)
      ->where('id', '!=', $id)
      ->where('counts_as', '=', '0')
      ->orderBy('name', 'asc')
      ->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the allowance of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type allowance
   */
  public function getAllowance($id = '') {
    $row = $this->db->select($this->table, [ 'allowance' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['allowance'];
    } else {
      return '0';
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the allowance per month
   *
   * @param string $id Record ID
   * @return string Absence type allowance
   */
  public function getAllowMonth($id = '') {
    $row = $this->db->select($this->table, [ 'allowmonth' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['allowmonth'];
    } else {
      return '0';
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the allowance per week
   *
   * @param string $id Record ID
   * @return string Absence type allowance
   */
  public function getAllowWeek($id = '') {
    $row = $this->db->select($this->table, [ 'allowweek' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['allowweek'];
    } else {
      return '0';
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the approval required value of an absence type
   *
   * @param string $id Record ID
   * @return boolean Approval required
   */
  public function getApprovalRequired($id = '') {
    $row = $this->db->select($this->table, [ 'approval_required' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['approval_required'];
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the background color of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type bgcolor
   */
  public function getBgColor($id = '') {
    $row = $this->db->select($this->table, [ 'bgcolor' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['bgcolor'];
    } else {
      return '';
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the background transparency flag of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type bgtrans
   */
  public function getBgTrans($id = '') {
    $row = $this->db->select($this->table, [ 'bgtrans' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['bgtrans'];
    } else {
      return '';
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets an absence type record
   *
   * @param string $name Absence type name
   * @return boolean Query result
   */
  public function getByName($name = '') {
    $row = $this->db->select($this->table)->where('name', '=', $name)->first()->run();
    if ($row) {
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
      return true;
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the color of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type color
   */
  public function getColor($id = '') {
    $row = $this->db->select($this->table, [ 'color' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['color'];
    } else {
      return '';
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the counts_as of the absence
   *
   * @param string $id Record ID
   * @return string Absence counts as
   */
  public function getCountsAs($id = '') {
    $row = $this->db->select($this->table, [ 'counts_as' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['counts_as'];
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the counts_as_present of the absence
   *
   * @param string $id Record ID
   * @return string Absence counts as
   */
  public function getCountsAsPresent($id = '') {
    $row = $this->db->select($this->table, [ 'counts_as_present' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['counts_as_present'];
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the factor value of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type factor
   */
  public function getFactor($id = '') {
    $row = $this->db->select($this->table, [ 'factor' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['factor'];
    } else {
      return 1;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the icon of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type icon
   */
  public function getIcon($id = '') {
    $row = $this->db->select($this->table, [ 'icon' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['icon'];
    } else {
      return '.';
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the last auto-increment ID
   *
   * @return string|boolean Last auto-increment ID
   */
  public function getLastId() {
    return $this->db->lastInsertId();
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the name of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type name
   */
  public function getName($id = '') {
    $row = $this->db->select($this->table, [ 'name' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['name'];
    } else {
      return '';
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the symbol of an absence type
   *
   * @param string $id Record ID
   * @return string Absence type symbol
   */
  public function getSymbol($id = '') {
    $row = $this->db->select($this->table, [ 'symbol' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['symbol'];
    } else {
      return '.';
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether an absence is confidential
   *
   * @param string $id Record ID
   * @return boolean
   */
  public function isConfidential($id = '') {
    $row = $this->db->select($this->table, [ 'confidential' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['confidential'];
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether an absence is manager only
   *
   * @param string $id Record ID
   * @return boolean
   */
  public function isManagerOnly($id = '') {
    $row = $this->db->select($this->table, [ 'manager_only' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['manager_only'];
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether an absence is takeover enabled
   *
   * @param string $id Record ID
   * @return boolean
   */
  public function isTakeover($id = '') {
    $row = $this->db->select($this->table, [ 'takeover' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['takeover'];
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Makes all sub-abs of a given abs primary. Sets counts_as = 0.
   *
   * @param string $id Absence ID of the primary
   * @return void
   */
  public function setAllSubsPrimary($id) {
    $data = [ 'counts_as' => 0 ];
    $this->db->update($this->table, $data)->where('counts_as', '=', $id)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Updates an absence type by it's symbol from the current array data
   *
   * @param string $id Record ID
   * @return boolean Query result
   */
  public function update($id = '') {
    $data = [
      'name' => $this->name,
      'symbol' => $this->symbol,
      'icon' => $this->icon,
      'color' => $this->color,
      'bgcolor' => $this->bgcolor,
      'bgtrans' => $this->bgtrans,
      'factor' => $this->factor,
      'allowance' => $this->allowance,
      'allowmonth' => $this->allowmonth,
      'allowweek' => $this->allowweek,
      'counts_as' => $this->counts_as,
      'show_in_remainder' => $this->show_in_remainder,
      'show_totals' => $this->show_totals,
      'approval_required' => $this->approval_required,
      'counts_as_present' => $this->counts_as_present,
      'manager_only' => $this->manager_only,
      'hide_in_profile' => $this->hide_in_profile,
      'confidential' => $this->confidential,
      'takeover' => $this->takeover
    ];
    return $this->db->update($this->table, $data)->where('id', '=', $id)->run();
  }
}
