<?php

return [
  'required' => 'Pole :attribute je povinné.',
  'date' => 'Pole :attribute není platné datum.',
  'date_format' => 'Pole :attribute neodpovídá formátu :format.',
  'after' => 'Pole :attribute musí být datum po :date.',
  'after_or_equal' => 'Pole :attribute musí být datum po nebo rovno :date.',
  'integer' => 'Pole :attribute musí být celé číslo.',
  'min' => [
    'numeric' => 'Pole :attribute musí být alespoň :min.',
  ],
  'max' => [
    'numeric' => 'Pole :attribute nesmí být větší než :max.',
  ],
  'attributes' => [
    'date' => 'datum',
    'time_from' => 'čas začátku',
    'time_to' => 'čas konce',
    'guests' => 'počet hostů',
  ],
];
