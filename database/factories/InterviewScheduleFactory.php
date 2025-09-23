<?php

namespace Database\Factories;

use App\Models\Lowongan;
use App\Models\Company;
use App\Models\Lamaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterviewScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_lowongan' => Lowongan::factory(),
            'id_company' => Company::factory(),
            'id_lamaran' => Lamaran::factory(),
            'tempat' => fake()->address(),
        ];
    }
}
