<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_poojas', function (Blueprint $table) {
            $table->id();

            // 1. CATEGORIZATION & CORE FIELDS
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // 2. SCHEDULING & LOCATION
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('location')->nullable();
            
            // 3. FINANCIALS & MEDIA
            $table->decimal('price', 8, 2)->default(0); // Price or required donation
            $table->string('image')->nullable(); // Path to the uploaded image

            // 4. CMS CONTROL (Status)
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->boolean('is_enabled')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_poojas');
    }
};