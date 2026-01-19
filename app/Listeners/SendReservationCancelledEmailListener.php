<?php

namespace App\Listeners;

use App\Events\ReservationCancelled;
use App\Jobs\SendReservationCancelledEmail;

class SendReservationCancelledEmailListener
{
  /**
   * @param ReservationCancelled $event
   */
  public function handle(ReservationCancelled $event): void {}
}
