<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ReservationRequestController;
use App\Http\Controllers\ReservationIntentController;
use App\Http\Controllers\ReservationIntentClearController;
use App\Http\Controllers\ReservationConfirmController;
use App\Http\Controllers\ReservationConfirmStoreController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\LocaleUpdateController;

Route::get('/', WelcomeController::class)->name('home');
// Guest reservation flow (stores intent before auth).
Route::get('reserve', ReservationRequestController::class)
  ->name('reservations.request');
Route::prefix('reservations')->name('reservations.')->group(function () {
  Route::post('intent', ReservationIntentController::class)
    ->name('intent');
  Route::post('intent/clear', ReservationIntentClearController::class)
    ->name('intent.clear');
});

// Auth-only reservation flow and management.
Route::middleware('auth')->group(function () {
  Route::prefix('reservations')->name('reservations.')->group(function () {
    Route::get('confirm', ReservationConfirmController::class)
      ->name('confirm');
    Route::post('confirm', ReservationConfirmStoreController::class)
      ->name('confirm.store');
  });
  Route::resource('reservations', ReservationController::class)
    ->only(['index', 'store', 'destroy']);
});

// Locale update (guest + auth).
Route::post('locale', LocaleUpdateController::class)->name('locale.update');

// Post-login dashboard shortcut.
Route::get('dashboard', function () {
  return redirect()->route('reservations.index');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
