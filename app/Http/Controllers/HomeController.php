<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Inertia\Inertia;

/**
 * Render the public home page with availability info.
 */
class HomeController extends Controller
{
  /**
   * Handle the incoming request.
   *
   * @return \Inertia\Response
   */
  public function __invoke()
  {
    $capacity = config('restaurant.tables');
    $now = Carbon::now();
    $date = $now->toDateString();
    $timeFrom = $now->format('H:i');
    $durationMinutes = 120;
    $timeTo = $now->copy()->addMinutes($durationMinutes)->format('H:i');

    $overlapping = Reservation::overlapping($date, $timeFrom, $timeTo)->count();
    $remaining = max(0, $capacity - $overlapping);

    $openingHours = config('restaurant.opening_hours', [
      'from' => '11:00',
      'to' => '22:00',
    ]);

    return Inertia::render('Welcome', [
      'availableTables' => $remaining,
      'openingHours' => $openingHours,
    ]);
  }
}
