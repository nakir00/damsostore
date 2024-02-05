<?php

use App\Models\Page;
use App\Models\User;
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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Media::class,'featured_image_id');
            $table->foreignIdFor(Media::class,'miniature_image_id')->nullable();
            $table->foreignIdFor(Page::class)->nullable();
            $table->foreignIdFor(User::class,'author_id')->nullable();
            $table->string('url')->nullable();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('description');
            $table->enum('status',['enPreparation','cache','Publie'])->default('enPreparation');
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
        Schema::dropIfExists('articles');
    }
};
