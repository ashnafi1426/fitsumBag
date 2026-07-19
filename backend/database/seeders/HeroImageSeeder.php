<?php

namespace Database\Seeders;

use App\Models\HeroImage;
use Illuminate\Database\Seeder;

class HeroImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heroImages = [
            [
                'image_url' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=1200&q=80',
                'title' => 'Designer Handbag',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'image_url' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=1200&q=80',
                'title' => 'Leather Satchel',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'image_url' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=1200&q=80',
                'title' => 'Business Briefcase',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'image_url' => 'https://images.unsplash.com/photo-1566150905458-1bf1fc113f0d?w=1200&q=80',
                'title' => 'Luxury Tote',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'image_url' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=1200&q=80',
                'title' => 'Leather Portfolio',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($heroImages as $image) {
            HeroImage::create($image);
        }

        $this->command->info('Hero images seeded successfully!');
    }
}
