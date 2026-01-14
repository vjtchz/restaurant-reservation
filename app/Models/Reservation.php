<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
  protected $fillable = [
    'user_id',
    'date',
    'time_from',
    'time_to',
    'guests',
  ];

  protected $casts = [
    'date' => 'date',
    'guests' => 'integer',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function getTimeRangeAttribute(): string
  {
    return "{$this->time_from}–{$this->time_to}";
  }
}
