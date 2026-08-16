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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('season');
            $table->unsignedTinyInteger('budget');
            $table->unsignedTinyInteger('entry_fee');
            $table->unsignedTinyInteger('teams_per_league');
            $table->timestamp('transfer_window_open_at')->nullable();
            $table->timestamp('transfer_window_close_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
