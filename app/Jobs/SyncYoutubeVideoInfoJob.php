<?php

namespace App\Jobs;

use App\Services\VideoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        VideoService::sync($this->videoId);
    }
}
