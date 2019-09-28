<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAircraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aircrafts', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('callsign');
            $table->uuid('type');
            $table->unsignedInteger('load')->comment('in kg');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('type')->references('id')->on('aircraft_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aircrafts');
    }
}
