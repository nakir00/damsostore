<?php

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(User::class)->nullable();
            $table->foreignIdFor(Address::class);
            //$table->foreignId('channel_id')->constrained('channels');
            $table->enum('status',['enAttente','confirme','enLivraison','livre','annule'])->default('enAttente');
            $table->string('reference')->nullable()->unique();
            //$table->string('customer_reference')->nullable();
            $table->unsignedBigInteger('sub_total')->index();
            $table->unsignedBigInteger('discount_total')->index();
            $table->integer('shipping_total')->nullable()->unsigned()->index();
            //$table->json('tax_breakdown');
            //$table->integer('tax_total')->unsigned()->index();
            $table->unsignedBigInteger('total')->index();
            $table->text('notes')->nullable();
            $table->timestamp('date_commande')->nullable();
            $table->timestamp('date_confirmation')->nullable();
            $table->timestamp('date_livraison')->nullable();
            $table->timestamp('date_annulation')->nullable();
            //$table->string('currency_code', 3);
            //$table->string('compare_currency_code', 3)->nullable();
            //$table->decimal('exchange_rate', 10, 4)->default(1);
            $table->date('date')->nullable()->index();
            $table->json('attribute_data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
