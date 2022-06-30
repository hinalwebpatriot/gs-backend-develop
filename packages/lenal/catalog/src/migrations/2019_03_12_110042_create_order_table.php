<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number');
            $table->string('additional_phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('company_name')->nullable();
            $table->string('town_city')->nullable();
            $table->string('zip_postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('first_name_home')->nullable();
            $table->string('last_name_home')->nullable();
            $table->string('phone_number_home')->nullable();
            $table->string('add_phone_number_home')->nullable();
            $table->string('address_home')->nullable();
            $table->string('town_city_home')->nullable();
            $table->string('zip_postal_code_home')->nullable();
            $table->string('country_home')->nullable();
            $table->string('state_home')->nullable();
            $table->string('appartman_number_home')->nullable();
            $table->boolean('billing_address');
            $table->boolean('special_package');
            $table->text('comment')->nullable();
            $table->integer('id_showroom')->unsigned()->index()->nullable();
            $table->foreign('id_showroom')->references('id')->on('show_rooms');
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
        Schema::dropIfExists('order');
    }
}
