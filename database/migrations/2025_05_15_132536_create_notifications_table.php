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
        Schema::create('notifications', function (Blueprint $table) {
           // Primary key as UUID for uniqueness across distributed systems
    $table->uuid('id')->primary();

    // Fully qualified class name of the notification (e.g., App\Notifications\...)
    $table->string('type');

    // Polymorphic relation columns: notifiable_id (int/uuid) + notifiable_type (e.g., App\Models\User)
    $table->morphs('notifiable'); // adds: notifiable_id, notifiable_type

    // Notification payload (stored as JSON)
    $table->json('data');

    // When the notification was read (null = unread)
    $table->timestamp('read_at')->nullable();

    // Created and updated timestamps
    $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
