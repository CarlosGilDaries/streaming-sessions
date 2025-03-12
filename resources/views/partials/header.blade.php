<a href="{{ route('principal') }}"><h1>Plataforma de Streaming</h1></a>
<a href="{{ route('login') }}">
    @if(Auth::check())
        {{ Auth::user()->name }}
    @else
        Inicio de sesión
    @endif
</a><br><br>