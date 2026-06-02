<?php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use Illuminate\Support\Str;
// use App\Models\Category;
// use App\Models\Section;
// use App\Models\Brand;
// use App\Models\Product;

// class StoreHierarchySeeder extends Seeder
// {
//     public function run()
//     {
//         ini_set('memory_limit', '512M');
//         set_time_limit(300);

//         $defaultImages = [
//             '/storage/products/PC_1.webp',
//             '/storage/products/PC_2.webp',
//         ];

//         $brandsPool = ['ASUS', 'MSI', 'Gigabyte', 'Razer', 'Corsair', 'Logitech', 'Intel', 'AMD', 'NVIDIA', 'HP', 'Dell', 'Lenovo', 'Cisco', 'TP-Link', 'Microsoft', 'Adobe'];
//         $modifiers = ['Pro', 'Ultra', 'X', 'Strix', 'Max', 'Elite', 'Plus', 'Gaming', 'Air', 'Evo', 'Super', 'Ultimate'];

//         // شجرة التصنيفات والسكشنات مترجمة فقط هنا لحقنها في الـ Category والـ Section
//         $hierarchy = [
//             'Component' => [
//                 'category_translations' => ['en' => 'Components', 'ar' => 'قطع الغيار والهاردوير'],
//                 'sections' => [
//                     ['en' => 'Graphic Cards', 'ar' => 'كروت الشاشة'],
//                     ['en' => 'Processors', 'ar' => 'المعالجات'],
//                     ['en' => 'Motherboards', 'ar' => 'اللوحات الأم'],
//                     ['en' => 'Power Supplies', 'ar' => 'مزودات الطاقة']
//                 ]
//             ],
//             'Monitors' => [
//                 'category_translations' => ['en' => 'Monitors', 'ar' => 'الشاشات الاحترافية'],
//                 'sections' => [
//                     ['en' => 'Gaming Monitors', 'ar' => 'شاشات الألعاب'],
//                     ['en' => '4K Office Monitors', 'ar' => 'شاشات الأعمال 4K'],
//                     ['en' => 'Curved Displays', 'ar' => 'الشاشات المنحنية']
//                 ]
//             ],
//             'Accessories' => [
//                 'category_translations' => ['en' => 'Accessories', 'ar' => 'الإكسسوارات والمحيطيات'],
//                 'sections' => [
//                     ['en' => 'Gaming Mice', 'ar' => 'فارات الألعاب'],
//                     ['en' => 'Mechanical Keyboards', 'ar' => 'لوحات مفاتيح ميكانيكية'],
//                     ['en' => 'Headsets', 'ar' => 'سماعات الرأس والجيمنج']
//                 ]
//             ],
//             'Networking' => [
//                 'category_translations' => ['en' => 'Networking', 'ar' => 'معدات الشبكات والربط'],
//                 'sections' => [
//                     ['en' => 'Wi-Fi Routers', 'ar' => 'راوترات الواي فاي'],
//                     ['en' => 'Network Switches', 'ar' => 'موزعات الشبكة'],
//                     ['en' => 'Access Points', 'ar' => 'نقاط الوصول']
//                 ]
//             ],
//             'Desktop' => [
//                 'category_translations' => ['en' => 'Desktops', 'ar' => 'الحواسب المكتبية المجمعة'],
//                 'sections' => [
//                     ['en' => 'Pre-built Gaming PCs', 'ar' => 'حواسب الألعاب الجاهزة'],
//                     ['en' => 'Workstations', 'ar' => 'محطات العمل الاحترافية'],
//                     ['en' => 'Mini PCs', 'ar' => 'الحواسب المصغرة']
//                 ]
//             ],
//             'Laptop' => [
//                 'category_translations' => ['en' => 'Laptops', 'ar' => 'الحواسب المحمولة'],
//                 'sections' => [
//                     ['en' => 'Gaming Laptops', 'ar' => 'لاب توب جيمنج'],
//                     ['en' => 'Ultrabooks', 'ar' => 'أولترابوك خفيف الوزن'],
//                     ['en' => 'Business Laptops', 'ar' => 'لاب توب للأعمال']
//                 ]
//             ],
//             'Software' => [
//                 'category_translations' => ['en' => 'Software', 'ar' => 'البرمجيات وأنظمة التشغيل'],
//                 'sections' => [
//                     ['en' => 'Operating Systems', 'ar' => 'أنظمة التشغيل'],
//                     ['en' => 'Creative Suites', 'ar' => 'حزم التصميم والإبداع'],
//                     ['en' => 'Antivirus & Security', 'ar' => 'أنظمة الحماية والأمان']
//                 ]
//             ]
//         ];

//         $detailsTemplates = [
//             'Component' => ['Architecture: Next-Gen Core Matrix', 'Power Specs: Efficiency Certified', 'Cooling: Twin Air-Flow Node'],
//             'Monitors' => ['Display: Ultra-Sharp Panel', 'Refresh Rate: Up to 240Hz Response', 'Interface: Dual HDMI + DisplayPort'],
//             'Accessories' => ['Sensor: Low-Latency Laser Node', 'Connection: Dual-Mode Wireless/Type-C', 'Battery: Long-Life Tech Pack'],
//             'Networking' => ['Bandwidth: High-Throughput Gig Node', 'Protocols: Advanced IPv6 / WPA3 Security', 'Coverage: Multi-Directional Array'],
//             'Desktop' => ['Architecture: High-Performance Desktop Node', 'Power Specs: 850W Gold Certified', 'Storage: High-Speed NVMe Arrays'],
//             'Laptop' => ['Processor: Low-Voltage Architecture', 'Display: Thin-Bezel IPS Matrix', 'Battery: Extended Lifecycle Cell'],
//             'Software' => ['License: Perpetual Enterprise Key', 'Platform: Cross-Platform Node Compatible', 'Updates: Continuous Security Patches']
//         ];

//         // ⚡ قالب الأوصاف التسويقية باللغة الإنجليزية الصافية فقط لجميع المنتجات تبعا للفئة ⚡
//         $descriptionTemplates = [
//             'Component' => 'Experience blistering core speeds and ultimate hardware reliability. Engineered for overclocking enthusiasts and top-tier workstation scaling.',
//             'Monitors' => 'Immerse yourself in beautiful pixel-perfect rendering and fluid motion refresh layers. Perfect for professional color grading and competitive eSports.',
//             'Accessories' => 'Unlock zero-latency mechanical response and ergonomic command grid parameters. Built to maximize gaming precision and everyday operational workflows.',
//             'Networking' => 'Deploy lightning-fast data throughput nodes backed by industrial-grade security frameworks. Total eliminated latency and extended range cell optimization.',
//             'Desktop' => 'Uncompromising pre-built battle stations optimized for heavy workflows and seamless triple-A tier gaming execution. Maximum physical component synergy guaranteed.',
//             'Laptop' => 'True portability meets ultimate desktop performance metrics. Featuring extreme battery cellular arrays and premium thermal dissipators.',
//             'Software' => 'Genuine licensing parameters engineered to expand enterprise productivity and shield critical system architecture from evolving breach components.'
//         ];

//         $componentTypeMap = [
//             'processors' => 'cpu',
//             'graphic_cards' => 'graphic_card',
//             'motherboards' => 'motherboard',
//             'power_supplies' => 'power_supply',
//             'gaming_monitors' => 'monitor',
//             '4k_office_monitors' => 'monitor',
//             'curved_displays' => 'monitor',
//             'gaming_mice' => 'mouse',
//             'mechanical_keyboards' => 'keyboard',
//             'headsets' => 'headphone'
//         ];

//         foreach ($hierarchy as $catKey => $catData) {
//             // 1. حقن الترجمة المزدوجة لجدول الـ Category
//             $category = Category::firstOrCreate(
//                 ['slug' => Str::slug($catData['category_translations']['en'])],
//                 ['name' => $catData['category_translations']]
//             );

//             foreach ($catData['sections'] as $secData) {
//                 // 2. حقن الترجمة المزدوجة لجدول الـ Section
//                 $section = Section::firstOrCreate(
//                     ['slug' => Str::slug($secData['en'])],
//                     [
//                         'category_id' => $category->id,
//                         'name' => [
//                             'en' => $secData['en'],
//                             'ar' => $secData['ar']
//                         ]
//                     ]
//                 );

//                 $rawSnakeType = Str::snake(Str::plural($secData['en']));
//                 $componentType = $componentTypeMap[$rawSnakeType] ?? null;

//                 for ($i = 1; $i <= 25; $i++) {
//                     $brandName = $brandsPool[array_rand($brandsPool)];
//                     $mod1 = $modifiers[array_rand($modifiers)];
//                     $mod2 = $modifiers[array_rand($modifiers)];

//                     // اسم المنتج بالإنجليزية فقط
//                     $productName = "{$brandName} {$mod1} {$mod2} No.{$i} " . rand(100, 999);
//                     $productSlug = Str::slug($productName);
//                     $brandSlug = Str::slug($brandName . '-' . $secData['en']);

//                     // 3. حقن الترجمة المزدوجة لجدول الـ Brand بناءً على اسم الماركة والترجمة الافتراضية له
//                     $brand = Brand::firstOrCreate(
//                         ['slug' => $brandSlug],
//                         [
//                             'section_id' => $section->id,
//                             'name' => [
//                                 'en' => $brandName,
//                                 'ar' => $brandName
//                             ]
//                         ]
//                     );

//                     // تفاصيل منتجات بالإنجليزية صافية
//                     $customDetails = array_map(function ($detail) {
//                         return $detail . ' v' . rand(1, 9);
//                     }, $detailsTemplates[$catKey]);

//                     // ⚡ سحب نموذج الوصف كنص عادي باللغة الإنجليزية الصافية وإلحاق كود تحقق عشوائي به ⚡
//                     $productDescription = $descriptionTemplates[$catKey] . " Asset verification ID: UNIT-NODE-" . rand(1000, 9999) . ".";

//                     Product::firstOrCreate(
//                         ['slug' => $productSlug],
//                         [
//                             'section_id' => $section->id,
//                             'brand_id' => $brand->id,
//                             'name' => $productName,
//                             'description' => $productDescription,
//                             'price' => rand(49, 2999) + 0.99,
//                             'stock' => rand(0, 120),
//                             'is_active' => true,
//                             'images' => $defaultImages,
//                             'colors' => rand(0, 1) ? ['Black', 'White'] : ['Gray'],
//                             'details' => $customDetails,
//                             'component_type' => $componentType
//                         ]
//                     );
//                 }
//             }
//         }
//     }
// }


// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use Illuminate\Support\Str;
// use App\Models\Category;
// use App\Models\Section;
// use App\Models\Brand;
// use App\Models\Product;
// use App\Models\AttributeValue;

// class StoreHierarchySeeder extends Seeder
// {
//     public function run()
//     {
//         ini_set('memory_limit', '512M');
//         set_time_limit(300);

//         $defaultImages = [
//             '/storage/products/PC_1.webp',
//             '/storage/products/PC_2.webp',
//         ];

//         $brandsPool = ['ASUS', 'MSI', 'Gigabyte', 'Razer', 'Corsair', 'Logitech', 'Intel', 'AMD', 'NVIDIA', 'HP', 'Dell', 'Lenovo', 'Cisco', 'TP-Link', 'Microsoft', 'Adobe'];
//         $modifiers = ['Pro', 'Ultra', 'X', 'Strix', 'Max', 'Elite', 'Plus', 'Gaming', 'Air', 'Evo', 'Super', 'Ultimate'];

//         // شجرة التصنيفات والسكشنات المحدثة (تم إضافة قسم RAM)
//         $hierarchy = [
//             'Component' => [
//                 'category_translations' => ['en' => 'Components', 'ar' => 'قطع الغيار والهاردوير'],
//                 'sections' => [
//                     ['en' => 'Graphic Cards', 'ar' => 'كروت الشاشة'],
//                     ['en' => 'Processors', 'ar' => 'المعالجات'],
//                     ['en' => 'Motherboards', 'ar' => 'اللوحات الأم'],
//                     ['en' => 'RAM', 'ar' => 'الرامات'], // ⚡ إضافة هامة جداً
//                     ['en' => 'Power Supplies', 'ar' => 'مزودات الطاقة']
//                 ]
//             ],
//             'Monitors' => [
//                 'category_translations' => ['en' => 'Monitors', 'ar' => 'الشاشات الاحترافية'],
//                 'sections' => [
//                     ['en' => 'Gaming Monitors', 'ar' => 'شاشات الألعاب'],
//                     ['en' => '4K Office Monitors', 'ar' => 'شاشات الأعمال 4K']
//                 ]
//             ]
//             // تم تخفيف باقي الأقسام العشوائية للتركيز على اختبار الـ PC Builder
//         ];

//         $detailsTemplates = [
//             'Component' => ['Architecture: Next-Gen Core Matrix', 'Power Specs: Efficiency Certified', 'Cooling: Twin Air-Flow Node'],
//             'Monitors' => ['Display: Ultra-Sharp Panel', 'Refresh Rate: Up to 240Hz Response', 'Interface: Dual HDMI + DisplayPort'],
//         ];

//         $descriptionTemplates = [
//             'Component' => 'Experience blistering core speeds and ultimate hardware reliability. Engineered for overclocking enthusiasts and top-tier workstation scaling.',
//             'Monitors' => 'Immerse yourself in beautiful pixel-perfect rendering and fluid motion refresh layers. Perfect for professional color grading and competitive eSports.',
//         ];

//         // ربط السكشنات بأنواع الـ PC Builder
//         $componentTypeMap = [
//             'processors' => 'cpu',
//             'graphic_cards' => 'graphic_card',
//             'motherboards' => 'motherboard',
//             'rams' => 'ram', // ⚡
//             'power_supplies' => 'power_supply',
//             'gaming_monitors' => 'monitor',
//             '4k_office_monitors' => 'monitor'
//         ];

//         // ==========================================
//         // 🧠 بيانات حقيقية لاختبار الـ Flip-Flop
//         // ==========================================
//         $realisticData = [
//             'cpu' => [
//                 ['name' => 'Intel Core i9-14900K', 'brand' => 'Intel', 'price' => 599, 'attrs' => ['Socket' => 'LGA 1700', 'Supported RAM Types' => ['DDR4', 'DDR5'], 'CPU Cores' => '24 Cores']],
//                 ['name' => 'Intel Core i5-12400F', 'brand' => 'Intel', 'price' => 150, 'attrs' => ['Socket' => 'LGA 1700', 'Supported RAM Types' => ['DDR4'], 'CPU Cores' => '6 Cores']],
//                 ['name' => 'AMD Ryzen 9 7950X3D', 'brand' => 'AMD', 'price' => 699, 'attrs' => ['Socket' => 'AM5', 'Supported RAM Types' => ['DDR5'], 'CPU Cores' => '16 Cores']],
//                 ['name' => 'AMD Ryzen 5 5600X', 'brand' => 'AMD', 'price' => 130, 'attrs' => ['Socket' => 'AM4', 'Supported RAM Types' => ['DDR4'], 'CPU Cores' => '6 Cores']],
//             ],
//             'motherboard' => [
//                 ['name' => 'ASUS ROG MAXIMUS Z790 HERO', 'brand' => 'ASUS', 'price' => 600, 'attrs' => ['Socket' => 'LGA 1700', 'RAM Type' => 'DDR5', 'Supported Generations' => ['Intel 12th Gen (Alder Lake)', 'Intel 13th Gen (Raptor Lake)', 'Intel 14th Gen (Raptor Lake Refresh)'], 'Motherboard Chipset' => 'Z790', 'Form Factor' => 'ATX']],
//                 ['name' => 'MSI PRO B660M-A', 'brand' => 'MSI', 'price' => 140, 'attrs' => ['Socket' => 'LGA 1700', 'RAM Type' => 'DDR4', 'Supported Generations' => ['Intel 12th Gen (Alder Lake)', 'Intel 13th Gen (Raptor Lake)'], 'Motherboard Chipset' => 'B660', 'Form Factor' => 'Micro-ATX (mATX)']],
//                 ['name' => 'Gigabyte X670E AORUS MASTER', 'brand' => 'Gigabyte', 'price' => 450, 'attrs' => ['Socket' => 'AM5', 'RAM Type' => 'DDR5', 'Supported Generations' => ['AMD Ryzen 7000 Series (Zen 4)', 'AMD Ryzen 9000 Series (Zen 5)'], 'Motherboard Chipset' => 'X670E', 'Form Factor' => 'ATX']],
//                 ['name' => 'MSI MAG B550 TOMAHAWK', 'brand' => 'MSI', 'price' => 170, 'attrs' => ['Socket' => 'AM4', 'RAM Type' => 'DDR4', 'Supported Generations' => ['AMD Ryzen 5000 Series (Zen 3)'], 'Motherboard Chipset' => 'B550', 'Form Factor' => 'ATX']],
//             ],
//             'ram' => [
//                 ['name' => 'Corsair Vengeance RGB 32GB', 'brand' => 'Corsair', 'price' => 110, 'attrs' => ['RAM Type' => 'DDR5', 'RAM Capacity' => '32 GB (2x16)', 'RAM Speed (MHz)' => '6000 MHz']],
//                 ['name' => 'Kingston Fury Renegade 64GB', 'brand' => 'Kingston', 'price' => 250, 'attrs' => ['RAM Type' => 'DDR5', 'RAM Capacity' => '64 GB (2x32)']],
//                 ['name' => 'G.Skill Trident Z Neo 16GB', 'brand' => 'G.Skill', 'price' => 75, 'attrs' => ['RAM Type' => 'DDR4', 'RAM Capacity' => '16 GB (2x8)', 'RAM Speed (MHz)' => '3600 MHz']],
//                 ['name' => 'Crucial Ballistix 8GB', 'brand' => 'Crucial', 'price' => 35, 'attrs' => ['RAM Type' => 'DDR4', 'RAM Capacity' => '8 GB (1x8)']],
//             ]
//         ];

//         // ⚡ جلب كافة قيم الخصائص لسرعة ربطها
//         $allValues = AttributeValue::with('attribute')->get();
//         $getValueId = function ($attrName, $valName) use ($allValues) {
//             $item = $allValues->first(function ($v) use ($attrName, $valName) {
//                 return $v->attribute && $v->attribute->name === $attrName && $v->value === $valName;
//             });
//             return $item ? $item->id : null;
//         };

//         foreach ($hierarchy as $catKey => $catData) {
//             $category = Category::firstOrCreate(
//                 ['slug' => Str::slug($catData['category_translations']['en'])],
//                 ['name' => $catData['category_translations']]
//             );

//             foreach ($catData['sections'] as $secData) {
//                 $section = Section::firstOrCreate(
//                     ['slug' => Str::slug($secData['en'])],
//                     [
//                         'category_id' => $category->id,
//                         'name' => ['en' => $secData['en'], 'ar' => $secData['ar']]
//                     ]
//                 );

//                 $rawSnakeType = Str::snake(Str::plural($secData['en']));
//                 $componentType = $componentTypeMap[$rawSnakeType] ?? null;

//                 // حلقة الإدخال (4 منتجات فقط لكل قسم للتجربة السريعة)
//                 for ($i = 0; $i < 4; $i++) {

//                     // إذا كان القسم من ضمن المكونات الحساسة (CPU, MB, RAM) نأخذ بياناتنا الحقيقية
//                     $isRealistic = in_array($componentType, ['cpu', 'motherboard', 'ram']);
//                     $prodMeta = $isRealistic ? $realisticData[$componentType][$i] : null;

//                     $brandName = $isRealistic ? $prodMeta['brand'] : $brandsPool[array_rand($brandsPool)];
//                     $productName = $isRealistic ? $prodMeta['name'] : "{$brandName} {$modifiers[array_rand($modifiers)]} No." . rand(100, 999);
//                     $productSlug = Str::slug($productName . '-' . rand(10, 99));
//                     $brandSlug = Str::slug($brandName . '-' . $secData['en']);

//                     $brand = Brand::firstOrCreate(
//                         ['slug' => $brandSlug],
//                         ['section_id' => $section->id, 'name' => ['en' => $brandName, 'ar' => $brandName]]
//                     );

//                     $customDetails = array_map(function ($detail) { return $detail . ' v' . rand(1, 9); }, $detailsTemplates[$catKey] ?? $detailsTemplates['Component']);
//                     $productDescription = ($descriptionTemplates[$catKey] ?? $descriptionTemplates['Component']) . " Asset ID: NODE-" . rand(1000, 9999) . ".";

//                     $product = Product::firstOrCreate(
//                         ['slug' => $productSlug],
//                         [
//                             'section_id' => $section->id,
//                             'brand_id' => $brand->id,
//                             'name' => $productName,
//                             'description' => $productDescription,
//                             'price' => $isRealistic ? $prodMeta['price'] : (rand(49, 1500) + 0.99),
//                             'stock' => rand(5, 50),
//                             'is_active' => true,
//                             'images' => $defaultImages,
//                             'colors' => ['Black'],
//                             'details' => $customDetails,
//                             'component_type' => $componentType
//                         ]
//                     );

//                     // ⚡ ربط الخصائص الفيزيائية الذكية في حال كان منتج واقعي ⚡
//                     if ($isRealistic && isset($prodMeta['attrs'])) {
//                         $attachIds = [];
//                         foreach ($prodMeta['attrs'] as $attrName => $attrValue) {
//                             if (is_array($attrValue)) {
//                                 foreach ($attrValue as $v) {
//                                     $id = $getValueId($attrName, $v);
//                                     if ($id) $attachIds[] = $id;
//                                 }
//                             } else {
//                                 $id = $getValueId($attrName, $attrValue);
//                                 if ($id) $attachIds[] = $id;
//                             }
//                         }
//                         // حقن المعرّفات في الجدول الوسيط
//                         $product->attributeValues()->sync($attachIds);
//                     }
//                 }
//             }
//         }
//     }
// }




// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use Illuminate\Support\Str;
// use App\Models\Category;
// use App\Models\Section;
// use App\Models\Brand;
// use App\Models\Product;
// use App\Models\Attribute;
// use App\Models\AttributeValue;

// class StoreHierarchySeeder extends Seeder
// {
//     public function run()
//     {
//         ini_set('memory_limit', '512M');
//         set_time_limit(300);

//         $defaultImages = [
//             '/storage/products/PC_1.webp',
//             '/storage/products/PC_2.webp',
//         ];

//         // ==========================================
//         // 1. هيكلة الأقسام
//         // ==========================================
//         $sectionsBlueprint = [
//             'Component' => [
//                 'category_translations' => ['en' => 'Components', 'ar' => 'قطع الغيار والهاردوير'],
//                 'sections' => [
//                     ['en' => 'Processors', 'ar' => 'المعالجات'],
//                     ['en' => 'Motherboards', 'ar' => 'اللوحات الأم'],
//                     ['en' => 'RAM', 'ar' => 'الرامات'],
//                     ['en' => 'Graphic Cards', 'ar' => 'كروت الشاشة'],
//                     ['en' => 'Power Supplies', 'ar' => 'مزودات الطاقة']
//                 ]
//             ]
//         ];

//         $componentTypeMap = [
//             'processors' => 'cpu',
//             'motherboards' => 'motherboard',
//             'rams' => 'ram',
//             'graphic_cards' => 'graphic_card',
//             'power_supplies' => 'power_supply'
//         ];

//         // ==========================================
//         // 2. خريطة ربط الخصائص بالأقسام (حصرياً ما يلزم كل قسم)
//         // ==========================================
//         $sectionAttributesMap = [

//             'processors' => ['Socket', 'Supported RAM Types', 'CPU Cores'],
//             'motherboards' => ['Socket', 'RAM Type', 'Supported Generations', 'Motherboard Chipset', 'Form Factor'],
//             'rams' => ['RAM Type', 'RAM Capacity', 'RAM Speed (MHz)'],
//             'graphic_cards' => ['GPU Chipset', 'VRAM Capacity'],
//             'power_supplies' => ['PSU Wattage', 'PSU Rating & Modularity']
//         ];

//         // ==========================================
//         // 3. قاعدة البيانات الحقيقية والصلبة (10 من كل نوع مع الـ Prefix)
//         // ==========================================
//         $realisticData = [
//             'cpu' => [
//                 ['name' => 'Processor Intel Core i9-14900K', 'brand' => 'Intel', 'price' => 599, 'attrs' => ['Socket' => 'LGA 1700', 'Supported RAM Types' => ['DDR4', 'DDR5'], 'CPU Cores' => '24 Cores']],
//                 ['name' => 'Processor Intel Core i7-14700K', 'brand' => 'Intel', 'price' => 399, 'attrs' => ['Socket' => 'LGA 1700', 'Supported RAM Types' => ['DDR4', 'DDR5'], 'CPU Cores' => '24 Cores']],
//                 ['name' => 'Processor Intel Core i5-13600K', 'brand' => 'Intel', 'price' => 299, 'attrs' => ['Socket' => 'LGA 1700', 'Supported RAM Types' => ['DDR4', 'DDR5'], 'CPU Cores' => '14 Cores']],
//                 ['name' => 'Processor Intel Core i5-12400F', 'brand' => 'Intel', 'price' => 149, 'attrs' => ['Socket' => 'LGA 1700', 'Supported RAM Types' => ['DDR4', 'DDR5'], 'CPU Cores' => '6 Cores']],
//                 ['name' => 'Processor Intel Core Ultra 9 285K', 'brand' => 'Intel', 'price' => 629, 'attrs' => ['Socket' => 'LGA 1851', 'Supported RAM Types' => ['DDR5'], 'CPU Cores' => '24 Cores']],
//                 ['name' => 'Processor AMD Ryzen 9 9950X', 'brand' => 'AMD', 'price' => 649, 'attrs' => ['Socket' => 'AM5', 'Supported RAM Types' => ['DDR5'], 'CPU Cores' => '16 Cores']],
//                 ['name' => 'Processor AMD Ryzen 7 9800X3D', 'brand' => 'AMD', 'price' => 499, 'attrs' => ['Socket' => 'AM5', 'Supported RAM Types' => ['DDR5'], 'CPU Cores' => '8 Cores']],
//                 ['name' => 'Processor AMD Ryzen 5 7600X', 'brand' => 'AMD', 'price' => 229, 'attrs' => ['Socket' => 'AM5', 'Supported RAM Types' => ['DDR5'], 'CPU Cores' => '6 Cores']],
//                 ['name' => 'Processor AMD Ryzen 9 5900X', 'brand' => 'AMD', 'price' => 349, 'attrs' => ['Socket' => 'AM4', 'Supported RAM Types' => ['DDR4'], 'CPU Cores' => '12 Cores']],
//                 ['name' => 'Processor AMD Ryzen 5 5600X', 'brand' => 'AMD', 'price' => 159, 'attrs' => ['Socket' => 'AM4', 'Supported RAM Types' => ['DDR4'], 'CPU Cores' => '6 Cores']],
//             ],
//             'motherboard' => [
//                 ['name' => 'Motherboard ASUS ROG Z790 Hero', 'brand' => 'ASUS', 'price' => 600, 'attrs' => ['Socket' => 'LGA 1700', 'RAM Type' => 'DDR5', 'Supported Generations' => ['Intel 12th Gen', 'Intel 13th Gen', 'Intel 14th Gen'], 'Motherboard Chipset' => 'Z790', 'Form Factor' => 'ATX']],
//                 ['name' => 'Motherboard MSI MAG B760 Tomahawk', 'brand' => 'MSI', 'price' => 190, 'attrs' => ['Socket' => 'LGA 1700', 'RAM Type' => 'DDR4', 'Supported Generations' => ['Intel 12th Gen', 'Intel 13th Gen', 'Intel 14th Gen'], 'Motherboard Chipset' => 'B760', 'Form Factor' => 'ATX']],
//                 ['name' => 'Motherboard Gigabyte Z690 AORUS', 'brand' => 'Gigabyte', 'price' => 250, 'attrs' => ['Socket' => 'LGA 1700', 'RAM Type' => 'DDR5', 'Supported Generations' => ['Intel 12th Gen', 'Intel 13th Gen'], 'Motherboard Chipset' => 'Z690', 'Form Factor' => 'ATX']],
//                 ['name' => 'Motherboard ASUS Prime H610M', 'brand' => 'ASUS', 'price' => 99, 'attrs' => ['Socket' => 'LGA 1700', 'RAM Type' => 'DDR4', 'Supported Generations' => ['Intel 12th Gen'], 'Motherboard Chipset' => 'H610', 'Form Factor' => 'Micro-ATX (mATX)']],
//                 ['name' => 'Motherboard MSI MEG Z890 Ace', 'brand' => 'MSI', 'price' => 699, 'attrs' => ['Socket' => 'LGA 1851', 'RAM Type' => 'DDR5', 'Supported Generations' => ['Intel Core Ultra (Series 2)'], 'Motherboard Chipset' => 'Z890', 'Form Factor' => 'ATX']],
//                 ['name' => 'Motherboard Gigabyte X870E Master', 'brand' => 'Gigabyte', 'price' => 450, 'attrs' => ['Socket' => 'AM5', 'RAM Type' => 'DDR5', 'Supported Generations' => ['AMD Ryzen 7000 Series', 'AMD Ryzen 8000/9000 Series'], 'Motherboard Chipset' => 'X870E', 'Form Factor' => 'ATX']],
//                 ['name' => 'Motherboard ASUS ROG B650E-F', 'brand' => 'ASUS', 'price' => 280, 'attrs' => ['Socket' => 'AM5', 'RAM Type' => 'DDR5', 'Supported Generations' => ['AMD Ryzen 7000 Series'], 'Motherboard Chipset' => 'B650E', 'Form Factor' => 'ATX']],
//                 ['name' => 'Motherboard MSI PRO A620M-E', 'brand' => 'MSI', 'price' => 85, 'attrs' => ['Socket' => 'AM5', 'RAM Type' => 'DDR5', 'Supported Generations' => ['AMD Ryzen 7000 Series'], 'Motherboard Chipset' => 'A620', 'Form Factor' => 'Micro-ATX (mATX)']],
//                 ['name' => 'Motherboard ASUS TUF X570-Plus', 'brand' => 'ASUS', 'price' => 199, 'attrs' => ['Socket' => 'AM4', 'RAM Type' => 'DDR4', 'Supported Generations' => ['AMD Ryzen 3000 Series', 'AMD Ryzen 5000 Series'], 'Motherboard Chipset' => 'X570', 'Form Factor' => 'ATX']],
//                 ['name' => 'Motherboard Gigabyte B550M DS3H', 'brand' => 'Gigabyte', 'price' => 110, 'attrs' => ['Socket' => 'AM4', 'RAM Type' => 'DDR4', 'Supported Generations' => ['AMD Ryzen 3000 Series', 'AMD Ryzen 5000 Series'], 'Motherboard Chipset' => 'B550', 'Form Factor' => 'Micro-ATX (mATX)']],
//             ],
//             'ram' => [
//                 ['name' => 'RAM Corsair Vengeance 32GB DDR5', 'brand' => 'Corsair', 'price' => 120, 'attrs' => ['RAM Type' => 'DDR5', 'RAM Capacity' => '32 GB (2x16GB)', 'RAM Speed (MHz)' => '6000 MHz']],
//                 ['name' => 'RAM G.Skill Trident Z5 64GB DDR5', 'brand' => 'G.Skill', 'price' => 240, 'attrs' => ['RAM Type' => 'DDR5', 'RAM Capacity' => '64 GB (2x32GB)', 'RAM Speed (MHz)' => '6400 MHz']],
//                 ['name' => 'RAM Kingston Fury 16GB DDR5', 'brand' => 'Kingston', 'price' => 80, 'attrs' => ['RAM Type' => 'DDR5', 'RAM Capacity' => '16 GB (2x8GB)', 'RAM Speed (MHz)' => '5200 MHz']],
//                 ['name' => 'RAM Crucial Pro 96GB DDR5', 'brand' => 'Crucial', 'price' => 350, 'attrs' => ['RAM Type' => 'DDR5', 'RAM Capacity' => '96 GB (2x48)', 'RAM Speed (MHz)' => '5600 MHz']],
//                 ['name' => 'RAM TeamGroup 32GB DDR5', 'brand' => 'TeamGroup', 'price' => 140, 'attrs' => ['RAM Type' => 'DDR5', 'RAM Capacity' => '32 GB (2x16GB)', 'RAM Speed (MHz)' => '7200 MHz']],
//                 ['name' => 'RAM Corsair Vengeance 32GB DDR4', 'brand' => 'Corsair', 'price' => 85, 'attrs' => ['RAM Type' => 'DDR4', 'RAM Capacity' => '32 GB (2x16GB)', 'RAM Speed (MHz)' => '3600 MHz']],
//                 ['name' => 'RAM G.Skill Ripjaws V 16GB DDR4', 'brand' => 'G.Skill', 'price' => 45, 'attrs' => ['RAM Type' => 'DDR4', 'RAM Capacity' => '16 GB (2x8GB)', 'RAM Speed (MHz)' => '3200 MHz']],
//                 ['name' => 'RAM Kingston Renegade 64GB DDR4', 'brand' => 'Kingston', 'price' => 160, 'attrs' => ['RAM Type' => 'DDR4', 'RAM Capacity' => '64 GB (2x32GB)', 'RAM Speed (MHz)' => '3600 MHz']],
//                 ['name' => 'RAM Silicon Power 8GB DDR4', 'brand' => 'Silicon Power', 'price' => 20, 'attrs' => ['RAM Type' => 'DDR4', 'RAM Capacity' => '8 GB (1x8GB)', 'RAM Speed (MHz)' => '3200 MHz']],
//                 ['name' => 'RAM Crucial Ballistix 16GB DDR4', 'brand' => 'Crucial', 'price' => 50, 'attrs' => ['RAM Type' => 'DDR4', 'RAM Capacity' => '16 GB (2x8GB)', 'RAM Speed (MHz)' => '3600 MHz']],
//             ],
//             'graphic_card' => [
//                 ['name' => 'Graphic Card NVIDIA RTX 4090', 'brand' => 'NVIDIA', 'price' => 1999, 'attrs' => ['GPU Chipset' => 'NVIDIA GeForce RTX 4090', 'VRAM Capacity' => '24 GB']],
//                 ['name' => 'Graphic Card NVIDIA RTX 4080 SUPER', 'brand' => 'NVIDIA', 'price' => 999, 'attrs' => ['GPU Chipset' => 'NVIDIA GeForce RTX 4080 SUPER', 'VRAM Capacity' => '16 GB']],
//                 ['name' => 'Graphic Card NVIDIA RTX 4070 Ti SUPER', 'brand' => 'NVIDIA', 'price' => 799, 'attrs' => ['GPU Chipset' => 'NVIDIA GeForce RTX 4070 Ti SUPER', 'VRAM Capacity' => '16 GB']],
//                 ['name' => 'Graphic Card NVIDIA RTX 4060', 'brand' => 'NVIDIA', 'price' => 299, 'attrs' => ['GPU Chipset' => 'NVIDIA GeForce RTX 4060', 'VRAM Capacity' => '8 GB']],
//                 ['name' => 'Graphic Card NVIDIA RTX 3060', 'brand' => 'NVIDIA', 'price' => 249, 'attrs' => ['GPU Chipset' => 'NVIDIA GeForce RTX 4060', /* using 4060 as fallback */ 'VRAM Capacity' => '12 GB']],
//                 ['name' => 'Graphic Card AMD RX 7900 XTX', 'brand' => 'AMD', 'price' => 950, 'attrs' => ['GPU Chipset' => 'AMD Radeon RX 7900 XTX', 'VRAM Capacity' => '24 GB']],
//                 ['name' => 'Graphic Card AMD RX 7900 XT', 'brand' => 'AMD', 'price' => 750, 'attrs' => ['GPU Chipset' => 'AMD Radeon RX 7900 XT', 'VRAM Capacity' => '20 GB']],
//                 ['name' => 'Graphic Card AMD RX 7800 XT', 'brand' => 'AMD', 'price' => 499, 'attrs' => ['GPU Chipset' => 'AMD Radeon RX 7800 XT', 'VRAM Capacity' => '16 GB']],
//                 ['name' => 'Graphic Card AMD RX 7600', 'brand' => 'AMD', 'price' => 269, 'attrs' => ['GPU Chipset' => 'AMD Radeon RX 7600', 'VRAM Capacity' => '8 GB']],
//                 ['name' => 'Graphic Card AMD RX 6700 XT', 'brand' => 'AMD', 'price' => 320, 'attrs' => ['GPU Chipset' => 'AMD Radeon RX 7600', /* fallback */ 'VRAM Capacity' => '12 GB']],
//             ],
//             'power_supply' => [
//                 ['name' => 'Power Supply Corsair RM1000x', 'brand' => 'Corsair', 'price' => 180, 'attrs' => ['PSU Wattage' => '1000W', 'PSU Rating & Modularity' => '80+ Gold Full-Modular']],
//                 ['name' => 'Power Supply EVGA SuperNOVA 850W', 'brand' => 'EVGA', 'price' => 140, 'attrs' => ['PSU Wattage' => '850W', 'PSU Rating & Modularity' => '80+ Gold Full-Modular']],
//                 ['name' => 'Power Supply Seasonic Focus 750W', 'brand' => 'Seasonic', 'price' => 120, 'attrs' => ['PSU Wattage' => '750W', 'PSU Rating & Modularity' => '80+ Gold Full-Modular']],
//                 ['name' => 'Power Supply Thermaltake 650W', 'brand' => 'Thermaltake', 'price' => 80, 'attrs' => ['PSU Wattage' => '650W', 'PSU Rating & Modularity' => '80+ Bronze Non-Modular']],
//                 ['name' => 'Power Supply MSI MEG 1300W', 'brand' => 'MSI', 'price' => 290, 'attrs' => ['PSU Wattage' => '1200W', 'PSU Rating & Modularity' => '80+ Platinum Full-Modular']],
//                 ['name' => 'Power Supply Be Quiet! 550W', 'brand' => 'Be Quiet!', 'price' => 60, 'attrs' => ['PSU Wattage' => '550W', 'PSU Rating & Modularity' => '80+ Bronze Non-Modular']],
//                 ['name' => 'Power Supply ASUS ROG Thor 1600W', 'brand' => 'ASUS', 'price' => 450, 'attrs' => ['PSU Wattage' => '1600W', 'PSU Rating & Modularity' => '80+ Titanium Full-Modular']],
//                 ['name' => 'Power Supply Gigabyte 850W', 'brand' => 'Gigabyte', 'price' => 110, 'attrs' => ['PSU Wattage' => '850W', 'PSU Rating & Modularity' => '80+ Gold Full-Modular']],
//                 ['name' => 'Power Supply Cooler Master 750W', 'brand' => 'Cooler Master', 'price' => 95, 'attrs' => ['PSU Wattage' => '750W', 'PSU Rating & Modularity' => '80+ Bronze Non-Modular']],
//                 ['name' => 'Power Supply XPG Core 1000W', 'brand' => 'XPG', 'price' => 150, 'attrs' => ['PSU Wattage' => '1000W', 'PSU Rating & Modularity' => '80+ Gold Full-Modular']],
//             ]
//         ];

//         // ⚡ جلب كافة قيم الخصائص لسرعة ربطها ⚡
//         $allValues = AttributeValue::with('attribute')->get();
//         $getValueId = function ($attrName, $valName) use ($allValues) {
//             // تنظيف النص لتفادي أخطاء المسافات
//             $cleanAttr = trim($attrName);
//             $cleanVal = trim($valName);

//             $item = $allValues->first(function ($v) use ($cleanAttr, $cleanVal) {
//                 // للتعامل مع الفروق البسيطة في الأسماء بالـ Seeder السابق
//                 $dbAttr = trim($v->attribute?->name);
//                 $dbVal = trim($v->value);

//                 // مطابقة جزئية ذكية لتفادي أي خطأ إملائي من الـ Seeder الأول
//                 return $v->attribute && str_contains($dbAttr, $cleanAttr) && str_contains($dbVal, $cleanVal);
//             });
//             return $item ? $item->id : null;
//         };

//         foreach ($sectionsBlueprint as $catKey => $catData) {
//             $category = Category::firstOrCreate(
//                 ['slug' => Str::slug($catData['category_translations']['en'])],
//                 ['name' => $catData['category_translations']]
//             );

//             foreach ($catData['sections'] as $secData) {
//                 $section = Section::firstOrCreate(
//                     ['slug' => Str::slug($secData['en'])],
//                     [
//                         'category_id' => $category->id,
//                         'name' => ['en' => $secData['en'], 'ar' => $secData['ar']]
//                     ]
//                 );

//                 $rawSnakeType = Str::snake(Str::plural($secData['en']));
//                 $componentType = $componentTypeMap[$rawSnakeType] ?? null;

//                 // ==========================================
//                 // ربط الخصائص بالقسم تلقائياً (بناءً على الماب)
//                 // ==========================================
//                 if (isset($sectionAttributesMap[$rawSnakeType])) {
//                     $attrNames = $sectionAttributesMap[$rawSnakeType];
//                     $attrIds = Attribute::whereIn('name', $attrNames)->pluck('id')->toArray();
//                     $section->attributes()->sync($attrIds);
//                 }

//                 // ==========================================
//                 // زراعة المنتجات (10 منتجات حقيقية لكل قسم)
//                 // ==========================================
//                 if (isset($realisticData[$componentType])) {
//                     foreach ($realisticData[$componentType] as $prodMeta) {

//                         $brandSlug = Str::slug($prodMeta['brand'] . '-' . $secData['en']);
//                         $brand = Brand::firstOrCreate(
//                             ['slug' => $brandSlug],
//                             ['section_id' => $section->id, 'name' => ['en' => $prodMeta['brand'], 'ar' => $prodMeta['brand']]]
//                         );

//                         $productSlug = Str::slug($prodMeta['name'] . '-' . rand(100, 999));

//                         $product = Product::firstOrCreate(
//                             ['slug' => $productSlug],
//                             [
//                                 'section_id' => $section->id,
//                                 'brand_id' => $brand->id,
//                                 'name' => $prodMeta['name'],
//                                 'description' => "Premium {$componentType} component. Verified Asset ID: NODE-" . rand(1000, 9999),
//                                 'price' => $prodMeta['price'],
//                                 'stock' => rand(10, 50),
//                                 'is_active' => true,
//                                 'images' => $defaultImages,
//                                 'colors' => ['Black'],
//                                 'details' => ['Quality: High Grade', 'Warranty: 2 Years'],
//                                 'component_type' => $componentType
//                             ]
//                         );

//                         // ربط القيم الفنية للمنتجات الواقعية
//                         if (isset($prodMeta['attrs'])) {
//                             $attachIds = [];
//                             foreach ($prodMeta['attrs'] as $attrName => $attrValue) {
//                                 if (is_array($attrValue)) {
//                                     foreach ($attrValue as $v) {
//                                         $id = $getValueId($attrName, $v);
//                                         if ($id) $attachIds[] = $id;
//                                     }
//                                 } else {
//                                     $id = $getValueId($attrName, $attrValue);
//                                     if ($id) $attachIds[] = $id;
//                                 }
//                             }
//                             $product->attributeValues()->sync($attachIds);
//                         }
//                     }
//                 }
//             }
//         }
//     }
// }







namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Section;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;

class StoreHierarchySeeder extends Seeder
{
    public function run()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $defaultImages = ['/storage/products/PC_1.webp', '/storage/products/PC_2.webp'];

        $sectionsBlueprint = [
            'Component' => [
                'category_translations' => ['en' => 'Components', 'ar' => 'قطع الغيار'],
                'sections' => [
                    ['en' => 'Processors', 'ar' => 'المعالجات'],
                    ['en' => 'Motherboards', 'ar' => 'اللوحات الأم'],
                    ['en' => 'RAM', 'ar' => 'الرامات'],
                    ['en' => 'Graphic Cards', 'ar' => 'كروت الشاشة'],
                    ['en' => 'CPU Coolers', 'ar' => 'المبردات']
                ]
            ]
        ];

        $componentTypeMap = [
            'processors' => 'cpu',
            'motherboards' => 'motherboard',
            'rams' => 'ram',
            'graphic_cards' => 'graphic_card',
            'cpu_coolers' => 'cooling_system'
        ];

        $sectionAttributesMap = [
            'processors' => ['Socket', 'Generation', 'Supported RAM Types'],
            'motherboards' => ['Socket', 'RAM Type', 'Supported Generations', 'Form Factor'],
            'rams' => ['RAM Type'],
            'cpu_coolers' => ['Supported Sockets']
        ];

        $realisticData = [
            'cpu' => [
                ['name' => 'Processor Intel Core i9-14900K', 'brand' => 'Intel', 'price' => 599, 'attrs' => ['Socket' => 'LGA 1700', 'Generation' => 'Intel 14th Gen', 'Supported RAM Types' => ['DDR4', 'DDR5']]],
                ['name' => 'Processor Intel Core i5-12400F', 'brand' => 'Intel', 'price' => 149, 'attrs' => ['Socket' => 'LGA 1700', 'Generation' => 'Intel 12th Gen', 'Supported RAM Types' => ['DDR4', 'DDR5']]],
                ['name' => 'Processor AMD Ryzen 9 9950X', 'brand' => 'AMD', 'price' => 649, 'attrs' => ['Socket' => 'AM5', 'Generation' => 'AMD Ryzen 9000 Series', 'Supported RAM Types' => ['DDR5']]],
                ['name' => 'Processor AMD Ryzen 5 5600X', 'brand' => 'AMD', 'price' => 159, 'attrs' => ['Socket' => 'AM4', 'Generation' => 'AMD Ryzen 5000 Series', 'Supported RAM Types' => ['DDR4']]],
            ],
            'motherboard' => [
                ['name' => 'Motherboard ASUS ROG Z790 Hero', 'brand' => 'ASUS', 'price' => 600, 'attrs' => ['Socket' => 'LGA 1700', 'RAM Type' => 'DDR5', 'Supported Generations' => ['Intel 12th Gen', 'Intel 13th Gen', 'Intel 14th Gen'], 'Form Factor' => 'ATX']],
                ['name' => 'Motherboard MSI B760 Tomahawk', 'brand' => 'MSI', 'price' => 190, 'attrs' => ['Socket' => 'LGA 1700', 'RAM Type' => 'DDR4', 'Supported Generations' => ['Intel 12th Gen', 'Intel 13th Gen', 'Intel 14th Gen'], 'Form Factor' => 'ATX']],
                ['name' => 'Motherboard Gigabyte X870E', 'brand' => 'Gigabyte', 'price' => 450, 'attrs' => ['Socket' => 'AM5', 'RAM Type' => 'DDR5', 'Supported Generations' => ['AMD Ryzen 7000 Series', 'AMD Ryzen 9000 Series'], 'Form Factor' => 'ATX']],
                ['name' => 'Motherboard MSI B550', 'brand' => 'MSI', 'price' => 120, 'attrs' => ['Socket' => 'AM4', 'RAM Type' => 'DDR4', 'Supported Generations' => ['AMD Ryzen 5000 Series'], 'Form Factor' => 'Micro-ATX (mATX)']],
            ],
            'ram' => [
                ['name' => 'RAM Corsair 32GB DDR5', 'brand' => 'Corsair', 'price' => 120, 'attrs' => ['RAM Type' => 'DDR5']],
                ['name' => 'RAM G.Skill 16GB DDR5', 'brand' => 'G.Skill', 'price' => 70, 'attrs' => ['RAM Type' => 'DDR5']],
                ['name' => 'RAM Corsair 32GB DDR4', 'brand' => 'Corsair', 'price' => 85, 'attrs' => ['RAM Type' => 'DDR4']],
                ['name' => 'RAM Crucial 16GB DDR4', 'brand' => 'Crucial', 'price' => 45, 'attrs' => ['RAM Type' => 'DDR4']],
            ],
            'cooling_system' => [
                ['name' => 'Cooler NZXT Kraken 360', 'brand' => 'NZXT', 'price' => 180, 'attrs' => ['Supported Sockets' => ['LGA 1700', 'AM5', 'AM4']]],
                ['name' => 'Cooler Noctua NH-D15', 'brand' => 'Noctua', 'price' => 110, 'attrs' => ['Supported Sockets' => ['LGA 1700', 'LGA 1200', 'AM5', 'AM4']]],
            ]
        ];

        $allValues = AttributeValue::with('attribute')->get();
        $getValueId = function ($attrName, $valName) use ($allValues) {
            $item = $allValues->first(function ($v) use ($attrName, $valName) {
                return $v->attribute && strtolower(trim($v->attribute->name)) === strtolower(trim($attrName)) && strtolower(trim($v->value)) === strtolower(trim($valName));
            });
            return $item ? $item->id : null;
        };

        foreach ($sectionsBlueprint as $catData) {
            $category = Category::firstOrCreate(['slug' => Str::slug($catData['category_translations']['en'])], ['name' => $catData['category_translations']]);

            foreach ($catData['sections'] as $secData) {
                $section = Section::firstOrCreate(['slug' => Str::slug($secData['en'])], ['category_id' => $category->id, 'name' => ['en' => $secData['en'], 'ar' => $secData['ar']]]);

                $rawSnakeType = Str::snake(Str::plural($secData['en']));
                $componentType = $componentTypeMap[$rawSnakeType] ?? null;

                if (isset($sectionAttributesMap[$rawSnakeType])) {
                    $attrIds = Attribute::whereIn('name', $sectionAttributesMap[$rawSnakeType])->pluck('id')->toArray();
                    $section->attributes()->sync($attrIds);
                }

                if (isset($realisticData[$componentType])) {
                    foreach ($realisticData[$componentType] as $prodMeta) {
                        $brand = Brand::firstOrCreate(['slug' => Str::slug($prodMeta['brand'] . '-' . $secData['en'])], ['section_id' => $section->id, 'name' => ['en' => $prodMeta['brand'], 'ar' => $prodMeta['brand']]]);

                        $product = Product::firstOrCreate(['slug' => Str::slug($prodMeta['name'] . '-' . rand(10, 99))], [
                            'section_id' => $section->id,
                            'brand_id' => $brand->id,
                            'name' => $prodMeta['name'],
                            'description' => "Asset NODE-" . rand(100, 999),
                            'price' => $prodMeta['price'],
                            'stock' => 50,
                            'is_active' => true,
                            'images' => $defaultImages,
                            'component_type' => $componentType
                        ]);

                        if (isset($prodMeta['attrs'])) {
                            $attachIds = [];
                            foreach ($prodMeta['attrs'] as $attrName => $attrValue) {
                                if (is_array($attrValue)) {
                                    foreach ($attrValue as $v) {
                                        if ($id = $getValueId($attrName, $v)) $attachIds[] = $id;
                                    }
                                } else {
                                    if ($id = $getValueId($attrName, $attrValue)) $attachIds[] = $id;
                                }
                            }
                            $product->attributeValues()->sync($attachIds);
                        }
                    }
                }
            }
        }
    }
}
