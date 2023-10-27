<?php

use App\Models\ProductOption;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionGroupsTable extends Migration
{

    public function up()
    {
        Schema::create('collection_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Media::class,'featured_image_id');
            $table->json('attribute_data')->nullable();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
    }


    public function down()
    {
        Schema::dropIfExists('collection_groups');
    }
}
