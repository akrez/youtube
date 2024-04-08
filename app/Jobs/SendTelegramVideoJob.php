<?php

namespace App\Jobs;

use App\Services\TelegramHelperService;
use App\Services\YoutubeUrlService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class SendTelegramVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $message)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $videoId = YoutubeUrlService::parse(Arr::get('text', $this->message));
        if (!$videoId) {
            // fail($exception = null);
        }
    }
}
