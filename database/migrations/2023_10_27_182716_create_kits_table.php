<?php

use App\Models\CollectionGroup;
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
        Schema::create('kits', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CollectionGroup::class);
            $table->foreignIdFor(Media::class,'featured_image_id');
            $table->string('name')->index();
            $table->string('slug')->unique()->index();
            $table->integer('total_price')->nullable();
            $table->integer('price')->index();
            $table->enum('status',['enPreparation','cache','Publie'])->default('enPreparation');
            $table->json('description')->nullable();
            $table->json('attribute_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kits');
    }
};
