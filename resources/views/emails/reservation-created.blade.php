<p>{{ __('reservations.mail.created.greeting', ['name' => $user->name]) }}</p>
<p>{{ __('reservations.mail.created.intro') }}</p>
<p>
  {{ __('reservations.mail.created.date', ['date' => $reservation->date->toDateString()]) }}<br>
  {{ __('reservations.mail.created.time', ['from' => $reservation->time_from, 'to' => $reservation->time_to]) }}<br>
  {{ __('reservations.mail.created.guests', ['guests' => $reservation->guests]) }}
</p>
<p>{{ __('reservations.mail.created.outro') }}</p>
