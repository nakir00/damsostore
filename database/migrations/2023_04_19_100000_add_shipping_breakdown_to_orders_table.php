<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddShippingBreakdownToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->json('shipping_breakdown')->nullable()->after('discount_total');
        });
    }

    public function down()
    {
        Schema::table('orders', function ($table) {
            $table->dropColumn('shipping_breakdown');
        });
    }
}
