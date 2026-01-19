<?php

return [
  'flash' => [
    'created' => 'Rezervace vytvořena.',
    'deleted' => 'Rezervace zrušena.',
  ],
  'errors' => [
    'no_available_tables' => 'Pro zvolený čas nejsou volné stoly.',
  ],
  'validation' => [
    'time_to_after' => 'Čas ukončení musí být po začátku.',
  ],
  'mail' => [
    'created' => [
      'subject' => 'Rezervace vytvořena',
      'greeting' => 'Dobrý den :name,',
      'intro' => 'Vaše rezervace byla vytvořena.',
      'date' => 'Datum: :date',
      'time' => 'Čas: :from - :to',
      'guests' => 'Hosté: :guests',
      'outro' => 'Těšíme se na Vás.',
    ],
    'cancelled' => [
      'subject' => 'Rezervace zrušena',
      'greeting' => 'Dobrý den :name,',
      'intro' => 'Vaše rezervace byla zrušena.',
      'date' => 'Datum: :date',
      'time' => 'Čas: :from - :to',
      'guests' => 'Hosté: :guests',
      'outro' => 'Pokud je to chyba, vytvořte prosím novou rezervaci.',
    ],
  ],
];
