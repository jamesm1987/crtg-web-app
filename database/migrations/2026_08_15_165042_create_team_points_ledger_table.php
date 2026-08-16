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
        Schema::create('team_points_ledger', function (Blueprint $table) {

            $table->id();
            $table->foreignId('team_id')->constrained();
            $table->foreignId('competition_id')->constrained();
            $table->foreignId('fixture_id')->nullable()->constrained();
            $table->foreignId('scoring_rule_id')->constrained();
            $table->integer('points');
            $table->string('source')->default('api');
            $table->text('notes')->nullable();
            $table->timestamps();
        
            $table->unique(
                ['team_id', 'fixture_id', 'scoring_rule_id'],
                'team_points_ledger_uniqueness'
            );
        
            $table->index(['competition_id', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_points_ledger');
    }
};
