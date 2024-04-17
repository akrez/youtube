<?php

namespace App\Services;

use App\Models\TelegramUpdate;

class TelegramUpdateService
{
    public static function firstOrCreate($id, $message, $videoId)
    {
        return TelegramUpdate::firstOrCreate(
            ['id' => $id],
            [
                'message' => json_encode($message),
                'video_id' => $videoId,
            ],
        );
    }
}
