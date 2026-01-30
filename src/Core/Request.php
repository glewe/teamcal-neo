<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Request Class
 *
 * Handles HTTP requests and input sanitization.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class Request
{
  private array $get;
  private array $post;
  private array $request;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   */
  public function __construct() {
    $this->get     = $_GET;
    $this->post    = $_POST;
    $this->request = $_REQUEST;
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves a value from $_GET.
   *
   * @param string $key     Key to retrieve
   * @param mixed  $default Default value if key not found
   *
   * @return mixed Value or default
   */
  public function get(string $key, mixed $default = null): mixed {
    return $this->get[$key] ?? $default;
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves a value from $_POST.
   *
   * @param string $key     Key to retrieve
   * @param mixed  $default Default value if key not found
   *
   * @return mixed Value or default
   */
  public function post(string $key, mixed $default = null): mixed {
    return $this->post[$key] ?? $default;
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves a value from $_REQUEST.
   *
   * @param string $key     Key to retrieve
   * @param mixed  $default Default value if key not found
   *
   * @return mixed Value or default
   */
  public function request(string $key, mixed $default = null): mixed {
    return $this->request[$key] ?? $default;
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves an integer value from $_GET.
   *
   * @param string $key     Key to retrieve
   * @param int    $default Default value if key not found or not numeric
   *
   * @return int Value or default
   */
  public function getInt(string $key, int $default = 0): int {
    $val = $this->get($key);
    return is_numeric($val) ? (int) $val : $default;
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves an integer value from $_POST.
   *
   * @param string $key     Key to retrieve
   * @param int    $default Default value if key not found or not numeric
   *
   * @return int Value or default
   */
  public function postInt(string $key, int $default = 0): int {
    $val = $this->post($key);
    return is_numeric($val) ? (int) $val : $default;
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves an alphanumeric string from $_GET.
   *
   * @param string $key     Key to retrieve
   * @param string $default Default value if key not found
   *
   * @return string Value or default
   */
  public function getAlpha(string $key, string $default = ''): string {
    $val = $this->get($key, $default);
    return preg_replace('/[^a-zA-Z0-9]/', '', (string) $val);
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves an alphanumeric string from $_POST.
   *
   * @param string $key     Key to retrieve
   * @param string $default Default value if key not found
   *
   * @return string Value or default
   */
  public function postAlpha(string $key, string $default = ''): string {
    $val = $this->post($key, $default);
    return preg_replace('/[^a-zA-Z0-9]/', '', (string) $val);
  }
}
