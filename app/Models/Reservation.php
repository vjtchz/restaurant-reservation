<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
  use HasFactory;

  /**
   * Mass assignable attributes.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'user_id',
    'date',
    'time_from',
    'time_to',
    'guests',
  ];

  /**
   * Attribute casting rules.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'date' => 'date',
    'guests' => 'integer',
  ];

  /**
   * Computed attributes to append to array/json output.
   *
   * @var array<int, string>
   */
  protected $appends = [
    'time_range',
  ];

  /**
   * Reservation owner.
   *
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Human-friendly time range accessor.
   *
   * @return string
   */
  public function getTimeRangeAttribute(): string
  {
    $from = Carbon::parse($this->time_from)->format('H:i');
    $to = Carbon::parse($this->time_to)->format('H:i');

    return "{$from}–{$to}";
  }

  /**
   * Scope reservations that overlap a given time range.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @param string $date
   * @param string $from
   * @param string $to
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeOverlapping($query, string $date, string $from, string $to)
  {
    return $query->whereDate('date', $date)
      ->whereTime('time_from', '<', $to)
      ->whereTime('time_to', '>', $from);
  }
}
