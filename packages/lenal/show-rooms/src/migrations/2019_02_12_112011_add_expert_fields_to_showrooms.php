<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpertFieldsToShowrooms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('show_rooms', function (Blueprint $table) {
            $table->json('expert_title')->nullable();
            $table->json('expert_text')->nullable();
            $table->json('expert_list_1')->nullable();
            $table->json('expert_list_2')->nullable();
            $table->json('expert_list_3')->nullable();
            $table->json('expert_name')->nullable();
            $table->json('expert_email')->nullable();
            $table->string('expert_photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('show_rooms', function (Blueprint $table) {
            $table->dropColumn('expert_title');
            $table->dropColumn('expert_text');
            $table->dropColumn('expert_list_1');
            $table->dropColumn('expert_list_2');
            $table->dropColumn('expert_list_3');
            $table->dropColumn('expert_name');
            $table->dropColumn('expert_email');
            $table->dropColumn('expert_photo');
        });
    }
}
