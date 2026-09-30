<?php
declare(strict_types=1);

namespace App\Services;

use DirectoryIterator;
use PDO;
use RuntimeException;

/**
 * LegacyImportService
 *
 * Imports the data of a TeamCal Neo 5.3.7 installation into a fresh 6.0.0
 * installation. The old database (and the old installation folder) are only
 * read, never changed. The 6.0.0 tables are emptied and refilled, so the
 * target must be a fresh installation.
 *
 * The import is a state machine that runs in steps, so that it can be driven
 * by several short web requests (see run()). The state is a plain array that
 * the caller keeps between requests (e.g. in the session).
 *
 * The data mapping is the same as in sql/migrate_5.3.7_to_6.0.0.php (numeric
 * user ids, one row per absence day instead of one row per month, ...), but
 * the data is copied row by row between two database connections instead of
 * with INSERT ... SELECT in a single database.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     6.0.0
 */
class LegacyImportService
{
  /** Import steps in execution order. */
  public const STEPS = [
    'wipe', 'reference', 'config', 'messages', 'log', 'users', 'userdata',
    'daynotes', 'absence_days', 'calendar_days', 'verify', 'files',
  ];

  /** Tables of a 5.3.7 installation (unprefixed). */
  private const OLD_TABLES = [
    'absence_group', 'absences', 'allowances', 'archive_allowances', 'archive_daynotes',
    'archive_templates', 'archive_user_attachment', 'archive_user_group', 'archive_user_message',
    'archive_user_option', 'archive_users', 'attachments', 'config', 'daynotes', 'groups',
    'holidays', 'log', 'messages', 'months', 'patterns', 'permissions', 'region_role', 'regions',
    'roles', 'templates', 'user_attachment', 'user_group', 'user_message', 'user_option', 'users',
  ];

  /** Tables whose row count must survive the import unchanged. */
  private const LOSSLESS_TABLES = [
    'roles', 'regions', 'holidays', 'absences', 'groups', 'patterns', 'users', 'archive_users', 'log', 'messages',
  ];

  /** Settings that belong to the old installation and are not carried over. */
  private const EXCLUDED_CONFIG = ['appURL', 'underMaintenance'];

  /** Generated per installation; never carried over (and not worth reporting). */
  private const SILENT_CONFIG = ['cookieSecret'];

  /** File extensions that are never copied from the old upload folders. */
  private const UNSAFE_EXTENSIONS = ['php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'pht', 'phps', 'phar', 'htaccess', 'htpasswd', 'ini'];

  private const PAGE_SIZE = 500;

  /** @var array<string, int|null> */
  private array $userIdCache = [];

  /** @var array<string, array<int, true>> */
  private array $idSets = [];

  /** @var array<string, \PDOStatement> */
  private array $userLookup = [];

  /** @var string[]|null */
  private ?array $schemaTables = null;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO      $target      Connection to the 6.0.0 database
   * @param string   $prefix      Table prefix of the 6.0.0 database
   * @param string   $appRoot     Absolute path of the 6.0.0 installation (no trailing slash)
   * @param string   $avatarDir   Avatar folder, relative to $appRoot (e.g. public/upload/avatars/)
   * @param string   $uploadDir   Attachment folder, relative to $appRoot (e.g. public/upload/files/)
   * @param string[] $configKeys  Names of the settings that exist in 6.0.0
   */
  public function __construct(
    private PDO $target,
    private string $prefix,
    private string $appRoot,
    private string $avatarDir,
    private string $uploadDir,
    private array $configKeys
  ) {
  }

  //---------------------------------------------------------------------------
  /**
   * Resolves the folder of the old installation given by the administrator.
   *
   * @param string $folder  Absolute path, or path relative to the application root
   * @param string $appRoot Absolute path of this installation
   *
   * @return string|null Real path, or null if it is not a readable TeamCal installation other than this one
   */
  public static function resolveFolder(string $folder, string $appRoot): ?string {
    $folder = trim($folder);
    if ($folder === '') {
      return null;
    }
    if (!preg_match('~^([A-Za-z]:[\\/]|[\\/])~', $folder)) {
      $folder = rtrim($appRoot, '/\\') . '/' . $folder;
    }
    $real = realpath($folder);
    if ($real === false || !is_dir($real) || !is_file($real . '/config/config.app.php')) {
      return null;
    }
    $self = realpath($appRoot);
    return $self !== false && $real === $self ? null : $real;
  }

  //---------------------------------------------------------------------------
  /**
   * Reads the database settings of an old installation from its .env file or,
   * for very old installations, from config/config.db.php.
   *
   * @param string $folder Absolute path of the old installation
   *
   * @return array<string, string> Settings found (keys host, port, socket, name, user, pass, prefix)
   */
  public static function readSourceSettings(string $folder): array {
    $folder = rtrim($folder, '/\\');
    $found  = [];
    $env    = $folder . '/.env';
    if (is_file($env) && class_exists('Dotenv\Dotenv')) {
      $values = \Dotenv\Dotenv::parse((string) file_get_contents($env));
      $map    = ['host' => 'DB_HOST', 'port' => 'DB_PORT', 'socket' => 'DB_SOCKET', 'name' => 'DB_NAME', 'user' => 'DB_USER', 'pass' => 'DB_PASS', 'prefix' => 'DB_PREFIX'];
      foreach ($map as $key => $envKey) {
        if (isset($values[$envKey])) {
          $found[$key] = (string) $values[$envKey];
        }
      }
    }
    $legacy = $folder . '/config/config.db.php';
    if (!isset($found['host']) && is_file($legacy)) {
      // Fallback block of config.db.php, read as text - the file is never executed
      $php = (string) file_get_contents($legacy);
      $map = ['host' => 'db_server', 'port' => 'db_port', 'socket' => 'db_socket', 'name' => 'db_name', 'user' => 'db_user', 'pass' => 'db_pass', 'prefix' => 'db_table_prefix'];
      foreach ($map as $key => $confKey) {
        if (preg_match('/\$CONF\[\'' . $confKey . '\'\]\s*=\s*(["\'])(.*?)\1\s*;/s', $php, $m)) {
          $found[$key] = $m[2];
        }
      }
    }
    return $found;
  }

  //---------------------------------------------------------------------------
  /**
   * Opens a read connection to the old database.
   *
   * @param array<string, string> $settings Keys host, port, socket, name, user, pass
   *
   * @return PDO Connection (UTC session time zone, like the target)
   */
  public static function connectSource(array $settings): PDO {
    $dsn = 'mysql:' . (($settings['socket'] ?? '') !== ''
        ? 'unix_socket=' . $settings['socket']
        : 'host=' . ($settings['host'] ?? 'localhost') . (($settings['port'] ?? '') !== '' ? ';port=' . $settings['port'] : ''))
      . ';dbname=' . ($settings['name'] ?? '') . ';charset=utf8mb4';
    $pdo = new PDO($dsn, (string) ($settings['user'] ?? ''), (string) ($settings['pass'] ?? ''), [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    $pdo->exec("SET time_zone = '+00:00'");
    return $pdo;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether the import can run. Changes nothing.
   *
   * @param PDO    $src       Connection to the old database
   * @param string $srcPrefix Table prefix of the old database
   *
   * @return array{errors: string[], counts: array<string, int>} Problems found (empty = ready) and old row counts
   */
  public function preflight(PDO $src, string $srcPrefix): array {
    $errors = [];
    $counts = [];
    $p      = $srcPrefix;
    $tables = $this->tableSet($src);

    // Target: a fresh 6.0.0 installation
    $tp = $this->prefix;
    if (!$this->targetHasTable('absence_days') || !$this->targetHasTable('pattern_days')) {
      $errors[] = 'err_target_schema';
    }
    else {
      $users = $this->scalar($this->target, 'SELECT COUNT(*) FROM `' . $tp . 'users`');
      $days  = $this->scalar($this->target, 'SELECT COUNT(*) FROM `' . $tp . 'absence_days`');
      $notes = $this->scalar($this->target, 'SELECT COUNT(*) FROM `' . $tp . 'daynotes`');
      if ($users > 1 || $days > 0 || $notes > 0) {
        $errors[] = 'err_target_not_fresh';
      }
    }

    // Source: a complete 5.3.7 database
    foreach (self::OLD_TABLES as $name) {
      if (!isset($tables[strtolower($p . $name)])) {
        $errors[] = 'err_source_table|' . $p . $name;
      }
    }
    if ($errors) {
      return ['errors' => $errors, 'counts' => $counts];
    }
    if ($this->columnExists($src, $p . 'users', 'id')) {
      $errors[] = 'err_source_not_537';
    }
    elseif (!$this->columnExists($src, $p . 'users', 'bad_logins_start')) {
      $errors[] = 'err_source_version';
    }
    if (!$this->columnExists($src, $p . 'templates', 'abs31')) {
      $errors[] = 'err_source_templates';
    }
    if ($errors) {
      return ['errors' => $errors, 'counts' => $counts];
    }

    // Data that cannot satisfy the new constraints
    $n = $this->scalar($src, "SELECT COUNT(*) FROM `{$p}users` u LEFT JOIN `{$p}roles` r ON r.id = COALESCE(NULLIF(u.role, 0), 2) WHERE r.id IS NULL");
    if ($n) {
      $errors[] = 'err_role_missing|' . $n;
    }
    foreach (['users', 'archive_users'] as $tbl) {
      $n = $this->scalar($src, 'SELECT COUNT(*) FROM (SELECT 1 FROM `' . $p . $tbl . '` GROUP BY ' . $this->uname('username') . ' HAVING COUNT(*) > 1) x');
      if ($n) {
        $errors[] = 'err_username_case|' . $p . $tbl;
      }
    }
    if ($this->scalar($src, "SELECT COUNT(*) FROM (SELECT name FROM `{$p}roles` GROUP BY name HAVING COUNT(*) > 1) x")) {
      $errors[] = 'err_role_duplicate';
    }
    if ($this->scalar($src, "SELECT COUNT(*) FROM (SELECT filename FROM `{$p}attachments` WHERE filename IS NOT NULL AND filename <> '' GROUP BY filename HAVING COUNT(*) > 1) x")) {
      $errors[] = 'err_attachment_duplicate';
    }
    $n = $this->scalar($src, "SELECT COUNT(*) FROM (SELECT type FROM `{$p}user_group` UNION SELECT type FROM `{$p}archive_user_group`) x WHERE type IS NOT NULL AND LOWER(TRIM(type)) NOT IN ('', 'member', 'manager', 'guest')");
    if ($n) {
      $errors[] = 'err_group_type';
    }

    if (!$errors) {
      foreach (self::OLD_TABLES as $name) {
        $counts[$name] = $this->scalar($src, 'SELECT COUNT(*) FROM `' . $p . $name . '`');
      }
    }
    return ['errors' => $errors, 'counts' => $counts];
  }

  //---------------------------------------------------------------------------
  /**
   * Creates the state of a new import.
   *
   * @param string                $srcPrefix Table prefix of the old database
   * @param array<string, int>    $oldCounts Row counts from preflight()
   * @param string|null           $srcDir    Absolute path of the old installation, null to skip the files
   *
   * @return array<string, mixed> Import state
   */
  public function begin(string $srcPrefix, array $oldCounts, ?string $srcDir): array {
    return [
      'srcPrefix' => $srcPrefix,
      'srcDir'    => $srcDir,
      'oldCounts' => $oldCounts,
      'step'      => 0,
      'cursor'    => [],
      'stats'     => [
        'cells'   => ['absence_days' => 0, 'archive_absence_days' => 0, 'calendar_days' => 0],
        'skipped' => [],
        'cal'     => ['business_day_collapsed' => 0, 'bad_date' => 0, 'bad_ref' => 0],
        'config'  => ['imported' => 0, 'skipped' => [], 'excluded' => []],
        'files'   => [],
      ],
      'maxUserId' => 0,
      'newCounts' => [],
    ];
  }

  //---------------------------------------------------------------------------
  /**
   * Runs the import for up to $budget seconds.
   *
   * @param PDO                  $src    Connection to the old database
   * @param array<string, mixed> $state  Import state from begin(), updated in place
   * @param float                $budget Time budget in seconds (a step in progress finishes its current batch)
   *
   * @return array{done: bool, step: string, percent: int, result?: array<string, mixed>} Progress
   */
  public function run(PDO $src, array &$state, float $budget = 15.0): array {
    $deadline = microtime(true) + $budget;
    $this->target->exec("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION'");
    $this->target->exec("SET time_zone = '+00:00'");

    $total = count(self::STEPS);
    while ($state['step'] < $total) {
      $name     = self::STEPS[$state['step']];
      $finished = match ($name) {
        'wipe'          => $this->stepWipe(),
        'reference'     => $this->stepReference($src, $state),
        'config'        => $this->stepConfig($src, $state),
        'messages'      => $this->stepMessages($src, $state, $deadline),
        'log'           => $this->stepLog($src, $state, $deadline),
        'users'         => $this->stepUsers($src, $state),
        'userdata'      => $this->stepUserData($src, $state, $deadline),
        'daynotes'      => $this->stepDaynotes($src, $state, $deadline),
        'absence_days'  => $this->stepAbsenceDays($src, $state, $deadline),
        'calendar_days' => $this->stepCalendarDays($src, $state, $deadline),
        'verify'        => $this->stepVerify($state),
        'files'         => $this->stepFiles($state, $deadline),
      };
      if ($finished) {
        $state['step']++;
        $state['cursor'] = [];
      }
      if (microtime(true) >= $deadline) {
        break;
      }
    }

    $done     = $state['step'] >= $total;
    $progress = [
      'done'    => $done,
      'step'    => $done ? 'done' : self::STEPS[$state['step']],
      'percent' => $done ? 100 : (int) floor((($state['step'] + (float) ($state['cursor']['frac'] ?? 0)) / $total) * 100),
    ];
    if ($done) {
      $progress['result'] = $this->result($state);
    }
    return $progress;
  }

  //---------------------------------------------------------------------------
  /**
   * Puts the target back into the state of a fresh installation (recreates all
   * tables from sql/basic.sql). Used when an import fails or is cancelled.
   *
   * @return void
   */
  public function restoreFresh(): void {
    $sql = file_get_contents($this->appRoot . '/sql/basic.sql');
    if ($sql === false) {
      throw new RuntimeException('Cannot read sql/basic.sql');
    }
    $this->target->exec("SET SESSION sql_mode = ''");
    $this->target->exec(str_replace('`tcneo_', '`' . $this->prefix, $sql));
  }

  //---------------------------------------------------------------------------
  // Steps. Each returns true when finished, false when it wants to be called again.
  //---------------------------------------------------------------------------

  /**
   * Empties all tables except the settings.
   */
  private function stepWipe(): bool {
    $this->userIdCache = [];
    $this->idSets      = [];
    $this->target->exec('SET FOREIGN_KEY_CHECKS = 0');
    foreach ($this->schemaTableNames() as $name) {
      if ($name !== 'config') {
        $this->target->exec('TRUNCATE TABLE ' . $this->t($name));
      }
    }
    $this->target->exec('SET FOREIGN_KEY_CHECKS = 1');
    return true;
  }

  /**
   * Reference data: small tables that are copied in one go.
   *
   * @param array<string, mixed> $state
   */
  private function stepReference(PDO $src, array &$state): bool {
    $p   = $state['srcPrefix'];
    $fix = fn($v): string => $this->fixDate($v);

    $this->copyAll($src, $p . 'roles', 'roles', ['id', 'name', 'description', 'color', 'created', 'updated'],
      fn(array $r): array => [(int) $r['id'], $r['name'], $r['description'], $r['color'], $fix($r['created'] ?? null), $fix($r['updated'] ?? null)]);
    $this->copyAll($src, $p . 'regions', 'regions', ['id', 'name', 'description'],
      fn(array $r): array => [(int) $r['id'], $r['name'], $r['description']]);
    $this->copyAll($src, $p . 'region_role', 'region_role', ['id', 'region_id', 'role_id', 'access'],
      fn(array $r): ?array => $this->has('regions', (int) $r['regionid']) && $this->has('roles', (int) $r['roleid'])
        ? [(int) $r['id'], (int) $r['regionid'], (int) $r['roleid'], $r['access'] ?? 'edit'] : null,
      true);
    $this->copyAll($src, $p . 'holidays', 'holidays', ['id', 'name', 'description', 'color', 'bgcolor', 'businessday', 'noabsence', 'keepweekendcolor', 'is_system'],
      fn(array $r): array => [(int) $r['id'], $r['name'], $r['description'], $r['color'], $r['bgcolor'], $r['businessday'], $r['noabsence'], $r['keepweekendcolor'], (int) $r['id'] <= 3 ? 1 : 0]);
    $this->copyAll($src, $p . 'absences', 'absences',
      ['id', 'name', 'symbol', 'icon', 'color', 'bgcolor', 'bgtrans', 'factor', 'allowance', 'allowmonth', 'allowweek', 'show_in_remainder', 'show_totals', 'approval_required', 'counts_as_present', 'manager_only', 'hide_in_profile', 'confidential', 'takeover'],
      fn(array $r): array => [(int) $r['id'], $r['name'], $r['symbol'], $r['icon'], $r['color'], $r['bgcolor'], $r['bgtrans'], $r['factor'], $r['allowance'], $r['allowmonth'], $r['allowweek'], $r['show_in_remainder'], $r['show_totals'], $r['approval_required'], $r['counts_as_present'], $r['manager_only'], $r['hide_in_profile'], $r['confidential'], $r['takeover']]);
    // counts_as references the same table, so it is set once all absences exist. 0 (the old "none") stays NULL.
    $update = $this->target->prepare('UPDATE ' . $this->t('absences') . ' SET counts_as = ? WHERE id = ?');
    foreach ($src->query('SELECT id, counts_as FROM `' . $p . 'absences` WHERE counts_as > 0 AND counts_as <> id') as $r) {
      if ($this->has('absences', (int) $r['counts_as'])) {
        $update->execute([(int) $r['counts_as'], (int) $r['id']]);
      }
    }
    $this->copyAll($src, $p . 'groups', 'groups', ['id', 'name', 'description', 'avatar', 'minpresent', 'maxabsent', 'minpresentwe', 'maxabsentwe'],
      fn(array $r): array => [(int) $r['id'], $r['name'], $r['description'], $r['avatar'], $r['minpresent'], $r['maxabsent'], $r['minpresentwe'], $r['maxabsentwe']]);
    $this->copyAll($src, $p . 'absence_group', 'absence_group', ['id', 'absence_id', 'group_id'],
      fn(array $r): ?array => $this->has('absences', (int) $r['absid']) && $this->has('groups', (int) $r['groupid'])
        ? [(int) $r['id'], (int) $r['absid'], (int) $r['groupid']] : null,
      true);
    $this->copyAll($src, $p . 'patterns', 'patterns', ['id', 'name', 'description'],
      fn(array $r): array => [(int) $r['id'], $r['name'], $r['description'] ?? '']);
    $days = [];
    foreach ($src->query('SELECT * FROM `' . $p . 'patterns` ORDER BY id') as $r) {
      for ($d = 1; $d <= 7; $d++) {
        if ($this->has('absences', (int) ($r["abs$d"] ?? 0))) {
          $days[] = [(int) $r['id'], $d, (int) $r["abs$d"]];
        }
      }
    }
    $this->insertRows('pattern_days', ['pattern_id', 'weekday', 'absence_id'], $days);
    $this->copyAll($src, $p . 'permissions', 'permissions', ['id', 'scheme', 'permission', 'role_id', 'allowed'],
      fn(array $r): ?array => $this->has('roles', (int) $r['role']) ? [(int) $r['id'], $r['scheme'], $r['permission'], (int) $r['role'], !empty($r['allowed']) ? 1 : 0] : null,
      true);
    return true;
  }

  /**
   * Settings: values of the old installation are carried over where the
   * setting still exists in 6.0.0. Names are matched case-insensitively.
   *
   * @param array<string, mixed> $state
   */
  private function stepConfig(PDO $src, array &$state): bool {
    $known = [];
    foreach ($this->configKeys as $key) {
      $known[strtolower($key)] = $key;
    }
    $upsert = $this->target->prepare('INSERT INTO ' . $this->t('config') . ' (name, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = VALUES(value)');
    foreach ($src->query('SELECT id, name, value FROM `' . $state['srcPrefix'] . 'config` ORDER BY id') as $r) {
      $name = $known[strtolower(trim((string) $r['name']))] ?? null;
      if ($name === null) {
        if (!in_array(trim((string) $r['name']), self::SILENT_CONFIG, true)) {
          $state['stats']['config']['skipped'][] = (string) $r['name'];
        }
      }
      elseif (in_array($name, self::EXCLUDED_CONFIG, true)) {
        $state['stats']['config']['excluded'][] = $name;
      }
      else {
        $upsert->execute([$name, (string) $r['value']]);
        $state['stats']['config']['imported']++;
      }
    }
    return true;
  }

  /**
   * @param array<string, mixed> $state
   */
  private function stepMessages(PDO $src, array &$state, float $deadline): bool {
    return $this->copyPaged($src, $state['srcPrefix'] . 'messages', 'messages', ['id', 'timestamp', 'text', 'type'], $state['cursor'], $deadline,
      fn(array $r): array => [(int) $r['id'], $this->fixDate($r['timestamp'] ?? null), $r['text'] ?? '', $r['type'] ?? '']);
  }

  /**
   * @param array<string, mixed> $state
   */
  private function stepLog(PDO $src, array &$state, float $deadline): bool {
    return $this->copyPaged($src, $state['srcPrefix'] . 'log', 'log', ['id', 'type', 'timestamp', 'ip', 'user', 'event'], $state['cursor'], $deadline,
      fn(array $r): array => [(int) $r['id'], $r['type'], $this->fixDate($r['timestamp'] ?? null), $r['ip'], $r['user'], $r['event']]);
  }

  /**
   * Users get numeric ids: live users first (admin = 1), archived users after them.
   *
   * @param array<string, mixed> $state
   */
  private function stepUsers(PDO $src, array &$state): bool {
    $p       = $state['srcPrefix'];
    $roleIds = array_flip(array_map('intval', $this->target->query('SELECT id FROM ' . $this->t('roles'))->fetchAll(PDO::FETCH_COLUMN)));
    $columns = ['id', 'username', 'password', 'firstname', 'lastname', 'email', 'order_key', 'role_id', 'locked', 'hidden', 'onhold', 'verify', 'is_system', 'bad_logins', 'bad_logins_start', 'grace_start', 'last_pw_change', 'last_login', 'created', 'oidc_sub'];
    $nextId  = 1;
    $queries = [
      ['users', false, 'SELECT * FROM `' . $p . "users` ORDER BY (LOWER(username) = 'admin') DESC, created, username"],
      ['archive_users', true, 'SELECT * FROM `' . $p . 'archive_users` ORDER BY created, username'],
    ];
    foreach ($queries as [$table, $archive, $query]) {
      $insert = $this->target->prepare('INSERT INTO ' . $this->t($table) . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', array_fill(0, count($columns), '?')) . ')');
      foreach ($src->query($query) as $r) {
        $role = (int) ($r['role'] ?? 0);
        if ($archive && !isset($roleIds[$role])) {
          $role = 0;   // archived user whose role was deleted: role_id becomes NULL
        }
        $insert->execute([
          $nextId,
          $r['username'],
          $r['password'],
          $r['firstname'],
          $r['lastname'],
          $r['email'],
          $r['order_key'] ?? '0',
          $role > 0 ? $role : ($archive ? null : 2),
          empty($r['locked']) ? 0 : 1,
          empty($r['hidden']) ? 0 : 1,
          empty($r['onhold']) ? 0 : 1,
          empty($r['verify']) ? 0 : 1,
          strtolower((string) $r['username']) === 'admin' ? 1 : 0,
          (int) ($r['bad_logins'] ?? 0),
          (int) ($r['bad_logins_start'] ?? 0),
          $this->fixDate($r['grace_start'] ?? null),
          $this->fixDate($r['last_pw_change'] ?? null),
          $this->fixDate($r['last_login'] ?? null),
          $this->fixDate($r['created'] ?? null),
          ($r['oidc_sub'] ?? '') !== '' ? $r['oidc_sub'] : null,
        ]);
        $nextId++;
      }
    }
    $state['maxUserId'] = $nextId - 1;
    $this->userIdCache  = [];
    return true;
  }

  /**
   * Everything that belongs to a user: options, group memberships, attachments, messages, allowances
   * (live and archive).
   *
   * @param array<string, mixed> $state
   */
  private function stepUserData(PDO $src, array &$state, float $deadline): bool {
    $p     = $state['srcPrefix'];
    $type  = static fn($v): string => match (strtolower(trim((string) ($v ?? '')))) {
      'manager' => 'manager',
      'guest'   => 'guest',
      default   => 'member',
    };
    $live  = fn(array $r): ?int => $this->userId((string) $r['username'], false);
    $arch  = fn(array $r): ?int => $this->userId((string) $r['username'], true);
    $specs = [
      // source table, destination, columns, row mapper, keep only the newest row per key
      ['user_option', 'user_option', ['id', 'user_id', 'option', 'value'],
        fn(array $r): ?array => ($u = $live($r)) !== null && $r['option'] !== null ? [(int) $r['id'], $u, $r['option'], $r['value']] : null, true],
      ['user_group', 'user_group', ['id', 'user_id', 'group_id', 'type'],
        fn(array $r): ?array => ($u = $live($r)) !== null && $this->has('groups', (int) $r['groupid']) ? [(int) $r['id'], $u, (int) $r['groupid'], $type($r['type'])] : null, true],
      ['attachments', 'attachments', ['id', 'filename', 'uploader_id'],
        fn(array $r): ?array => ($r['filename'] ?? '') !== '' ? [(int) $r['id'], $r['filename'], $this->userId((string) ($r['uploader'] ?? ''), false)] : null, false],
      ['user_attachment', 'user_attachment', ['id', 'user_id', 'attachment_id'],
        fn(array $r): ?array => ($u = $live($r)) !== null && $this->has('attachments', (int) $r['fileid']) ? [(int) $r['id'], $u, (int) $r['fileid']] : null, true],
      ['user_message', 'user_message', ['id', 'user_id', 'message_id', 'popup'],
        fn(array $r): ?array => ($u = $live($r)) !== null && $this->has('messages', (int) $r['msgid']) ? [(int) $r['id'], $u, (int) $r['msgid'], !empty($r['popup']) ? 1 : 0] : null, true],
      ['allowances', 'allowances', ['id', 'user_id', 'absence_id', 'carryover', 'allowance'],
        fn(array $r): ?array => ($u = $live($r)) !== null && $this->has('absences', (int) $r['absid']) ? [(int) $r['id'], $u, (int) $r['absid'], $r['carryover'] ?? 0, $r['allowance'] ?? 0] : null, true],
      ['archive_user_option', 'archive_user_option', ['id', 'user_id', 'option', 'value'],
        fn(array $r): ?array => ($u = $arch($r)) !== null ? [(int) $r['id'], $u, $r['option'], $r['value']] : null, false],
      ['archive_user_group', 'archive_user_group', ['id', 'user_id', 'group_id', 'type'],
        fn(array $r): ?array => ($u = $arch($r)) !== null && $this->has('groups', (int) $r['groupid']) ? [(int) $r['id'], $u, (int) $r['groupid'], $type($r['type'])] : null, false],
      ['archive_user_attachment', 'archive_user_attachment', ['id', 'user_id', 'attachment_id'],
        fn(array $r): ?array => ($u = $arch($r)) !== null && $this->has('attachments', (int) $r['fileid']) ? [(int) $r['id'], $u, (int) $r['fileid']] : null, false],
      ['archive_user_message', 'archive_user_message', ['id', 'user_id', 'message_id', 'popup'],
        fn(array $r): ?array => ($u = $arch($r)) !== null && $this->has('messages', (int) $r['msgid']) ? [(int) $r['id'], $u, (int) $r['msgid'], !empty($r['popup']) ? 1 : 0] : null, false],
      ['archive_allowances', 'archive_allowances', ['id', 'user_id', 'absence_id', 'carryover', 'allowance'],
        fn(array $r): ?array => ($u = $arch($r)) !== null && $this->has('absences', (int) $r['absid']) ? [(int) $r['id'], $u, (int) $r['absid'], $r['carryover'] ?? 0, $r['allowance'] ?? 0] : null, false],
    ];

    $cursor = &$state['cursor'];
    $cursor['i'] ??= 0;
    while ($cursor['i'] < count($specs)) {
      [$table, $dest, $columns, $map, $newest] = $specs[$cursor['i']];
      $sub = ['last' => $cursor['last'] ?? 0, 'max' => $cursor['max'] ?? null];
      $fin = $this->copyPaged($src, $p . $table, $dest, $columns, $sub, $deadline, $map, $newest);
      $cursor['last'] = $sub['last'];
      $cursor['max']  = $sub['max'];
      $cursor['frac'] = ($cursor['i'] + ($sub['frac'] ?? 0)) / count($specs);
      if (!$fin) {
        return false;
      }
      $cursor['i']++;
      $cursor['last'] = 0;
      $cursor['max']  = null;
      if (microtime(true) >= $deadline) {
        return $cursor['i'] >= count($specs);
      }
    }
    return true;
  }

  /**
   * Day notes: the yyyymmdd string becomes a DATE, 'all' becomes a NULL user, an unknown region becomes NULL.
   *
   * @param array<string, mixed> $state
   */
  private function stepDaynotes(PDO $src, array &$state, float $deadline): bool {
    $p       = $state['srcPrefix'];
    $columns = ['id', 'day', 'user_id', 'region_id', 'daynote', 'color', 'confidential'];
    $cursor  = &$state['cursor'];
    $cursor['i'] ??= 0;
    foreach (['daynotes', 'archive_daynotes'] as $i => $table) {
      if ($cursor['i'] > $i) {
        continue;
      }
      $archive = $table === 'archive_daynotes';
      $sub     = ['last' => $cursor['last'] ?? 0, 'max' => $cursor['max'] ?? null];
      $map     = function (array $r) use (&$state, $table, $archive): ?array {
        $ymd = (string) ($r['yyyymmdd'] ?? '');
        if (!preg_match('/^(\d{4})(\d{2})(\d{2})$/', $ymd, $m) || !checkdate((int) $m[2], (int) $m[3], (int) $m[1])) {
          $state['stats']['skipped'][$table] = ($state['stats']['skipped'][$table] ?? 0) + 1;
          return null;
        }
        if (!$archive && strtolower((string) $r['username']) === 'all') {
          $uid = null;
        }
        else {
          $uid = $this->userId((string) $r['username'], $archive);
          if ($uid === null) {
            $state['stats']['skipped'][$table] = ($state['stats']['skipped'][$table] ?? 0) + 1;
            return null;
          }
        }
        return [(int) $r['id'], "$m[1]-$m[2]-$m[3]", $uid, $this->has('regions', (int) $r['region']) ? (int) $r['region'] : null, $r['daynote'], $r['color'] ?? 'default', empty($r['confidential']) ? 0 : 1];
      };
      $fin = $this->copyPaged($src, $p . $table, $table, $columns, $sub, $deadline, $map);
      $cursor['last'] = $sub['last'];
      $cursor['max']  = $sub['max'];
      $cursor['frac'] = ($i + ($sub['frac'] ?? 0)) / 2;
      if (!$fin) {
        return false;
      }
      $cursor['i']    = $i + 1;
      $cursor['last'] = 0;
      $cursor['max']  = null;
    }
    return true;
  }

  /**
   * Absence days: abs1..abs31 of every user and month become one row per day.
   *
   * @param array<string, mixed> $state
   */
  private function stepAbsenceDays(PDO $src, array &$state, float $deadline): bool {
    $p      = $state['srcPrefix'];
    $cursor = &$state['cursor'];
    $cursor['i'] ??= 0;
    foreach (['templates' => 'absence_days', 'archive_templates' => 'archive_absence_days'] as $table => $dest) {
      $i = $table === 'templates' ? 0 : 1;
      if ($cursor['i'] > $i) {
        continue;
      }
      $archive = $i === 1;
      $last    = (int) ($cursor['last'] ?? 0);
      $max     = $cursor['max'] ?? $this->scalar($src, 'SELECT COALESCE(MAX(id), 0) FROM `' . $p . $table . '`');
      do {
        $page = $src->query('SELECT * FROM `' . $p . $table . "` WHERE id > $last ORDER BY id LIMIT 200")->fetchAll();
        $rows = [];
        foreach ($page as $r) {
          $last = (int) $r['id'];
          $uid  = $this->userId((string) $r['username'], $archive);
          $y    = (int) $r['year'];
          $mo   = (int) $r['month'];
          if ($uid === null) {
            $state['stats']['skipped'][$table . '_no_user'] = ($state['stats']['skipped'][$table . '_no_user'] ?? 0) + 1;
            continue;
          }
          for ($d = 1; $d <= 31; $d++) {
            $abs = (int) ($r["abs$d"] ?? 0);
            if ($abs <= 0) {
              continue;
            }
            if (!checkdate($mo, $d, $y)) {
              $state['stats']['skipped'][$table . '_bad_date'] = ($state['stats']['skipped'][$table . '_bad_date'] ?? 0) + 1;
            }
            elseif (!$this->has('absences', $abs)) {
              $state['stats']['skipped'][$table . '_bad_absence'] = ($state['stats']['skipped'][$table . '_bad_absence'] ?? 0) + 1;
            }
            else {
              $rows[] = [$uid, sprintf('%04d-%02d-%02d', $y, $mo, $d), $abs];
              $state['stats']['cells'][$dest]++;
            }
          }
        }
        $this->insertRows($dest, ['user_id', 'day', 'absence_id'], $rows);
        $cursor['last'] = $last;
        $cursor['max']  = $max;
        $cursor['frac'] = ($i + ($max > 0 ? min(1, $last / $max) : 1)) / 2;
        if (count($page) === 200 && microtime(true) >= $deadline) {
          return false;
        }
      } while (count($page) === 200);
      $cursor['i']    = $i + 1;
      $cursor['last'] = 0;
      $cursor['max']  = null;
    }
    return true;
  }

  /**
   * Calendar days: only real overrides (holiday id > 1) become rows, "Business Day" is the default.
   *
   * @param array<string, mixed> $state
   */
  private function stepCalendarDays(PDO $src, array &$state, float $deadline): bool {
    $p      = $state['srcPrefix'];
    $cursor = &$state['cursor'];
    $last   = (int) ($cursor['last'] ?? 0);
    $max    = $cursor['max'] ?? $this->scalar($src, 'SELECT COALESCE(MAX(id), 0) FROM `' . $p . 'months`');
    do {
      $page = $src->query('SELECT * FROM `' . $p . "months` WHERE id > $last ORDER BY id LIMIT 200")->fetchAll();
      $rows = [];
      foreach ($page as $r) {
        $last = (int) $r['id'];
        $y    = (int) $r['year'];
        $mo   = (int) $r['month'];
        $reg  = (int) $r['region'];
        for ($d = 1; $d <= 31; $d++) {
          $h = (int) ($r["hol$d"] ?? 0);
          if ($h <= 0) {
            continue;
          }
          if ($h === 1) {
            $state['stats']['cal']['business_day_collapsed']++;
          }
          elseif (!checkdate($mo, $d, $y)) {
            $state['stats']['cal']['bad_date']++;
          }
          elseif (!$this->has('regions', $reg) || !$this->has('holidays', $h)) {
            $state['stats']['cal']['bad_ref']++;
          }
          else {
            $rows[] = [$reg, sprintf('%04d-%02d-%02d', $y, $mo, $d), $h];
            $state['stats']['cells']['calendar_days']++;
          }
        }
      }
      $this->insertRows('calendar_days', ['region_id', 'day', 'holiday_id'], $rows);
      $cursor['last'] = $last;
      $cursor['max']  = $max;
      $cursor['frac'] = $max > 0 ? min(1, $last / $max) : 1;
      if (count($page) === 200 && microtime(true) >= $deadline) {
        return false;
      }
    } while (count($page) === 200);
    return true;
  }

  /**
   * Compares the row counts with what the old database held.
   *
   * @param array<string, mixed> $state
   */
  private function stepVerify(array &$state): bool {
    $new = [];
    foreach ($this->schemaTableNames() as $name) {
      $new[$name] = $this->scalar($this->target, 'SELECT COUNT(*) FROM ' . $this->t($name));
    }
    $old      = $state['oldCounts'];
    $mismatch = [];
    foreach (self::LOSSLESS_TABLES as $name) {
      if ($new[$name] !== $old[$name]) {
        $mismatch[] = "$name: {$old[$name]} rows before, {$new[$name]} after";
      }
    }
    foreach ($state['stats']['cells'] as $name => $expected) {
      if ($new[$name] !== $expected) {
        $mismatch[] = "$name: expected $expected rows, found {$new[$name]}";
      }
    }
    if ($mismatch) {
      throw new RuntimeException('Verification failed: ' . implode('; ', $mismatch));
    }
    // New users must never receive an id already used by an archived user.
    $this->target->exec('ALTER TABLE ' . $this->t('users') . ' AUTO_INCREMENT = ' . ($state['maxUserId'] + 1));
    $state['newCounts'] = $new;
    return true;
  }

  /**
   * Copies avatars and attachments from the old installation.
   *
   * @param array<string, mixed> $state
   */
  private function stepFiles(array &$state, float $deadline): bool {
    $srcDir = $state['srcDir'];
    if ($srcDir === null) {
      $state['stats']['files'] = ['skipped' => true];
      return true;
    }
    $dirs   = ['avatars' => [$this->avatarDir, 'public/upload/avatars/'], 'uploads' => [$this->uploadDir, 'public/upload/files/']];
    $cursor = &$state['cursor'];
    $cursor['d'] ??= 0;
    $cursor['n'] ??= 0;
    $i = 0;
    foreach ($dirs as $key => [$targetRel, $sourceRel]) {
      if ($cursor['d'] > $i++) {
        continue;
      }
      $stats = &$state['stats']['files'][$key];
      $stats ??= ['copied' => 0, 'existing' => 0, 'unsafe' => 0, 'failed' => [], 'missing' => false];
      $from  = rtrim($srcDir, '/\\') . '/' . $sourceRel;
      $to    = $this->appRoot . '/' . $targetRel;
      if (!is_dir($from)) {
        $stats['missing'] = true;
        $cursor['d']      = $i;
        $cursor['n']      = 0;
        continue;
      }
      if (!is_dir($to) && !@mkdir($to, 0775, true) && !is_dir($to)) {
        $stats['failed'][] = $targetRel;
        $cursor['d']       = $i;
        $cursor['n']       = 0;
        continue;
      }
      $names = [];
      foreach (new DirectoryIterator($from) as $file) {
        if ($file->isFile() && !$file->isLink()) {
          $names[] = $file->getFilename();
        }
      }
      sort($names);
      for ($n = $cursor['n']; $n < count($names); $n++) {
        $name = $names[$n];
        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if ($name === 'index.html') {
          // placeholder file of the upload folders, the new installation has its own
        }
        elseif ($name[0] === '.' || in_array($ext, self::UNSAFE_EXTENSIONS, true)) {
          $stats['unsafe']++;
        }
        elseif (file_exists($to . $name)) {
          $stats['existing']++;
        }
        elseif (@copy($from . $name, $to . $name)) {
          $stats['copied']++;
        }
        else {
          $stats['failed'][] = $name;
        }
        $cursor['n'] = $n + 1;
        if ($n % 50 === 49 && microtime(true) >= $deadline) {
          return false;
        }
      }
      $cursor['d'] = $i;
      $cursor['n'] = 0;
    }
    return true;
  }

  //---------------------------------------------------------------------------
  // Result report
  //---------------------------------------------------------------------------

  /**
   * Builds the report shown after a successful import.
   *
   * @param array<string, mixed> $state
   *
   * @return array<string, mixed> Keys: counts (table => [old, new, into]), notes (list of [key, count]), config, files
   */
  private function result(array $state): array {
    $old    = $state['oldCounts'];
    $new    = $state['newCounts'];
    $counts = [];
    foreach (self::OLD_TABLES as $name) {
      $to            = match ($name) {
        'templates'         => 'absence_days',
        'archive_templates' => 'archive_absence_days',
        'months'            => 'calendar_days',
        default             => $name,
      };
      $counts[$name] = ['old' => $old[$name], 'new' => $new[$to] ?? 0, 'into' => $to];
    }
    $counts['pattern_days'] = ['old' => null, 'new' => $new['pattern_days'] ?? 0, 'into' => 'pattern_days'];

    $notes = [];
    foreach ($state['stats']['skipped'] as $key => $n) {
      $notes[] = [$key, $n];
    }
    foreach (['bad_date', 'bad_ref', 'business_day_collapsed'] as $key) {
      if ($state['stats']['cal'][$key]) {
        $notes[] = ['calendar_' . $key, $state['stats']['cal'][$key]];
      }
    }
    foreach (['user_option', 'user_group', 'user_attachment', 'user_message', 'allowances', 'absence_group', 'region_role', 'permissions', 'archive_user_option', 'archive_user_group', 'archive_user_attachment', 'archive_user_message', 'archive_allowances', 'attachments'] as $name) {
      $diff = $old[$name] - ($new[$name] ?? 0);
      if ($diff > 0) {
        $notes[] = ['rows_' . $name, $diff];
      }
    }
    return ['counts' => $counts, 'notes' => $notes, 'config' => $state['stats']['config'], 'files' => $state['stats']['files']];
  }

  //---------------------------------------------------------------------------
  // Helpers
  //---------------------------------------------------------------------------

  /**
   * Backticked, prefixed name of a 6.0.0 table.
   */
  private function t(string $name): string {
    return '`' . $this->prefix . $name . '`';
  }

  /**
   * Username comparison expression, independent of the legacy table charset/collation.
   */
  private function uname(string $column): string {
    return "CONVERT($column USING utf8mb4) COLLATE utf8mb4_unicode_ci";
  }

  /**
   * Zero-safe datetime (the old tables allowed 0000-00-00).
   */
  private function fixDate(mixed $value): string {
    return ($value === null || $value === '' || str_starts_with((string) $value, '0000')) ? '1970-01-01 00:00:00' : (string) $value;
  }

  private function scalar(PDO $db, string $sql): int {
    return (int) $db->query($sql)->fetchColumn();
  }

  /**
   * @return array<string, true> Lowercased names of all tables in the connection's schema
   */
  private function tableSet(PDO $db): array {
    $set = [];
    foreach ($db->query('SELECT table_name AS n FROM information_schema.tables WHERE table_schema = DATABASE()') as $row) {
      $set[strtolower((string) $row['n'])] = true;
    }
    return $set;
  }

  private function targetHasTable(string $name): bool {
    return isset($this->tableSet($this->target)[strtolower($this->prefix . $name)]);
  }

  private function columnExists(PDO $db, string $table, string $column): bool {
    $q = $db->prepare('SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = :t AND column_name = :c');
    $q->execute([':t' => $table, ':c' => $column]);
    return (int) $q->fetchColumn() > 0;
  }

  /**
   * @return string[] Unprefixed names of the tables of the 6.0.0 schema that exist in the target
   */
  private function schemaTableNames(): array {
    if ($this->schemaTables === null) {
      $basic = (string) file_get_contents($this->appRoot . '/sql/basic.sql');
      preg_match_all('/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?`tcneo_([a-z_]+)`/', $basic, $m);
      $existing = $this->tableSet($this->target);
      $this->schemaTables = array_values(array_filter($m[1], fn(string $n): bool => isset($existing[strtolower($this->prefix . $n)])));
    }
    return $this->schemaTables;
  }

  /**
   * Whether a row with this id exists in a (already filled) 6.0.0 table.
   */
  private function has(string $table, int $id): bool {
    if (!isset($this->idSets[$table])) {
      $this->idSets[$table] = array_fill_keys(array_map('intval', $this->target->query('SELECT id FROM ' . $this->t($table))->fetchAll(PDO::FETCH_COLUMN)), true);
    }
    return isset($this->idSets[$table][$id]);
  }

  /**
   * Resolves a user name to its new id. The lookup runs in the database so that
   * it uses the same collation as the username column (case/accent-insensitive).
   */
  private function userId(string $username, bool $archive): ?int {
    if ($username === '') {
      return null;
    }
    $key = ($archive ? 'a|' : 'l|') . $username;
    if (!array_key_exists($key, $this->userIdCache)) {
      $table = $archive ? 'archive_users' : 'users';
      $this->userLookup[$table] ??= $this->target->prepare('SELECT id FROM ' . $this->t($table) . ' WHERE username = ?');
      $this->userLookup[$table]->execute([$username]);
      $id = $this->userLookup[$table]->fetchColumn();
      $this->userIdCache[$key] = $id === false ? null : (int) $id;
    }
    return $this->userIdCache[$key];
  }

  /**
   * Multi-row INSERT.
   *
   * @param string[]               $columns
   * @param array<int, array<int, mixed>> $rows
   */
  private function insertRows(string $table, array $columns, array $rows, string $suffix = ''): void {
    if (!$rows) {
      return;
    }
    $quoted = implode(', ', array_map(static fn(string $c): string => '`' . $c . '`', $columns));
    $per    = '(' . implode(',', array_fill(0, count($columns), '?')) . ')';
    foreach (array_chunk($rows, max(1, intdiv(60000, count($columns)))) as $chunk) {
      $stmt = $this->target->prepare('INSERT INTO ' . $this->t($table) . " ($quoted) VALUES " . implode(',', array_fill(0, count($chunk), $per)) . $suffix);
      $stmt->execute(array_merge(...$chunk));
    }
  }

  /**
   * ON DUPLICATE KEY UPDATE clause that keeps the last row written (= the newest source row) per unique key.
   *
   * @param string[] $columns
   */
  private function newestSuffix(array $columns): string {
    return ' ON DUPLICATE KEY UPDATE ' . implode(', ', array_map(static fn(string $c): string => "`$c` = VALUES(`$c`)", $columns));
  }

  /**
   * Copies a whole (small) source table.
   *
   * @param string[]                                         $columns
   * @param callable(array<string, mixed>): (array<int, mixed>|null) $map    Row mapper, null skips the row
   */
  private function copyAll(PDO $src, string $srcTable, string $dest, array $columns, callable $map, bool $newest = false): void {
    $rows = [];
    foreach ($src->query('SELECT * FROM `' . $srcTable . '` ORDER BY id') as $r) {
      $values = $map($r);
      if ($values !== null) {
        $rows[] = $values;
      }
    }
    $this->insertRows($dest, $columns, $rows, $newest ? $this->newestSuffix($columns) : '');
    unset($this->idSets[$dest]);
  }

  /**
   * Copies a source table page by page (by id), stopping when the deadline has passed.
   *
   * @param string[]                                         $columns
   * @param array<string, mixed>                             $cursor Keys last (last source id done), max, frac; updated
   * @param callable(array<string, mixed>): (array<int, mixed>|null) $map    Row mapper, null skips the row
   *
   * @return bool True when the table is complete
   */
  private function copyPaged(PDO $src, string $srcTable, string $dest, array $columns, array &$cursor, float $deadline, callable $map, bool $newest = false): bool {
    $last         = (int) ($cursor['last'] ?? 0);
    $cursor['max'] ??= $this->scalar($src, 'SELECT COALESCE(MAX(id), 0) FROM `' . $srcTable . '`');
    $suffix       = $newest ? $this->newestSuffix($columns) : '';
    do {
      $page = $src->query('SELECT * FROM `' . $srcTable . "` WHERE id > $last ORDER BY id LIMIT " . self::PAGE_SIZE)->fetchAll();
      $rows = [];
      foreach ($page as $r) {
        $last   = (int) $r['id'];
        $values = $map($r);
        if ($values !== null) {
          $rows[] = $values;
        }
      }
      $this->insertRows($dest, $columns, $rows, $suffix);
      $cursor['last'] = $last;
      $cursor['frac'] = $cursor['max'] > 0 ? min(1, $last / $cursor['max']) : 1;
      if (count($page) === self::PAGE_SIZE && microtime(true) >= $deadline) {
        return false;
      }
    } while (count($page) === self::PAGE_SIZE);
    unset($this->idSets[$dest]);
    $cursor['frac'] = 1;
    return true;
  }
}
