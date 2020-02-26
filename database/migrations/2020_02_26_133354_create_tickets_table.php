<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('shortcode')->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->enum('type', ['regular', 'discounted']);
            $table->boolean('small_headset');
            $table->double('price', 8, 2);
            $table->uuid('booking_id');
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->foreign('booking_id')->references('id')->on('bookings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
