<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        Testimonial::create([
            'text' => 'Tim Sentosaku sangat profesional dalam mengembangkan aplikasi mobile kami. Proses yang transparan dan hasil yang melebihi ekspektasi.',
            'author' => 'John Doe',
            'title' => 'CEO, TechCorp Indonesia',
            'initials' => 'JD',
            'rating' => 5,
            'display_order' => 1,
            'is_active' => true,
        ]);

        Testimonial::create([
            'text' => 'Kualitas kode dan komunikasi yang sangat baik. Sangat direkomendasikan untuk proyek development.',
            'author' => 'Jane Smith',
            'title' => 'CTO, StartupXYZ',
            'initials' => 'JS',
            'rating' => 5,
            'display_order' => 2,
            'is_active' => true,
        ]);

        Testimonial::create([
            'text' => 'Delivery on-time dan budget sesuai plan. Tim sangat responsif terhadap perubahan requirements.',
            'author' => 'Robert Johnson',
            'title' => 'Product Manager, Enterprise Solutions',
            'initials' => 'RJ',
            'rating' => 4,
            'display_order' => 3,
            'is_active' => true,
        ]);
    }
}
