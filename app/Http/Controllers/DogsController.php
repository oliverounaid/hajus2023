<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DogsController extends Controller
{
    public function index()
    {
        $dogs = Cache::remember('dogs', 60 * 60, function () {
            $response = Http::get('https://hajusrakendused.deno.dev/api/dogs');
            return $response->json('data');
        });

        return Inertia::render('Dogs', [
            'dogs' => $dogs,
        ]);
    }
}

