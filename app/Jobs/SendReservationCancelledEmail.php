<?php

namespace App\Jobs;

use App\Mail\ReservationCancelledMail;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReservationCancelledEmail implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public static function dispatchForReservation(Reservation $reservation): void
  {
    $reservation->loadMissing('user');

    $details = [
      'user_email' => $reservation->user->email,
      'user_name' => $reservation->user->name,
      'date' => $reservation->date->toDateString(),
      'time_from' => $reservation->time_from,
      'time_to' => $reservation->time_to,
      'guests' => $reservation->guests,
    ];

    self::dispatch($details);
  }

  /**
   * @param array{user_email:string,user_name:string,date:string,time_from:string,time_to:string,guests:int} $details
   */
  public function __construct(public array $details)
  {
  }

  public function handle(): void
  {
    Mail::to($this->details['user_email'])->send(new ReservationCancelledMail($this->details));
  }
}
