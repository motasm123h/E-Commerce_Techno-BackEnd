<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Section;
use App\Models\Brand;
use App\Models\Product;

class StoreHierarchySeeder extends Seeder
{
    public function run()
    {
        $defaultImages = [
            '/storage/products/PC_1.png',
            '/storage/products/PC_2.png',
            '/storage/products/PC_3.png',
        ];

        $laptopDetails = [
            "Processor: Intel® Core™ i7 / AMD Ryzen 7\nTotal Cores: 16\nMax Turbo Frequency 5.2 GHz",
            "Cache: 30 MB Smart Cache",
            "Memory: 32GB (2x16GB DDR5-5600)\nMax Capacity: 64GB",
            "Graphic Card: NVIDIA® GeForce RTX™ Series\n8GB GDDR6",
            "Display: 16-inch, FHD+ 16:10\nRefresh Rate: 165Hz, G-Sync",
            "Storage: 1TB PCIe® 4.0 NVMe™ M.2 SSD",
            "Networking: Wi-Fi 6E (802.11ax)\nBluetooth® 5.3",
            "Interface: 1x HDMI 2.1\n3x USB 3.2 Gen 2 Type-A\n1x USB Type-C (DP / Power Delivery)\n1x Thunderbolt™ 4",
            "Battery: 90WHrs, 4-cell Li-ion",
            "Operating System: Windows 11 Home",
            "Special features: RGB Backlit Keyboard"
        ];

        $gpuDetails = [
            "Architecture: NVIDIA Ada Lovelace / AMD RDNA 3\nDedicated Ray Tracing Cores",
            "Boost Clock: Up to 2.52 GHz",
            "Memory: 12GB - 24GB GDDR6X",
            "Display Support: Maximum Resolution 7680x4320\nHDMI 2.1a, 3x DisplayPort 1.4a",
            "Power Specs: Required System Power 750W-850W",
            "Cooling: Triple Fan Design\nZero Frozr Technology"
        ];

        // ==========================================
        // 1. Laptops Category
        // ==========================================
        $catLaptops = Category::firstOrCreate(['name' => 'Laptops', 'slug' => Str::slug('Laptops')]);
        $secGaming = Section::firstOrCreate(['category_id' => $catLaptops->id, 'name' => 'Gaming Laptops', 'slug' => Str::slug('Gaming Laptops')]);

        $gamingLaptops = [
            ['name' => 'ASUS ROG Strix G16', 'brand' => 'ASUS', 'price' => 1499.99],
            ['name' => 'MSI Raider GE78', 'brand' => 'MSI', 'price' => 1899.00],
            ['name' => 'Razer Blade 16', 'brand' => 'Razer', 'price' => 2499.99],
            ['name' => 'Alienware m16 R2', 'brand' => 'Dell', 'price' => 1799.00],
            ['name' => 'Acer Predator Helios 16', 'brand' => 'Acer', 'price' => 1599.50],
            ['name' => 'Lenovo Legion Pro 7i', 'brand' => 'Lenovo', 'price' => 1999.00],
            ['name' => 'HP Omen 16', 'brand' => 'HP', 'price' => 1299.99],
            ['name' => 'Gigabyte AORUS 15', 'brand' => 'Gigabyte', 'price' => 1349.00],
            ['name' => 'ASUS TUF Gaming A15', 'brand' => 'ASUS', 'price' => 1099.99],
            ['name' => 'MSI Stealth 16 Studio', 'brand' => 'MSI', 'price' => 1699.00],
            ['name' => 'Lenovo LOQ 15', 'brand' => 'Lenovo', 'price' => 899.99],
            ['name' => 'Dell G15 Gaming', 'brand' => 'Dell', 'price' => 999.00],
        ];

        foreach ($gamingLaptops as $laptop) {
            // حل المشكلة: دمج اسم الماركة مع القسم لضمان Slug فريد دائماً
            $brandSlug = Str::slug($laptop['brand'] . '-' . $secGaming->name);
            $brand = Brand::firstOrCreate(
                ['slug' => $brandSlug],
                ['section_id' => $secGaming->id, 'name' => $laptop['brand']]
            );

            Product::firstOrCreate(
                ['slug' => Str::slug($laptop['name'])],
                [
                    'section_id' => $secGaming->id,
                    'brand_id' => $brand->id,
                    'name' => $laptop['name'],
                    'price' => $laptop['price'],
                    'stock' => rand(5, 25),
                    'is_active' => true,
                    'images' => $defaultImages,
                    'colors' => ['Black', 'Gray'],
                    'details' => $laptopDetails,
                ]
            );
        }

        // ==========================================
        // 2. PC Components Category
        // ==========================================
        $catComponents = Category::firstOrCreate(['name' => 'PC Components', 'slug' => Str::slug('PC Components')]);
        $secGpu = Section::firstOrCreate(['category_id' => $catComponents->id, 'name' => 'Graphic Cards', 'slug' => Str::slug('Graphic Cards')]);

        $gpus = [
            ['name' => 'ASUS ROG Strix RTX 4090', 'brand' => 'ASUS', 'price' => 1999.99],
            ['name' => 'MSI Suprim X RTX 4080', 'brand' => 'MSI', 'price' => 1199.00],
            ['name' => 'Gigabyte AORUS RTX 4070 Ti', 'brand' => 'Gigabyte', 'price' => 799.99],
            ['name' => 'ZOTAC Trinity RTX 4070', 'brand' => 'ZOTAC', 'price' => 599.00],
            ['name' => 'ASUS Dual RTX 4060 Ti', 'brand' => 'ASUS', 'price' => 399.99],
            ['name' => 'MSI Ventus 2X RTX 4060', 'brand' => 'MSI', 'price' => 299.99],
            ['name' => 'Sapphire Nitro+ RX 7900 XTX', 'brand' => 'Sapphire', 'price' => 999.00],
            ['name' => 'PowerColor Red Devil RX 7900 XT', 'brand' => 'PowerColor', 'price' => 849.99],
            ['name' => 'XFX Speedster RX 7800 XT', 'brand' => 'XFX', 'price' => 499.00],
            ['name' => 'ASUS TUF RX 7700 XT', 'brand' => 'ASUS', 'price' => 449.00],
            ['name' => 'Gigabyte Eagle RX 7600', 'brand' => 'Gigabyte', 'price' => 269.99],
            ['name' => 'Intel Arc A770', 'brand' => 'Intel', 'price' => 349.00],
        ];

        foreach ($gpus as $gpu) {
            $brandSlug = Str::slug($gpu['brand'] . '-' . $secGpu->name);
            $brand = Brand::firstOrCreate(
                ['slug' => $brandSlug],
                ['section_id' => $secGpu->id, 'name' => $gpu['brand']]
            );

            Product::firstOrCreate(
                ['slug' => Str::slug($gpu['name'])],
                [
                    'section_id' => $secGpu->id,
                    'brand_id' => $brand->id,
                    'name' => $gpu['name'],
                    'price' => $gpu['price'],
                    'stock' => rand(2, 15),
                    'is_active' => true,
                    'images' => $defaultImages,
                    'colors' => [],
                    'details' => $gpuDetails,
                    // 'component_type' => 'gpus'
                ]
            );
        }

        // ==========================================
        // 3. Peripherals Category
        // ==========================================
        $catPeripherals = Category::firstOrCreate(['name' => 'Peripherals', 'slug' => Str::slug('Peripherals')]);
        $secMice = Section::firstOrCreate(['category_id' => $catPeripherals->id, 'name' => 'Gaming Mice', 'slug' => Str::slug('Gaming Mice')]);

        $miceDetails = [
            "Sensor: Optical Gaming Sensor\nResolution: Up to 25,600 DPI",
            "Responsiveness: USB report rate 1000 Hz (1ms)",
            "Buttons: 5-8 Programmable Buttons",
            "Weight: Ultra-lightweight (<70g)",
            "Connection: Wireless 2.4GHz / Wired USB-C",
            "Battery Life: Up to 70 hours"
        ];

        $mice = [
            ['name' => 'Logitech G PRO X Superlight', 'brand' => 'Logitech', 'price' => 149.99],
            ['name' => 'Razer DeathAdder V3 Pro', 'brand' => 'Razer', 'price' => 149.00],
            ['name' => 'SteelSeries Aerox 3', 'brand' => 'SteelSeries', 'price' => 99.99],
            ['name' => 'Corsair Dark Core RGB', 'brand' => 'Corsair', 'price' => 79.99],
            ['name' => 'HyperX Pulsefire Haste', 'brand' => 'HyperX', 'price' => 49.99],
            ['name' => 'Glorious Model O', 'brand' => 'Glorious', 'price' => 79.99],
            ['name' => 'Zowie EC2-C', 'brand' => 'Zowie', 'price' => 69.99],
            ['name' => 'Roccat Kone Pro Air', 'brand' => 'Roccat', 'price' => 89.99],
            ['name' => 'ASUS ROG Gladius III', 'brand' => 'ASUS', 'price' => 109.99],
            ['name' => 'Cooler Master MM710', 'brand' => 'Cooler Master', 'price' => 39.99],
            ['name' => 'Endgame Gear XM1r', 'brand' => 'Endgame Gear', 'price' => 59.99],
            ['name' => 'Finalmouse Starlight-12', 'brand' => 'Finalmouse', 'price' => 189.00],
        ];

        foreach ($mice as $mouse) {
            $brandSlug = Str::slug($mouse['brand'] . '-' . $secMice->name);
            $brand = Brand::firstOrCreate(
                ['slug' => $brandSlug],
                ['section_id' => $secMice->id, 'name' => $mouse['brand']]
            );

            Product::firstOrCreate(
                ['slug' => Str::slug($mouse['name'])],
                [
                    'section_id' => $secMice->id,
                    'brand_id' => $brand->id,
                    'name' => $mouse['name'],
                    'price' => $mouse['price'],
                    'stock' => rand(10, 50),
                    'is_active' => true,
                    'images' => $defaultImages,
                    'colors' => ['Black', 'White'],
                    'details' => $miceDetails,
                    // 'component_type'=>'Headphone'
                ]
            );
        }
    }
}
