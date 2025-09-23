<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelamar;
use App\Models\Lowongan;
use App\Models\Lamaran;

class LamaranSeeder extends Seeder
{
    public function run(): void
    {
        $pelamars = Pelamar::all();
        $lowongans = Lowongan::all();

        if ($pelamars->isEmpty() || $lowongans->isEmpty()) {
            return;
        }

        foreach ($pelamars as $pelamar) {
            $lowongan = $lowongans->random();
            Lamaran::factory()->for($pelamar, 'pelamar')->for($lowongan, 'lowongan')->create();
        }
    }
}
