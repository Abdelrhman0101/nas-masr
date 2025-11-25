<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ListingResource;
use App\Models\Favorite;
use App\Models\Listing;
use App\Support\Section;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $rows = Favorite::query()
            ->where('user_id', $user->id)
            ->with(['ad.attributes', 'ad.governorate', 'ad.city', 'ad.make', 'ad.model'])
            ->get();

        $listings = $rows->map(fn($f) => $f->ad)->filter();

        return ListingResource::collection($listings)
            ->additional(['count' => $listings->count()]);
    }

    public function toggle(Request $request)
    {
        $data = $request->validate([
            'ad_id' => ['required', 'integer', 'exists:listings,id'],
        ]);

        $user = $request->user();

        $existing = Favorite::query()
            ->where('user_id', $user->id)
            ->where('ad_id', $data['ad_id'])
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'message' => 'تم إزالة الإعلان من المفضلة',
                'favorited' => false,
            ]);
        }

        $ad = Listing::find($data['ad_id']);
        $slug = Section::fromId($ad->category_id)->slug;

        Favorite::create([
            'user_id' => $user->id,
            'ad_id' => $ad->id,
            'category_slug' => $slug,
        ]);

        return response()->json([
            'message' => 'تم إضافة الإعلان إلى المفضلة',
            'favorited' => true,
        ], 201);
    }
}

