<?php

namespace Database\Seeders;

use App\Models\Stat;
use Illuminate\Database\Seeder;

class StatSeeder extends Seeder
{
    public function run(): void
    {
        Stat::create([
            'value' => '32',
            'label' => 'Produk digital sukses',
            'display_order' => 1,
            'is_active' => true,
        ]);

        Stat::create([
            'value' => '150',
            'label' => 'Klien puas',
            'display_order' => 2,
            'is_active' => true,
        ]);

        Stat::create([
            'value' => '5',
            'label' => 'Tahun pengalaman',
            'display_order' => 3,
            'is_active' => true,
        ]);

        Stat::create([
            'value' => '20+',
            'label' => 'Team ahli',
            'display_order' => 4,
            'is_active' => true,
        ]);
    }
}
