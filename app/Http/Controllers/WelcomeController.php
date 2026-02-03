<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use Carbon\Carbon;
use Inertia\Inertia;

/**
 * Render the public home page with availability info.
 */
class WelcomeController extends Controller
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

    $openingHours = config('restaurant.opening_hours', [
      'from' => '11:00',
      'to' => '22:00',
    ]);

    return Inertia::render('public/Welcome', [
      'availableTables' => $remaining,
      'openingHours' => $openingHours,
    ]);
  }
}
