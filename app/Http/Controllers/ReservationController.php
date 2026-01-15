<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Services\ReservationService;
use App\Http\Requests\StoreReservationRequest;

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
      ->orderBy('date')
      ->orderBy('time_from')
      ->get();

    return Inertia::render('Reservations/Index', [
      'reservations' => $reservations,
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
    $service->create($request->validated());

    return redirect()->back();
  }

  /**
   * Delete a reservation owned by the authenticated user.
   *
   * @param Reservation $reservation
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Reservation $reservation)
  {
    abort_unless($reservation->user_id === auth()->id(), 403);

    $reservation->delete();

    return back()->with('success', 'Reservation deleted.');
  }
}
