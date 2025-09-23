<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
            PelamarSeeder::class,
            LowonganSeeder::class,
            SkillSeeder::class,
            ResumeSeeder::class,
            LamaranSeeder::class,
            StatusLamaranSeeder::class,
            InterviewScheduleSeeder::class,
            LogSeeder::class,
        ]);
    }
}
