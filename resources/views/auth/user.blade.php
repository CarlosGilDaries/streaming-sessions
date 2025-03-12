@extends('layout')

@section('content')
    <h1>Hola {{ Auth::user()->name }}</h1>
    <p>Nombre: {{ Auth::user()->name }}</p>
    <p>Correo: {{ Auth::user()->email }}</p>
    <p>Plan: {{ Auth::user()->plan }} --- <a href="{{ route('planes') }}">Cambiar plan</a></p>
    <a href="{{ route('logout') }}">Cerrar sesión</a>
@endsection