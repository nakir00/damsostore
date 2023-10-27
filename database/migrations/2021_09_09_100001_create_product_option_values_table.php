<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateProductOptionValuesTable extends Migration
{
    public function up()
    {
        Schema::create('product_option_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_option_id')->constrained('product_options');
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_option_values');
    }
}
