<?php

namespace App\Services;

use App\Models\Reservation;
use App\Exceptions\NoAvailableTablesException;

interface ReservationServiceInterface
{
  /**
   * Create a reservation if capacity allows.
   *
   * @param array $data
   * @return Reservation
   *
   * @throws NoAvailableTablesException
   */
  public function create(array $data): Reservation;

  /**
   * Delete a reservation owned by the authenticated user.
   *
   * @param Reservation $reservation
   * @param int $userId
   * @return void
   */
  public function delete(Reservation $reservation, int $userId): void;
}
