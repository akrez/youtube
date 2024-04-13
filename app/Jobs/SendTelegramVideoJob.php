<?php

namespace App\Jobs;

use App\Models\Videos;
use App\Services\YoutubeUrlService;
use Dotenv\Exception\ValidationException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
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
                    if (1) {
                        $headers['X-POWERED-BY'] = $url;
                        $url = "https://agent.akrezing.ir/";
                    }
                    $contentLength = Http::withHeaders($headers)->head($url)->header('Content-Length');
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

        $client = new Client();
        $multipartStream = new MultipartStream([
            [
                'name'     => 'video',
                'contents' => \GuzzleHttp\Psr7\Utils::streamFor($format['url']),
                'filename' => 'videoplayback.mp4'
            ],
            [
                'name'     => 'chat_id',
                'contents' => $this->chatId,
            ],
            [
                'name'     => 'reply_to_message_id',
                'contents' => $this->replyToMessageId,
            ],
        ]);

        $token = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot$token/sendvideo";

        $request = new Request(
            'POST',
            'https://agent.akrezing.ir/',
            [
                'X-POWERED-BY' => $url,
            ],
            $multipartStream
        );
        $client->send($request);
    }
}
