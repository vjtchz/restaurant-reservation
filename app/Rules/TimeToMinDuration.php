<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class TimeToMinDuration implements ValidationRule
{
  public function __construct(
    private int $minDuration,
    private ?string $timeFrom,
  ) {}

  public function validate(string $attribute, mixed $value, \Closure $fail): void
  {
    if (!$this->timeFrom) {
      return;
    }

    $timeFrom = $this->timeFrom;
    $timeTo = (string) $value;

    if (strlen($timeFrom) !== 5 || strlen($timeTo) !== 5) {
      return;
    }

    if ($timeFrom[2] !== ':' || $timeTo[2] !== ':') {
      return;
    }

    $fromMinutes = ((int) substr($timeFrom, 0, 2) * 60) + (int) substr($timeFrom, 3, 2);
    $toMinutes = ((int) substr($timeTo, 0, 2) * 60) + (int) substr($timeTo, 3, 2);

    if (($toMinutes - $fromMinutes) < $this->minDuration) {
      $fail(__('reservations.validation.time_to_min_duration', [
        'minutes' => $this->minDuration,
      ]));
    }
  }
}
