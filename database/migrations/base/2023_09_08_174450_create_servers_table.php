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

            $table->string('name', 150)->unique();
            $table->text('description')->nullable();

            $table->string('address')->unique();
            $table->string('ip')->nullable();

            $table->string('country_code', 2)->nullable();
            $table->string('region')->nullable();

            $table->text('gamemodes')->nullable();

            $table->string('latest_version')->nullable();
            $table->integer('latest_latency')->nullable();

            $table->integer('current_players')->nullable();
            $table->integer('max_players')->nullable();

            $table->string('votifier_token')->nullable();
            $table->string('votifier_ip')->nullable();
            $table->string('votifier_port')->nullable();

            $table->integer('channel_id');

            $table->string('favicon_path')->nullable();
            $table->string('info_path')->nullable();
            $table->string('motd_path')->nullable();

            $table->boolean('is_vip')->default(false);
            $table->boolean('is_active')->default(true);

            $table->bigInteger('up_from');
            $table->timestamps();
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
