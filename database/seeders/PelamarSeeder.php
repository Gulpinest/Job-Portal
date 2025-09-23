<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelamar;
use App\Models\User;

class PelamarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel users sudah terisi, karena pelamars bergantung pada users.
        $users = User::all();

        foreach ($users as $user) {
            Pelamar::factory()->for($user, 'user')->create();
        }
    }
}
