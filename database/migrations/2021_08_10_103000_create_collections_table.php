<?php

use App\Models\Collection;
use Awcodes\Curator\Models\Media;
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
            $table->foreignIdFor(Media::class,'featured_image_id')->nullable();
            $table->foreignId('collection_group_id')->constrained('collection_groups');
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('parent_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('onNavBar')->default(false);
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
