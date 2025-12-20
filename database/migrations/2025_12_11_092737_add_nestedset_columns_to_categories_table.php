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
            // Add nested set columns if they don't exist
            if (!Schema::hasColumn('categories', '_lft')) {
                $table->nestedSet();
            }
        });

        // Rebuild the tree to populate _lft and _rgt values for existing records.
        // We check if the model has data before fixing to avoid errors.
        if (Schema::hasColumn('categories', '_lft') && Category::withoutGlobalScopes()->count() > 0) {
            Category::fixTree();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropNestedSet();
        });
    }
};
