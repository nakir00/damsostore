<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class UpdateQuantityOnCartLinesTable extends Migration
{
    public function up()
    {
        Schema::table('cart_lines', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->change();
        });
    }

    public function down()
    {
        Schema::table('cart_lines', function ($table) {
            $table->smallInteger('quantity')->unsigned()->change();
        });
    }
}
