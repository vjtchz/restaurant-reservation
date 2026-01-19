<?php

/**
 * Exception thrown when no tables are available for a requested time slot.
 */

namespace App\Exceptions;

use RuntimeException;

class NoAvailableTablesException extends RuntimeException
{
  /**
   * Create a new exception instance.
   */
  public function __construct()
  {
    parent::__construct(__('reservations.errors.no_available_tables'));
  }
}
