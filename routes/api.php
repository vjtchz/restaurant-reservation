<?php

use App\Http\Controllers\Api\ReservationAvailabilityController;
use Illuminate\Support\Facades\Route;

Route::get('reservations/availability', ReservationAvailabilityController::class)
  ->name('reservations.availability');
