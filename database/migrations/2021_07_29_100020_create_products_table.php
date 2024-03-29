<?php

use App\Models\Collection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Collection::class)->default(null)->nullable();
            $table->string('name')->index();
            $table->string('slug')->unique()->index();
            $table->json('description')->nullable();
            $table->json('attribute_data')->nullable();
            $table->integer('old_price')->nullable()->default(0);
            $table->enum('status',['enPreparation','cache','Publie'])->default('enPreparation');
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
        Schema::dropIfExists('products');
    }
}
