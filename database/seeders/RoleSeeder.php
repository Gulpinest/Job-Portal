<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles;

class RoleSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'System Administrator',
                'description' => 'Full system access and management capabilities',
                'is_active' => true
            ],
            [
                'name' => 'pelamar',
                'display_name' => 'Pelamar',
                'description' => 'Job Seekers Looking for Employment',
                'is_active' => true
            ],
            [
                'name' => 'company',
                'display_name' => 'Company',
                'description' => 'Organizations Posting Job Listings',
                'is_active' => true
            ]
        ];

        foreach ($roles as $role) {
            Roles::create($role);
        }
    }
}
