<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lamaran;
use App\Models\StatusLamaran;

class StatusLamaranSeeder extends Seeder
{
    public function run(): void
    {
        $lamarans = Lamaran::all();

        foreach ($lamarans as $lamaran) {
            StatusLamaran::factory()->for($lamaran, 'lamaran')->create();
        }
    }
}
