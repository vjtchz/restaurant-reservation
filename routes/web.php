<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Laravel\Fortify\Features;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ReservationController;

Route::get('/', WelcomeController::class)->name('home');

Route::post('locale', function (Request $request) {
  $locales = config('app.locales', [config('app.locale')]);
  $locale = $request->string('locale')->toString();

  if (! in_array($locale, $locales, true)) {
    abort(404);
  }

  $request->session()->put('locale', $locale);

  return back();
})->name('locale.update');

Route::get('dashboard', function () {
  return redirect()->route('reservations.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::resource('reservations', ReservationController::class)
    ->only(['index', 'store', 'destroy']);
});

require __DIR__ . '/settings.php';
