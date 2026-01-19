<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationAvailabilityController extends Controller
{
  /**
   * Check availability for a proposed reservation time.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function __invoke(Request $request)
  {
    $data = $request->validate([
      'date' => ['required', 'date'],
      'time_from' => ['required', 'date_format:H:i'],
      'time_to' => ['required', 'date_format:H:i', 'after:time_from'],
    ]);

    $capacity = config('restaurant.tables');

    $overlapping = Reservation::overlapping(
      $data['date'],
      $data['time_from'],
      $data['time_to']
    )->count();

    $remaining = max(0, $capacity - $overlapping);

    return response()->json([
      'available' => $remaining > 0,
      'remaining' => $remaining,
      'capacity' => $capacity,
    ]);
  }
}
