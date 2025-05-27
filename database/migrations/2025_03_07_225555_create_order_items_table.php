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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Foreign Keys
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained('users')->onDelete('set null'); // Assuming vendors are stored in 'users'

            // Product Details
            $table->string('color', 50)->nullable();
            $table->string('size', 50)->nullable();
            $table->unsignedInteger('qty'); // Changed from string to integer for numeric value
            $table->float('price', 10, 2); // Changed from float to decimal for accurate currency calculations

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oreder_items');
    }
};
