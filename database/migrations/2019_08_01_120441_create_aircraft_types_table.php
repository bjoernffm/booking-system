<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAircraftTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aircraft_types', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('manufacturer');
            $table->string('model');
            $table->string('designator');
            $table->string('description');
            $table->string('engine_count');
            $table->string('engine_type');
            $table->string('wake_turbulence_category');
            $table->timestamps();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aircraft_types');
    }
}
