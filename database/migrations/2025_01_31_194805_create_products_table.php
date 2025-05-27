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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategory_id')->nullable()->constrained('categories');
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // relationship between vendors and products
            $table->string('product_name');
            $table->string('product_slug')->unique();
            $table->string('product_code')->unique();
            $table->decimal('product_quantity',10,2)->default(0.00);
            $table->string('product_tags')->nullable();
            $table->string('product_size')->nullable();
            $table->string('product_color')->nullable();
            $table->decimal('selling_price', 10, 2); 
            $table->decimal('discount_price', 10, 2)->nullable(); // Nullable decimal for discount price
            $table->text('short_descp'); 
            $table->text('long_descp'); // Same as above
            $table->string('product_thumbnail')->nullable(); // Store path to the thumbnail image (nullable)
            $table->tinyInteger('hot_deals')->nullable(); 
            $table->tinyInteger('featured')->nullable(); 
            $table->tinyInteger('special_offer')->nullable();
            $table->tinyInteger('special_deals')->nullable();
            $table->tinyInteger('status')->default(0); // Default value for status (0 for inactive, 1 for active)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
