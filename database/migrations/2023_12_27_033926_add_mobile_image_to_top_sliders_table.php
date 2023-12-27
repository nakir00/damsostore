<?php

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('top_sliders', function (Blueprint $table) {
            //
            $table->foreignIdFor(Media::class,'mobile_media_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('top_sliders', function (Blueprint $table) {
            //
            $table->dropColumn('mobile_media_id');
        });
    }
};
