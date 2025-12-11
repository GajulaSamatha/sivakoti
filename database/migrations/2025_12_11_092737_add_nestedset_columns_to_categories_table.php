<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Manually add the _lft and _rgt columns for the nested set.
            // We do this manually because the parent_id column already exists.
            $table->unsignedInteger('_lft')->default(0);
            $table->unsignedInteger('_rgt')->default(0);

            // Rebuild the tree structure from the parent_id column
            Category::fixTree();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Manually drop the columns if the migration is rolled back.
            $table->dropNestedSet();
        });
    }
};
