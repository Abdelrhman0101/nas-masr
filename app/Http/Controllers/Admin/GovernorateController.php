<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use App\Models\City;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    public function index()
    {
        $items = Governorate::with('cities')->orderBy('name')->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:191','unique:governorates,name'],
            'cities' => ['nullable','array'],
            'cities.*' => ['string','max:191'],
        ]);

        $gov = Governorate::create(['name' => $data['name']]);

        $bulk = [];
        foreach (($data['cities'] ?? []) as $cityName) {
            $bulk[] = [
                'name' => $cityName,
                'governorate_id' => $gov->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        if ($bulk) {
            City::insert($bulk);
        }

        return response()->json($gov->load('cities'), 201);
    }

    public function update(Request $request, Governorate $governorate)
    {
        $data = $request->validate([
            'name' => ['sometimes','string','max:191','unique:governorates,name,' . $governorate->id],
        ]);

        if (array_key_exists('name', $data)) {
            $governorate->update(['name' => $data['name']]);
        }

        return response()->json($governorate->load('cities'));
    }

    public function destroy(Governorate $governorate)
    {
        $governorate->delete();
        return response()->json(null, 204);
    }

    public function cities(Governorate $governorate)
    {
        return response()->json($governorate->cities()->orderBy('name')->get());
    }

    public function addCity(Request $request, Governorate $governorate)
    {
        $data = $request->validate([
            'name' => ['required','string','max:191','unique:cities,name,NULL,id,governorate_id,' . $governorate->id],
        ]);

        $city = City::create([
            'name' => $data['name'],
            'governorate_id' => $governorate->id,
        ]);

        return response()->json($city, 201);
    }

    public function updateCity(Request $request, City $city)
    {
        $data = $request->validate([
            'name' => ['sometimes','string','max:191','unique:cities,name,' . $city->id . ',id,governorate_id,' . $city->governorate_id],
            'governorate_id' => ['sometimes','integer','exists:governorates,id'],
        ]);

        $city->update($data);
        return response()->json($city);
    }

    public function deleteCity(City $city)
    {
        $city->delete();
        return response()->json(null, 204);
    }
}