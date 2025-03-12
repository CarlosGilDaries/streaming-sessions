@extends('layout')

@section('content')

    <h2>Nuevo Dispositivo Detectado</h2>
    <p>Se ha detectado un nuevo dispositivo. Por favor, introduce un nombre para identificarlo.</p>

    <form method="POST" action="{{ route('device.name.store') }}">
        @csrf
        <input type="hidden" name="device_id" value="{{ $device_id }}">

        <label for="device_name">Nombre del Dispositivo</label>
        <input type="text" id="device_name" name="device_name" required><br><br>

        <button type="submit">Guardar</button>
    </form>

@endsection
