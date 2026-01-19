<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationCreatedMail extends Mailable
{
  use Queueable, SerializesModels;

  public function __construct(public Reservation $reservation)
  {
  }

  public function build(): self
  {
    return $this->subject(__('reservations.mail.created.subject'))
      ->view('emails.reservation-created')
      ->with([
        'reservation' => $this->reservation,
        'user' => $this->reservation->user,
      ]);
  }
}
