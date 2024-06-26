<?php

namespace App\Http\Controllers;

use App\Http\Requests\HomeIndexRequest;
use App\Models\Videos;
use App\Services\VideoService;
use App\Services\YoutubeApiService;
use Illuminate\Support\Arr;

class HomeController extends Controller
{
    public function index(HomeIndexRequest $request)
    {
        $videoId = $request->validated('v');
        if (!$videoId) {
            return view('home.index', [
                'video' => null,
            ]);
        }

        $response = VideoService::sync($videoId);
        if ($response->status != 200) {
            return redirect()->route('index')
                ->with('v', $response->data['video_id'])
                ->setStatusCode($response->status)
                ->withErrors($response->message);
        }

        return view('home.index', [
            'video' => $response->data['video'],
        ]);
    }

    public function stream()
    {
        $video = Videos::findOrFail(request('video_id'));
        $formatKey = (request('format_key') === 'formats' ? 'formats' : 'adaptive_formats');
        $formatId = intval(request('format_id'));

        throw_if(!isset($video->$formatKey[$formatId]));
        $format = $video->$formatKey[$formatId];

        return YoutubeApiService::stream(
            Arr::get($format, 'url'),
            $video->title . '.' . VideoService::getMimeExtention(Arr::get($format, 'mimeType')),
            request()->header('Range')
        );
    }
}
