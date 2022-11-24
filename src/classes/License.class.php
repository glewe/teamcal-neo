<?php

/**
 * License.class.php
 *
 * @category TeamCal Neo 
 * @version 2.2.3
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed.
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to interface with the license server
 */
class License
{
    private $db = '';
    private $table = '';

    public $details;

    // ---------------------------------------------------------------------
    /**
     * Constructor
     */
    public function __construct()
    {
        global $CONF, $DB;
        $this->db = $DB->db;
        $this->table = $CONF['db_table_config'];
        $this->load();
    }

    // ---------------------------------------------------------------------------
    /**
     * Activates a license (and registers the domain the request is coming from)
     *
     * @return JSON
     */
    function activate()
    {
        $parms = array(
            'slm_action' => 'slm_activate',
            'secret_key' => APP_LIC_KEY,
            'license_key' => $this->readKey(),
            'registered_domain' => $_SERVER['SERVER_NAME'],
            'item_reference' => urlencode(APP_LIC_ITM),
        );

        $response = $this->callAPI('GET', APP_LIC_SRV, $parms);
        $response = json_decode((string)$response);

        return $response;
    }

    // ---------------------------------------------------------------------------
    /**
     * API Call
     * $query = APPL_LIC_SRV . '?slm_action=' . $parms['slm_action'] . '&amp;secret_key=' . $parms['secret_key'] . '&amp;license_key=' . $parms['license_key'] . '&amp;registered_domain=' . $parms['registered_domain'] . '&amp;item_reference=' . $parms['item_reference'];
     *
     * @param string $method  POST, PUT, GET, ...
     * @param string $url     API host URL
     * @param array  $data    URL paramater: array("param" => "value") ==> index.php?param=value
     * @return JSON
     */
    function callAPI($method, $url, $data = false)
    {
        if (defined('APP_LIC_LOCAL')) return APP_LIC_LOCAL;

        $curl = curl_init();

        switch (strtoupper($method)) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    // ---------------------------------------------------------------------------
    /**
     * Checks the license against the license server and fills the alert array in
     * case of a problem. The alert array is used on pages to display messages.
     *
     * @param array &$alertData  Alert array. Passed by reference
     * @param bool  &$showAlert  Flag to show the alert message. Passed by reference
     * @param int   $liceExpiryWarning  Number of license days left for showing the expiry warning. 0 = no warning.
     * @param bool  &$LANG       The language array. Passed by reference
     */
    function check(&$alertData, &$showAlert, $licExpiryWarning, &$LANG)
    {
        $parms = array(
            'slm_action' => 'slm_check',
            'secret_key' => APP_LIC_KEY,
            'license_key' => $this->readKey(),
        );

        $response = $this->callAPI('GET', APP_LIC_SRV, $parms);
        $response = json_decode((string)$response);
        $this->details = $response;

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

        if ($licExpiryWarning) {
            $daysToExpiry = $this->daysToExpiry();
            if ($daysToExpiry <= $licExpiryWarning) {
                $alertData['type'] = 'warning';
                $alertData['title'] = $LANG['lic_expiringsoon'];
                $alertData['subject'] = sprintf($LANG['lic_expiringsoon_subject'], $daysToExpiry);
                $alertData['text'] = '';
                $alertData['help'] = $LANG['lic_expiringsoon_help'];
                $showAlert = true;
            }
        }
    }

    // ---------------------------------------------------------------------------
    /**
     * Deactivates a license (deregisters the domain the request is coming from)
     *
     * @return JSON
     */
    function deactivate()
    {
        $parms = array(
            'slm_action' => 'slm_deactivate',
            'secret_key' => APP_LIC_KEY,
            'license_key' => $this->readKey(),
            'registered_domain' => $_SERVER['SERVER_NAME'],
            'item_reference' => urlencode(APP_LIC_ITM),
        );

        $response = $this->callAPI('GET', APP_LIC_SRV, $parms);
        $response = json_decode((string)$response);

        return $response;
    }

    // ---------------------------------------------------------------------------
    /**
     * Checks whether the current domain is registered
     *
     * @return boolean
     */
    function domainRegistered()
    {
        if (!$this->readKey()) return false;

        if (count($this->details->registered_domains)) {
            foreach ($this->details->registered_domains as $domain) {
                if ($domain->registered_domain == $_SERVER['SERVER_NAME']) return true;
            }
            return false;
        } else {
            return false;
        }
    }

    // ---------------------------------------------------------------------------
    /**
     * Returns the days until expiry
     *
     * @return integer
     */
    function daysToExpiry()
    {
        if (!isset($this->details->date_expiry)) return 0;
        $todayDate = new DateTime('now');
        $expiryDate = new DateTime($this->details->date_expiry);
        $daysToExpiry = $todayDate->diff($expiryDate);
        return intval($daysToExpiry->format('%R%a'));
    }

    // ---------------------------------------------------------------------------
    /**
     * Loads the license information from license server
     *
     * @return JSON
     */
    function load()
    {
        $parms = array(
            'slm_action' => 'slm_check',
            'secret_key' => APP_LIC_KEY,
            'license_key' => $this->readKey(),
        );

        $response = $this->callAPI('GET', APP_LIC_SRV, $parms);
        $response = json_decode((string)$response);
        $this->details = $response;
    }

    // ---------------------------------------------------------------------
    /**
     * Read licensekey
     *
     * @param string $name Name of the option
     * @return string Value of the option or false if not found
     */
    public function readKey()
    {
        $query = $this->db->prepare("SELECT value FROM " . $this->table . " WHERE `name` = 'licKey';");
        $result = $query->execute();

        if ($result and $row = $query->fetch()) {
            return $row['value'];
        } else {
            return '';
        }
    }

    // ---------------------------------------------------------------------
    /**
     * Save licensekey
     *
     * @param string $value  Licenskey
     * @return boolean
     */
    public function saveKey($value)
    {
        $query = $this->db->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE `name` = 'licKey'");
        $result = $query->execute();

        if ($result and $query->fetchColumn()) {
            $query2 = $this->db->prepare("UPDATE " . $this->table . " SET value = :val1 WHERE name = 'licKey'");
        } else {
            $query2 = $this->db->prepare("INSERT INTO " . $this->table . " (`name`, `value`) VALUES ('licKey', :val1)");
        }
        $query2->bindParam('val1', $value);
        $result2 = $query2->execute();
        return $result2;
    }

    // ---------------------------------------------------------------------------
    /**
     * Creates a form-group object based on input parameters
     *
     * @param string $type  Type of information: notfound, invalid, details
     * @param objcet $data   License information array
     * @return string HTML
     */
    function show($data, $showDetails = false)
    {
        global $LANG;

        if (isset($data->result) && $data->result == "error") {
            $alert['type'] = 'danger';
            $alert['title'] = $LANG['lic_invalid'];
            $alert['subject'] = $LANG['lic_invalid_subject'];
            $alert['text'] = $LANG['lic_invalid_text'];
            $alert['help'] = $LANG['lic_invalid_help'];
            $details = "";
        } else {
            $domains = "";
            if (count($data->registered_domains)) {
                foreach ($data->registered_domains as $domain) {
                    $domains .= $domain->registered_domain . ', ';
                }
                $domains = substr($domains, 0, -2); // Remove last comma and blank
            }

            $daysleft = "";
            if ($daysToExpiry = $this->daysToExpiry()) {
                $daysleft = " (" . $daysToExpiry . " " . $LANG['lic_daysleft'] . ")";
            }

            $details = "<div style=\"height:20px;\"></div>";
            $details .= "<table class=\"table table-hover\">
                <tr><th>" . $LANG['lic_product'] . ":</th><td>" . $data->product_ref . "</td></tr>
                <tr><th>" . $LANG['lic_key'] . ":</th><td>" . $data->license_key . "</td></tr>
                <tr><th>" . $LANG['lic_name'] . ":</th><td>" . $data->first_name . " " . $data->last_name . "</td></tr>
                <tr><th>" . $LANG['lic_email'] . ":</th><td>" . $data->email . "</td></tr>
                <tr><th>" . $LANG['lic_company'] . ":</th><td>" . $data->company_name . "</td></tr>
                <tr><th>" . $LANG['lic_date_created'] . ":</th><td>" . $data->date_created . "</td></tr>
                <tr><th>" . $LANG['lic_date_renewed'] . ":</th><td>" . $data->date_renewed . "</td></tr>
                <tr><th>" . $LANG['lic_date_expiry'] . ":</th><td>" . $data->date_expiry . $daysleft . "</td></tr>
                <tr><th>" . $LANG['lic_registered_domains'] . ":</th><td>" . $domains . "</td></tr>
            </table>";

            switch ($this->status()) {

                case "active":
                    $title = $LANG['lic_active'];
                    $alert['type'] = 'success';
                    $alert['title'] = $title . '<span class="btn btn-' . $alert['type'] . ' btn-sm" style="margin-left:16px;">' . proper($data->status) . '</span>';
                    $alert['subject'] = $LANG['lic_active_subject'];
                    $alert['text'] = '';
                    $alert['help'] = '';
                    break;

                case "expired":
                    $title = $LANG['lic_expired'];
                    $alert['type'] = 'warning';
                    $alert['title'] = $title . '<span class="btn btn-' . $alert['type'] . ' btn-sm" style="margin-left:16px;">' . proper($data->status) . '</span>';
                    $alert['subject'] = $LANG['lic_expired_subject'];
                    $alert['text'] = '';
                    $alert['help'] = $LANG['lic_expired_help'];
                    break;

                case "blocked":
                    $alert['type'] = 'warning';
                    $title = $LANG['lic_blocked'];
                    $alert['title'] = $title . '<span class="btn btn-' . $alert['type'] . ' btn-sm" style="margin-left:16px;">' . proper($data->status) . '</span>';
                    $alert['subject'] = $LANG['lic_blocked_subject'];
                    $alert['text'] = '';
                    $alert['help'] = $LANG['lic_blocked_help'];
                    break;

                case "pending":
                    $alert['type'] = 'warning';
                    $title = $LANG['lic_pending'];
                    $alert['title'] = $title . '<span class="btn btn-' . $alert['type'] . ' btn-sm" style="margin-left:16px;">' . proper($data->status) . '</span>';
                    $alert['subject'] = $LANG['lic_pending_subject'];
                    $alert['text'] = '';
                    $alert['help'] = $LANG['lic_pending_help'];
                    break;

                case "unregistered":
                    $title = $LANG['lic_active'];
                    $alert['type'] = 'warning';
                    $alert['title'] = $title . '<span class="btn btn-' . $alert['type'] . ' btn-sm" style="margin-left:16px;">' . proper($data->status) . '</span>';
                    $alert['subject'] = $LANG['lic_active_unregistered_subject'];
                    $alert['text'] = '';
                    $alert['help'] = '';
                    break;
            }
        }

        $alertBox = '
        <div class="alert alert-dismissable alert-' . $alert['type'] . '">
            <button type="button" class="close" data-bs-dismiss="alert" title="' . $LANG['close_this_message'] . '"><i class="far fa-times-circle"></i></button>
            <h4><strong>' . $alert['title'] . '</strong></h4>
            <hr>
            <p><strong>' . $alert['subject'] . '</strong></p>
            <p>' . $alert['text'] . '</p>
            ' . (strlen($alert['help']) ? "<p><i>" . $alert['help'] . "</i></p>" : "") . (($showDetails) ? $details : '') . '
        </div>';

        return $alertBox;
    }

    // ---------------------------------------------------------------------------
    /**
     * Get status
     *
     * @return string  active/blocked/invalid/expired/pending/unregistered
     */
    function status()
    {
        if (!isset($this->details) || $this->details->result == 'error') return "invalid";

        switch ($this->details->status) {
            case "active":
                if (!$this->domainRegistered()) return 'unregistered';
                return 'active';
                break;

            case "expired":
                return 'expired';
                break;

            case "blocked":
                return 'blocked';
                break;

            case "pending":
                return 'pending';
                break;
        }
    }
}
