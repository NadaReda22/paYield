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
        Schema::table('users', function (Blueprint $table) {
        $table->string('username')->nullable()->after('id');
        $table->string('photo')->nullable();
        $table->string('phone')->nullable();
        $table->string('address')->nullable();
        $table->enum('role',['user','vendor','admin'])->default('user');
        $table->enum('status',['active','inactive'])->default('active');
        $table->timestamp('lastseen')->nullable()->default(now());
        $table->year('vendor_join_date')->nullable()->default(date('Y'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove Columns
      $removeColumns=['username','photo','phone','address','role','status','lastseen','vendor_join_date'];
      foreach( $removeColumns as  $removeColumn)
      {
        if(schema::has('users', $removeColumn))
        {
            $table->dropColumn( $removeColumn);
        }
      }
      //End Removing Columns
        });
    }
};
