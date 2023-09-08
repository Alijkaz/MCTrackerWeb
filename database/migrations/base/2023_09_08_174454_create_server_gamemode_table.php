<?php

use App\Models\GameMode;
use App\Models\Server;
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
        Schema::create('server_gamemode', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(GameMode::class, 'gamemode_id')->index()->references('id')->on('gamemodes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Server::class, 'server_id')->index()->references('id')->on('servers')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('server_gamemode');
    }
};
