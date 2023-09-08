<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique('server_name');
            $table->longText('description')->nullable();
            $table->string('address', 150)->unique('server_address');
            $table->string('ip', 150)->nullable();
            $table->string('country_code', 50)->nullable();
            $table->string('region', 100)->nullable();
            $table->longText('favicon_path')->nullable();
            $table->longText('info_path')->nullable();
            $table->longText('motd_path')->nullable();
            $table->longText('gamemodes')->nullable();
            $table->string('latest_version')->nullable();
            $table->integer('latest_latency')->nullable();
            $table->integer('current_players')->nullable();
            $table->integer('max_players')->nullable();
            $table->string('votifier_token')->nullable();
            $table->string('votifier_ip')->nullable();
            $table->string('votifier_port')->nullable();
            $table->bigInteger('up_from');
            $table->boolean('is_vip');
            $table->bigInteger('channel_id');
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
};
