<?php
require_once 'PDODb.php';

/**
 * Config
 *
 * This class provides methods and properties for application settings.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Config {
  public $id = null;
  public $name = '';
  public $value = '';

  private $db = '';
  private $table = '';

   //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct($conf, $db) {
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
    $this->table = $conf['db_table_config'];
  }

   //---------------------------------------------------------------------------
  /**
   * Read the value of an option
   *
   * @param string $name Name of the option
   * @return string Value of the option or false if not found
   */
  public function read($name) {
    $row = $this->db->select($this->table, [ 'value' ])->where('name', '=', $name)->first()->run();
    if ($row) {
      return $row['value'];
    } else {
      return '0';
    }
  }

   //---------------------------------------------------------------------------
  /**
   * Save a value
   *
   * @param string $name Name of the option
   * @param string $value Value to save
   * @return boolean $result Query result or false
   */
  public function save($name, $value) {
    //
    // Try to find the record
    //
    $row = $this->db->select($this->table)->where('name', '=', $name)->first()->run();
    if ($row) {
      //
      // Record exists, update it
      //
      $data = [
        'name' => $name,
        'value' => $value
      ];
      return $this->db->update($this->table, $data)->where('id', '=', $row['id'])->run();
    } else {
      //
      // Record does not exist, insert it
      //
      $data = [
        'id' => null,
        'name' => $name,
        'value' => $value
      ];
      return $this->db->insert($this->table, $data)->run();
    }
  }
}
