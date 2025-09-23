<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Log;
use App\Models\User;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user yang sudah ada
        $users = User::all();

        // Buat 1-10 log acak untuk setiap user
        foreach ($users as $user) {
            Log::factory(rand(1, 10))->for($user, 'user')->create();
        }
    }
}
