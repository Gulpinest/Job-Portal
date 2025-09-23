<?php

namespace Database\Factories;

use App\Models\Lamaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusLamaranFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_lamaran' => Lamaran::factory(),
            'status_lamaran' => fake()->randomElement(['Diajukan', 'Diproses', 'Interview', 'Diterima', 'Ditolak']),
        ];
    }
}
