<div class="row mt-3">
    <div class="col-md-12">
        <div class="alert alert-success alert-dismissible">
            {{ $video->title }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <table class="table table-striped table-hover table-bordered mb-3 text-center" dir="ltr">
            <thead class="table-dark">
                <tr>
                    <th>Mime</th>
                    <th>Video</th>
                    <th>Audio</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach (['formats', 'adaptive_formats'] as $formatKey)
                    @foreach ($video->$formatKey as $format)
                        @if (Arr::get($format, 'url'))
                            <tr>
                                <td class="align-middle">
                                    {{ App\Services\VideoService::getMimeExtention(Arr::get($format, 'mimeType')) }}
                                    @if (Arr::get($format, 'contentLength'))
                                        <br>
                                        <span class="badge rounded-pill bg-secondary">
                                            {{ App\Services\VideoService::formatToHumanableSize(Arr::get($format, 'contentLength')) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if (Arr::get($format, 'width') && Arr::get($format, 'height'))
                                        {{ Arr::get($format, 'width') . 'X' . Arr::get($format, 'height') }}
                                        <br>
                                    @endif
                                    @if (Arr::get($format, 'quality'))
                                        <span class="badge rounded-pill bg-secondary">
                                            {{ Arr::get($format, 'quality') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ Arr::get($format, 'audioSampleRate') }}</td>
                                <td class="align-middle">
                                    <a class="btn btn-primary w-100"
                                        href="{{ route('stream', [
                                            'payload' => App\Services\VideoService::encodeLink(
                                                Arr::get($format, 'url'),
                                                $video->title,
                                                App\Services\VideoService::getMimeExtention(Arr::get($format, 'mimeType')),
                                                'download',
                                            ),
                                        ]) }}">
                                        @lang('Download')
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        @if ($format = Arr::last($video->formats))
            <video class="rounded w-100" controls>
                <source
                    src="{{ route('stream', [
                        'payload' => App\Services\VideoService::encodeLink(
                            Arr::get($format, 'url'),
                            $video->title,
                            App\Services\VideoService::getMimeExtention(Arr::get($format, 'mimeType')),
                            'download',
                        ),
                    ]) }}" />
                <em>Sorry, your browser doesn't support HTML5 video.</em>
            </video>
        @endif
    </div>
</div>
