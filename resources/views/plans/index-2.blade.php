@extends('layout')

@section('content')
    <h1>CAMBIO DE PLAN</h1>
    @if (Auth::user()->plan == 'sencillo')
        <h2>Sencillo</h2>
        <p>{{ Auth::user()->name }} tiene este plan</p>
        <p>Puedes iniciar sesión en 2 dispositivos</p>
        <p>No puedes reproducir el contenido en más de un dispositivo de forma simultánea</p><br><br>

        <h2>Premium</h2>
        <p>Puedes iniciar sesión en 4 dispositivos</p>
        <p>Puedes reproducir el contenido en 2 dispositivos de forma simultánea</p>
        <form action="{{ route('cambiar.plan') }}" method="POST" id="cambiarPlanForm">
            @csrf
            <input type="hidden" name="plan" value="premium">
            <button type="submit">Cambiar a Premium</button>
        </form>
    @else
        <h2>Sencillo</h2>
        <p>Puedes iniciar sesión en 2 dispositivos</p>
        <p>No puedes reproducir el contenido en más de un dispositivo de forma simultánea</p>
        <form action="{{ route('cambiar.plan') }}" method="POST" id="cambiarPlanForm">
            @csrf
            <input type="hidden" name="plan" value="sencillo">
            <button type="submit">Cambiar a Sencillo</button>
        </form><br><br>

        <h2>Premium</h2></a>
        <p>{{ Auth::user()->name }} tiene este plan</p>
        <p>Puedes iniciar sesión en 4 dispositivos</p>
        <p>Puedes reproducir el contenido en 2 dispositivos de forma simultánea</p>
    @endif
@endsection