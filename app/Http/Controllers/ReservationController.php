<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Services\ReservationService;
use App\Http\Requests\StoreReservationRequest;
use App\Exceptions\NoAvailableTablesException;

/**
 * Handle reservation CRUD for the authenticated user.
 */
class ReservationController extends Controller
{
  /**
   * Display the authenticated user's reservations.
   *
   * @param Request $request
   * @return \Illuminate\View\View
   */
  public function index(Request $request)
  {
    $reservations = Reservation::query()
      ->where('user_id', $request->user()->id)
      ->where('end_at', '>', now())
      ->orderBy('date')
      ->orderBy('time_from')
      ->paginate(config('system.reservations_per_page', 3))
      ->withQueryString();

    return Inertia::render('reservations/Index', [
      'reservations' => $reservations,
      'maxGuests' => config('restaurant.max_guests', 10),
      'minDuration' => config('restaurant.min_duration', 60),
      'openingHours' => config('restaurant.opening_hours', [
        'from' => '11:00',
        'to' => '22:00',
      ]),
    ]);
  }

  /**
   * Store a newly created reservation.
   *
   * @param StoreReservationRequest $request
   * @param ReservationService $service
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(StoreReservationRequest $request, ReservationService $service)
  {
    try {
      $data = $request->validated();
      $data['user_id'] = $request->user()->id;

      $service->create($data);
    } catch (NoAvailableTablesException $exception) {
      return back()
        ->withErrors(['time_from' => $exception->getMessage()])
        ->withInput();
    }

    return redirect()->back()->with('success', __('reservations.flash.created'));
  }

  /**
   * Delete a reservation owned by the authenticated user.
   *
   * @param Reservation $reservation
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Reservation $reservation, ReservationService $service)
  {
    $service->delete($reservation, auth()->id());

    return back()->with('success', __('reservations.flash.deleted'));
  }
}
