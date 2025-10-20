<?php

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
        Schema::table('categories', function (Blueprint $table) {
            // New column for Enable/Disable (defaulting to enabled/true)
            $table->boolean('is_enabled')->default(true)->after('order');
            // New column for Draft/Publish (defaulting to published)
            $table->string('status')->default('published')->after('is_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['is_enabled', 'status']);
        });
    }
};