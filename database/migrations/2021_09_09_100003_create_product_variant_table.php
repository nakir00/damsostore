<?php

use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Product::class);
            $table->string('name')->nullable();
            $table->integer('min_price')->default(0);
            $table->json('attribute_data');
            $table->boolean('disponibility')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};
