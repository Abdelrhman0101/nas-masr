<?php

namespace App\Services;

use App\Models\AdminNotifications;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminNotificationService
{
    /**
     * Cooldown period in seconds for duplicate notifications
     */
    private const COOLDOWN_SECONDS = 120; // 2 minutes

    public function dispatch(string $title, string $body, ?string $type = null, ?array $data = null, string $source = 'system'): array
    {
        // Create internal notification directly without cooldown
        $notification = AdminNotifications::create([
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'data' => $data,
            'source' => $source,
        ]);

        return [
            'notification' => $notification,
            'skipped' => false,
        ];
    }
}
