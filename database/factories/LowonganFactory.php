<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class LowonganFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_company' => Company::factory(),
            'judul' => fake()->jobTitle(),
            'posisi' => fake()->randomElement(['Manajer', 'Staf', 'Analis', 'Pengembang']),
            'deskripsi' => fake()->paragraphs(3, true),
            'status' => fake()->randomElement(['Open', 'Closed']),
        ];
    }
}
