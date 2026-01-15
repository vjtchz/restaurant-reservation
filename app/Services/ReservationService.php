<?php

namespace App\Services;

use App\Models\Reservation;
use App\Exceptions\NoAvailableTablesException;

class ReservationService
{
  public function create(array $data): Reservation
  {
    $capacity = config('restaurant.tables');

    $overlapping = Reservation::overlapping(
      $data['date'],
      $data['time_from'],
      $data['time_to']
    )->count();

    if ($overlapping >= $capacity) {
      throw new NoAvailableTablesException();
    }

    return Reservation::create($data);
  }
}
