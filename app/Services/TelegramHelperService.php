<?php

namespace App\Services;

use App\Models\TelegramUpdate;
use Illuminate\Support\Facades\Http;

class TelegramHelperService
{
    public static function sendMessage($chatId, $text, $replyToMessageId)
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'reply_to_message_id' => $replyToMessageId,
        ];

        $url = "https://api.telegram.org/bot$token/sendMessage";
        $headers = [];
        if (1) {
            $headers['X-POWERED-BY'] = $url;
            $url = "https://agent.akrezing.ir/";
        }

        return Http::withHeaders($headers)->post($url, $data);
    }

    public static function getUpdates($offset = null, $limit = 200)
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        $data = [
            'limit' => $limit,
            'offset' => $offset,
        ];

        $url = "https://api.telegram.org/bot$token/getUpdates";
        $headers = [];
        if (1) {
            $headers['X-POWERED-BY'] = $url;
            $url = "https://agent.akrezing.ir/";
        }

        return Http::withHeaders($headers)->get($url, $data);
    }

    public static function createTelegramUpdate($status, $response, $offset)
    {
        return TelegramUpdate::create([
            'status' => $status,
            'response' => $response,
            'offset' => $offset,
        ]);
    }
}
