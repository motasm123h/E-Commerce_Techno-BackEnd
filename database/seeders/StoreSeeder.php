<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Section;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Brands
        $brandsData = ['ASUS', 'MSI', 'Lenovo', 'Intel', 'AMD', 'Corsair', 'Samsung', 'Acer'];
        $brands = [];
        foreach ($brandsData as $brandName) {
            // إضافة الـ slug هنا
            $brands[$brandName] = Brand::firstOrCreate([
                'name' => $brandName,
                'slug' => Str::slug($brandName) 
            ]);
        }

        // 2. Seed Categories & Sections
        $catalogStructure = [
            'Laptops' => ['Gaming Laptops', 'Ultrabooks', 'Business Laptops'],
            'Components' => ['Processors', 'Graphic Cards', 'Motherboards', 'RAM', 'Storage'],
            'Peripherals' => ['Keyboards', 'Mice', 'Headsets'],
            'Displays' => ['Gaming Monitors', 'Smart Monitors'],
        ];

        $sections = [];
        foreach ($catalogStructure as $categoryName => $sectionNames) {
            // إضافة الـ slug هنا
            $category = Category::firstOrCreate([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName)
            ]);
            
            foreach ($sectionNames as $sectionName) {
                // إضافة الـ slug هنا
                $sections[$sectionName] = Section::firstOrCreate([
                    'name' => $sectionName,
                    'slug' => Str::slug($sectionName),
                    'category_id' => $category->id
                ]);
            }
        }

        // 3. Seed Realistic Products
        $productsData = [
            [
                'name' => 'Asus ROG Strix XG27AQMR 27" 2K 300Hz 1ms Gaming Monitor',
                'price' => 699.00,
                'stock' => 15,
                'brand' => 'ASUS',
                'section' => 'Gaming Monitors',
                'component_type' => null,
                'colors' => ['Black', 'Dark Grey'],
                'details' => [
                    'Resolution : 2560 x 1440 (2K)',
                    'Refresh Rate : 300Hz',
                    'Response Time : 1ms Fast IPS',
                    'Ports : 2x HDMI 2.0, 1x DisplayPort 1.4'
                ]
            ],
            [
                'name' => 'Lenovo Legion Pro 7i Gen 8 - Intel Core i9 13900HX RTX 4080',
                'price' => 2499.00,
                'stock' => 5,
                'brand' => 'Lenovo',
                'section' => 'Gaming Laptops',
                'component_type' => null,
                'colors' => ['Onyx Grey'],
                'details' => [
                    'CPU : Intel Core i9-13900HX',
                    'GPU : NVIDIA GeForce RTX 4080 12GB',
                    'RAM : 32GB DDR5 5600MHz',
                    'Storage : 1TB PCIe SSD Gen 4'
                ]
            ],
            [
                'name' => 'Intel Core i7-14700K 14th Gen 20-Core Desktop Processor',
                'price' => 399.00,
                'stock' => 40,
                'brand' => 'Intel',
                'section' => 'Processors',
                'component_type' => 'cpu',
                'colors' => [],
                'details' => [
                    'Cores : 20 (8 P-cores + 12 E-cores)',
                    'Threads : 28',
                    'Max Turbo Frequency : 5.60 GHz',
                    'Socket : LGA 1700'
                ]
            ],
            [
                'name' => 'MSI GeForce RTX 4070 Ti SUPER 16G GAMING X SLIM',
                'price' => 849.00,
                'stock' => 8,
                'brand' => 'MSI',
                'section' => 'Graphic Cards',
                'component_type' => 'graphic_card',
                'colors' => ['Black', 'Silver'],
                'details' => [
                    'Memory : 16GB GDDR6X',
                    'Core Clocks : Extreme Performance: 2685 MHz',
                    'Power Connectors : 16-pin x 1',
                    'Recommended PSU : 700 W'
                ]
            ],
            [
                'name' => 'Corsair Vengeance RGB 32GB (2x16GB) DDR5 6000MHz',
                'price' => 129.00,
                'stock' => 25,
                'brand' => 'Corsair',
                'section' => 'RAM',
                'component_type' => 'ram',
                'colors' => ['Black', 'White'],
                'details' => [
                    'Capacity : 32GB (2 x 16GB)',
                    'Speed : DDR5 6000 (PC5 48000)',
                    'CAS Latency : 36',
                    'Voltage : 1.35V'
                ]
            ],
            [
                'name' => 'Acer Predator Helios 16 - i7 13700HX RTX 4070',
                'price' => 1599.00,
                'stock' => 0,
                'brand' => 'Acer',
                'section' => 'Gaming Laptops',
                'component_type' => null,
                'colors' => ['Abyssal Black'],
                'details' => [
                    'Display : 16" WQXGA (2560 x 1600) 165Hz',
                    'CPU : Intel Core i7-13700HX',
                    'GPU : NVIDIA GeForce RTX 4070 8GB',
                    'RAM : 16GB DDR5'
                ]
            ],
            [
                'name' => 'Samsung 32" Odyssey G7 Neo 4K UHD Quantum Mini-LED',
                'price' => 1099.00,
                'stock' => 12,
                'brand' => 'Samsung',
                'section' => 'Gaming Monitors',
                'component_type' => null,
                'colors' => ['Black', 'White Back'],
                'details' => [
                    'Resolution : 4K UHD (3,840 x 2,160)',
                    'Refresh Rate : 165Hz',
                    'Panel Type : Quantum Mini-LED',
                    'HDR : Quantum HDR2000'
                ]
            ]
        ];

        foreach ($productsData as $item) {
            $section = $sections[$item['section']];
            
            Product::firstOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'is_active' => true,
                    'brand_id' => $brands[$item['brand']]->id,
                    'section_id' => $section->id,
                    'category_id' => $section->category_id,
                    'component_type' => $item['component_type'],
                    'colors' => $item['colors'], 
                    'details' => $item['details'],
                ]
            );
        }

        $this->command->info('Tech Store catalog has been seeded successfully with real-world components!');
    }
}