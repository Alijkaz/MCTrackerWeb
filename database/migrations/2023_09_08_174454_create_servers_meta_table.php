<?php

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
        Schema::create('servers_meta', function (Blueprint $table) {
            $table->id();

            $table->string('key');
            $table->string('value');
            $table->foreignIdFor(Server::class, 'server_id')->index()->references('id')->on('servers')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unique(['key', 'server_id']);

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
        Schema::dropIfExists('servers_meta');
    }
};
