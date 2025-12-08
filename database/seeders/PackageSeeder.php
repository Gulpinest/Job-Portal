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
                'name' => 'Gratis',
                'nama_package' => 'Gratis',
                'price' => 0,
                'job_limit' => 2,
                'duration_months' => null,
                'description' => 'Paket gratis dengan limit 2 lowongan aktif secara bersamaan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium - 6 Bulan',
                'nama_package' => 'Premium - 6 Bulan',
                'price' => 4500000,
                'job_limit' => null, // NULL = unlimited
                'duration_months' => 6,
                'description' => 'Paket premium selama 6 bulan dengan lowongan tak terbatas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium - 12 Bulan',
                'nama_package' => 'Premium - 12 Bulan',
                'price' => 8000000,
                'job_limit' => null, // NULL = unlimited
                'duration_months' => 12,
                'description' => 'Paket premium selama 12 bulan dengan lowongan tak terbatas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
