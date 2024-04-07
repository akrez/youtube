<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class YoutubeHelperService
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

    public static function fetchInfo($videoId): array
    {
        $url = 'https://www.youtube.com/youtubei/v1/player';

        $headers = [
            'Accept-Encoding' => 'gzip, deflate',
        ];

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

    public static function getMimeExtention($mimeType)
    {
        $mime = explode(';', $mimeType)[0];

        $mimes = [
            'audio/ac3' => 'ac3',
            'audio/aiff' => 'aif',
            'audio/midi' => 'mid',
            'audio/mp3' => 'mp3',
            'audio/mp4' => 'm4a',
            'audio/mpeg' => 'mp3',
            'audio/mpeg3' => 'mp3',
            'audio/mpg' => 'mp3',
            'audio/ogg' => 'ogg',
            'audio/wav' => 'wav',
            'audio/wave' => 'wav',
            'audio/x-acc' => 'aac',
            'audio/x-aiff' => 'aif',
            'audio/x-au' => 'au',
            'audio/x-flac' => 'flac',
            'audio/x-m4a' => 'm4a',
            'audio/x-ms-wma' => 'wma',
            'audio/x-pn-realaudio' => 'ram',
            'audio/x-pn-realaudio-plugin' => 'rpm',
            'audio/x-realaudio' => 'ra',
            'audio/x-wav' => 'wav',
            'video/3gp' => '3gp',
            'video/3gpp' => '3gp',
            'video/avi' => 'avi',
            'video/mj2' => 'jp2',
            'video/mp4' => 'mp4',
            'video/mpeg' => 'mpeg',
            'video/msvideo' => 'avi',
            'video/ogg' => 'ogg',
            'video/quicktime' => 'mov',
            'video/vnd.rn-realvideo' => 'rv',
            'video/webm' => 'webm',
            'video/x-f4v' => 'f4v',
            'video/x-flv' => 'flv',
            'video/x-ms-asf' => 'wmv',
            'video/x-ms-wmv' => 'wmv',
            'video/x-msvideo' => 'avi',
            'video/x-sgi-movie' => 'movie',
            'video/3gpp2' => '3g2',
        ];

        return isset($mimes[$mime]) ? $mimes[$mime] : null;
    }

    public static function encodeLink($url, $title, $ext, $disposition)
    {
        return [
            'payload' => encrypt([
                'url' => $url,
                'title' => $title,
                'ext' => $ext,
                'disposition' => $disposition,
            ]),
        ];
    }

    public static function dencodeLink($payload)
    {
        return decrypt($payload);
    }

    public static function formatToHumanableSize($size, $unit = "")
    {
        if ((!$unit && $size >= 1 << 30) || $unit == "GB")
            return number_format($size / (1 << 30), 2) . " GB";
        if ((!$unit && $size >= 1 << 20) || $unit == "MB")
            return number_format($size / (1 << 20), 2) . " MB";
        if ((!$unit && $size >= 1 << 10) || $unit == "KB")
            return number_format($size / (1 << 10), 2) . " KB";
        return number_format($size) . " Bytes";
    }
}
