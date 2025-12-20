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
        // 1. Add _lft if missing
        if (!Schema::hasColumn('categories', '_lft')) {
            $table->unsignedInteger('_lft')->default(0);
        }

        // 2. Add _rgt if missing
        if (!Schema::hasColumn('categories', '_rgt')) {
            $table->unsignedInteger('_rgt')->default(0);
        }

        // 3. Add parent_id ONLY if it doesn't exist. 
        // If it exists, we might need to make sure it's an unsignedBigInteger for the package.
        if (!Schema::hasColumn('categories', 'parent_id')) {
            $table->unsignedBigInteger('parent_id')->nullable()->index();
        }
        
        // Ensure index exists for nested set performance
        $table->index(['_lft', '_rgt', 'parent_id']);
    });

    // Rebuild the tree
    if (Category::withoutGlobalScopes()->count() > 0) {
        Category::fixTree();
    }
}

public function down(): void
{
    Schema::table('categories', function (Blueprint $table) {
        // We drop only the columns this specific migration added
        $table->dropColumn(['_lft', '_rgt']);
        // Only drop parent_id if you are sure it wasn't there before
        // $table->dropColumn('parent_id'); 
    });
}
};
