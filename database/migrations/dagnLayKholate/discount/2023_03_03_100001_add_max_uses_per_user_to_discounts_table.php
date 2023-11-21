<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddMaxUsesPerUserToDiscountsTable // extends Migration
{
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->mediumInteger('max_uses_per_user')->unsigned()->nullable()->after('max_uses');
        });
    }

    public function down()
    {
        Schema::table('discounts', function ($table) {
            $table->dropColumn('max_uses_per_user');
        });
    }
}
