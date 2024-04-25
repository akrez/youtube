<?php

namespace App\Http\Controllers;

use App\Http\Requests\HomeIndexRequest;
use App\Services\VideoService;
use App\Services\YoutubeApiService;

class HomeController extends Controller
{
    public function index(HomeIndexRequest $request)
    {
        $videoId = $request->validated('v');
        if (!$videoId) {
            return view('home.index');
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
        $params = VideoService::dencodeLink(request('payload'));
        return YoutubeApiService::stream(
            $params['url'],
            $params['title'] . '.' . $params['ext'],
            request()->header('Range'),
            boolval('inline' == $params['disposition'])
        );
    }
}
