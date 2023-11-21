<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddDiscountBreakdownToOrdersTable// extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->json('discount_breakdown')->nullable()->after('sub_total');
        });
    }

    public function down()
    {
        Schema::table('orders', function ($table) {
            $table->dropColumn('discount_breakdown');
        });
    }
}
