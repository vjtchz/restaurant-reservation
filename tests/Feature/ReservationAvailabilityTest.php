<?php

use App\Exceptions\NoAvailableTablesException;
use App\Jobs\SendReservationCancelledEmail;
use App\Jobs\SendReservationCreatedEmail;
use App\Models\Reservation;
use App\Models\User;
use App\Services\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

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

it('does not count reservations on other dates toward capacity', function () {
  config(['restaurant.tables' => 1]);

  Reservation::factory()->create([
    'date' => '2026-02-01',
    'time_from' => '18:00',
    'time_to' => '20:00',
  ]);

  $user = User::factory()->create();

  $service = app(ReservationService::class);

  $reservation = $service->create([
    'user_id' => $user->id,
    'date' => '2026-02-02',
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

it('allows reservation that starts when another ends', function () {
  config(['restaurant.tables' => 1]);

  Reservation::factory()->create([
    'date' => '2026-02-01',
    'time_from' => '18:00:00',
    'time_to' => '20:00:00',
  ]);

  $user = User::factory()->create();

  $service = app(ReservationService::class);

  $reservation = $service->create([
    'user_id' => $user->id,
    'date' => '2026-02-01',
    'time_from' => '20:00:00',
    'time_to' => '21:00:00',
    'guests' => 2,
  ]);

  expect($reservation)->toBeInstanceOf(Reservation::class)
    ->and(Reservation::count())->toBe(2);
});

it('dispatches a create email job when reservation is created', function () {
  Queue::fake();
  config(['restaurant.tables' => 1]);

  $user = User::factory()->create();

  $service = app(ReservationService::class);

  $reservation = $service->create([
    'user_id' => $user->id,
    'date' => '2026-02-01',
    'time_from' => '18:00',
    'time_to' => '20:00',
    'guests' => 2,
  ]);

  Queue::assertPushed(SendReservationCreatedEmail::class, function ($job) use ($reservation) {
    return $job->reservationId === $reservation->id;
  });
});

it('dispatches a cancellation email job when reservation is deleted', function () {
  Queue::fake();

  $user = User::factory()->create();

  $reservation = Reservation::factory()->create([
    'user_id' => $user->id,
    'date' => '2026-02-01',
    'time_from' => '18:00',
    'time_to' => '20:00',
    'guests' => 2,
  ]);

  $service = app(ReservationService::class);

  $service->delete($reservation, $user->id);

  Queue::assertPushed(SendReservationCancelledEmail::class, function ($job) use ($reservation, $user) {
    return $job->details['user_email'] === $user->email
      && $job->details['user_name'] === $user->name
      && $job->details['date'] === $reservation->date->toDateString()
      && $job->details['time_from'] === $reservation->time_from
      && $job->details['time_to'] === $reservation->time_to
      && $job->details['guests'] === $reservation->guests;
  });

  $this->assertDatabaseMissing('reservations', [
    'id' => $reservation->id,
  ]);
});
