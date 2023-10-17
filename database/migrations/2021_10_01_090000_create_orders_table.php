<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            //$table->userForeignKey(nullable: true);
            $table->foreignId('user_id')->constrained('users')->nullable()->onUpdate('cascade');
            //$table->foreignId('channel_id')->constrained('channels');
            $table->string('status')->index();
            $table->string('reference')->nullable()->unique();
            $table->string('customer_reference')->nullable();
            $table->integer('sub_total')->unsigned()->index();
            $table->integer('discount_total')->default(0)->unsigned()->index();
            $table->integer('shipping_total')->default(0)->unsigned()->index();
            //$table->json('tax_breakdown');
            //$table->integer('tax_total')->unsigned()->index();
            $table->integer('total')->unsigned()->index();
            $table->text('notes')->nullable();
            //$table->string('currency_code', 3);
            //$table->string('compare_currency_code', 3)->nullable();
            //$table->decimal('exchange_rate', 10, 4)->default(1);
            $table->dateTime('placed_at')->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
