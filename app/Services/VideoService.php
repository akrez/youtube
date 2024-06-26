<?php

namespace App\Services;

use App\Facades\Response;
use App\Models\Videos;
use App\Services\YoutubeApiService;
use App\Services\YoutubeUrlService;
use App\Support\Encryption;
use Illuminate\Support\Arr;

class VideoService
{
    public static function response($status, $message, $videoId, ?Videos $video)
    {
        return Response::status($status)->message($message)->data([
            'video_id' => $videoId,
            'video' => ($status == 200 ? $video : null),
        ]);
    }

    public static function sync(string $videoId)
    {
        $videoIdParsed = YoutubeUrlService::parse($videoId);
        if (!$videoIdParsed) {
            return VideoService::response(
                422,
                __('validation.regex', ['attribute' => __('validation.attributes.v')]),
                $videoId,
                null
            );
        }

        $video = Videos::where('id', $videoIdParsed)->filterRecently()->first();
        if ($video) {
            return VideoService::response(
                $video->status,
                'OK',
                $videoIdParsed,
                $video
            );
        }

        $youtubeResponse = YoutubeApiService::getInfo($videoIdParsed);
        $video = Videos::updateOrCreate(['id' => $videoIdParsed], [
            'status' => ($youtubeResponse ? 200 : 404),
            'title' => Arr::get($youtubeResponse, 'videoDetails.title'),
            'formats' => Arr::get($youtubeResponse, 'streamingData.formats', []),
            'adaptive_formats' => Arr::get($youtubeResponse, 'streamingData.adaptiveFormats', []),
            'synced_at' => now(),
        ]);
        return VideoService::response(
            $video->status,
            $youtubeResponse ? 'OK' : __('Not Found'),
            $videoIdParsed,
            $video
        );
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
        $encryption = new Encryption();

        return [
            'url' => $encryption->encrypt($url),
            'title' => $encryption->encrypt($title),
            'ext' => $encryption->encrypt($ext),
            'disposition' => $encryption->encrypt($disposition),
        ];
    }

    public static function dencodeLink($url, $title, $ext, $disposition)
    {
        $encryption = new Encryption();

        return [
            'url' => $encryption->decrypt($url),
            'title' => $encryption->decrypt($title),
            'ext' => $encryption->decrypt($ext),
            'disposition' => $encryption->decrypt($disposition),
        ];
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
