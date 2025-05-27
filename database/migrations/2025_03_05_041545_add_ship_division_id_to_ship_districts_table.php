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
        Schema::table('ship_districts', function (Blueprint $table) {
            $table->foreignId('ship_division_id')->constrained('ship_divisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ship_districts', function (Blueprint $table) {
            $table->dropColumn('ship_division_id');
        });
    }
};
