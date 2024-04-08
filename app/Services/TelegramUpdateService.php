<?php

namespace App\Services;

use App\Models\TelegramUpdate;

class TelegramUpdateService
{
    public static function create($status, $response, $offset)
    {
        return TelegramUpdate::create([
            'status' => $status,
            'response' => $response,
            'offset' => $offset,
        ]);
    }
}
