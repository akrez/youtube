<?php

namespace App\Jobs;

use App\Models\TelegramUpdate;
use App\Services\TelegramHelperService;
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
        $response = TelegramHelperService::getUpdates($newOffset + 1);
        $results = Arr::get($response->json(), 'result', []);

        foreach ($results as $result) {
            $newOffset = max($result['update_id'], $newOffset);
            Bus::chain([
                // new SyncYoutubeVideoInfoJob($result['message']),
                new SendTelegramMessageJob($result['message']),
            ])->dispatch();
        }

        TelegramHelperService::createTelegramUpdate(
            $response->status(),
            $response->body(),
            $newOffset
        );
    }
}
