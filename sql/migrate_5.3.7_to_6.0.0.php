<?php
declare(strict_types=1);

/**
 * TeamCal Neo migration script: 5.3.7 -> 6.0.0
 *
 * Converts an existing 5.3.7 database to the 6.0.0 schema. It changes the
 * structure and the representation of the data (surrogate user ids, one row
 * per absence day instead of one row per month, InnoDB, foreign keys), so it
 * cannot be expressed as plain SQL. See sql/update_5.3.7_to_6.0.0.sql for the
 * upgrade instructions.
 *
 * Usage (run from the application root, with the application in maintenance
 * mode or otherwise not in use):
 *
 *   php sql/migrate_5.3.7_to_6.0.0.php --dry-run          Check only, change nothing
 *   php sql/migrate_5.3.7_to_6.0.0.php --confirm-backup   Migrate
 *   php sql/migrate_5.3.7_to_6.0.0.php --drop-backup --confirm-drop
 *                                                         Remove the backup tables afterwards
 *
 * Safety model:
 *   - Every check runs before anything is changed. Any failed check aborts
 *     the run with the database untouched.
 *   - The old tables are never modified or deleted by the migration. They are
 *     renamed to <prefix>v537_<name> and kept until you drop them explicitly.
 *   - If anything fails while migrating, the new tables are dropped and the
 *     old tables are renamed back, so the database ends up as it started.
 *   - The data copy runs in one transaction; the structure changes cannot
 *     (MySQL/MariaDB commit DDL implicitly), which is why the rollback above
 *     exists. A database backup before running is still required.
 *
 * @package TeamCal Neo
 * @since   6.0.0
 */

if (PHP_SAPI !== 'cli') {
  http_response_code(403);
  exit("This script must be run from the command line.\n");
}

const BACKUP_TAG = 'v537_';

/** Tables of a 5.3.7 installation (unprefixed). */
const OLD_TABLES = [
  'absence_group', 'absences', 'allowances', 'archive_allowances', 'archive_daynotes',
  'archive_templates', 'archive_user_attachment', 'archive_user_group', 'archive_user_message',
  'archive_user_option', 'archive_users', 'attachments', 'config', 'daynotes', 'groups',
  'holidays', 'log', 'messages', 'months', 'patterns', 'permissions', 'region_role', 'regions',
  'roles', 'templates', 'user_attachment', 'user_group', 'user_message', 'user_option', 'users',
];

/** Tables whose row count must survive the migration unchanged (no orphans/duplicates possible). */
const LOSSLESS_TABLES = [
  'roles', 'regions', 'holidays', 'absences', 'groups', 'patterns', 'users', 'archive_users', 'log', 'messages',
];

$root = dirname(__DIR__);
$opts = getopt('', ['confirm-backup', 'confirm-drop', 'dry-run', 'drop-backup', 'help']);

if (isset($opts['help']) || (!isset($opts['dry-run']) && !isset($opts['confirm-backup']) && !isset($opts['drop-backup']))) {
  echo <<<TXT
  TeamCal Neo migration 5.3.7 -> 6.0.0

    php sql/migrate_5.3.7_to_6.0.0.php --dry-run
        Runs all pre-flight checks and reports row counts. Changes nothing.

    php sql/migrate_5.3.7_to_6.0.0.php --confirm-backup
        Migrates the database. Only use this after you have made a full
        database backup - the flag is your confirmation that you did.

    php sql/migrate_5.3.7_to_6.0.0.php --drop-backup --confirm-drop
        Drops the <prefix>v537_* backup tables once you have verified the
        migrated application.

  TXT;
  exit(isset($opts['help']) ? 0 : 1);
}

//-----------------------------------------------------------------------------
// Bootstrap: database credentials from .env / config.db.php
//-----------------------------------------------------------------------------
define('VALID_ROOT', 1);
$CONF = [];
if (is_file($root . '/vendor/autoload.php')) {
  require $root . '/vendor/autoload.php';
}
if (is_file($root . '/.env') && class_exists('Dotenv\Dotenv')) {
  Dotenv\Dotenv::createImmutable($root)->safeLoad();
}
require $root . '/config/config.db.php';

$P   = (string) $CONF['db_table_prefix'];
$B   = $P . BACKUP_TAG;
$dsn = 'mysql:' . (!empty($CONF['db_socket'])
    ? 'unix_socket=' . $CONF['db_socket']
    : 'host=' . $CONF['db_server'] . (!empty($CONF['db_port']) ? ';port=' . $CONF['db_port'] : ''))
  . ';dbname=' . $CONF['db_name'] . ';charset=utf8mb4';

try {
  $db = new PDO($dsn, (string) $CONF['db_user'], (string) $CONF['db_pass'], [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ]);
}
catch (PDOException $e) {
  fwrite(STDERR, 'Cannot connect to the database: ' . $e->getMessage() . "\n");
  exit(1);
}

//-----------------------------------------------------------------------------
// Helpers
//-----------------------------------------------------------------------------

/** Backticked, prefixed name of a current-schema table. */
function t(string $name): string {
  global $P;
  return '`' . $P . $name . '`';
}

/** Backticked name of a backed-up (5.3.7) table. */
function b(string $name): string {
  global $B;
  return '`' . $B . $name . '`';
}

/** Username comparison expression, independent of the legacy table charset/collation. */
function uname(string $column): string {
  return "CONVERT($column USING utf8mb4) COLLATE utf8mb4_unicode_ci";
}

/** Zero-safe datetime expression. */
function dt(string $column): string {
  return "IF($column IS NULL OR $column < '1000-01-01', '1970-01-01 00:00:00', $column)";
}

/** JOIN that keeps only the newest row (highest id) per key. Alias of the joined table must be "o". */
function latest(string $table, string $keys): string {
  return "JOIN (SELECT MAX(id) AS mid FROM $table GROUP BY $keys) k ON k.mid = o.id";
}

/** @return array<string, true> Lowercased names of all tables in the current schema. */
function existingTables(PDO $db): array {
  $set = [];
  foreach ($db->query('SELECT table_name AS n FROM information_schema.tables WHERE table_schema = DATABASE()') as $row) {
    $set[strtolower((string) $row['n'])] = true;
  }
  return $set;
}

function columnExists(PDO $db, string $table, string $column): bool {
  $q = $db->prepare('SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = :t AND column_name = :c');
  $q->execute([':t' => $table, ':c' => $column]);
  return (int) $q->fetchColumn() > 0;
}

function scalar(PDO $db, string $sql): int {
  return (int) $db->query($sql)->fetchColumn();
}

/** Lowercase key for user name lookups (multibyte-safe when mbstring is available). */
function lc(string $s): string {
  return function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s);
}

function say(string $msg = ''): void {
  echo $msg . "\n";
}

/** Batched multi-row INSERT for the unpivot loops. */
function flushRows(PDO $db, string $table, string $columns, array &$rows, string $suffix = ''): void {
  if (!$rows) {
    return;
  }
  $per  = '(' . implode(',', array_fill(0, count($rows[0]), '?')) . ')';
  $stmt = $db->prepare("INSERT INTO $table ($columns) VALUES " . implode(',', array_fill(0, count($rows), $per)) . $suffix);
  $stmt->execute(array_merge(...$rows));
  $rows = [];
}

//-----------------------------------------------------------------------------
// --drop-backup
//-----------------------------------------------------------------------------
if (isset($opts['drop-backup'])) {
  $tables = existingTables($db);
  if (!isset($tables[strtolower($P . 'absence_days')])) {
    fwrite(STDERR, "The database has not been migrated to 6.0.0. Nothing dropped.\n");
    exit(1);
  }
  $backups = [];
  foreach (OLD_TABLES as $name) {
    if (isset($tables[strtolower($B . $name)])) {
      $backups[] = $name;
    }
  }
  if (!$backups) {
    say('No backup tables found.');
    exit(0);
  }
  if (!isset($opts['confirm-drop'])) {
    say('This would permanently drop ' . count($backups) . ' backup tables (' . $B . '*). Run again with --confirm-drop to do it.');
    exit(1);
  }
  foreach ($backups as $name) {
    $db->exec('DROP TABLE ' . b($name));
  }
  say('Dropped ' . count($backups) . ' backup tables.');
  exit(0);
}

//-----------------------------------------------------------------------------
// Pre-flight checks - nothing has been changed up to the end of this section
//-----------------------------------------------------------------------------
$errors   = [];
$existing = existingTables($db);

say('TeamCal Neo migration 5.3.7 -> 6.0.0');
say('Database: ' . $CONF['db_name'] . ', table prefix: "' . $P . '"');
say();

// Database version floor (MariaDB 10.4 / MySQL 8.0)
$version = (string) $db->query('SELECT VERSION()')->fetchColumn();
$isMaria = stripos($version, 'mariadb') !== false;
$numeric = preg_replace('/^5\.5\.5-/', '', $version) ?? $version;
preg_match('/^\d+(?:\.\d+){1,2}/', $numeric, $m);
$floor = $isMaria ? '10.4' : '8.0';
if (!isset($m[0]) || version_compare($m[0], $floor, '<')) {
  $errors[] = "Database version $version is not supported. 6.0.0 requires MariaDB 10.4+ or MySQL 8.0+.";
}

// Already migrated?
if (isset($existing[strtolower($P . 'absence_days')]) && !isset($existing[strtolower($P . 'templates')])) {
  say('This database is already on the 6.0.0 schema. Nothing to do.');
  exit(0);
}

// Old shape present?
foreach (OLD_TABLES as $name) {
  if (!isset($existing[strtolower($P . $name)])) {
    $errors[] = "Table {$P}{$name} not found. This does not look like a complete 5.3.7 database (wrong prefix?).";
  }
}
foreach (OLD_TABLES as $name) {
  if (isset($existing[strtolower($B . $name)])) {
    $errors[] = "Backup table {$B}{$name} already exists - leftovers of an earlier run. Verify them, then remove with --drop-backup --confirm-drop.";
  }
}
foreach (['absence_days', 'calendar_days', 'pattern_days'] as $name) {
  if (isset($existing[strtolower($P . $name)])) {
    $errors[] = "Table {$P}{$name} already exists, but the database is not fully migrated. Resolve manually.";
  }
}

if (!$errors) {
  if (columnExists($db, $P . 'users', 'id')) {
    $errors[] = "{$P}users already has an id column - not a 5.3.7 schema.";
  }
  if (!columnExists($db, $P . 'users', 'bad_logins_start')) {
    $errors[] = "{$P}users has no bad_logins_start column. Run sql/update_5.3.6_to_5.3.7.sql first.";
  }
  if (!columnExists($db, $P . 'templates', 'abs31')) {
    $errors[] = "{$P}templates does not have the expected abs1..abs31 columns.";
  }
}

// Data checks that would make the new constraints impossible to satisfy
if (!$errors) {
  $bad = scalar($db, "SELECT COUNT(*) FROM {$P}users u LEFT JOIN {$P}roles r ON r.id = COALESCE(NULLIF(u.role, 0), 2) WHERE r.id IS NULL");
  if ($bad) {
    $errors[] = "$bad user(s) have a role that does not exist in {$P}roles. Fix with: SELECT username, role FROM {$P}users u LEFT JOIN {$P}roles r ON r.id = u.role WHERE r.id IS NULL;";
  }
  foreach (['users', 'archive_users'] as $tbl) {
    // The new schema compares user names case- and accent-insensitively (utf8mb4_unicode_ci).
    $bad = scalar($db, 'SELECT COUNT(*) FROM (SELECT 1 FROM ' . $P . $tbl . ' GROUP BY ' . uname('username') . ' HAVING COUNT(*) > 1) x');
    if ($bad) {
      $errors[] = "$bad group(s) of user names in {$P}{$tbl} differ only by case or accents (e.g. 'Bob' and 'bob'); user names must be unique in 6.0.0. Rename one of each pair first.";
    }
  }
  $bad = scalar($db, "SELECT COUNT(*) FROM (SELECT name FROM {$P}roles GROUP BY name HAVING COUNT(*) > 1) x");
  if ($bad) {
    $errors[] = "Duplicate role names exist in {$P}roles; role names must be unique in 6.0.0.";
  }
  $bad = scalar($db, "SELECT COUNT(*) FROM (SELECT filename FROM {$P}attachments WHERE filename IS NOT NULL AND filename <> '' GROUP BY filename HAVING COUNT(*) > 1) x");
  if ($bad) {
    $errors[] = "Duplicate file names exist in {$P}attachments; file names must be unique in 6.0.0.";
  }
  $bad = scalar($db, "SELECT COUNT(*) FROM (SELECT type FROM {$P}user_group UNION SELECT type FROM {$P}archive_user_group) x WHERE type IS NOT NULL AND LOWER(TRIM(type)) NOT IN ('', 'member', 'manager', 'guest')");
  if ($bad) {
    $errors[] = "{$P}user_group contains membership types other than member/manager/guest.";
  }
}

$oldCounts = [];
if (!$errors) {
  foreach (OLD_TABLES as $name) {
    $oldCounts[$name] = scalar($db, "SELECT COUNT(*) FROM `{$P}{$name}`");
  }
}

if ($errors) {
  say('Pre-flight checks FAILED. The database has not been changed:');
  foreach ($errors as $e) {
    say('  - ' . $e);
  }
  exit(1);
}

say('Pre-flight checks passed (' . $version . ').');
say('Rows to migrate:');
foreach ($oldCounts as $name => $n) {
  say(sprintf('  %-24s %d', $name, $n));
}
say();

if (isset($opts['dry-run'])) {
  say('Dry run - nothing was changed. Make a database backup, then run with --confirm-backup.');
  exit(0);
}

//-----------------------------------------------------------------------------
// Schema definition: CREATE TABLE statements from sql/basic.sql
//-----------------------------------------------------------------------------
$basic = file_get_contents($root . '/sql/basic.sql');
if ($basic === false || !preg_match_all('/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?`tcneo_([a-z_]+)`\s*\(.*?\)\s*ENGINE[^;]*;/s', $basic, $mm, PREG_SET_ORDER)) {
  fwrite(STDERR, "Cannot read the table definitions from sql/basic.sql.\n");
  exit(1);
}
$newSchema = [];
foreach ($mm as $match) {
  $newSchema[$match[1]] = str_replace('`tcneo_', '`' . $P, $match[0]);
}
foreach (['users', 'absence_days', 'calendar_days', 'pattern_days', 'archive_absence_days', 'config'] as $must) {
  if (!isset($newSchema[$must])) {
    fwrite(STDERR, "sql/basic.sql does not define $must - incomplete 6.0.0 package.\n");
    exit(1);
  }
}
$manifestFile = $root . '/sql/basic.manifest.php';
$manifest     = is_file($manifestFile) ? (require $manifestFile) : [];
$newConfig    = is_array($manifest['config'] ?? null) ? $manifest['config'] : [];

//-----------------------------------------------------------------------------
// Migration
//-----------------------------------------------------------------------------
$renamed = [];   // unprefixed name => true, tables moved to the backup names
$created = [];   // unprefixed name => true, new tables created by this run

try {
  say('Renaming the 5.3.7 tables to ' . $B . '* ...');
  $pairs = [];
  foreach (OLD_TABLES as $name) {
    $pairs[] = "`{$P}{$name}` TO `{$B}{$name}`";
  }
  $db->exec('RENAME TABLE ' . implode(', ', $pairs));
  foreach (OLD_TABLES as $name) {
    $renamed[$name] = true;
  }

  say('Creating the 6.0.0 tables ...');
  foreach ($newSchema as $name => $ddl) {
    $db->exec($ddl);
    $created[$name] = true;
  }

  $db->exec("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION'");
  $db->beginTransaction();
  say('Copying data ...');


  // --- Reference data ---------------------------------------------------------------------------
  $db->exec('INSERT INTO ' . t('roles') . ' (id, name, description, color, created, updated) SELECT id, name, description, color, created, updated FROM ' . b('roles'));
  $db->exec('INSERT INTO ' . t('regions') . ' (id, name, description) SELECT id, name, description FROM ' . b('regions'));
  $db->exec('INSERT INTO ' . t('region_role') . ' (id, region_id, role_id, access)
    SELECT o.id, o.regionid, o.roleid, COALESCE(o.access, \'edit\') FROM ' . b('region_role') . ' o ' . latest(b('region_role'), 'regionid, roleid') . '
    JOIN ' . t('regions') . ' r ON r.id = o.regionid JOIN ' . t('roles') . ' ro ON ro.id = o.roleid');
  $db->exec('INSERT INTO ' . t('holidays') . ' (id, name, description, color, bgcolor, businessday, noabsence, keepweekendcolor, is_system)
    SELECT id, name, description, color, bgcolor, businessday, noabsence, keepweekendcolor, IF(id <= 3, 1, 0) FROM ' . b('holidays'));
  $db->exec('INSERT INTO ' . t('absences') . ' (id, name, symbol, icon, color, bgcolor, bgtrans, factor, allowance, allowmonth, allowweek, show_in_remainder, show_totals, approval_required, counts_as_present, manager_only, hide_in_profile, confidential, takeover)
    SELECT id, name, symbol, icon, color, bgcolor, bgtrans, factor, allowance, allowmonth, allowweek, show_in_remainder, show_totals, approval_required, counts_as_present, manager_only, hide_in_profile, confidential, takeover FROM ' . b('absences'));
  // counts_as references the same table, so it is set once all absences exist. 0 (the old "none") stays NULL.
  $db->exec('UPDATE ' . t('absences') . ' a JOIN ' . b('absences') . ' o ON o.id = a.id JOIN ' . t('absences') . ' tgt ON tgt.id = o.counts_as
    SET a.counts_as = o.counts_as WHERE o.counts_as > 0 AND o.counts_as <> o.id');
  $db->exec('INSERT INTO ' . t('groups') . ' (id, name, description, avatar, minpresent, maxabsent, minpresentwe, maxabsentwe)
    SELECT id, name, description, avatar, minpresent, maxabsent, minpresentwe, maxabsentwe FROM ' . b('groups'));
  $db->exec('INSERT INTO ' . t('absence_group') . ' (id, absence_id, group_id)
    SELECT o.id, o.absid, o.groupid FROM ' . b('absence_group') . ' o ' . latest(b('absence_group'), 'absid, groupid') . '
    JOIN ' . t('absences') . ' a ON a.id = o.absid JOIN ' . t('groups') . ' g ON g.id = o.groupid');
  $db->exec('INSERT INTO ' . t('patterns') . ' (id, name, description) SELECT id, name, COALESCE(description, \'\') FROM ' . b('patterns'));
  $unions = [];
  for ($d = 1; $d <= 7; $d++) {
    $unions[] = "SELECT p.id, $d, p.abs$d FROM " . b('patterns') . " p JOIN " . t('absences') . " a ON a.id = p.abs$d";
  }
  $db->exec('INSERT INTO ' . t('pattern_days') . ' (pattern_id, weekday, absence_id) ' . implode(' UNION ALL ', $unions));
  $db->exec('INSERT INTO ' . t('permissions') . ' (id, scheme, permission, role_id, allowed)
    SELECT o.id, o.scheme, o.permission, o.role, IF(o.allowed <> 0, 1, 0) FROM ' . b('permissions') . ' o ' . latest(b('permissions'), 'scheme, permission, role') . '
    JOIN ' . t('roles') . ' ro ON ro.id = o.role');
  $db->exec('INSERT INTO ' . t('config') . ' (id, name, value) SELECT o.id, o.name, o.value FROM ' . b('config') . ' o ' . latest(b('config'), 'name'));
  // Config keys introduced by 6.0.0 get their default; existing values are never overwritten.
  $seed = $db->prepare('INSERT IGNORE INTO ' . t('config') . ' (name, value) VALUES (?, ?)');
  foreach ($newConfig as $key => $value) {
    $seed->execute([(string) $key, (string) $value]);
  }
  $db->exec('INSERT INTO ' . t('messages') . ' (id, `timestamp`, `text`, `type`) SELECT id, ' . dt('`timestamp`') . ', COALESCE(text, \'\'), COALESCE(type, \'\') FROM ' . b('messages'));
  $db->exec('INSERT INTO ' . t('log') . ' (id, `type`, `timestamp`, ip, `user`, event) SELECT id, `type`, ' . dt('`timestamp`') . ', ip, `user`, event FROM ' . b('log'));

  // --- Users: surrogate ids. Live users first (admin = 1), archived users after them, so no id is ever shared. ---
  $roleIds  = array_flip(array_map('intval', $db->query('SELECT id FROM ' . t('roles'))->fetchAll(PDO::FETCH_COLUMN)));
  $fixDate  = static fn($v): string => ($v === null || $v === '' || str_starts_with((string) $v, '0000')) ? '1970-01-01 00:00:00' : (string) $v;
  $insUser  = static function (string $table, int $id, array $r, bool $archive) use ($db, $fixDate, $roleIds): void {
    $role = (int) ($r['role'] ?? 0);
    if ($archive && !isset($roleIds[$role])) {
      $role = 0;   // archived user whose role was deleted: role_id becomes NULL, as the schema's ON DELETE SET NULL intends
    }
    $stmt = $db->prepare('INSERT INTO ' . t($table) . ' (id, username, password, firstname, lastname, email, order_key, role_id, locked, hidden, onhold, verify, is_system, bad_logins, bad_logins_start, grace_start, last_pw_change, last_login, created, oidc_sub)
      VALUES (:id, :username, :password, :firstname, :lastname, :email, :order_key, :role_id, :locked, :hidden, :onhold, :verify, :is_system, :bad_logins, :bad_logins_start, :grace_start, :last_pw_change, :last_login, :created, :oidc_sub)');
    $stmt->execute([
      ':id'               => $id,
      ':username'         => $r['username'],
      ':password'         => $r['password'],
      ':firstname'        => $r['firstname'],
      ':lastname'         => $r['lastname'],
      ':email'            => $r['email'],
      ':order_key'        => $r['order_key'] ?? '0',
      ':role_id'          => $role > 0 ? $role : ($archive ? null : 2),
      ':locked'           => empty($r['locked']) ? 0 : 1,
      ':hidden'           => empty($r['hidden']) ? 0 : 1,
      ':onhold'           => empty($r['onhold']) ? 0 : 1,
      ':verify'           => empty($r['verify']) ? 0 : 1,
      ':is_system'        => strtolower((string) $r['username']) === 'admin' ? 1 : 0,
      ':bad_logins'       => (int) ($r['bad_logins'] ?? 0),
      ':bad_logins_start' => (int) ($r['bad_logins_start'] ?? 0),
      ':grace_start'      => $fixDate($r['grace_start'] ?? null),
      ':last_pw_change'   => $fixDate($r['last_pw_change'] ?? null),
      ':last_login'       => $fixDate($r['last_login'] ?? null),
      ':created'          => $fixDate($r['created'] ?? null),
      ':oidc_sub'         => ($r['oidc_sub'] ?? '') !== '' ? $r['oidc_sub'] : null,
    ]);
  };
  $nextId = 1;
  $liveId = [];
  foreach ($db->query('SELECT * FROM ' . b('users') . " ORDER BY (LOWER(username) = 'admin') DESC, created, username") as $r) {
    $insUser('users', $nextId, $r, false);
    $liveId[lc((string) $r['username'])] = $nextId++;
  }
  $archId = [];
  foreach ($db->query('SELECT * FROM ' . b('archive_users') . ' ORDER BY created, username') as $r) {
    $insUser('archive_users', $nextId, $r, true);
    $archId[lc((string) $r['username'])] = $nextId++;
  }
  $maxUserId = $nextId - 1;

  $J  = static fn(string $tbl, string $col, string $users): string => "JOIN " . t($users) . " u ON u.username = " . uname("$tbl.$col");
  $LJ = static fn(string $tbl, string $col, string $users): string => "LEFT JOIN " . t($users) . " u ON u.username = " . uname("$tbl.$col");

  // --- Per-user data (live) -----------------------------------------------------------------------
  $db->exec('INSERT INTO ' . t('user_option') . ' (id, user_id, `option`, value)
    SELECT o.id, u.id, o.`option`, o.value FROM ' . b('user_option') . ' o ' . latest(b('user_option'), 'username, `option`') . ' ' . $J('o', 'username', 'users') . ' WHERE o.`option` IS NOT NULL');
  $db->exec('INSERT INTO ' . t('user_group') . ' (id, user_id, group_id, type)
    SELECT o.id, u.id, o.groupid, CASE LOWER(TRIM(COALESCE(o.type, \'\'))) WHEN \'manager\' THEN \'manager\' WHEN \'guest\' THEN \'guest\' ELSE \'member\' END
    FROM ' . b('user_group') . ' o ' . latest(b('user_group'), 'username, groupid') . ' ' . $J('o', 'username', 'users') . ' JOIN ' . t('groups') . ' g ON g.id = o.groupid');
  $db->exec('INSERT INTO ' . t('attachments') . ' (id, filename, uploader_id)
    SELECT o.id, o.filename, u.id FROM ' . b('attachments') . ' o ' . $LJ('o', 'uploader', 'users') . " WHERE o.filename IS NOT NULL AND o.filename <> ''");
  $db->exec('INSERT INTO ' . t('user_attachment') . ' (id, user_id, attachment_id)
    SELECT o.id, u.id, o.fileid FROM ' . b('user_attachment') . ' o ' . latest(b('user_attachment'), 'username, fileid') . ' ' . $J('o', 'username', 'users') . ' JOIN ' . t('attachments') . ' a ON a.id = o.fileid');
  $db->exec('INSERT INTO ' . t('user_message') . ' (id, user_id, message_id, popup)
    SELECT o.id, u.id, o.msgid, IF(o.popup <> 0, 1, 0) FROM ' . b('user_message') . ' o ' . latest(b('user_message'), 'username, msgid') . ' ' . $J('o', 'username', 'users') . ' JOIN ' . t('messages') . ' m ON m.id = o.msgid');
  $db->exec('INSERT INTO ' . t('allowances') . ' (id, user_id, absence_id, carryover, allowance)
    SELECT o.id, u.id, o.absid, COALESCE(o.carryover, 0), COALESCE(o.allowance, 0) FROM ' . b('allowances') . ' o ' . latest(b('allowances'), 'username, absid') . ' ' . $J('o', 'username', 'users') . ' JOIN ' . t('absences') . ' a ON a.id = o.absid');

  // --- Per-user data (archive). No uniqueness constraints exist there, so every row with a valid owner is kept. ---
  $db->exec('INSERT INTO ' . t('archive_user_option') . ' (id, user_id, `option`, value)
    SELECT o.id, u.id, o.`option`, o.value FROM ' . b('archive_user_option') . ' o ' . $J('o', 'username', 'archive_users'));
  $db->exec('INSERT INTO ' . t('archive_user_group') . ' (id, user_id, group_id, type)
    SELECT o.id, u.id, o.groupid, CASE LOWER(TRIM(COALESCE(o.type, \'\'))) WHEN \'manager\' THEN \'manager\' WHEN \'guest\' THEN \'guest\' ELSE \'member\' END
    FROM ' . b('archive_user_group') . ' o ' . $J('o', 'username', 'archive_users') . ' JOIN ' . t('groups') . ' g ON g.id = o.groupid');
  $db->exec('INSERT INTO ' . t('archive_user_attachment') . ' (id, user_id, attachment_id)
    SELECT o.id, u.id, o.fileid FROM ' . b('archive_user_attachment') . ' o ' . $J('o', 'username', 'archive_users') . ' JOIN ' . t('attachments') . ' a ON a.id = o.fileid');
  $db->exec('INSERT INTO ' . t('archive_user_message') . ' (id, user_id, message_id, popup)
    SELECT o.id, u.id, o.msgid, IF(o.popup <> 0, 1, 0) FROM ' . b('archive_user_message') . ' o ' . $J('o', 'username', 'archive_users') . ' JOIN ' . t('messages') . ' m ON m.id = o.msgid');
  $db->exec('INSERT INTO ' . t('archive_allowances') . ' (id, user_id, absence_id, carryover, allowance)
    SELECT o.id, u.id, o.absid, COALESCE(o.carryover, 0), COALESCE(o.allowance, 0) FROM ' . b('archive_allowances') . ' o ' . $J('o', 'username', 'archive_users') . ' JOIN ' . t('absences') . ' a ON a.id = o.absid');

  // --- Day notes (yyyymmdd string -> DATE; 'all' -> NULL user; unknown region -> NULL) ---------------
  $regionIds = array_flip(array_map('intval', $db->query('SELECT id FROM ' . t('regions'))->fetchAll(PDO::FETCH_COLUMN)));
  $dropped   = ['daynotes' => 0, 'archive_daynotes' => 0];
  foreach (['daynotes' => $liveId, 'archive_daynotes' => $archId] as $table => $idMap) {
    $rows = [];
    foreach ($db->query('SELECT * FROM ' . b($table) . ' ORDER BY id') as $r) {
      $ymd = (string) ($r['yyyymmdd'] ?? '');
      if (!preg_match('/^(\d{4})(\d{2})(\d{2})$/', $ymd, $p) || !checkdate((int) $p[2], (int) $p[3], (int) $p[1])) {
        $dropped[$table]++;
        continue;
      }
      $owner = lc((string) $r['username']);
      if ($owner === 'all' && $table === 'daynotes') {
        $uid = null;
      }
      elseif (isset($idMap[$owner])) {
        $uid = $idMap[$owner];
      }
      else {
        $dropped[$table]++;
        continue;
      }
      $rows[] = [(int) $r['id'], "$p[1]-$p[2]-$p[3]", $uid, isset($regionIds[(int) $r['region']]) ? (int) $r['region'] : null, $r['daynote'], $r['color'] ?? 'default', empty($r['confidential']) ? 0 : 1];
      if (count($rows) >= 500) {
        flushRows($db, t($table), 'id, day, user_id, region_id, daynote, color, confidential', $rows);
      }
    }
    flushRows($db, t($table), 'id, day, user_id, region_id, daynote, color, confidential', $rows);
  }

  // --- Absence days: unpivot abs1..abs31 of tcneo_templates / tcneo_archive_templates -------------------
  $absenceIds = array_flip(array_map('intval', $db->query('SELECT id FROM ' . t('absences'))->fetchAll(PDO::FETCH_COLUMN)));
  $stats      = ['templates' => ['cells' => 0, 'bad_date' => 0, 'bad_absence' => 0, 'no_user' => 0], 'archive_templates' => ['cells' => 0, 'bad_date' => 0, 'bad_absence' => 0, 'no_user' => 0]];
  foreach (['templates' => [$liveId, 'absence_days'], 'archive_templates' => [$archId, 'archive_absence_days']] as $src => [$idMap, $dest]) {
    $rows = [];
    $last = 0;
    do {
      $page = $db->query('SELECT * FROM ' . b($src) . " WHERE id > $last ORDER BY id LIMIT 500")->fetchAll();
      foreach ($page as $r) {
        $last = (int) $r['id'];
        $uid  = $idMap[lc((string) $r['username'])] ?? null;
        $y    = (int) $r['year'];
        $mo   = (int) $r['month'];
        if ($uid === null) {
          $stats[$src]['no_user']++;
          continue;
        }
        for ($d = 1; $d <= 31; $d++) {
          $abs = (int) ($r["abs$d"] ?? 0);
          if ($abs <= 0) {
            continue;
          }
          if (!checkdate($mo, $d, $y)) {
            $stats[$src]['bad_date']++;
          }
          elseif (!isset($absenceIds[$abs])) {
            $stats[$src]['bad_absence']++;
          }
          else {
            $rows[] = [$uid, sprintf('%04d-%02d-%02d', $y, $mo, $d), $abs];
            $stats[$src]['cells']++;
          }
        }
        if (count($rows) >= 1000) {
          flushRows($db, t($dest), 'user_id, day, absence_id', $rows);
        }
      }
    } while (count($page) === 500);
    flushRows($db, t($dest), 'user_id, day, absence_id', $rows);
  }

  // --- Calendar days: only real overrides (holiday id > 1) become rows; wday/week are derived, not stored ---
  $holidayIds = array_flip(array_map('intval', $db->query('SELECT id FROM ' . t('holidays'))->fetchAll(PDO::FETCH_COLUMN)));
  $cal        = ['cells' => 0, 'business_day_collapsed' => 0, 'bad_date' => 0, 'bad_ref' => 0];
  $rows       = [];
  $last       = 0;
  do {
    $page = $db->query('SELECT * FROM ' . b('months') . " WHERE id > $last ORDER BY id LIMIT 200")->fetchAll();
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
          $cal['business_day_collapsed']++;   // "Business Day" is the default, so it is not stored as an override
          continue;
        }
        if (!checkdate($mo, $d, $y)) {
          $cal['bad_date']++;
        }
        elseif (!isset($regionIds[$reg]) || !isset($holidayIds[$h])) {
          $cal['bad_ref']++;
        }
        else {
          $rows[] = [$reg, sprintf('%04d-%02d-%02d', $y, $mo, $d), $h];
          $cal['cells']++;
        }
      }
      if (count($rows) >= 1000) {
        flushRows($db, t('calendar_days'), 'region_id, day, holiday_id', $rows);
      }
    }
  } while (count($page) === 200);
  flushRows($db, t('calendar_days'), 'region_id, day, holiday_id', $rows);

  // --- Verification (inside the transaction: any failure rolls everything back) ----------------------------
  say('Verifying ...');
  $newCounts = [];
  foreach (array_keys($newSchema) as $name) {
    $newCounts[$name] = scalar($db, 'SELECT COUNT(*) FROM ' . t($name));
  }
  $mismatch = [];
  foreach (LOSSLESS_TABLES as $name) {
    if ($newCounts[$name] !== $oldCounts[$name]) {
      $mismatch[] = "$name: {$oldCounts[$name]} rows before, {$newCounts[$name]} after";
    }
  }
  if ($newCounts['absence_days'] !== $stats['templates']['cells']) {
    $mismatch[] = "absence_days: expected {$stats['templates']['cells']} rows, found {$newCounts['absence_days']}";
  }
  if ($newCounts['archive_absence_days'] !== $stats['archive_templates']['cells']) {
    $mismatch[] = "archive_absence_days: expected {$stats['archive_templates']['cells']} rows, found {$newCounts['archive_absence_days']}";
  }
  if ($newCounts['calendar_days'] !== $cal['cells']) {
    $mismatch[] = "calendar_days: expected {$cal['cells']} rows, found {$newCounts['calendar_days']}";
  }
  if ($mismatch) {
    throw new RuntimeException("Verification failed:\n  - " . implode("\n  - ", $mismatch));
  }

  $db->commit();

  // New users must never receive an id already used by an archived user.
  $db->exec('ALTER TABLE ' . t('users') . ' AUTO_INCREMENT = ' . ($maxUserId + 1));
}
catch (Throwable $e) {
  fwrite(STDERR, "\nMIGRATION FAILED: " . $e->getMessage() . "\n");
  fwrite(STDERR, "Restoring the original tables ...\n");
  try {
    if ($db->inTransaction()) {
      $db->rollBack();
    }
    $db->exec('SET FOREIGN_KEY_CHECKS = 0');
    foreach (array_reverse(array_keys($created)) as $name) {
      $db->exec('DROP TABLE IF EXISTS ' . t($name));
    }
    foreach (array_keys($renamed) as $name) {
      $db->exec('RENAME TABLE ' . b($name) . ' TO ' . t($name));
    }
    $db->exec('SET FOREIGN_KEY_CHECKS = 1');
    fwrite(STDERR, "The database is back in its 5.3.7 state. Nothing was lost.\n");
  }
  catch (Throwable $e2) {
    fwrite(STDERR, 'AUTOMATIC RESTORE FAILED: ' . $e2->getMessage() . "\n");
    fwrite(STDERR, "Your original data is intact in the {$B}* tables. Restore your backup, or rename them back manually.\n");
  }
  exit(1);
}

//-----------------------------------------------------------------------------
// Report
//-----------------------------------------------------------------------------
say();
say('Migration finished.');
say();
say(sprintf('  %-24s %10s %10s', 'table', '5.3.7 rows', '6.0.0 rows'));
foreach (OLD_TABLES as $name) {
  $to = match ($name) {
    'templates'         => 'absence_days',
    'archive_templates' => 'archive_absence_days',
    'months'            => 'calendar_days',
    default             => $name,
  };
  say(sprintf('  %-24s %10d %10d%s', $name, $oldCounts[$name], $newCounts[$to], $to !== $name ? "   -> $to" : ''));
}
say(sprintf('  %-24s %10s %10d   (from patterns)', 'pattern_days', '-', $newCounts['pattern_days']));
say();
$notes = [];
foreach (['templates' => 'absence days', 'archive_templates' => 'archived absence days'] as $src => $label) {
  if ($stats[$src]['no_user']) {
    $notes[] = "{$stats[$src]['no_user']} monthly {$src} record(s) skipped because their user no longer exists.";
  }
  foreach (['bad_date' => 'on dates that do not exist', 'bad_absence' => 'with an absence type that no longer exists'] as $k => $why) {
    if ($stats[$src][$k]) {
      $notes[] = "{$stats[$src][$k]} $label skipped $why.";
    }
  }
}
foreach (['bad_date' => 'on dates that do not exist', 'bad_ref' => 'for a region or holiday type that no longer exists'] as $k => $why) {
  if ($cal[$k]) {
    $notes[] = "{$cal[$k]} calendar day overrides skipped $why.";
  }
}
if ($cal['business_day_collapsed']) {
  $notes[] = "{$cal['business_day_collapsed']} calendar day overrides set to \"Business Day\" are now the default and are not stored as rows.";
}
foreach ($dropped as $table => $count) {
  if ($count) {
    $notes[] = "$count $table skipped (invalid date or owner no longer exists).";
  }
}
foreach ([
  'user_option', 'user_group', 'user_attachment', 'user_message', 'allowances', 'absence_group', 'region_role', 'permissions',
  'archive_user_option', 'archive_user_group', 'archive_user_attachment', 'archive_user_message', 'archive_allowances', 'attachments', 'config',
] as $name) {
  $diff = $oldCounts[$name] - $newCounts[$name];
  if ($diff > 0) {
    $notes[] = "$diff rows of $name skipped (orphaned or duplicate).";
  }
}
if ($notes) {
  say('Rows that could not be carried over (they referenced data that no longer exists):');
  foreach ($notes as $note) {
    say('  - ' . $note);
  }
  say();
}
say("Your 5.3.7 tables are kept as {$B}*. Verify the application, then remove them with:");
say('  php sql/migrate_5.3.7_to_6.0.0.php --drop-backup --confirm-drop');
exit(0);
