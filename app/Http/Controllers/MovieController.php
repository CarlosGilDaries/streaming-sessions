<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();

        return view('movies.index', compact('movies'));
    }

    public function show(string $id)
    {
        $movie = Movie::where('id', $id)->firstOrFail();
        
        return view('movies.show', compact('movie'));
    }
}
