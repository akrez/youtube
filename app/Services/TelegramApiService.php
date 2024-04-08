<?php

namespace App\Services;

use App\Models\TelegramUpdate;
use Illuminate\Support\Facades\Http;

class TelegramApiService
{
    private static function sendPost($path, $data = [], $headers = [])
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        $url = "https://api.telegram.org/bot$token/$path";
        if (1) {
            $headers['X-POWERED-BY'] = $url;
            $url = "https://agent.akrezing.ir/";
        }

        return Http::withHeaders($headers)->post($url, $data);
    }

    public static function getMe()
    {
        return static::sendPost('getMe');
    }

    public static function sendMessage($chatId, $text, $replyToMessageId = null)
    {
        return static::sendPost('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
            'reply_to_message_id' => $replyToMessageId,
        ]);
    }

    public static function getUpdates($offset = null, $limit = 200)
    {
        return static::sendPost('getUpdates', [
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }
}
