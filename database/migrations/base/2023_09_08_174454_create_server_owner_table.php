<?php

use App\Models\Server;
use App\Models\User;
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
        Schema::create('server_owner', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'discord_id')->index()->references('discord_id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('server_owner');
    }
};
