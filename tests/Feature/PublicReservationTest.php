<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

it('shows the public welcome page', function () {
  $this->get('/')
    ->assertOk()
    ->assertInertia(fn (Assert $page) => $page->component('public/Welcome'));
});

it('shows the public reservation request page', function () {
  $this->get('/reserve')
    ->assertOk()
    ->assertInertia(fn (Assert $page) => $page->component('public/Reservations/Request'));
});

it('stores a reservation intent for guests', function () {
  $date = now()->addDay()->toDateString();

  $this->post('/reservations/intent', [
    'date' => $date,
    'time_from' => '18:00',
    'time_to' => '19:30',
    'guests' => 2,
  ])
    ->assertRedirect('/reserve')
    ->assertSessionHas('reservation_intent', [
      'date' => $date,
      'time_from' => '18:00',
      'time_to' => '19:30',
      'guests' => 2,
    ]);
});
