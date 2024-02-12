<?php

use App\Models\Collection;
use App\Models\CollectionGroup;
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
        Schema::table('kits', function (Blueprint $table) {
            //
            $table->dropColumn('collection_group_id');

        });
        Schema::table('kits', function (Blueprint $table) {
            //
            $table->foreignIdFor(CollectionGroup::class)->nullable()->after('id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kits', function (Blueprint $table) {
            //
            if (Schema::hasColumn('kits', 'collection_group_id')) {
                $table->dropColumn('collection_group_id');
            }
        });
        Schema::table('kits', function (Blueprint $table) {
            //
            $table->foreignIdFor(CollectionGroup::class)->after('id');
        });
    }
};
