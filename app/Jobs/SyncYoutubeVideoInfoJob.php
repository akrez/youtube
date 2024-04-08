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

class SyncYoutubeVideoInfoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $videoId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
    }
}
