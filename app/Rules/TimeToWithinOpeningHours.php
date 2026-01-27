<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;

class TimeToWithinOpeningHours implements ValidationRule
{
  public function __construct(
    private string $openingFrom,
    private string $openingTo,
  ) {}

  public function validate(string $attribute, mixed $value, \Closure $fail): void
  {
    try {
      $time = Carbon::createFromFormat('H:i', (string) $value);
      $open = Carbon::createFromFormat('H:i', $this->openingFrom);
      $close = Carbon::createFromFormat('H:i', $this->openingTo);
    } catch (\Throwable) {
      return;
    }

    if ($time->lte($open) || $time->gt($close)) {
      $fail(__('reservations.validation.time_outside_opening_hours', [
        'from' => $this->openingFrom,
        'to' => $this->openingTo,
      ]));
    }
  }
}
