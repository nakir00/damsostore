<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('merged_id')->nullable()->constrained('carts');
            //$table->foreignId('currency_id')->constrained($'currencies');
            //$table->foreignId('channel_id')->constrained('channels');
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->string('coupon_code')->index()->nullable();
            $table->dateTime('completed_at')->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
