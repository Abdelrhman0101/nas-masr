<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Make;
use App\Models\CarModel;
use Illuminate\Http\Request;

class MakeController extends Controller
{
    public function index()
    {
        $items = Make::with('models')->orderBy('name')->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:191','unique:makes,name'],
            'models' => ['nullable','array'],
            'models.*' => ['string','max:191'],
        ]);

        $make = Make::create(['name' => $data['name']]);

        $bulk = [];
        foreach (($data['models'] ?? []) as $modelName) {
            $bulk[] = [
                'name' => $modelName,
                'make_id' => $make->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        if ($bulk) {
            CarModel::insert($bulk);
        }

        return response()->json($make->load('models'), 201);
    }

    public function update(Request $request, Make $make)
    {
        $data = $request->validate([
            'name' => ['sometimes','string','max:191','unique:makes,name,' . $make->id],
        ]);

        if (array_key_exists('name', $data)) {
            $make->update(['name' => $data['name']]);
        }

        return response()->json($make->load('models'));
    }

    public function destroy(Make $make)
    {
        $make->delete();
        return response()->json(null, 204);
    }

    public function models(Make $make)
    {
        return response()->json($make->models()->orderBy('name')->get());
    }

    public function addModel(Request $request, Make $make)
    {
        $data = $request->validate([
            'name' => ['required','string','max:191','unique:models,name,NULL,id,make_id,' . $make->id],
        ]);

        $model = CarModel::create([
            'name' => $data['name'],
            'make_id' => $make->id,
        ]);

        return response()->json($model, 201);
    }

    public function updateModel(Request $request, CarModel $model)
    {
        $data = $request->validate([
            'name' => ['sometimes','string','max:191','unique:models,name,' . $model->id . ',id,make_id,' . $model->make_id],
            'make_id' => ['sometimes','integer','exists:makes,id'],
        ]);

        $model->update($data);
        return response()->json($model);
    }

    public function deleteModel(CarModel $model)
    {
        $model->delete();
        return response()->json(null, 204);
    }
}