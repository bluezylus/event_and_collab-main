<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointment_reservations', function (Blueprint $table) {
            // event_id
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_reservations', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }
};
