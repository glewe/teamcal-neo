<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Cache Class
 *
 * Simple file-based caching.
 *
 * @package TeamCal Neo
 */
class Cache
{
  private string $cacheDir;
  private int    $defaultTtl;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param string $cacheDir   Directory to store cache files
   * @param int    $defaultTtl Default Time To Live in seconds (default: 3600)
   */
  public function __construct(string $cacheDir, int $defaultTtl = 3600) {
    $this->cacheDir   = rtrim($cacheDir, '/\\') . DIRECTORY_SEPARATOR;
    $this->defaultTtl = $defaultTtl;

    if (!is_dir($this->cacheDir)) {
      if (!mkdir($this->cacheDir, 0755, true)) {
        // Fallback or handle error if needed, but for now we assume it works or throws later
      }
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Get a value from the cache.
   *
   * @param string $key Cache key
   *
   * @return mixed The cached value or null if not found/expired
   */
  public function get(string $key): mixed {
    $filename = $this->getFilename($key);

    if (!file_exists($filename)) {
      return null;
    }

    $content = file_get_contents($filename);
    $data    = unserialize($content);

    if ($data['expire'] > 0 && time() > $data['expire']) {
      $this->delete($key);
      return null;
    }

    return $data['value'];
  }

  //---------------------------------------------------------------------------
  /**
   * Set a value in the cache.
   *
   * @param string   $key   Cache key
   * @param mixed    $value Value to cache
   * @param int|null $ttl   Time To Live in seconds (null for default)
   *
   * @return bool True on success
   */
  public function set(string $key, mixed $value, ?int $ttl = null): bool {
    $filename = $this->getFilename($key);
    $ttl      = $ttl ?? $this->defaultTtl;
    $expire   = $ttl > 0 ? time() + $ttl : 0;

    $data = [
      'value'  => $value,
      'expire' => $expire
    ];

    return (bool) file_put_contents($filename, serialize($data));
  }

  //---------------------------------------------------------------------------
  /**
   * Delete a value from the cache.
   *
   * @param string $key Cache key
   *
   * @return bool True on success
   */
  public function delete(string $key): bool {
    $filename = $this->getFilename($key);
    if (file_exists($filename)) {
      return unlink($filename);
    }
    return true;
  }

  //---------------------------------------------------------------------------
  /**
   * Flush the entire cache.
   *
   * @return bool True on success
   */
  public function flush(): bool {
    $files = glob($this->cacheDir . '*.cache');
    foreach ($files as $file) {
      if (is_file($file)) {
        unlink($file);
      }
    }
    return true;
  }

  //---------------------------------------------------------------------------
  /**
   * Get the cache filename for a key.
   *
   * @param string $key Cache key
   *
   * @return string
   */
  private function getFilename(string $key): string {
    return $this->cacheDir . hash('sha256', $key) . '.cache';
  }
}
