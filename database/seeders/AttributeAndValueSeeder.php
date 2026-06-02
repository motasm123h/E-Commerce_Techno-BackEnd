<?php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use App\Models\Attribute;
// use App\Models\AttributeValue;

// class AttributeAndValueSeeder extends Seeder
// {
//     public function run()
//     {
//         $blueprint = [
//             // ==========================================
//             // 1. Sockets (The Physical Connection)
//             // ==========================================
//             [
//                 'attribute' => 'Socket',
//                 'type' => 'select',
//                 'values' => [
//                     'LGA 1200', // Intel 10th/11th Gen
//                     'LGA 1700', // Intel 12th/13th/14th Gen
//                     'LGA 1851', // Intel Core Ultra (800 Series)
//                     'AM4',      // AMD Ryzen 3000/5000
//                     'AM5'       // AMD Ryzen 7000/8000/9000
//                 ]
//             ],
//             // For CPU Coolers that support multiple sockets
//             [
//                 'attribute' => 'Supported Sockets',
//                 'type' => 'checkbox',
//                 'values' => ['LGA 1200', 'LGA 1700', 'LGA 1851', 'AM4', 'AM5']
//             ],

//             // ==========================================
//             // 2. Motherboard Chipsets
//             // ==========================================
//             [
//                 'attribute' => 'Motherboard Chipset',
//                 'type' => 'select',
//                 'values' => [
//                     // Intel 400, 500, 600, 700, 800 Series
//                     'Z490',
//                     'B460',
//                     'H410',
//                     'Z590',
//                     'B560',
//                     'H510',
//                     'Z690',
//                     'B660',
//                     'H610',
//                     'Z790',
//                     'B760',
//                     'H770',
//                     'Z890',
//                     'B860',
//                     'H810',
//                     // AMD 400, 500, 600, 800 Series
//                     'X470',
//                     'B450',
//                     'X570',
//                     'B550',
//                     'A520',
//                     'X670E',
//                     'X670',
//                     'B650E',
//                     'B650',
//                     'A620',
//                     'A620A',
//                     'X870E',
//                     'X870',
//                     'B850',
//                     'B840'
//                 ]
//             ],

//             // ==========================================
//             // 3. Supported CPU Generations (For Motherboards)
//             // ==========================================
//             [
//                 'attribute' => 'Supported Generations',
//                 'type' => 'checkbox', // A motherboard can support multiple generations
//                 'values' => [
//                     'Intel 10th Gen',
//                     'Intel 11th Gen',
//                     'Intel 12th Gen',
//                     'Intel 13th Gen',
//                     'Intel 14th Gen',
//                     'Intel Core Ultra (Series 2)',
//                     'AMD Ryzen 3000 Series',
//                     'AMD Ryzen 5000 Series',
//                     'AMD Ryzen 7000 Series',
//                     'AMD Ryzen 8000/9000 Series'
//                 ]
//             ],

//             // ==========================================
//             // 4. Memory (RAM) Specifications
//             // ==========================================
//             [
//                 'attribute' => 'RAM Type',
//                 'type' => 'select',
//                 'values' => ['DDR4', 'DDR5']
//             ],
//             [
//                 'attribute' => 'Supported RAM Types',
//                 'type' => 'checkbox',
//                 'values' => ['DDR4', 'DDR5']
//             ],
//         ];

//         foreach ($blueprint as $item) {
//             $attribute = Attribute::firstOrCreate(
//                 ['name' => $item['attribute']],
//                 ['type' => $item['type']]
//             );

//             foreach ($item['values'] as $valueString) {
//                 AttributeValue::firstOrCreate([
//                     'attribute_id' => $attribute->id,
//                     'value' => $valueString
//                 ]);
//             }
//         }
//     }
// }






namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeAndValueSeeder extends Seeder
{
    public function run()
    {
        $blueprint = [
            [
                'attribute' => 'Socket',
                'type' => 'select',
                'values' => ['LGA 1700', 'LGA 1200', 'LGA 1851', 'AM5', 'AM4']
            ],
            [
                'attribute' => 'Generation',
                'type' => 'select',
                'values' => [
                    'Intel 12th Gen',
                    'Intel 13th Gen',
                    'Intel 14th Gen',
                    'Intel Core Ultra',
                    'AMD Ryzen 5000 Series',
                    'AMD Ryzen 7000 Series',
                    'AMD Ryzen 9000 Series'
                ]
            ],
            [
                'attribute' => 'Supported Generations',
                'type' => 'checkbox',
                'values' => [
                    'Intel 12th Gen',
                    'Intel 13th Gen',
                    'Intel 14th Gen',
                    'Intel Core Ultra',
                    'AMD Ryzen 5000 Series',
                    'AMD Ryzen 7000 Series',
                    'AMD Ryzen 9000 Series'
                ]
            ],
            [
                'attribute' => 'Supported Sockets',
                'type' => 'checkbox',
                'values' => ['LGA 1700', 'LGA 1200', 'LGA 1851', 'AM5', 'AM4']
            ],
            [
                'attribute' => 'RAM Type',
                'type' => 'select',
                'values' => ['DDR4', 'DDR5']
            ],
            [
                'attribute' => 'Supported RAM Types',
                'type' => 'checkbox',
                'values' => ['DDR4', 'DDR5']
            ],
            [
                'attribute' => 'Form Factor',
                'type' => 'select',
                'values' => ['ATX', 'Micro-ATX (mATX)', 'Mini-ITX']
            ],
            [
                'attribute' => 'Supported Form Factors',
                'type' => 'checkbox',
                'values' => ['ATX', 'Micro-ATX (mATX)', 'Mini-ITX']
            ]
        ];

        foreach ($blueprint as $item) {
            $attribute = Attribute::firstOrCreate(
                ['name' => $item['attribute']],
                ['type' => $item['type']]
            );

            foreach ($item['values'] as $valueString) {
                AttributeValue::firstOrCreate([
                    'attribute_id' => $attribute->id,
                    'value' => $valueString
                ]);
            }
        }
    }
}
