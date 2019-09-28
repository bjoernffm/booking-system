<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->uuid('id');
            $table->timestamp('starts_on')->nullable();
            $table->timestamp('ends_on')->nullable();
            $table->uuid('aircraft');
            $table->uuid('pilot');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('aircraft')->references('id')->on('aircrafts');
            $table->foreign('pilot')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slots');
    }
}
