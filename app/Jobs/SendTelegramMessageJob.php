<?php

namespace App\Jobs;

use App\Services\TelegramApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class SendTelegramMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $chatId, public $text, public $replyToMessageId = null)
    {
        $this->onQueue('SendTelegramMessageJob');
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [new WithoutOverlapping()];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        TelegramApiService::sendMessage(
            $this->chatId,
            $this->text,
            $this->replyToMessageId
        );
    }
}
