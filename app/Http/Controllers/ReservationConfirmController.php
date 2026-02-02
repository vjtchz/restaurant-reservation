<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

/**
 * Display the confirmation step after authentication.
 */
class ReservationConfirmController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke()
  {
    $intent = session('reservation_intent');

    if (! $intent) {
      return redirect()->route('reservations.request');
    }

    return Inertia::render('reservations/Confirm', [
      'intent' => $intent,
      'maxGuests' => config('restaurant.max_guests', 10),
    ]);
  }
}
