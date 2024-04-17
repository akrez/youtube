<?php

namespace App\Jobs;

use App\Models\Videos;
use App\Services\TelegramApiService;
use App\Services\YoutubeUrlService;
use Dotenv\Exception\ValidationException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SendTelegramVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $chatId, public $videoId, public $replyToMessageId = null)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $videoId = YoutubeUrlService::parse($this->videoId);
        if (!$videoId) {
            throw new ValidationException();
        }

        $video = Videos::where('status', 200)->findOrFail($videoId);

        $format = collect($video->formats)
            ->reverse()
            ->first(function ($value, $key) {
                if (!isset($value['url'])) {
                    return false;
                }

                try {
                    $headers = [];
                    $url = $value['url'];
                    if (env('X_POWERED_BY')) {
                        $headers['X-POWERED-BY'] = $url;
                        $url = env('X_POWERED_BY');
                    }
                    $contentLength = Http::withHeaders($headers)
                        ->asForm()
                        ->head($url)
                        ->header('Content-Length');
                } catch (\Throwable $th) {
                    return false;
                }

                if (empty($contentLength)) {
                    return false;
                }

                if ($contentLength > 52428800) {
                    return false;
                }

                return true;
            });
        if (!$format) {
            throw new NotFoundHttpException();
        }

        TelegramApiService::sendVideo(
            $this->chatId,
            $format['url'],
            $this->replyToMessageId,
            $video->title
        );
    }
}
