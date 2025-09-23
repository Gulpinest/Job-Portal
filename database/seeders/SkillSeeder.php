<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelamar;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $pelamars = Pelamar::all();

        foreach ($pelamars as $pelamar) {
            Skill::factory(rand(1, 5))->for($pelamar, 'pelamar')->create();
        }
    }
}
