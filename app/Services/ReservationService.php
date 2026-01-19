<?php

namespace App\Services;

use App\Models\Reservation;
use App\Exceptions\NoAvailableTablesException;
use App\Events\ReservationCreated;
use App\Events\ReservationCancelled;

class ReservationService implements ReservationServiceInterface
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
    $capacity = config('restaurant.tables');

    $overlapping = Reservation::overlapping(
      $data['date'],
      $data['time_from'],
      $data['time_to']
    )->count();

    if ($overlapping >= $capacity) {
      throw new NoAvailableTablesException();
    }

    $reservation = Reservation::create($data);
    event(new ReservationCreated($reservation));

    return $reservation;
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
