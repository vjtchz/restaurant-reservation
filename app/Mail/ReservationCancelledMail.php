<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationCancelledMail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * @param array{user_email:string,user_name:string,date:string,time_from:string,time_to:string,guests:int} $details
   */
  public function __construct(public array $details)
  {
  }

  public function build(): self
  {
    return $this->subject(__('reservations.mail.cancelled.subject'))
      ->view('emails.reservation-cancelled')
      ->with([
        'details' => $this->details,
      ]);
  }
}
