<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePartySongTable
 */
class CreatePartySongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('party_song', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('party_id');
            $table->unsignedInteger('song_id');
            $table->unsignedInteger('user_id')->nullable();

            $table->foreign('party_id')->references('id')->on('parties');
            $table->foreign('song_id')->references('id')->on('songs');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('party_song');
    }
}
