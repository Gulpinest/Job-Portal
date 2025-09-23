<?php

namespace Database\Factories;

use App\Models\Pelamar;
use App\Models\Lowongan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LamaranFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_pelamar' => Pelamar::factory(),
            'id_lowongan' => Lowongan::factory(),
            'cv' => fake()->url(),
            'status_ajuan' => fake()->randomElement(['Pending', 'Accepted', 'Rejected']),
        ];
    }
}
