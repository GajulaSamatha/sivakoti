<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::table('devotees', function (Blueprint $table) {
        $table->string('username')->nullable()->change(); // Use change() method
    });
}

public function down()
{
    Schema::table('devotees', function (Blueprint $table) {
        // You may need to specify the original column type here if it wasn't nullable
        $table->string('username')->nullable(false)->change(); 
    });
}
};