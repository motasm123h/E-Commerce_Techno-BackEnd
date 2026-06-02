<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        // مصفوفة الإعدادات العامة مترجمة لدعم اللغتين بالتوازي
        $settings = [
            // ==========================================
            // 1. About Us Content CMS
            // ==========================================
            [
                'key' => 'about_who_we_are',
                'value' => [
                    'en' => 'We are the ultimate destination for PC enthusiasts, gamers, and creative professionals. We specialize in providing top-tier hardware, custom PC builds, and cutting-edge tech accessories. Our mission is to empower your digital experience with unparalleled performance and reliability.',
                    'ar' => 'نحن الوجهة الأولى لعشاق الحواسب المخصصة، اللاعبين، والمحترفين المبدعين في سوريا. نتخصص في تقديم قطع الهاردوير الاحترافية، تجميعات الحواسب المخصصة، وإكسسوارات الألعاب المتطورة. رسالتنا هي تعزيز تجربتك الرقمية بأداء وموثوقية لا مثيل لهما.'
                ]
            ],
            [
                'key' => 'about_history',
                'value' => [
                    'en' => 'Founded in 2018, what started as a small passion project by a group of hardware overclockers has evolved into a leading tech retailer. Over the years, we have built thousands of custom workstations and earned the trust of the local gaming community.',
                    'ar' => 'تأسست الشركة في عام 2018، حيث بدأت كمشروع شغوف صغير من قِبل مجموعة من الخبراء في كسر سرعة المعالجات والهاردوير وتطورت لتصبح متجراً ريادياً. على مدار السنوات، قمنا ببناء آلاف محطات العمل الاحترافية وحصلنا على ثقة مجتمع الألعاب المحلي.'
                ]
            ],
            [
                'key' => 'about_foundation',
                'value' => [
                    'en' => 'Our foundation rests on three core pillars: Uncompromising Quality, Expert Knowledge, and Absolute Transparency. We don’t just sell boxes; we test, benchmark, and guarantee the performance of every component that leaves our store.',
                    'ar' => 'تأسست ركائزنا على ثلاثة مبادئ أساسية: الجودة الصارمة، المعرفة العميقة، والشفافية المطلقة. نحن لا نبيع مجرد صناديق مغلقة؛ بل نختبر، ونحلل الأداء، ونضمن كفاءة كل قطعة هاردوير تخرج من متجرنا.'
                ]
            ],
            [
                'key' => 'about_agents',
                'value' => [
                    'en' => 'We are proud to be authorized resellers and partners with global industry titans including ASUS ROG, GIGABYTE, MSI, NVIDIA, Intel, AMD, and Corsair. This ensures every product is authentic and fully backed by official warranties.',
                    'ar' => 'فخورون بكوننا موزعين معتمدين وشركاء لأبرز عمالقة التكنولوجيا في العالم بما في ذلك ASUS ROG، وGIGABYTE، وMSI، وNVIDIA، وIntel، وAMD، وCorsair. هذا يضمن أن كل منتج أصلي ومحمي بالكامل بالضمان الرسمي.'
                ]
            ],

            // ==========================================
            // 2. Contact Info & Locations
            // ==========================================
            [
                'key' => 'contact_address',
                'value' => [
                    'en' => 'Tech District, Al-Bahsa Street, Damascus, Syria',
                    'ar' => 'منطقة التكنولوجيا، شارع البحصة، دمشق، سوريا'
                ]
            ],
            [
                'key' => 'contact_email',
                'value' => 'support@premium-hardware.com',
            ],
            [
                'key' => 'contact_phone_1',
                'value' => '+963 11 123 4567',
                // 'value' => [
                //     'en' => '+963 11 123 4567',
                //     'ar' => '+963 11 123 4567'
                // ]
            ],
            [
                'key' => 'contact_phone_2',
                'value' => '+963 999 123 456',
                // 'value' => [
                //     'en' => '+963 999 123 456',
                //     'ar' => '+963 999 123 456'
                // ]
            ],
            [
                'key' => 'contact_whatsapp',
                'value' => '+963999123456',
                // 'value' => [
                //     'en' => '+963999123456',
                //     'ar' => '+963999123456'
                // ]
            ],
            [
                'key' => 'contact_hall_location',
                'value' => [
                    'en' => 'Main Showroom: Tech Tower, Ground Floor, Damascus. Open daily from 10:00 AM to 8:00 PM.',
                    'ar' => 'صالة العرض الرئيسية: برج التكنولوجيا، الطابق الأرضي، دمشق. نفتح يومياً من الساعة 10:00 صباحاً حتى 8:00 مساءً.'
                ]
            ],
            [
                'key' => 'contact_map_url',
                'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3326.313885375549!2d36.2934272!3d33.511394!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1518e6921b764d0d%3A0x6bbaedcf94b00b41!2z2KfZhNio2K3YtdmH2Iwg2K_Zhdi02YI!5e0!3m2!1sar!2ssy!4v1716999999999!5m2!1sar!2ssy'
            ],

            // ==========================================
            // 3. Footer Data
            // ==========================================
            [
                'key' => 'footer_branches',
                'value' => [
                    'en' => "Damascus, Al-Bahsa (Main HQ)\nRural Damascus (Warehouse)\nAleppo, Center (Showroom)",
                    'ar' => "دمشق، شارع البحصة (المقر الرئيسي)\nريف دمشق (المستودع المركزي)\nحلب، المركز (صالة العرض)"
                ]
            ],
            [
                'key' => 'footer_phones',
                'value' => [
                    'en' => "Sales: +963 11 123 4567\nTechnical Support: +963 999 123 456",
                    'ar' => "المبيعات: +963 11 123 4567\nالدعم الفني: +963 999 123 456"
                ]
            ],


            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/PremiumHardware'
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/PremiumHardware'
            ],
            [
                'key' => 'social_youtube',
                'value' => 'https://youtube.com/@PremiumHardware'
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

// كيف تحصل على رابط خاص بموقع متجرك الفعلي مستقبلاً؟
// افتح Google Maps وابحث عن موقع متجرك.

// اضغط على زر مشاركة (Share).

// اختر تبويب تضمين خريطة (Embed a map).

// سيظهر لك كود <iframe> كامل، قم بنسخ الرابط الموجود داخل علامتي الاقتباس الخاصة بحقل الـ src="..." فقط وحصرياً، وضعه في لوحة التحكم.