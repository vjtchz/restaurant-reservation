<?php

return [
  'flash' => [
    'created' => 'Reservation created.',
    'deleted' => 'Reservation canceled.',
  ],
  'errors' => [
    'no_available_tables' => 'No available tables for the selected time slot.',
  ],
  'validation' => [
    'time_to_after' => 'End time must be after start time.',
    'time_to_min_duration' => 'End time must be at least :minutes minutes after start time.',
    'time_outside_opening_hours' => 'Time must be within opening hours (:from–:to).',
  ],
  'mail' => [
    'created' => [
      'subject' => 'Reservation created',
      'greeting' => 'Hello :name,',
      'intro' => 'Your reservation has been created.',
      'date' => 'Date: :date',
      'time' => 'Time: :from - :to',
      'guests' => 'Guests: :guests',
      'outro' => 'We look forward to seeing you.',
    ],
    'cancelled' => [
      'subject' => 'Reservation cancelled',
      'greeting' => 'Hello :name,',
      'intro' => 'Your reservation has been cancelled.',
      'date' => 'Date: :date',
      'time' => 'Time: :from - :to',
      'guests' => 'Guests: :guests',
      'outro' => 'If this is a mistake, please create a new reservation.',
    ],
  ],
];
