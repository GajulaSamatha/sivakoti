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
        Schema::create('devotees', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('phone_number')->unique();
            $table->string('alternate_phone_number')->nullable();
            $table->string('gotram');
            $table->text('family_details')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('anniversary')->nullable();
            $table->string('email_id')->unique();
            $table->text('area_address')->nullable();
            $table->string('password'); // This will store the hashed password
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devotees');
    }
};