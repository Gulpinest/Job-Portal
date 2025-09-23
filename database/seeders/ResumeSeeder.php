<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelamar;
use App\Models\Resume;

class ResumeSeeder extends Seeder
{
    public function run(): void
    {
        $pelamars = Pelamar::all();

        foreach ($pelamars as $pelamar) {
            Resume::factory()->for($pelamar, 'pelamar')->create();
        }
    }
}
