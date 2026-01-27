<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('reservations', function (Blueprint $table) {
      $table->dateTime('end_at')->nullable()->after('time_to');
      $table->index('end_at');
    });

    DB::statement('UPDATE reservations SET end_at = TIMESTAMP(date, time_to) WHERE end_at IS NULL');
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('reservations', function (Blueprint $table) {
      $table->dropIndex(['end_at']);
      $table->dropColumn('end_at');
    });
  }
};
