<?php

use App\Models\CollectionProductSlider;
use App\Models\collectionsSlider;
use App\Models\home;
use App\Models\infoSlider;
use App\Models\topSlider;
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
        Schema::create('homes', function (Blueprint $table) {
            $table->id();
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

        Schema::dropIfExists('homes');
    }
};
