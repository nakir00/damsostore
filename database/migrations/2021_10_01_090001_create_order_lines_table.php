<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLinesTable extends Migration
{
    public function up()
    {
        Schema::create('order_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_id')->constrained('orders');
            $table->morphs('purchasable');
            $table->string('type')->index();
            $table->string('description');
            $table->string('option')->nullable();
            $table->string('identifier')->index();
            $table->integer('unit_price')->unsigned()->index();
            $table->smallInteger('unit_quantity')->default(1)->unsigned()->index();
            $table->smallInteger('quantity')->unsigned();
            $table->integer('sub_total')->unsigned()->index();
            $table->integer('discount_total')->default(0)->unsigned()->index();
            //$table->json('tax_breakdown');
            //$table->integer('tax_total')->unsigned()->index();
            $table->integer('total')->unsigned()->index();
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_lines');
    }
}