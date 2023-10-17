<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddPositionToMediaProductVariantTable// extends Migration
{
    public function up()
    {
        Schema::table('media_product_variant', function (Blueprint $table) {
            $table->smallInteger('position')->after('primary')->default(1)->index();
        });
    }

    public function down()
    {
        Schema::table('media_product_variant', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
}
