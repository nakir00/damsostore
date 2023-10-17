<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            //$table->foreignId('tax_class_id')->constrained('tax_classes');
            //$table->string('tax_ref')->index()->nullable();
            $table->integer('unit_quantity')->unsigned()->index()->default(1);
            $table->string('sku')->nullable()->index();
            $table->string('length_Value')->nullable()->index();
            $table->string('length_unit')->default('cm')->index();
            $table->string('width_value')->nullable()->index();
            $table->string('width_unit')->default('cm')->index();
            $table->string('heigth_Value')->nullable()->index();
            $table->string('heigth_unit')->default('cm')->index();
            $table->string('weigth_Value')->nullable()->index();
            $table->string('weigth_unit')->default('g')->index();
            $table->string('volume_Value')->nullable()->index();
            $table->string('Volume_unit')->default('cl')->index();
            $table->boolean('shippable')->default(true)->index();
            $table->integer('stock')->default(0)->index();
            $table->integer('backorder')->default(0)->index();
            $table->string('purchasable')->default('always')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}
