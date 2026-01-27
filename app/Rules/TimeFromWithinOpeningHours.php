<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class TimeFromWithinOpeningHours implements ValidationRule
{
  public function __construct(
    private string $openingFrom,
    private string $openingTo,
  ) {}

  public function validate(string $attribute, mixed $value, \Closure $fail): void
  {
    $time = (string) $value;
    if (strlen($time) !== 5) {
      return;
    }

    if ($time < $this->openingFrom || $time >= $this->openingTo) {
      $fail(__('reservations.validation.time_outside_opening_hours', [
        'from' => $this->openingFrom,
        'to' => $this->openingTo,
      ]));
    }
  }
}
