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
        $newOffset = TelegramUpdate::max('offset') + 0;
        $response = TelegramApiService::getUpdates($newOffset + 1);
        $results = Arr::get($response->json(), 'result', []);

        foreach ($results as $result) {
            $newOffset = max($result['update_id'], $newOffset);

            $messageId = $result['message']['message_id'];
            $chatId = $result['message']['chat']['id'];
            $text = $result['message']['text'];

            $videoId = YoutubeUrlService::parse($text);
            if ($videoId) {
                Bus::chain([
                    new SyncYoutubeVideoInfoJob($videoId),
                    new SendTelegramVideoJob($result['message']),
                ])->dispatch();
            } else {
                dispatch(new SendTelegramMessageJob(
                    $chatId,
                    __('validation.regex', [
                        'attribute' => __('validation.attributes.v'),
                    ]),
                    $messageId
                ));
            }
        }

        TelegramUpdateService::create(
            $response->status(),
            $response->body(),
            $newOffset
        );
    }
}
