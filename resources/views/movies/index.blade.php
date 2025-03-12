@extends('layout')

@section('content')
    <h1>LISTADO DE PELÍCULAS</h1>
    @foreach ($movies as $movie)
        <p>Título: <a href="{{ route('movies.show', $movie->id) }}">{{ $movie->title }}</a></p>
    @endforeach
@endsection