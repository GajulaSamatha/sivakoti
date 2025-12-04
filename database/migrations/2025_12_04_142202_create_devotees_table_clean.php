<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devotees', function (Blueprint $table) {
            $table->id();
            $table->string('user_name'); // Corresponds to the 'username' field
            
            // Unique index for social login and account creation
            $table->string('email_id')->unique(); 
            
            // Required for Socialite linkage, must be nullable for standard signup
            $table->string('google_id')->nullable()->unique();
            
            $table->string('phone_number')->nullable()->unique(); // Made nullable/unique
            $table->string('alternate_phone_number')->nullable();
            $table->string('gotram')->nullable(); // Made nullable
            
            $table->text('family_details')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('anniversary')->nullable();
            
            $table->text('address')->nullable(); // Renamed from 'area_address'
            $table->string('password'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devotees');
    }
};