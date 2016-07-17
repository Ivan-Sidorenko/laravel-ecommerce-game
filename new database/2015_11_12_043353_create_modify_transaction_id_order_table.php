<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModifyTransactionIdOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function($t) {
            $t->string('transaction_id');
            $t->integer('billing_id');
			$t->integer('active')->nullable()->default(0);
			$t->integer('previous_status')->nullable()->default(0);
			 $t->string('security_key')->nullable();
            $t->string('tx_auth_no')->nullable();
            $t->string('vps_tx_id')->nullable();
			$t->integer('billing_address_id')->nullable();
			$t->integer('shipping_address_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function($t) {
            $t->dropColumn('transaction_id');
            $t->dropColumn('billing_id');
			$t->dropColumn('active');
			$t->dropColumn('previous_status');
			$t->dropColumn('security_key');
            $t->dropColumn('tx_auth_no');
            $t->dropColumn('vps_tx_id');
			$t->dropColumn('billing_address_id');
			$t->dropColumn('shipping_address_id');
        });
    }
}
