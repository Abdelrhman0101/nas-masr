<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;

class SystemSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(SystemSetting::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'support_number' => ['nullable','string','max:255'],
            'panner_image'   => ['nullable','string','max:255'],
        ]);

        $setting = SystemSetting::create($validated);
        return response()->json($setting, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $setting = SystemSetting::findOrFail($id);
        return response()->json($setting);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'support_number' => ['nullable','string','max:255'],
            'panner_image'   => ['nullable','string','max:255'],
        ]);

        $setting = SystemSetting::findOrFail($id);
        $setting->update($validated);
        return response()->json($setting);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $setting = SystemSetting::findOrFail($id);
        $setting->delete();
        return response()->json(null, 204);
    }
}
