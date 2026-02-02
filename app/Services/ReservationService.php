<?php

namespace App\Services;

use App\Models\Reservation;
use App\Exceptions\NoAvailableTablesException;
use App\Events\ReservationCreated;
use App\Events\ReservationCancelled;
use App\Contracts\ReservationServiceContract;

class ReservationService implements ReservationServiceContract
{
  /**
   * Create a reservation if capacity allows.
   *
   * @param array $data
   * @return Reservation
   *
   * @throws NoAvailableTablesException
   */
  public function create(array $data): Reservation
  {
    if ($this->remainingTables($data['date'], $data['time_from'], $data['time_to']) < 1) {
      throw new NoAvailableTablesException();
    }

    $reservation = Reservation::create($data);
    event(new ReservationCreated($reservation));

    return $reservation;
  }

  /**
   * Get remaining tables for a given time window.
   *
   * @param string $date
   * @param string $timeFrom
   * @param string $timeTo
   * @return int
   */
  public function remainingTables(string $date, string $timeFrom, string $timeTo): int
  {
    $capacity = config('restaurant.tables');

    $overlapping = Reservation::overlapping($date, $timeFrom, $timeTo)->count();

    return max(0, $capacity - $overlapping);
  }

  /**
   * Delete a reservation owned by the authenticated user.
   *
   * @param Reservation $reservation
   * @param int $userId
   * @return void
   */
  public function delete(Reservation $reservation, int $userId): void
  {
    abort_unless($reservation->user_id === $userId, 403);

    event(new ReservationCancelled($reservation));
    $reservation->delete();
  }
}
