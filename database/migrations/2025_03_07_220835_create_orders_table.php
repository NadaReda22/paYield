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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('post_code')->nullable();
            $table->text('notes')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('transaction_id')->nullable()->unique();
            $table->string('currency');
            $table->float('amount',8,2);
            $table->string('order_number')->nullable()->unique();
            $table->string('invoice_no')->unique();

            //Order Tracking Dates
            $table->string('order_month');
            $table->string('order_date');
            $table->string('order_year');


            $table->string('confirmed_date')->nullable();
            $table->string('processing_date')->nullable();
            $table->string('picked_date')->nullable();
            $table->string('shipped_date')->nullable();
            $table->string('delivered_date')->nullable();
            $table->string('cancel_date')->nullable();
            $table->string('return_date')->nullable();
            $table->string('return_reason')->nullable();


            $table->string('status')->default('pending');

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ship_division_id')->constrained('ship_divisions')->onDelete('cascade');
            $table->foreignId('ship_district_id')->constrained('ship_districts')->onDelete('cascade');
            $table->foreignId('ship_state_id')->constrained('ship_states')->onDelete('cascade');             
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
