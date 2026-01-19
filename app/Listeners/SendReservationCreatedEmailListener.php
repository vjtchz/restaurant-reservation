<?php

namespace App\Listeners;

use App\Events\ReservationCreated;
use App\Jobs\SendReservationCreatedEmail;

class SendReservationCreatedEmailListener
{
  /**
   * @param ReservationCreated $event
   */
  public function handle(ReservationCreated $event): void
  {
    SendReservationCreatedEmail::dispatch($event->reservation->id);
  }
}
