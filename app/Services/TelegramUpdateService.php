<?php

namespace App\Services;

use App\Models\TelegramUpdate;
use Illuminate\Support\Facades\Http;

class TelegramUpdateService
{
    public static function createTelegramUpdate($status, $response, $offset)
    {
        return TelegramUpdate::create([
            'status' => $status,
            'response' => $response,
            'offset' => $offset,
        ]);
    }
}
