@extends('layout')

@section('content')
    <h2>{{ $movie->title }}</h2>

    <video id="my-video" class="video-js" preload="auto" width="640" height="264" controls>
        <source src="{{ asset($movie->url) }}" type="application/vnd.apple.mpegurl" />
        <p class="vjs-no-js">
            Para ver este video, habilita JavaScript o usa un navegador compatible con HTML5.
            <a href="https://videojs.com/html5-video-support/" target="_blank">Más info</a>
        </p>
    </video>
    <br>
    <a href="{{ route('movies') }}">Volver al contenido</a>
@endsection