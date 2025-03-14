@extends('layout')

@section('content')
    <h2>Se ha superado el límite de visualizaciones simultáneas</h2>

    <a href="{{ route('planes.2') }}"><h3>Cambiar plan</h3></a>

    <a href="{{ route('principal') }}">Cancelar</a>
@endsection
