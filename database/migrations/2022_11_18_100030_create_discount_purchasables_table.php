<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountPurchasablesTable extends Migration
{
    public function up()
    {
        Schema::create('purchasables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained('discounts')->cascadeOnDelete();
            $table->morphs('purchasable');
            $table->string('type')->default('classic')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchasables');
    }
}
