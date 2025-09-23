<?php

namespace Database\Factories;

use App\Models\Pelamar;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_pelamar' => Pelamar::factory(),
            'nama_skill' => fake()->randomElement(['PHP', 'Laravel', 'JavaScript', 'Python', 'React', 'Vue', 'SQL', 'Git']),
        ];
    }
}
