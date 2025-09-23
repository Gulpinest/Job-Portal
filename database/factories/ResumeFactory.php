<?php

namespace Database\Factories;

use App\Models\Pelamar;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResumeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_pelamar' => Pelamar::factory(),
            'nama_resume' => fake()->word() . ' Resume',
            'file_resume' => fake()->url(),
        ];
    }
}
