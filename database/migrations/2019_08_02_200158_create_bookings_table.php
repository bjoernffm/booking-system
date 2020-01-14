<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id');
            $table->json('passengers');
            $table->tinyInteger('regular');
            $table->tinyInteger('discounted');
            $table->tinyInteger('small_headsets');
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->text('internal_information')->nullable();
            $table->double('price', 8, 2);
            $table->uuid('slot_id');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('slot_id')->references('id')->on('slots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
