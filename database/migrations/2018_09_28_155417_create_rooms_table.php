<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('number');
            $table->integer('rm_class_id')->unsigned();
            $table->enum('status', ['Available', 'Occupied', 'Under Maintenance'])
                  ->default('Available');
            $table->timestamps();
        });

        Schema::create('rm_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->text('description');
            $table->double('cost');
            $table->timestamps();
        });

        Schema::create('rm_bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('room_id')->unsigned();
            $table->string('client_name');
            $table->string('client_id');
            $table->double('price');
            $table->dateTime('checkin_time');
            $table->dateTime('checkout_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('rm_classes');
        Schema::dropIfExists('rm_bookings');
    }
}
