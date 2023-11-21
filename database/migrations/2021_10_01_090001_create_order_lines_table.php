<?php

use App\Models\Order;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLinesTable extends Migration
{
    public function up()
    {
        Schema::create('orderable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Order::class);
            $table->morphs('orderable');
            $table->json('option')->nullable();
            //$table->string('identifier')->index();
            $table->unsignedBigInteger('unit_price')->index();
            //$table->smallInteger('unit_quantity')->default(1)->unsigned()->index();
            $table->unsignedInteger('quantity');
            //$table->unsignedBigInteger('sub_total')->index();
            //$table->unsignedBigInteger('discount_total')->unsigned()->index();
            //$table->json('tax_breakdown');
            //$table->integer('tax_total')->unsigned()->index();
            $table->unsignedBigInteger('total')->index();
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orderable');
    }
}
