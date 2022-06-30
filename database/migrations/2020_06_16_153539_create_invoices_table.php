<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('alias', 128)->unique();
            $table->string('email', 128)->index();
            $table->decimal('raw_price', 14, 2)->default(0);
            $table->decimal('inc_price', 14, 2)->default(0);
            $table->string('status', 16)->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id')->index();
            $table->json('title');
            $table->json('description');
            $table->decimal('price', 14, 2)->default(0);
            $table->decimal('gst', 14, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_services');
    }
}
