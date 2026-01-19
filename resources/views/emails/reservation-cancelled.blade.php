<p>{{ __('reservations.mail.cancelled.greeting', ['name' => $details['user_name']]) }}</p>
<p>{{ __('reservations.mail.cancelled.intro') }}</p>
<p>
  {{ __('reservations.mail.cancelled.date', ['date' => $details['date']]) }}<br>
  {{ __('reservations.mail.cancelled.time', ['from' => $details['time_from'], 'to' => $details['time_to']]) }}<br>
  {{ __('reservations.mail.cancelled.guests', ['guests' => $details['guests']]) }}
</p>
<p>{{ __('reservations.mail.cancelled.outro') }}</p>
