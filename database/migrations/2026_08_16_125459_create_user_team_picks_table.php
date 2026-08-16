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
        Schema::create('user_team_picks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('competition_id')->constrained();
            $table->foreignId('team_id')->constrained();
            $table->timestamp('active_from');
            $table->timestamp('active_to')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'competition_id']);
            $table->index(['team_id', 'active_from', 'active_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_team_picks');
    }
};
