<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::create([
            'name' => 'TechCorp Indonesia',
            'initial' => 'TC',
            'logo_url' => 'https://via.placeholder.com/100x100/4F46E5/FFFFFF?text=TC',
            'display_order' => 1,
            'is_active' => true,
        ]);

        Client::create([
            'name' => 'StartupXYZ',
            'initial' => 'SXYZ',
            'logo_url' => 'https://via.placeholder.com/100x100/28A745/FFFFFF?text=SXYZ',
            'display_order' => 2,
            'is_active' => true,
        ]);

        Client::create([
            'name' => 'Enterprise Solutions',
            'initial' => 'ES',
            'logo_url' => 'https://via.placeholder.com/100x100/DC3545/FFFFFF?text=ES',
            'display_order' => 3,
            'is_active' => true,
        ]);

        Client::create([
            'name' => 'Global Finance',
            'initial' => 'GF',
            'logo_url' => 'https://via.placeholder.com/100x100/FFC107/FFFFFF?text=GF',
            'display_order' => 4,
            'is_active' => true,
        ]);

        Client::create([
            'name' => 'HealthCare Plus',
            'initial' => 'HCP',
            'logo_url' => 'https://via.placeholder.com/100x100/17A2B8/FFFFFF?text=HCP',
            'display_order' => 5,
            'is_active' => true,
        ]);
    }
}
