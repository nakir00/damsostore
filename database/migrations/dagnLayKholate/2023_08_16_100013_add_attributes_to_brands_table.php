<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddAttributesToBrandsTable //extends Migration
{
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->json('attribute_data')->after('name')->nullable();
        });
    }

    public function down()
    {
        Schema::table('brands', function ($table) {
            $table->dropColumn('attribute_data');
        });
    }
}
