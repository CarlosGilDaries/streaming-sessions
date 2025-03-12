@extends('layout')

@section('content')
    <h2>Gestiona tus dispositivos</h2>
    <p>El límite de dispositivos de tu cuenta ha sido alcanzado. Para poder iniciar sesión en un nuevo dispositivo, elimina uno de los dispositivos registrados</p>
    <ul>
        @foreach($devices as $device)
            <li>
                {{ $device->device_name }}
                <form action="{{ route('devices.destroy', $device->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar este dispositivo?')">Eliminar</button>
                </form>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('logout') }}">Cancelar</a>
@endsection
