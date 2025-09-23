<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'nama_perusahaan' => fake()->company(),
            'alamat_perusahaan' => fake()->address(),
            'no_telp_perusahaan' => fake()->phoneNumber(),
            'desc_company' => fake()->paragraph(),
        ];
    }
}
