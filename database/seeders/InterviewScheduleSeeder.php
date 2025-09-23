<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\Company;
use App\Models\InterviewSchedule;

class InterviewScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $lamarans = Lamaran::all();
        $lowongans = Lowongan::all();
        $companies = Company::all();

        if ($lamarans->isEmpty() || $lowongans->isEmpty() || $companies->isEmpty()) {
            return;
        }

        foreach ($lamarans as $lamaran) {
            $lowongan = $lowongans->random();
            $company = $companies->random();
            InterviewSchedule::factory()
                ->for($lowongan, 'lowongan')
                ->for($company, 'company')
                ->for($lamaran, 'lamaran')
                ->create();
        }
    }
}
