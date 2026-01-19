<?php

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

it('redirects guest users to login', function () {
  $this->get('/reservations')->assertRedirect('/login');
});

it('shows only authenticated user reservations', function () {
  config(['restaurant.max_guests' => 6]);

  $userA = User::factory()->create();
  $userB = User::factory()->create();

  Reservation::factory()->create([
    'user_id' => $userA->id,
    'date' => '2026-02-01',
    'time_from' => '18:00',
    'time_to' => '20:00',
    'guests' => 2,
  ]);

  Reservation::factory()->create([
    'user_id' => $userB->id,
    'date' => '2026-02-02',
    'time_from' => '18:00',
    'time_to' => '20:00',
    'guests' => 2,
  ]);

  $response = $this->actingAs($userA)->get('/reservations');

  $response->assertOk()
    ->assertInertia(fn (Assert $page) => $page
      ->component('reservations/Index')
      ->where('maxGuests', 6)
      ->has('reservations', 1)
      ->where('reservations.0.user_id', $userA->id)
    );
});

it('stores a reservation', function () {
  $user = User::factory()->create();

  $this->actingAs($user)
    ->post('/reservations', [
      'date' => '2026-02-01',
      'time_from' => '18:00',
      'time_to' => '20:00',
      'guests' => 2,
    ])
    ->assertRedirect(); // typicky back()

  $this->assertDatabaseHas('reservations', [
    'user_id' => $user->id,
    'date' => '2026-02-01 00:00:00',
    'time_from' => '18:00',
    'time_to' => '20:00',
    'guests' => 2,
  ]);
});

it('returns validation error when no tables are available', function () {
  config(['restaurant.tables' => 1]);

  $user = User::factory()->create();

  Reservation::factory()->create([
    'user_id' => $user->id,
    'date' => '2026-02-01',
    'time_from' => '18:00',
    'time_to' => '20:00',
    'guests' => 2,
  ]);

  $this->actingAs($user)
    ->post('/reservations', [
      'date' => '2026-02-01',
      'time_from' => '18:30',
      'time_to' => '19:30',
      'guests' => 2,
    ])
    ->assertSessionHasErrors(['time_from']);

  $this->assertDatabaseCount('reservations', 1);
});

it('validates reservation input', function () {
  $user = User::factory()->create();

  $this->actingAs($user)
    ->post('/reservations', [
      'date' => '2026-02-01',
      'time_from' => '20:00',
      'time_to' => '18:00', // invalid
      'guests' => 2,
    ])
    ->assertSessionHasErrors(['time_to']);
});

it('validates date is not in the past', function () {
  $user = User::factory()->create();

  $this->actingAs($user)
    ->post('/reservations', [
      'date' => now()->subDay()->toDateString(),
      'time_from' => '18:00',
      'time_to' => '20:00',
      'guests' => 2,
    ])
    ->assertSessionHasErrors(['date']);
});

it('validates guests count boundaries', function () {
  config(['restaurant.max_guests' => 4]);

  $user = User::factory()->create();

  $this->actingAs($user)
    ->post('/reservations', [
      'date' => now()->addDay()->toDateString(),
      'time_from' => '18:00',
      'time_to' => '20:00',
      'guests' => 0,
    ])
    ->assertSessionHasErrors(['guests']);

  $this->actingAs($user)
    ->post('/reservations', [
      'date' => now()->addDay()->toDateString(),
      'time_from' => '18:00',
      'time_to' => '20:00',
      'guests' => 5,
    ])
    ->assertSessionHasErrors(['guests']);
});

it('deletes own reservation', function () {
  $user = User::factory()->create();

  $reservation = Reservation::factory()->create([
    'user_id' => $user->id,
    'date' => '2026-02-01',
    'time_from' => '18:00',
    'time_to' => '20:00',
    'guests' => 2,
  ]);

  $this->actingAs($user)
    ->delete("/reservations/{$reservation->id}")
    ->assertRedirect();

  $this->assertDatabaseMissing('reservations', [
    'id' => $reservation->id,
  ]);
});

it('forbids deleting someone elses reservation', function () {
  $owner = User::factory()->create();
  $attacker = User::factory()->create();

  $reservation = Reservation::factory()->create([
    'user_id' => $owner->id,
    'date' => '2026-02-01',
    'time_from' => '18:00',
    'time_to' => '20:00',
    'guests' => 2,
  ]);

  $this->actingAs($attacker)
    ->delete("/reservations/{$reservation->id}")
    ->assertStatus(403);
});

it('returns availability when slots are open', function () {
  config(['restaurant.tables' => 2]);

  $user = User::factory()->create();

  Reservation::factory()->create([
    'date' => '2026-02-01',
    'time_from' => '18:00',
    'time_to' => '20:00',
  ]);

  $this->actingAs($user)
    ->getJson('/api/reservations/availability?date=2026-02-01&time_from=18:30&time_to=19:30')
    ->assertOk()
    ->assertJson([
      'available' => true,
      'remaining' => 1,
      'capacity' => 2,
    ]);
});

it('returns availability when slots are full', function () {
  config(['restaurant.tables' => 1]);

  $user = User::factory()->create();

  Reservation::factory()->create([
    'date' => '2026-02-01',
    'time_from' => '18:00',
    'time_to' => '20:00',
  ]);

  $this->actingAs($user)
    ->getJson('/api/reservations/availability?date=2026-02-01&time_from=18:30&time_to=19:30')
    ->assertOk()
    ->assertJson([
      'available' => false,
      'remaining' => 0,
      'capacity' => 1,
    ]);
});

it('validates availability inputs', function () {
  $user = User::factory()->create();

  $this->actingAs($user)
    ->getJson('/api/reservations/availability?date=2026-02-01&time_from=20:00&time_to=18:00')
    ->assertStatus(422)
    ->assertJsonValidationErrors(['time_to']);
});
