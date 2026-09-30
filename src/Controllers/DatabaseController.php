<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\DatabaseStructureModel;
use App\Core\Cache;
use App\Models\LicenseModel;
use App\Services\LegacyImportService;
use PDOException;
use Throwable;

/**
 * Database Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class DatabaseController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    // AJAX endpoints (?method=check / ?method=fix). Routed before the
    // normal HTML flow so failures return JSON, not the alert template.
    if (isset($_GET['method'])) {
      $this->dispatchAjax((string) $_GET['method']);
      return;
    }

    if (!isAllowed($this->CONF['controllers']['database']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    // Check License
    $alertData        = [];
    $showAlert        = false;
    $licExpiryWarning = (int) $this->allConfig['licExpiryWarning'];
    $LIC              = new LicenseModel();
    $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);

    $viewData                = [];
    $viewData['pageHelp']    = $this->allConfig['pageHelp'];
    $viewData['showAlerts']  = $this->allConfig['showAlerts'];
    $viewData['cleanBefore'] = '';

    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];
    $showAlert  = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;
      if (isset($_POST['btn_delete']) && !formInputValid('txt_deleteConfirm', 'required|alpha|equals_string', 'DELETE')) {
        $inputError = true;
      }
      if (isset($_POST['btn_cleanup'])) {
        if (!formInputValid('txt_cleanBefore', 'required|date')) {
          $inputError = true;
        }
        if (!formInputValid('txt_cleanConfirm', 'required|alpha|equals_string', 'CLEANUP')) {
          $inputError = true;
        }
        if (!isValidDate($_POST['txt_cleanBefore'])) {
          $inputError = true;
        }
        $viewData['cleanBefore'] = $_POST['txt_cleanBefore'];
      }

      if (!$inputError) {

        if (isset($_POST['btn_cleanup'])) {
          // ,----------,
          // | Cleanup  |
          // '----------'
          $cleanBeforeDate          = $_POST['txt_cleanBefore'];
          $cleanBeforeDateNoHyphens = str_replace('-', '', $cleanBeforeDate);
          $cleanBeforeYear          = substr($cleanBeforeDate, 0, 4);
          $cleanBeforeMonth         = substr($cleanBeforeDate, 5, 2);

          if (isset($_POST['chk_cleanDaynotes'])) {
            $this->daynoteModel->deleteAllBefore($cleanBeforeDateNoHyphens);
          }
          if (isset($_POST['chk_cleanMonths'])) {
            $this->calendarDayModel->deleteBefore($cleanBeforeYear, $cleanBeforeMonth);
          }
          if (isset($_POST['chk_cleanTemplates'])) {
            $this->absenceDayModel->deleteBefore($cleanBeforeYear, $cleanBeforeMonth);
          }
          if (isset($_POST['chk_daynoteRegions'])) {
            $daynotes = $this->daynoteModel->getAllRegionless();
            foreach ($daynotes as $daynote) {
              $this->daynoteModel->setRegion($daynote['id'], '1');
            }
          }

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['db_alert_cleanup'];
          $alertData['text']    = $this->LANG['db_alert_cleanup_success'];
          $alertData['help']    = '';
        }
        elseif (isset($_POST['btn_delete'])) {
          // ,--------,
          // | Delete |
          // '--------'
          if (isset($_POST['chk_delUsers'])) {
            $this->userModel->deleteAll();
            $this->userOptionModel->deleteAll();
            $this->daynoteModel->deleteAll();
            $this->absenceDayModel->deleteAll();
            $this->allowanceModel->deleteAll();
            $this->logModel->logEvent("logDatabase", $this->userLoggedIn->username, "log_db_delete_users");
          }
          if (isset($_POST['chk_delGroups'])) {
            $this->groupModel->deleteAll();
            $this->userGroupModel->deleteAll();
            $this->logModel->logEvent("logDatabase", $this->userLoggedIn->username, "log_db_delete_groups");
          }
          if (isset($_POST['chk_delMessages'])) {
            $this->messageModel->deleteAll();
            $this->userMessageModel->deleteAll();
            $this->logModel->logEvent("logDatabase", $this->userLoggedIn->username, "log_db_delete_msg");
          }
          if (isset($_POST['chk_delOrphMessages'])) {
            $this->deleteOrphanedMessages();
            $this->logModel->logEvent("logMessage", $this->userLoggedIn->username, "log_db_delete_msg_orph");
          }
          if (isset($_POST['chk_delPermissions'])) {
            $this->permissionModel->deleteAll();
            $this->logModel->logEvent("logDatabase", $this->userLoggedIn->username, "log_db_delete_perm");
          }
          if (isset($_POST['chk_delLog'])) {
            $this->logModel->deleteAll();
            $this->logModel->logEvent("logDatabase", $this->userLoggedIn->username, "log_db_delete_log");
          }
          if (isset($_POST['chkDBDeleteArchive'])) {
            $this->userModel->deleteAll(true);
            $this->userGroupModel->deleteAll(true);
            $this->userOptionModel->deleteAll(true);
            $this->userMessageModel->deleteAll(true);
            $this->logModel->logEvent("logDatabase", $this->userLoggedIn->username, "log_db_delete_archive");
          }

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['db_alert_delete'];
          $alertData['text']    = $this->LANG['db_alert_delete_success'];
          $alertData['help']    = '';
        }
        elseif (isset($_POST['btn_optimize'])) {
          // ,-----------,
          // | Optimize  |
          // '-----------'
          $this->dbModel->optimizeTables();
          $this->logModel->logEvent("logDatabase", $this->userLoggedIn->username, "log_db_optimized");
          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['db_alert_optimize'];
          $alertData['text']    = $this->LANG['db_alert_optimize_success'];
          $alertData['help']    = '';
        }
        elseif (isset($_POST['btn_saveURL'])) {
          // ,----------,
          // | Save URL |
          // '----------'
          if (filter_var($_POST['txt_dbURL'], FILTER_VALIDATE_URL)) {
            $this->configModel->save("dbURL", $_POST['txt_dbURL']);
            $showAlert            = true;
            $alertData['type']    = 'success';
            $alertData['title']   = $this->LANG['alert_success_title'];
            $alertData['subject'] = $this->LANG['db_alert_url'];
            $alertData['text']    = $this->LANG['db_alert_url_success'];
            $alertData['help']    = '';
          }
          else {
            $showAlert            = true;
            $alertData['type']    = 'warning';
            $alertData['title']   = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['db_alert_url'];
            $alertData['text']    = $this->LANG['db_alert_url_fail'];
            $alertData['help']    = '';
            $this->configModel->save("dbURL", "#");
          }
        }
        elseif (isset($_POST['btn_reset']) && $_POST['txt_dbResetString'] == "YesIAmSure") {
          // ,--------,
          // | Reset  |
          // '--------'
          $sqlFile = "sql/basic.sql";
          if (isset($_POST['opt_dataset']) && $_POST['opt_dataset'] === 'sample') {
            $sqlFile = "sql/sample.sql";
          }
          $query = file_get_contents($sqlFile);
          $this->dbModel->db->exec($query);
          $this->logModel->logEvent("logDatabase", $this->userLoggedIn->username, "log_db_reset");
          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['db_alert_reset'];
          $alertData['text']    = $this->LANG['db_alert_reset_success'];
          $alertData['help']    = '';
        }

        if (isset($_SESSION)) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
      }
      else {
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_input'];
        $alertData['text']    = $this->LANG['db_alert_failed'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $viewData['inputAlert']       = $inputAlert;
    $viewData['importInProgress'] = isset($_SESSION['legacy_import']['state']);
    $viewData['dbURL']            = $this->allConfig['dbURL'];
    $viewData['dbInfo']     = $this->dbModel->getAttributes();

    $this->render('database', $viewData);
  }

  /**
   * Deletes all orphaned announcements, meaning those announcements that are
   * not assigned to any user.
   */
  private function deleteOrphanedMessages(): void {
    $messages = $this->messageModel->getAll();
    foreach ($messages as $msg) {
      if (!count($this->userMessageModel->getAllByMsgId($msg['id']))) {
        $this->messageModel->delete($msg['id']);
      }
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Dispatch AJAX requests for the Database Structure repair feature.
   *
   * Verifies admin permission and CSRF token once, then routes to the
   * specific handler. Always returns JSON.
   *
   * @param string $method 'check' or 'fix'
   */
  private function dispatchAjax(string $method): void {
    if (!isAllowed($this->CONF['controllers']['database']->permission)) {
      $this->respondJson(['error' => 'forbidden'], 403);
      return;
    }
    if (!$this->verifyAjaxCsrf()) {
      $this->respondJson(['error' => 'invalid_csrf'], 403);
      return;
    }
    match ($method) {
      'check'         => $this->handleAjaxCheck(),
      'fix'           => $this->handleAjaxFix(),
      'import_check'  => $this->handleImportCheck(),
      'import_start'  => $this->handleImportStart(),
      'import_step'   => $this->handleImportStep(),
      'import_cancel' => $this->handleImportCancel(),
      default         => $this->respondJson(['error' => 'unknown_method'], 400),
    };
  }

  //---------------------------------------------------------------------------
  /**
   * AJAX: run a structure check against the manifest.
   *
   * Returns { findings: [...] }.
   */
  private function handleAjaxCheck(): void {
    try {
      $model = new DatabaseStructureModel($this->dbModel->db, $this->CONF);
      $this->respondJson(['findings' => $model->check()]);
    } catch (\Throwable $e) {
      $this->respondJson(['error' => $e->getMessage()], 500);
    }
  }

  //---------------------------------------------------------------------------
  /**
   * AJAX: apply a list of findings produced by handleAjaxCheck().
   *
   * Expects a JSON body { findings: [...] }. The findings are treated as
   * identifiers only - the model regenerates SQL from the manifest, never
   * from client-supplied data.
   *
   * Returns { results: [...] }.
   */
  private function handleAjaxFix(): void {
    $payload  = json_decode((string) file_get_contents('php://input'), true);
    $findings = is_array($payload['findings'] ?? null) ? $payload['findings'] : [];
    try {
      $model = new DatabaseStructureModel($this->dbModel->db, $this->CONF);
      $this->respondJson(['results' => $model->apply($findings)]);
    } catch (\Throwable $e) {
      $this->respondJson(['error' => $e->getMessage()], 500);
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Creates the import service for the current (6.0.0) database.
   */
  private function createImportService(): LegacyImportService {
    $manifest = require WEBSITE_ROOT . '/sql/basic.manifest.php';
    return new LegacyImportService(
      $this->dbModel->db,
      (string) $this->CONF['db_table_prefix'],
      WEBSITE_ROOT,
      APP_AVATAR_DIR,
      APP_UPL_DIR,
      array_keys($manifest['config'])
    );
  }

  //---------------------------------------------------------------------------
  /**
   * Translates an error code of LegacyImportService::preflight() ("code" or "code|argument").
   */
  private function importError(string $code): string {
    [$key, $arg] = array_pad(explode('|', $code, 2), 2, '');
    $text = $this->LANG['db_import_' . $key] ?? $key;
    return $arg !== '' ? sprintf($text, $arg) : $text;
  }

  //---------------------------------------------------------------------------
  /**
   * AJAX: check whether an old 5.3.7 installation can be imported.
   *
   * Expects a JSON body { folder, host, port, name, user, pass, prefix }. The
   * connection settings are read from the old installation's .env file; fields
   * that are filled in override them. Changes nothing. Remembers the source in
   * the session for import_start.
   */
  private function handleImportCheck(): void {
    unset($_SESSION['legacy_import']);
    $in     = json_decode((string) file_get_contents('php://input'), true);
    $in     = is_array($in) ? $in : [];
    $folder = trim((string) ($in['folder'] ?? ''));
    $srcDir = null;
    $source = [];
    if ($folder !== '') {
      $srcDir = LegacyImportService::resolveFolder($folder, WEBSITE_ROOT);
      if ($srcDir === null) {
        $this->respondJson(['errors' => [$this->LANG['db_import_err_folder']]]);
        return;
      }
      $source = LegacyImportService::readSourceSettings($srcDir);
    }
    foreach (['host', 'port', 'name', 'user', 'pass', 'prefix'] as $key) {
      $value = (string) ($in[$key] ?? '');
      if ($value !== '') {
        $source[$key] = $value;
      }
    }
    $source['prefix'] ??= 'tcneo_';
    if (($source['name'] ?? '') === '' || (($source['host'] ?? '') === '' && ($source['socket'] ?? '') === '')) {
      $this->respondJson(['errors' => [$this->LANG['db_import_err_no_db']]]);
      return;
    }

    try {
      $src = LegacyImportService::connectSource($source);
    }
    catch (PDOException $e) {
      $this->respondJson(['errors' => [sprintf($this->LANG['db_import_err_connect'], $e->getMessage())]]);
      return;
    }
    try {
      $check = $this->createImportService()->preflight($src, $source['prefix']);
    }
    catch (Throwable $e) {
      $this->respondJson(['errors' => [$e->getMessage()]]);
      return;
    }
    if ($check['errors']) {
      $this->respondJson(['errors' => array_map(fn(string $code): string => $this->importError($code), $check['errors'])]);
      return;
    }

    $_SESSION['legacy_import'] = ['source' => $source, 'srcDir' => $srcDir, 'counts' => $check['counts']];
    $warnings                  = $srcDir === null ? [$this->LANG['db_import_warn_no_folder']] : [];
    $this->respondJson(['errors' => [], 'warnings' => $warnings, 'counts' => $check['counts'], 'database' => $source['name'], 'folder' => $srcDir]);
  }

  //---------------------------------------------------------------------------
  /**
   * AJAX: start the import that import_check prepared. Empties this installation's data tables.
   *
   * Expects a JSON body { confirm: "IMPORT" }.
   */
  private function handleImportStart(): void {
    $in  = json_decode((string) file_get_contents('php://input'), true);
    $ses = $_SESSION['legacy_import'] ?? null;
    if (!is_array($ses) || !isset($ses['source'], $ses['counts']) || isset($ses['state'])) {
      $this->respondJson(['error' => $this->LANG['db_import_err_session']], 409);
      return;
    }
    if (!is_array($in) || ($in['confirm'] ?? '') !== 'IMPORT') {
      $this->respondJson(['error' => $this->LANG['db_import_err_confirm']], 400);
      return;
    }
    // The check may be a while ago: re-check so that nothing has changed in the meantime
    try {
      $service = $this->createImportService();
      $check   = $service->preflight(LegacyImportService::connectSource($ses['source']), $ses['source']['prefix']);
    }
    catch (Throwable $e) {
      $this->respondJson(['error' => $e->getMessage()], 500);
      return;
    }
    if ($check['errors']) {
      $this->respondJson(['error' => implode(' ', array_map(fn(string $code): string => $this->importError($code), $check['errors']))], 409);
      return;
    }
    $_SESSION['legacy_import']['state'] = $service->begin($ses['source']['prefix'], $check['counts'], $ses['srcDir']);
    $this->respondJson(['started' => true]);
  }

  //---------------------------------------------------------------------------
  /**
   * AJAX: run the next slice of the import. Called repeatedly until it reports done.
   *
   * If a step fails, the installation is put back to its fresh state, so the import can be started again.
   */
  private function handleImportStep(): void {
    $ses = $_SESSION['legacy_import'] ?? null;
    if (!is_array($ses) || !isset($ses['state'])) {
      $this->respondJson(['error' => $this->LANG['db_import_err_session']], 409);
      return;
    }
    ignore_user_abort(true);
    @set_time_limit(120);
    $max    = (int) ini_get('max_execution_time');
    $budget = $max > 0 ? max(3.0, min(15.0, $max * 0.4)) : 15.0;

    $service = $this->createImportService();
    try {
      $state    = $ses['state'];
      $progress = $service->run(LegacyImportService::connectSource($ses['source']), $state, $budget);
      $_SESSION['legacy_import']['state'] = $state;
    }
    catch (Throwable $e) {
      unset($_SESSION['legacy_import']);
      $message = $e->getMessage();
      try {
        $service->restoreFresh();
      }
      catch (Throwable $restoreError) {
        $message .= ' ' . sprintf($this->LANG['db_import_err_restore'], $restoreError->getMessage());
      }
      $this->respondJson(['error' => $message], 500);
      return;
    }

    if ($progress['done']) {
      unset($_SESSION['legacy_import']);
      (new Cache(WEBSITE_ROOT . '/cache'))->flush();
      $this->logModel->logEvent('logDatabase', $this->userLoggedIn->username, 'log_db_import', $ses['source']['name']);
      $progress['result'] = $this->formatImportResult($progress['result']);
    }
    $this->respondJson($progress);
  }

  //---------------------------------------------------------------------------
  /**
   * AJAX: cancel an import in progress and put this installation back to its fresh state.
   */
  private function handleImportCancel(): void {
    unset($_SESSION['legacy_import']);
    try {
      $this->createImportService()->restoreFresh();
      $this->respondJson(['cancelled' => true]);
    }
    catch (Throwable $e) {
      $this->respondJson(['error' => $e->getMessage()], 500);
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Turns the import report into texts for the page.
   *
   * @param array<string, mixed> $result Result of LegacyImportService::run()
   *
   * @return array<string, mixed> Keys counts, notes, files, settings (texts ready to display)
   */
  private function formatImportResult(array $result): array {
    $notes = [];
    foreach ($result['notes'] as [$key, $count]) {
      $notes[] = $key === 'calendar_business_day_collapsed'
        ? sprintf($this->LANG['db_import_note_business_day'], $count)
        : sprintf($this->LANG['db_import_note_skipped'], $count, $key);
    }

    $files = [];
    $f     = $result['files'];
    if (isset($f['skipped'])) {
      $files[] = $this->LANG['db_import_files_skipped'];
    }
    else {
      foreach (['avatars' => 'db_import_files_avatars', 'uploads' => 'db_import_files_uploads'] as $key => $langKey) {
        $s = $f[$key] ?? [];
        if ($s['missing'] ?? false) {
          $files[] = sprintf($this->LANG['db_import_files_missing'], $this->LANG[$langKey]);
          continue;
        }
        $files[] = sprintf($this->LANG['db_import_files_summary'], $this->LANG[$langKey], $s['copied'] ?? 0, $s['existing'] ?? 0);
        if (!empty($s['unsafe'])) {
          $files[] = sprintf($this->LANG['db_import_files_unsafe'], $s['unsafe']);
        }
        if (!empty($s['failed'])) {
          $files[] = sprintf($this->LANG['db_import_files_failed'], implode(', ', array_slice($s['failed'], 0, 10)));
        }
      }
    }

    $config   = $result['config'];
    $settings = [sprintf($this->LANG['db_import_config_summary'], $config['imported'])];
    if ($config['skipped']) {
      $settings[] = sprintf($this->LANG['db_import_config_skipped'], implode(', ', $config['skipped']));
    }
    if ($config['excluded']) {
      $settings[] = sprintf($this->LANG['db_import_config_excluded'], implode(', ', $config['excluded']));
    }

    return ['counts' => $result['counts'], 'notes' => $notes, 'files' => $files, 'settings' => $settings];
  }

  //---------------------------------------------------------------------------
  /**
   * Verify the CSRF token sent with an AJAX request.
   *
   * Accepts the token via the `X-CSRF-Token` header (for JSON-body POSTs)
   * or the `csrf_token` POST field (form-encoded fallback).
   */
  private function verifyAjaxCsrf(): bool {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['csrf_token'] ?? '');
    $session = $_SESSION['csrf_token'] ?? '';
    return is_string($token) && $token !== '' && hash_equals($session, $token);
  }

  //---------------------------------------------------------------------------
  /**
   * Emit a JSON response and terminate the request.
   *
   * @param array<string, mixed> $data   Response payload
   * @param int                  $status HTTP status code
   */
  private function respondJson(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
  }
}
