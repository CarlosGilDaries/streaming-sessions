<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Streaming</title>
    <link href="https://vjs.zencdn.net/7.0/video-js.css" rel="stylesheet" />
</head>
<body>
    <header>
        @include('partials.header')
    </header>
    <main>
        @yield('content')
    </main>
    <footer>
        @include('partials.footer')
    </footer>
    <script src="https://vjs.zencdn.net/7.0/video.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-http-streaming/3.17.0/videojs-http-streaming.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var player = videojs('my-video');
        });
    </script>
</body>
</html>