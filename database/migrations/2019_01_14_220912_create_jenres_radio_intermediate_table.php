<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJenresRadioIntermediateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenres_radio_intermediate', function (Blueprint $table) {
            $table->unsignedInteger('id_station');
            $table->foreign('id_station')->references('id')->on('radio_stations');            
            $table->unsignedTinyInteger('id_jenre');
            $table->foreign('id_jenre')->references('id')->on('jenres');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenres_radio_intermediate');
    }
}
