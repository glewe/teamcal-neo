<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;
use RuntimeException;

/**
 * DatabaseStructureModel
 *
 * Compares the live database schema against the manifest shipped with
 * this release (sql/basic.manifest.php) and applies "add missing" fixes
 * for tables, columns, indexes, and config rows.
 *
 * Scope is intentionally narrow: only MISSING items are reported and
 * repaired. Drift in column type, charset, engine, default value, or
 * extra/unknown items is never reported and never altered - those cases
 * can silently destroy data and are out of scope by design.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     5.0.10
 */
class DatabaseStructureModel
{
  private PDO    $db;
  private string $prefix;
  private string $dbName;
  private string $configTable;

  /** @var array<string, mixed>|null Cached manifest. */
  private ?array $manifest = null;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null                   $db   PDO connection
   * @param array<string, string>|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    if ($db && $conf) {
      $this->db     = $db;
      $this->prefix = $conf['db_table_prefix'] ?? 'tcneo_';
      $this->dbName = $conf['db_name'] ?? '';
      $this->configTable = $conf['db_table_config'] ?? ($this->prefix . 'config');
    }
    else {
      global $CONF, $DB;
      $this->db     = $DB->db;
      $this->prefix = $CONF['db_table_prefix'] ?? 'tcneo_';
      $this->dbName = $CONF['db_name'] ?? '';
      $this->configTable = $CONF['db_table_config'] ?? ($this->prefix . 'config');
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Load (and cache) the generated manifest.
   *
   * @return array<string, mixed>
   * @throws RuntimeException when the manifest file is missing or malformed.
   */
  public function loadManifest(): array {
    if ($this->manifest !== null) {
      return $this->manifest;
    }
    $path = dirname(__DIR__, 2) . '/sql/basic.manifest.php';
    if (!is_file($path)) {
      throw new RuntimeException("Database manifest not found: $path. Run 'composer db:manifest' to generate it.");
    }
    $data = require_once $path;
    if (!is_array($data) || !isset($data['tables'], $data['config'])) {
      throw new RuntimeException("Database manifest is malformed: $path");
    }
    $this->manifest = $data;
    return $data;
  }

  //---------------------------------------------------------------------------
  /**
   * Compare the live database against the manifest.
   *
   * Returns an ordered list of findings. Order matters for apply():
   * tables first (a missing table makes its columns/indexes implicit),
   * then columns, then indexes, then config rows.
   *
   * @return list<array{kind: string, table?: string, column?: string, index?: string, name?: string, current?: string}>
   */
  public function check(): array {
    $manifest = $this->loadManifest();
    $findings = [];

    $liveTables = $this->liveTableNames();

    // Pass 1: missing tables
    foreach ($manifest['tables'] as $shortName => $_) {
      $fullName = $this->prefix . $shortName;
      if (!in_array($fullName, $liveTables, true)) {
        $findings[] = ['kind' => 'missing_table', 'table' => $fullName];
      }
    }

    // Pass 2: missing columns and indexes (only for tables that DO exist)
    foreach ($manifest['tables'] as $shortName => $tableDef) {
      $fullName = $this->prefix . $shortName;
      if (!in_array($fullName, $liveTables, true)) {
        continue;
      }
      $liveCols = $this->liveColumnNames($fullName);
      foreach ($tableDef['columns'] as $col) {
        if (!in_array($col['name'], $liveCols, true)) {
          $findings[] = [
            'kind'   => 'missing_column',
            'table'  => $fullName,
            'column' => $col['name'],
          ];
        }
      }
      $liveIdx = $this->liveIndexNames($fullName);
      foreach ($tableDef['indexes'] as $idx) {
        if (!in_array($idx['name'], $liveIdx, true)) {
          $findings[] = [
            'kind'  => 'missing_index',
            'table' => $fullName,
            'index' => $idx['name'],
          ];
        }
      }
    }

    // Pass 3: missing or case-mismatched config rows (only if the
    // config table exists). The `name` column uses utf8_general_ci, so
    // a row whose name differs from the manifest only in letter case is
    // an *orphan* the application can never read - and a plain INSERT
    // for the manifest spelling would fail the unique index. Detect
    // those as a separate kind so apply() can rename them in place.
    if (in_array($this->configTable, $liveTables, true)) {
      $liveConfig  = $this->liveConfigNames();
      $liveByLower = [];
      foreach ($liveConfig as $n) {
        $liveByLower[strtolower($n)] = $n;
      }
      foreach (array_keys($manifest['config']) as $name) {
        if (in_array($name, $liveConfig, true)) {
          continue;
        }
        $lower = strtolower($name);
        if (isset($liveByLower[$lower])) {
          $findings[] = [
            'kind'    => 'mismatched_config_case',
            'name'    => $name,
            'current' => $liveByLower[$lower],
          ];
        }
        else {
          $findings[] = ['kind' => 'missing_config', 'name' => $name];
        }
      }
    }

    return $findings;
  }

  //---------------------------------------------------------------------------
  /**
   * Apply a list of findings.
   *
   * Each finding is resolved against the manifest (the client-supplied
   * shape is treated as an identifier only - the SQL is generated server-
   * side from the manifest). User-supplied identifiers are additionally
   * sanity-checked through assertSafeIdent() before any DDL is built, so
   * even a malicious client cannot inject arbitrary SQL. Failures are
   * collected per-finding so a single bad ALTER doesn't abort the whole
   * repair.
   *
   * @param list<array<string, mixed>> $findings Findings from check()
   *
   * @return list<array{finding: array<string, mixed>, status: string, sql?: string, error?: string}>
   */
  public function apply(array $findings): array {
    $manifest = $this->loadManifest();
    $results  = [];

    foreach ($findings as $f) {
      $kind = $f['kind'] ?? '';
      try {
        switch ($kind) {
          case 'missing_table':
            $table = (string) $f['table'];
            self::assertSafeIdent($table, 'table');
            $sql = $this->buildCreateTable($manifest, $table);
            $this->db->exec($sql);
            $results[] = ['finding' => $f, 'status' => 'ok', 'sql' => $sql];
            break;

          case 'missing_column':
            $table  = (string) $f['table'];
            $column = (string) $f['column'];
            self::assertSafeIdent($table, 'table');
            self::assertSafeIdent($column, 'column');
            $sql = $this->buildAddColumn($manifest, $table, $column);
            $this->db->exec($sql);
            $results[] = ['finding' => $f, 'status' => 'ok', 'sql' => $sql];
            break;

          case 'missing_index':
            $table = (string) $f['table'];
            $index = (string) $f['index'];
            self::assertSafeIdent($table, 'table');
            self::assertSafeIdent($index, 'index');
            $sql = $this->buildAddIndex($manifest, $table, $index);
            $this->db->exec($sql);
            $results[] = ['finding' => $f, 'status' => 'ok', 'sql' => $sql];
            break;

          case 'missing_config':
            $name  = (string) $f['name'];
            $value = $manifest['config'][$name] ?? null;
            if ($value === null) {
              throw new RuntimeException("Config key '$name' not in manifest");
            }
            $stmt = $this->db->prepare(
              'INSERT INTO `' . $this->configTable . '` (`name`, `value`) VALUES (:name, :value)'
            );
            $stmt->execute([':name' => $name, ':value' => $value]);
            $results[] = ['finding' => $f, 'status' => 'ok'];
            break;

          case 'mismatched_config_case':
            $name    = (string) ($f['name'] ?? '');
            $current = (string) ($f['current'] ?? '');
            if ($name === '' || $current === '') {
              throw new RuntimeException("mismatched_config_case requires both 'name' and 'current'");
            }
            if (!array_key_exists($name, $manifest['config'])) {
              throw new RuntimeException("Config key '$name' not in manifest");
            }
            // Safe rename: utf8_general_ci guarantees the orphan is
            // unique regardless of case, so UPDATE cannot duplicate.
            $stmt = $this->db->prepare(
              'UPDATE `' . $this->configTable . '` SET `name` = :new WHERE `name` = :old'
            );
            $stmt->execute([':new' => $name, ':old' => $current]);
            $results[] = ['finding' => $f, 'status' => 'ok'];
            break;

          default:
            throw new RuntimeException("Unknown finding kind: $kind");
        }
      } catch (PDOException | RuntimeException $e) {
        $results[] = ['finding' => $f, 'status' => 'error', 'error' => $e->getMessage()];
      }
    }

    return $results;
  }

  //---------------------------------------------------------------------------
  /**
   * Build a CREATE TABLE statement from the manifest for the given
   * prefixed table name.
   *
   * @param array<string, mixed> $manifest
   * @param string               $fullName Prefixed table name
   *
   * @return string SQL statement
   */
  private function buildCreateTable(array $manifest, string $fullName): string {
    $short = $this->stripPrefix($fullName);
    if (!isset($manifest['tables'][$short])) {
      throw new RuntimeException("Table '$short' not in manifest");
    }
    $t     = $manifest['tables'][$short];
    $parts = [];
    foreach ($t['columns'] as $col) {
      $parts[] = '  `' . $col['name'] . '` ' . $col['definition'];
    }
    foreach ($t['indexes'] as $idx) {
      $parts[] = '  ' . $this->renderIndex($idx);
    }
    return 'CREATE TABLE IF NOT EXISTS `' . $fullName . "` (\n"
      . implode(",\n", $parts) . "\n"
      . ') ' . $t['options'];
  }

  //---------------------------------------------------------------------------
  /**
   * Build an ALTER TABLE ADD COLUMN statement.
   *
   * @param array<string, mixed> $manifest
   * @param string               $fullName   Prefixed table name
   * @param string               $columnName Column name to add
   *
   * @return string SQL statement
   */
  private function buildAddColumn(array $manifest, string $fullName, string $columnName): string {
    $short = $this->stripPrefix($fullName);
    if (!isset($manifest['tables'][$short])) {
      throw new RuntimeException("Table '$short' not in manifest");
    }
    foreach ($manifest['tables'][$short]['columns'] as $col) {
      if ($col['name'] === $columnName) {
        return 'ALTER TABLE `' . $fullName . '` ADD COLUMN `' . $columnName . '` ' . $col['definition'];
      }
    }
    throw new RuntimeException("Column '$columnName' not in manifest for table '$short'");
  }

  //---------------------------------------------------------------------------
  /**
   * Build an ALTER TABLE ADD ... statement for a missing index.
   *
   * @param array<string, mixed> $manifest
   * @param string               $fullName  Prefixed table name
   * @param string               $indexName Index name to add
   *
   * @return string SQL statement
   */
  private function buildAddIndex(array $manifest, string $fullName, string $indexName): string {
    $short = $this->stripPrefix($fullName);
    if (!isset($manifest['tables'][$short])) {
      throw new RuntimeException("Table '$short' not in manifest");
    }
    foreach ($manifest['tables'][$short]['indexes'] as $idx) {
      if ($idx['name'] === $indexName) {
        return 'ALTER TABLE `' . $fullName . '` ADD ' . $this->renderIndex($idx);
      }
    }
    throw new RuntimeException("Index '$indexName' not in manifest for table '$short'");
  }

  //---------------------------------------------------------------------------
  /**
   * Render an index spec as the SQL fragment used in both CREATE TABLE
   * and ALTER TABLE ADD contexts.
   *
   * @param array{name: string, type: string, columns: list<string>} $idx
   *
   * @return string
   */
  private function renderIndex(array $idx): string {
    $cols = '`' . implode('`, `', $idx['columns']) . '`';
    return match ($idx['type']) {
      'PRIMARY' => 'PRIMARY KEY (' . $cols . ')',
      'UNIQUE'  => 'UNIQUE KEY `' . $idx['name'] . '` (' . $cols . ')',
      default   => 'KEY `' . $idx['name'] . '` (' . $cols . ')',
    };
  }

  //---------------------------------------------------------------------------
  /**
   * Assert that an identifier is safe to splice into SQL.
   *
   * DDL (CREATE TABLE / ALTER TABLE) does not support placeholders for
   * identifiers, so dynamic table/column/index names must be sanitised
   * at the application layer. We whitelist the conservative ASCII set
   * `[A-Za-z0-9_]+` - anything outside that is rejected outright. The
   * manifest lookup performed afterwards is a second line of defence.
   *
   * @param string $value Identifier supplied by the caller
   * @param string $kind  Human-readable kind for the error message
   *
   * @throws RuntimeException when $value contains anything other than
   *                          letters, digits, or underscore.
   */
  private static function assertSafeIdent(string $value, string $kind = 'identifier'): void {
    if (!preg_match('/^[A-Za-z0-9_]+$/', $value)) {
      throw new RuntimeException("Unsafe $kind: '$value'");
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Strip the configured prefix from a prefixed table name.
   *
   * @param string $fullName
   *
   * @return string Unprefixed name
   */
  private function stripPrefix(string $fullName): string {
    if (str_starts_with($fullName, $this->prefix)) {
      return substr($fullName, strlen($this->prefix));
    }
    return $fullName;
  }

  //---------------------------------------------------------------------------
  /**
   * Live table names in the configured database.
   *
   * @return list<string>
   */
  private function liveTableNames(): array {
    $stmt = $this->db->prepare(
      'SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = :db'
    );
    $stmt->execute([':db' => $this->dbName]);
    return array_map('strval', $stmt->fetchAll(PDO::FETCH_COLUMN));
  }

  //---------------------------------------------------------------------------
  /**
   * Live column names for a given table.
   *
   * @param string $table Prefixed table name
   *
   * @return list<string>
   */
  private function liveColumnNames(string $table): array {
    $stmt = $this->db->prepare(
      'SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :t'
    );
    $stmt->execute([':db' => $this->dbName, ':t' => $table]);
    return array_map('strval', $stmt->fetchAll(PDO::FETCH_COLUMN));
  }

  //---------------------------------------------------------------------------
  /**
   * Live index names for a given table (DISTINCT so multi-column indexes
   * appear once).
   *
   * @param string $table Prefixed table name
   *
   * @return list<string>
   */
  private function liveIndexNames(string $table): array {
    $stmt = $this->db->prepare(
      'SELECT DISTINCT INDEX_NAME FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :t'
    );
    $stmt->execute([':db' => $this->dbName, ':t' => $table]);
    return array_map('strval', $stmt->fetchAll(PDO::FETCH_COLUMN));
  }

  //---------------------------------------------------------------------------
  /**
   * Names of config rows currently in the database.
   *
   * @return list<string>
   */
  private function liveConfigNames(): array {
    $stmt = $this->db->prepare('SELECT `name` FROM `' . $this->configTable . '`');
    $stmt->execute();
    return array_map('strval', $stmt->fetchAll(PDO::FETCH_COLUMN));
  }
}
