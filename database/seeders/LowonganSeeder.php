<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Lowongan;

class LowonganSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            Lowongan::factory(rand(1, 5))->for($company, 'company')->create();
        }
    }
}
