<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/..._create_popups_table.php

    public function up(): void
    {
        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content')->nullable(); // For Visual Editor content
            $table->string('image')->nullable(); // For Featured Image
            
            // Optional: Assuming you'll link it to a Category (e.g., 'News Popup', 'Donation Popup')
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            
            $table->boolean('is_enabled')->default(true); // For Active/Inactive/Enable/Disable
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popups');
    }
};
