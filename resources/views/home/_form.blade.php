@if ($errors->any())
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                @endforeach
            </div>
        </div>
    </div>
@endif
<div class="row mt-3">
    <div class="col-12">
        <form enctype="multipart/form-data" method="GET">
            <div class="input-group">
                <span class="input-group-text">@lang('validation.attributes.v')</span>
                <input name="v" class="form-control" dir="ltr" required="required"
                    value="{{ @old('v', isset($video) ? $video->id : '') }}">
                <button class="btn btn-success btn-lg" type="submit">@lang('View')</button>
            </div>
        </form>
    </div>
</div>
