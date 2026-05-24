<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting; // تأكد من أن اسم الموديل الخاص بك هو Setting

class SettingsSeeder extends Seeder
{
    public function run()
    {
        // مصفوفة تحتوي على جميع الإعدادات بقيم احترافية لمتجر الهاردوير
        $settings = [
            // ==========================================
            // 1. About Us Page Data
            // ==========================================
            [
                'key' => 'about_who_we_are',
                'value' => 'We are the ultimate destination for PC enthusiasts, gamers, and creative professionals. We specialize in providing top-tier hardware, custom PC builds, and cutting-edge tech accessories. Our mission is to empower your digital experience with unparalleled performance and reliability.'
            ],
            [
                'key' => 'about_history',
                'value' => 'Founded in 2018, what started as a small passion project by a group of hardware overclockers has evolved into a leading tech retailer. Over the years, we have built thousands of custom workstations and earned the trust of the local gaming community.'
            ],
            [
                'key' => 'about_foundation',
                'value' => 'Our foundation rests on three core pillars: Uncompromising Quality, Expert Knowledge, and Absolute Transparency. We don’t just sell boxes; we test, benchmark, and guarantee the performance of every component that leaves our store.'
            ],
            [
                'key' => 'about_agents',
                'value' => 'We are proud to be authorized resellers and partners with global industry titans including ASUS ROG, NVIDIA, Intel, AMD, Corsair, and Cooler Master. This ensures every product is authentic and fully backed by official warranties.'
            ],

            // ==========================================
            // 2. Contact Page Data
            // ==========================================
            [
                'key' => 'contact_address',
                'value' => 'Tech District, Al-Bahsa Street, Damascus, Syria'
            ],
            [
                'key' => 'contact_email',
                'value' => 'support@premium-hardware.com'
            ],
            [
                'key' => 'contact_phone_1',
                'value' => '+963 11 123 4567'
            ],
            [
                'key' => 'contact_phone_2',
                'value' => '+963 999 123 456'
            ],
            [
                'key' => 'contact_whatsapp',
                'value' => '+963999123456' // أرقام متصلة لكي يعمل رابط الواتساب برمجياً
            ],
            [
                'key' => 'contact_hall_location',
                'value' => 'Main Showroom: Tech Tower, Ground Floor, Damascus. Open daily from 10:00 AM to 8:00 PM.'
            ],
            [
                'key' => 'contact_map_url',
                // رابط تضمين Google Maps (مثال لدمشق)
                'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d106432.22857467364!2d36.216667!3d33.513056!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1518e6dc413cc6a7%3A0x6b9f66ebd1e394f2!2sDamascus%2C%20Syria!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s'
            ],

            // ==========================================
            // 3. Footer Data
            // ==========================================
            [
                'key' => 'footer_branches',
                'value' => 'Damascus (Main HQ) | Rural Damascus (Warehouse) | Aleppo (Showroom)'
            ],
            [
                'key' => 'footer_phones',
                'value' => 'Sales: +963 11 123 4567 | Technical Support: +963 999 123 456'
            ],

            // ==========================================
            // 4. Social Media Links
            // ==========================================
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/PremiumHardware'
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/PremiumHardware'
            ],
            [
                'key' => 'social_linkedin',
                'value' => 'https://linkedin.com/company/premium-hardware'
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://x.com/PremiumHardware'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']], 
                ['value' => $setting['value']] 
            );
        }
    }
}