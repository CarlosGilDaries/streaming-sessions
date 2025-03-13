@if(Auth::check())
    <a href="{{ route('principal') }}"><h1>Plataforma de Streaming</h1></a>
    <a href="{{ route('login') }}">{{ Auth::user()->name }}</a><br><br>
@else
    <a href="{{ route('welcome') }}"><h1>Plataforma de Streaming</h1></a>
    <a href="{{ route('login') }}">Inicio de sesión</a><br><br>
@endif
