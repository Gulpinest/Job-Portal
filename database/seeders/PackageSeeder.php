<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('packages')->insert([
            [
                'name' => 'Bronze',
                'price' => 199000,
                'job_limit' => 3, // Maksimal lowongan aktif
                'description' => 'Paket dasar untuk kebutuhan rekrutmen kecil.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Silver',
                'price' => 699000,
                'job_limit' => 15,
                'description' => 'Paling populer untuk perusahaan yang berkembang cepat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gold',
                'price' => 1499000,
                'job_limit' => null, // NULL = unlimited
                'description' => 'Akses penuh dengan lowongan tanpa batas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
