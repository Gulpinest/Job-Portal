<?php

namespace Database\Factories;

use App\Models\Log;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'action' => fake()->randomElement(['login', 'logout', 'update profile', 'create skill', 'delete skill']),
            // Kolom `created_at` dan `updated_at` akan diisi otomatis oleh timestamps()
        ];
    }
}
