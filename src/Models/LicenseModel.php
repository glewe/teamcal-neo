<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use DateTime;
use Exception;

/**
 * LicenseModel
 *
 * This class provides methods and properties for license management.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class LicenseModel
{
  private ?PDO   $db         = null;
  private string $table      = '';
  private bool   $debugCurl  = false;
  private bool   $disableSSL = false;
  public ?object $details    = null;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param PDO|null   $db   Database object
   * @param array|null $conf Configuration array
   */
  public function __construct(?PDO $db = null, ?array $conf = null) {
    global $CONF, $DB;

    $this->db    = $db ?? $DB->db;
    $this->table = $conf['db_table_config'] ?? $CONF['db_table_config'];
    $this->load();
  }

  //---------------------------------------------------------------------------
  /**
   * Activates a license (and registers the domain the request is coming from).
   *
   * @return object|null
   */
  public function activate(): ?object {
    $parms    = [
      'slm_action'        => 'slm_activate',
      'secret_key'        => APP_LIC_KEY,
      'license_key'       => $this->readKey(),
      'registered_domain' => $_SERVER['SERVER_NAME'] ?? 'localhost',
      'item_reference'    => urlencode(APP_LIC_ITM),
    ];
    $response = $this->callAPI('GET', APP_LIC_SRV, $parms);
    return is_string($response) ? json_decode($response) : null;
  }

  //---------------------------------------------------------------------------
  /**
   * API Call.
   *
   * @param string     $method POST, PUT, GET, ...
   * @param string     $url    API host URL
   * @param array|bool $data   URL parameter: array("param" => "value") ==> index.php?param=value
   *
   * @return bool|string
   */
  public function callAPI(string $method, string $url, $data = false): bool|string {
    if (defined('APP_LIC_LOCAL')) {
      return constant('APP_LIC_LOCAL');
    }
    $curl = curl_init();
    switch (strtoupper($method)) {
      case "POST":
        curl_setopt($curl, CURLOPT_POST, true);
        if ($data) {
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        break;

      case "PUT":
        curl_setopt($curl, CURLOPT_PUT, true);
        break;

      default: // Means also 'GET'
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        if ($data && is_array($data)) {
          $url = sprintf("%s?%s", $url, http_build_query($data));
        }
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:43.0) Gecko/20100101 Firefox/43.0');

    if ($this->disableSSL) {
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    }

    $response = curl_exec($curl);

    if ($this->debugCurl) {
      echo '<pre>';
      echo "URL: " . $url . "\n";
      echo "Response: " . var_export($response, true) . "\n";
      echo "cURL Info: " . var_export(curl_getinfo($curl), true) . "\n";
      echo '</pre>';
    }

    curl_close($curl);
    return $response;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks the license against the license server and fills the alert array in
   * case of a problem. The alert array is used on pages to display messages.
   *
   * @param array $alertData        Alert array. Passed by reference
   * @param bool  $showAlert        Flag to show the alert message. Passed by reference
   * @param int   $licExpiryWarning Number of license days left for showing the expiry warning. 0 = no warning.
   * @param array $LANG             The language array. Passed by reference
   */
  public function check(array &$alertData, bool &$showAlert, int $licExpiryWarning, array $LANG): void {
    $parms       = [
      'slm_action'  => 'slm_check',
      'secret_key'  => APP_LIC_KEY,
      'license_key' => $this->readKey(),
    ];
    $response    = $this->callAPI('GET', APP_LIC_SRV, $parms);
    $responseObj = is_string($response) ? json_decode($response) : null;

    if ($responseObj) {
      $this->details = $responseObj;
      switch ($this->status()) {
        case "blocked":
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['lic_blocked'];
          $alertData['subject'] = $LANG['lic_blocked_subject'];
          $alertData['text'] = '';
          $alertData['help'] = $LANG['lic_blocked_help'];
          $showAlert = true;
          break;

        case "expired":
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['lic_expired'];
          $alertData['subject'] = $LANG['lic_expired_subject'];
          $alertData['help'] = $LANG['lic_expired_help'];
          $showAlert = true;
          break;

        case "invalid":
          $alertData['type'] = 'danger';
          $alertData['title'] = $LANG['lic_invalid'];
          $alertData['subject'] = $LANG['lic_invalid_subject'];
          $alertData['text'] = $LANG['lic_invalid_text'];
          $alertData['help'] = $LANG['lic_invalid_help'];
          $showAlert = true;
          break;

        case "pending":
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['lic_pending'];
          $alertData['subject'] = $LANG['lic_pending_subject'];
          $alertData['text'] = '';
          $alertData['help'] = $LANG['lic_pending_help'];
          $showAlert = true;
          break;

        case "unregistered":
          $alertData['type'] = 'warning';
          $alertData['title'] = $LANG['lic_unregistered'];
          $alertData['subject'] = $LANG['lic_unregistered_subject'];
          $alertData['text'] = '';
          $alertData['help'] = $LANG['lic_unregistered_help'];
          $showAlert = true;
          break;
      }

      if ($licExpiryWarning && !$showAlert) {
        $daysToExpiry = $this->daysToExpiry();
        if ($daysToExpiry <= $licExpiryWarning) {
          $alertData['type']    = 'warning';
          $alertData['title']   = $LANG['lic_expiringsoon'];
          $alertData['subject'] = sprintf($LANG['lic_expiringsoon_subject'], $daysToExpiry);
          $alertData['text']    = '';
          $alertData['help']    = $LANG['lic_expiringsoon_help'];
          $showAlert            = true;
        }
      }
    }
    else {
      $alertData['type']    = 'warning';
      $alertData['title']   = $LANG['lic_unavailable'];
      $alertData['subject'] = $LANG['lic_unavailable_subject'];
      $alertData['text']    = $LANG['lic_unavailable_text'];
      $alertData['help']    = $LANG['lic_unavailable_help'];
      $showAlert            = true;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Deactivates a license (deregisters the domain the request is coming from).
   *
   * @return object|null
   */
  public function deactivate(): ?object {
    $parms    = [
      'slm_action'        => 'slm_deactivate',
      'secret_key'        => APP_LIC_KEY,
      'license_key'       => $this->readKey(),
      'registered_domain' => $_SERVER['SERVER_NAME'] ?? 'localhost',
      'item_reference'    => urlencode(APP_LIC_ITM),
    ];
    $response = $this->callAPI('GET', APP_LIC_SRV, $parms);
    return is_string($response) ? json_decode($response) : null;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether the current domain is registered.
   *
   * @return bool
   */
  public function domainRegistered(): bool {
    if (!$this->readKey()) {
      return false;
    }
    if (!empty($this->details->registered_domains)) {
      $serverName = $_SERVER['SERVER_NAME'] ?? 'localhost';
      foreach ($this->details->registered_domains as $domain) {
        if ($domain->registered_domain === $serverName) {
          return true;
        }
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Returns the days until expiry.
   *
   * @return int
   */
  public function daysToExpiry(): int {
    if (!isset($this->details->date_expiry)) {
      return 0;
    }
    try {
      $todayDate    = new DateTime('now');
      $expiryDate   = new DateTime($this->details->date_expiry);
      $daysToExpiry = $todayDate->diff($expiryDate);
      return (int) $daysToExpiry->format('%R%a');
    } catch (Exception $e) {
      return 0;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Loads the license information from license server.
   */
  public function load(): void {
    $parms    = [
      'slm_action'  => 'slm_check',
      'secret_key'  => APP_LIC_KEY,
      'license_key' => $this->readKey(),
    ];
    $response = $this->callAPI('GET', APP_LIC_SRV, $parms);
    if (is_string($response)) {
      $this->details = json_decode($response);
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Read licensekey.
   *
   * @return string Value of the option or false if not found
   */
  public function readKey(): string {
    if (!$this->db) {
      return '';
    }
    $query = $this->db->prepare("SELECT value FROM " . $this->table . " WHERE `name` = 'licKey';");
    if ($query->execute() && $row = $query->fetch()) {
      return (string) $row['value'];
    }
    return '';
  }

  //---------------------------------------------------------------------------
  /**
   * Save licensekey.
   *
   * @param string $value Licenskey
   *
   * @return bool
   */
  public function saveKey(string $value): bool {
    if (!$this->db) {
      return false;
    }
    $query = $this->db->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE `name` = 'licKey'");
    if ($query->execute() && $query->fetchColumn()) {
      $query2 = $this->db->prepare("UPDATE " . $this->table . " SET value = :value WHERE name = 'licKey'");
    }
    else {
      $query2 = $this->db->prepare("INSERT INTO " . $this->table . " (`name`, `value`) VALUES ('licKey', :value)");
    }
    $query2->bindParam(':value', $value);
    return $query2->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a form-group object based on input parameters.
   *
   * @param object|null $data        License information array
   * @param bool        $showDetails Show details
   *
   * @return string HTML
   */
  public function show(?object $data = null, bool $showDetails = false): string {
    global $LANG;

    $alert = [];

    if (!isset($data)) {
      $alert['type']    = 'warning';
      $alert['title']   = $LANG['lic_unavailable'];
      $alert['subject'] = $LANG['lic_unavailable_subject'];
      $alert['text']    = $LANG['lic_unavailable_text'];
      $alert['help']    = $LANG['lic_unavailable_help'];
      $details          = "";
    }
    elseif (isset($data->result) && $data->result === "error") {
      $alert['type']    = 'danger';
      $alert['title']   = $LANG['lic_invalid'];
      $alert['subject'] = $LANG['lic_invalid_subject'];
      $alert['text']    = $LANG['lic_invalid_text'];
      $alert['help']    = $LANG['lic_invalid_help'];
      $details          = "";
    }
    else {
      $domains = "";
      if (!empty($data->registered_domains) && is_array($data->registered_domains)) {
        foreach ($data->registered_domains as $domain) {
          $domains .= $domain->registered_domain . ', ';
        }
        $domains = substr($domains, 0, -2);
      }
      $daysleft = "";
      if ($daysToExpiry = $this->daysToExpiry()) {
        $daysleft = " (" . $daysToExpiry . " " . $LANG['lic_daysleft'] . ")";
      }

      $details  = "<div style=\"height:20px;\"></div>";
      $details .= "
                <table class=\"table table-hover\">
                  <tr><th style=\"background-color: inherit;\">" . $LANG['lic_product'] . ":</th><td style=\"background-color: inherit;\">" . ($data->product_ref ?? '') . "</td></tr>
                  <tr><th style=\"background-color: inherit;\">" . $LANG['lic_key'] . ":</th><td style=\"background-color: inherit;\">" . ($data->license_key ?? '') . "</td></tr>
                  <tr><th style=\"background-color: inherit;\">" . $LANG['lic_name'] . ":</th><td style=\"background-color: inherit;\">" . ($data->first_name ?? '') . " " . ($data->last_name ?? '') . "</td></tr>
                  <tr><th style=\"background-color: inherit;\">" . $LANG['lic_email'] . ":</th><td style=\"background-color: inherit;\">" . ($data->email ?? '') . "</td></tr>
                  <tr><th style=\"background-color: inherit;\">" . $LANG['lic_company'] . ":</th><td style=\"background-color: inherit;\">" . ($data->company_name ?? '') . "</td></tr>
                  <tr><th style=\"background-color: inherit;\">" . $LANG['lic_date_created'] . ":</th><td style=\"background-color: inherit;\">" . ($data->date_created ?? '') . "</td></tr>
                  <tr><th style=\"background-color: inherit;\">" . $LANG['lic_date_renewed'] . ":</th><td style=\"background-color: inherit;\">" . ($data->date_renewed ?? '') . "</td></tr>
                  <tr><th style=\"background-color: inherit;\">" . $LANG['lic_date_expiry'] . ":</th><td style=\"background-color: inherit;\">" . ($data->date_expiry ?? '') . $daysleft . "</td></tr>
                  <tr><th style=\"background-color: inherit;\">" . $LANG['lic_registered_domains'] . ":</th><td style=\"background-color: inherit;\">" . $domains . "</td></tr>
                </table>";

      switch ($this->status()) {
        case "active":
          $title = $LANG['lic_active'];
          $alert['type'] = 'success';
          $alert['title'] = $title . '<span class="btn btn-' . $alert['type'] . ' btn-sm" style="margin-left:16px;">' . ucwords($data->status) . '</span>';
          $alert['subject'] = $LANG['lic_active_subject'];
          $alert['text'] = '';
          $alert['help'] = '';
          break;

        case "expired":
          $title = $LANG['lic_expired'];
          $alert['type'] = 'warning';
          $alert['title'] = $title . '<span class="btn btn-' . $alert['type'] . ' btn-sm" style="margin-left:16px;">' . ucwords($data->status) . '</span>';
          $alert['subject'] = $LANG['lic_expired_subject'];
          $alert['text'] = '';
          $alert['help'] = $LANG['lic_expired_help'];
          break;

        case "blocked":
          $alert['type'] = 'warning';
          $title = $LANG['lic_blocked'];
          $alert['title'] = $title . '<span class="btn btn-' . $alert['type'] . ' btn-sm" style="margin-left:16px;">' . ucwords($data->status) . '</span>';
          $alert['subject'] = $LANG['lic_blocked_subject'];
          $alert['text'] = '';
          $alert['help'] = $LANG['lic_blocked_help'];
          break;

        case "pending":
          $alert['type'] = 'warning';
          $title = $LANG['lic_pending'];
          $alert['title'] = $title . '<span class="btn btn-' . $alert['type'] . ' btn-sm" style="margin-left:16px;">' . ucwords($data->status) . '</span>';
          $alert['subject'] = $LANG['lic_pending_subject'];
          $alert['text'] = '';
          $alert['help'] = $LANG['lic_pending_help'];
          break;

        case "unregistered":
          $title = $LANG['lic_active'];
          $alert['type'] = 'warning';
          $alert['title'] = $title . '<span class="btn btn-' . $alert['type'] . ' btn-sm" style="margin-left:16px;">' . ucwords($data->status) . '</span>';
          $alert['subject'] = $LANG['lic_active_unregistered_subject'];
          $alert['text'] = '';
          $alert['help'] = '';
          break;
      }
    }

    return '
            <div class="alert alert-dismissable alert-' . ($alert['type'] ?? 'info') . '">
              <button type="button" class="btn-close float-end" data-bs-dismiss="alert" title="' . $LANG['close_this_message'] . '"></button>
              <h4><strong>' . ($alert['title'] ?? '') . '</strong></h4>
              <hr>
              <p><strong>' . ($alert['subject'] ?? '') . '</strong></p>
              <p>' . ($alert['text'] ?? '') . '</p>
              ' . (isset($alert['help']) && strlen($alert['help']) ? "<p><i>" . $alert['help'] . "</i></p>" : "") . (($showDetails) ? $details : '') . '
            </div>';
  }

  //---------------------------------------------------------------------------
  /**
   * Get status.
   *
   * @return string  active/blocked/invalid/expired/pending/unregistered
   */
  public function status(): string {
    if (!isset($this->details) || (isset($this->details->result) && $this->details->result === 'error')) {
      return "invalid";
    }

    switch ($this->details->status) {
      case "active":
        if (!$this->domainRegistered()) {
          return 'unregistered';
        }
        return 'active';

      case "expired":
        return 'expired';

      case "blocked":
        return 'blocked';

      case "pending":
        return 'pending';

      default:
        return 'invalid';
    }
  }
}
