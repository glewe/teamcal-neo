<?php
declare(strict_types=1);

namespace App\Core;

use App\Exceptions\ContainerException;

/**
 * Dependency Injection Container
 *
 * Manages class dependencies and instances.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class Container
{
  private array $services  = [];
  private array $instances = [];

  //---------------------------------------------------------------------------
  /**
   * Registers a service factory.
   *
   * @param string   $id      Service identifier
   * @param callable $factory Factory function that returns the service instance
   *
   * @return void
   */
  public function set(string $id, callable $factory): void {
    $this->services[$id] = $factory;
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves a service instance. Creates it if it doesn't exist.
   *
   * @param string $id Service identifier
   *
   * @return mixed Service instance
   * @throws ContainerException If service is not found
   */
  public function get(string $id): mixed {
    if (!isset($this->instances[$id])) {
      if (!isset($this->services[$id])) {
        throw new ContainerException("Service not found: " . $id);
      }
      $this->instances[$id] = $this->services[$id]($this);
    }
    return $this->instances[$id];
  }

  //---------------------------------------------------------------------------
  /**
   * Checks if a service is registered.
   *
   * @param string $id Service identifier
   *
   * @return bool True if service exists, false otherwise
   */
  public function has(string $id): bool {
    return isset($this->services[$id]) || isset($this->instances[$id]);
  }
}
