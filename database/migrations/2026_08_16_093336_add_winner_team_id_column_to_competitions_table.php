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
        Schema::table('competitions', function (Blueprint $table) {
            $table->foreignId('winner_team_id')->nullable()->constrained('teams')->after('track_scorers');
            $table->foreignId('trophy_scoring_rule_id')->nullable()->constrained('scoring_rules')->after('winner_team_id');
            $table->timestamp('concluded_at')->nullable()->after('trophy_scoring_rule_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            Schema::dropIfExists('winner_team_id');
            Schema::dropIfExists('trophy_scoring_rule_id');
            Schema::dropIfExists('concluded_at');
        });
    }
};
