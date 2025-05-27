<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $count = 10;

    foreach (range($count, 1) as $i) {
        User::factory()->create([
            'id' => $i, // descending manually
        ]);
    }
    }
}
