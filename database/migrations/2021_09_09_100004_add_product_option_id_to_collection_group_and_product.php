<?php

use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\ProductOption;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collection_groups', function (Blueprint $table) {
            $table->foreignIdFor(ProductOption::class)->after('id')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignIdFor(ProductOption::class)->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('products', function ($table) {
            $table->dropColumn('product_option_id');
        });

        Schema::table('collection_groups', function ($table) {
            $table->dropColumn('product_option_id');
        });
    }
};
