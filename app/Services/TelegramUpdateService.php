<?php

namespace App\Services;

use App\Jobs\SendTelegramMessageJob;
use App\Jobs\SendTelegramVideoJob;
use App\Jobs\SyncYoutubeVideoInfoJob;
use App\Models\TelegramUpdate;
use App\Services\TelegramApiService;
use App\Services\YoutubeUrlService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;

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

    public static function getTelegramUpdates()
    {
        $maxId = TelegramUpdate::max('id') + 0;
        $response = TelegramApiService::getUpdates($maxId + 1);
        $results = Arr::get($response->json(), 'result', []);

        foreach ($results as $result) {

            $id = $result['update_id'];
            $message = ($result['message'] ?? $result['edited_message']);

            if (
                isset($message['from']['is_bot']) and
                $message['from']['is_bot']
            ) {
                continue;
            }

            $videoId = YoutubeUrlService::parse($message['text']);

            $telegramUpdate = TelegramUpdateService::firstOrCreate(
                $id,
                $message,
                $videoId
            );

            if (!$telegramUpdate->wasRecentlyCreated) {
                continue;
            }

            if ($videoId) {
                Bus::chain([
                    new SyncYoutubeVideoInfoJob($videoId),
                    new SendTelegramVideoJob($message['chat']['id'], $videoId, $message['message_id']),
                ])->dispatch();
            } else {
                dispatch(new SendTelegramMessageJob(
                    $message['chat']['id'],
                    __('validation.regex', ['attribute' => __('validation.attributes.v')]),
                    $message['message_id']
                ));
            }
        }
    }
}
