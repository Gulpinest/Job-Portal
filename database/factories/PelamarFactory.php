<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PelamarFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'nama_pelamar' => fake()->name(),
            'status_pekerjaan' => fake()->randomElement(['Full-time', 'Part-time', 'Freelancer', 'Mahasiswa']),
            'no_telp' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'tgl_lahir' => fake()->date(),
        ];
    }
}
