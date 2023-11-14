<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateProductAssociationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_associations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_parent_id')->constrained('products');
            $table->foreignId('product_target_id')->constrained('products');
            $table->string('type')->index();
            $table->integer('times')->default(0);
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
        Schema::dropIfExists('product_associations');
    }
}
