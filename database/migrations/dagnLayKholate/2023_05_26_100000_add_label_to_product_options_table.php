<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddLabelToProductOptionsTable //extends Migration
{
    public function up()
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->json('label')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('product_options', function ($table) {
            $table->dropColumn('label');
        });
    }
}
