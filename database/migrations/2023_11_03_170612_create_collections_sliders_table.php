<?php

use App\Models\Collection;
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
        Schema::create('collections_sliders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Home::class);
            $table->morphs('collectionable');
            $table->string('name');
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
        Schema::dropIfExists('collections_sliders');
    }
};
