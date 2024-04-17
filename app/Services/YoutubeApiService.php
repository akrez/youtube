<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class YoutubeApiService
{
    public static function stream($url, $name, $range, $isInline, $bufferLength = 262144): StreamedResponse
    {
        $guzzleHeaders = [
            'User-Agent' => fake()->userAgent(),
        ];
        if ($range) {
            $guzzleHeaders['Range'] = $range;
        }

        $curlConfigs = [
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_RETURNTRANSFER => 0,
            CURLOPT_HEADER => 0,
        ];

        $guzzleClient = new Client([
            'curl' => $curlConfigs,
            'stream' => true,
        ]);
        $guzzleRequest = new Request('GET', $url, $guzzleHeaders);

        try {
            $guzzleResponse = $guzzleClient->send($guzzleRequest);
        } catch (ClientException $e) {
            $guzzleResponse = $e->getResponse();
        }

        $headers = [];
        foreach (['content-type', 'content-length', 'accept-ranges', 'content-range'] as $allowedHeader) {
            if ($guzzleResponse->getHeader($allowedHeader)) {
                $headers[$allowedHeader] = $guzzleResponse->getHeader($allowedHeader);
            }
        }

        return response()
            ->streamDownload(
                function () use ($guzzleResponse, $bufferLength) {
                    $body = $guzzleResponse->getBody();
                    while (!$body->eof()) {
                        echo $body->read($bufferLength);
                        flush();
                    }
                },
                $name,
                $headers,
                ($isInline ? 'inline' : 'attachment')
            )
            ->setStatusCode($guzzleResponse->getStatusCode())
            ->setProtocolVersion($guzzleResponse->getProtocolVersion());
    }

    public static function getInfo($videoId): array
    {
        $headers = [
            'Accept-Encoding' => 'gzip, deflate',
        ];
        $url = 'https://www.youtube.com/youtubei/v1/player';
        if (env('X_POWERED_BY')) {
            $headers['X-POWERED-BY'] = $url;
            $url = env('X_POWERED_BY');
        }

        $jsonData = [
            "context" => [
                "client" => [
                    "hl" => "en",
                    "clientName" => "WEB",
                    "clientVersion" => "2.20210721.00.00",
                    "clientFormFactor" => "UNKNOWN_FORM_FACTOR",
                    "clientScreen" => "WATCH",
                    "mainAppWebInfo" => [
                        "graftUrl" => "/watch?v=" . $videoId,
                    ]
                ],
                "user" => [
                    "lockedSafetyMode" => false
                ],
                "request" => [
                    "useSsl" => true,
                    "internalExperimentFlags" => [],
                    "consistencyTokenJars" => []
                ]
            ],
            "videoId" => $videoId,
            "playbackContext" => [
                "contentPlaybackContext" => [
                    "vis" => 0,
                    "splay" => false,
                    "autoCaptionsDefaultOn" => false,
                    "autonavState" => "STATE_NONE",
                    "html5Preference" => "HTML5_PREF_WANTS",
                    "lactMilliseconds" => "-1"
                ]
            ],
            "racyCheckOk" => false,
            "contentCheckOk" => false
        ];

        try {
            $result = Http::withHeaders($headers)
                ->asForm()
                ->withBody(json_encode($jsonData))
                ->post($url)
                ->json();
        } catch (\Exception $e) {
            $result = [];
        }

        if (Arr::get($result, 'videoDetails.videoId')) {
            return $result;
        }
        return [];
    }
}
