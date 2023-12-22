<?php

use App\Models\Home;
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
        Schema::create('top_sliders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Media::class,'featured_image_id');
            $table->foreignIdFor(Home::class);
            $table->string('button_message')->nullable()->default('voir Plus');
            $table->string('button_link');
            $table->string('primary')->nullable();
            $table->string('secondary')->nullable();
            $table->string('info')->nullable();
            $table->enum('position',['center','N','S','E','W','NE','NW','SE','SW']);
            $table->integer('order')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_sliders');
    }
};
