<?php

/**
 * Restaurant configuration.
 *
 * @return array{tables:int,max_guests:int,min_duration:int}
 */
return [
  "tables" => 10, // Total number of tables available in the restaurant
  "max_guests" => 10, // Maximum guests per reservation
  "min_duration" => 60, // Minimum reservation length in minutes
];
