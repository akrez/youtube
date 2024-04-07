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
        $offset = TelegramUpdate::max('new_offset') + 0;
        $response = TelegramHelperService::getUpdates($offset + 1);
        $results = Arr::get($response->json(), 'result', []);

        $newOffset = $offset;
        foreach ($results as $result) {
            $newOffset = max($result['update_id'], $newOffset);
            SendTelegramMessageJob::dispatch($result['message']);
        }

        TelegramHelperService::createTelegramUpdate(
            $response->status(),
            $response->body(),
            $offset,
            $newOffset
        );
    }
}
