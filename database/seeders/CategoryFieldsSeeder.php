<?php

namespace Database\Seeders;

use App\Models\CategoryField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $realEstateFields = [
            [
                'category_slug' => 'real_estate',
                'field_name' => 'property_type',
                'display_name' => 'نوع العقار',
                'type' => 'string',
                'options' => ['فيلا', 'شقة', 'أرض', 'استوديو', 'محل تجاري', 'مكتب', 'أخرى'],
                'required' => true,
                'filterable' => true,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'real_estate',
                'field_name' => 'contract_type',
                'display_name' => 'نوع العقد',
                'type' => 'string',
                'options' => ['بيع', 'إيجار'],
                'required' => true,
                'filterable' => true,
                'sort_order' => 2,
            ],
        ];
        $carsRentFields = [
            [
                'category_slug' => 'cars_rent',
                'field_name' => 'year',
                'display_name' => 'السنة',
                'type' => 'string',
                'options' => range(2000, 2030),
                'required' => true,
                'filterable' => true,
                'sort_order' => 3,
            ],
            [
                'category_slug' => 'cars_rent',
                'field_name' => 'driver_option',
                'display_name' => 'السائق',
                'type' => 'string',
                'options' => ['بدون سائق', 'بسائق'],
                'required' => true,
                'filterable' => true,
                'sort_order' => 4,
            ],
        ];
        $animalsFields = [
            [
                'category_slug' => 'animals',
                'field_name' => 'main_category',
                'display_name' => 'القسم الرئيسي',
                'type' => 'string',
                'options' => ['طيور', 'حيوانات أليفة', 'إكسسوارات', 'أعلاف', 'أخرى'],
                'required' => true,
                'filterable' => true,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'animals',
                'field_name' => 'sub_category',
                'display_name' => 'القسم الفرعي',
                'type' => 'string',
                'options' => ['ببغاء', 'حمام', 'قطط', 'كلاب', 'أسماك', 'أرانب', 'معدات تربية'],
                'required' => true,
                'filterable' => true,
                'sort_order' => 2,
            ],
        ];
        $jobsFields = [
            [
                'category_slug' => 'jobs',
                'field_name' => 'job_category',
                'display_name' => 'التصنيف',
                'type' => 'string',
                'options' => [
                    'إدارة',
                    'محاسبة ومالية',
                    'مبيعات',
                    'تسويق',
                    'تكنولوجيا معلومات',
                    'تعليم وتدريب',
                    'طب وتمريض',
                    'خدمة عملاء',
                    'حرف وصناعات',
                    'سياحة وفنادق',
                ],
                'required' => true,
                'filterable' => true,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'jobs',
                'field_name' => 'specialization',
                'display_name' => 'التخصص',
                'type' => 'string',
                'options' => [
                    'محاسب',
                    'مسؤول موارد بشرية',
                    'مندوب مبيعات',
                    'مسوّق رقمي',
                    'مبرمج',
                    'مصمم جرافيك',
                    'مدرس',
                    'ممرض',
                    'سكرتارية',
                    'سائق',
                ],
                'required' => true,
                'filterable' => true,
                'sort_order' => 2,
            ],
            [
                'category_slug' => 'jobs',
                'field_name' => 'salary',
                'display_name' => 'الراتب',
                'type' => 'decimal',    // أو int حسب ما تحبي
                'options' => [],           // مفيش اختيارات
                'required' => true,
                'filterable' => false,
                'rules_json' => ['min:0'],    // عشان مايدخلش رقم سالب
                'sort_order' => 3,
            ],
            [
                'category_slug' => 'jobs',
                'field_name' => 'contact_via',
                'display_name' => 'التواصل عبر',
                'type' => 'string',
                'options' => [],           // برضه حر
                'required' => true,
                'filterable' => false,        // مش لازم أفلتر بيه
                'sort_order' => 4,
            ],
        ];


        $carFields = [
            [
                'category_slug' => 'cars',
                'field_name' => 'year',
                'display_name' => 'السنة',
                'type' => 'string',
                'options' => range(1990, 2025),
                'required' => true,
                'filterable' => true,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'cars',
                'field_name' => 'kilometers',
                'display_name' => 'الكيلو متر',
                'type' => 'string',
                'options' => [
                    '0 - 10،000',
                    '10،000 - 50،000',
                    '50،000 - 100،000',
                    '100،000 - 200،000',
                    'أكثر من 200،000',
                ],
                'required' => true,
                'filterable' => true,
                'sort_order' => 2,
            ],
            [
                'category_slug' => 'cars',
                'field_name' => 'fuel_type',
                'display_name' => 'نوع الوقود',
                'type' => 'string',
                'options' => ['بنزين', 'ديزل', 'غاز', 'كهرباء', 'أخرى'],
                'required' => true,
                'filterable' => true,
                'sort_order' => 3,
            ],
            [
                'category_slug' => 'cars',
                'field_name' => 'transmission',
                'display_name' => 'الفتيس',
                'type' => 'string',
                'options' => ['أوتوماتيك', 'مانيوال'],
                'required' => true,
                'filterable' => true,
                'sort_order' => 4,
            ],
            [
                'category_slug' => 'cars',
                'field_name' => 'exterior_color',
                'display_name' => 'اللون الخارجي',
                'type' => 'string',
                'options' => ['أبيض', 'أسود', 'أزرق', 'رمادي', 'فضي', 'أحمر', 'أخرى'],
                'required' => true,
                'filterable' => true,
                'sort_order' => 5,
            ],
            [
                'category_slug' => 'cars',
                'field_name' => 'type',
                'display_name' => 'النوع',
                'type' => 'string',
                'options' => [
                    'سيدان',
                    'هاتشباك',
                    'SUV',
                    'كروس أوفر',
                    'بيك أب',
                    'كوبيه',
                    'كشف',
                ],
                'required' => true,
                'filterable' => true,
                'sort_order' => 6,
            ],
        ];

        $allFields = array_merge($realEstateFields, $carFields, $carsRentFields, $animalsFields, $jobsFields);

        foreach ($allFields as $field) {
            CategoryField::updateOrCreate(
                [
                    'category_slug' => $field['category_slug'],
                    'field_name' => $field['field_name'],
                ],
                [
                    'display_name' => $field['display_name'],
                    'type' => $field['type'] ?? 'string',
                    'options' => $field['options'] ?? [],
                    'required' => $field['required'] ?? true,
                    'filterable' => $field['filterable'] ?? true,
                    'is_active' => true,
                    'sort_order' => $field['sort_order'] ?? 999,
                ]
            );
        }
    }
}
