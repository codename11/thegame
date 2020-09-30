<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivots', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("game_id")->nullable();
            $table->foreign("game_id")->references("id")->on("games")->onDelete("cascade")->onUpdate("cascade");

            $table->unsignedBigInteger("army_id")->nullable();
            $table->foreign("army_id")->references("id")->on("armies")->onDelete("cascade")->onUpdate("cascade");
            
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
        Schema::dropIfExists('pivot');
    }
}
