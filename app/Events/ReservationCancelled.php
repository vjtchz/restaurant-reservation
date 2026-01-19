<?php

namespace App\Events;

use App\Models\Reservation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReservationCancelled
{
  use Dispatchable, SerializesModels;

  /**
   * @param Reservation $reservation
   */
  public function __construct(public Reservation $reservation)
  {
  }
}
