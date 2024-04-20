<?php

namespace App\Jobs;

use App\Models\TelegramUpdate;
use App\Services\TelegramApiService;
use App\Services\TelegramUpdateService;
use App\Services\YoutubeUrlService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;

class GetTelegramUpdatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
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
