<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserClient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserClientController extends Controller
{
    /**
     * رجّع كل سجلات user_clients (ممكن تبقى للادمن مثلاً).
     */
    public function index(): JsonResponse
    {
        $data = UserClient::with('user')->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * رجّع clients الخاصين بـ user معيّن عن طريق user_id.
     */
    public function show(int $userId): JsonResponse
    {
        $userClient = UserClient::where('user_id', $userId)->first();

        if (! $userClient) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found for this user.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'user_id' => $userClient->user_id,
                'clients' => $userClient->clients ?? [],
            ],
        ]);
    }

    /**
     * إنشاء سجل جديد لمستخدم (لو ماعندوش record قبل كده).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id'     => ['required', 'integer', 'exists:users,id'],
            'clients'     => ['nullable', 'array'],
            'clients.*'   => ['integer'],
        ]);

        // لو فيه record لنفس اليوزر، نمنع التكرار
        $existing = UserClient::where('user_id', $validated['user_id'])->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Record already exists for this user. Use update instead.',
            ], 422);
        }

        $userClient = UserClient::create([
            'user_id' => $validated['user_id'],
            'clients' => $validated['clients'] ?? [],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User clients record created successfully.',
            'data'    => $userClient,
        ], 201);
    }

    /**
     * تحديث قائمة clients بالكامل ليوزر معيّن.
     */
    public function update(Request $request, int $userId): JsonResponse
    {
        $validated = $request->validate([
            'clients'   => ['required', 'array'],
            'clients.*' => ['integer'],
        ]);

        $userClient = UserClient::where('user_id', $userId)->first();

        if (! $userClient) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found for this user.',
            ], 404);
        }

        $userClient->clients = $validated['clients'];
        $userClient->save();

        return response()->json([
            'success' => true,
            'message' => 'Clients updated successfully.',
            'data'    => $userClient,
        ]);
    }

    /**
     * إضافة client جديد للّستة بدون ما نستبدلها.
     */
    public function addClient(Request $request, int $userId): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'integer'],
        ]);

        $userClient = UserClient::firstOrCreate(
            ['user_id' => $userId],
            ['clients' => []]
        );

        $clients = $userClient->clients ?? [];
        $clients[] = $validated['client_id'];

        $userClient->clients = $clients;
        $userClient->save();

        return response()->json([
            'success' => true,
            'message' => 'Client added successfully.',
            'data'    => $userClient,
        ]);
    }

    /**
     * حذف client من اللستة.
     */
    public function removeClient(Request $request, int $userId): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'integer'],
        ]);

        $userClient = UserClient::where('user_id', $userId)->first();

        if (! $userClient) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found for this user.',
            ], 404);
        }

        $clients = $userClient->clients ?? [];

        $clients = array_values(array_filter($clients, function ($id) use ($validated) {
            return (int) $id !== (int) $validated['client_id'];
        }));

        $userClient->clients = $clients;
        $userClient->save();

        return response()->json([
            'success' => true,
            'message' => 'Client removed successfully.',
            'data'    => $userClient,
        ]);
    }


    public function destroy(int $userId): JsonResponse
    {
        $userClient = UserClient::where('user_id', $userId)->first();

        if (! $userClient) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found for this user.',
            ], 404);
        }

        $userClient->delete();

        return response()->json([
            'success' => true,
            'message' => 'User clients record deleted successfully.',
        ]);
    }
}
