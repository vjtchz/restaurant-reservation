<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use Carbon\Carbon;
use Inertia\Inertia;

/**
 * Render the public reservation request page.
 */
class ReservationRequestController extends Controller
{
  /**
   * Handle the incoming request.
   *
   * @return \Inertia\Response
   */
  public function __invoke(ReservationService $service)
  {
    $now = Carbon::now();
    $date = $now->toDateString();
    $timeFrom = $now->format('H:i');
    $durationMinutes = 120;
    $timeTo = $now->addMinutes($durationMinutes)->format('H:i');

    $remaining = $service->remainingTables($date, $timeFrom, $timeTo);

    return Inertia::render('reservations/Request', [
      'availableTables' => $remaining,
      'openingHours' => config('restaurant.opening_hours', [
        'from' => '11:00',
        'to' => '22:00',
      ]),
      'maxGuests' => config('restaurant.max_guests', 10),
      'minDuration' => config('restaurant.min_duration', 60),
      'intent' => session('reservation_intent'),
    ]);
  }
}
