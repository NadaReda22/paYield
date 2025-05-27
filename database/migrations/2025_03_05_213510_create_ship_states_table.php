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
        Schema::create('ship_states', function (Blueprint $table) {
            $table->id();
            $table->string('state_name');
            $table->foreignId('ship_division_id')->constrained('ship_divisions')->onDelete('cascade');
            $table->foreignId('ship_district_id')->constrained('ship_districts')->onDelete('cascade');         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ship_states');
    }
};
