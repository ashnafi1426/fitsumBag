<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\RestaurantSetting;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Create restaurant settings
        RestaurantSetting::create([
            'name' => 'Royal Leather',
            'tagline' => 'Crafted Excellence, Timeless Elegance',
            'slug' => 'royal-leather',
            'currency' => 'USD',
            'language' => 'en',
            'is_active' => true,
        ]);

        $menuData = $this->getMenuData();

        foreach ($menuData as $index => $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'name_amharic' => $categoryData['nameAmharic'],
                'slug' => $categoryData['id'],
                'sort_order' => $index,
                'is_active' => true,
            ]);

            foreach ($categoryData['items'] as $itemIndex => $item) {
                MenuItem::create([
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'name_amharic' => $item['nameAmharic'],
                    'description' => $item['description'] ?? null,
                    'price' => $item['price'],
                    'image' => $item['image'] ?? null,
                    'tags' => $item['tags'] ?? [],
                    'features' => $item['features'] ?? [],
                    'is_available' => true,
                    'is_featured' => in_array('Bestseller', $item['tags'] ?? []) || in_array('Premium', $item['tags'] ?? []),
                    'sort_order' => $itemIndex,
                ]);
            }
        }
    }

    private function getMenuData(): array
    {
        return [
            [
                'id' => 'luxury-bags',
                'name' => 'Luxury Bags',
                'nameAmharic' => 'የቅንጦት ቦርሳዎች',
                'items' => [
                    [
                        'name' => 'Executive Briefcase',
                        'nameAmharic' => 'የአስፈፃሚ ቦርሳ',
                        'price' => 299.99,
                        'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&q=80',
                        'description' => 'Premium full-grain leather briefcase with padded laptop compartment. Perfect for the modern professional.',
                        'tags' => ['Bestseller', 'Business'],
                        'features' => ['Genuine Full-Grain Leather', 'Padded Laptop Compartment', 'TSA-Friendly Design', 'Lifetime Warranty', 'Water Resistant']
                    ],
                    [
                        'name' => 'Classic Tote Bag',
                        'nameAmharic' => 'ክላሲክ ቦርሳ',
                        'price' => 189.99,
                        'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=800&q=80',
                        'description' => 'Elegant leather tote with spacious interior. Handcrafted with attention to every detail.',
                        'tags' => ['Popular', 'Everyday'],
                        'features' => ['Handcrafted Quality', 'Spacious Interior', 'Magnetic Closure', 'Interior Pockets', 'Comfortable Handles']
                    ],
                    [
                        'name' => 'Designer Crossbody',
                        'nameAmharic' => 'ዲዛይነር መተላለፊያ',
                        'price' => 159.99,
                        'image' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=800&q=80',
                        'description' => 'Compact yet spacious crossbody bag in premium leather. Adjustable strap for ultimate comfort.',
                        'tags' => ['Trending'],
                        'features' => ['Adjustable Strap', 'Premium Leather', 'Secure Zipper', 'Multiple Compartments', 'Lightweight Design']
                    ],
                    [
                        'name' => 'Heritage Satchel',
                        'nameAmharic' => 'ቅርስ ቦርሳ',
                        'price' => 249.99,
                        'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&q=80',
                        'description' => 'Vintage-inspired satchel crafted from the finest Italian leather. Timeless design meets functionality.',
                        'tags' => ['Premium', 'Classic']
                    ],
                    [
                        'name' => 'Metropolitan Backpack',
                        'nameAmharic' => 'የከተማ ጀርባ ቦርሳ',
                        'price' => 279.99,
                        'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&q=80',
                        'description' => 'Sophisticated leather backpack with multiple compartments. Ideal for work and travel.',
                        'tags' => ['Modern', 'Travel']
                    ],
                    [
                        'name' => 'Royal Messenger',
                        'nameAmharic' => 'ሮያል መልእክተኛ',
                        'price' => 219.99,
                        'image' => 'https://images.unsplash.com/photo-1547949003-9792a18a2601?w=800&q=80',
                        'description' => 'Classic messenger bag in top-quality leather. Features secure closures and organized pockets.',
                        'tags' => ['Bestseller']
                    ],
                    [
                        'name' => 'Luxury Hobo Bag',
                        'nameAmharic' => 'የቅንጦት ሆቦ ቦርሳ',
                        'price' => 199.99,
                        'image' => 'https://images.unsplash.com/photo-1566150905458-1bf1fc113f0d?w=800&q=80',
                        'description' => 'Slouchy yet structured hobo bag in buttery soft leather. Perfect blend of style and practicality.',
                        'tags' => ['Comfortable']
                    ],
                    [
                        'name' => 'Professional Portfolio',
                        'nameAmharic' => 'ፕሮፌሽናል ፖርትፎሊዮ',
                        'price' => 169.99,
                        'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=800&q=80',
                        'description' => 'Sleek leather portfolio with zippered closure. Keeps documents safe and organized.',
                        'tags' => ['Business', 'Organized']
                    ],
                    [
                        'name' => 'Evening Clutch',
                        'nameAmharic' => 'የምሽት ክላች',
                        'price' => 129.99,
                        'image' => 'https://images.unsplash.com/photo-1566150905458-1bf1fc113f0d?w=800&q=80',
                        'description' => 'Elegant leather clutch with gold hardware. Perfect for formal occasions and special events.',
                        'tags' => ['Elegant', 'Special']
                    ],
                    [
                        'name' => 'Weekender Duffel',
                        'nameAmharic' => 'የሳምንት ዳፈል',
                        'price' => 349.99,
                        'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&q=80',
                        'description' => 'Spacious leather duffel bag for weekend getaways. Durable construction with classic styling.',
                        'tags' => ['Travel', 'Spacious']
                    ],
                    [
                        'name' => 'Mini Shoulder Bag',
                        'nameAmharic' => 'ትንሽ ትከሻ ቦርሳ',
                        'price' => 139.99,
                        'image' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=800&q=80',
                        'description' => 'Compact shoulder bag in premium leather. Lightweight and versatile for everyday use.',
                        'tags' => ['Compact', 'Everyday']
                    ],
                    [
                        'name' => 'Business Laptop Bag',
                        'nameAmharic' => 'የቢዝነስ ላፕቶፕ ቦርሳ',
                        'price' => 259.99,
                        'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&q=80',
                        'description' => 'Professional laptop bag with reinforced corners. Protects your tech in style.',
                        'tags' => ['Professional', 'Tech']
                    ],
                ],
            ],
            [
                'id' => 'premium-wallets',
                'name' => 'Premium Wallets',
                'nameAmharic' => 'የቅንጦት ቦርሳዎች',
                'items' => [
                    [
                        'name' => 'Executive Bi-Fold',
                        'nameAmharic' => 'የአስፈፃሚ ባይፎልድ',
                        'price' => 79.99,
                        'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&q=80',
                        'description' => 'Classic bi-fold wallet in genuine leather. Multiple card slots and bill compartment.',
                        'tags' => ['Bestseller', 'Classic'],
                        'features' => ['Genuine Italian Leather', '8 Card Slots', 'Bill Compartment', 'ID Window', 'RFID Blocking']
                    ],
                    [
                        'name' => 'Slim Card Holder',
                        'nameAmharic' => 'ቀጭን ካርድ መያዣ',
                        'price' => 49.99,
                        'image' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=800&q=80',
                        'description' => 'Minimalist card holder in premium leather. Holds up to 8 cards with ease.',
                        'tags' => ['Modern', 'Minimalist'],
                        'features' => ['Ultra-Slim Profile', 'Premium Leather', 'Holds 8 Cards', 'Easy Access Design', 'Durable Construction']
                    ],
                    [
                        'name' => 'Money Clip Wallet',
                        'nameAmharic' => 'የገንዘብ ክሊፕ',
                        'price' => 89.99,
                        'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&q=80',
                        'description' => 'Sophisticated wallet with metal money clip. Sleek design for the modern gentleman.',
                        'tags' => ['Premium', 'Elegant']
                    ],
                    [
                        'name' => 'Zipper Wallet',
                        'nameAmharic' => 'ዚፐር ቦርሳ',
                        'price' => 99.99,
                        'image' => 'https://images.unsplash.com/photo-1591492275342-26d035217b94?w=800&q=80',
                        'description' => 'Full zip-around wallet with RFID protection. Maximum security meets style.',
                        'tags' => ['Secure', 'Bestseller']
                    ],
                    [
                        'name' => 'Travel Wallet',
                        'nameAmharic' => 'የጉዞ ቦርሳ',
                        'price' => 119.99,
                        'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&q=80',
                        'description' => 'Multi-compartment travel wallet for passports, cards, and currency. Essential for travelers.',
                        'tags' => ['Travel', 'Organized']
                    ],
                    [
                        'name' => 'Coin Pocket Wallet',
                        'nameAmharic' => 'ሳንቲም ኪስ',
                        'price' => 69.99,
                        'image' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=800&q=80',
                        'description' => 'Traditional wallet with secure coin pocket. Practical design with elegant finish.',
                        'tags' => ['Traditional', 'Practical']
                    ],
                    [
                        'name' => 'Tri-Fold Wallet',
                        'nameAmharic' => 'ትሪፎልድ ቦርሳ',
                        'price' => 74.99,
                        'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&q=80',
                        'description' => 'Compact tri-fold design with multiple compartments. Perfect balance of size and storage.',
                        'tags' => ['Compact']
                    ],
                    [
                        'name' => 'RFID Blocking Wallet',
                        'nameAmharic' => 'RFID መከላከያ',
                        'price' => 94.99,
                        'image' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=800&q=80',
                        'description' => 'Advanced RFID protection in sleek leather. Keep your information safe and secure.',
                        'tags' => ['Technology', 'Secure']
                    ],
                    [
                        'name' => 'Long Wallet',
                        'nameAmharic' => 'ረጅም ቦርሳ',
                        'price' => 109.99,
                        'image' => 'https://images.unsplash.com/photo-1591492275342-26d035217b94?w=800&q=80',
                        'description' => 'Extended wallet with checkbook holder. Luxury meets functionality.',
                        'tags' => ['Spacious', 'Premium']
                    ],
                    [
                        'name' => 'Designer Card Case',
                        'nameAmharic' => 'ዲዛይነር ካርድ ኬዝ',
                        'price' => 59.99,
                        'image' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=800&q=80',
                        'description' => 'Ultra-thin card case in exotic leather. Sophisticated minimalism at its finest.',
                        'tags' => ['Designer', 'Thin']
                    ],
                    [
                        'name' => 'Smartphone Wallet',
                        'nameAmharic' => 'ስማርትፎን ቦርሳ',
                        'price' => 84.99,
                        'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&q=80',
                        'description' => 'Innovative wallet with phone pocket. Modern solution for the digital age.',
                        'tags' => ['Modern', 'Innovative']
                    ],
                    [
                        'name' => 'Vintage Wallet',
                        'nameAmharic' => 'ቪንቴጅ ቦርሳ',
                        'price' => 89.99,
                        'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&q=80',
                        'description' => 'Distressed leather wallet with vintage charm. Ages beautifully over time.',
                        'tags' => ['Vintage', 'Character']
                    ],
                ],
            ],
        ];
    }
}
