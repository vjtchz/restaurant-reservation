<?php

namespace App\Jobs;

use App\Mail\ReservationCreatedMail;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReservationCreatedEmail implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public function __construct(public int $reservationId)
  {
  }

  public function handle(): void
  {
    $reservation = Reservation::with('user')->find($this->reservationId);

    if (!$reservation || !$reservation->user) {
      return;
    }

    Mail::to($reservation->user->email)->send(new ReservationCreatedMail($reservation));
  }
}
