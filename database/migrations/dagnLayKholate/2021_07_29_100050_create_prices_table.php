<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable// extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('customer_group_id')->nullable()->constrained('customer_groups');
            //$table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->morphs('priceable');
            $table->integer('price')->unsigned()->index();
            $table->integer('compare_price')->unsigned()->nullable();
            $table->integer('tier')->default(1)->index();
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
        Schema::dropIfExists('prices');
    }
}
