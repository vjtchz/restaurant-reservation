<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationIntentRequest;

/**
 * Store a reservation intent for guests.
 */
class ReservationIntentController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(StoreReservationIntentRequest $request)
  {
    $data = $request->validated();
    $request->session()->put('reservation_intent', $data);
    $request->session()->put('url.intended', route('reservations.confirm'));

    return redirect()->route('reservations.request');
  }
}
