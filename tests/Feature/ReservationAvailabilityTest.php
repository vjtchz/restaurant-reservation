<?php

use App\Exceptions\NoAvailableTablesException;
use App\Models\Reservation;
use App\Models\User;
use App\Services\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Reservation availability feature tests.
 *
 * Covers capacity checks for overlapping reservations.
 */

uses(RefreshDatabase::class);

it('creates reservation when capacity is available', function () {
  config(['restaurant.tables' => 2]);

  Reservation::factory()->create([
    'date' => '2026-02-01',
    'time_from' => '18:00',
    'time_to' => '20:00',
  ]);

  $user = User::factory()->create();

  $service = app(ReservationService::class);

  $reservation = $service->create([
    'user_id' => $user->id,
    'date' => '2026-02-01',
    'time_from' => '18:30',
    'time_to' => '19:30',
    'guests' => 2,
  ]);

  expect($reservation)->toBeInstanceOf(Reservation::class)
    ->and(Reservation::count())->toBe(2);
});

it('throws when capacity is full for overlapping time slot', function () {
  config(['restaurant.tables' => 1]);

  Reservation::factory()->create([
    'date' => '2026-02-01',
    'time_from' => '18:00',
    'time_to' => '20:00',
  ]);

  $user = User::factory()->create();

  $service = app(ReservationService::class);

  expect(fn() => $service->create([
    'user_id' => $user->id,
    'date' => '2026-02-01',
    'time_from' => '18:30',
    'time_to' => '19:30',
    'guests' => 2,
  ]))->toThrow(NoAvailableTablesException::class);
});
