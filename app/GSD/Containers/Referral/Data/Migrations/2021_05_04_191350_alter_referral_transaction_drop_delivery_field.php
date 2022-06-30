<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterReferralTransactionDropDeliveryField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_transactions', function (Blueprint $table) {
            $table->dropColumn('delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referral_transactions', function (Blueprint $table) {
            $table->timestamp('delivered_at')->nullable();
        });
    }
}
