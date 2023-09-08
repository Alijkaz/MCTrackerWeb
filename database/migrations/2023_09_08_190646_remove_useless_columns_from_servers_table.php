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
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn([
                'latest_version',
                'latest_latency',
                'current_players',
                'max_players'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->string('latest_version')->nullable();
            $table->integer('latest_latency')->nullable();

            $table->integer('current_players')->nullable();
            $table->integer('max_players')->nullable();
        });
    }
};
