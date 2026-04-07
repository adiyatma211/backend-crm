<?php

namespace Database\Seeders;

use App\Models\ProcessInfo;
use Illuminate\Database\Seeder;

class ProcessInfoSeeder extends Seeder
{
    public function run(): void
    {
        ProcessInfo::create([
            'label' => 'Proyek Selesai',
            'value' => 150.00,
            'unit' => 'produk',
            'display_order' => 1,
            'is_active' => true,
        ]);

        ProcessInfo::create([
            'label' => 'Client Puas',
            'value' => 95.00,
            'unit' => '%',
            'display_order' => 2,
            'is_active' => true,
        ]);

        ProcessInfo::create([
            'label' => 'On-time Delivery',
            'value' => 98.00,
            'unit' => '%',
            'display_order' => 3,
            'is_active' => true,
        ]);

        ProcessInfo::create([
            'label' => 'Repeat Clients',
            'value' => 45.00,
            'unit' => '%',
            'display_order' => 4,
            'is_active' => true,
        ]);
    }
}
