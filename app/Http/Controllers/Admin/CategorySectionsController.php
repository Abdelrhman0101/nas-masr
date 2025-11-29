<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryMainSection;
use App\Models\CategorySubSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategorySectionsController extends Controller
{

    public function index(Request $request)
    {
        $slug = $request->query('category_slug');

        if (!$slug) {
            return response()->json([
                'message' => 'يجب تحديد القسم بواسطة باراميتر category_slug.',
            ], 422);
        }

        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return response()->json([
                'message' => 'القسم غير موجود.',
            ], 404);
        }

        $mainSections = CategoryMainSection::with([
            'subSections' => function ($q) {
                $q->where('is_active', true)
                    ->orderBy('sort_order');
            }
        ])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'category' => [
                'id' => $category->id,
                'slug' => $category->slug,
                'name' => $category->name,
            ],
            'main_sections' => $mainSections,
        ]);
    }

    /**
     * POST /api/admin/category-sections/{category_slug}
     *
     * body:
     * {
     *   "main_sections": [
     *     {
     *       "id": 1,                    // اختياري (للتعديل)
     *       "name": "ملابس رجالي كاجوال",
     *       "sort_order": 1,            // اختياري
     *       "is_active": true,          // اختياري
     *       "sub_sections": [
     *         {
     *           "id": 10,               // اختياري (للتعديل)
     *           "name": "تيشيرت",
     *           "sort_order": 1,        // اختياري
     *           "is_active": true       // اختياري
     *         }
     *       ]
     *     }
     *   ]
     * }
     */
    public function store(Request $request, string $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();

        $data = $request->validate([
            'main_sections' => ['required', 'array'],

            'main_sections.*.id' => ['nullable', 'integer', 'exists:category_main_sections,id'],
            'main_sections.*.name' => ['required', 'string', 'max:191'],
            'main_sections.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'main_sections.*.is_active' => ['nullable', 'boolean'],

            'main_sections.*.sub_sections' => ['nullable', 'array'],
            'main_sections.*.sub_sections.*.id' => ['nullable', 'integer', 'exists:category_sub_sections,id'],
            'main_sections.*.sub_sections.*.name' => ['required', 'string', 'max:191'],
            'main_sections.*.sub_sections.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'main_sections.*.sub_sections.*.is_active' => ['nullable', 'boolean'],
        ], [
            'main_sections.required' => 'يجب إرسال قائمة الأقسام الرئيسية.',
            'main_sections.array' => 'الأقسام الرئيسية يجب أن تكون في شكل مصفوفة.',
        ]);

        $mainSectionsInput = $data['main_sections'];

        $mainIdsToKeep = [];
        $subIdsToKeep = [];

        DB::transaction(function () use ($category, $mainSectionsInput, &$mainIdsToKeep, &$subIdsToKeep) {

            $mainOrder = 1;

            foreach ($mainSectionsInput as $mainPayload) {

                $mainData = [
                    'category_id' => $category->id,
                    'name' => $mainPayload['name'],
                    'sort_order' => $mainPayload['sort_order'] ?? $mainOrder,
                    'is_active' => $mainPayload['is_active'] ?? true,
                ];

                // لو فيه id، نحاول نعدّل عليه
                if (!empty($mainPayload['id'])) {
                    $main = CategoryMainSection::where('id', $mainPayload['id'])
                        ->where('category_id', $category->id)
                        ->first();
                } else {
                    // لو مفيش id: نحاول نلاقيه بالاسم لنفس الكاتيجوري
                    $main = CategoryMainSection::where('category_id', $category->id)
                        ->where('name', $mainPayload['name'])
                        ->first();
                }

                if ($main) {
                    $main->update($mainData);
                } else {
                    $main = CategoryMainSection::create($mainData);
                }

                $mainIdsToKeep[] = $main->id;
                $mainOrder++;

                // sub_sections
                $subOrder = 1;
                $subSectionsInput = $mainPayload['sub_sections'] ?? [];

                foreach ($subSectionsInput as $subPayload) {
                    $subData = [
                        'category_id' => $category->id,
                        'main_section_id' => $main->id,
                        'name' => $subPayload['name'],
                        'sort_order' => $subPayload['sort_order'] ?? $subOrder,
                        'is_active' => $subPayload['is_active'] ?? true,
                    ];

                    if (!empty($subPayload['id'])) {
                        $sub = CategorySubSection::where('id', $subPayload['id'])
                            ->where('category_id', $category->id)
                            ->where('main_section_id', $main->id)
                            ->first();
                    } else {
                        $sub = CategorySubSection::where('category_id', $category->id)
                            ->where('main_section_id', $main->id)
                            ->where('name', $subPayload['name'])
                            ->first();
                    }

                    if ($sub) {
                        $sub->update($subData);
                    } else {
                        $sub = CategorySubSection::create($subData);
                    }

                    $subIdsToKeep[] = $sub->id;
                    $subOrder++;
                }
            }

            // ✅ نقدر نعمل sync: نمسح أي main/sub مش مرسل في الريكوست
            if (!empty($mainIdsToKeep)) {
                CategoryMainSection::where('category_id', $category->id)
                    ->whereNotIn('id', $mainIdsToKeep)
                    ->delete();
            } else {
                // لو مفيش ولا واحد مرسل → نمسحهم كلهم
                CategoryMainSection::where('category_id', $category->id)->delete();
            }

            if (!empty($subIdsToKeep)) {
                CategorySubSection::where('category_id', $category->id)
                    ->whereNotIn('id', $subIdsToKeep)
                    ->delete();
            } else {
                CategorySubSection::where('category_id', $category->id)->delete();
            }
        });

        // نرجّع الداتا بعد التعديل
        $mainSections = CategoryMainSection::with([
            'subSections' => function ($q) {
                $q->where('is_active', true)
                    ->orderBy('sort_order');
            }
        ])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'message' => 'تم حفظ الأقسام الرئيسية والفرعية بنجاح.',
            'category' => [
                'id' => $category->id,
                'slug' => $category->slug,
                'name' => $category->name,
            ],
            'main_sections' => $mainSections,
        ]);
    }
}
