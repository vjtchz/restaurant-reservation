<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
  use HasFactory;

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

  public function scopeOverlapping($query, string $date, string $from, string $to)
  {
    return $query->whereDate('date', $date)
      ->whereTime('time_from', '<', $to)
      ->whereTime('time_to', '>', $from);
  }
}
