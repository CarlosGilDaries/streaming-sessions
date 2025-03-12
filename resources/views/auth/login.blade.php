@extends('layout')

@section('content')
    <h1>Login de usuarios</h1>
    <form action="{{ route('login') }}" method="post">
        @csrf
        <label for="email">Email: </label>
        <input type="text" id="email" name="email"><br><br>

        <label for="password">Contraseña: </label>
        <input type="password" id="password" name="password"><br>
        @error('password') <p style="color:red">{{ $message }}</p>@enderror<br>

        <label for="remember">Recuérdame</label>
        <input type="checkbox" name="remember" id="remember"><br><br>

        <input type="submit" value="Iniciar Sesión">
    </form>
@endsection