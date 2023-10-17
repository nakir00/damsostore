<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateProductOptionValueProductVariantTable extends Migration
{
    public function up()
    {
        Schema::create('product_option_value_product_variant', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('value_id')->constrained('product_option_values');
            $table->foreignId('variant_id')->constrained('product_variants');
            $table->integer('old_price')->default(0);
            $table->integer('price')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_option_value_product_variant');
    }
}
