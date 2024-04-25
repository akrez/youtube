@extends('layouts.main')

@section('content')
    @include('home._form', ['video' => $video])
    @includeWhen($video, 'home._video', ['video' => $video])
@endsection
