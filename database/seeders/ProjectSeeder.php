<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::create([
            'slug' => 'pulse-market',
            'title' => 'Pulse Market',
            'category' => 'Platform Web Fintech',
            'description' => 'Marketplace investasi responsif dengan pembayaran multi-dompet dan dasbor investor yang akurat.',
            'status' => 'Production',
            'image' => 'https://via.placeholder.com/400x250/4F46E5/FFFFFF?text=Pulse+Market',
            'project_url' => 'https://pulse-market.example.com',
            'technologies' => ['React', 'Node.js', 'MongoDB', 'Stripe API'],
            'features' => ['Multi-wallet payments', 'Real-time analytics', 'Investor dashboard'],
            'display_order' => 1,
            'is_active' => true,
        ]);

        Project::create([
            'slug' => 'shopify-clone',
            'title' => 'Shopify Clone',
            'category' => 'E-commerce',
            'description' => 'Platform e-commerce lengkap dengan fitur manajemen inventory dan pembayaran.',
            'status' => 'Production',
            'image' => 'https://via.placeholder.com/400x250/28A745/FFFFFF?text=Shopify+Clone',
            'project_url' => 'https://shopify-clone.example.com',
            'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Redis'],
            'features' => ['Inventory management', 'Payment gateway integration', 'Real-time notifications'],
            'display_order' => 2,
            'is_active' => true,
        ]);

        Project::create([
            'slug' => 'health-tracker-app',
            'title' => 'Health Tracker App',
            'category' => 'Mobile App Development',
            'description' => 'Aplikasi mobile tracking kesehatan dengan fitur pelaporan dan reminder.',
            'status' => 'Beta publik',
            'image' => 'https://via.placeholder.com/400x250/DC3545/FFFFFF?text=Health+Tracker',
            'project_url' => 'https://health-tracker.example.com',
            'technologies' => ['Flutter', 'Firebase', 'Node.js'],
            'features' => ['Health data tracking', 'Reminder system', 'Analytics dashboard'],
            'display_order' => 3,
            'is_active' => true,
        ]);
    }
}
