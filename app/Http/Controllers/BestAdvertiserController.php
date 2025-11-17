<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BestAdvertiser;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Section;

class BestAdvertiserController extends Controller
{

    public function index(string $section)
    {
        $sec = Section::fromSlug($section);
        $categoryId = $sec->id();

        $featured = BestAdvertiser::active()
            ->whereRaw('JSON_CONTAINS(category_ids, ?)', [json_encode((int) $categoryId)])
            ->with('user')
            // ->orderBy('rank') // ترتيب المعلنين
            ->get();

        $userIds = $featured->pluck('user_id')->map(fn($v) => (int)$v)->all();

        if (count($userIds) === 0) {
            return response()->json(['advertisers' => []]);
        }

        $idsStr = implode(',', $userIds);

        // Get 8 listings per user ordered by rank first
        $rows = DB::select("
        SELECT id, user_id
        FROM (
            SELECT l.*,
                ROW_NUMBER() OVER (
                    PARTITION BY user_id
                    ORDER BY l.rank DESC, l.published_at DESC, l.created_at DESC
                ) rn
            FROM listings l
            WHERE l.category_id = ?
                AND l.status = 'Valid'
                AND l.user_id IN ($idsStr)
        ) t
        WHERE rn <= 8
    ", [(int)$categoryId]);

        // Collect listing IDs so we can eager load them
        $listingIds = collect($rows)->pluck('id')->all();

        $listings = \App\Models\Listing::with([
            'attributes',
            'governorate',
            'city',
            'make',
            'model',
        ])->whereIn('id', $listingIds)->get()->keyBy('id');

        // Group by user
        $byUser = [];
        foreach ($rows as $row) {
            $listing = $listings[$row->id] ?? null;
            if ($listing) {
                $byUser[$row->user_id][] = new \App\Http\Resources\ListingResource($listing);
            }
        }

        // Build output
        $out = $featured->map(function (BestAdvertiser $ba) use ($byUser) {
            $u = $ba->user;

            return [
                'id' => $ba->id,
                'user' => [
                    'id' => $u->id,
                    'name' => $u->name,
                    'phone' => $u->phone,
                    'user_code' => $u->referral_code ?: (string)$u->id,
                    'role' => $u->role ?? 'user',
                ],
                'listings' => $byUser[$ba->user_id] ?? [],
            ];
        })->values();

        return response()->json(['advertisers' => $out]);
    }


    //--------------------------------------------Admin Endpoints

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'category_ids' => ['required', 'array', 'min:1'],
            'category_ids.*' => ['integer'],
            'max_listings' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        // Check user active
        $user = User::find($data['user_id']);
        if (!$user || $user->status !== 'active') {
            return response()->json(['message' => 'User must be active'], 422);
        }

        // Validate categories exist
        $existingCategoryIds = Category::whereIn('id', $data['category_ids'])->pluck('id')->all();
        $invalidIds = array_diff($data['category_ids'], $existingCategoryIds);

        if (!empty($invalidIds)) {
            return response()->json([
                'message' => 'Some categories do not exist',
                'invalid_ids' => $invalidIds
            ], 422);
        }

        // Check if advertiser exists
        $ba = BestAdvertiser::where('user_id', $data['user_id'])->first();

        if ($ba) {
            $ba->update($data);
            $message = 'Best advertiser updated';
        } else {
            $ba = BestAdvertiser::create($data);
            $message = 'Best advertiser created';
        }

        // Fetch the categories for response
        $categories = Category::whereIn('id', $ba->category_ids)->get(['id', 'name', 'slug']);

        return response()->json([
            'message' => $message,
            'data' => [
                'best_advertiser' => $ba,
                'categories' => $categories,
            ]
        ], $ba->wasRecentlyCreated ? 201 : 200);
    }



    // ADMIN: تعطيل مستخدم مميز
    public function disable(BestAdvertiser $bestAdvertiser)
    {
        $bestAdvertiser->update(['is_active' => false]);
        return response()->json(['message' => 'Best advertiser disabled']);
    }

    // ADMIN: حذف مستخدم مميز
    // public function destroy(BestAdvertiser $bestAdvertiser)
    // {
    //     $bestAdvertiser->delete();
    //     return response()->json(['message' => 'Best advertiser deleted']);
    // }
}
