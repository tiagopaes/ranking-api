<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(false);
            $table->integer('score')->default(0);
            $table->unsignedInteger('ranking_id');
            $table->foreign('ranking_id')->references('id')->on('rankings');
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
        Schema::table('players', function(Blueprint $table)
        {
            $table->dropForeign('players_ranking_id_foreign');
        });
        Schema::dropIfExists('players');
    }
}
