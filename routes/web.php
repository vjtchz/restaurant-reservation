<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ReservationController;

Route::get('/', function () {
  return redirect()->route('reservations.index');
})->name('home');

Route::get('dashboard', function () {
  return redirect()->route('reservations.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::resource('reservations', ReservationController::class)
    ->only(['index', 'store', 'destroy']);
});

require __DIR__ . '/settings.php';
