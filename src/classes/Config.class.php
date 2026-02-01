<?php

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

  private $conf = [];
  private $db = null;
  private $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct(array $conf, object $db) {
    $this->conf = $conf;
    $this->db = $db->db;
    $this->table = $conf['db_table_config'];
  }

  //---------------------------------------------------------------------------
  /**
   * Read the value of an option
   *
   * @param string $name Name of the option
   * @return string Value of the option or false if not found
   */
  public function read(string $name): string|false {
    $query = $this->db->prepare("SELECT `value` FROM " . $this->table . " WHERE `name` = :name");
    $query->bindParam(':name', $name);
    $query->execute();
    $value = $query->fetchColumn();
    return $value !== false ? $value : false;
  }

  //---------------------------------------------------------------------------
  /**
   * Read all config values into an associative array
   *
   * @return array $config Associative array of all config name=>value pairs
   */
  public function readAll(): array {
    $query = $this->db->prepare("SELECT `name`, `value` FROM " . $this->table);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_KEY_PAIR);
  }

  //---------------------------------------------------------------------------
  /**
   * Save a value
   *
   * @param string $name Name of the option
   * @param string $value Value to save
   * @return boolean $result Query result or false
   */
  public function save(string $name, string $value): bool {
    $query = $this->db->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE `name` = :name");
    $query->bindParam(':name', $name);
    $query->execute();

    if ($query->fetchColumn()) {
      $query2 = $this->db->prepare("UPDATE " . $this->table . " SET `value` = :value WHERE `name` = :name");
    } else {
      $query2 = $this->db->prepare("INSERT INTO " . $this->table . " (`name`, `value`) VALUES (:name, :value)");
    }
    $query2->bindParam(':name', $name);
    $query2->bindParam(':value', $value);
    return $query2->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Save multiple values in a batch
   *
   * @param array $configs Associative array of name=>value pairs
   * @return boolean $result Query result or false
   */
  public function saveBatch(array $configs): bool {
    if (empty($configs)) {
      return true;
    }

    $placeholders = [];
    $values = [];
    foreach ($configs as $name => $value) {
      $placeholders[] = "(?, ?)";
      $values[] = $name;
      $values[] = $value;
    }

    $sql = "INSERT INTO " . $this->table . " (`name`, `value`) VALUES " . implode(', ', $placeholders) . " ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)";
    $query = $this->db->prepare($sql);
    return $query->execute($values);
  }
}
