<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;



/**
 * PHP Info Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class PhpInfoController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['phpinfo']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $viewData            = [];
    $viewData['phpInfo'] = $this->getPhpInfoBootstrap();

    $this->render('phpinfo', $viewData);
  }

  /**
   * Reads phpinfo() and parses it into a Bootstrap panel display with enhanced performance, security, and theme support.
   *
   * @param bool $useCache Whether to cache the phpinfo output (default: true for performance)
   * @param string $theme Theme preference: 'auto', 'light', 'dark' (default: 'auto')
   *
   * @return string Bootstrap formatted phpinfo() output with theme support
   */
  private function getPhpInfoBootstrap(bool $useCache = true, string $theme = 'auto'): string {
    // Static cache for performance - separate cache for each theme
    static $cachedOutputs = [];
    $cacheKey = $theme;

    if ($useCache && isset($cachedOutputs[$cacheKey])) {
      return $cachedOutputs[$cacheKey];
    }

    // Validate theme parameter
    if (!in_array($theme, ['auto', 'light', 'dark'], true)) {
      $theme = 'auto';
    }

    // Early check if phpinfo is available
    if (!function_exists('phpinfo')) {
      $errorMsg = '<div class="alert alert-warning"><p>The phpinfo() function is not available or has been disabled. <a href="https://php.net/manual/en/function.phpinfo.php" target="_blank">See the documentation</a>.</p></div>';
      if ($useCache) {
        $cachedOutputs[$cacheKey] = $errorMsg;
      }
      return $errorMsg;
    }

    // Use standard Bootstrap table classes - they handle theming automatically
    $tableClass = 'table table-striped table-bordered table-hover';

    // Capture phpinfo output with error handling
    ob_start();
    try {
      phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES | INFO_ENVIRONMENT);
      $phpinfoHtml = ob_get_clean();
    } catch (\Exception $e) {
      ob_end_clean();
      $errorMsg = '<div class="alert alert-danger"><p>Error executing phpinfo(): ' . htmlspecialchars($e->getMessage()) . '</p></div>';
      if ($useCache) {
        $cachedOutputs[$cacheKey] = $errorMsg;
      }
      return $errorMsg;
    }

    if (empty($phpinfoHtml)) {
      $errorMsg = '<div class="alert alert-warning"><p>phpinfo() returned no output. It may be disabled or restricted.</p></div>';
      if ($useCache) {
        $cachedOutputs[$cacheKey] = $errorMsg;
      }
      return $errorMsg;
    }

    // More efficient regex pattern - compiled once, used once
    $pattern = '#(?:<h2>(?:<a[^>]*>)?(.*?)(?:</a>)?</h2>)|(?:<tr(?:[^>]*)><t[hd](?:[^>]*)>(.*?)\s*</t[hd]>(?:<t[hd](?:[^>]*)>(.*?)\s*</t[hd]>(?:<t[hd](?:[^>]*)>(.*?)\s*</t[hd]>)?)?</tr>)#s';

    // Parse phpinfo HTML into structured data
    $phpinfo        = ['phpinfo' => []];
    $currentSection = 'phpinfo';

    if (preg_match_all($pattern, $phpinfoHtml, $matches, PREG_SET_ORDER)) {
      foreach ($matches as $match) {
        // Section header found
        if (!empty($match[1])) {
          $currentSection           = trim(strip_tags($match[1]));
          $phpinfo[$currentSection] = [];
        }
        // Data row found
        elseif (isset($match[2]) && !empty(trim($match[2]))) {
          $key = trim(strip_tags($match[2]));

          if (isset($match[3]) && !empty(trim($match[3]))) {
            // Key-value pair(s)
            $value1 = trim(strip_tags($match[3]));
            $value2 = isset($match[4]) && !empty(trim($match[4])) ? trim(strip_tags($match[4])) : null;

            $phpinfo[$currentSection][$key] = $value2 !== null ? [$value1, $value2] : $value1;
          }
          else {
            // Single value row
            $phpinfo[$currentSection][] = $key;
          }
        }
      }
    }

    // Build output efficiently using proper HTML table with Bootstrap classes
    $tableSections = [];

    if (count($phpinfo) > 1) { // More than just the initial 'phpinfo' key
      foreach ($phpinfo as $sectionName => $section) {
        if (empty($section))
          continue;

        // Start new section with table
        $sectionHtml = '';

        // Add section header (except for the main phpinfo section)
        if ($sectionName !== 'phpinfo') {
          $sectionHeaderStyle = "background-color: var(--bs-primary, #0d6efd); color: #ffffff; border-bottom: 2px solid var(--bs-border-color, #dee2e6);";

          $sectionHtml .= sprintf(
            "<h5 class='mt-4 mb-3 p-2 rounded' style='%s'>%s</h5>\n",
            $sectionHeaderStyle,
            htmlspecialchars($sectionName)
          );
        }

        // Start table for this section
        $sectionHtml .= sprintf(
          "<table class='%s'>\n<tbody>\n",
          $tableClass
        );

        foreach ($section as $key => $val) {
          if (is_array($val)) {
            // Three-column row for arrays
            $sectionHtml .= sprintf(
              "<tr>\n<td class='fw-bold text-truncate' title='%s'>%s</td>\n<td class='text-break' title='%s'>%s</td>\n<td class='text-break' title='%s'>%s</td>\n</tr>\n",
              htmlspecialchars($key),
              htmlspecialchars((string) $key),
              htmlspecialchars((string) $val[0]),
              htmlspecialchars((string) $val[0]),
              htmlspecialchars((string) $val[1]),
              htmlspecialchars((string) $val[1])
            );
          }
          elseif (is_string($key)) {
            // Two-column row for key-value pairs
            $sectionHtml .= sprintf(
              "<tr>\n<td class='fw-bold text-truncate' title='%s'>%s</td>\n<td class='text-break' title='%s' colspan='2'>%s</td>\n</tr>\n",
              htmlspecialchars($key),
              htmlspecialchars((string) $key),
              htmlspecialchars((string) $val),
              htmlspecialchars((string) $val)
            );
          }
          else {
            // Single column row spanning full width
            $sectionHtml .= sprintf(
              "<tr>\n<td class='text-break' title='%s' colspan='3'>%s</td>\n</tr>\n",
              htmlspecialchars((string) $val),
              htmlspecialchars((string) $val)
            );
          }
        }

        // Close table
        $sectionHtml     .= "</tbody>\n</table>\n";
        $tableSections[]  = $sectionHtml;
      }
    }

    // Wrap output in container - let Bootstrap handle colors
    $output = empty($tableSections)
      ? '<div class="alert alert-info"><p>No phpinfo data could be parsed.</p></div>'
      : sprintf(
        "<div class='phpinfo-container' style='border-radius: 0.375rem; padding: 1rem;'>\n%s</div>",
        implode('', $tableSections)
      );

    // Apply HTML fixes in one pass for better performance
    $htmlFixes = [
      'border="0"' => 'style="border: 0px;"',
      '<font '     => '<span ',
      '</font>'    => '</span>'
    ];

    $output = str_replace(array_keys($htmlFixes), array_values($htmlFixes), $output);

    // Add minimal CSS for layout only - let Bootstrap handle colors
    $css = '
      <style>
        .phpinfo-container .table {
          margin-bottom: 1.5rem;
          font-size: 0.875rem;
        }
        .phpinfo-container .table td {
          vertical-align: middle;
          padding: 0.5rem;
          word-wrap: break-word;
          max-width: 300px;
        }
        .phpinfo-container .table .fw-bold {
          font-weight: 600;
        }
        .phpinfo-container h5 {
          font-size: 1.1rem;
          text-transform: uppercase;
          letter-spacing: 0.5px;
          margin-top: 2rem !important;
        }
        .phpinfo-container h5:first-child {
          margin-top: 0 !important;
        }
        @media (max-width: 768px) {
          .phpinfo-container .table {
            font-size: 0.75rem;
          }
          .phpinfo-container .table td {
            padding: 0.25rem;
            max-width: 150px;
          }
        }
      </style>
    ';

    $output = $css . $output;

    // Cache the result if caching is enabled
    if ($useCache) {
      $cachedOutputs[$cacheKey] = $output;
    }

    return $output;
  }
}
