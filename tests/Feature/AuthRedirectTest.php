<?php

use App\Models\User;

it('redirects home to reservations', function () {
  $this->get('/')
    ->assertOk()
    ->assertInertia(fn ($page) => $page->component('public/Welcome'));
});

it('redirects guests to login when visiting dashboard', function () {
  $this->get('/dashboard')->assertRedirect('/login');
});

it('redirects authenticated users from dashboard to reservations', function () {
  $user = User::factory()->create();

  $this->actingAs($user)
    ->get('/dashboard')
    ->assertRedirect('/reservations');
});
