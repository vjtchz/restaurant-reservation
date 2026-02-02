<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Clear a stored reservation intent.
 */
class ReservationIntentClearController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $request->session()->forget('reservation_intent');

    return redirect()->route('reservations.request');
  }
}
