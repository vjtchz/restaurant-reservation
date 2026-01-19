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
  'ui' => [
    'page_title' => 'Reservations',
    'form' => [
      'title' => 'New reservation',
      'description' => 'Pick a date and time range, then confirm the guest count.',
      'labels' => [
        'date' => 'Date',
        'start_time' => 'Start time',
        'end_time' => 'End time',
        'guests' => 'Guests',
      ],
      'min_duration' => 'Minimum duration is :minutes minutes.',
      'guests_hint' => '1-:max guests',
      'availability' => [
        'checking' => 'Checking availability...',
        'available_single' => '1 table left for this slot.',
        'available' => ':count tables left for this slot.',
        'full' => 'No tables available for this slot.',
        'error' => 'Unable to check availability right now.',
      ],
      'summary_empty' => 'Choose a date and time to see your reservation summary.',
      'summary_single' => ':date - :from-:to - :guests guest',
      'summary_plural' => ':date - :from-:to - :guests guests',
      'submit' => 'Create reservation',
    ],
    'list' => [
      'title' => 'My reservations',
      'description' => 'Upcoming bookings and recent reservations.',
      'guests' => ':count guests',
      'delete' => 'Cancel',
      'confirm_cancel' => 'Do you really want to cancel this reservation?',
      'empty' => 'No reservations yet.',
      'prev' => 'Prev',
      'next' => 'Next',
      'page' => 'Page :current of :total',
    ],
  ],
];
