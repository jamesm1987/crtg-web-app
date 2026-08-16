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
        Schema::table('team_points_ledger', function (Blueprint $table) {
            $table->timestamp('earned_at')->nullable()->after('points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_points_ledger', function (Blueprint $table) {
            $table->dropColumn('earned_at');
        });
    }
};
