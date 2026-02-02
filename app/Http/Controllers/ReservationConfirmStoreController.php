<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use App\Exceptions\NoAvailableTablesException;
use Illuminate\Http\Request;

/**
 * Create a reservation from a stored intent.
 */
class ReservationConfirmStoreController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request, ReservationService $service)
  {
    $intent = $request->session()->get('reservation_intent');

    if (! $intent) {
      return redirect()->route('reservations.request');
    }

    $data = array_merge($intent, [
      'user_id' => $request->user()->id,
    ]);

    try {
      $service->create($data);
    } catch (NoAvailableTablesException $exception) {
      return back()
        ->withErrors(['time_from' => $exception->getMessage()]);
    }

    $request->session()->forget('reservation_intent');

    return redirect()
      ->route('reservations.index')
      ->with('success', __('reservations.flash.created'));
  }
}
