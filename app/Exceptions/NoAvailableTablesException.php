<?php

namespace App\Exceptions;

use RuntimeException;

class NoAvailableTablesException extends RuntimeException
{
  public function __construct()
  {
    parent::__construct('No available tables for the selected time slot.');
  }
}
