<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Core\Cache;

/**
 * ConfigModel
 *
 * This class provides methods and properties for system configuration settings.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class ConfigModel
{
  public ?int   $id    = null;
  public string $name  = '';
  public string $value = '';

  /** @var array<string, mixed> */
  private        $conf  = [];
  private PDO    $db;
  private string $table = '';
  private ?Cache $cache = null;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param array<string, string> $conf  Configuration array
   * @param object                $db    Database object
   * @param Cache|null            $cache Cache instance
   */
  public function __construct(array $conf, object $db, ?Cache $cache = null) {
    $this->conf  = $conf;
    $this->db    = $db->db;
    $this->table = $conf['db_table_config'];
    $this->cache = $cache;
    $this->load();
  }

  //---------------------------------------------------------------------------
  /**
   * Load configuration from cache or database.
   *
   * @param bool $forceReload Whether to force reload from DB
   *
   * @return void
   */
  private function load(bool $forceReload = false): void {
    if (!$forceReload && $this->cache) {
      $cachedConf = $this->cache->get('app_config');
      if ($cachedConf !== null) {
        $this->conf = array_merge($this->conf, $cachedConf);
        return;
      }
    }

    $query = $this->db->prepare("SELECT `name`, `value` FROM " . $this->table);
    $query->execute();
    $dbConf = $query->fetchAll(PDO::FETCH_KEY_PAIR);

    $this->conf = array_merge($this->conf, $dbConf);

    if ($this->cache) {
      $this->cache->set('app_config', $this->conf);
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Read the value of an option.
   *
   * @param string $name Name of the option
   *
   * @return string|false Value of the option or false if not found
   */
  public function read(string $name): string|false {
    return $this->conf[$name] ?? false;
  }

  //---------------------------------------------------------------------------
  /**
   * Read all config values into an associative array.
   *
   * @return array<string, mixed> $config Associative array of all config name=>value pairs
   */
  public function readAll(): array {
    return $this->conf;
  }

  //---------------------------------------------------------------------------
  /**
   * Save a value.
   *
   * @param string             $name  Name of the option
   * @param string|int|float|bool|null $value Value to save
   *
   * @return bool $result Query result or false
   */
  public function save(string $name, string|int|float|bool|null $value): bool {
    $query = $this->db->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE `name` = :name");
    $query->bindParam(':name', $name);
    $query->execute();

    if ($query->fetchColumn()) {
      $query2 = $this->db->prepare("UPDATE " . $this->table . " SET `value` = :value WHERE `name` = :name");
    }
    else {
      $query2 = $this->db->prepare("INSERT INTO " . $this->table . " (`name`, `value`) VALUES (:name, :value)");
    }
    $query2->bindParam(':name', $name);
    $query2->bindParam(':value', $value);
    $result = $query2->execute();
    if ($result) {
      $this->load(true); // Force reload cache
    }
    return $result;
  }

  //---------------------------------------------------------------------------
  /**
   * Save multiple values in a batch.
   *
   * @param array<string, string|int|float|bool|null> $configs Associative array of name=>value pairs
   *
   * @return bool $result Query result or false
   */
  public function saveBatch(array $configs): bool {
    if (empty($configs)) {
      return true;
    }

    $placeholders = [];
    $values       = [];
    foreach ($configs as $name => $value) {
      $placeholders[] = "(?, ?)";
      $values[]       = $name;
      $values[]       = $value;
    }

    $sql    = "INSERT INTO " . $this->table . " (`name`, `value`) VALUES " . implode(', ', $placeholders) . " ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)";
    $query  = $this->db->prepare($sql);
    $result = $query->execute($values);
    if ($result) {
      $this->load(true); // Force reload cache
    }
    return $result;
  }
}
