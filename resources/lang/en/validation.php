<?php

return [
  'required' => 'The :attribute field is required.',
  'date' => 'The :attribute is not a valid date.',
  'date_format' => 'The :attribute does not match the format :format.',
  'after' => 'The :attribute must be a date after :date.',
  'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
  'integer' => 'The :attribute must be an integer.',
  'min' => [
    'numeric' => 'The :attribute must be at least :min.',
  ],
  'max' => [
    'numeric' => 'The :attribute may not be greater than :max.',
  ],
  'attributes' => [
    'date' => 'date',
    'time_from' => 'start time',
    'time_to' => 'end time',
    'guests' => 'guests',
  ],
];
