<?php

namespace App\Jobs;

use App\Services\TelegramHelperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class SendTelegramMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $chatId, public $text, public $replyToMessageId = null)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        TelegramHelperService::sendMessage(
            $this->chatId,
            $this->text,
            $this->replyToMessageId
        );
    }
}
