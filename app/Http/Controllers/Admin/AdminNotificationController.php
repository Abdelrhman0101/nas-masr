<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotifications;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = AdminNotifications::latest()->paginate(20);

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = AdminNotifications::findOrFail($id);
        $notification->update(['read_at' => now()]);

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function unreadCount()
    {
        $count = AdminNotifications::whereNull('read_at')->count();

        return response()->json(['count' => $count]);
    }
}
