<?php

namespace App\Http\Requests;

class StoreReservationIntentRequest extends StoreReservationRequest
{
  /**
   * Allow guests to start a reservation intent.
   */
  public function authorize(): bool
  {
    return true;
  }
}
