<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class APIController extends Controller
{
    public function index()
    {
        $apiKey = config('services.omdb.api_key');
        $response = Http::get("https://www.omdbapi.com/?s=Shrek&page=1&apikey=$apiKey");

        $data = $response->json();

        if (isset($data['Search'])) {
            $movies = collect($data['Search'])->take(6)->map(function ($movie) {
                return [
                    'title' => $movie['Title'],
                    'year' => $movie['Year'],
                    'poster' => $movie['Poster'],
                ];
            });

            return Inertia::render('Api', [
                'jsonData' => $movies,
            ]);
        }

        return Inertia::render('Api', [
            'jsonData' => $movies,
        ]);
    }
}