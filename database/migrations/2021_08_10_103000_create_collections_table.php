<?php

use App\Models\Collection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_group_id')->nullable()->constrained('collection_groups');
            $table->foreignIdFor(Collection::class)->nullable();
            $table->string('name');
            $table->integer('order')->default(0);
            $table->integer('parent_id')->nullable();
            $table->string('type')->default('static')->index();
            $table->string('sort')->default('custom')->index();
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
        Schema::dropIfExists('collections');
    }
}
