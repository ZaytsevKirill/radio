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
            $table->unsignedInteger('station_id');
            $table->foreign('station_id')->references('id')->on('radio_stations');
            $table->unsignedTinyInteger('jenre_id');
            $table->foreign('jenre_id')->references('id')->on('jenres');
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
