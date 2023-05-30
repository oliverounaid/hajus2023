<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class WeatherController extends Controller
{
    public function index()
    {
        $url = 'https://api.openweathermap.org/data/2.5/weather?q=kuressaare&units=metric&appid='.config('services.weather.key');

        $data = $this->cacheData($url);
        return Inertia::render('Weather', [
            'data' => $data
        ]);
    }

    private function cacheData($url)
    {
        $filename = 'weather.json';
        $cacheKey = 'weather';

        if (Storage::disk('local')->exists($filename)) {
            $content = Storage::disk('local')->get($filename);
            $data = json_decode($content);
            if ($data && isset($data->expires_at) && time() < $data->expires_at) {
                // Data is still valid, return it
                return $data->data;
            }
        }

        // Data not found or expired, fetch it from API and store in file and cache
        $data = $this->fetchData($url);
        $json = json_encode([
            'data' => $data,
            'expires_at' => time() + 15
        ]);
        Storage::disk('local')->put($filename, $json);
        Cache::put($cacheKey, $data, now()->addSeconds(15));

        return $data;
    }

    private function fetchData($url)
    {
        $response = Http::get($url);
        if ($response->ok()) {
            return $response->json();
        }
        return null;
    }
}