<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenres', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 100);
            $table->unsignedTinyInteger('jenre_group_id');
            $table->foreign('jenre_group_id')->references('id')->on('jenres_group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenres');
    }
}
