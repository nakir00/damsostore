<?php

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateProductOptionValueProductVariantTable //extends Migration
{
    public function up()
    {
        Schema::create('product_option_value_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(ProductOptionValue::class);
            $table->foreignIdFor(Product::class);
            $table->integer('bonus')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_option_value_product');
    }
}
