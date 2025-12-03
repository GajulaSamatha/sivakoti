<?php

// database/migrations/YYYY_MM_DD_HHMMSS_make_devotee_profile_fields_nullable.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devotees', function (Blueprint $table) {
            // Make these fields nullable as they are optional during Google login
            $table->string('phone_number')->nullable()->change();
            $table->string('gotram')->nullable()->change();
            
            // If you did not make the username nullable earlier, you can do it here:
            // $table->string('username')->nullable()->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('devotees', function (Blueprint $table) {
            // Revert them to NOT NULL if you need to roll back
            $table->string('phone_number')->nullable(false)->change();
            $table->string('gotram')->nullable(false)->change();
            
            // $table->string('username')->nullable(false)->change();
        });
    }
};